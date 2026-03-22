<?php
if (session_id() == '') {
    session_start();
}

require_once(__DIR__ . '/../../../config.php');
require_once(LIB_PATH . '/functions.class.php');
require_once(LIB_PATH . '/security.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user' || !isset($_SESSION['userName'])) {
    header('Location: ' . BASE_URL . '/public/pages/Authentication/login.php');
    exit;
}

$fcObj = new DataFunctions();
$tbUsers = TB_USERS;

$message = '';
$messageType = '';

$userData = $fcObj->userCheck($tbUsers, $_SESSION['userName']);
if (empty($userData)) {
    header('Location: ' . BASE_URL . '/public/pages/Authentication/logout.php');
    exit;
}

$user = $userData[0];
$currentProfileImage = trim((string)$user['image']);
$currentProfileImageUrl = $currentProfileImage !== '' ? BASE_URL . '/public/assets/images/users/' . rawurlencode($currentProfileImage) : '';

if (isset($_POST['update_profile'])) {
    if (!app_validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $message = 'Your session expired. Please refresh and try again.';
        $messageType = 'danger';
    } else {
        $username = trim((string)($_POST['username'] ?? ''));
        $firstName = trim((string)($_POST['firstname'] ?? ''));
        $lastName = trim((string)($_POST['lastname'] ?? ''));
        $email = trim((string)($_POST['email'] ?? ''));
        $gender = strtolower(trim((string)($_POST['gender'] ?? '')));
        if ($gender === '') {
            $gender = strtolower(trim((string)($user['gender'] ?? '')));
        }
        if (!in_array($gender, array('male', 'female', 'other'), true)) {
            $gender = strtolower(trim((string)($user['gender'] ?? '')));
        }
        $address = trim((string)($_POST['address'] ?? ''));
        $mobile = trim((string)($_POST['mobile_no'] ?? ''));
        $newPassword = trim((string)($_POST['new_password'] ?? ''));
        $confirmPassword = trim((string)($_POST['confirm_password'] ?? ''));

        if ($username === '' || $firstName === '' || $lastName === '' || $email === '') {
            $message = 'Username, first name, last name, and email are required.';
            $messageType = 'danger';
        } elseif ($username !== $user['username'] && !empty($fcObj->userCheck($tbUsers, $username))) {
            $message = 'That username is already taken.';
            $messageType = 'danger';
        } elseif ($newPassword !== '' && $newPassword !== $confirmPassword) {
            $message = 'New password and confirm password do not match.';
            $messageType = 'danger';
        } elseif ($newPassword !== '' && strlen($newPassword) < 8) {
            $message = 'New password must be at least 8 characters long.';
            $messageType = 'danger';
        } else {
            $passwordToStore = $user['password'];
            $imageToStore = trim((string)$user['image']);
            $uploadedImagePath = '';
            $isNewImageUploaded = false;

            if ($newPassword !== '') {
                $passwordToStore = $fcObj->hashPassword($newPassword);
            }

            if (isset($_FILES['profile_image']) && is_uploaded_file($_FILES['profile_image']['tmp_name'])) {
                $uploadDir = ROOT_PATH . '/public/assets/images/users/';
                $safeAdmission = preg_replace('/[^a-zA-Z0-9_-]/', '', (string)$user['admission_id']);
                $uploadError = '';
                $uploadedFile = app_store_uploaded_image($_FILES['profile_image'], $uploadDir, 'user_' . $safeAdmission, $uploadError, 2 * 1024 * 1024);

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
                    'username' => $username,
                    'password' => $passwordToStore,
                    'mail_id' => $email,
                    'firstname' => $firstName,
                    'lastname' => $lastName,
                    'gender' => $gender,
                    'address' => $address,
                    'mobile_no' => $mobile,
                    'batch_id' => $user['batch_id'],
                    'stream_id' => $user['stream_id'],
                    'section' => $user['section'],
                    'admission_id' => $user['admission_id'],
                    'image' => $imageToStore
                );

                $updated = $fcObj->changeUserProfile($tbUsers, $varArray, $_SESSION['userName']);
                if ($updated !== false) {
                    $_SESSION['userName'] = $username;
                    $_SESSION['firstName'] = $firstName;
                    $_SESSION['image'] = $imageToStore;
                    if ((int)$updated === 0) {
                        $message = 'No changes to update.';
                        $messageType = 'info';
                    } else {
                        $message = 'Your profile has been updated successfully.';
                        $messageType = 'success';
                    }
                    $userData = $fcObj->userCheck($tbUsers, $_SESSION['userName']);
                    $user = $userData[0];
                    $currentProfileImage = trim((string)$user['image']);
                    $currentProfileImageUrl = $currentProfileImage !== '' ? BASE_URL . '/public/assets/images/users/' . rawurlencode($currentProfileImage) : '';
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

$streams = $fcObj->getStreams(TB_STREAM);
$userStreamName = 'N/A';
foreach ($streams as $stream) {
    if ((int)$stream['id'] === (int)$user['stream_id']) {
        $userStreamName = $stream['stream_name'] . ' (' . $stream['stream_code'] . ')';
        break;
    }
}

$classSection = $fcObj->getClsBySec(TB_SECTION, $user['section']);
$className = !empty($classSection) ? $classSection[0]['class_name'] : 'N/A';
$sectionName = !empty($classSection) ? $classSection[0]['section_name'] : 'N/A';
$profileDisplayName = trim((string)$user['firstname'] . ' ' . (string)$user['lastname']);
if ($profileDisplayName === '') {
    $profileDisplayName = (string)$user['username'];
}
$profileInitials = strtoupper(substr((string)$user['firstname'], 0, 1) . substr((string)$user['lastname'], 0, 1));
if ($profileInitials === '') {
    $profileInitials = strtoupper(substr((string)$user['username'], 0, 1));
}

include_once(INCLUDES_PATH . '/header.php');

$userActivePage = 'profile';
include_once(__DIR__ . '/layout/main_header.php');
?>
<style>
.student-page{--sp-primary:#173d69;--sp-primary-deep:#13345a;--sp-accent:#f0b323;--sp-accent-deep:#d79a12;--sp-surface:#eef4fa;--sp-card:#fff;--sp-border:#d8e3ef;--sp-text:#284767;--sp-muted:#6b819c;display:grid;gap:20px;padding-bottom:28px}.student-hero{position:relative;overflow:hidden;border:1px solid var(--sp-border);border-radius:26px;padding:28px;background:radial-gradient(circle at top right,rgba(240,179,35,.18),transparent 30%),linear-gradient(135deg,#f9fbfe 0%,var(--sp-surface) 100%);box-shadow:0 18px 36px rgba(15,23,42,.08)}.student-hero:before{content:"";position:absolute;inset:0 auto 0 0;width:7px;background:linear-gradient(180deg,var(--sp-accent),var(--sp-accent-deep))}.student-hero-grid{display:grid;grid-template-columns:160px minmax(0,1fr);gap:22px;align-items:center}.student-avatar{width:160px;height:180px;border-radius:24px;overflow:hidden;border:4px solid rgba(255,255,255,.95);background:linear-gradient(180deg,#dae6f3 0%,#c4d5e7 100%);box-shadow:0 18px 34px rgba(19,52,90,.16);display:flex;align-items:center;justify-content:center}.student-avatar img{width:100%;height:100%;object-fit:cover}.student-avatar-fallback{width:100%;height:100%;display:flex;align-items:center;justify-content:center;color:var(--sp-primary-deep);font-size:52px;font-weight:800;letter-spacing:.08em}.student-kicker,.student-tag{display:inline-flex;align-items:center;gap:8px;padding:8px 14px;border-radius:999px;background:rgba(23,61,105,.08);color:var(--sp-primary);font-size:12px;font-weight:800;letter-spacing:.08em;text-transform:uppercase}.student-kicker:before{content:"";width:8px;height:8px;border-radius:999px;background:linear-gradient(135deg,var(--sp-accent),var(--sp-accent-deep))}.student-hero h1{margin:14px 0 10px;color:var(--sp-primary-deep);font-size:clamp(28px,4vw,42px);font-weight:800;line-height:1.04;letter-spacing:-.04em}.student-hero p{margin:0;max-width:760px;color:var(--sp-muted);font-size:15px;line-height:1.7}.student-meta-line{display:flex;flex-wrap:wrap;gap:10px;margin-top:16px}.student-meta-pill{display:inline-flex;align-items:center;gap:8px;padding:10px 14px;border-radius:999px;border:1px solid #d5e1ee;background:rgba(255,255,255,.88);color:var(--sp-text);font-size:14px;font-weight:700}.student-meta-pill strong{color:var(--sp-primary)}.student-layout-grid{display:grid;grid-template-columns:minmax(0,1fr) minmax(260px,.42fr);gap:20px}.student-panel{border:1px solid var(--sp-border);border-radius:22px;background:var(--sp-card);box-shadow:0 12px 24px rgba(15,23,42,.06);padding:22px}.student-panel-header{display:flex;align-items:flex-start;justify-content:space-between;gap:14px;margin-bottom:18px}.student-panel-title{margin:0;color:var(--sp-primary-deep);font-size:22px;font-weight:800;letter-spacing:-.03em}.student-panel-subtitle{margin:6px 0 0;color:var(--sp-muted);font-size:14px}.student-alert{border-radius:16px;border:none;padding:14px 16px;font-weight:600}.student-alert.alert-success{background:#ecf7ef;color:#12653a}.student-alert.alert-danger{background:#fff0f0;color:#9a2a2a}.student-alert.alert-info{background:#edf5ff;color:#1f568d}.student-form-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px}.student-field{display:grid;gap:8px}.student-field.full{grid-column:1/-1}.student-label{color:var(--sp-primary);font-size:13px;font-weight:800;letter-spacing:.05em;text-transform:uppercase}.student-input,.student-select,.student-textarea{width:100%;border:1px solid #d5e1ee;border-radius:15px;padding:13px 15px;background:#fff;color:var(--sp-text);font-size:15px;transition:border-color .2s ease,box-shadow .2s ease}.student-input:focus,.student-select:focus,.student-textarea:focus{border-color:#88aacf;box-shadow:0 0 0 4px rgba(23,61,105,.08);outline:none}.student-textarea{min-height:112px;resize:vertical}.student-inline-options{display:flex;flex-wrap:wrap;gap:16px;padding:10px 4px}.student-radio{display:inline-flex;align-items:center;gap:8px;color:var(--sp-text);font-weight:600}.student-radio input{accent-color:var(--sp-primary)}.student-file-picker{display:grid;grid-template-columns:auto minmax(0,1fr);gap:10px;align-items:center}.student-file-btn,.student-primary-btn{display:inline-flex;align-items:center;justify-content:center;border:none;border-radius:14px;padding:12px 18px;font-weight:800;cursor:pointer;text-decoration:none}.student-file-btn{background:#e8f0f8;color:var(--sp-primary)}.student-primary-btn{background:linear-gradient(135deg,var(--sp-primary),var(--sp-primary-deep));color:#fff;box-shadow:0 14px 24px rgba(23,61,105,.18)}.student-readonly-grid{display:grid;gap:12px}.student-readonly-card{border:1px solid #e2ebf5;border-radius:18px;background:linear-gradient(180deg,#fbfdff 0%,#f6f9fc 100%);padding:16px}.student-readonly-label{margin:0 0 6px;color:var(--sp-muted);font-size:12px;font-weight:800;letter-spacing:.06em;text-transform:uppercase}.student-readonly-value{margin:0;color:var(--sp-primary-deep);font-size:16px;font-weight:800;line-height:1.5}.student-help{color:var(--sp-muted);font-size:13px;line-height:1.6;margin-top:8px}.student-actions{margin-top:20px;display:flex;flex-wrap:wrap;gap:12px}
@media(max-width:1199px){.student-layout-grid{grid-template-columns:1fr}}
@media(max-width:767px){.student-page{gap:16px}.student-hero,.student-panel{padding:18px;border-radius:20px}.student-hero-grid,.student-form-grid,.student-file-picker{grid-template-columns:1fr}.student-avatar{margin:0 auto;width:150px;height:170px}.student-actions,.student-inline-options,.student-meta-line{justify-content:flex-start}.student-panel-header{flex-direction:column;align-items:flex-start}}
</style>
<div class="student-page">
    <section class="student-hero">
        <div class="student-hero-grid">
            <div class="student-avatar">
                <?php if ($currentProfileImageUrl !== '') { ?>
                    <img src="<?php echo htmlspecialchars($currentProfileImageUrl); ?>" alt="<?php echo htmlspecialchars($profileDisplayName); ?>" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="student-avatar-fallback" style="display:none;"><?php echo htmlspecialchars($profileInitials); ?></div>
                <?php } else { ?>
                    <div class="student-avatar-fallback"><?php echo htmlspecialchars($profileInitials); ?></div>
                <?php } ?>
            </div>
            <div>
                <span class="student-kicker">Account Settings</span>
                <h1><?php echo htmlspecialchars(strtoupper($profileDisplayName)); ?></h1>
                <p>Update your account details, profile photo, and password from one cleaner student profile workspace that also adapts better on phones.</p>
                <div class="student-meta-line">
                    <span class="student-meta-pill"><strong>Roll</strong> <?php echo htmlspecialchars((string)$user['admission_id']); ?></span>
                    <span class="student-meta-pill"><strong>Class</strong> <?php echo htmlspecialchars($className); ?></span>
                    <span class="student-meta-pill"><strong>Section</strong> <?php echo htmlspecialchars($sectionName); ?></span>
                </div>
            </div>
        </div>
    </section>

    <?php if ($message !== '') { ?>
        <div class="student-alert alert alert-<?php echo $messageType; ?>"><?php echo htmlspecialchars($message); ?></div>
    <?php } ?>

    <section class="student-layout-grid">
        <div class="student-panel">
            <div class="student-panel-header">
                <div><h2 class="student-panel-title">Edit My Details</h2><p class="student-panel-subtitle">Your personal details stay connected to the current student account and class allocation.</p></div>
                <span class="student-tag">Profile Form</span>
            </div>

            <form method="POST" action="" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(app_get_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
                <div class="student-form-grid">
                    <div class="student-field full">
                        <label class="student-label">Update Profile Photo</label>
                        <div class="student-file-picker">
                            <button type="button" class="student-file-btn" id="profile_file_btn">Choose File</button>
                            <input type="text" class="student-input" id="profile_file_name" value="No file chosen" readonly>
                        </div>
                        <input type="file" name="profile_image" id="profile_image" class="d-none" accept=".jpg,.jpeg,.png,.webp">
                        <div class="student-help">Allowed: JPG, JPEG, PNG, WEBP. Maximum size: 2MB.</div>
                    </div>

                    <div class="student-field"><label class="student-label">Username</label><input type="text" name="username" class="student-input" value="<?php echo htmlspecialchars($user['username']); ?>" required></div>
                    <div class="student-field"><label class="student-label">Roll Number</label><input type="text" class="student-input" value="<?php echo htmlspecialchars($user['admission_id']); ?>" readonly></div>
                    <div class="student-field"><label class="student-label">First Name</label><input type="text" name="firstname" class="student-input" value="<?php echo htmlspecialchars($user['firstname']); ?>" required></div>
                    <div class="student-field"><label class="student-label">Last Name</label><input type="text" name="lastname" class="student-input" value="<?php echo htmlspecialchars($user['lastname']); ?>" required></div>
                    <div class="student-field"><label class="student-label">Email</label><input type="email" name="email" class="student-input" value="<?php echo htmlspecialchars($user['mail_id']); ?>" required></div>
                    <div class="student-field"><label class="student-label">Mobile</label><input type="text" name="mobile_no" class="student-input" value="<?php echo htmlspecialchars($user['mobile_no']); ?>"></div>
                    <div class="student-field full"><label class="student-label">Gender</label><div class="student-inline-options"><?php $genderValue = strtolower((string)($user['gender'] ?? '')); ?><label class="student-radio"><input type="radio" name="gender" value="Male" <?php echo ($genderValue === 'male') ? 'checked' : ''; ?>> Male</label><label class="student-radio"><input type="radio" name="gender" value="Female" <?php echo ($genderValue === 'female') ? 'checked' : ''; ?>> Female</label><label class="student-radio"><input type="radio" name="gender" value="Other" <?php echo ($genderValue === 'other') ? 'checked' : ''; ?>> Other</label></div></div>
                    <div class="student-field full"><label class="student-label">Address</label><textarea name="address" class="student-textarea"><?php echo htmlspecialchars($user['address']); ?></textarea></div>
                    <div class="student-field"><label class="student-label">New Password</label><input type="password" name="new_password" class="student-input" placeholder="Leave empty to keep current password"></div>
                    <div class="student-field"><label class="student-label">Confirm Password</label><input type="password" name="confirm_password" class="student-input" placeholder="Retype new password"></div>
                </div>
                <div class="student-actions"><button type="submit" name="update_profile" class="student-primary-btn">Update Profile</button></div>
            </form>
        </div>

        <aside class="student-panel">
            <div class="student-panel-header">
                <div><h2 class="student-panel-title">Academic Snapshot</h2><p class="student-panel-subtitle">Read-only academic details linked to your account.</p></div>
                <span class="student-tag">Student</span>
            </div>
            <div class="student-readonly-grid">
                <div class="student-readonly-card"><p class="student-readonly-label">Stream</p><p class="student-readonly-value"><?php echo htmlspecialchars($userStreamName); ?></p></div>
                <div class="student-readonly-card"><p class="student-readonly-label">Class</p><p class="student-readonly-value"><?php echo htmlspecialchars($className); ?></p></div>
                <div class="student-readonly-card"><p class="student-readonly-label">Section</p><p class="student-readonly-value"><?php echo htmlspecialchars($sectionName); ?></p></div>
                <div class="student-readonly-card"><p class="student-readonly-label">Email on Record</p><p class="student-readonly-value"><?php echo htmlspecialchars((string)$user['mail_id']); ?></p></div>
            </div>
        </aside>
    </section>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var fileInput = document.getElementById('profile_image');
    var fileButton = document.getElementById('profile_file_btn');
    var fileName = document.getElementById('profile_file_name');
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
