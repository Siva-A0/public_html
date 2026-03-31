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

if (isset($_POST['submit_achievement'])) {
    $typeId = isset($_POST['achievement_type']) ? (int)$_POST['achievement_type'] : 0;
    $organizationName = trim((string)($_POST['organization_name'] ?? ''));
    $theme = trim((string)($_POST['achievement_theme'] ?? ''));
    $title = trim((string)($_POST['achievement_title'] ?? ''));
    $description = trim((string)($_POST['achievement_text'] ?? ''));
    $contextTag = 'Organization: ' . $organizationName . ' | Theme: ' . $theme;

    if ($typeId !== DOCUMENT && $typeId !== NON_DOCUMENT) {
        $message = 'Please select a valid achievement type.';
        $messageType = 'danger';
    } elseif ($organizationName === '') {
        $message = 'Organization name is required.';
        $messageType = 'danger';
    } elseif ($theme === '') {
        $message = 'Theme is required.';
        $messageType = 'danger';
    } elseif ($typeId === DOCUMENT) {
        if ($title === '') {
            $message = 'Achievement title is required for document upload.';
            $messageType = 'danger';
        } elseif (!isset($_FILES['achievement_file']) || !is_uploaded_file($_FILES['achievement_file']['tmp_name'])) {
            $message = 'Please choose a file to upload.';
            $messageType = 'danger';
        } else {
            $originalName = (string)$_FILES['achievement_file']['name'];
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
                $fileName = 'fachv_' . $safeFacultyId . '_' . date('YmdHis') . '_' . mt_rand(1000, 9999) . '.' . $extension;
                $targetFile = $uploadDir . $fileName;

                if (!@move_uploaded_file($_FILES['achievement_file']['tmp_name'], $targetFile)) {
                    $message = 'File upload failed. Please try again.';
                    $messageType = 'danger';
                } else {
                    $varArray = array(
                        'typeId' => DOCUMENT,
                        'achievement_desc' => addslashes(app_build_submission_desc('faculty', 'achievement', $faculty['id'], $facultyTag, $contextTag, $title)) . '$$' . $fileName
                    );
                    $saved = $fcObj->addAchievement(TB_ACHIEVEMENTS, $varArray);

                    if ($saved) {
                        $message = 'Achievement uploaded successfully.';
                        $messageType = 'success';
                    } else {
                        @unlink($targetFile);
                        $message = $fcObj->getLastError() !== '' ? $fcObj->getLastError() : 'Unable to save achievement right now. Please try again.';
                        $messageType = 'danger';
                    }
                }
            }
        }
    } else {
        if ($description === '') {
            $message = 'Please add your achievement details.';
            $messageType = 'danger';
        } else {
            $varArray = array(
                'typeId' => NON_DOCUMENT,
                'achievement_desc' => addslashes(app_build_submission_desc('faculty', 'achievement', $faculty['id'], $facultyTag, $contextTag, $description))
            );
            $saved = $fcObj->addAchievement(TB_ACHIEVEMENTS, $varArray);

            if ($saved) {
                $message = 'Achievement submitted successfully.';
                $messageType = 'success';
            } else {
                $message = $fcObj->getLastError() !== '' ? $fcObj->getLastError() : 'Unable to submit achievement right now. Please try again.';
                $messageType = 'danger';
            }
        }
    }
}

$hidePublicNavbar = true;
include_once(INCLUDES_PATH . '/header.php');
$facultyActivePage = 'achievements';
include_once(__DIR__ . '/layout/main_header.php');
?>
<style>
.faculty-page{--fp-primary:#173d69;--fp-primary-deep:#13345a;--fp-accent:#f0b323;--fp-accent-deep:#d79a12;--fp-surface:#eef4fa;--fp-card:#fff;--fp-border:#d8e3ef;--fp-text:#284767;--fp-muted:#6b819c;display:grid;gap:20px;padding-bottom:28px}.faculty-hero{position:relative;overflow:hidden;border:1px solid var(--fp-border);border-radius:26px;padding:28px;background:radial-gradient(circle at top right,rgba(240,179,35,.18),transparent 30%),linear-gradient(135deg,#f9fbfe 0%,var(--fp-surface) 100%);box-shadow:0 18px 36px rgba(15,23,42,.08)}.faculty-hero:before{content:"";position:absolute;inset:0 auto 0 0;width:7px;background:linear-gradient(180deg,var(--fp-accent),var(--fp-accent-deep))}.faculty-kicker,.faculty-tag{display:inline-flex;align-items:center;gap:8px;padding:8px 14px;border-radius:999px;background:rgba(23,61,105,.08);color:var(--fp-primary);font-size:12px;font-weight:800;letter-spacing:.08em;text-transform:uppercase}.faculty-kicker:before{content:"";width:8px;height:8px;border-radius:999px;background:linear-gradient(135deg,var(--fp-accent),var(--fp-accent-deep))}.faculty-hero h1{margin:14px 0 10px;color:var(--fp-primary-deep);font-size:clamp(28px,4vw,42px);font-weight:800;line-height:1.04;letter-spacing:-.04em}.faculty-hero p{margin:0;max-width:820px;color:var(--fp-muted);font-size:15px;line-height:1.7}.faculty-layout-grid{display:grid;grid-template-columns:minmax(0,1fr) minmax(260px,.38fr);gap:20px}.faculty-panel{border:1px solid var(--fp-border);border-radius:22px;background:var(--fp-card);box-shadow:0 12px 24px rgba(15,23,42,.06);padding:22px}.faculty-panel-header{display:flex;align-items:flex-start;justify-content:space-between;gap:14px;margin-bottom:18px}.faculty-panel-title{margin:0;color:var(--fp-primary-deep);font-size:22px;font-weight:800;letter-spacing:-.03em}.faculty-panel-subtitle{margin:6px 0 0;color:var(--fp-muted);font-size:14px}.faculty-alert{border-radius:16px;border:none;padding:14px 16px;font-weight:600}.faculty-alert.alert-success{background:#ecf7ef;color:#12653a}.faculty-alert.alert-danger{background:#fff0f0;color:#9a2a2a}.faculty-form-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px}.faculty-field{display:grid;gap:8px}.faculty-field.full{grid-column:1/-1}.faculty-label{color:var(--fp-primary);font-size:13px;font-weight:800;letter-spacing:.05em;text-transform:uppercase}.faculty-input,.faculty-select,.faculty-textarea{width:100%;border:1px solid #d5e1ee;border-radius:15px;padding:13px 15px;background:#fff;color:var(--fp-text);font-size:15px;transition:border-color .2s ease,box-shadow .2s ease}.faculty-input:focus,.faculty-select:focus,.faculty-textarea:focus{border-color:#88aacf;box-shadow:0 0 0 4px rgba(23,61,105,.08);outline:none}.faculty-textarea{min-height:130px;resize:vertical}.faculty-file-picker{display:grid;grid-template-columns:auto minmax(0,1fr);gap:10px;align-items:center}.faculty-file-btn,.faculty-primary-btn,.faculty-link-btn{display:inline-flex;align-items:center;justify-content:center;border:none;border-radius:14px;padding:12px 18px;font-weight:800;cursor:pointer;text-decoration:none}.faculty-file-btn{background:#e8f0f8;color:var(--fp-primary)}.faculty-primary-btn,.faculty-link-btn{background:linear-gradient(135deg,var(--fp-primary),var(--fp-primary-deep));color:#fff;box-shadow:0 14px 24px rgba(23,61,105,.18)}.faculty-help{color:var(--fp-muted);font-size:13px;line-height:1.6;margin-top:8px}.faculty-info-list{display:grid;gap:12px}.faculty-info-card{border:1px solid #e2ebf5;border-radius:18px;background:linear-gradient(180deg,#fbfdff 0%,#f6f9fc 100%);padding:16px}.faculty-info-card h3{margin:0 0 8px;color:var(--fp-primary-deep);font-size:17px;font-weight:800}.faculty-info-card p{margin:0;color:var(--fp-text);font-size:14px;line-height:1.6}.faculty-actions{margin-top:20px;display:flex;flex-wrap:wrap;gap:12px}
@media(max-width:1199px){.faculty-layout-grid{grid-template-columns:1fr}}
@media(max-width:767px){.faculty-page{gap:16px}.faculty-hero,.faculty-panel{padding:18px;border-radius:20px}.faculty-form-grid,.faculty-file-picker{grid-template-columns:1fr}.faculty-panel-header{flex-direction:column;align-items:flex-start}}
</style>
<div class="faculty-page">
    <section class="faculty-hero">
        <span class="faculty-kicker">Achievements</span>
        <h1>Upload Achievement</h1>
        <p></p>
    </section>

    <?php if ($message !== '') { ?>
        <div class="faculty-alert alert alert-<?php echo $messageType; ?>"><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php } ?>

    <section class="faculty-layout-grid">
        <div class="faculty-panel">
            <div class="faculty-panel-header">
                <div><h2 class="faculty-panel-title">Achievement Submission</h2><p class="faculty-panel-subtitle"></p></div>
                <span class="faculty-tag">Submit</span>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <div class="faculty-form-grid">
                    <div class="faculty-field"><label class="faculty-label">Type</label><select name="achievement_type" id="faculty_achievement_type" class="faculty-select" required><option value="">Select Type</option><option value="<?php echo DOCUMENT; ?>" <?php echo (isset($_POST['achievement_type']) && (int)$_POST['achievement_type'] === DOCUMENT) ? 'selected' : ''; ?>>Document Upload</option><option value="<?php echo NON_DOCUMENT; ?>" <?php echo (isset($_POST['achievement_type']) && (int)$_POST['achievement_type'] === NON_DOCUMENT) ? 'selected' : ''; ?>>Text Achievement</option></select></div>
                    <div class="faculty-field"><label class="faculty-label">Organization Name</label><input type="text" name="organization_name" class="faculty-input" value="<?php echo isset($_POST['organization_name']) ? htmlspecialchars((string)$_POST['organization_name'], ENT_QUOTES, 'UTF-8') : ''; ?>" placeholder="" required></div>
                    <div class="faculty-field full"><label class="faculty-label">Theme</label><input type="text" name="achievement_theme" class="faculty-input" value="<?php echo isset($_POST['achievement_theme']) ? htmlspecialchars((string)$_POST['achievement_theme'], ENT_QUOTES, 'UTF-8') : ''; ?>" placeholder="" required></div>
                    <div class="faculty-field full" id="faculty_achievement_title_wrap"><label class="faculty-label">Achievement Title</label><input type="text" name="achievement_title" class="faculty-input" value="<?php echo isset($_POST['achievement_title']) ? htmlspecialchars((string)$_POST['achievement_title'], ENT_QUOTES, 'UTF-8') : ''; ?>" placeholder=""></div>
                    <div class="faculty-field full" id="faculty_achievement_file_wrap"><label class="faculty-label">Achievement File</label><div class="faculty-file-picker"><button type="button" class="faculty-file-btn" id="faculty_achievement_file_btn">Choose File</button><input type="text" class="faculty-input" id="faculty_achievement_file_name" value="No file chosen" readonly></div><input type="file" name="achievement_file" id="faculty_achievement_file" class="d-none" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"><div class="faculty-help">Allowed: PDF, DOC, DOCX, JPG, JPEG, PNG.</div></div>
                    <div class="faculty-field full d-none" id="faculty_achievement_text_wrap"><label class="faculty-label">Achievement Details</label><textarea name="achievement_text" class="faculty-textarea" placeholder=""><?php echo isset($_POST['achievement_text']) ? htmlspecialchars((string)$_POST['achievement_text'], ENT_QUOTES, 'UTF-8') : ''; ?></textarea></div>
                </div>
                <div class="faculty-actions">
                    <button type="submit" name="submit_achievement" class="faculty-primary-btn">Submit Achievement</button>
                    <a class="faculty-link-btn" href="<?php echo BASE_URL; ?>/public/pages/faculty/my_achievements.php">View My Achievements</a>
                </div>
            </form>
        </div>

        <aside class="faculty-panel">
            <div class="faculty-panel-header">
                <div><h2 class="faculty-panel-title">Submission Guide</h2><p class="faculty-panel-subtitle"></p></div>
                <span class="faculty-tag">Guide</span>
            </div>
            <div class="faculty-info-list">
                <article class="faculty-info-card"><h3>Document Upload</h3><p></p></article>
                <article class="faculty-info-card"><h3>Text Achievement</h3><p></p></article>
                <article class="faculty-info-card"><h3>Keep It Searchable</h3><p></p></article>
            </div>
        </aside>
    </section>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var typeSelect = document.getElementById('faculty_achievement_type');
    var titleWrap = document.getElementById('faculty_achievement_title_wrap');
    var fileWrap = document.getElementById('faculty_achievement_file_wrap');
    var textWrap = document.getElementById('faculty_achievement_text_wrap');
    var fileInput = document.getElementById('faculty_achievement_file');
    var fileButton = document.getElementById('faculty_achievement_file_btn');
    var fileName = document.getElementById('faculty_achievement_file_name');
    if (!typeSelect || !titleWrap || !fileWrap || !textWrap) {
        return;
    }
    function toggleFacultyAchievementFields() {
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
    typeSelect.addEventListener('change', toggleFacultyAchievementFields);
    toggleFacultyAchievementFields();
    if (fileInput && fileButton && fileName) {
        fileButton.addEventListener('click', function () { fileInput.click(); });
        fileInput.addEventListener('change', function () {
            fileName.value = (fileInput.files && fileInput.files.length > 0) ? fileInput.files[0].name : 'No file chosen';
        });
    }
});
</script>
<?php include_once(__DIR__ . '/layout/main_footer.php'); ?>
