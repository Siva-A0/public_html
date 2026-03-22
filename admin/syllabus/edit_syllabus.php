<?php require_once(__DIR__ . '/../../config.php');?>
<style type="text/css">
	.edit-syllabus-page {
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
	#content_left { display: none; }
	#content { grid-template-columns: minmax(320px, 920px); justify-content: center; gap: 0; }
	#page { max-width: none; }
	.edit-syllabus-hero {
		position: relative;
		overflow: hidden;
		border: 1px solid var(--sp-border);
		border-radius: 22px;
		padding: 22px 24px;
		background: linear-gradient(135deg, #f9fbfe 0%, var(--sp-surface) 100%);
		box-shadow: 0 14px 30px rgba(15, 23, 42, 0.08);
		margin-bottom: 16px;
	}
	.edit-syllabus-hero::before {
		content: "";
		position: absolute;
		inset: 0 auto 0 0;
		width: 6px;
		background: linear-gradient(180deg, var(--sp-accent), var(--sp-accent-deep));
	}
	.edit-syllabus-title { margin: 0; font-size: 32px; font-weight: 800; letter-spacing: -0.6px; color: var(--sp-primary-deep); }
	.edit-syllabus-subtitle { margin: 8px 0 0; font-size: 15px; color: var(--sp-muted); }
	#content_right .comteeMem {
		max-width: 920px;
		border: 1px solid var(--sp-border);
		border-radius: 18px;
		box-shadow: 0 10px 22px rgba(15, 23, 42, 0.06);
		padding: 24px;
	}
	#editSyllabus .form_label label { font-size: 16px; font-weight: 700; color: var(--sp-primary); }
	#editSyllabus .form_field input[type="file"],
	#editSyllabus .form_field select {
		width: 100%;
		min-height: 52px;
		border: 1px solid var(--sp-border-strong);
		border-radius: 12px;
		padding: 11px 14px;
		background: #f7f9fc;
		font-size: 16px;
	}
	#editSyllabus .button {
		border: 0;
		border-radius: 12px;
		padding: 11px 20px;
		background: linear-gradient(135deg, #13345a, #173d69);
		font-weight: 700;
		box-shadow: 0 10px 20px rgba(16, 42, 72, 0.24);
	}
</style>

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

