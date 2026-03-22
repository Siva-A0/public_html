<?php 
	
   require_once(__DIR__ . '/../../config.php');
    
    require_once(LIB_PATH . '/functions.class.php');

   $fcObj	= new DataFunctions();
   
   $tbClass		= TB_CLASS;
    
   $tbSubject	= TB_SUBJECTS;

   $tbBatch = TB_BATCH;
   $batches = $fcObj->getBatches($tbBatch);
   $batchesCnt = sizeof($batches);
    
   $classes		= $fcObj->getClassesWOPO( $tbClass );
   
   $classesCnt	= sizeof($classes);

   $batchId  = isset($_POST['batchId']) ? trim($_POST['batchId']) : '';
   $classId  = isset($_POST['classId']) ? trim($_POST['classId']) : '';
   $subjCode = isset($_POST['subjCode']) ? trim($_POST['subjCode']) : '';
   $subjName = isset($_POST['subjName']) ? trim($_POST['subjName']) : '';

   if ( isset ( $_POST['addNewSubject'] ) ){
    				
		if ($batchId === '' || $classId === '' || $subjCode === '' || $subjName === '') {
			$msg = 'Please select a batch and class, then fill subject code and subject name.';
		} else {
			$varArray['batch_id']  = $batchId;
			$varArray['class_id']	= $classId;
			$varArray['subj_code']	= $subjCode;
			$varArray['subj_name']	= $subjName;
			
			$addSubj	= $fcObj->addSubject ( $tbSubject, $varArray );
			
			if( $addSubj ){
				header('Location: subjects.php');
				return false;
			}else{
				$msg	= 'Sorry, Please try again';
			}
		}
   }

	include_once('../layout/main_header.php');
	include_once('../layout/core_forms_style.php');

?>
			<div id="page">
				<div id="content">
					<div class="post">
						<span class="alignCenter">
							<h4>AIML Department </h4>
						</span>
					</div>
					<div id='content_right' class='content_right'>
						<div class="add-subject-page">
						<div class="core-hero">
							<h1>Add Subject</h1>
							<p>Add a subject with class, code, and name details.</p>
						</div>
						<div class="comteeMem">
							<?php
								if( isset ( $msg ) ){
							?>
								<div class="comteeMemRow form-alert error">
									<div>
										<?php echo $msg;?>
									</div>
								</div>
							<?php
								}
							?>
							<form id='addSubject' action='add_subject.php' method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
								<div class="form_row">
									<div class="form_label">
										<label for='batchId'>Batch:</label>
									</div>
									<div class="form_field">
										<select name="batchId" id="batchId" required>
											<option value="">SELECT</option>
											<?php for($i=0;$i<$batchesCnt;$i++){ ?>
												<option value="<?php echo $batches[$i]['id']; ?>" <?php echo ($batchId == $batches[$i]['id']) ? 'selected="selected"' : ''; ?>>
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
														<option value="<?php echo $classes[$i]['id']; ?>" <?php echo ($classId == $classes[$i]['id']) ? 'selected="selected"' : ''; ?>>
															<?php echo $classes[$i]['class_name']; ?>
														</option>
													<?php
												}
											?>
										</select>
									</div>
								</div>
								<div class="form_row">
									<div class="form_label">
										<label for="subjectcode">Subject Code:</label>
									</div>
									<div class="form_field">
										<input type="text" name="subjCode" id="subjCode" value="<?php echo htmlspecialchars($subjCode); ?>" />
									</div>
								</div>
								<div class="form_row">
									<div class="form_label">
										<label for="subjectname">Subject Name :</label>
									</div>
									<div class="form_field">
										<input type="text" name="subjName" id="subjName" value="<?php echo htmlspecialchars($subjName); ?>" />
									</div>
								</div>
								<div class="form_row form-actions">
									<div class="form_field">
										<input type='submit' name='addNewSubject' id="addNewSubject" class="button" value='Add Subject' />
										<a href="subjects.php" class="btn-secondary">Cancel</a>
									</div>
								</div>
							</form>
						</div>
						</div>
					</div>
					<br class="clearfix" />
				</div>
				                <div class="mt-3">
                    <a href="../settings/department_option.php?option=subjects" class="btn btn-outline-secondary">Back</a>
                </div><?php 
					include_once('../layout/sidebar.php');
				?>
				<br class="clearfix" />
			</div>
		</div>

<?php 
	include_once('../layout/footer.php');
?>

<style type="text/css">
	.add-subject-page {
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

	#content {
		grid-template-columns: 1fr !important;
	}

	#content_left {
		display: none !important;
	}

	#content_right {
		max-width: 980px;
	}

	.core-hero {
		position: relative;
		overflow: hidden;
		background: linear-gradient(135deg, #f9fbfe 0%, var(--sp-surface) 100%);
		border: 1px solid var(--sp-border);
		border-radius: 24px;
		padding: 24px 28px;
		margin-bottom: 16px;
	}

	.core-hero::before {
		content: "";
		position: absolute;
		inset: 0 auto 0 0;
		width: 6px;
		background: linear-gradient(180deg, var(--sp-accent), var(--sp-accent-deep));
	}

	.core-hero h1 {
		margin: 0 0 6px;
		font-size: 52px;
		line-height: 1.05;
		font-weight: 800;
		color: var(--sp-primary-deep);
		letter-spacing: -1px;
	}

	.core-hero p {
		margin: 0;
		font-size: 30px;
		line-height: 1.25;
		color: var(--sp-muted);
	}

	.comteeMem {
		border: 1px solid var(--sp-border);
		border-radius: 18px;
		box-shadow: 0 10px 22px rgba(15, 23, 42, 0.06);
		padding: 24px;
		background: #fff;
	}

	.form-alert {
		border-radius: 12px;
		padding: 12px 14px;
		font-size: 14px;
		margin-bottom: 14px;
	}

	.form-alert.error {
		border: 1px solid #fecaca;
		background: #fef2f2;
		color: #991b1b;
	}

	#addSubject .form_label label {
		color: var(--sp-primary);
		font-weight: 700;
	}

	#addSubject .form_field select,
	#addSubject .form_field input[type="text"] {
		width: 100%;
		min-height: 52px;
		border: 1px solid var(--sp-border-strong);
		border-radius: 12px;
		padding: 11px 14px;
		background: #f7f9fc;
		font-size: 16px;
		outline: none;
	}

	#addSubject .form_field select:focus,
	#addSubject .form_field input[type="text"]:focus {
		border-color: #87a6cb;
		background: #ffffff;
		box-shadow: 0 0 0 4px rgba(23, 61, 105, 0.12);
	}

	#addSubject .button {
		border: 0;
		border-radius: 12px;
		padding: 11px 20px;
		background: linear-gradient(135deg, var(--sp-primary-deep), var(--sp-primary));
		font-weight: 700;
		box-shadow: 0 10px 20px rgba(19, 52, 90, 0.24);
		color: #fff;
	}

	.form-actions .form_field {
		display: flex;
		align-items: center;
		gap: 10px;
	}

	.btn-secondary {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		min-height: 48px;
		padding: 12px 22px;
		border-radius: 12px;
		border: 1px solid var(--sp-border-strong);
		background: #64748b;
		color: #ffffff;
		font-size: 16px;
		font-weight: 700;
		text-decoration: none;
		transition: .2s ease;
	}

	.btn-secondary:hover {
		filter: brightness(1.05);
		transform: translateY(-1px);
	}

	@media (max-width: 980px) {
		.core-hero h1 {
			font-size: 34px;
		}

		.core-hero p {
			font-size: 20px;
		}
	}

	@media (max-width: 640px) {
		.core-hero {
			padding: 16px 18px;
			border-radius: 18px;
		}

		.core-hero h1 {
			font-size: 28px;
		}

		.core-hero p {
			font-size: 17px;
		}

		.form-actions .form_field {
			flex-direction: column;
			align-items: stretch;
		}

		.form-actions .button,
		.form-actions .btn-secondary {
			width: 100%;
			text-align: center;
		}
	}
</style>
