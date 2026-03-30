<?php require_once(__DIR__ . '/../../config.php');?>

<?php 
 

// require_once("libraries/functions.class.php");
require_once(LIB_PATH . '/functions.class.php');

   $fcObj	= new DataFunctions();
   
   $tbClass		= TB_CLASS;
   
   $tbSyllabus	= TB_SYLLABUS;
   $tbBatch       = TB_BATCH;
   $batches       = $fcObj->getBatches($tbBatch);
   $batchesCnt    = sizeof($batches);
   
   $classes		= $fcObj->getClassesWOPO( $tbClass );
  
   $classesCnt	= sizeof($classes);
   
   $sylId = 0;
   if (isset($_GET['syllabus']) && $_GET['syllabus'] !== '') {
	   $sylId = (int)$_GET['syllabus'];
   } elseif (isset($_POST['sylId']) && $_POST['sylId'] !== '') {
	   $sylId = (int)$_POST['sylId'];
   }

   if ($sylId > 0) {
	   $syllabus = $fcObj->getSyllabusById($tbSyllabus, $sylId);
   } else {
	   $syllabus = array();
   }

   if (empty($syllabus)) {
	   header('Location: syllabus.php');
	   exit;
   }
   
   if ( isset ( $_POST['editSyllabus'] ) ){
    				
 		$varArray['class_id']		= $_POST['classId'];
 		$varArray['syl_id']			= intval($_POST['sylId'] ?? 0);
		$varArray['batch_id']        = (int)($_POST['batchId'] ?? 0);
		if ($varArray['batch_id'] <= 0) {
			$msg = 'Please select a batch.';
		}
 		
 		if( isset( $_FILES['syllabusFile'] ) ){
		
			$fileName	= $_FILES['syllabusFile']['name'];
			$uploadDir   = ROOT_PATH . '/public/uploads/syllabus/';

			if (!is_dir($uploadDir)) {
				@mkdir($uploadDir, 0777, true);
			}
			
			if ((move_uploaded_file($_FILES['syllabusFile']['tmp_name'], $uploadDir . $fileName))){
				
				$prevFile	= $_POST['syllabusName'];
				$prevPath    = $uploadDir . $prevFile;
				if ($prevFile !== '' && file_exists($prevPath)) {
					@unlink($prevPath);
				}
				$fileName 	= $fileName;
			}else{
			
				$fileName 	= $_POST['syllabusName'];
			}
		}else{
			$fileName 	= $_POST['syllabusName'];
		}
		
 		$varArray['syllabus_name']	= $fileName;

		$editSyllabus = false;
		if (!isset($msg)) {
			$editSyllabus	= $fcObj->editSyllabus ( $tbSyllabus, $varArray );
		}
		
		if( $editSyllabus ){
			
			$redirect = 'syllabus.php';
			if (!empty($varArray['batch_id'])) {
				$redirect .= '?batchId=' . (int)$varArray['batch_id'];
			}
			header('Location: ' . $redirect);
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
						<div class="edit-syllabus-page">
							<div class="edit-syllabus-hero">
								<h3 class="edit-syllabus-title">Edit Syllabus</h3>
								<p class="edit-syllabus-subtitle">Update class-wise syllabus files in the same school-branded academic workspace.</p>
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
							<form id='editSyllabus' action='edit_syllabus.php' method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
								<div class="form_row">
									<div class="form_label">
										<label for="batchId">Batch:</label>
									</div>
									<div class="form_field">
										<select name="batchId" id="batchId" required>
											<option value="">SELECT</option>
											<?php for($i=0; $i<$batchesCnt; $i++){ ?>
												<option value="<?php echo (int)$batches[$i]['id']; ?>" <?php echo ((int)($syllabus[0]['batch_id'] ?? 0) === (int)$batches[$i]['id']) ? 'selected="selected"' : ''; ?>>
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
											<?php
												for($i=0;$i<$classesCnt;$i++){
													if( $classes[$i]['id'] == $syllabus[0]['class_id'] ){
													?>
														<option value="<?php echo $classes[$i]['id']; ?>"><?php echo $classes[$i]['class_name']; ?></option>
													<?php
													}
												}
											?>
											<?php
												for($i=0;$i<$classesCnt;$i++){
													if( $classes[$i]['id'] != $syllabus[0]['class_id'] ){
													?>
														<option value="<?php echo $classes[$i]['id']; ?>"><?php echo $classes[$i]['class_name']; ?></option>
													<?php
													}
												}
											?>
										</select>
									</div>
								</div>
								<div class="form_row">
									<div class="form_label">
										<label for="syllabus">Syllabus :</label>
									</div>
									<div class="form_field">
										<input type="file" name="syllabusFile" id="syllabusFile" />
									</div>
								</div>
								<div class="form_row">
									<div class="form_label">
										
									</div>
									<div class="form_field">
										<input type="hidden" name="syllabusName" id="syllabusName" value="<?php echo $syllabus[0]['syllabus_name']; ?>"/>
										<input type="hidden" name="sylId" id="sylId" value="<?php echo (int)($syllabus[0]['id'] ?? 0); ?>"/>
										<input type='submit' name='editSyllabus' class="button" value='Edit Syllabus' />
									</div>
								</div>
							</form>
						</div>
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


