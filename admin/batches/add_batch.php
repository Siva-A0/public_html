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

include_once('../layout/main_header.php');
include_once('../layout/core_forms_style.php');
?>
<style type="text/css">
	.add-batch-page {
		--bp-primary: #173d69;
		--bp-primary-deep: #13345a;
		--bp-accent: #f0b323;
		--bp-accent-deep: #d79a12;
		--bp-surface: #eef4fa;
		--bp-border: #d9e3ef;
		--bp-border-strong: #c8d6e6;
		--bp-muted: #6b819c;
		background: linear-gradient(180deg, #f3f7fb 0%, var(--bp-surface) 100%);
		border-radius: 24px;
		padding: 24px;
	}
	#content_left {
		display: none;
	}

	#content {
		grid-template-columns: minmax(320px, 840px);
		justify-content: center;
		gap: 0;
	}

	.batch-add-hero {
		position: relative;
		overflow: hidden;
		border: 1px solid var(--bp-border);
		border-radius: 22px;
		padding: 22px 24px;
		background: linear-gradient(135deg, #f9fbfe 0%, var(--bp-surface) 100%);
		box-shadow: 0 14px 30px rgba(15, 23, 42, 0.08);
		margin-bottom: 16px;
	}
	.batch-add-hero::before { content:""; position:absolute; inset:0 auto 0 0; width:6px; background:linear-gradient(180deg,var(--bp-accent),var(--bp-accent-deep)); }

	.batch-add-title {
		margin: 0;
		font-size: 32px;
		font-weight: 800;
		letter-spacing: -0.6px;
		color: var(--bp-primary-deep);
	}

	.batch-add-subtitle {
		margin: 8px 0 0;
		font-size: 15px;
		color: var(--bp-muted);
	}

	#content_right .comteeMem {
		max-width: 840px;
		border: 1px solid var(--bp-border);
		border-radius: 18px;
		box-shadow: 0 10px 22px rgba(15, 23, 42, 0.06);
		padding: 24px;
	}

	#addBatch .form_label label {
		font-size: 16px;
		font-weight: 700;
		color: var(--bp-primary);
	}

	#addBatch .form_field input[type="text"] {
		width: 100%;
		min-height: 52px;
		border: 1px solid var(--bp-border-strong);
		border-radius: 12px;
		padding: 11px 14px;
		background: #f7f9fc;
		font-size: 16px;
		outline: none;
	}

	#addBatch .form_field input[type="text"]:focus {
		border-color: #87a6cb;
		background: #ffffff;
		box-shadow: 0 0 0 4px rgba(23, 61, 105, 0.12);
	}

	#addBatch .button {
		border: 0;
		border-radius: 12px;
		padding: 11px 20px;
		background: linear-gradient(135deg, var(--bp-primary-deep), var(--bp-primary));
		font-weight: 700;
		box-shadow: 0 10px 20px rgba(16, 42, 72, 0.24);
	}

	#addBatch .button:hover {
		filter: brightness(1.06);
	}
</style>
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
