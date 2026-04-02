<?php require_once(__DIR__ . '/../../config.php');?>

<?php
session_start();

if (!isset($_SESSION['adminId'])) {
    header("Location: index.php");
    exit;
}

// require_once("libraries/functions.class.php");
require_once(LIB_PATH . '/functions.class.php');
require_once(LIB_PATH . '/security.php');

$fcObj = new DataFunctions();

$tbStaffCateg = TB_STAFF_CATEGORY;
$tbStaff      = TB_STAFF;
$staffForm = array();
$defaultFacultyPassword = 'Nrcm@123';

/* ================= ADD STAFF LOGIC ================= */
if (isset($_POST['addNewStaff'])) {
    if (!app_validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $msg = 'Your session expired. Please try again.';
    } else {

    $varArray['staffType']     = (int)$_POST['staffType'];
    $varArray['firstName']     = trim($_POST['firstName']);
    $varArray['lastName']      = trim($_POST['lastName']);
    $varArray['staffQualif']   = str_replace(',', '\,', $_POST['staffQualif']);
    $varArray['staffDesig']    = $_POST['staffDesig'];
    $varArray['email']         = $_POST['email'];

    $varArray['indusExp']      = $_POST['indusExp'];
    $varArray['teachingExp']   = $_POST['teachingExp'];
    $varArray['research']      = $_POST['research'];

    $varArray['pub_nat']       = $_POST['pub_nat'];
    $varArray['pub_internat']  = $_POST['pub_internat'];

    $varArray['conf_nat']      = $_POST['conf_nat'];
    $varArray['conf_internat'] = $_POST['conf_internat'];

    /* Image Upload */
    $userName = $_POST['firstName'] . $_POST['lastName'];
    $fileName = '';

    if (
        !empty($_POST['staffImage_cropped'])
        || (!empty($_FILES['staffImage']['name']) && (int)($_FILES['staffImage']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK)
    ) {
        $uploadError = '';
        $fileName = app_store_processed_image(
            $_FILES['staffImage'],
            $_POST['staffImage_cropped'] ?? '',
            ROOT_PATH . '/public/assets/images/faculty/',
            strtolower(str_replace(' ', '', $userName)),
            $uploadError,
            2 * 1024 * 1024
        );

        if ($fileName === '') {
            $msg = $uploadError;
        }
    }

    if (!isset($msg)) {
        $varArray['image'] = $fileName;
        $varArray['password'] = $fcObj->hashPassword($defaultFacultyPassword);

        $addStaff = $fcObj->addStaffDetails($tbStaff, $varArray);

        if ($addStaff) {
            header("Location: ../Department/department.php");
            exit;
        } else {
            $msg = "Failed to add faculty member. Please try again.";
        }
    }
    }
}

$staffForm = array(
    'staffType' => isset($_POST['staffType']) ? (string)$_POST['staffType'] : '',
    'email' => isset($_POST['email']) ? (string)$_POST['email'] : '',
    'firstName' => isset($_POST['firstName']) ? (string)$_POST['firstName'] : '',
    'lastName' => isset($_POST['lastName']) ? (string)$_POST['lastName'] : '',
    'staffQualif' => isset($_POST['staffQualif']) ? (string)$_POST['staffQualif'] : '',
    'staffDesig' => isset($_POST['staffDesig']) ? (string)$_POST['staffDesig'] : '',
    'indusExp' => isset($_POST['indusExp']) ? (string)$_POST['indusExp'] : '',
    'teachingExp' => isset($_POST['teachingExp']) ? (string)$_POST['teachingExp'] : '',
    'research' => isset($_POST['research']) ? (string)$_POST['research'] : '',
    'pub_nat' => isset($_POST['pub_nat']) ? (string)$_POST['pub_nat'] : '',
    'pub_internat' => isset($_POST['pub_internat']) ? (string)$_POST['pub_internat'] : '',
    'conf_nat' => isset($_POST['conf_nat']) ? (string)$_POST['conf_nat'] : '',
    'conf_internat' => isset($_POST['conf_internat']) ? (string)$_POST['conf_internat'] : ''
);

if (!isset($adminExtraStyles) || !is_array($adminExtraStyles)) {
    $adminExtraStyles = array();
}
$adminExtraStyles[] = BASE_URL . '/public/assets/css/admin/admin_misc_pages.css';

include_once('../layout/main_header.php');

$staffCateg = $fcObj->getStaffCategories($tbStaffCateg);
?>

<div class="container-fluid add-staff-page">
    <div class="form-shell">

    <div class="page-hero">
        <h4 class="staff-title">Add New Faculty</h4>
        <!-- <p class="staff-subtitle">Create a complete profile including qualifications, experience, and publications.</p> -->
    </div>

    <?php if (isset($msg)) { ?>
        <div class="alert alert-danger">
            <?php echo htmlspecialchars($msg, ENT_QUOTES, 'UTF-8'); ?>
        </div>
    <?php } ?>

    <div class="card staff-form-card border-0">
        <div class="card-body">

            <form method="POST" enctype="multipart/form-data" id="addFacultyForm">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(app_get_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
                <input type="hidden" name="staffImage_cropped" id="staffImage_cropped" value="">

                <div class="section-title"><span class="section-dot"></span>Basic Details</div>
                <div class="section-box">
                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Faculty Type</label>
                        <select name="staffType" class="form-select" required>
                            <option value="">Select</option>
                            <?php foreach ($staffCateg as $cat) { ?>
                                <?php $catId = (string)$cat['id']; ?>
                                <option value="<?php echo htmlspecialchars($catId, ENT_QUOTES, 'UTF-8'); ?>" <?php if ($staffForm['staffType'] === $catId) echo 'selected'; ?>>
                                    <?php echo htmlspecialchars((string)$cat['category_name'], ENT_QUOTES, 'UTF-8'); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($staffForm['email'], ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">First Name</label>
                        <input type="text" name="firstName" class="form-control" value="<?php echo htmlspecialchars($staffForm['firstName'], ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Last Name</label>
                        <input type="text" name="lastName" class="form-control" value="<?php echo htmlspecialchars($staffForm['lastName'], ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Qualification</label>
                        <input type="text" name="staffQualif" class="form-control" value="<?php echo htmlspecialchars($staffForm['staffQualif'], ENT_QUOTES, 'UTF-8'); ?>">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Designation</label>
                        <input type="text" name="staffDesig" class="form-control" value="<?php echo htmlspecialchars($staffForm['staffDesig'], ENT_QUOTES, 'UTF-8'); ?>">
                    </div>
                </div>
                <div class="default-password-note">
                    Default faculty login password: <strong><?php echo htmlspecialchars($defaultFacultyPassword, ENT_QUOTES, 'UTF-8'); ?></strong>. Faculty can change it later from their profile page.
                </div>
                </div>

                <div class="section-title"><span class="section-dot"></span>Academic Profile</div>
                <div class="section-box">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Industry Experience</label>
                        <input type="text" name="indusExp" class="form-control" value="<?php echo htmlspecialchars($staffForm['indusExp'], ENT_QUOTES, 'UTF-8'); ?>">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Teaching Experience</label>
                        <input type="text" name="teachingExp" class="form-control" value="<?php echo htmlspecialchars($staffForm['teachingExp'], ENT_QUOTES, 'UTF-8'); ?>">
                    </div>

                    <div class="col-12">
                        <label class="form-label">Research</label>
                        <textarea name="research" class="form-control" rows="3"><?php echo htmlspecialchars($staffForm['research'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">National Publications</label>
                        <textarea name="pub_nat" class="form-control" rows="3"><?php echo htmlspecialchars($staffForm['pub_nat'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">International Publications</label>
                        <textarea name="pub_internat" class="form-control" rows="3"><?php echo htmlspecialchars($staffForm['pub_internat'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">National Conferences</label>
                        <textarea name="conf_nat" class="form-control" rows="3"><?php echo htmlspecialchars($staffForm['conf_nat'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">International Conferences</label>
                        <textarea name="conf_internat" class="form-control" rows="3"><?php echo htmlspecialchars($staffForm['conf_internat'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                    </div>
                </div>
                </div>

                    <div class="col-12">
                        <label class="form-label">Faculty Image</label>
                        <input type="file" name="staffImage" id="staffImage" class="form-control" accept=".jpg,.jpeg,.png,.webp">
                        <div class="square-cropper mt-3" id="staff_cropper" hidden>
                            <div class="square-cropper-stage" id="staff_cropper_stage">
                                <img id="staff_cropper_image" alt="Crop preview" draggable="false" style="display:none;">
                                <div class="square-cropper-frame"></div>
                            </div>
                            <div class="square-cropper-controls">
                                <label class="square-cropper-slider-row" for="staff_cropper_zoom">
                                    <span>Zoom</span>
                                    <input type="range" id="staff_cropper_zoom" class="square-cropper-slider" min="1" max="3" step="0.01" value="1">
                                    <span>3x</span>
                                </label>
                                <div class="square-cropper-actions">
                                    <button type="button" class="square-cropper-btn" id="staff_cropper_reset">Reset</button>
                                </div>
                            </div>
                            <div class="square-cropper-help">Drag the image to choose the square region that should appear for the faculty profile photo.</div>
                        </div>
                        <div class="upload-help">Allowed: JPG, PNG, WEBP</div>
                    </div>

                    <div class="col-12">
                        <div class="action-bar d-flex gap-2 flex-wrap">
                            <button type="submit" name="addNewStaff" class="btn btn-primary">
                                <i class="bi bi-person-plus me-1"></i> Add Faculty
                            </button>
                            <a href="../Department/department.php" class="btn btn-secondary">
                                Cancel
                            </a>
                        </div>
                    </div>

                </div>

            </form>

        </div>
    </div>
    </div>

</div>
<script src="<?php echo BASE_URL; ?>/public/assets/js/square-image-cropper.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    window.initSquareImageCropper({
        formId: 'addFacultyForm',
        fileInputId: 'staffImage',
        hiddenInputId: 'staffImage_cropped',
        wrapperId: 'staff_cropper',
        stageId: 'staff_cropper_stage',
        imageId: 'staff_cropper_image',
        sliderId: 'staff_cropper_zoom',
        resetButtonId: 'staff_cropper_reset'
    });
});
</script>
<?php include_once('../layout/footer.php'); ?>


