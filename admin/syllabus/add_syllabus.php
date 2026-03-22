<?php 
	
   require_once(__DIR__ . '/../../config.php');
    
    require_once(LIB_PATH . '/functions.class.php');

   $fcObj	= new DataFunctions();
   
   $tbClass		= TB_CLASS;
   
   $tbSyllabus	= TB_SYLLABUS;
   $tbBatch       = TB_BATCH;
   $batches       = $fcObj->getBatches($tbBatch);
   $batchesCnt    = sizeof($batches);
   
   $classes		= $fcObj->getClassesWOPO( $tbClass );
  
   $classesCnt	= sizeof($classes);

   if ( isset ( $_POST['addNewSyllabus'] ) ){
    				
 		$varArray['class_id']		= $_POST['classId'];
		$varArray['batch_id']        = (int)($_POST['batchId'] ?? 0);
		if ($varArray['batch_id'] <= 0) {
			$msg = 'Please select a batch.';
		}
 		
 		$fileName	= $_FILES['syllabusFile']['name'];
 		$uploadDir   = ROOT_PATH . '/public/uploads/syllabus/';

		if (!is_dir($uploadDir)) {
			@mkdir($uploadDir, 0777, true);
		}
		
		if ((move_uploaded_file($_FILES['syllabusFile']['tmp_name'], $uploadDir . $fileName))){
								
			$fileName 	= $fileName;
		}else{
		
			$fileName 	= '';
		}
		
		$varArray['syllabus_name']	= $fileName;

		$addSyllabus = false;
		if (!isset($msg)) {
			$addSyllabus = $fcObj->addSyllabus ( $tbSyllabus, $varArray );
		}
		
		if( $addSyllabus ){
			
			header('Location: syllabus.php');
			exit;
		}else{
			$msg	= 'Sorry, Please try again';
		}
   }

	include_once('../layout/main_header.php');
	include_once('../layout/core_forms_style.php');


?>
<style type="text/css">
	#content_left {
		display: none;
	}

	#content.single-panel-layout {
		grid-template-columns: minmax(320px, 840px);
		justify-content: center;
		gap: 0;
	}

	#content.single-panel-layout #content_right {
		grid-column: 1;
		width: 100%;
	}

	#content.single-panel-layout .post {
		display: none;
	}

	.syllabus-add-hero {
		border: 1px solid #d9e3ef;
		border-radius: 18px;
		padding: 18px 22px;
		background:
			linear-gradient(135deg, #f9fbfe 0%, #eef4fa 100%);
		box-shadow: 0 14px 30px rgba(15, 23, 42, 0.08);
		margin-bottom: 16px;
	}

	.syllabus-add-title {
		margin: 0;
		font-size: 32px;
		font-weight: 800;
		letter-spacing: -0.6px;
		color: #13345a;
	}

	.syllabus-add-subtitle {
		margin: 8px 0 0;
		font-size: 15px;
		color: #6b819c;
	}

	#content.single-panel-layout #content_right .comteeMem {
		width: 100%;
		max-width: 840px;
		border: 1px solid #d7dde6;
		border-radius: 16px;
		box-shadow: 0 10px 22px rgba(15, 23, 42, 0.06);
		padding: 24px;
	}

	#content.single-panel-layout #content_right .syllabus-add-hero {
		width: 100%;
		max-width: 840px;
	}

	#addSyllabus.core-form .form_row {
		grid-template-columns: 1fr;
		gap: 8px;
	}

	#addSyllabus.core-form .form_label {
		min-height: 0;
		display: block;
		padding: 0;
	}

	#addSyllabus.core-form .form_label label {
		font-size: 16px;
		font-weight: 700;
		color: #1f324b;
	}

	#addSyllabus.core-form .field_shell {
		border: 1px solid #c8d8ea;
		border-radius: 12px;
		padding: 3px;
		background: #f6faff;
	}

	#addSyllabus.core-form .form_row--class .field_shell select {
		min-height: 52px;
		padding: 11px 14px;
	}

	#addSyllabus.core-form .form_row--file .field_shell {
		min-height: 52px;
		padding: 10px 12px;
	}

	#addSyllabus.core-form .form_hint {
		margin-top: 8px;
		font-size: 13px;
		color: #5d728d;
	}

	#addSyllabus.core-form .form_actions .form_label {
		display: none;
	}

	#addSyllabus.core-form .form_actions .button {
		border: 0;
		border-radius: 12px;
		padding: 11px 22px;
		min-height: 0;
		background: linear-gradient(135deg, #13345a, #173d69);
		font-size: 18px;
		font-weight: 700;
		box-shadow: 0 10px 20px rgba(16, 42, 72, 0.24);
	}

	#addSyllabus.core-form .form_actions .button:hover {
		filter: brightness(1.06);
	}
</style>
			<div id="page">
				<div id="content" class="single-panel-layout">
					<div class="post">
						<span class="section-kicker">Academic Files</span>
						<h4>Add Syllabus</h4>
						<p class="text-muted mb-0">Upload one syllabus file for a selected class.</p>
					</div>
					<!-- <div id='content_left' class='content_left'>
						<?php 
							include_once('../layout/leftnav.php');
						?>						
					</div> -->
					<div id='content_right' class='content_right'>
						<div class="syllabus-add-hero">
							<h3 class="syllabus-add-title">Add New Syllabus</h3>
							<p class="syllabus-add-subtitle">Upload syllabus files for selected classes.</p>
						</div>
						<div class="comteeMem">
							<?php
								if( isset ( $msg ) ){
							?>
								<div class="form_alert"><?php echo htmlspecialchars($msg, ENT_QUOTES, 'UTF-8'); ?></div>
							<?php
								}
							?>
							<form id='addSyllabus' class="core-form" action='add_syllabus.php' method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
								<div class="form_row">
									<div class="form_label">
										<label for="batchId">Batch:</label>
									</div>
									<div class="form_field">
										<div class="field_shell">
											<select name="batchId" id="batchId" required>
												<option value="">Select Batch</option>
												<?php for($i=0;$i<$batchesCnt;$i++){ ?>
													<option value="<?php echo (int)$batches[$i]['id']; ?>" <?php echo ((int)($_POST['batchId'] ?? 0) === (int)$batches[$i]['id']) ? 'selected="selected"' : ''; ?>>
														<?php echo htmlspecialchars((string)$batches[$i]['batch'], ENT_QUOTES, 'UTF-8'); ?>
													</option>
												<?php } ?>
											</select>
										</div>
									</div>
								</div>
								<div class="form_row form_row--class">
									<div class="form_label">
										<label for='classId'>Class:</label>
									</div>
									<div class="form_field">
										<div class="field_shell">
											<select name="classId" id="classId" class="classId" required>
												<option value="">Select Class</option>
												<?php
													for($i=0;$i<$classesCnt;$i++){
														?>
															<option value="<?php echo $classes[$i]['id']; ?>"><?php echo $classes[$i]['class_name']; ?></option>
														<?php
													}
												?>
											</select>
										</div>
									</div>
								</div>
								<div class="form_row form_row--file">
									<div class="form_label">
										<label for="syllabusFile">Syllabus File:</label>
									</div>
									<div class="form_field">
										<div class="field_shell">
											<input type="file" name="syllabusFile" id="syllabusFile" accept=".pdf,.doc,.docx" aria-describedby="syllabusFileHint" required />
										</div>
										<small id="syllabusFileHint" class="form_hint">Accepted formats: PDF, DOC, DOCX.</small>
									</div>
								</div>
								<div class="form_row form_actions">
									<div class="form_label" aria-hidden="true"></div>
									<div class="form_field">
										<input type='submit' name='addNewSyllabus' id="addNewSyllabus" class="button" value='Add Syllabus' />
									</div>
								</div>
							</form>
						</div>
					</div>
					<br class="clearfix" />
				</div>
				                <div class="mt-3">
                    <a href="../settings/department_option.php?option=syllabus" class="btn btn-outline-secondary">Back</a>
                </div><?php 
					include_once('../layout/sidebar.php');
				?>
				<br class="clearfix" />
			</div>
		</div>

<?php 
	include_once('../layout/footer.php');
?>
