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

include_once('../layout/main_header.php');
include_once('../layout/core_forms_style.php');
?>
<style type="text/css">
	.papers-page {
		--pp-primary: #173d69;
		--pp-primary-deep: #13345a;
		--pp-accent: #f0b323;
		--pp-accent-deep: #d79a12;
		--pp-surface: #eef4fa;
		--pp-border: #d9e3ef;
		--pp-border-strong: #c8d6e6;
		--pp-muted: #6b819c;
		background: linear-gradient(180deg, #f3f7fb 0%, var(--pp-surface) 100%);
		border-radius: 24px;
		padding: 24px;
	}

	#content_left {
		display: none;
	}

	#content {
		grid-template-columns: 1fr;
		gap: 0;
	}

	#page {
		max-width: none;
	}

	.papers-hero {
		position: relative;
		overflow: hidden;
		border: 1px solid var(--pp-border);
		border-radius: 22px;
		padding: 22px 24px;
		background: linear-gradient(135deg, #f9fbfe 0%, var(--pp-surface) 100%);
		box-shadow: 0 14px 30px rgba(15, 23, 42, 0.08);
		margin-bottom: 16px;
	}

	.papers-hero::before {
		content: "";
		position: absolute;
		inset: 0 auto 0 0;
		width: 6px;
		background: linear-gradient(180deg, var(--pp-accent), var(--pp-accent-deep));
	}

	.papers-title {
		margin: 0;
		font-size: 32px;
		font-weight: 800;
		letter-spacing: -0.6px;
		color: var(--pp-primary-deep);
	}

	.papers-subtitle {
		margin: 8px 0 0;
		font-size: 15px;
		color: var(--pp-muted);
	}

	.papers-filter {
		display: flex;
		gap: 10px;
		align-items: center;
		flex-wrap: wrap;
		padding: 14px 16px;
		border: 1px solid var(--pp-border);
		border-radius: 16px;
		background: #ffffff;
		box-shadow: 0 10px 22px rgba(15, 23, 42, 0.05);
		margin-bottom: 16px;
	}

	.papers-filter label {
		font-weight: 800;
		color: var(--pp-primary);
	}

	.papers-filter select {
		min-height: 46px;
		padding: 10px 12px;
		border-radius: 12px;
		border: 1px solid var(--pp-border-strong);
		background: #f7faff;
		font-size: 15px;
		color: #1f324b;
	}

	.papers-filter select:focus {
		outline: none;
		border-color: #87a6cb;
		box-shadow: 0 0 0 4px rgba(23, 61, 105, 0.12);
		background: #ffffff;
	}

	.papers-card {
		border: 1px solid var(--pp-border);
		border-radius: 18px;
		background: #ffffff;
		box-shadow: 0 10px 22px rgba(15, 23, 42, 0.06);
		padding: 18px;
	}

	.papers-card .materialDet {
		border: 1px solid #dbe6f3;
		border-radius: 16px;
		padding: 14px;
		background: #fbfdff;
		margin-bottom: 14px;
	}

	.papers-card .materialDet:last-child {
		margin-bottom: 0;
	}

	.papers-card .classHeader {
		border-bottom: 1px solid #e3ebf5;
		margin-bottom: 12px;
		padding-bottom: 10px;
	}

	.papers-card .className {
		font-size: 22px;
		font-weight: 800;
		color: var(--pp-primary-deep);
	}

	.papers-card .subjHeader {
		display: grid;
		grid-template-columns: minmax(140px, 180px) 1fr;
		gap: 12px;
		align-items: start;
		padding: 12px 0;
		border-bottom: 1px solid #edf2f7;
	}

	.papers-card .subjHeader:last-of-type {
		border-bottom: 0;
		padding-bottom: 0;
	}

	.papers-card .subjName {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		min-height: 40px;
		padding: 8px 12px;
		border-radius: 999px;
		background: rgba(23, 61, 105, 0.08);
		color: var(--pp-primary);
		font-weight: 800;
	}

	.papers-card .subjMaterials {
		display: grid;
		gap: 10px;
	}

	.papers-card .eventCandName {
		display: flex;
		align-items: center;
		gap: 10px;
		flex-wrap: wrap;
	}

	.papers-card .eventCandName a {
		color: var(--pp-primary);
		font-weight: 700;
		text-decoration: none;
	}

	.papers-card .eventCandName a:hover {
		color: var(--pp-primary-deep);
	}

	.papers-card .button,
	.papers-footer .button {
		border: 0;
		border-radius: 11px;
		padding: 9px 15px;
		background: linear-gradient(135deg, var(--pp-primary-deep), var(--pp-primary));
		font-weight: 700;
		color: #ffffff;
		box-shadow: 0 10px 20px rgba(16, 42, 72, 0.18);
	}

	.papers-card #delete {
		background: linear-gradient(135deg, #b91c1c, #dc2626);
	}

	.papers-footer {
		margin-top: 16px;
	}

	@media (max-width: 768px) {
		.papers-title {
			font-size: 26px;
		}

		.papers-card .subjHeader {
			grid-template-columns: 1fr;
		}
	}
</style>
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
