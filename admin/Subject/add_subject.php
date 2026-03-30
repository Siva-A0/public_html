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


