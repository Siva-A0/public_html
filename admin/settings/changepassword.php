<?php require_once(__DIR__ . '/../../config.php'); ?>
<?php

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
<style type="text/css">
    .profile-settings-page .page-hero {
        position: relative;
        overflow: hidden;
        border: 1px solid #d9e3ef;
        border-radius: 18px;
        padding: 18px 22px;
        background:
            linear-gradient(135deg, #f9fbfe 0%, #eef4fa 100%);
        box-shadow: 0 14px 30px rgba(15, 23, 42, 0.08);
        margin-bottom: 16px;
    }

        .profile-settings-page .page-hero::before {
        content: "";
        position: absolute;
        inset: 0 auto 0 0;
        width: 6px;
        background: linear-gradient(180deg, #f0b323, #d79a12);
    }

.profile-settings-page .page-title {
        margin: 0;
        font-size: 32px;
        font-weight: 800;
        letter-spacing: -0.6px;
        color: #13345a;
    }

    .profile-settings-page .page-subtitle {
        margin: 8px 0 0;
        font-size: 15px;
        color: #6b819c;
    }

    #content .post h4,
    #content_left {
        display: none;
    }

    #content {
        grid-template-columns: minmax(320px, 860px);
        justify-content: center;
        gap: 0;
    }

    #content_right .comteeMem {
        max-width: 860px;
        margin: 0 auto;
        border: 1px solid #d9e3ef;
        border-radius: 16px;
        box-shadow: 0 10px 22px rgba(15, 23, 42, 0.06);
        padding: 24px;
    }

    #content_right .usersDetHeader {
        font-size: 16px;
        border-radius: 12px;
        padding: 12px 14px;
        border: 1px solid #dbe5f3;
        background: #f8fbff;
    }

    #content_right .usersDetHeader.danger {
        border-color: #fecaca;
        background: #fff1f2;
        color: #991b1b;
    }

    #content_right .usersDetHeader.success {
        border-color: #bbf7d0;
        background: #f0fdf4;
        color: #166534;
    }

    .profile-grid {
        display: grid;
        grid-template-columns: 220px minmax(0, 1fr);
        gap: 24px;
        align-items: start;
    }

    .profile-card {
        border: 1px solid #dbe5f3;
        border-radius: 16px;
        padding: 18px;
        background: linear-gradient(180deg, #fbfdff 0%, #f3f8fe 100%);
    }

    .profile-preview-image {
        width: 128px;
        height: 128px;
        border-radius: 50%;
        object-fit: cover;
        display: block;
        margin: 0 auto 14px;
        border: 4px solid #fff;
        box-shadow: 0 12px 24px rgba(15, 23, 42, 0.12);
        background: #d9e3ef;
    }

    .profile-preview-name {
        text-align: center;
        font-size: 18px;
        font-weight: 700;
        color: #13345a;
    }

    .profile-preview-email {
        text-align: center;
        margin-top: 4px;
        font-size: 13px;
        color: #6b819c;
        word-break: break-word;
    }

    .profile-upload-note,
    .profile-form-note {
        margin-top: 12px;
        padding: 10px 12px;
        border: 1px dashed #d9e3ef;
        border-radius: 12px;
        background: linear-gradient(90deg, #f9fbfe, #eef4fa);
        color: #6b819c;
        font-size: 13px;
    }

    .profile-section-title {
        margin: 0 0 14px;
        font-size: 20px;
        font-weight: 800;
        color: #13345a;
    }

    .form-columns {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0 16px;
    }

    #content_right form .form_label label {
        font-size: 16px;
        font-weight: 700;
        color: #173d69;
    }

    #content_right form .form_field input[type="text"],
    #content_right form .form_field input[type="email"],
    #content_right form .form_field input[type="password"],
    #content_right form .form_field input[type="file"],
    #content_right form .form_field select,
    #content_right form .form_field textarea {
        width: 100%;
        min-height: 54px;
        border: 1px solid #c8d6e6;
        border-radius: 12px;
        padding: 12px 14px;
        background: #f6faff;
        font-size: 15px;
        outline: none;
    }

    #content_right form .form_field textarea {
        min-height: 120px;
        resize: vertical;
    }

    #content_right form .form_field input:focus,
    #content_right form .form_field select:focus,
    #content_right form .form_field textarea:focus {
        border-color: #87a6cb;
        background: #fff;
        box-shadow: 0 0 0 4px rgba(23, 61, 105, 0.12);
    }

    .profile-password-block {
        margin-top: 22px;
        padding-top: 18px;
        border-top: 1px solid #dbe5f3;
    }

    #content_right #saveProfileSettings.button {
        font-size: 16px;
        padding: 11px 22px;
        border-radius: 12px;
        background: linear-gradient(135deg, #13345a, #173d69);
        box-shadow: 0 10px 20px rgba(16, 42, 72, 0.24);
    }

    #content_right #saveProfileSettings.button:hover {
        filter: brightness(1.06);
    }

    @media (max-width: 860px) {
        .profile-grid,
        .form-columns {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .profile-settings-page .page-title {
            font-size: 26px;
        }
    }
</style>

<div class="profile-settings-page">
    <div class="page-hero">
        <h3 class="page-title">Profile Settings</h3>
        <p class="page-subtitle">Manage your admin profile details, photo, and password from one place.</p>
    </div>
</div>

<div id="page">
    <div id="content">
        <div class="post">
            <span class="alignCenter">
                <h4>AIML Department</h4>
            </span>
        </div>
        <div id='content_left' class='content_left'></div>
        <div id='content_right' class='content_right'>
            <div class="comteeMem">
                <?php if ($msg !== '') { ?>
                    <div class="comteeMemRow">
                        <div class="usersDetHeader <?php echo htmlspecialchars($msgType, ENT_QUOTES, 'UTF-8'); ?>">
                            <?php echo $msg; ?>
                        </div>
                    </div>
                <?php } ?>

                <form action="changepassword.php" method="POST" enctype="multipart/form-data">
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

                        <div>
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
                            <div class="form_row">
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
