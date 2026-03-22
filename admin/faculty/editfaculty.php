<?php require_once(__DIR__ . '/../../config.php');?>
<?php
session_start();

if (!isset($_SESSION['adminId'])) {
    header("Location: ../index.php");
    exit;
}

require_once(LIB_PATH . '/functions.class.php');
require_once(LIB_PATH . '/security.php');

$fcObj = new DataFunctions();

$tbStaffCateg = TB_STAFF_CATEGORY;
$tbStaff      = TB_STAFF;

/* ---------------- GET STAFF DETAILS ---------------- */
if (isset($_GET['faculty'])) {
    $staffId = (int)$_GET['faculty'];
    $staffDetails = $fcObj->getStaffDetailsById($tbStaff, $staffId);
}

/* ---------------- UPDATE STAFF ---------------- */
if (isset($_POST['editStaffDetails'])) {
    if (!app_validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $msg = 'Your session expired. Please try again.';
    } else {

    $varArray['staffType']     = $_POST['staffType'];
    $varArray['firstName']     = $_POST['firstName'];
    $varArray['lastName']      = $_POST['lastName'];
    $varArray['staffQualif']   = $_POST['staffQualif'];
    $varArray['staffDesig']    = $_POST['staffDesig'];
    $varArray['email']         = $_POST['email'];

    $varArray['indusExp']      = $_POST['indusExp'];
    $varArray['teachingExp']   = $_POST['teachingExp'];
    $varArray['research']      = $_POST['research'];

    $varArray['pub_nat']       = $_POST['pub_nat'];
    $varArray['pub_internat']  = $_POST['pub_internat'];

    $varArray['conf_nat']      = $_POST['conf_nat'];
    $varArray['conf_internat'] = $_POST['conf_internat'];
    $staffPassword             = (string)($_POST['staffPassword'] ?? '');

    $previousImage = $_POST['imageName'];
    $staffId       = $_POST['staffId'];
    $existingPassword = (string)($_POST['existingPassword'] ?? '');

    if ($_FILES['staffImage']['error'] == 0) {

        if (file_exists("../../public/assets/images/staff/" . $previousImage)) {
            unlink("../../public/assets/images/staff/" . $previousImage);
        }

        $userName = $_POST['firstName'] . $_POST['lastName'];
        $fileName = strtolower(str_replace(' ', '', $userName)) . '.png';

        if (move_uploaded_file($_FILES['staffImage']['tmp_name'], "../../public/assets/images/staff/" . $fileName)) {
            $varArray['image'] = $fileName;
        } else {
            $varArray['image'] = '';
        }

    } else {
        $varArray['image'] = $previousImage;
    }

    if (!isset($msg)) {
        if ($staffPassword !== '' && strlen($staffPassword) < 8) {
            $msg = 'Faculty password must be at least 8 characters long.';
        } else {
            $varArray['password'] = $staffPassword !== '' ? $fcObj->hashPassword($staffPassword) : $existingPassword;
        }
    }

    if (!isset($msg)) {
        $editStaff = $fcObj->editStaffDetails($tbStaff, $staffId, $varArray);

        if ($editStaff !== false) {
            header('Location: ../Department/department.php');
            exit;
        } else {
            $msg = "Update failed. Please try again.";
        }
    }
    }
}

include_once('../layout/main_header.php');

$staffCateg = $fcObj->getStaffCategories($tbStaffCateg);
$staffCatCnt = sizeof($staffCateg);
?>

<style>
.edit-staff-page {
    background: linear-gradient(180deg, #f3f7fb 0%, #eef4fa 100%);
    border-radius: 24px;
    padding: 24px;
}

.edit-staff-page .page-hero {
    position: relative;
    overflow: hidden;
    border: 1px solid #d9e3ef;
    border-radius: 18px;
    padding: 18px 22px;
    background: linear-gradient(135deg, #f9fbfe 0%, #eef4fa 100%);
    box-shadow: 0 14px 30px rgba(15, 23, 42, 0.08);
    margin-bottom: 18px;
}

.edit-staff-page .page-hero::before {
    content: "";
    position: absolute;
    inset: 0 auto 0 0;
    width: 6px;
    background: linear-gradient(180deg, #f0b323, #d79a12);
}

.edit-staff-page .staff-title {
    margin: 0;
    font-size: 32px;
    font-weight: 800;
    letter-spacing: -0.6px;
    color: #13345a;
}

.edit-staff-page .staff-subtitle {
    margin: 8px 0 0;
    color: #6b819c;
    font-size: 15px;
}

.edit-staff-page .staff-card {
    border: 1px solid #d9e3ef;
    border-radius: 18px;
    background: #ffffff;
    box-shadow: 0 10px 22px rgba(15, 23, 42, 0.06);
}

.edit-staff-page .form-label {
    font-weight: 700;
    color: #173d69;
}

.edit-staff-page .form-control,
.edit-staff-page .form-select {
    border: 1px solid #c8d8ea;
    border-radius: 12px;
    min-height: 50px;
    background: #f6faff;
}

.edit-staff-page textarea.form-control {
    min-height: 108px;
}

.edit-staff-page .form-control:focus,
.edit-staff-page .form-select:focus {
    border-color: #87a6cb;
    box-shadow: 0 0 0 4px rgba(23, 61, 105, 0.12);
    background: #ffffff;
}

.staff-password-toggle-wrap {
    position: relative;
}

.staff-password-toggle-wrap .form-control {
    padding-right: 92px;
}

.staff-password-toggle-btn {
    position: absolute;
    top: 50%;
    right: 10px;
    transform: translateY(-50%);
    border: 0;
    background: #ffffff;
    border-radius: 10px;
    min-width: 72px;
    height: 36px;
    font-size: 13px;
    font-weight: 700;
    color: #1f3d60;
    box-shadow: inset 0 0 0 1px #c8d8ea;
}

.edit-staff-page .btn-primary {
    border: 0;
    border-radius: 12px;
    padding: 11px 20px;
    background: linear-gradient(135deg, #13345a, #173d69);
    font-weight: 700;
    box-shadow: 0 10px 20px rgba(16, 42, 72, 0.24);
}

.edit-staff-page .btn-secondary {
    border: 1px solid #c8d8ea;
    border-radius: 12px;
    padding: 11px 20px;
    font-weight: 600;
    color: #173d69;
    background: #ffffff;
}

@media (max-width: 768px) {
    .edit-staff-page .staff-title {
        font-size: 26px;
    }
}
</style>

<div class="container-fluid edit-staff-page">
<div class="page-hero">
    <h3 class="staff-title">Edit Faculty</h3>
    <p class="staff-subtitle">Update faculty details inside the same school-branded department workspace.</p>
</div>

<div class="card shadow-sm border-0 staff-card">
    <div class="card-body">

        <?php if (isset($msg)) { ?>
            <div class="alert alert-danger"><?php echo $msg; ?></div>
        <?php } ?>

        <?php if (isset($staffDetails)) { ?>

        <form action="editfaculty.php?faculty=<?php echo (int)$staffId; ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(app_get_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">

            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">Faculty Type</label>
                    <select name="staffType" class="form-select" required>
                        <?php for ($i = 0; $i < $staffCatCnt; $i++) { ?>
                            <option value="<?php echo $staffCateg[$i]['id']; ?>"
                                <?php if ($staffDetails[0]['staff_categ_id'] == $staffCateg[$i]['id']) echo "selected"; ?>>
                                <?php echo $staffCateg[$i]['category_name']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control"
                           value="<?php echo $staffDetails[0]['e_mail']; ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">First Name</label>
                    <input type="text" name="firstName" class="form-control"
                           value="<?php echo $staffDetails[0]['first_name']; ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Last Name</label>
                    <input type="text" name="lastName" class="form-control"
                           value="<?php echo $staffDetails[0]['last_name']; ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Qualification</label>
                    <input type="text" name="staffQualif" class="form-control"
                           value="<?php echo $staffDetails[0]['qualification']; ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Designation</label>
                    <input type="text" name="staffDesig" class="form-control"
                           value="<?php echo $staffDetails[0]['designation']; ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Faculty Login Password</label>
                    <div class="staff-password-toggle-wrap">
                        <input type="password" name="staffPassword" id="staffPasswordEdit" class="form-control" minlength="8" placeholder="Leave blank to keep current password">
                        <button type="button" class="staff-password-toggle-btn" data-toggle-password="staffPasswordEdit">Show</button>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Industry Experience</label>
                    <input type="text" name="indusExp" class="form-control"
                           value="<?php echo $staffDetails[0]['industry_exp']; ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Teaching Experience</label>
                    <input type="text" name="teachingExp" class="form-control"
                           value="<?php echo $staffDetails[0]['teach_exp']; ?>">
                </div>

                <div class="col-12">
                    <label class="form-label">Research</label>
                    <textarea name="research" class="form-control" rows="3"><?php echo $staffDetails[0]['research']; ?></textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label">National Publications</label>
                    <textarea name="pub_nat" class="form-control" rows="3"><?php echo $staffDetails[0]['publ_national']; ?></textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label">International Publications</label>
                    <textarea name="pub_internat" class="form-control" rows="3"><?php echo $staffDetails[0]['publ_international']; ?></textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label">National Conferences</label>
                    <textarea name="conf_nat" class="form-control" rows="3"><?php echo $staffDetails[0]['conf_national']; ?></textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label">International Conferences</label>
                    <textarea name="conf_internat" class="form-control" rows="3"><?php echo $staffDetails[0]['conf_international']; ?></textarea>
                </div>

                <div class="col-12">
                    <label class="form-label">Faculty Image</label>
                    <input type="file" name="staffImage" class="form-control">
                    <input type="hidden" name="imageName" value="<?php echo $staffDetails[0]['image']; ?>">
                    <input type="hidden" name="existingPassword" value="<?php echo htmlspecialchars((string)$staffDetails[0]['password'], ENT_QUOTES, 'UTF-8'); ?>">
                </div>

            </div>

            <input type="hidden" name="staffId" value="<?php echo $staffId; ?>">

            <div class="mt-4">
                <button type="submit" name="editStaffDetails" class="btn btn-primary">
                    Update Faculty
                </button>
                <a href="../Department/department.php" class="btn btn-secondary">
                    Cancel
                </a>
            </div>

        </form>

        <?php } ?>

    </div>
</div>

</div>

<script>
(function () {
    Array.prototype.forEach.call(document.querySelectorAll('[data-toggle-password]'), function (button) {
        button.addEventListener('click', function () {
            var inputId = button.getAttribute('data-toggle-password');
            var input = document.getElementById(inputId);
            if (!input) {
                return;
            }

            input.type = input.type === 'password' ? 'text' : 'password';
            button.textContent = input.type === 'password' ? 'Show' : 'Hide';
        });
    });
})();
</script>

<?php include_once('../layout/footer.php'); ?>
