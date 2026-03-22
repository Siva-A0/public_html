<?php 
    require_once(__DIR__ . '/../../config.php');
    
    require_once(LIB_PATH . '/functions.class.php');

   $fcObj	= new DataFunctions();
   
   $tbClass		= TB_CLASS;
   
   $tbSection	= TB_SECTION;

   $tbBatch = TB_BATCH;
   $batches = $fcObj->getBatches($tbBatch);
   $batchesCnt = sizeof($batches);
   
   $classes		= $fcObj->getClassesWOPO( $tbClass );
  
   $classesCnt	= sizeof($classes);

   if ( isset ( $_POST['addNewSection'] ) ){
   				
		$varArray['batch_id']		= isset($_POST['batchId']) ? (int)$_POST['batchId'] : 0;
		$varArray['class_id']		= $_POST['classId'];
		$varArray['section_code']	= $_POST['sectionCode'];
		$varArray['section_name']	= $_POST['sectionName'];
		
		if ($varArray['batch_id'] <= 0) {
			$msg = 'Please select a batch.';
		} else {
			$addSec = $fcObj->addSection($tbSection, $varArray);
		}
		
		if( isset($addSec) && $addSec ){
			
			header('Location: sections.php');
			exit;
		}else{
			if (!isset($msg)) {
				$msg = 'Sorry, Please try again';
			}
		}
   }

	include_once('../layout/main_header.php');
	include_once('../layout/core_forms_style.php');

?>
<style type="text/css">
	.section-add-page {
		--sp-primary: #173d69;
		--sp-primary-deep: #13345a;
		--sp-accent: #f0b323;
		--sp-accent-deep: #d79a12;
		--sp-surface: #eef4fa;
		--sp-border: #d9e3ef;
		--sp-border-strong: #c8d6e6;
		--sp-muted: #6b819c;
	}

	#content_left {
		display: none;
	}

	#content {
		grid-template-columns: minmax(320px, 840px);
		justify-content: center;
		gap: 0;
	}

	.section-add-page {
		background: linear-gradient(180deg, #f3f7fb 0%, var(--sp-surface) 100%);
		border-radius: 24px;
		padding: 24px;
	}

	.section-add-hero {
		position: relative;
		overflow: hidden;
		border: 1px solid var(--sp-border);
		border-radius: 22px;
		padding: 22px 24px;
		background: linear-gradient(135deg, #f9fbfe 0%, var(--sp-surface) 100%);
		box-shadow: 0 14px 30px rgba(15, 23, 42, 0.08);
		margin-bottom: 16px;
	}

	.section-add-hero::before {
		content: "";
		position: absolute;
		inset: 0 auto 0 0;
		width: 6px;
		background: linear-gradient(180deg, var(--sp-accent), var(--sp-accent-deep));
	}

	.section-add-title {
		margin: 0;
		font-size: 32px;
		font-weight: 800;
		letter-spacing: -0.6px;
		color: var(--sp-primary-deep);
	}

	.section-add-subtitle {
		margin: 8px 0 0;
		font-size: 15px;
		color: var(--sp-muted);
	}

	#content_right .comteeMem {
		max-width: 840px;
		border: 1px solid var(--sp-border);
		border-radius: 18px;
		box-shadow: 0 10px 22px rgba(15, 23, 42, 0.06);
		padding: 24px;
	}

	#addSection .form_label label {
		font-size: 16px;
		font-weight: 700;
		color: var(--sp-primary);
	}

	#addSection .form_field select,
	#addSection .form_field input[type="text"] {
		width: 100%;
		min-height: 52px;
		border: 1px solid var(--sp-border-strong);
		border-radius: 12px;
		padding: 11px 14px;
		background: #f7f9fc;
		font-size: 16px;
		outline: none;
	}

	#addSection .form_field select:focus,
	#addSection .form_field input[type="text"]:focus {
		border-color: #87a6cb;
		background: #ffffff;
		box-shadow: 0 0 0 4px rgba(23, 61, 105, 0.12);
	}

	#addSection .button {
		border: 0;
		border-radius: 12px;
		padding: 11px 20px;
		background: linear-gradient(135deg, var(--sp-primary-deep), var(--sp-primary));
		font-weight: 700;
		box-shadow: 0 10px 20px rgba(16, 42, 72, 0.24);
	}

	#addSection .button:hover {
		filter: brightness(1.06);
	}
</style>
			<div id="page">
				<div id="content">
					<div class="post">
						<span class="alignCenter"></span>
						<p></p>
					</div>

					<div id='content_left' class='content_left'></div>
                    
					<div id='content_right' class='content_right'>
						<div class="section-add-page">
						<div class="section-add-hero">
							<h3 class="section-add-title">Add New Section</h3>
							<p class="section-add-subtitle">Create section records and map them to classes.</p>
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
							<form id='addSection' action='add_section.php' method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
								<div class="form_row">
									<div class="form_label">
										<label for='batchId'>Batch:</label>
									</div>
									<div class="form_field">
										<select name="batchId" id="batchId" class="batchId" required>
											<option value="">SELECT</option>
											<?php for($i=0;$i<$batchesCnt;$i++){ ?>
												<option value="<?php echo (int)$batches[$i]['id']; ?>" <?php if (isset($_POST['batchId']) && (string)$_POST['batchId'] === (string)$batches[$i]['id']) echo 'selected'; ?>>
													<?php echo htmlspecialchars((string)$batches[$i]['batch'], ENT_QUOTES, 'UTF-8'); ?>
												</option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="form_row">
									<div class="form_label">
										<label for='classes' >Class:</label>
									</div>
									<div class="form_field">
										<select name="classId" id="classId" class="classId">
											<option value="">SELECT</option>
											<?php
												for($i=0;$i<$classesCnt;$i++){
													?>
														<option value="<?php echo (int)$classes[$i]['id']; ?>" <?php if (isset($_POST['classId']) && (string)$_POST['classId'] === (string)$classes[$i]['id']) echo 'selected'; ?>>
															<?php echo htmlspecialchars((string)$classes[$i]['class_name'], ENT_QUOTES, 'UTF-8'); ?>
														</option>
													<?php
												}
											?>
										</select>
									</div>
								</div>
								<div class="form_row">
									<div class="form_label">
										<label for="sectioncode">Section Code:</label>
									</div>
									<div class="form_field">
										<input type="text" name="sectionCode" id="sectionCode" value="<?php echo htmlspecialchars($_POST['sectionCode'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" />
									</div>
								</div>
								<div class="form_row">
									<div class="form_label">
										<label for="sectionname">Section Name :</label>
									</div>
									<div class="form_field">
										<input type="text" name="sectionName" id="sectionName" value="<?php echo htmlspecialchars($_POST['sectionName'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" />
									</div>
								</div>
								<div class="form_row">
									<div class="form_label">
										
									</div>
									<div class="form_field">
										<input type='submit' name='addNewSection' id="addNewSection" class="button" value='Add Section' />
									</div>
								</div>
							</form>
						</div>
					</div>
					</div>
					<br class="clearfix" />
				</div>
				                <div class="mt-3">
                    <a href="../settings/department_option.php?option=sections" class="btn btn-outline-secondary">Back</a>
                </div><?php 
					include_once('../layout/sidebar.php');
				?>
				<br class="clearfix" />
			</div>
		</div>

<?php 
	include_once('../layout/footer.php');
?>
