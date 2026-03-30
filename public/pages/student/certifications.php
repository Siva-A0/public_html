<?php
if (session_id() == '') {
    session_start();
}

require_once(__DIR__ . '/../../../config.php');
require_once(LIB_PATH . '/functions.class.php');
require_once(LIB_PATH . '/submission_helpers.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user' || !isset($_SESSION['userName'])) {
    header('Location: ' . BASE_URL . '/public/pages/Authentication/login.php');
    exit;
}

$fcObj = new DataFunctions();
$message = '';
$messageType = '';

$userData = $fcObj->userCheck(TB_USERS, $_SESSION['userName']);
if (empty($userData)) {
    header('Location: ' . BASE_URL . '/public/pages/Authentication/logout.php');
    exit;
}

$user = $userData[0];
$userFullName = trim((string)$user['firstname'] . ' ' . (string)$user['lastname']);
$studentTag = ($userFullName !== '' ? $userFullName : (string)$user['username']) . ' [' . (string)$user['admission_id'] . ']';

if (isset($_POST['submit_certification'])) {
    $issuer = trim((string)($_POST['issuing_organization'] ?? ''));
    $theme = trim((string)($_POST['certification_theme'] ?? ''));
    $title = trim((string)($_POST['certification_title'] ?? ''));

    if ($issuer === '') {
        $message = 'Issuing organization is required.';
        $messageType = 'danger';
    } elseif ($theme === '') {
        $message = 'Certification area is required.';
        $messageType = 'danger';
    } elseif ($title === '') {
        $message = 'Certification title is required.';
        $messageType = 'danger';
    } elseif (!isset($_FILES['certification_file']) || !is_uploaded_file($_FILES['certification_file']['tmp_name'])) {
        $message = 'Please choose a certificate file to upload.';
        $messageType = 'danger';
    } else {
        $originalName = (string)$_FILES['certification_file']['name'];
        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $allowedExtensions = array('pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png');

        if (!in_array($extension, $allowedExtensions, true)) {
            $message = 'Only PDF, DOC, DOCX, JPG, JPEG, and PNG files are allowed.';
            $messageType = 'danger';
        } else {
            $uploadDir = ROOT_PATH . '/public/assets/images/achievements/';
            if (!is_dir($uploadDir)) {
                @mkdir($uploadDir, 0777, true);
            }

            $safeAdmission = preg_replace('/[^a-zA-Z0-9_-]/', '', (string)$user['admission_id']);
            $fileName = 'cert_' . $safeAdmission . '_' . date('YmdHis') . '_' . mt_rand(1000, 9999) . '.' . $extension;
            $targetFile = $uploadDir . $fileName;

            if (!@move_uploaded_file($_FILES['certification_file']['tmp_name'], $targetFile)) {
                $message = 'File upload failed. Please try again.';
                $messageType = 'danger';
            } else {
                $contextTag = 'Issuer: ' . $issuer . ' | Area: ' . $theme;
                $varArray = array(
                    'typeId' => DOCUMENT,
                    'achievement_desc' => addslashes(app_build_submission_desc('student', 'certification', $user['admission_id'], $studentTag, $contextTag, $title)) . '$$' . $fileName
                );
                $saved = $fcObj->addAchievement(TB_ACHIEVEMENTS, $varArray);

                if ($saved) {
                    $message = 'Certification uploaded successfully.';
                    $messageType = 'success';
                } else {
                    @unlink($targetFile);
                    $message = $fcObj->getLastError() !== '' ? $fcObj->getLastError() : 'Unable to save certification right now. Please try again.';
                    $messageType = 'danger';
                }
            }
        }
    }
}

include_once(INCLUDES_PATH . '/header.php');
$userActivePage = 'certifications';
include_once(__DIR__ . '/layout/main_header.php');
?>
<style>
.student-page{--sp-primary:#173d69;--sp-primary-deep:#13345a;--sp-accent:#f0b323;--sp-accent-deep:#d79a12;--sp-surface:#eef4fa;--sp-card:#fff;--sp-border:#d8e3ef;--sp-text:#284767;--sp-muted:#6b819c;display:grid;gap:20px;padding-bottom:28px}.student-hero{position:relative;overflow:hidden;border:1px solid var(--sp-border);border-radius:26px;padding:28px;background:radial-gradient(circle at top right,rgba(240,179,35,.18),transparent 30%),linear-gradient(135deg,#f9fbfe 0%,var(--sp-surface) 100%);box-shadow:0 18px 36px rgba(15,23,42,.08)}.student-hero:before{content:"";position:absolute;inset:0 auto 0 0;width:7px;background:linear-gradient(180deg,var(--sp-accent),var(--sp-accent-deep))}.student-kicker,.student-tag{display:inline-flex;align-items:center;gap:8px;padding:8px 14px;border-radius:999px;background:rgba(23,61,105,.08);color:var(--sp-primary);font-size:12px;font-weight:800;letter-spacing:.08em;text-transform:uppercase}.student-kicker:before{content:"";width:8px;height:8px;border-radius:999px;background:linear-gradient(135deg,var(--sp-accent),var(--sp-accent-deep))}.student-hero h1{margin:14px 0 10px;color:var(--sp-primary-deep);font-size:clamp(28px,4vw,42px);font-weight:800;line-height:1.04;letter-spacing:-.04em}.student-hero p{margin:0;max-width:820px;color:var(--sp-muted);font-size:15px;line-height:1.7}.student-layout-grid{display:grid;grid-template-columns:minmax(0,1fr) minmax(260px,.38fr);gap:20px}.student-panel{border:1px solid var(--sp-border);border-radius:22px;background:var(--sp-card);box-shadow:0 12px 24px rgba(15,23,42,.06);padding:22px}.student-panel-header{display:flex;align-items:flex-start;justify-content:space-between;gap:14px;margin-bottom:18px}.student-panel-title{margin:0;color:var(--sp-primary-deep);font-size:22px;font-weight:800;letter-spacing:-.03em}.student-panel-subtitle{margin:6px 0 0;color:var(--sp-muted);font-size:14px}.student-alert{border-radius:16px;border:none;padding:14px 16px;font-weight:600}.student-alert.alert-success{background:#ecf7ef;color:#12653a}.student-alert.alert-danger{background:#fff0f0;color:#9a2a2a}.student-form-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px}.student-field{display:grid;gap:8px}.student-field.full{grid-column:1/-1}.student-label{color:var(--sp-primary);font-size:13px;font-weight:800;letter-spacing:.05em;text-transform:uppercase}.student-input{width:100%;border:1px solid #d5e1ee;border-radius:15px;padding:13px 15px;background:#fff;color:var(--sp-text);font-size:15px;transition:border-color .2s ease,box-shadow .2s ease}.student-input:focus{border-color:#88aacf;box-shadow:0 0 0 4px rgba(23,61,105,.08);outline:none}.student-file-picker{display:grid;grid-template-columns:auto minmax(0,1fr);gap:10px;align-items:center}.student-file-btn,.student-primary-btn,.student-link-btn{display:inline-flex;align-items:center;justify-content:center;border:none;border-radius:14px;padding:12px 18px;font-weight:800;cursor:pointer;text-decoration:none}.student-file-btn{background:#e8f0f8;color:var(--sp-primary)}.student-primary-btn,.student-link-btn{background:linear-gradient(135deg,var(--sp-primary),var(--sp-primary-deep));color:#fff;box-shadow:0 14px 24px rgba(23,61,105,.18)}.student-help{color:var(--sp-muted);font-size:13px;line-height:1.6;margin-top:8px}.student-info-list{display:grid;gap:12px}.student-info-card{border:1px solid #e2ebf5;border-radius:18px;background:linear-gradient(180deg,#fbfdff 0%,#f6f9fc 100%);padding:16px}.student-info-card h3{margin:0 0 8px;color:var(--sp-primary-deep);font-size:17px;font-weight:800}.student-info-card p{margin:0;color:var(--sp-text);font-size:14px;line-height:1.6}.student-actions{margin-top:20px;display:flex;flex-wrap:wrap;gap:12px}
@media(max-width:1199px){.student-layout-grid{grid-template-columns:1fr}}
@media(max-width:767px){.student-page{gap:16px}.student-hero,.student-panel{padding:18px;border-radius:20px}.student-form-grid,.student-file-picker{grid-template-columns:1fr}.student-panel-header{flex-direction:column;align-items:flex-start}}
</style>
<div class="student-page">
    <section class="student-hero">
        <span class="student-kicker">Certifications</span>
        <h1>Upload Certification</h1>
        <p>Store your verified certificates separately from achievements so they are easier to review and find later.</p>
    </section>

    <?php if ($message !== '') { ?>
        <div class="student-alert alert alert-<?php echo $messageType; ?>"><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php } ?>

    <section class="student-layout-grid">
        <div class="student-panel">
            <div class="student-panel-header">
                <div><h2 class="student-panel-title">Certification Submission</h2><p class="student-panel-subtitle">Upload file-based certifications with clear issuer and area details.</p></div>
                <span class="student-tag">Submit</span>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <div class="student-form-grid">
                    <div class="student-field"><label class="student-label">Issuing Organization</label><input type="text" name="issuing_organization" class="student-input" value="<?php echo isset($_POST['issuing_organization']) ? htmlspecialchars((string)$_POST['issuing_organization'], ENT_QUOTES, 'UTF-8') : ''; ?>" placeholder="Example: Coursera" required></div>
                    <div class="student-field"><label class="student-label">Certification Area</label><input type="text" name="certification_theme" class="student-input" value="<?php echo isset($_POST['certification_theme']) ? htmlspecialchars((string)$_POST['certification_theme'], ENT_QUOTES, 'UTF-8') : ''; ?>" placeholder="Example: Machine Learning" required></div>
                    <div class="student-field full"><label class="student-label">Certification Title</label><input type="text" name="certification_title" class="student-input" value="<?php echo isset($_POST['certification_title']) ? htmlspecialchars((string)$_POST['certification_title'], ENT_QUOTES, 'UTF-8') : ''; ?>" placeholder="Example: AI Foundations Certificate" required></div>
                    <div class="student-field full"><label class="student-label">Certificate File</label><div class="student-file-picker"><button type="button" class="student-file-btn" id="certification_file_btn">Choose File</button><input type="text" class="student-input" id="certification_file_name" value="No file chosen" readonly></div><input type="file" name="certification_file" id="certification_file" class="d-none" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"><div class="student-help">Allowed: PDF, DOC, DOCX, JPG, JPEG, PNG.</div></div>
                </div>
                <div class="student-actions">
                    <button type="submit" name="submit_certification" class="student-primary-btn">Submit Certification</button>
                    <a class="student-link-btn" href="<?php echo BASE_URL; ?>/public/pages/student/my_certifications.php">View My Certifications</a>
                </div>
            </form>
        </div>

        <aside class="student-panel">
            <div class="student-panel-header">
                <div><h2 class="student-panel-title">Submission Guide</h2><p class="student-panel-subtitle">A few quick reminders before you upload.</p></div>
                <span class="student-tag">Guide</span>
            </div>
            <div class="student-info-list">
                <article class="student-info-card"><h3>Use Valid Files</h3><p>Upload the actual certificate or a clear scanned copy so it is easy to verify later.</p></article>
                <article class="student-info-card"><h3>Name It Clearly</h3><p>Keep the certification title and issuing organization exact to the original document.</p></article>
                <article class="student-info-card"><h3>Separate From Achievements</h3><p>Use this page for certifications only. Awards, wins, and recognitions should stay under achievements.</p></article>
            </div>
        </aside>
    </section>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var fileInput = document.getElementById('certification_file');
    var fileButton = document.getElementById('certification_file_btn');
    var fileName = document.getElementById('certification_file_name');
    if (!fileInput || !fileButton || !fileName) {
        return;
    }
    fileButton.addEventListener('click', function () { fileInput.click(); });
    fileInput.addEventListener('change', function () {
        fileName.value = (fileInput.files && fileInput.files.length > 0) ? fileInput.files[0].name : 'No file chosen';
    });
});
</script>
<?php include_once(__DIR__ . '/layout/main_footer.php'); ?>

