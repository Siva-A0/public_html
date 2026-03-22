<?php require_once(__DIR__ . '/../../config.php'); ?>

<?php 

// require_once("libraries/functions.class.php");
require_once(LIB_PATH . '/functions.class.php');

$fcObj = new DataFunctions();

$tbSection = TB_SECTION;
$tbBatch = TB_BATCH;
$batches = $fcObj->getBatches($tbBatch);


/* ---------------- Get Section Details ---------------- */
$sectionDet = [];

if (isset($_GET['section']) && !empty($_GET['section'])) {

    $secId = intval($_GET['section']); // Security

    $sectionDet = $fcObj->getSectionById($tbSection, $secId);

    if (empty($sectionDet)) {
        header('Location: sections.php');
        exit;
    }
}


/* ---------------- Update Section ---------------- */
if (isset($_POST['editSection'])) {

    $varArray = [];

    $varArray['class_id'] = intval($_POST['clsId']);
    $varArray['sec_id']   = intval($_POST['secId']);
    $varArray['batch_id'] = intval($_POST['batchId'] ?? 0);

    $varArray['sec_name'] = trim($_POST['secName']);
    $varArray['sec_code'] = trim($_POST['secCode']);

    if ($varArray['batch_id'] <= 0) {
        $msg = 'Please select a batch.';
        $editSec = false;
    } else {
        $editSec = $fcObj->editSection($tbSection, $varArray);
    }

    if ($editSec) {

        header('Location: sections.php');
        exit;

    } else {

        $sectionDet = $fcObj->getSectionById($tbSection, intval($_POST['secId']));
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
    .edit-section-page {
        --sp-primary: #173d69;
        --sp-primary-deep: #13345a;
        --sp-accent: #f0b323;
        --sp-accent-deep: #d79a12;
        --sp-surface: #eef4fa;
        --sp-border: #d9e3ef;
        --sp-muted: #6b819c;
        padding-bottom: 22px;
        background: linear-gradient(180deg, #f3f7fb 0%, var(--sp-surface) 100%);
        border-radius: 24px;
        padding: 24px;
    }

    .edit-section-page #page {
        max-width: 980px;
    }

    .edit-section-page #content {
        grid-template-columns: minmax(0, 1fr);
        gap: 18px;
    }

    .edit-section-page .post {
        margin-bottom: 4px !important;
    }

    .edit-section-page .page-hero {
        position: relative;
        overflow: hidden;
        border: 1px solid var(--sp-border);
        border-radius: 22px;
        padding: 22px 24px;
        background: linear-gradient(135deg, #f9fbfe 0%, var(--sp-surface) 100%);
        box-shadow: 0 14px 30px rgba(15, 23, 42, 0.08);
        margin-bottom: 16px;
    }

    .edit-section-page .page-hero::before {
        content: "";
        position: absolute;
        inset: 0 auto 0 0;
        width: 6px;
        background: linear-gradient(180deg, var(--sp-accent), var(--sp-accent-deep));
    }

    .edit-section-page .hero-title {
        font-size: 32px;
        letter-spacing: -0.6px;
        margin: 0;
        color: var(--sp-primary-deep);
        font-weight: 800;
    }

    .edit-section-page .page-subtitle {
        margin: 8px 0 0;
        color: var(--sp-muted);
        font-size: 15px;
    }

    .edit-section-page #content_right .comteeMem {
        padding: 28px 30px;
        border-radius: 18px;
    }

    .edit-section-page .edit-form {
        display: grid;
        gap: 16px;
    }

    .edit-section-page .edit-form .form_row {
        margin: 0 !important;
    }

    .edit-section-page .edit-form .form_label {
        margin-bottom: 8px !important;
    }

    .edit-section-page .edit-form .form_label label {
        font-size: 16px;
        font-weight: 800;
    }

    .edit-section-page .edit-form .form_field input[type="text"] {
        min-height: 60px !important;
        border-radius: 14px !important;
        font-size: 18px !important;
        padding: 12px 16px !important;
    }

    .edit-section-page .edit-form .form_field input[readonly] {
        background: #eef2ff !important;
        color: #0f172a !important;
        -webkit-text-fill-color: #0f172a;
        opacity: 1;
        font-weight: 700;
    }

    .edit-section-page .form-actions {
        padding-top: 2px;
    }

    .edit-section-page .edit-form .form-actions .button {
        min-height: 54px !important;
        border-radius: 14px !important;
        padding: 12px 24px !important;
        font-size: 20px !important;
        width: auto;
        min-width: 220px;
    }

    .edit-section-page .form-message {
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
        .edit-section-page .hero-title { font-size: 30px; }

        .edit-section-page .edit-form .form_label label {
            font-size: 15px;
        }

        .edit-section-page .edit-form .form_field input[type="text"] {
            min-height: 56px !important;
            font-size: 17px !important;
        }

        .edit-section-page .edit-form .form-actions .button {
            width: 100%;
            min-width: 0;
            font-size: 19px !important;
            min-height: 56px !important;
        }
    }
</style>

<div class="edit-section-page">
<div id="page">
    <div id="content">

        <div class="page-hero">
            <h1 class="hero-title">Edit Section</h1>
            <p class="page-subtitle">Update the batch and section details without leaving the academic admin workflow.</p>
        </div>


        <div id='content_left' class='content_left'></div>


        <div id='content_right' class='content_right'>

            <div class="comteeMem">

                <?php if (isset($msg)) { ?>
                    <div class="form-message"><?php echo $msg; ?></div>
                <?php } ?>


                <form id='editsection' class="edit-form" action='edit_sections.php' method='POST' accept-charset='UTF-8' enctype="multipart/form-data">


                    <!-- Batch -->
                    <div class="form_row">
                        <div class="form_label">
                            <label for="batchId">Batch :</label>
                        </div>

                        <div class="form_field">
                            <select name="batchId" id="batchId" class="form-control" style="min-height:60px; border-radius:14px; font-size:18px; padding:12px 16px;" required>
                                <option value="">Select Batch</option>
                                <?php foreach ($batches as $b) { ?>
                                    <option value="<?php echo (int)$b['id']; ?>" <?php echo ((int)($sectionDet[0]['batch_id'] ?? 0) === (int)$b['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars((string)$b['batch'], ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <!-- Class Name -->
                    <div class="form_row">
                        <div class="form_label">
                            <label for="clsName">Class Name :</label>
                        </div>

                        <div class="form_field">

                            <input type="text" name="clsName" id="clsName"
                                value="<?php echo isset($sectionDet[0]['class_code']) ? $sectionDet[0]['class_code'] : ''; ?>"
                                readonly="readonly" />

                            <input type="hidden" name="clsId" id="clsId"
                                value="<?php echo isset($sectionDet[0]['class_id']) ? $sectionDet[0]['class_id'] : ''; ?>" />

                        </div>
                    </div>


                    <!-- Section Code -->
                    <div class="form_row">
                        <div class="form_label">
                            <label for="sectioncode">Section Code :</label>
                        </div>

                        <div class="form_field">

                            <input type="text" name="secCode" id="secCode"
                                value="<?php echo isset($sectionDet[0]['section_code']) ? $sectionDet[0]['section_code'] : ''; ?>" />

                        </div>
                    </div>


                    <!-- Section Name -->
                    <div class="form_row">
                        <div class="form_label">
                            <label for="sectionname">Section Name :</label>
                        </div>

                        <div class="form_field">

                            <input type="text" name="secName" id="secName"
                                value="<?php echo isset($sectionDet[0]['section_name']) ? $sectionDet[0]['section_name'] : ''; ?>" />

                        </div>
                    </div>


                    <!-- Submit -->
                    <div class="form_row form-actions">
                        <div class="form_field">

                            <input type="hidden" name="secId" id="secId"
                                value="<?php echo isset($sectionDet[0]['id']) ? $sectionDet[0]['id'] : ''; ?>" />

                            <input type='submit' name='editSection' class="button" value='Update Section' />

                        </div>
                    </div>


                </form>

            </div>
        </div>


        <br class="clearfix" />

    </div>


                    <div class="mt-3">
                    <a href="../settings/department_option.php?option=sections" class="btn btn-outline-secondary">Back</a>
                </div><?php include_once('../layout/sidebar.php'); ?>


    <br class="clearfix" />
</div>

</div>
</div>

<?php include_once('../layout/footer.php'); ?>
