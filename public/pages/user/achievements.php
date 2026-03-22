<?php
if (session_id() == '') {
    session_start();
}

require_once(__DIR__ . '/../../../config.php');
require_once(LIB_PATH . '/functions.class.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user' || !isset($_SESSION['userName'])) {
    header('Location: ' . BASE_URL . '/public/pages/Authentication/login.php');
    exit;
}

$fcObj = new DataFunctions();
$achievementMessage = '';
$achievementMessageType = '';

$userData = $fcObj->userCheck(TB_USERS, $_SESSION['userName']);
if (empty($userData)) {
    header('Location: ' . BASE_URL . '/public/pages/Authentication/logout.php');
    exit;
}

$user = $userData[0];
$userFullName = trim($user['firstname'] . ' ' . $user['lastname']);
$studentTag = ($userFullName !== '' ? $userFullName : $user['username']) . ' [' . $user['admission_id'] . ']';

if (isset($_POST['submit_achievement'])) {
    $typeId = isset($_POST['achievement_type']) ? (int)$_POST['achievement_type'] : 0;
    $collegeName = trim((string)$_POST['college_name']);
    $theme = trim((string)$_POST['achievement_theme']);
    $title = trim((string)$_POST['achievement_title']);
    $description = trim((string)$_POST['achievement_text']);
    $contextTag = 'College: ' . $collegeName . ' | Theme: ' . $theme;

    if ($typeId !== DOCUMENT && $typeId !== NON_DOCUMENT) {
        $achievementMessage = 'Please select a valid achievement type.';
        $achievementMessageType = 'danger';
    } elseif ($collegeName === '') {
        $achievementMessage = 'College name is required.';
        $achievementMessageType = 'danger';
    } elseif ($theme === '') {
        $achievementMessage = 'Theme is required.';
        $achievementMessageType = 'danger';
    } elseif ($typeId === DOCUMENT) {
        if ($title === '') {
            $achievementMessage = 'Achievement title is required for document upload.';
            $achievementMessageType = 'danger';
        } elseif (!isset($_FILES['achievement_file']) || !is_uploaded_file($_FILES['achievement_file']['tmp_name'])) {
            $achievementMessage = 'Please choose a file to upload.';
            $achievementMessageType = 'danger';
        } else {
            $originalName = (string)$_FILES['achievement_file']['name'];
            $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
            $allowedExtensions = array('pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png');

            if (!in_array($extension, $allowedExtensions, true)) {
                $achievementMessage = 'Only PDF, DOC, DOCX, JPG, JPEG, and PNG files are allowed.';
                $achievementMessageType = 'danger';
            } else {
                $uploadDir = ROOT_PATH . '/public/assets/images/achievements/';
                if (!is_dir($uploadDir)) {
                    @mkdir($uploadDir, 0777, true);
                }

                $safeAdmission = preg_replace('/[^a-zA-Z0-9_-]/', '', (string)$user['admission_id']);
                $fileName = 'achv_' . $safeAdmission . '_' . date('YmdHis') . '_' . mt_rand(1000, 9999) . '.' . $extension;
                $targetFile = $uploadDir . $fileName;

                if (!@move_uploaded_file($_FILES['achievement_file']['tmp_name'], $targetFile)) {
                    $achievementMessage = 'File upload failed. Please try again.';
                    $achievementMessageType = 'danger';
                } else {
                    $varArray = array(
                        'typeId' => DOCUMENT,
                        'achievement_desc' => addslashes($studentTag . ' - ' . $contextTag . ' - ' . $title) . '$$' . $fileName
                    );
                    $saved = $fcObj->addAchievement(TB_ACHIEVEMENTS, $varArray);

                    if ($saved) {
                        $achievementMessage = 'Achievement uploaded successfully. It is now available for recognition.';
                        $achievementMessageType = 'success';
                    } else {
                        @unlink($targetFile);
                        $achievementMessage = 'Unable to save achievement right now. Please try again.';
                        $achievementMessageType = 'danger';
                    }
                }
            }
        }
    } else {
        if ($description === '') {
            $achievementMessage = 'Please add your achievement details.';
            $achievementMessageType = 'danger';
        } else {
            $varArray = array(
                'typeId' => NON_DOCUMENT,
                'achievement_desc' => addslashes($studentTag . ' - ' . $contextTag . ' - ' . $description)
            );
            $saved = $fcObj->addAchievement(TB_ACHIEVEMENTS, $varArray);

            if ($saved) {
                $achievementMessage = 'Achievement submitted successfully. It is now available for recognition.';
                $achievementMessageType = 'success';
            } else {
                $achievementMessage = 'Unable to submit achievement right now. Please try again.';
                $achievementMessageType = 'danger';
            }
        }
    }
}

include_once(INCLUDES_PATH . '/header.php');

$userActivePage = 'achievements';
include_once(__DIR__ . '/layout/main_header.php');
?>
<style>
.student-page{--sp-primary:#173d69;--sp-primary-deep:#13345a;--sp-accent:#f0b323;--sp-accent-deep:#d79a12;--sp-surface:#eef4fa;--sp-card:#fff;--sp-border:#d8e3ef;--sp-text:#284767;--sp-muted:#6b819c;display:grid;gap:20px;padding-bottom:28px}.student-hero{position:relative;overflow:hidden;border:1px solid var(--sp-border);border-radius:26px;padding:28px;background:radial-gradient(circle at top right,rgba(240,179,35,.18),transparent 30%),linear-gradient(135deg,#f9fbfe 0%,var(--sp-surface) 100%);box-shadow:0 18px 36px rgba(15,23,42,.08)}.student-hero:before{content:"";position:absolute;inset:0 auto 0 0;width:7px;background:linear-gradient(180deg,var(--sp-accent),var(--sp-accent-deep))}.student-kicker,.student-tag{display:inline-flex;align-items:center;gap:8px;padding:8px 14px;border-radius:999px;background:rgba(23,61,105,.08);color:var(--sp-primary);font-size:12px;font-weight:800;letter-spacing:.08em;text-transform:uppercase}.student-kicker:before{content:"";width:8px;height:8px;border-radius:999px;background:linear-gradient(135deg,var(--sp-accent),var(--sp-accent-deep))}.student-hero h1{margin:14px 0 10px;color:var(--sp-primary-deep);font-size:clamp(28px,4vw,42px);font-weight:800;line-height:1.04;letter-spacing:-.04em}.student-hero p{margin:0;max-width:820px;color:var(--sp-muted);font-size:15px;line-height:1.7}.student-layout-grid{display:grid;grid-template-columns:minmax(0,1fr) minmax(260px,.38fr);gap:20px}.student-panel{border:1px solid var(--sp-border);border-radius:22px;background:var(--sp-card);box-shadow:0 12px 24px rgba(15,23,42,.06);padding:22px}.student-panel-header{display:flex;align-items:flex-start;justify-content:space-between;gap:14px;margin-bottom:18px}.student-panel-title{margin:0;color:var(--sp-primary-deep);font-size:22px;font-weight:800;letter-spacing:-.03em}.student-panel-subtitle{margin:6px 0 0;color:var(--sp-muted);font-size:14px}.student-alert{border-radius:16px;border:none;padding:14px 16px;font-weight:600}.student-alert.alert-success{background:#ecf7ef;color:#12653a}.student-alert.alert-danger{background:#fff0f0;color:#9a2a2a}.student-form-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px}.student-field{display:grid;gap:8px}.student-field.full{grid-column:1/-1}.student-label{color:var(--sp-primary);font-size:13px;font-weight:800;letter-spacing:.05em;text-transform:uppercase}.student-input,.student-select,.student-textarea{width:100%;border:1px solid #d5e1ee;border-radius:15px;padding:13px 15px;background:#fff;color:var(--sp-text);font-size:15px;transition:border-color .2s ease,box-shadow .2s ease}.student-input:focus,.student-select:focus,.student-textarea:focus{border-color:#88aacf;box-shadow:0 0 0 4px rgba(23,61,105,.08);outline:none}.student-textarea{min-height:130px;resize:vertical}.student-file-picker{display:grid;grid-template-columns:auto minmax(0,1fr);gap:10px;align-items:center}.student-file-btn,.student-primary-btn{display:inline-flex;align-items:center;justify-content:center;border:none;border-radius:14px;padding:12px 18px;font-weight:800;cursor:pointer;text-decoration:none}.student-file-btn{background:#e8f0f8;color:var(--sp-primary)}.student-primary-btn{background:linear-gradient(135deg,var(--sp-primary),var(--sp-primary-deep));color:#fff;box-shadow:0 14px 24px rgba(23,61,105,.18)}.student-help{color:var(--sp-muted);font-size:13px;line-height:1.6;margin-top:8px}.student-info-list{display:grid;gap:12px}.student-info-card{border:1px solid #e2ebf5;border-radius:18px;background:linear-gradient(180deg,#fbfdff 0%,#f6f9fc 100%);padding:16px}.student-info-card h3{margin:0 0 8px;color:var(--sp-primary-deep);font-size:17px;font-weight:800}.student-info-card p{margin:0;color:var(--sp-text);font-size:14px;line-height:1.6}.student-actions{margin-top:20px;display:flex;flex-wrap:wrap;gap:12px}
@media(max-width:1199px){.student-layout-grid{grid-template-columns:1fr}}
@media(max-width:767px){.student-page{gap:16px}.student-hero,.student-panel{padding:18px;border-radius:20px}.student-form-grid,.student-file-picker{grid-template-columns:1fr}.student-panel-header{flex-direction:column;align-items:flex-start}}
</style>
<div class="student-page">
    <section class="student-hero">
        <span class="student-kicker">Achievements</span>
        <h1>Upload Achievement</h1>
        <p>Share certificates, wins, event participation, or text-only recognitions from one cleaner submission form that matches the updated student dashboard style.</p>
    </section>

    <?php if ($achievementMessage !== '') { ?>
        <div class="student-alert alert alert-<?php echo $achievementMessageType; ?>"><?php echo htmlspecialchars($achievementMessage); ?></div>
    <?php } ?>

    <section class="student-layout-grid">
        <div class="student-panel">
            <div class="student-panel-header">
                <div><h2 class="student-panel-title">Achievement Submission</h2><p class="student-panel-subtitle">Choose whether you are uploading a file-based achievement or a text-only recognition note.</p></div>
                <span class="student-tag">Submit</span>
            </div>
            <form method="POST" enctype="multipart/form-data" id="achievementUploadForm">
                <div class="student-form-grid">
                    <div class="student-field"><label class="student-label">Type</label><select name="achievement_type" id="achievement_type" class="student-select" required><option value="">Select Type</option><option value="<?php echo DOCUMENT; ?>" <?php echo (isset($_POST['achievement_type']) && (int)$_POST['achievement_type'] === DOCUMENT) ? 'selected' : ''; ?>>Document Upload</option><option value="<?php echo NON_DOCUMENT; ?>" <?php echo (isset($_POST['achievement_type']) && (int)$_POST['achievement_type'] === NON_DOCUMENT) ? 'selected' : ''; ?>>Text Achievement</option></select></div>
                    <div class="student-field"><label class="student-label">College Name</label><input type="text" name="college_name" class="student-input" value="<?php echo isset($_POST['college_name']) ? htmlspecialchars((string)$_POST['college_name']) : ''; ?>" placeholder="Enter college name" required></div>
                    <div class="student-field full"><label class="student-label">Theme</label><input type="text" name="achievement_theme" class="student-input" value="<?php echo isset($_POST['achievement_theme']) ? htmlspecialchars((string)$_POST['achievement_theme']) : ''; ?>" placeholder="Enter achievement theme" required></div>
                    <div class="student-field full" id="achievement_title_wrap"><label class="student-label">Achievement Title</label><input type="text" name="achievement_title" class="student-input" value="<?php echo isset($_POST['achievement_title']) ? htmlspecialchars((string)$_POST['achievement_title']) : ''; ?>" placeholder="Example: 1st Prize in Hackathon"></div>
                    <div class="student-field full" id="achievement_file_wrap"><label class="student-label">Achievement File</label><div class="student-file-picker"><button type="button" class="student-file-btn" id="achievement_file_btn">Choose File</button><input type="text" class="student-input" id="achievement_file_name" value="No file chosen" readonly></div><input type="file" name="achievement_file" id="achievement_file" class="d-none" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"><div class="student-help">Allowed: PDF, DOC, DOCX, JPG, JPEG, PNG.</div></div>
                    <div class="student-field full d-none" id="achievement_text_wrap"><label class="student-label">Achievement Details</label><textarea name="achievement_text" class="student-textarea" placeholder="Describe your achievement in short."><?php echo isset($_POST['achievement_text']) ? htmlspecialchars((string)$_POST['achievement_text']) : ''; ?></textarea></div>
                </div>
                <div class="student-actions"><button type="submit" name="submit_achievement" class="student-primary-btn">Submit Achievement</button></div>
            </form>
        </div>

        <aside class="student-panel">
            <div class="student-panel-header">
                <div><h2 class="student-panel-title">Submission Guide</h2><p class="student-panel-subtitle">A quick reminder before you submit your entry.</p></div>
                <span class="student-tag">Guide</span>
            </div>
            <div class="student-info-list">
                <article class="student-info-card"><h3>Document Upload</h3><p>Use this when you have a certificate, proof file, or supporting document to attach with your achievement title.</p></article>
                <article class="student-info-card"><h3>Text Achievement</h3><p>Use this when your recognition can be explained clearly in words and no document upload is required.</p></article>
                <article class="student-info-card"><h3>Keep It Clear</h3><p>College name, theme, and description should be specific so admin can review and recognize your submission faster.</p></article>
            </div>
        </aside>
    </section>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var typeSelect = document.getElementById('achievement_type');
    var titleWrap = document.getElementById('achievement_title_wrap');
    var fileWrap = document.getElementById('achievement_file_wrap');
    var textWrap = document.getElementById('achievement_text_wrap');
    var fileInput = document.getElementById('achievement_file');
    var fileButton = document.getElementById('achievement_file_btn');
    var fileName = document.getElementById('achievement_file_name');
    if (!typeSelect || !titleWrap || !fileWrap || !textWrap) {
        return;
    }
    function toggleAchievementFields() {
        var selectedType = typeSelect.value;
        if (selectedType === '<?php echo NON_DOCUMENT; ?>') {
            titleWrap.classList.add('d-none');
            fileWrap.classList.add('d-none');
            textWrap.classList.remove('d-none');
        } else {
            titleWrap.classList.remove('d-none');
            fileWrap.classList.remove('d-none');
            textWrap.classList.add('d-none');
        }
    }
    typeSelect.addEventListener('change', toggleAchievementFields);
    toggleAchievementFields();
    if (fileInput && fileButton && fileName) {
        fileButton.addEventListener('click', function () { fileInput.click(); });
        fileInput.addEventListener('change', function () {
            fileName.value = (fileInput.files && fileInput.files.length > 0) ? fileInput.files[0].name : 'No file chosen';
        });
    }
});
</script>
<?php include_once(__DIR__ . '/layout/main_footer.php'); ?>
