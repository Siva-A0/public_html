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

