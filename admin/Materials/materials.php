<?php
require_once(__DIR__ . '/../../config.php');
require_once(LIB_PATH . '/functions.class.php');

$fcObj = new DataFunctions();

$tbBatch    = TB_BATCH;
$tbClass    = TB_CLASS;
$tbSubjects = TB_SUBJECTS;
$tbMaterails = TB_MATERAILS;

$batches = $fcObj->getBatches($tbBatch);
$batchesCnt = sizeof($batches);
$batchId = 0;
if (isset($_GET['batchId']) && $_GET['batchId'] !== '') {
    $batchId = (int)$_GET['batchId'];
} elseif (!empty($batches)) {
    $batchId = (int)$batches[0]['id'];
}

$classes = $fcObj->getClassesWOPO($tbClass);
$classesCnt = sizeof($classes);

$subjects = array();
$materials = array();
for ($i = 0; $i < $classesCnt; $i++) {
    $classId = (int)$classes[$i]['id'];
    $subjects[$i] = ($batchId > 0) ? $fcObj->getSubjectsForClass($tbSubjects, $classId, $batchId) : array();

    $subjCnt = sizeof($subjects[$i]);
    for ($j = 0; $j < $subjCnt; $j++) {
        $subjId = (int)$subjects[$i][$j]['id'];
        $materials[$i][$j] = $fcObj->getMaterialsForSubj($tbMaterails, $subjId);
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
					</div>
					<div id='content_left' class='content_left'></div>
					<div id='content_right' class='content_right'>
						<div class="materials-page">
						<div class="materials-hero">
							<h1>Manage Materials</h1>
							<p>Browse and maintain learning materials batch-wise, class-wise, and subject-wise.</p>
						</div>
						<div class="comteeMem materials-shell">
							<form method="GET" style="margin-bottom:14px; display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
								<label for="batchId" style="font-weight:800; color:#1f324b;">Batch:</label>
								<select name="batchId" id="batchId" onchange="this.form.submit()" style="min-height:44px; padding:8px 10px; border-radius:10px; border:1px solid #c8d8ea; background:#f6faff;">
									<?php for($i=0; $i<$batchesCnt; $i++){ ?>
										<option value="<?php echo (int)$batches[$i]['id']; ?>" <?php echo ($batchId === (int)$batches[$i]['id']) ? 'selected="selected"' : ''; ?>>
											<?php echo htmlspecialchars((string)$batches[$i]['batch'], ENT_QUOTES, 'UTF-8'); ?>
										</option>
									<?php } ?>
								</select>
							</form>
							<?php
								
								for($i=0; $i< $classesCnt; $i++){
								
							?>
								<div class="materialDet">
									<div class="classHeader">
										<div class='className'>
										<?php 
											echo $classes[$i]['class_name'];
										?>
										</div>
									</div>
									<?php
								
										$subjCnt	= sizeof( $subjects[$i] );
							
										for($j=0; $j< $subjCnt; $j++){
									?>
										<div  class="subjHeader">
											<div class='subjName'>
												<?php 
													echo $subjects[$i][$j]['sub_code'];
												?>
											</div>
											<div class='subjMaterials'>
												<?php 
													$materCnt	= sizeof($materials[$i][$j]);
													
													for( $k=0;$k<$materCnt;$k++){
														?>
															<div class="eventCandName">
																<a href="<?php echo BASE_URL . '/public/uploads/materials/' . rawurlencode($materials[$i][$j][$k]['mater_file']); ?>" target="_blank">
																<?php
																	echo $materials[$i][$j][$k]['material_name'];
																?>
																</a>
															</div>
															<div  class="eventCandName">
																<a href="edit_materials.php?material=<?php echo $materials[$i][$j][$k]['id'];?>" >
																	<input type="button" class="button" value="Edit" />
																</a>
																<a href="delete_materials.php?material=<?php echo $materials[$i][$j][$k]['id'];?>" >
																	<input type="button" class="button" id="delete" value="Delete"/>
																</a>
															</div>
														<?php
													}
												?>
											</div>
										</div>
										<br class="clearfix" />
									<?php
										}
									?>
									
									<br class="clearfix" />
									</div>
							<?php 
								} 
							?>
							
						</div>
						<div  class="eventCandName">
							<a href="add_materials.php?batchId=<?php echo (int)$batchId; ?>" >
								<input type="button" class="button" value="Add Material" />
							</a>
						</div>
						</div>
					</div>
					<br class="clearfix" />
				</div>
				                <div class="mt-3">
                    <a href="../settings/department_option.php?option=materials" class="btn btn-outline-secondary">Back</a>
                </div><?php 
					include_once('../layout/sidebar.php');
				?>
				<br class="clearfix" />
			</div>
		</div>

<?php 
	include_once('../layout/footer.php');
?>


<script type="text/javascript">
	$('.document').ready(function(){
		$('#delete').click(function(){
			var conf	= confirm('Do You Want To Continue To Delete');
			if( conf ){
				
			}else{
				return false;
			}
		});
	});
</script>

