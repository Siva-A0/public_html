<?php require_once(__DIR__ . '/../../config.php'); ?>
<?php

if (!isset($adminExtraStyles) || !is_array($adminExtraStyles)) {
    $adminExtraStyles = array();
}
$adminExtraStyles[] = BASE_URL . '/public/assets/css/admin/admin_misc_pages.css';

include_once('../layout/main_header.php');
include_once('../layout/core_forms_style.php');

require_once(LIB_PATH . '/functions.class.php');
require_once(LIB_PATH . '/security.php');

$fcObj = new DataFunctions();
$tbAdmin = ADMIN_TABLE;
$adminId = (int)($_SESSION['adminId'] ?? 0);
$profileRows = $fcObj->getAdminById($tbAdmin, $adminId);

if (empty($profileRows)) {
    header('Location: ' . BASE_URL . '/admin/logout.php');
    exit;
}

$profile = $profileRows[0];
$msg = '';
$msgType = 'success';

$formData = array(
    'adminname' => trim((string)($profile['adminname'] ?? '')),
    'firstname' => trim((string)($profile['firstname'] ?? '')),
    'lastname' => trim((string)($profile['lastname'] ?? '')),
    'mail_id' => trim((string)($profile['mail_id'] ?? '')),
    'gender' => trim((string)($profile['gender'] ?? '')),
    'address' => trim((string)($profile['address'] ?? '')),
    'mobile_no' => trim((string)($profile['mobile_no'] ?? '')),
    'qualification' => trim((string)($profile['qualification'] ?? '')),
    'image' => trim((string)($profile['image'] ?? ''))
);

if (isset($_POST['saveProfileSettings'])) {
    if (!app_validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $msg = 'Your session expired. Please try again.';
        $msgType = 'danger';
    } else {
        $formData['adminname'] = trim((string)($_POST['adminname'] ?? ''));
        $formData['firstname'] = trim((string)($_POST['firstname'] ?? ''));
        $formData['lastname'] = trim((string)($_POST['lastname'] ?? ''));
        $formData['mail_id'] = trim((string)($_POST['mail_id'] ?? ''));
        $formData['gender'] = trim((string)($_POST['gender'] ?? ''));
        $formData['address'] = trim((string)($_POST['address'] ?? ''));
        $formData['mobile_no'] = trim((string)($_POST['mobile_no'] ?? ''));
        $formData['qualification'] = trim((string)($_POST['qualification'] ?? ''));

        $newPassword = (string)($_POST['adminPassWord'] ?? '');
        $confirmPassword = (string)($_POST['adminCPassWord'] ?? '');
        $passwordHash = (string)($profile['password'] ?? '');

        if (
            $formData['adminname'] === '' ||
            $formData['firstname'] === '' ||
            $formData['mail_id'] === ''
        ) {
            $msg = 'Username, first name, and email are required.';
            $msgType = 'danger';
        } elseif (!filter_var($formData['mail_id'], FILTER_VALIDATE_EMAIL)) {
            $msg = 'Please enter a valid email address.';
            $msgType = 'danger';
        } elseif ($formData['mobile_no'] !== '' && !preg_match('/^[0-9+\-\s]{7,20}$/', $formData['mobile_no'])) {
            $msg = 'Please enter a valid mobile number.';
            $msgType = 'danger';
        } elseif (!empty($fcObj->adminNameExistsForOther($tbAdmin, $formData['adminname'], $adminId))) {
            $msg = 'That username is already in use by another admin.';
            $msgType = 'danger';
        } elseif (($newPassword !== '' || $confirmPassword !== '') && $newPassword !== $confirmPassword) {
            $msg = 'Password and confirm password must match.';
            $msgType = 'danger';
        } elseif ($newPassword !== '' && strlen($newPassword) < 8) {
            $msg = 'Password must be at least 8 characters long.';
            $msgType = 'danger';
        } else {
            if ($newPassword !== '') {
                $passwordHash = $fcObj->hashPassword($newPassword);
            }

            $imageName = $formData['image'];

            if (isset($_FILES['adminImage']) && (int)($_FILES['adminImage']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
                $uploadError = '';
                $uploadedImage = app_store_uploaded_image(
                    $_FILES['adminImage'],
                    ROOT_PATH . '/public/assets/images/admin/',
                    'admin_' . $adminId,
                    $uploadError,
                    4 * 1024 * 1024
                );

                if ($uploadedImage === '') {
                    $msg = $uploadError !== '' ? $uploadError : 'Unable to upload the admin image.';
                    $msgType = 'danger';
                } else {
                    $imageName = $uploadedImage;
                }
            }

            if ($msg === '') {
                $updated = $fcObj->updateAdminProfile($tbAdmin, $adminId, array(
                    'adminname' => $formData['adminname'],
                    'password' => $passwordHash,
                    'mail_id' => $formData['mail_id'],
                    'firstname' => $formData['firstname'],
                    'lastname' => $formData['lastname'],
                    'gender' => $formData['gender'],
                    'address' => $formData['address'],
                    'mobile_no' => $formData['mobile_no'],
                    'qualification' => $formData['qualification'],
                    'image' => $imageName
                ));

                if ($updated) {
                    $formData['image'] = $imageName;
                    $profile['password'] = $passwordHash;
                    $_SESSION['adminName'] = $formData['adminname'];
                    $_SESSION['adminFirstName'] = $formData['firstname'];
                    $_SESSION['adminImage'] = $formData['image'];
                    $msg = 'Profile settings updated successfully.';
                    $msgType = 'success';
                } else {
                    $msg = 'Profile settings could not be updated. Please try again.';
                    $msgType = 'danger';
                }
            }
        }
    }
}

$adminImageFile = trim((string)($formData['image'] ?? ''));
$defaultAdminImage = 'ithod.png';
$adminImageDiskPath = ROOT_PATH . '/public/assets/images/admin/' . ($adminImageFile !== '' ? $adminImageFile : $defaultAdminImage);
$adminImageWebPath = BASE_URL . '/public/assets/images/admin/' . rawurlencode($adminImageFile !== '' ? $adminImageFile : $defaultAdminImage);

if (!file_exists($adminImageDiskPath)) {
    $adminImageWebPath = BASE_URL . '/public/assets/images/admin/' . $defaultAdminImage;
}
?>

<div class="profile-settings-page">
    <div class="page-hero">
        <h3 class="page-title">Profile Settings</h3>
        <p class="page-subtitle">Manage your admin profile details, photo, and password from one place.</p>
    </div>
</div>

<div id="page" class="profile-settings-shell">
    <div id="content" class="profile-settings-content">
        <div class="post">
            <span class="alignCenter">
                <h4>AIML Department</h4>
            </span>
        </div>
        <div id='content_left' class='content_left'></div>
        <div id='content_right' class='content_right profile-settings-main'>
            <div class="comteeMem">
                <?php if ($msg !== '') { ?>
                    <div class="comteeMemRow">
                        <div class="usersDetHeader <?php echo htmlspecialchars($msgType, ENT_QUOTES, 'UTF-8'); ?>">
                            <?php echo $msg; ?>
                        </div>
                    </div>
                <?php } ?>

                <form action="changepassword.php" method="POST" enctype="multipart/form-data" class="profile-settings-form">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(app_get_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">

                    <div class="profile-grid">
                        <div class="profile-card">
                            <img src="<?php echo htmlspecialchars($adminImageWebPath, ENT_QUOTES, 'UTF-8'); ?>" alt="Admin image" class="profile-preview-image">
                            <div class="profile-preview-name">
                                <?php echo htmlspecialchars(trim($formData['firstname'] . ' ' . $formData['lastname']) !== '' ? trim($formData['firstname'] . ' ' . $formData['lastname']) : $formData['adminname'], ENT_QUOTES, 'UTF-8'); ?>
                            </div>
                            <div class="profile-preview-email">
                                <?php echo htmlspecialchars($formData['mail_id'], ENT_QUOTES, 'UTF-8'); ?>
                            </div>
                            <div class="profile-upload-note">
                                Upload a JPG, PNG, or WEBP profile image up to 4 MB.
                            </div>
                        </div>

                        <div class="profile-form-panel">
                            <h4 class="profile-section-title">Admin Details</h4>
                            <div class="profile-form-note">
                                Leave the password fields empty if you only want to update your profile information.
                            </div>

                            <div class="form-columns">
                                <div class="form_row">
                                    <div class="form_label">
                                        <label for="adminname">Username :</label>
                                    </div>
                                    <div class="form_field">
                                        <input type="text" name="adminname" id="adminname" value="<?php echo htmlspecialchars($formData['adminname'], ENT_QUOTES, 'UTF-8'); ?>" required>
                                    </div>
                                </div>

                                <div class="form_row">
                                    <div class="form_label">
                                        <label for="mail_id">Email :</label>
                                    </div>
                                    <div class="form_field">
                                        <input type="email" name="mail_id" id="mail_id" value="<?php echo htmlspecialchars($formData['mail_id'], ENT_QUOTES, 'UTF-8'); ?>" required>
                                    </div>
                                </div>

                                <div class="form_row">
                                    <div class="form_label">
                                        <label for="firstname">First Name :</label>
                                    </div>
                                    <div class="form_field">
                                        <input type="text" name="firstname" id="firstname" value="<?php echo htmlspecialchars($formData['firstname'], ENT_QUOTES, 'UTF-8'); ?>" required>
                                    </div>
                                </div>

                                <div class="form_row">
                                    <div class="form_label">
                                        <label for="lastname">Last Name :</label>
                                    </div>
                                    <div class="form_field">
                                        <input type="text" name="lastname" id="lastname" value="<?php echo htmlspecialchars($formData['lastname'], ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                </div>

                                <div class="form_row">
                                    <div class="form_label">
                                        <label for="gender">Gender :</label>
                                    </div>
                                    <div class="form_field">
                                        <select name="gender" id="gender">
                                            <option value="">Select</option>
                                            <option value="Male" <?php echo $formData['gender'] === 'Male' ? 'selected' : ''; ?>>Male</option>
                                            <option value="Female" <?php echo $formData['gender'] === 'Female' ? 'selected' : ''; ?>>Female</option>
                                            <option value="Other" <?php echo $formData['gender'] === 'Other' ? 'selected' : ''; ?>>Other</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form_row">
                                    <div class="form_label">
                                        <label for="mobile_no">Mobile Number :</label>
                                    </div>
                                    <div class="form_field">
                                        <input type="text" name="mobile_no" id="mobile_no" value="<?php echo htmlspecialchars($formData['mobile_no'], ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                </div>

                                <div class="form_row">
                                    <div class="form_label">
                                        <label for="qualification">Qualification :</label>
                                    </div>
                                    <div class="form_field">
                                        <input type="text" name="qualification" id="qualification" value="<?php echo htmlspecialchars($formData['qualification'], ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                </div>

                                <div class="form_row">
                                    <div class="form_label">
                                        <label for="adminImage">Profile Image :</label>
                                    </div>
                                    <div class="form_field">
                                        <input type="file" name="adminImage" id="adminImage" accept=".jpg,.jpeg,.png,.webp">
                                    </div>
                                </div>
                            </div>

                            <div class="form_row">
                                <div class="form_label">
                                    <label for="address">Address :</label>
                                </div>
                                <div class="form_field">
                                    <textarea name="address" id="address"><?php echo htmlspecialchars($formData['address'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                                </div>
                            </div>

                            <div class="profile-password-block">
                                <h4 class="profile-section-title">Change Password</h4>

                                <div class="form-columns">
                                    <div class="form_row">
                                        <div class="form_label">
                                            <label for="adminPassWord">New Password :</label>
                                        </div>
                                        <div class="form_field">
                                            <input type="password" name="adminPassWord" id="adminPassWord" autocomplete="new-password">
                                        </div>
                                    </div>

                                    <div class="form_row">
                                        <div class="form_label">
                                            <label for="adminCPassWord">Confirm Password :</label>
                                        </div>
                                        <div class="form_field">
                                            <input type="password" name="adminCPassWord" id="adminCPassWord" autocomplete="new-password">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <br class="clearfix" />
                            <div class="form_row profile-form-actions">
                                <div class="form_label"></div>
                                <div class="form_field">
                                    <input type="submit" name="saveProfileSettings" id="saveProfileSettings" class="button" value="Save Profile Settings">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <br class="clearfix" />
    </div>
    <?php include_once('../layout/sidebar.php'); ?>
    <br class="clearfix" />
</div>
</div>
<?php include_once('../layout/footer.php'); ?>

