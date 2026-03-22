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

include_once('../layout/main_header.php');
include_once('../layout/core_forms_style.php');
?>
<style type="text/css">
	.materials-page {
		--mp-primary: #173d69;
		--mp-primary-deep: #13345a;
		--mp-accent: #f0b323;
		--mp-accent-deep: #d79a12;
		--mp-accent-soft: #fff5da;
		--mp-surface: #eef4fa;
		--mp-border: #d9e3ef;
		--mp-border-strong: #c8d6e6;
		--mp-muted: #6b819c;
		background: linear-gradient(180deg, #f3f7fb 0%, var(--mp-surface) 100%);
		border-radius: 24px;
		padding: 24px;
	}

	#content { grid-template-columns: 1fr !important; }
	#content_left { display: none !important; }
	#content_right { max-width: none; }

	.materials-hero {
		position: relative;
		overflow: hidden;
		border: 1px solid var(--mp-border);
		border-radius: 22px;
		padding: 22px 24px;
		background: linear-gradient(135deg, #f9fbfe 0%, var(--mp-surface) 100%);
		box-shadow: 0 14px 30px rgba(15, 23, 42, 0.08);
		margin-bottom: 16px;
	}

	.materials-hero::before {
		content: "";
		position: absolute;
		inset: 0 auto 0 0;
		width: 6px;
		background: linear-gradient(180deg, var(--mp-accent), var(--mp-accent-deep));
	}

	.materials-hero h1 {
		margin: 0 0 6px;
		font-size: 32px;
		font-weight: 800;
		letter-spacing: -0.6px;
		color: var(--mp-primary-deep);
	}

	.materials-hero p {
		margin: 0;
		font-size: 15px;
		color: var(--mp-muted);
	}

	.materials-shell.comteeMem {
		border: 1px solid var(--mp-border);
		border-radius: 18px;
		box-shadow: 0 10px 22px rgba(15, 23, 42, 0.06);
		padding: 24px;
		background: #fff;
	}

	.materialDet {
		border: 1px solid var(--mp-border);
		border-radius: 16px;
		background: #f8fafc;
		padding: 16px;
		margin-bottom: 16px;
	}

	.classHeader {
		padding: 12px 14px;
		border-radius: 12px;
		background: #f3f7fb;
		border: 1px solid var(--mp-border);
		margin-bottom: 12px;
	}

	.className {
		font-size: 20px;
		font-weight: 800;
		color: var(--mp-primary-deep);
	}

	.subjHeader {
		border: 1px solid #e1e8f1;
		border-radius: 12px;
		background: #fff;
		padding: 12px 14px;
		margin-bottom: 10px;
	}

	.subjName {
		font-size: 16px;
		font-weight: 700;
		color: var(--mp-primary);
		margin-bottom: 10px;
	}

	.subjMaterials {
		display: grid;
		gap: 8px;
	}

	.eventCandName a {
		color: var(--mp-primary-deep);
		font-weight: 600;
		text-decoration: none;
	}

	.eventCandName .button {
		border: 0;
		border-radius: 10px;
		padding: 8px 14px;
		font-size: 14px;
		font-weight: 700;
		color: #fff;
		background: linear-gradient(135deg, var(--mp-primary-deep), var(--mp-primary));
	}

	.eventCandName #delete.button {
		background: linear-gradient(135deg, #b91c1c, #dc2626);
	}
</style>
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
