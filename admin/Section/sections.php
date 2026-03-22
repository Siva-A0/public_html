<?php require_once(__DIR__ . '/../../config.php');

   require_once(LIB_PATH . '/functions.class.php');

   $fcObj	= new DataFunctions();
   
   $tbClass		= TB_CLASS;
   
   $tbSection	= TB_SECTION;
   $tbBatch     = TB_BATCH;
   
   $classes		= $fcObj->getClassesWOPO( $tbClass );
   $batches      = $fcObj->getBatches($tbBatch);
   $currentBatchId = isset($_GET['batchId']) ? (int)$_GET['batchId'] : 0;
   if ($currentBatchId <= 0 && !empty($batches)) {
        $currentBatchId = (int)$batches[0]['id'];
   }
  
   $classesCnt	= sizeof($classes);
   
   for($i=0; $i<$classesCnt;$i++){
  		
		$classId		= $classes[$i]['id'];
		
		$sections[$i]	= $fcObj->getSections($tbSection, $classId, $currentBatchId);
	}
	
	include_once('../layout/main_header.php');
	include_once('../layout/core_forms_style.php');
?>
<style type="text/css">
	.section-manage-page {
		--sp-primary: #173d69;
		--sp-primary-deep: #13345a;
		--sp-accent: #f0b323;
		--sp-accent-deep: #d79a12;
		--sp-accent-soft: #fff5da;
		--sp-surface: #eef4fa;
		--sp-border: #d9e3ef;
		--sp-border-strong: #c8d6e6;
		--sp-muted: #6b819c;
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

	.section-manage-page {
		background: linear-gradient(180deg, #f3f7fb 0%, var(--sp-surface) 100%);
		border-radius: 24px;
		padding: 24px;
	}

	.section-list-hero {
		position: relative;
		overflow: hidden;
		border: 1px solid var(--sp-border);
		border-radius: 22px;
		padding: 22px 24px;
		background: linear-gradient(135deg, #f9fbfe 0%, var(--sp-surface) 100%);
		box-shadow: 0 14px 30px rgba(15, 23, 42, 0.08);
		margin-bottom: 16px;
	}

	.section-list-hero::before {
		content: "";
		position: absolute;
		inset: 0 auto 0 0;
		width: 6px;
		background: linear-gradient(180deg, var(--sp-accent), var(--sp-accent-deep));
	}

	.section-list-title {
		margin: 0;
		font-size: 32px;
		font-weight: 800;
		letter-spacing: -0.6px;
		color: var(--sp-primary-deep);
	}

	.section-list-subtitle {
		margin: 8px 0 0;
		font-size: 15px;
		color: var(--sp-muted);
	}

	.section-list-card {
		background: #ffffff;
		border: 1px solid var(--sp-border);
		border-radius: 18px;
		box-shadow: 0 10px 22px rgba(15, 23, 42, 0.06);
		padding: 16px;
	}

	.section-group {
		border: 1px solid var(--sp-border);
		border-radius: 14px;
		background: #f8fbff;
		margin-bottom: 12px;
		overflow: hidden;
	}

	.section-group:last-child {
		margin-bottom: 0;
	}

	.section-group-head {
		display: flex;
		align-items: center;
		gap: 10px;
		padding: 12px 14px;
		background: #f3f7fb;
		border-bottom: 1px solid var(--sp-border);
	}

	.section-toggle {
		width: 30px;
		height: 30px;
		border: 1px solid var(--sp-border-strong);
		border-radius: 8px;
		background: #ffffff;
		color: var(--sp-primary);
		font-size: 12px;
		line-height: 1;
		cursor: pointer;
		display: inline-flex;
		align-items: center;
		justify-content: center;
	}

	.section-group.collapsed .section-toggle {
		transform: rotate(-90deg);
	}

	.section-class-name {
		font-size: 19px;
		font-weight: 700;
		color: var(--sp-primary-deep);
		margin: 0;
	}

	.section-table-head,
	.section-row {
		display: grid;
		grid-template-columns: minmax(160px, 1fr) 210px;
		align-items: center;
		gap: 12px;
		padding: 10px 14px;
	}

	.section-table-head {
		font-size: 13px;
		font-weight: 800;
		color: var(--sp-primary);
		text-transform: uppercase;
		letter-spacing: 0.4px;
		background: #f9fcff;
		border-bottom: 1px solid #e0e8f2;
	}

	.section-row {
		background: #ffffff;
		border-bottom: 1px solid #e8edf5;
	}

	.section-row:last-child {
		border-bottom: 0;
	}

	.section-code {
		font-size: 17px;
		color: var(--sp-primary-deep);
		overflow-wrap: anywhere;
	}

	.section-actions {
		display: flex;
		justify-content: flex-end;
		gap: 8px;
		flex-wrap: wrap;
	}

	.section-btn {
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

	.section-btn-edit {
		background: linear-gradient(135deg, var(--sp-primary-deep), var(--sp-primary));
	}

	.section-btn-delete {
		background: linear-gradient(135deg, #b91c1c, #dc2626);
	}

	.section-group.collapsed .section-body {
		display: none;
	}

	.section-empty {
		border: 1px dashed #cbd5e1;
		border-radius: 12px;
		background: #f8fafc;
		color: #64748b;
		font-weight: 600;
		padding: 16px;
		text-align: center;
	}

	.section-footer {
		margin-top: 14px;
	}

	.section-add-btn {
		border: 0;
		border-radius: 12px;
		padding: 11px 20px;
		background: linear-gradient(135deg, var(--sp-primary-deep), var(--sp-primary));
		color: #fff;
		font-weight: 700;
		text-decoration: none;
		display: inline-flex;
		align-items: center;
		gap: 6px;
		box-shadow: 0 10px 20px rgba(16, 42, 72, 0.24);
	}

	@media (max-width: 768px) {
		.section-list-title {
			font-size: 26px;
		}

		.section-table-head {
			display: none;
		}

		.section-row {
			grid-template-columns: 1fr;
		}

		.section-actions {
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
						<div class="section-manage-page">
						<div class="section-list-hero">
							<h3 class="section-list-title">Manage Sections</h3>
							<p class="section-list-subtitle">Choose a batch, then manage sections year-wise (A/B/C can vary per batch).</p>
						</div>

                        <?php if (!empty($batches)) { ?>
                        <form method="get" action="" class="mb-3" style="max-width:520px;">
                            <label for="batchId" class="form-label" style="font-weight:800; color:#1f324b;">Batch</label>
                            <select name="batchId" id="batchId" class="form-select" onchange="this.form.submit()">
                                <?php foreach ($batches as $b) { ?>
                                    <option value="<?php echo (int)$b['id']; ?>" <?php echo ((int)$b['id'] === (int)$currentBatchId) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars((string)$b['batch'], ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </form>
                        <?php } else { ?>
                            <div class="alert alert-warning">No batches found. Add an academic batch first.</div>
                        <?php } ?>
						<div class="section-list-card">
							<?php for($j=0; $j< $classesCnt; $j++){ ?>
								<?php if( !empty( $sections[$j] ) ){ ?>
									<div class="section-group expanded">
										<div class="section-group-head">
											<button type="button" class="section-toggle" aria-expanded="true" title="Collapse">
												&#9660;
											</button>
											<p class="section-class-name"><?php echo htmlspecialchars((string)$classes[$j]['class_name'], ENT_QUOTES, 'UTF-8'); ?></p>
										</div>
										<div class="section-body">
											<div class="section-table-head">
												<div>Section</div>
												<div style="text-align:right;">Actions</div>
											</div>
											<?php $sectionsCnt = sizeof($sections[$j]); ?>
											<?php for( $k=0; $k<$sectionsCnt; $k++){ ?>
												<div class="section-row">
													<div class="section-code">
														<?php echo htmlspecialchars((string)$sections[$j][$k]['section_code'], ENT_QUOTES, 'UTF-8'); ?>
													</div>
													<div class="section-actions">
														<a class="section-btn section-btn-edit" href="edit_sections.php?section=<?php echo (int)$sections[$j][$k]['id']; ?>">
															Edit
														</a>
														<a class="section-btn section-btn-delete" href="delete_sections.php?section=<?php echo (int)$sections[$j][$k]['id']; ?>" onclick="return confirm('Do You Want To Continue To Delete');">
															Delete
														</a>
													</div>
												</div>
											<?php } ?>
										</div>
									</div>
								<?php } ?>
							<?php } ?>

							<?php
								$hasSections = false;
								for($x=0; $x<$classesCnt; $x++){
									if (!empty($sections[$x])) {
										$hasSections = true;
										break;
									}
								}
								if (!$hasSections) {
									echo '<div class="section-empty">No sections found.</div>';
								}
							?>

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

<script type="text/javascript">
	document.addEventListener('DOMContentLoaded', function () {
		var toggles = document.querySelectorAll('.section-toggle');
		toggles.forEach(function (btn) {
			btn.addEventListener('click', function () {
				var group = btn.closest('.section-group');
				if (!group) return;
				var isCollapsed = group.classList.toggle('collapsed');
				group.classList.toggle('expanded', !isCollapsed);
				btn.setAttribute('aria-expanded', String(!isCollapsed));
				btn.title = isCollapsed ? 'Expand' : 'Collapse';
			});
		});
	});
</script>
