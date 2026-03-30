<?php
require_once(__DIR__ . '/../../config.php');
require_once(LIB_PATH . '/functions.class.php');

$fcObj = new DataFunctions();

$tbBatch    = TB_BATCH;
$tbClass    = TB_CLASS;
$tbSubjects = TB_SUBJECTS;
$tbPrevPapers = TB_PREV_PAPERS;

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
$prevPapers = array();
for ($i = 0; $i < $classesCnt; $i++) {
    $classId = (int)$classes[$i]['id'];
    $subjects[$i] = ($batchId > 0) ? $fcObj->getSubjectsForClass($tbSubjects, $classId, $batchId) : array();

    $subjCnt = sizeof($subjects[$i]);
    for ($j = 0; $j < $subjCnt; $j++) {
        $subjId = (int)$subjects[$i][$j]['id'];
        $prevPapers[$i][$j] = $fcObj->getPrePapersForSubj($tbPrevPapers, $subjId);
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
						<div class="papers-page">
						<div class="papers-hero">
							<h3 class="papers-title">Previous Papers</h3>
							<p class="papers-subtitle">Manage class-wise previous question papers in the same branded academic workspace.</p>
						</div>
						<div class="papers-filter">
							<form method="GET" style="display:flex; gap:10px; align-items:center; flex-wrap:wrap; margin:0;">
								<label for="batchId" style="font-weight:800; color:#1f324b;">Batch:</label>
								<select name="batchId" id="batchId" onchange="this.form.submit()" style="min-height:44px; padding:8px 10px; border-radius:10px; border:1px solid #c8d8ea; background:#f6faff;">
									<?php for($i=0; $i<$batchesCnt; $i++){ ?>
										<option value="<?php echo (int)$batches[$i]['id']; ?>" <?php echo ($batchId === (int)$batches[$i]['id']) ? 'selected="selected"' : ''; ?>>
											<?php echo htmlspecialchars((string)$batches[$i]['batch'], ENT_QUOTES, 'UTF-8'); ?>
										</option>
									<?php } ?>
								</select>
							</form>
						</div>
						<div class="papers-card">
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
													$papersCnt	= sizeof($prevPapers[$i][$j]);
													
													for( $k=0;$k<$papersCnt;$k++){
														?>
															<div class="eventCandName">
																<a href="<?php echo BASE_URL . '/public/uploads/previous_papers/' . rawurlencode($prevPapers[$i][$j][$k]['paper_file']); ?>" target="_blank">
																<?php
																	echo $prevPapers[$i][$j][$k]['paper_name'];
																?>
																</a>
															</div>
															<div  class="eventCandName">
																<a href="edit_papers.php?paper=<?php echo $prevPapers[$i][$j][$k]['id'];?>" >
																	<input type="button" class="button" value="Edit" />
																</a>
																<a href="delete_papers.php?paper=<?php echo $prevPapers[$i][$j][$k]['id'];?>" >
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
						<div class="papers-footer eventCandName">
							<a href="add_papers.php?batchId=<?php echo (int)$batchId; ?>" >
								<input type="button" class="button" value="Add Previous Papers" />
							</a>
						</div>
						</div>
					</div>
					<br class="clearfix" />
				</div>
				                <div class="mt-3">
                    <a href="../settings/department_option.php?option=previous_papers" class="btn btn-outline-secondary">Back</a>
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

