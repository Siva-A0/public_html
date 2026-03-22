<?php require_once(__DIR__ . '/../../config.php');

   require_once(LIB_PATH . '/functions.class.php');

   $fcObj	= new DataFunctions();

   $tbStream	= TB_STREAM;

   $branches	= $fcObj->getStreams( $tbStream );
 
   $branchesCnt	= sizeof($branches);
   
	include_once('../layout/main_header.php');
	include_once('../layout/core_forms_style.php');
?>
<style type="text/css">
    .branch-page {
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

    .branch-list-hero {
        position: relative;
        overflow: hidden;
        border: 1px solid var(--bp-border);
        border-radius: 22px;
        padding: 22px 24px;
        background: linear-gradient(135deg, #f9fbfe 0%, var(--bp-surface) 100%);
        box-shadow: 0 14px 30px rgba(15, 23, 42, 0.08);
        margin-bottom: 16px;
    }
    .branch-list-hero::before { content:""; position:absolute; inset:0 auto 0 0; width:6px; background:linear-gradient(180deg,var(--bp-accent),var(--bp-accent-deep)); }

    .branch-list-title {
        margin: 0;
        font-size: 32px;
        font-weight: 800;
        letter-spacing: -0.6px;
        color: var(--bp-primary-deep);
    }

    .branch-list-subtitle {
        margin: 8px 0 0;
        font-size: 15px;
        color: var(--bp-muted);
    }

    .branch-list-card {
        background: #ffffff;
        border: 1px solid var(--bp-border);
        border-radius: 18px;
        box-shadow: 0 10px 22px rgba(15, 23, 42, 0.06);
        padding: 16px;
    }

    .branch-list-head,
    .branch-list-row {
        display: grid;
        grid-template-columns: minmax(220px, 1fr) 210px;
        align-items: center;
        gap: 12px;
    }

    .branch-list-head {
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

    .branch-list-row {
        border: 1px solid #e0e8f2;
        border-radius: 12px;
        padding: 11px 14px;
        background: #ffffff;
        margin-bottom: 10px;
    }

    .branch-list-row:last-child {
        margin-bottom: 0;
    }

    .branch-name {
        font-size: 22px;
        font-weight: 600;
        color: #1f324b;
        line-height: 1.4;
        overflow-wrap: anywhere;
    }

    .branch-actions {
        display: flex;
        justify-content: flex-end;
        gap: 8px;
        flex-wrap: wrap;
    }

    .branch-btn {
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

    .branch-btn-edit {
        background: linear-gradient(135deg, var(--bp-primary-deep), var(--bp-primary));
    }

    .branch-btn-delete {
        background: linear-gradient(135deg, #b91c1c, #dc2626);
    }

    .branch-empty {
        border: 1px dashed #cbd5e1;
        border-radius: 12px;
        background: #f8fafc;
        color: #64748b;
        font-weight: 600;
        padding: 16px;
        text-align: center;
    }

    .branch-footer {
        margin-top: 14px;
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .branch-add-btn {
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
        .branch-list-title {
            font-size: 26px;
        }

        .branch-list-head {
            display: none;
        }

        .branch-list-row {
            grid-template-columns: 1fr;
        }

        .branch-actions {
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
						<div class="branch-page">
						<div class="branch-list-hero">
                            <h3 class="branch-list-title">Manage Branches</h3>
                            <p class="branch-list-subtitle">Keep branch/specialization records aligned and easy to manage.</p>
                        </div>

                        <div class="branch-list-card">
                            <div class="branch-list-head">
                                <div>Branch Name</div>
                                <div style="text-align:right;">Actions</div>
                            </div>

                            <?php if ($branchesCnt > 0) { ?>
                                <?php for($j=0; $j< $branchesCnt; $j++){ ?>
                                    <div class="branch-list-row">
                                        <div class="branch-name">
                                            <?php echo htmlspecialchars((string)$branches[$j]['stream_code'], ENT_QUOTES, 'UTF-8'); ?>
                                        </div>
                                        <div class="branch-actions">
                                            <a class="branch-btn branch-btn-edit" href="edit_branch.php?branch=<?php echo (int)$branches[$j]['id'];?>">
                                                Edit
                                            </a>
                                            <a class="branch-btn branch-btn-delete" href="delete_branch.php?branch=<?php echo (int)$branches[$j]['id'];?>" onclick="return confirm('Do You Want To Continue To Delete');">
                                                Delete
                                            </a>
                                        </div>
                                    </div>
                                <?php } ?>
                            <?php } else { ?>
                                <div class="branch-empty">No branches found.</div>
                            <?php } ?>

                        </div>
						</div>
					</div>
					<br class="clearfix" />
				</div>
				                <div class="mt-3">
                    <a href="../settings/department_option.php?option=streams" class="btn btn-outline-secondary">Back</a>
                </div><?php 
					include_once('../layout/sidebar.php');
				?>
				<br class="clearfix" />
			</div>
		</div>

<?php 
	include_once('../layout/footer.php');
?>

<script type="text/javascript"></script>
