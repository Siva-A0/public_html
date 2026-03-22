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


include_once('../layout/main_header.php');
include_once('../layout/core_forms_style.php');
?>
<style type="text/css">
    #content_left {
        display: none;
    }

    #content {
        grid-template-columns: 1fr;
        gap: 0;
    }

    #page {
        max-width: none;
    }
</style>

<style type="text/css">
    .edit-class-page {
        --cp-primary: #173d69;
        --cp-primary-deep: #13345a;
        --cp-accent: #f0b323;
        --cp-accent-deep: #d79a12;
        --cp-surface: #eef4fa;
        --cp-border: #d9e3ef;
        --cp-muted: #6b819c;
        padding-bottom: 22px;
        background: linear-gradient(180deg, #f3f7fb 0%, var(--cp-surface) 100%);
        border-radius: 24px;
        padding: 24px;
    }

    .edit-class-page #page {
        max-width: 980px;
    }

    .edit-class-page #content {
        grid-template-columns: minmax(0, 1fr);
        gap: 18px;
        align-items: start;
    }

    .edit-class-page .post {
        margin-bottom: 4px !important;
    }

    .edit-class-page .page-hero {
        position: relative;
        overflow: hidden;
        border: 1px solid var(--cp-border);
        border-radius: 22px;
        padding: 22px 24px;
        background: linear-gradient(135deg, #f9fbfe 0%, var(--cp-surface) 100%);
        box-shadow: 0 14px 30px rgba(15, 23, 42, 0.08);
        margin-bottom: 16px;
    }

    .edit-class-page .page-hero::before {
        content: "";
        position: absolute;
        inset: 0 auto 0 0;
        width: 6px;
        background: linear-gradient(180deg, var(--cp-accent), var(--cp-accent-deep));
    }

    .edit-class-page .hero-title {
        font-size: 32px;
        letter-spacing: -0.6px;
        margin: 0;
        color: var(--cp-primary-deep);
        font-weight: 800;
    }

    .edit-class-page .page-subtitle {
        margin: 8px 0 0;
        color: var(--cp-muted);
        font-size: 15px;
    }

    .edit-class-page #content_right .comteeMem {
        padding: 28px 30px;
        border-radius: 18px;
        max-width: 100%;
    }

    .edit-class-page .edit-form {
        display: grid;
        gap: 16px;
    }

    .edit-class-page .edit-form .form_row {
        margin: 0 !important;
    }

    .edit-class-page .edit-form .form_label {
        margin-bottom: 8px !important;
    }

    .edit-class-page .edit-form .form_label label {
        font-size: 16px;
        font-weight: 800;
    }

    .edit-class-page .edit-form .form_field input[type="text"] {
        min-height: 60px !important;
        border-radius: 14px !important;
        font-size: 18px !important;
        padding: 12px 16px !important;
    }

    .edit-class-page .form-actions {
        padding-top: 2px;
    }

    .edit-class-page .edit-form .form-actions .button {
        min-height: 54px !important;
        border-radius: 14px !important;
        padding: 12px 24px !important;
        font-size: 20px !important;
        width: auto;
        min-width: 220px;
    }

    .edit-class-page .form-message {
        margin-bottom: 14px;
        padding: 12px 14px;
        border-radius: 11px;
        border: 1px solid #fecaca;
        background: #fef2f2;
        color: #b91c1c;
        font-weight: 700;
        font-size: 15px;
    }

    @media (max-width: 980px) {
        .edit-class-page .hero-title { font-size: 30px; }

        .edit-class-page .edit-form .form_label label {
            font-size: 15px;
        }

        .edit-class-page .edit-form .form_field input[type="text"] {
            min-height: 60px !important;
            font-size: 17px !important;
        }

        .edit-class-page .edit-form .form-actions .button {
            width: 100%;
            font-size: 19px !important;
            min-height: 56px !important;
            min-width: 0;
        }
    }
</style>

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
