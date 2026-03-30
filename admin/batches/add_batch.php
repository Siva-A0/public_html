<?php require_once(__DIR__ . '/../../config.php');

require_once(LIB_PATH . '/functions.class.php');

$fcObj = new DataFunctions();
$tbBatch = TB_BATCH;

if (isset($_POST['addNewBatch'])) {

	$varArray['batch_name'] = $_POST['batchName'];

	$addBatch = $fcObj->addBatch($tbBatch, $varArray);

	if ($addBatch) {

		header('Location: batch.php');
		exit;
	} else {
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

<div id="page">
				<div id="content">
					<div class="post">
						<span class="alignCenter"></span>
						<p>
							
						</p>
					</div>
					<div id='content_left' class='content_left'></div>
					<div id='content_right' class='content_right'>
						<div class="add-batch-page">
						<div class="batch-add-hero">
							<h3 class="batch-add-title">Add New Batch</h3>
							<p class="batch-add-subtitle">Create academic batch records for enrollment and reporting.</p>
						</div>
						<div class="comteeMem">
							<?php
								if( isset ( $msg ) ){
							?>
								<div class="comteeMemRow">
									<div class="usersDetHeader">
										<?php echo $msg;?>
									</div>
								</div>
							<?php
								}
							?>
							<form id='addBatch' action='add_batch.php' method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
								<div class="form_row">
									<div class="form_label">
										<label for="batch">Batch :</label>
									</div>
									<div class="form_field">
										<input type="text" name="batchName" id="batchName" value="<?php echo htmlspecialchars($_POST['batchName'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" />
									</div>
								</div>
								<div class="form_row">
									<div class="form_label">
										
									</div>
									<div class="form_field">
										<input type='submit' name='addNewBatch' id="addNewBatch" class="button" value='Add Batch' />
									</div>
								</div>
							</form>
						</div>
						</div>
					</div>
					<br class="clearfix" />
				</div>
				                <div class="mt-3">
                    <a href="../settings/department_option.php?option=batches" class="btn btn-outline-secondary">Back</a>
                </div><?php 
					include_once('../layout/sidebar.php');
				?>
				<br class="clearfix" />
			</div>
		</div>

<?php 
	include_once('../layout/footer.php');
?>

