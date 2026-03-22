<?php require_once(__DIR__ . '/../../config.php');

require_once(LIB_PATH . '/functions.class.php');

$fcObj = new DataFunctions();
$tbBatch = TB_BATCH;

if (isset($_POST['editBatch'])) {

	$varArray['batch_id'] = $_POST['batchId'];
	$varArray['batch_name'] = $_POST['batchName'];

	$editBatch = $fcObj->editBatch($tbBatch, $varArray);

	if ($editBatch) {

		header('Location: batch.php');
		exit;
	} else {

		$batchDet = $fcObj->getBatchById($tbBatch, $_POST['batchId']);
		$msg = 'Sorry, Please try again';
	}
}

$batchId = 0;
if (isset($_GET['batch']) && $_GET['batch'] !== '') {
	$batchId = (int)$_GET['batch'];
} elseif (isset($_POST['batchId']) && $_POST['batchId'] !== '') {
	$batchId = (int)$_POST['batchId'];
}

if ($batchId <= 0) {
	header('Location: batch.php');
	exit;
}

$batchDet = $fcObj->getBatchById($tbBatch, $batchId);
if (empty($batchDet)) {
	header('Location: batch.php');
	exit;
}

include_once('../layout/main_header.php');
include_once('../layout/core_forms_style.php');
?>
<style type="text/css">
	.edit-batch-page {
		--bp-primary: #173d69;
		--bp-primary-deep: #13345a;
		--bp-accent: #f0b323;
		--bp-accent-deep: #d79a12;
		--bp-surface: #eef4fa;
		--bp-border: #d9e3ef;
		--bp-muted: #6b819c;
		background: linear-gradient(180deg, #f3f7fb 0%, var(--bp-surface) 100%);
		border-radius: 24px;
		padding: 24px;
	}
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
			<div class="edit-batch-page">
			<div id="page">
				<div id="content">
					<div class="post">
						<span class="alignCenter">
							<h4>AIML Department </h4>
						</span>
						<p>
							
						</p>
					</div>
					<div id='content_left' class='content_left'></div>
					<div id='content_right' class='content_right'>
						<div class="page-hero" style="position:relative;overflow:hidden;border:1px solid #d9e3ef;border-radius:22px;padding:22px 24px;background:linear-gradient(135deg,#f9fbfe 0%,#eef4fa 100%);box-shadow:0 14px 30px rgba(15,23,42,.08);margin-bottom:16px;">
							<div style="position:absolute;inset:0 auto 0 0;width:6px;background:linear-gradient(180deg,#f0b323,#d79a12);"></div>
							<h1 style="margin:0 0 6px;font-size:32px;font-weight:800;letter-spacing:-.6px;color:#13345a;">Edit Batch</h1>
							<p style="margin:0;font-size:15px;color:#6b819c;">Update academic batch names inside the same branded admin flow.</p>
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
							<form id='editclass' action='edit_batch.php' method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
								<div class="form_row">
									<div class="form_label">
										<label for="batchname">Batch Name :</label>
									</div>
									<div class="form_field">
										<input type="text" name="batchName" id="batchName" value="<?php echo $batchDet[0]['batch'];?>" />
									</div>
								</div>
								<div class="form_row">
									<div class="form_label">
										
									</div>
									<div class="form_field">
										<input type="hidden" name="batchId" id="batchId" value="<?php echo $batchDet[0]['id']; ?>"/>
										<input type='submit' name='editBatch' class="button" value='Update Batch' />
									</div>
								</div>
							</form>
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
		</div>

<?php 
	include_once('../layout/footer.php');
?>

