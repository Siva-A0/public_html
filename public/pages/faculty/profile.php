<?php
if (session_id() == '') {
    session_start();
}

require_once(__DIR__ . '/../../../config.php');
require_once(LIB_PATH . '/functions.class.php');
require_once(LIB_PATH . '/security.php');

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
$currentProfileImage = trim((string)($faculty['image'] ?? ''));
$currentProfileImageUrl = '';
if ($currentProfileImage !== '' && preg_match('/^[A-Za-z0-9._-]+$/', $currentProfileImage) === 1) {
    $currentProfileImageFsPath = ROOT_PATH . '/public/assets/images/staff/' . $currentProfileImage;
    if (is_file($currentProfileImageFsPath)) {
        $currentProfileImageUrl = BASE_URL . '/public/assets/images/staff/' . rawurlencode($currentProfileImage);
    }
}

if (isset($_POST['update_profile'])) {
    if (!app_validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $message = 'Your session expired. Please refresh and try again.';
        $messageType = 'danger';
    } else {
        $firstName = trim((string)($_POST['first_name'] ?? ''));
        $lastName = trim((string)($_POST['last_name'] ?? ''));
        $email = trim((string)($_POST['email'] ?? ''));
        $designation = trim((string)($_POST['designation'] ?? ''));
        $qualification = trim((string)($_POST['qualification'] ?? ''));
        $industryExp = trim((string)($_POST['industry_exp'] ?? ''));
        $teachingExp = trim((string)($_POST['teach_exp'] ?? ''));
        $research = trim((string)($_POST['research'] ?? ''));
        $publNational = trim((string)($_POST['publ_national'] ?? ''));
        $publInternational = trim((string)($_POST['publ_international'] ?? ''));
        $confNational = trim((string)($_POST['conf_national'] ?? ''));
        $confInternational = trim((string)($_POST['conf_international'] ?? ''));
        $newPassword = trim((string)($_POST['new_password'] ?? ''));
        $confirmPassword = trim((string)($_POST['confirm_password'] ?? ''));

        if ($firstName === '' || $lastName === '' || $email === '') {
            $message = 'First name, last name, and email are required.';
            $messageType = 'danger';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = 'Please enter a valid email address.';
            $messageType = 'danger';
        } elseif ($newPassword !== '' && $newPassword !== $confirmPassword) {
            $message = 'New password and confirm password do not match.';
            $messageType = 'danger';
        } elseif ($newPassword !== '' && strlen($newPassword) < 8) {
            $message = 'New password must be at least 8 characters long.';
            $messageType = 'danger';
        } else {
            $passwordToStore = (string)$faculty['password'];
            if ($newPassword !== '') {
                $passwordToStore = $fcObj->hashPassword($newPassword);
            }

            $imageToStore = trim((string)$faculty['image']);
            $uploadedImagePath = '';
            $isNewImageUploaded = false;

            if (isset($_FILES['profile_image']) && is_uploaded_file($_FILES['profile_image']['tmp_name'])) {
                $uploadDir = ROOT_PATH . '/public/assets/images/staff/';
                $safeFacultyId = preg_replace('/[^a-zA-Z0-9_-]/', '', (string)$faculty['id']);
                $uploadError = '';
                $uploadedFile = app_store_uploaded_image($_FILES['profile_image'], $uploadDir, 'faculty_' . $safeFacultyId, $uploadError, 2 * 1024 * 1024);
                if ($uploadedFile === '') {
                    $message = $uploadError;
                    $messageType = 'danger';
                } else {
                    $imageToStore = $uploadedFile;
                    $uploadedImagePath = $uploadDir . $imageToStore;
                    $isNewImageUploaded = true;
                }
            }

            if ($message === '') {
                $varArray = array(
                    'staffType' => (int)$faculty['staff_categ_id'],
                    'firstName' => $firstName,
                    'lastName' => $lastName,
                    'staffQualif' => $qualification,
                    'staffDesig' => $designation,
                    'indusExp' => $industryExp,
                    'teachingExp' => $teachingExp,
                    'research' => $research,
                    'pub_nat' => $publNational,
                    'pub_internat' => $publInternational,
                    'conf_nat' => $confNational,
                    'conf_internat' => $confInternational,
                    'email' => $email,
                    'password' => $passwordToStore,
                    'image' => $imageToStore
                );

                $updated = $fcObj->editStaffDetails(TB_STAFF, (int)$faculty['id'], $varArray);
                if ($updated !== false) {
                    $_SESSION['facultyEmail'] = $email;
                    $_SESSION['facultyFirstName'] = $firstName;
                    $_SESSION['facultyName'] = trim($firstName . ' ' . $lastName);
                    $_SESSION['facultyImage'] = $imageToStore;

                    if ((int)$updated === 0) {
                        $message = 'No changes to update.';
                        $messageType = 'info';
                    } else {
                        $message = 'Your faculty profile has been updated successfully.';
                        $messageType = 'success';
                    }

                    $staffRows = $fcObj->getStaffDetailsById(TB_STAFF, (int)$_SESSION['facultyId']);
                    $faculty = $staffRows[0];
                    $currentProfileImage = trim((string)($faculty['image'] ?? ''));
                    $currentProfileImageUrl = '';
                    if ($currentProfileImage !== '' && preg_match('/^[A-Za-z0-9._-]+$/', $currentProfileImage) === 1) {
                        $currentProfileImageFsPath = ROOT_PATH . '/public/assets/images/staff/' . $currentProfileImage;
                        if (is_file($currentProfileImageFsPath)) {
                            $currentProfileImageUrl = BASE_URL . '/public/assets/images/staff/' . rawurlencode($currentProfileImage);
                        }
                    }
                } else {
                    if ($isNewImageUploaded && $uploadedImagePath !== '' && file_exists($uploadedImagePath)) {
                        @unlink($uploadedImagePath);
                    }
                    $message = 'Profile update failed. Please try again.';
                    $messageType = 'danger';
                }
            }
        }
    }
}

$facultyName = trim((string)$faculty['first_name'] . ' ' . (string)$faculty['last_name']);
if ($facultyName === '') {
    $facultyName = trim((string)($_SESSION['facultyName'] ?? $_SESSION['facultyFirstName'] ?? 'Faculty Member'));
}
$facultyDisplayName = strtoupper($facultyName !== '' ? $facultyName : 'FACULTY');
$facultyInitials = strtoupper(substr((string)$faculty['first_name'], 0, 1) . substr((string)$faculty['last_name'], 0, 1));
if ($facultyInitials === '') {
    $facultyInitials = strtoupper(substr($facultyDisplayName, 0, 1));
}

$hidePublicNavbar = true;
include_once(INCLUDES_PATH . '/header.php');
$facultyActivePage = 'profile';
include_once(__DIR__ . '/layout/main_header.php');
?>
<style>
.faculty-profile-page {--fd-primary:#173d69;--fd-primary-deep:#13345a;--fd-accent:#f0b323;--fd-accent-deep:#d79a12;--fd-surface:#eef4fa;--fd-card:#ffffff;--fd-border:#d9e3ef;--fd-muted:#6b819c;--fd-text:#23415f;display:grid;gap:20px;padding-bottom:24px}
.faculty-hero{position:relative;overflow:hidden;border:1px solid var(--fd-border);border-radius:26px;padding:28px;background:radial-gradient(circle at top right,rgba(240,179,35,.18),transparent 28%),linear-gradient(135deg,#f9fbfe 0%,var(--fd-surface) 100%);box-shadow:0 18px 36px rgba(15,23,42,.08)}
.faculty-hero:before{content:"";position:absolute;inset:0 auto 0 0;width:7px;background:linear-gradient(180deg,var(--fd-accent),var(--fd-accent-deep))}
.faculty-hero-grid{display:grid;grid-template-columns:180px minmax(0,1fr);gap:22px;align-items:center}.faculty-avatar{width:180px;height:200px;border-radius:24px;overflow:hidden;border:4px solid rgba(255,255,255,.94);box-shadow:0 18px 34px rgba(19,52,90,.16);background:linear-gradient(180deg,#d8e4f1 0%,#c4d4e6 100%);display:flex;align-items:center;justify-content:center}.faculty-avatar img{width:100%;height:100%;object-fit:cover}.faculty-avatar-fallback{width:100%;height:100%;display:flex;align-items:center;justify-content:center;color:var(--fd-primary-deep);font-size:52px;font-weight:800;letter-spacing:.08em}
.faculty-kicker,.faculty-tag{display:inline-flex;align-items:center;gap:8px;padding:8px 14px;border-radius:999px;background:rgba(23,61,105,.08);color:var(--fd-primary);font-size:12px;font-weight:800;letter-spacing:.08em;text-transform:uppercase}.faculty-kicker:before{content:"";width:8px;height:8px;border-radius:999px;background:linear-gradient(135deg,var(--fd-accent),var(--fd-accent-deep))}.faculty-display-name{margin:14px 0 10px;font-size:clamp(28px,4vw,42px);line-height:1.05;font-weight:800;letter-spacing:-.04em;color:var(--fd-primary-deep);text-transform:uppercase}.faculty-meta-line{display:flex;flex-wrap:wrap;gap:10px;margin-top:14px}.faculty-meta-pill{display:inline-flex;align-items:center;gap:8px;padding:10px 14px;border-radius:999px;border:1px solid #d6e2ef;background:rgba(255,255,255,.84);color:var(--fd-text);font-size:14px;font-weight:700}.faculty-meta-pill strong{color:var(--fd-primary)}.faculty-hero-copy{margin-top:16px;max-width:760px;color:var(--fd-muted);font-size:15px;line-height:1.7}
.faculty-profile-grid{display:grid;grid-template-columns:minmax(0,1fr) minmax(260px,.42fr);gap:20px}.faculty-panel{border:1px solid var(--fd-border);border-radius:22px;background:var(--fd-card);box-shadow:0 12px 24px rgba(15,23,42,.06);padding:22px}.faculty-panel-header{display:flex;align-items:flex-start;justify-content:space-between;gap:14px;margin-bottom:18px}.faculty-panel-title{margin:0;color:var(--fd-primary-deep);font-size:22px;font-weight:800;letter-spacing:-.03em}.faculty-panel-subtitle{margin:6px 0 0;color:var(--fd-muted);font-size:14px}
.faculty-alert{border-radius:16px;border:none;padding:14px 16px;font-weight:600}.faculty-alert.alert-success{background:#ecf7ef;color:#12653a}.faculty-alert.alert-danger{background:#fff0f0;color:#9a2a2a}.faculty-alert.alert-info{background:#edf5ff;color:#1f568d}
.faculty-form-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px}.faculty-field{display:grid;gap:8px}.faculty-field.full{grid-column:1/-1}.faculty-label{color:var(--fd-primary);font-size:13px;font-weight:800;letter-spacing:.05em;text-transform:uppercase}.faculty-input,.faculty-textarea{width:100%;border:1px solid #d5e1ee;border-radius:15px;padding:13px 15px;background:#fff;color:var(--fd-text);font-size:15px;transition:border-color .2s ease,box-shadow .2s ease}.faculty-input:focus,.faculty-textarea:focus{border-color:#88aacf;box-shadow:0 0 0 4px rgba(23,61,105,.08);outline:none}.faculty-textarea{min-height:130px;resize:vertical}.faculty-file-picker{display:grid;grid-template-columns:auto minmax(0,1fr);gap:10px;align-items:center}.faculty-file-btn,.faculty-primary-btn{display:inline-flex;align-items:center;justify-content:center;border:none;border-radius:14px;padding:12px 18px;font-weight:800;cursor:pointer;text-decoration:none}.faculty-file-btn{background:#e8f0f8;color:var(--fd-primary)}.faculty-primary-btn{background:linear-gradient(135deg,var(--fd-primary),var(--fd-primary-deep));color:#fff;box-shadow:0 14px 24px rgba(23,61,105,.18)}.faculty-help{color:var(--fd-muted);font-size:13px;line-height:1.6;margin-top:8px}.faculty-info-list{display:grid;gap:12px}.faculty-info-card{border:1px solid #e2ebf5;border-radius:18px;background:linear-gradient(180deg,#fbfdff 0%,#f6f9fc 100%);padding:16px}.faculty-info-label{margin:0 0 6px;color:var(--fd-muted);font-size:12px;font-weight:800;letter-spacing:.06em;text-transform:uppercase}.faculty-info-value{margin:0;color:var(--fd-primary-deep);font-size:16px;font-weight:800;line-height:1.5}.faculty-actions{margin-top:20px;display:flex;flex-wrap:wrap;gap:12px}
@media (max-width:1199px){.faculty-profile-grid{grid-template-columns:1fr}}
@media (max-width:767px){.faculty-profile-page{gap:16px}.faculty-hero,.faculty-panel{padding:18px;border-radius:20px}.faculty-hero-grid,.faculty-form-grid,.faculty-file-picker{grid-template-columns:1fr}.faculty-avatar{margin:0 auto;width:160px;height:180px}.faculty-panel-header{flex-direction:column;align-items:flex-start}.faculty-meta-line{justify-content:flex-start}}
</style>
<div class="faculty-profile-page">
    <section class="faculty-hero">
        <div class="faculty-hero-grid">
            <div class="faculty-avatar">
                <?php if ($currentProfileImageUrl !== '') { ?>
                    <img src="<?php echo htmlspecialchars($currentProfileImageUrl, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($facultyDisplayName, ENT_QUOTES, 'UTF-8'); ?>" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="faculty-avatar-fallback" style="display:none;"><?php echo htmlspecialchars($facultyInitials, ENT_QUOTES, 'UTF-8'); ?></div>
                <?php } else { ?>
                    <div class="faculty-avatar-fallback"><?php echo htmlspecialchars($facultyInitials, ENT_QUOTES, 'UTF-8'); ?></div>
                <?php } ?>
            </div>
            <div>
                <span class="faculty-kicker">Account Settings</span>
                <h1 class="faculty-display-name"><?php echo htmlspecialchars($facultyDisplayName, ENT_QUOTES, 'UTF-8'); ?></h1>
                <div class="faculty-meta-line">
                    <span class="faculty-meta-pill"><strong>Designation</strong> <?php echo htmlspecialchars((string)($faculty['designation'] ?? 'Not Set'), ENT_QUOTES, 'UTF-8'); ?></span>
                    <span class="faculty-meta-pill"><strong>Email</strong> <?php echo htmlspecialchars((string)($faculty['e_mail'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></span>
                </div>
                <p class="faculty-hero-copy">Manage your faculty profile, academic details, research summary, publications, and password from one working settings page that matches the redesigned faculty dashboard.</p>
            </div>
        </div>
    </section>

    <?php if ($message !== '') { ?>
        <div class="faculty-alert alert alert-<?php echo $messageType; ?>"><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php } ?>

    <section class="faculty-profile-grid">
        <div class="faculty-panel">
            <div class="faculty-panel-header">
                <div>
                    <h2 class="faculty-panel-title">Edit Faculty Profile</h2>
                    <p class="faculty-panel-subtitle">Update the information shown across your faculty dashboard and public-facing department profile.</p>
                </div>
                <span class="faculty-tag">Profile Form</span>
            </div>
            <form method="POST" action="" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(app_get_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
                <div class="faculty-form-grid">
                    <div class="faculty-field full">
                        <label class="faculty-label">Profile Photo</label>
                        <div class="faculty-file-picker">
                            <button type="button" class="faculty-file-btn" id="faculty_file_btn">Choose File</button>
                            <input type="text" class="faculty-input" id="faculty_file_name" value="No file chosen" readonly>
                        </div>
                        <input type="file" name="profile_image" id="profile_image" class="d-none" accept=".jpg,.jpeg,.png,.webp">
                        <div class="faculty-help">Allowed: JPG, JPEG, PNG, WEBP. Maximum size: 2MB.</div>
                    </div>
                    <div class="faculty-field"><label class="faculty-label">First Name</label><input type="text" name="first_name" class="faculty-input" value="<?php echo htmlspecialchars((string)($faculty['first_name'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>" required></div>
                    <div class="faculty-field"><label class="faculty-label">Last Name</label><input type="text" name="last_name" class="faculty-input" value="<?php echo htmlspecialchars((string)($faculty['last_name'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>" required></div>
                    <div class="faculty-field"><label class="faculty-label">Email</label><input type="email" name="email" class="faculty-input" value="<?php echo htmlspecialchars((string)($faculty['e_mail'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>" required></div>
                    <div class="faculty-field"><label class="faculty-label">Designation</label><input type="text" name="designation" class="faculty-input" value="<?php echo htmlspecialchars((string)($faculty['designation'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>"></div>
                    <div class="faculty-field"><label class="faculty-label">Qualification</label><input type="text" name="qualification" class="faculty-input" value="<?php echo htmlspecialchars(str_replace('\\,', ',', (string)($faculty['qualification'] ?? '')), ENT_QUOTES, 'UTF-8'); ?>"></div>
                    <div class="faculty-field"><label class="faculty-label">Industry Experience</label><input type="text" name="industry_exp" class="faculty-input" value="<?php echo htmlspecialchars((string)($faculty['industry_exp'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>"></div>
                    <div class="faculty-field"><label class="faculty-label">Teaching Experience</label><input type="text" name="teach_exp" class="faculty-input" value="<?php echo htmlspecialchars((string)($faculty['teach_exp'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>"></div>
                    <div class="faculty-field full"><label class="faculty-label">Research Summary</label><textarea name="research" class="faculty-textarea"><?php echo htmlspecialchars((string)($faculty['research'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></textarea></div>
                    <div class="faculty-field"><label class="faculty-label">National Publications</label><input type="text" name="publ_national" class="faculty-input" value="<?php echo htmlspecialchars((string)($faculty['publ_national'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>"></div>
                    <div class="faculty-field"><label class="faculty-label">International Publications</label><input type="text" name="publ_international" class="faculty-input" value="<?php echo htmlspecialchars((string)($faculty['publ_international'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>"></div>
                    <div class="faculty-field"><label class="faculty-label">National Conferences</label><input type="text" name="conf_national" class="faculty-input" value="<?php echo htmlspecialchars((string)($faculty['conf_national'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>"></div>
                    <div class="faculty-field"><label class="faculty-label">International Conferences</label><input type="text" name="conf_international" class="faculty-input" value="<?php echo htmlspecialchars((string)($faculty['conf_international'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>"></div>
                    <div class="faculty-field"><label class="faculty-label">New Password</label><input type="password" name="new_password" class="faculty-input" placeholder="Leave empty to keep current password"></div>
                    <div class="faculty-field"><label class="faculty-label">Confirm Password</label><input type="password" name="confirm_password" class="faculty-input" placeholder="Retype new password"></div>
                </div>
                <div class="faculty-actions"><button type="submit" name="update_profile" class="faculty-primary-btn">Update Faculty Profile</button></div>
            </form>
        </div>

        <aside class="faculty-panel">
            <div class="faculty-panel-header">
                <div>
                    <h2 class="faculty-panel-title">Profile Snapshot</h2>
                    <p class="faculty-panel-subtitle">A quick read-only summary of your current faculty record.</p>
                </div>
                <span class="faculty-tag">Snapshot</span>
            </div>
            <div class="faculty-info-list">
                <div class="faculty-info-card"><p class="faculty-info-label">Faculty ID</p><p class="faculty-info-value"><?php echo htmlspecialchars((string)$faculty['id'], ENT_QUOTES, 'UTF-8'); ?></p></div>
                <div class="faculty-info-card"><p class="faculty-info-label">Designation</p><p class="faculty-info-value"><?php echo htmlspecialchars((string)($faculty['designation'] ?? 'Not Set'), ENT_QUOTES, 'UTF-8'); ?></p></div>
                <div class="faculty-info-card"><p class="faculty-info-label">Qualification</p><p class="faculty-info-value"><?php echo htmlspecialchars(str_replace('\\,', ',', (string)($faculty['qualification'] ?? 'Not Set')), ENT_QUOTES, 'UTF-8'); ?></p></div>
                <div class="faculty-info-card"><p class="faculty-info-label">Teaching Experience</p><p class="faculty-info-value"><?php echo htmlspecialchars((string)($faculty['teach_exp'] ?? 'Not Set'), ENT_QUOTES, 'UTF-8'); ?></p></div>
                <div class="faculty-info-card"><p class="faculty-info-label">Industry Experience</p><p class="faculty-info-value"><?php echo htmlspecialchars((string)($faculty['industry_exp'] ?? 'Not Set'), ENT_QUOTES, 'UTF-8'); ?></p></div>
            </div>
        </aside>
    </section>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var fileInput = document.getElementById('profile_image');
    var fileButton = document.getElementById('faculty_file_btn');
    var fileName = document.getElementById('faculty_file_name');
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
