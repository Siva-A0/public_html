<?php require_once(__DIR__ . '/../../config.php');

   require_once(LIB_PATH . '/functions.class.php');

   $fcObj	= new DataFunctions();
   
   $tbClass		= TB_CLASS;
    
   $tbSubject	= TB_SUBJECTS;

   $tbBatch = TB_BATCH;
   $batches = $fcObj->getBatches($tbBatch);
   $batchesCnt = sizeof($batches);
   $batchId = 0;
   if (isset($_GET['batchId']) && $_GET['batchId'] !== '') {
       $batchId = (int)$_GET['batchId'];
   } elseif (!empty($batches)) {
       $batchId = (int)$batches[0]['id'];
   }
    
   $classes		= $fcObj->getClassesWOPO( $tbClass );
   
   $classesCnt	= sizeof($classes);
    
   for($i=0; $i<$classesCnt;$i++){
   		
		$classId		= $classes[$i]['id'];
		
		$subjects[$i]	= $fcObj->getSubjectsForClass($tbSubject,$classId,$batchId);
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
					<div id='content_left' class='content_left'>
						<?php 
							include_once('../layout/other_leftnav.php');
						?>						
					</div>
 					<div id='content_right' class='content_right'>
 						<div class="subjects-page">
						<div class="subjects-hero">
							<h1>Manage Subjects</h1>
							<p>Review and maintain subject records batch-wise and class-wise.</p>
						</div>
 						<div class="comteeMem subjects-list">
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
 							<div class="committeeTitle">Classes And Subjects</div>
							<?php
								
								for($j=0; $j< $classesCnt; $j++){
									$subjectsCnt = !empty($subjects[$j]) ? sizeof($subjects[$j]) : 0;
							?>
									<div class="class-block collapsed">
										<div class='class-header class-toggle-wrap'>
											<button type="button" class="class-toggle" aria-expanded="false" title="Expand">
												&#9660;
											</button>
											<span class="class-name"><?php echo $classes[$j]['class_name']; ?></span>
											<span class="class-count"><?php echo $subjectsCnt; ?> Subjects</span>
										</div>
										<div class='class-items class-body'>
											<div class="subject-table">
												<div class="subject-row subject-head">
													<div class="subject-col-code">Subject</div>
													<div class="subject-col-actions">Actions</div>
												</div>
											<?php
												if($subjectsCnt > 0){
												for( $k=0;$k<$subjectsCnt;$k++){
													?>
														<div class="subject-row">
															<div class="subject-col-code">
																<?php echo $subjects[$j][$k]['sub_code']; ?>
															</div>
															<div class="subject-col-actions">
																<a href="edit_subjects.php?subject=<?php echo $subjects[$j][$k]['id'];?>" >
																	<input type="button" class="button" value="Edit" />
																</a>
																<a href="delete_subjects.php?subject=<?php echo $subjects[$j][$k]['id'];?>" >
																	<input type="button" class="button delete-btn" value="Delete"/>
																</a>
															</div>
														</div>
													<?php
												}
												}else{
											?>
												<div class="subject-row">
													<div class="subject-col-code empty-row">No subjects added for this class.</div>
													<div class="subject-col-actions"></div>
												</div>
											<?php
												}
											?>
											</div>
										</div>
									</div>
									
									<br class="clearfix" />
							<?php 
								} 
							?>
							
						</div>
						</div>
					</div>
					<br class="clearfix" />
				</div>
				                <div class="mt-3">
                    <a href="../settings/department_option.php?option=subjects" class="btn btn-outline-secondary">Back</a>
                </div><?php 
					include_once('../layout/sidebar.php');
				?>
				<br class="clearfix" />
			</div>
		</div>

<?php 
	include_once('../layout/footer.php');
?>

<style type="text/css">
	.subjects-page {
		--sp-primary: #173d69;
		--sp-primary-deep: #13345a;
		--sp-accent: #f0b323;
		--sp-accent-deep: #d79a12;
		--sp-accent-soft: #fff5da;
		--sp-surface: #eef4fa;
		--sp-border: #d9e3ef;
		--sp-border-strong: #c8d6e6;
		--sp-muted: #6b819c;
		background: linear-gradient(180deg, #f3f7fb 0%, var(--sp-surface) 100%);
		border-radius: 24px;
		padding: 24px;
	}

	#content {
		grid-template-columns: 1fr !important;
	}

	#content_left {
		display: none !important;
	}

	#content_right {
		max-width: none;
	}

	.subjects-hero {
		position: relative;
		overflow: hidden;
		border: 1px solid var(--sp-border);
		border-radius: 22px;
		padding: 22px 24px;
		background: linear-gradient(135deg, #f9fbfe 0%, var(--sp-surface) 100%);
		box-shadow: 0 14px 30px rgba(15, 23, 42, 0.08);
		margin-bottom: 16px;
	}

	.subjects-hero::before {
		content: "";
		position: absolute;
		inset: 0 auto 0 0;
		width: 6px;
		background: linear-gradient(180deg, var(--sp-accent), var(--sp-accent-deep));
	}

	.subjects-hero h1 {
		margin: 0 0 6px;
		font-size: 32px;
		font-weight: 800;
		letter-spacing: -0.6px;
		color: var(--sp-primary-deep);
	}

	.subjects-hero p {
		margin: 0;
		font-size: 15px;
		color: var(--sp-muted);
	}

	.subjects-list .committeeTitle {
		display: block;
		color: var(--sp-primary-deep);
		font-weight: 800;
		font-size: 22px;
		margin-bottom: 16px;
	}

	.subjects-list .class-block {
		border: 1px solid var(--sp-border);
		border-radius: 14px;
		background: #f8fafc;
		margin-bottom: 16px;
		overflow: hidden;
	}

	.subjects-list .class-toggle-wrap {
		display: flex;
		align-items: center;
		gap: 10px;
		font-weight: 700;
		padding: 14px 16px;
		background: #f3f7fb;
		border-bottom: 1px solid var(--sp-border);
		cursor: pointer;
	}

	.subjects-list .class-name {
		font-size: 17px;
		color: var(--sp-primary-deep);
	}

	.subjects-list .class-count {
		margin-left: auto;
		font-size: 13px;
		font-weight: 700;
		color: #8b6510;
		background: var(--sp-accent-soft);
		padding: 4px 10px;
		border-radius: 999px;
	}

	.subjects-list .class-toggle {
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

	.subjects-list .class-toggle-wrap:focus {
		outline: 2px solid #87a6cb;
		outline-offset: 2px;
	}

	.subjects-list .class-body {
		padding: 12px 16px;
	}

	.subjects-list .subject-table {
		display: block;
	}

	.subjects-list .subject-row {
		display: grid;
		grid-template-columns: 1fr auto;
		gap: 12px;
		align-items: center;
		padding: 10px 0;
		border-bottom: 1px solid #e2e8f0;
	}

	.subjects-list .subject-row:last-child {
		border-bottom: 0;
	}

	.subjects-list .subject-head {
		padding-top: 0;
		font-weight: 700;
		color: var(--sp-primary);
	}

	.subjects-list .subject-col-code {
		font-size: 15px;
		color: #1e293b;
	}

	.subjects-list .subject-col-actions {
		display: flex;
		gap: 8px;
	}

	.subjects-list .subject-col-actions .button {
		border: 0;
		border-radius: 10px;
		padding: 8px 14px;
		font-size: 14px;
		font-weight: 700;
		color: #fff;
		background: linear-gradient(135deg, var(--sp-primary-deep), var(--sp-primary));
	}

	.subjects-list .subject-col-actions .delete-btn {
		background: linear-gradient(135deg, #b91c1c, #dc2626);
	}

	.subjects-list .empty-row {
		color: var(--sp-muted);
		font-style: italic;
	}

	.subjects-list .class-block.collapsed .class-items {
		display: none;
	}

	.subjects-list .class-block.collapsed .class-toggle {
		transform: rotate(-90deg);
	}

	@media (max-width: 980px) {
		.subjects-list .class-count {
			display: none;
		}

		.subjects-list .subject-row {
			grid-template-columns: 1fr;
		}

		.subjects-list .subject-col-actions {
			justify-content: flex-start;
		}
	}
</style>

<script type="text/javascript">
	document.addEventListener('DOMContentLoaded', function () {
		var deleteButtons = document.querySelectorAll('.delete-btn');
		deleteButtons.forEach(function (btn) {
			btn.addEventListener('click', function (event) {
				var conf = confirm('Do You Want To Continue To Delete');
				if (!conf) {
					event.preventDefault();
				}
			});
		});

		var groups = document.querySelectorAll('.subjects-list .class-block');

		function toggleGroup(group) {
			if (!group) return;
			var btn = group.querySelector('.class-toggle');
			var isCollapsed = group.classList.toggle('collapsed');
			group.classList.toggle('expanded', !isCollapsed);
			if (btn) {
				btn.setAttribute('aria-expanded', String(!isCollapsed));
				btn.title = isCollapsed ? 'Expand' : 'Collapse';
			}
		}

		groups.forEach(function (group) {
			var header = group.querySelector('.class-toggle-wrap');
			var btn = group.querySelector('.class-toggle');

			if (!header || !btn) return;

			header.setAttribute('role', 'button');
			header.setAttribute('tabindex', '0');

			btn.addEventListener('click', function (event) {
				event.stopPropagation();
				toggleGroup(group);
			});

			header.addEventListener('click', function () {
				toggleGroup(group);
			});

			header.addEventListener('keydown', function (event) {
				if (event.key === 'Enter' || event.key === ' ') {
					event.preventDefault();
					toggleGroup(group);
				}
			});
		});
	});
</script>
