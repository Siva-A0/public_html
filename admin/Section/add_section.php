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

