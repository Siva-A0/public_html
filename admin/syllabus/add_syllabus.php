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

	if (!isset($adminExtraStyles) || !is_array($adminExtraStyles)) {
    $adminExtraStyles = array();
}
$adminExtraStyles[] = BASE_URL . '/public/assets/css/admin/admin_academic_pages.css';

include_once('../layout/main_header.php');
	include_once('../layout/core_forms_style.php');


?>

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

