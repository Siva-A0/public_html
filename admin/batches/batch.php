<?php require_once(__DIR__ . '/../../config.php');

   require_once(LIB_PATH . '/functions.class.php');

   $fcObj	= new DataFunctions();

   $tbBatch		= TB_BATCH;

   $batches		= $fcObj->getBatches( $tbBatch );
 
   $batchesCnt	= sizeof($batches);
   
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
						<p>
							
						</p>
					</div>
					<div id='content_left' class='content_left'></div>
					<div id='content_right' class='content_right'>
						<div class="batch-page">
						<div class="batch-list-hero">
                            <h3 class="batch-list-title">Manage Batches</h3>
                            <p class="batch-list-subtitle">Keep batch/year records organized and easy to manage.</p>
                        </div>

                        <div class="batch-list-card">
                            <div class="batch-list-head">
                                <div>Batch / Year</div>
                                <div style="text-align:right;">Actions</div>
                            </div>

                            <?php if ($batchesCnt > 0) { ?>
                                <?php for($j=0; $j< $batchesCnt; $j++){ ?>
                                    <div class="batch-list-row">
                                        <div class="batch-name">
                                            <?php echo htmlspecialchars((string)$batches[$j]['batch'], ENT_QUOTES, 'UTF-8'); ?>
                                        </div>
                                        <div class="batch-actions">
                                            <a class="batch-btn batch-btn-edit" href="edit_batch.php?batch=<?php echo (int)$batches[$j]['id'];?>">
                                                Edit
                                            </a>
                                            <a class="batch-btn batch-btn-delete" href="delete_batch.php?batch=<?php echo (int)$batches[$j]['id'];?>" onclick="return confirm('Do You Want To Continue To Delete');">
                                                Delete
                                            </a>
                                        </div>
                                    </div>
                                <?php } ?>
                            <?php } else { ?>
                                <div class="batch-empty">No batches found.</div>
                            <?php } ?>

                        </div>
						</div>
					</div>
					<br class="clearfix" />
				</div>
				                <div class="mt-3">
                    <a href="../settings/department_option.php?option=batches" class="btn btn-outline-secondary">Back</a>
                </div><?php 
					include_once('../layout/sidebar.php');
				?>
				<br class="clearfix" />
			</div>
		</div>

<?php 
	include_once('../layout/footer.php');
?>

