<?php require_once(__DIR__ . '/../../config.php');

   require_once(LIB_PATH . '/functions.class.php');

   $fcObj	= new DataFunctions();
   
   $tbClass		= TB_CLASS;
   
   $tbSyllabus	= TB_SYLLABUS;
   $tbBatch    = TB_BATCH;
   $batches    = $fcObj->getBatches($tbBatch);
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
		
		$syllabus[$i]	= $fcObj->getSyllabusForClass($tbSyllabus,$classId,$batchId);
 	}
	
	if (!isset($adminExtraStyles) || !is_array($adminExtraStyles)) {
    $adminExtraStyles = array();
}
$adminExtraStyles[] = BASE_URL . '/public/assets/css/admin/admin_academic_pages.css';

include_once('../layout/main_header.php');
	include_once('../layout/core_forms_style.php');
?>
			<div id="page">
				<div id="content" class="single-panel-layout">
					<div class="post">
						<span class="alignCenter">
							<h4>AIML Department </h4>
						</span>
						<p>
							
						</p>
					</div>
					<!-- <div id='content_left' class='content_left'>
						<?php 
							include_once('../layout/other_leftnav.php');
						?>						
					</div> -->
 					<div id='content_right' class='content_right'>
 						<div class="syllabus-list-hero">
 							<h3 class="syllabus-list-title">AIML Department</h3>
 							<p class="syllabus-list-subtitle">Manage class-wise syllabus files.</p>
 						</div>
 						<div class="comteeMem syllabus-list">
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
 							<div class="committeeTitle">
 								<div class='eventCandName'>
 									Class Name
 								</div>
								<div  class="eventCandName">
									Syllabus
								</div>
								<div class="eventCandName action-col">Actions</div>
							</div>
							<?php
								
								for($j=0; $j< $classesCnt; $j++){
									
									if( !empty( $syllabus[$j] ) ){
							?>
									<div class="usersDetHeader class-group expanded">
										<div class='eventCandName class-toggle-wrap'>
											<button type="button" class="class-toggle" aria-expanded="true" title="Collapse">
												&#9660;
											</button>
										<?php 
											echo $classes[$j]['class_name'];
										?>
										</div>
										<div  class="eventCandName class-items">
											<a href="<?php echo BASE_URL; ?>/public/uploads/syllabus/<?php echo rawurlencode($syllabus[$j][0]['syllabus_name']); ?>" target="_blank">
												Download Syllabus
											</a>
										</div>
										<div  class="eventCandName class-items">
											<a href="edit_syllabus.php?syllabus=<?php echo $syllabus[$j][0]['id'];?>" >
												<input type="button" class="button" value="Edit" />
											</a>
											<a href="delete_syllabus.php?syllabus=<?php echo $syllabus[$j][0]['id'];?>" onclick="return confirm('Do You Want To Continue To Delete');">
												<input type="button" class="button" value="Delete"/>
											</a>
										</div>
									</div>
									
									<br class="clearfix" />
							<?php 
									}
								} 
							?>
							
						</div>
					</div>
					<br class="clearfix" />
				</div>
				                <div class="mt-3">
                    <a href="../settings/department_option.php?option=syllabus" class="btn btn-outline-secondary">Back</a>
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
		var toggles = document.querySelectorAll('.syllabus-list .class-toggle');
		toggles.forEach(function (btn) {
			btn.addEventListener('click', function () {
				var group = btn.closest('.class-group');
				if (!group) return;
				var isCollapsed = group.classList.toggle('collapsed');
				group.classList.toggle('expanded', !isCollapsed);
				btn.setAttribute('aria-expanded', String(!isCollapsed));
				btn.title = isCollapsed ? 'Expand' : 'Collapse';
			});
		});
	});
</script>

