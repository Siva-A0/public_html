<?php require_once(__DIR__ . '/../../config.php'); ?>

<?php 

// require_once("libraries/functions.class.php");
require_once(LIB_PATH . '/functions.class.php');

$fcObj = new DataFunctions();

$tbClass = TB_CLASS;


/* ---------------- Get Class Details ---------------- */
$classDet = [];

if (isset($_GET['class']) && !empty($_GET['class'])) {

    $clsId = intval($_GET['class']); // Security

    $classDet = $fcObj->getClassById($tbClass, $clsId);

    if (empty($classDet)) {
        header('Location: classes.php');
        exit;
    }
}


/* ---------------- Update Class ---------------- */
if (isset($_POST['editClass'])) {

    $varArray = [];

    $varArray['class_id']   = intval($_POST['classId']);
    $varArray['class_name'] = trim($_POST['className']);
    $varArray['class_code'] = trim($_POST['classCode']);

    $editClass = $fcObj->editClass($tbClass, $varArray);

    if ($editClass) {

        header('Location: classes.php');
        exit;

    } else {

        $classDet = $fcObj->getClassById($tbClass, intval($_POST['classId']));
        $msg = 'Sorry, Please try again';
    }
}


if (!isset($adminExtraStyles) || !is_array($adminExtraStyles)) {
    $adminExtraStyles = array();
}
$adminExtraStyles[] = BASE_URL . '/public/assets/css/admin/admin_academic_pages.css';

include_once('../layout/main_header.php');
include_once('../layout/core_forms_style.php');
?>



<div class="edit-class-page">
<div id="page">
    <div id="content">

        <div class="page-hero">
            <h1 class="hero-title">Edit Class</h1>
            <p class="page-subtitle">Update the class code and class name in the same branded admin workspace.</p>
        </div>

        <div id='content_left' class='content_left'></div>


        <div id='content_right' class='content_right'>

            <div class="comteeMem">

                <?php if (isset($msg)) { ?>
                    <div class="form-message"><?php echo $msg; ?></div>
                <?php } ?>


                <form id='editclass' class="edit-form" action='edit_class.php' method='POST' accept-charset='UTF-8' enctype="multipart/form-data">

                    <div class="form_row">
                        <div class="form_label">
                            <label for="classcode">Class Code :</label>
                        </div>
                        <div class="form_field">
                            <input type="text" name="classCode" id="classCode"
                                value="<?php echo isset($classDet[0]['class_code']) ? $classDet[0]['class_code'] : ''; ?>" />
                        </div>
                    </div>


                    <div class="form_row">
                        <div class="form_label">
                            <label for="classname">Class Name :</label>
                        </div>
                        <div class="form_field">
                            <input type="text" name="className" id="className"
                                value="<?php echo isset($classDet[0]['class_name']) ? $classDet[0]['class_name'] : ''; ?>" />
                        </div>
                    </div>


                    <div class="form_row form-actions">
                        <div class="form_field">

                            <input type="hidden" name="classId" id="classId"
                                value="<?php echo isset($classDet[0]['id']) ? $classDet[0]['id'] : ''; ?>" />

                            <input type='submit' name='editClass' class="button" value='Update Class' />

                        </div>
                    </div>

                </form>

            </div>
        </div>


        <br class="clearfix" />

    </div>

                    <div class="mt-3">
                    <a href="../settings/department_option.php?option=classes" class="btn btn-outline-secondary">Back</a>
                </div><?php include_once('../layout/sidebar.php'); ?>

    <br class="clearfix" />
</div>

</div>
</div>

<?php include_once('../layout/footer.php'); ?>

