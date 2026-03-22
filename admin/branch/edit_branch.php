<?php require_once(__DIR__ . '/../../config.php'); ?>

<?php 

require_once(LIB_PATH . '/functions.class.php');

$fcObj = new DataFunctions();

$tbStream = TB_STREAM;


/* ---------- Default Safe Value ---------- */
$branchDet = [
    [
        'stream_code' => '',
        'stream_name' => '',
        'id' => ''
    ]
];


/* ---------- Get Branch ---------- */
if (isset($_GET['branch'])) {

    $branchId = intval($_GET['branch']);

    $data = $fcObj->getBranchById($tbStream, $branchId);

    if (!empty($data)) {
        $branchDet = $data;
    }
}


/* ---------- Update Branch ---------- */
if (isset($_POST['editBranch'])) {

    $varArray['branch_id'] = intval($_POST['branchId']);

    $varArray['branch_code'] = $_POST['branchCode'];			
    $varArray['branch_name'] = $_POST['branchName'];

    $editBranch = $fcObj->editBranch($tbStream, $varArray);

    if ($editBranch) {

        header('Location: branch.php');
        exit;

    } else {

        $data = $fcObj->getBranchById($tbStream, intval($_POST['branchId']));

        if (!empty($data)) {
            $branchDet = $data;
        }

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
    .edit-branch-page {
        --bp-primary: #173d69;
        --bp-primary-deep: #13345a;
        --bp-accent: #f0b323;
        --bp-accent-deep: #d79a12;
        --bp-surface: #eef4fa;
        --bp-border: #d9e3ef;
        --bp-muted: #6b819c;
        padding-bottom: 22px;
        background: linear-gradient(180deg, #f3f7fb 0%, var(--bp-surface) 100%);
        border-radius: 24px;
        padding: 24px;
    }

    .edit-branch-page #page {
        max-width: 980px;
    }

    .edit-branch-page #content {
        grid-template-columns: minmax(0, 1fr);
        gap: 18px;
    }

    .edit-branch-page .post {
        margin-bottom: 4px !important;
    }

    .edit-branch-page .page-hero {
        position: relative;
        overflow: hidden;
        border: 1px solid var(--bp-border);
        border-radius: 22px;
        padding: 22px 24px;
        background: linear-gradient(135deg, #f9fbfe 0%, var(--bp-surface) 100%);
        box-shadow: 0 14px 30px rgba(15, 23, 42, 0.08);
        margin-bottom: 16px;
    }
    .edit-branch-page .page-hero::before { content:""; position:absolute; inset:0 auto 0 0; width:6px; background:linear-gradient(180deg,var(--bp-accent),var(--bp-accent-deep)); }
    .edit-branch-page .hero-title { font-size:32px; letter-spacing:-.6px; margin:0; color:var(--bp-primary-deep); font-weight:800; }
    .edit-branch-page .page-subtitle { margin:8px 0 0; color:var(--bp-muted); font-size:15px; }

    .edit-branch-page #content_right .comteeMem {
        padding: 28px 30px;
        border-radius: 18px;
    }

    .edit-branch-page .edit-form {
        display: grid;
        gap: 16px;
    }

    .edit-branch-page .edit-form .form_row {
        margin: 0 !important;
    }

    .edit-branch-page .edit-form .form_label {
        margin-bottom: 8px !important;
    }

    .edit-branch-page .edit-form .form_label label {
        font-size: 16px;
        font-weight: 800;
    }

    .edit-branch-page .edit-form .form_field input[type="text"] {
        min-height: 60px !important;
        border-radius: 14px !important;
        font-size: 18px !important;
        padding: 12px 16px !important;
    }

    .edit-branch-page .form-actions {
        padding-top: 2px;
    }

    .edit-branch-page .edit-form .form-actions .button {
        min-height: 54px !important;
        border-radius: 14px !important;
        padding: 12px 24px !important;
        font-size: 20px !important;
        width: auto;
        min-width: 220px;
    }

    .edit-branch-page .form-message {
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
        .edit-branch-page .hero-title { font-size: 30px; }

        .edit-branch-page .edit-form .form_label label {
            font-size: 15px;
        }

        .edit-branch-page .edit-form .form_field input[type="text"] {
            min-height: 56px !important;
            font-size: 17px !important;
        }

        .edit-branch-page .edit-form .form-actions .button {
            width: 100%;
            min-width: 0;
            font-size: 19px !important;
            min-height: 56px !important;
        }
    }
</style>

<div class="edit-branch-page">
<div id="page">
    <div id="content">

        <div class="page-hero">
            <h1 class="hero-title">Edit Branch</h1>
            <p class="page-subtitle">Update branch code and branch name in the same branded admin workspace.</p>
        </div>


        <div id='content_left' class='content_left'></div>


        <div id='content_right' class='content_right'>
            <div class="comteeMem">

                <?php if (isset($msg)) { ?>
                    <div class="form-message"><?php echo $msg; ?></div>
                <?php } ?>


                <form id='editclass' class="edit-form" action='edit_branch.php' method='POST' accept-charset='UTF-8' enctype="multipart/form-data">


                    <!-- Branch Code -->
                    <div class="form_row">
                        <div class="form_label">
                            <label for="branchcode">Branch Code :</label>
                        </div>

                        <div class="form_field">
                            <input type="text" name="branchCode" id="branchCode"
                                   value="<?php echo htmlspecialchars((string)$branchDet[0]['stream_code'], ENT_QUOTES, 'UTF-8'); ?>" />
                        </div>
                    </div>


                    <!-- Branch Name -->
                    <div class="form_row">
                        <div class="form_label">
                            <label for="branchname">Branch Name :</label>
                        </div>

                        <div class="form_field">
                            <input type="text" name="branchName" id="branchName"
                                   value="<?php echo htmlspecialchars((string)$branchDet[0]['stream_name'], ENT_QUOTES, 'UTF-8'); ?>" />
                        </div>
                    </div>


                    <!-- Submit -->
                    <div class="form_row form-actions">
                        <div class="form_field">

                            <input type="hidden" name="branchId" id="branchId"
                                   value="<?php echo (int)$branchDet[0]['id']; ?>" />

                            <input type='submit' name='editBranch'
                                   class="button"
                                   value='Update Branch' />

                        </div>
                    </div>


                </form>

            </div>
        </div>


        <br class="clearfix" />

    </div>


                    <div class="mt-3">
                    <a href="../settings/department_option.php?option=streams" class="btn btn-outline-secondary">Back</a>
                </div><?php include_once('../layout/sidebar.php'); ?>


    <br class="clearfix" />
</div>

</div>
</div>

<?php include_once('../layout/footer.php'); ?>
