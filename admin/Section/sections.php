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

