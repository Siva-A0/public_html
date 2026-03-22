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

include_once('../layout/main_header.php');
include_once('../layout/core_forms_style.php');
?>
<style type="text/css">
    .subject-edit-page {
        --sp-primary: #173d69;
        --sp-primary-deep: #13345a;
        --sp-accent: #f0b323;
        --sp-accent-deep: #d79a12;
        --sp-surface: #eef4fa;
        --sp-border: #d9e3ef;
        --sp-border-strong: #c8d6e6;
        --sp-muted: #6b819c;
        background: linear-gradient(180deg, #f3f7fb 0%, var(--sp-surface) 100%);
        border-radius: 24px;
        padding: 24px;
    }

    #content_left {
        display: none;
    }

    #content.single-panel-layout {
        grid-template-columns: minmax(320px, 840px);
        justify-content: center;
        gap: 0;
    }

    #content.single-panel-layout .post {
        display: none;
    }

    #content.single-panel-layout #content_right {
        grid-column: 1;
        width: 100%;
    }

    .subject-edit-hero {
        width: 100%;
        max-width: 840px;
        position: relative;
        overflow: hidden;
        border: 1px solid var(--sp-border);
        border-radius: 22px;
        padding: 22px 24px;
        background: linear-gradient(135deg, #f9fbfe 0%, var(--sp-surface) 100%);
        box-shadow: 0 14px 30px rgba(15, 23, 42, 0.08);
        margin-bottom: 16px;
    }

    .subject-edit-hero::before {
        content: "";
        position: absolute;
        inset: 0 auto 0 0;
        width: 6px;
        background: linear-gradient(180deg, var(--sp-accent), var(--sp-accent-deep));
    }

    .subject-edit-title {
        margin: 0;
        font-size: 32px;
        font-weight: 800;
        letter-spacing: -0.6px;
        color: var(--sp-primary-deep);
    }

    .subject-edit-subtitle {
        margin: 8px 0 0;
        font-size: 15px;
        color: var(--sp-muted);
    }

    #content.single-panel-layout #content_right .comteeMem {
        width: 100%;
        max-width: 840px;
        border: 1px solid var(--sp-border);
        border-radius: 18px;
        box-shadow: 0 10px 22px rgba(15, 23, 42, 0.06);
        padding: 24px;
    }

    #editsubject.core-form .form_row {
        grid-template-columns: 1fr;
        gap: 8px;
    }

    #editsubject.core-form .form_label {
        min-height: 0;
        display: block;
        margin: 0;
    }

    #editsubject.core-form .form_label label {
        font-size: 16px;
        font-weight: 700;
        color: var(--sp-primary);
    }

    #editsubject.core-form .form_field input[type="text"] {
        width: 100%;
        min-height: 52px;
        border: 1px solid var(--sp-border-strong);
        border-radius: 12px;
        padding: 11px 14px;
        background: #f7f9fc;
        font-size: 16px;
        outline: none;
    }

    #editsubject.core-form .form_field input[type="text"]:focus {
        border-color: #87a6cb;
        background: #ffffff;
        box-shadow: 0 0 0 4px rgba(23, 61, 105, 0.12);
    }

    #editsubject.core-form .form_actions .form_label {
        display: none;
    }

    #editsubject.core-form .form_actions .button,
    .invalid-subject-actions .button {
        border: 0;
        border-radius: 12px;
        padding: 11px 22px;
        background: linear-gradient(135deg, var(--sp-primary-deep), var(--sp-primary));
        font-size: 18px;
        font-weight: 700;
        box-shadow: 0 10px 20px rgba(16, 42, 72, 0.24);
    }

    #editsubject.core-form .form_actions .button:hover,
    .invalid-subject-actions .button:hover {
        filter: brightness(1.06);
    }

    .invalid-subject {
        padding: 14px;
        border-radius: 12px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #0f172a;
        font-size: 16px;
        line-height: 1.4;
    }

    .invalid-subject-actions {
        margin-top: 12px;
    }
</style>

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
