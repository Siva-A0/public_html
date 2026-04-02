<?php require_once(__DIR__ . '/../../config.php'); ?>
<?php
session_start();

if (!isset($_SESSION['adminId'])) {
    header('Location: ../index.php');
    exit;
}

if (!isset($adminExtraStyles) || !is_array($adminExtraStyles)) {
    $adminExtraStyles = array();
}
$adminExtraStyles[] = BASE_URL . '/public/assets/css/admin/admin_misc_pages.css';

require_once(LIB_PATH . '/functions.class.php');
require_once(LIB_PATH . '/security.php');

$fcObj = new DataFunctions();
$tbComtCtg = TB_COMT_CATEG;
$tbCmt = TB_COMMITTEE;

$categories = $fcObj->getComiteCatg($tbComtCtg);
$message = '';
$messageType = 'danger';
$editMember = null;
$editMemberId = (int)($_POST['edit_member_id'] ?? ($_GET['member'] ?? 0));

$formData = array(
    'cmtCat' => isset($_POST['cmtCat']) ? (string)$_POST['cmtCat'] : '',
    'member_name' => isset($_POST['member_name']) ? trim((string)$_POST['member_name']) : '',
    'member_about' => isset($_POST['member_about']) ? trim((string)$_POST['member_about']) : '',
    'member_image' => ''
);

if ($editMemberId > 0) {
    $memberRows = $fcObj->getCommitteeMemberById($tbCmt, $editMemberId);
    if (!empty($memberRows)) {
        $editMember = $memberRows[0];
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $formData['cmtCat'] = (string)$editMember['committee_cat_id'];
            $formData['member_name'] = trim((string)$editMember['member_name']);
            $formData['member_about'] = trim((string)$editMember['member_about']);
            $formData['member_image'] = trim((string)$editMember['member_image']);
        }
    }
}

if (isset($_POST['addCmtMember'])) {
    if (!app_validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $message = 'Your session expired. Please try again.';
    } else {
        $categoryId = (int)($formData['cmtCat'] ?? 0);
        $memberName = $formData['member_name'];
        $memberAbout = $formData['member_about'];
        $memberImage = '';

        if ($categoryId <= 0) {
            $message = 'Please select a valid category.';
        } elseif ($memberName === '') {
            $message = 'Please enter the member name.';
        } else {
            $memberImage = trim((string)($editMember['member_image'] ?? ''));
            if (
                !empty($_POST['member_photo_cropped'])
                || (isset($_FILES['member_photo']) && (int)($_FILES['member_photo']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK)
            ) {
                $uploadError = '';
                $newFileName = app_store_processed_image(
                    isset($_FILES['member_photo']) ? $_FILES['member_photo'] : null,
                    $_POST['member_photo_cropped'] ?? '',
                    ROOT_PATH . '/public/assets/images/students/',
                    'committee_' . $categoryId,
                    $uploadError,
                    2 * 1024 * 1024
                );

                if ($newFileName === '') {
                    $message = $uploadError;
                } else {
                    if ($memberImage !== '') {
                        $oldImagePath = ROOT_PATH . '/public/assets/images/students/' . $memberImage;
                        if (is_file($oldImagePath)) {
                            @unlink($oldImagePath);
                        }
                    }
                    $memberImage = $newFileName;
                }
            }

            if ($message === '') {
                $payload = array(
                    'committee_cat_id' => $categoryId,
                    'user_id' => 0,
                    'member_name' => $memberName,
                    'member_about' => $memberAbout,
                    'member_image' => $memberImage
                );

                $saveResult = $editMember
                    ? $fcObj->updateCommitteeMember($tbCmt, $editMemberId, $payload)
                    : $fcObj->addCommitteeMember($tbCmt, $payload);

                if ($saveResult === 'Successfully Added' || $saveResult === 'Successfully Updated') {
                    header('Location: assoc.php?status=' . ($editMember ? 'member_updated' : 'member_added'));
                    exit;
                }

                $message = 'Sorry, please try again.';
            }
        }
    }
}

include_once('../layout/main_header.php');
include_once('../layout/core_forms_style.php');
?>

<div id="page">
    <div id="content">
        <div class="post">
            <span class="alignCenter"></span>
            <p></p>
        </div>
        <div id='content_left' class='content_left'></div>
        <div id='content_right' class='content_right'>
            <div class="committee-form-shell">
                <div class="committee-add-hero">
                    <h3 class="committee-add-title"><?php echo $editMember ? 'Edit Association Member' : 'Add Association Member'; ?></h3>
                    <p class="committee-add-subtitle"></p>
                </div>

                <?php if ($message !== '') { ?>
                    <div class="alert alert-<?php echo $messageType; ?>">
                        <?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                <?php } ?>

                <?php if (empty($categories)) { ?>
                    <div class="alert alert-warning">
                        Create a category first before adding members.
                    </div>
                    <div class="committee-action-bar">
                        <a href="categories.php" class="button">Manage Categories</a>
                    </div>
                <?php } else { ?>
                    <div class="login">
                        <form id='addcommitteemem' action='addmem.php<?php echo $editMemberId > 0 ? ('?member=' . $editMemberId) : ''; ?>' method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(app_get_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
                            <input type="hidden" name="edit_member_id" value="<?php echo (int)$editMemberId; ?>">
                            <input type="hidden" name="member_photo_cropped" id="member_photo_cropped" value="">

                            <div class="form_row">
                                <div class="form_label">
                                    <label for='cmtCat'>Category:</label>
                                </div>
                                <div class="form_field">
                                    <select name="cmtCat" id="cmtCat" required>
                                        <option value="">Select category</option>
                                        <?php foreach ($categories as $category) { ?>
                                            <option value="<?php echo (int)$category['id']; ?>" <?php echo $formData['cmtCat'] === (string)$category['id'] ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars((string)$category['category_name'], ENT_QUOTES, 'UTF-8'); ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form_row">
                                <div class="form_label">
                                    <label for='memberName'>Member Name:</label>
                                </div>
                                <div class="form_field">
                                    <input type="text" id="memberName" name="member_name" value="<?php echo htmlspecialchars($formData['member_name'], ENT_QUOTES, 'UTF-8'); ?>" required />
                                </div>
                            </div>

                            <div class="form_row">
                                <div class="form_label">
                                    <label for='memberAbout'>About:</label>
                                </div>
                                <div class="form_field">
                                    <textarea id="memberAbout" name="member_about" rows="3"><?php echo htmlspecialchars($formData['member_about'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                                </div>
                            </div>

                            <div class="form_row">
                                <div class="form_label">
                                    <label for='memberPhotoUpload'>Profile Photo:</label>
                                </div>
                                <div class="form_field">
                                    <div class="member-upload-wrap">
                                        <input type="file" id="memberPhotoUpload" name="member_photo" accept=".jpg,.jpeg,.png,.gif,.webp" />
                                        <img
                                            id="memberPhoto"
                                            class="member-photo-preview square-cropper-preview"
                                            src="<?php echo $formData['member_image'] !== '' ? htmlspecialchars(BASE_URL . '/public/assets/images/students/' . rawurlencode($formData['member_image']), ENT_QUOTES, 'UTF-8') : ''; ?>"
                                            alt="Profile preview"
                                            <?php echo $formData['member_image'] !== '' ? '' : 'hidden'; ?>
                                        />
                                        <div id="memberPhotoPlaceholder" <?php echo $formData['member_image'] !== '' ? 'hidden' : ''; ?>>No profile photo selected.</div>
                                        <div class="square-cropper" id="member_cropper" hidden>
                                            <div class="square-cropper-stage" id="member_cropper_stage">
                                                <img id="member_cropper_image" alt="Crop preview" draggable="false" style="display:none;">
                                                <div class="square-cropper-frame"></div>
                                            </div>
                                            <div class="square-cropper-controls">
                                                <label class="square-cropper-slider-row" for="member_cropper_zoom">
                                                    <span>Zoom</span>
                                                    <input type="range" id="member_cropper_zoom" class="square-cropper-slider" min="1" max="3" step="0.01" value="1">
                                                    <span>3x</span>
                                                </label>
                                                <div class="square-cropper-actions">
                                                    <button type="button" class="square-cropper-btn" id="member_cropper_reset">Reset</button>
                                                </div>
                                            </div>
                                            <div class="square-cropper-help">Drag the image inside the square to choose the visible profile region.</div>
                                        </div>
                                        <div class="upload-hint">Allowed: JPG, PNG, GIF, WEBP</div>
                                    </div>
                                </div>
                            </div>

                            <div class="form_row">
                                <div class="form_label"></div>
                                <div class="form_field">
                                    <div class="committee-action-bar">
                                        <input type='submit' name='addCmtMember' class="button" value='<?php echo $editMember ? 'Update Member' : 'Add Member'; ?>' />
                                        <?php if ($editMember) { ?>
                                            <a href="addmem.php" class="button" style="text-decoration:none;">Cancel</a>
                                        <?php } ?>
                                        <a href="categories.php" class="button" style="text-decoration:none;">Manage Categories</a>
                                        <a href="assoc.php" class="button" style="text-decoration:none;">Back</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                <?php } ?>
            </div>
        </div>
        <br class="clearfix" />
    </div>
    <?php include_once('../layout/sidebar.php'); ?>
    <br class="clearfix" />
</div>

<script src="<?php echo BASE_URL; ?>/public/assets/js/square-image-cropper.js"></script>
<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function () {
    window.initSquareImageCropper({
        formId: 'addcommitteemem',
        fileInputId: 'memberPhotoUpload',
        hiddenInputId: 'member_photo_cropped',
        wrapperId: 'member_cropper',
        stageId: 'member_cropper_stage',
        imageId: 'member_cropper_image',
        sliderId: 'member_cropper_zoom',
        resetButtonId: 'member_cropper_reset',
        previewId: 'memberPhoto',
        emptyStateId: 'memberPhotoPlaceholder',
        initialImageUrl: <?php echo json_encode($formData['member_image'] !== '' ? (BASE_URL . '/public/assets/images/students/' . rawurlencode($formData['member_image'])) : '', JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>
    });
});
</script>

<?php include_once('../layout/footer.php'); ?>
