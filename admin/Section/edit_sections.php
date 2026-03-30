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


if (!isset($adminExtraStyles) || !is_array($adminExtraStyles)) {
    $adminExtraStyles = array();
}
$adminExtraStyles[] = BASE_URL . '/public/assets/css/admin/admin_academic_pages.css';

include_once('../layout/main_header.php');
include_once('../layout/core_forms_style.php');

?>



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

