<?php
if (session_id() == '') {
    session_start();
}

require_once(__DIR__ . '/../../../config.php');
require_once(LIB_PATH . '/functions.class.php');
require_once(LIB_PATH . '/submission_helpers.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'faculty' || !isset($_SESSION['facultyId'])) {
    header('Location: ' . BASE_URL . '/public/pages/Authentication/faculty_login.php');
    exit;
}

$fcObj = new DataFunctions();
$message = '';
$messageType = '';
$staffRows = $fcObj->getStaffDetailsById(TB_STAFF, (int)$_SESSION['facultyId']);
if (empty($staffRows)) {
    header('Location: ' . BASE_URL . '/public/pages/Authentication/logout.php');
    exit;
}

$faculty = $staffRows[0];
$facultyName = trim((string)($faculty['first_name'] ?? '') . ' ' . (string)($faculty['last_name'] ?? ''));
if ($facultyName === '') {
    $facultyName = trim((string)($_SESSION['facultyName'] ?? $_SESSION['facultyFirstName'] ?? 'Faculty'));
}
$facultyTag = $facultyName . ' [Faculty ID: ' . (string)$faculty['id'] . ']';

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

            $safeFacultyId = preg_replace('/[^a-zA-Z0-9_-]/', '', (string)$faculty['id']);
            $fileName = 'faccert_' . $safeFacultyId . '_' . date('YmdHis') . '_' . mt_rand(1000, 9999) . '.' . $extension;
            $targetFile = $uploadDir . $fileName;

            if (!@move_uploaded_file($_FILES['certification_file']['tmp_name'], $targetFile)) {
                $message = 'File upload failed. Please try again.';
                $messageType = 'danger';
            } else {
                $contextTag = 'Issuer: ' . $issuer . ' | Area: ' . $theme;
                $varArray = array(
                    'typeId' => DOCUMENT,
                    'achievement_desc' => addslashes(app_build_submission_desc('faculty', 'certification', $faculty['id'], $facultyTag, $contextTag, $title)) . '$$' . $fileName
                );
                $saved = $fcObj->addAchievement(TB_ACHIEVEMENTS, $varArray);

                if ($saved) {
                    $message = 'Certification uploaded successfully.';
                    $messageType = 'success';
                } else {
                    @unlink($targetFile);
                    $message = $fcObj->getLastError() !== '' ? $fcObj->getLastError() : '';
                    $messageType = 'danger';
                }
            }
        }
    }
}

$hidePublicNavbar = true;
include_once(INCLUDES_PATH . '/header.php');
$facultyActivePage = 'certifications';
include_once(__DIR__ . '/layout/main_header.php');
?>
<style>
.faculty-page{--fp-primary:#173d69;--fp-primary-deep:#13345a;--fp-accent:#f0b323;--fp-accent-deep:#d79a12;--fp-surface:#eef4fa;--fp-card:#fff;--fp-border:#d8e3ef;--fp-text:#284767;--fp-muted:#6b819c;display:grid;gap:20px;padding-bottom:28px}.faculty-hero{position:relative;overflow:hidden;border:1px solid var(--fp-border);border-radius:26px;padding:28px;background:radial-gradient(circle at top right,rgba(240,179,35,.18),transparent 30%),linear-gradient(135deg,#f9fbfe 0%,var(--fp-surface) 100%);box-shadow:0 18px 36px rgba(15,23,42,.08)}.faculty-hero:before{content:"";position:absolute;inset:0 auto 0 0;width:7px;background:linear-gradient(180deg,var(--fp-accent),var(--fp-accent-deep))}.faculty-kicker,.faculty-tag{display:inline-flex;align-items:center;gap:8px;padding:8px 14px;border-radius:999px;background:rgba(23,61,105,.08);color:var(--fp-primary);font-size:12px;font-weight:800;letter-spacing:.08em;text-transform:uppercase}.faculty-kicker:before{content:"";width:8px;height:8px;border-radius:999px;background:linear-gradient(135deg,var(--fp-accent),var(--fp-accent-deep))}.faculty-hero h1{margin:14px 0 10px;color:var(--fp-primary-deep);font-size:clamp(28px,4vw,42px);font-weight:800;line-height:1.04;letter-spacing:-.04em}.faculty-hero p{margin:0;max-width:820px;color:var(--fp-muted);font-size:15px;line-height:1.7}.faculty-layout-grid{display:grid;grid-template-columns:minmax(0,1fr) minmax(260px,.38fr);gap:20px}.faculty-panel{border:1px solid var(--fp-border);border-radius:22px;background:var(--fp-card);box-shadow:0 12px 24px rgba(15,23,42,.06);padding:22px}.faculty-panel-header{display:flex;align-items:flex-start;justify-content:space-between;gap:14px;margin-bottom:18px}.faculty-panel-title{margin:0;color:var(--fp-primary-deep);font-size:22px;font-weight:800;letter-spacing:-.03em}.faculty-panel-subtitle{margin:6px 0 0;color:var(--fp-muted);font-size:14px}.faculty-alert{border-radius:16px;border:none;padding:14px 16px;font-weight:600}.faculty-alert.alert-success{background:#ecf7ef;color:#12653a}.faculty-alert.alert-danger{background:#fff0f0;color:#9a2a2a}.faculty-form-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px}.faculty-field{display:grid;gap:8px}.faculty-field.full{grid-column:1/-1}.faculty-label{color:var(--fp-primary);font-size:13px;font-weight:800;letter-spacing:.05em;text-transform:uppercase}.faculty-input{width:100%;border:1px solid #d5e1ee;border-radius:15px;padding:13px 15px;background:#fff;color:var(--fp-text);font-size:15px;transition:border-color .2s ease,box-shadow .2s ease}.faculty-input:focus{border-color:#88aacf;box-shadow:0 0 0 4px rgba(23,61,105,.08);outline:none}.faculty-file-picker{display:grid;grid-template-columns:auto minmax(0,1fr);gap:10px;align-items:center}.faculty-file-btn,.faculty-primary-btn,.faculty-link-btn{display:inline-flex;align-items:center;justify-content:center;border:none;border-radius:14px;padding:12px 18px;font-weight:800;cursor:pointer;text-decoration:none}.faculty-file-btn{background:#e8f0f8;color:var(--fp-primary)}.faculty-primary-btn,.faculty-link-btn{background:linear-gradient(135deg,var(--fp-primary),var(--fp-primary-deep));color:#fff;box-shadow:0 14px 24px rgba(23,61,105,.18)}.faculty-help{color:var(--fp-muted);font-size:13px;line-height:1.6;margin-top:8px}.faculty-info-list{display:grid;gap:12px}.faculty-info-card{border:1px solid #e2ebf5;border-radius:18px;background:linear-gradient(180deg,#fbfdff 0%,#f6f9fc 100%);padding:16px}.faculty-info-card h3{margin:0 0 8px;color:var(--fp-primary-deep);font-size:17px;font-weight:800}.faculty-info-card p{margin:0;color:var(--fp-text);font-size:14px;line-height:1.6}.faculty-actions{margin-top:20px;display:flex;flex-wrap:wrap;gap:12px}
@media(max-width:1199px){.faculty-layout-grid{grid-template-columns:1fr}}
@media(max-width:767px){.faculty-page{gap:16px}.faculty-hero,.faculty-panel{padding:18px;border-radius:20px}.faculty-form-grid,.faculty-file-picker{grid-template-columns:1fr}.faculty-panel-header{flex-direction:column;align-items:flex-start}}
</style>
<div class="faculty-page">
    <section class="faculty-hero">
        <span class="faculty-kicker">Certifications</span>
        <h1>Upload Certification</h1>
        <!-- <p>Keep faculty certificates in a dedicated section so they stay separate from general achievements.</p> -->
    </section>

    <?php if ($message !== '') { ?>
        <div class="faculty-alert alert alert-<?php echo $messageType; ?>"><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php } ?>

    <section class="faculty-layout-grid">
        <div class="faculty-panel">
            <div class="faculty-panel-header">
                <div><h2 class="faculty-panel-title">Certification Submission</h2><p class="faculty-panel-subtitle"></p></div>
                <span class="faculty-tag">Submit</span>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <div class="faculty-form-grid">
                    <div class="faculty-field"><label class="faculty-label">Issuing Organization</label><input type="text" name="issuing_organization" class="faculty-input" value="<?php echo isset($_POST['issuing_organization']) ? htmlspecialchars((string)$_POST['issuing_organization'], ENT_QUOTES, 'UTF-8') : ''; ?>" placeholder="" ></div>
                    <div class="faculty-field"><label class="faculty-label">Certification Area</label><input type="text" name="certification_theme" class="faculty-input" value="<?php echo isset($_POST['certification_theme']) ? htmlspecialchars((string)$_POST['certification_theme'], ENT_QUOTES, 'UTF-8') : ''; ?>" placeholder="" ></div>
                    <div class="faculty-field full"><label class="faculty-label">Certification Title</label><input type="text" name="certification_title" class="faculty-input" value="<?php echo isset($_POST['certification_title']) ? htmlspecialchars((string)$_POST['certification_title'], ENT_QUOTES, 'UTF-8') : ''; ?>" placeholder="" ></div>
                    <div class="faculty-field full"><label class="faculty-label">Certificate File</label><div class="faculty-file-picker"><button type="button" class="faculty-file-btn" id="faculty_certification_file_btn">Choose File</button><input type="text" class="faculty-input" id="faculty_certification_file_name" value="" readonly></div><input type="file" name="certification_file" id="faculty_certification_file" class="d-none" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"><div class="faculty-help">Allowed: PDF, DOC, DOCX, JPG, JPEG, PNG.</div></div>
                </div>
                <div class="faculty-actions">
                    <button type="submit" name="submit_certification" class="faculty-primary-btn">Submit Certification</button>
                    <a class="faculty-link-btn" href="<?php echo BASE_URL; ?>/public/pages/faculty/my_certifications.php">View My Certifications</a>
                </div>
            </form>
        </div>

        <aside class="faculty-panel">
            <div class="faculty-panel-header">
                <div><h2 class="faculty-panel-title">Submission Guide</h2><p class="faculty-panel-subtitle"></p></div>
                <span class="faculty-tag">Guide</span>
            </div>
            <div class="faculty-info-list">
                <article class="faculty-info-card"><h3>Upload the Real Proof</h3><p></p></article>
                <article class="faculty-info-card"><h3>Keep Names Accurate</h3><p></p></article>
                <article class="faculty-info-card"><h3>Use the Right Section</h3><p></p></article>
            </div>
        </aside>
    </section>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var fileInput = document.getElementById('faculty_certification_file');
    var fileButton = document.getElementById('faculty_certification_file_btn');
    var fileName = document.getElementById('faculty_certification_file_name');
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
