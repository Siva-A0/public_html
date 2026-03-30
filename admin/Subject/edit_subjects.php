<?php require_once(__DIR__ . '/../../config.php'); ?>
<?php 

require_once(LIB_PATH . '/functions.class.php');

$fcObj = new DataFunctions();

$tbClass   = TB_CLASS;
$tbSubject = TB_SUBJECTS;
$tbBatch   = TB_BATCH;
$batches   = $fcObj->getBatches($tbBatch);
$batchesCnt = sizeof($batches);

$classes    = $fcObj->getClassesWOPO($tbClass);
$classesCnt = sizeof($classes);

$subjDet = array();
$subjId = 0;
foreach (array('subject', 'subId', 'id', 'subjectId', 'subjId', 'subjects', 'subj') as $paramName) {
    if (isset($_GET[$paramName]) && $_GET[$paramName] !== '') {
        $subjId = intval($_GET[$paramName]);
        break;
    }
}

if ($subjId > 0) {
    $subjDet = $fcObj->getSubjectById($tbSubject, $subjId);
}

if (isset($_POST['editSubject'])) {

    $varArray = array();
    $varArray['batch_id']  = intval($_POST['batchId']);
    $varArray['class_id']  = intval($_POST['clsId']);
    $varArray['subj_id']   = intval($_POST['subId']);
    $varArray['subj_name'] = trim($_POST['subName']);
    $varArray['subj_code'] = trim($_POST['subCode']);

    $editSubj = $fcObj->editSubject($tbSubject, $varArray);

    if ($editSubj) {
        header('Location: subjects.php');
        exit;
    } else {
        $subjDet = $fcObj->getSubjectById($tbSubject, intval($_POST['subId']));
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

<div class="subject-edit-page">
<div id="page">
    <div id="content" class="single-panel-layout">
        <div class="post">
            <span class="alignCenter">
                <h4>AIML Department</h4>
            </span>
        </div>

        <div id='content_right' class='content_right'>
            <div class="subject-edit-hero">
                <h3 class="subject-edit-title">Edit Subject</h3>
                <p class="subject-edit-subtitle">Update class, subject code, and subject name.</p>
            </div>
            <div class="comteeMem">

                <?php if (isset($msg)) { ?>
                    <div class="comteeMemRow">
                        <div class="usersDetHeader">
                            <?php echo $msg; ?>
                        </div>
                    </div>
                <?php } ?>

                <?php if (!empty($subjDet)) { ?>

                <form id='editsubject' class="core-form" action='edit_subjects.php' method='POST' accept-charset='UTF-8' enctype="multipart/form-data">

                    <div class="form_row">
                        <div class="form_label">
                            <label>Class Name :</label>
                        </div>
                        <div class="form_field">
                            <input type="text" name="clsName" 
                                value="<?php echo htmlspecialchars($subjDet[0]['class_code']); ?>" 
                                readonly="readonly" />
                            <input type="hidden" name="clsId" 
                                value="<?php echo intval($subjDet[0]['class_id']); ?>" />
                        </div>
                    </div>

                    <div class="form_row">
                        <div class="form_label">
                            <label>Batch :</label>
                        </div>
                        <div class="form_field">
                            <select name="batchId" required>
                                <option value="">SELECT</option>
                                <?php for ($i=0; $i<$batchesCnt; $i++) { ?>
                                    <option value="<?php echo (int)$batches[$i]['id']; ?>" <?php echo ((int)($subjDet[0]['batch_id'] ?? 0) === (int)$batches[$i]['id']) ? 'selected="selected"' : ''; ?>>
                                        <?php echo htmlspecialchars((string)$batches[$i]['batch'], ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="form_row">
                        <div class="form_label">
                            <label>Subject Code :</label>
                        </div>
                        <div class="form_field">
                            <input type="text" name="subCode" 
                                value="<?php echo htmlspecialchars($subjDet[0]['sub_code']); ?>" />
                        </div>
                    </div>

                    <div class="form_row">
                        <div class="form_label">
                            <label>Subject Name :</label>
                        </div>
                        <div class="form_field">
                            <input type="text" name="subName" 
                                value="<?php echo htmlspecialchars($subjDet[0]['sub_name']); ?>" />
                        </div>
                    </div>


                    <div class="form_row form_actions">
                        <div class="form_label"></div>
                        <div class="form_field">
                            <input type="hidden" name="subId" 
                                value="<?php echo intval($subjDet[0]['id']); ?>" />
                            <input type='submit' name='editSubject' class="button" value='Update Subject' />
                        </div>
                    </div>

                </form>

                <?php } else { ?>
                    <div class="invalid-subject">Invalid subject selected. Open this page from the Subjects list.</div>
                    <div class="invalid-subject-actions">
                        <a href="subjects.php">
                            <input type="button" class="button" value="Back to Subjects" />
                        </a>
                    </div>
                <?php } ?>

            </div>
        </div>

        <br class="clearfix" />
    </div>

                    <div class="mt-3">
                    <a href="../settings/department_option.php?option=subjects" class="btn btn-outline-secondary">Back</a>
                </div><?php include_once('../layout/sidebar.php'); ?>

    <br class="clearfix" />
</div>
</div>

<?php include_once('../layout/footer.php'); ?>

