<?php require_once(__DIR__ . '/../../config.php');

   require_once(LIB_PATH . '/functions.class.php');

   $fcObj	= new DataFunctions();

   $tbBatch		= TB_BATCH;

   $batches		= $fcObj->getBatches( $tbBatch );
 
   $batchesCnt	= sizeof($batches);
   
	include_once('../layout/main_header.php');
	include_once('../layout/core_forms_style.php');
?>
<style type="text/css">
    .batch-page {
        --bp-primary: #173d69;
        --bp-primary-deep: #13345a;
        --bp-accent: #f0b323;
        --bp-accent-deep: #d79a12;
        --bp-surface: #eef4fa;
        --bp-border: #d9e3ef;
        --bp-muted: #6b819c;
        background: linear-gradient(180deg, #f3f7fb 0%, var(--bp-surface) 100%);
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

    .batch-list-hero {
        position: relative;
        overflow: hidden;
        border: 1px solid var(--bp-border);
        border-radius: 22px;
        padding: 22px 24px;
        background: linear-gradient(135deg, #f9fbfe 0%, var(--bp-surface) 100%);
        box-shadow: 0 14px 30px rgba(15, 23, 42, 0.08);
        margin-bottom: 16px;
    }
    .batch-list-hero::before { content:""; position:absolute; inset:0 auto 0 0; width:6px; background:linear-gradient(180deg,var(--bp-accent),var(--bp-accent-deep)); }

    .batch-list-title {
        margin: 0;
        font-size: 32px;
        font-weight: 800;
        letter-spacing: -0.6px;
        color: var(--bp-primary-deep);
    }

    .batch-list-subtitle {
        margin: 8px 0 0;
        font-size: 15px;
        color: var(--bp-muted);
    }

    .batch-list-card {
        background: #ffffff;
        border: 1px solid var(--bp-border);
        border-radius: 18px;
        box-shadow: 0 10px 22px rgba(15, 23, 42, 0.06);
        padding: 16px;
    }

    .batch-list-head,
    .batch-list-row {
        display: grid;
        grid-template-columns: minmax(220px, 1fr) 210px;
        align-items: center;
        gap: 12px;
    }

    .batch-list-head {
        border: 1px solid #dbe6f3;
        border-radius: 12px;
        background: #f7faff;
        padding: 12px 14px;
        font-size: 13px;
        font-weight: 800;
        color: #19436f;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        margin-bottom: 10px;
    }

    .batch-list-row {
        border: 1px solid #e0e8f2;
        border-radius: 12px;
        padding: 11px 14px;
        background: #ffffff;
        margin-bottom: 10px;
    }

    .batch-list-row:last-child {
        margin-bottom: 0;
    }

    .batch-name {
        font-size: 22px;
        font-weight: 600;
        color: #1f324b;
        line-height: 1.4;
        overflow-wrap: anywhere;
    }

    .batch-actions {
        display: flex;
        justify-content: flex-end;
        gap: 8px;
        flex-wrap: wrap;
    }

    .batch-btn {
        border: 0;
        border-radius: 11px;
        padding: 8px 14px;
        font-size: 14px;
        font-weight: 700;
        color: #fff;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 74px;
    }

    .batch-btn-edit {
        background: linear-gradient(135deg, var(--bp-primary-deep), var(--bp-primary));
    }

    .batch-btn-delete {
        background: linear-gradient(135deg, #b91c1c, #dc2626);
    }

    .batch-empty {
        border: 1px dashed #cbd5e1;
        border-radius: 12px;
        background: #f8fafc;
        color: #64748b;
        font-weight: 600;
        padding: 16px;
        text-align: center;
    }

    .batch-footer {
        margin-top: 14px;
    }

    .batch-add-btn {
        border: 0;
        border-radius: 12px;
        padding: 11px 20px;
        background: linear-gradient(135deg, var(--bp-primary-deep), var(--bp-primary));
        color: #fff;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 10px 20px rgba(16, 42, 72, 0.24);
    }

    @media (max-width: 768px) {
        .batch-list-title {
            font-size: 26px;
        }

        .batch-list-head {
            display: none;
        }

        .batch-list-row {
            grid-template-columns: 1fr;
        }

        .batch-actions {
            justify-content: flex-start;
        }
    }
</style>
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
