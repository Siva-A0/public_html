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

if (!isset($adminExtraStyles) || !is_array($adminExtraStyles)) {
    $adminExtraStyles = array();
}
$adminExtraStyles[] = BASE_URL . '/public/assets/css/admin/admin_academic_pages.css';

include_once('../layout/main_header.php');
include_once('../layout/core_forms_style.php');

?>



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

