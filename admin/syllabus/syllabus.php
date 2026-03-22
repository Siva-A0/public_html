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

<style type="text/css">
	#content.single-panel-layout {
		grid-template-columns: minmax(320px, 840px);
		justify-content: center;
		gap: 0;
	}

	#content.single-panel-layout .post {
		display: none;
	}

	#content.single-panel-layout #content_right {
		grid-column: 1;
		width: 100%;
	}

	.syllabus-list-hero {
		width: 100%;
		max-width: 840px;
		border: 1px solid #d9e3ef;
		border-radius: 18px;
		padding: 18px 22px;
		background:
			linear-gradient(135deg, #f9fbfe 0%, #eef4fa 100%);
		box-shadow: 0 14px 30px rgba(15, 23, 42, 0.08);
		margin-bottom: 16px;
	}

	.syllabus-list-title {
		margin: 0;
		font-size: 32px;
		font-weight: 800;
		letter-spacing: -0.6px;
		color: #13345a;
	}

	.syllabus-list-subtitle {
		margin: 8px 0 0;
		font-size: 15px;
		color: #6b819c;
	}

	#content.single-panel-layout #content_right .comteeMem.syllabus-list {
		width: 100%;
		max-width: 840px;
		border: 1px solid #d7dde6;
		border-radius: 16px;
		box-shadow: 0 10px 22px rgba(15, 23, 42, 0.06);
		padding: 24px;
	}

	.syllabus-list .committeeTitle,
	.syllabus-list .usersDetHeader {
		display: grid;
		grid-template-columns: minmax(180px, 1fr) minmax(180px, 1fr) auto;
		gap: 12px;
		align-items: center;
	}

	.syllabus-list .committeeTitle .action-col {
		text-align: right;
	}

	.syllabus-list .usersDetHeader {
		margin-top: 10px;
	}

	.syllabus-list .usersDetHeader .eventCandName:last-child {
		display: flex;
		justify-content: flex-end;
		gap: 8px;
	}

	.syllabus-list .usersDetHeader .eventCandName a {
		color: #173d69;
		font-weight: 600;
	}

	.syllabus-list .class-toggle-wrap {
		display: flex;
		align-items: center;
		gap: 10px;
		font-weight: 700;
	}

	.syllabus-list .class-toggle {
		width: 30px;
		height: 30px;
		border: 1px solid #cbd5e1;
		border-radius: 8px;
		background: #ffffff;
		color: #173d69;
		font-size: 12px;
		line-height: 1;
		cursor: pointer;
		display: inline-flex;
		align-items: center;
		justify-content: center;
	}

	.syllabus-list .class-group.collapsed .class-items {
		display: none;
	}

	.syllabus-list .class-group.collapsed .class-toggle {
		transform: rotate(-90deg);
	}

	.syllabus-actions {
		margin-top: 16px;
	}

	.syllabus-actions .button {
		border: 0;
		border-radius: 12px;
		padding: 11px 22px;
		background: linear-gradient(135deg, #13345a, #173d69);
		font-size: 18px;
		font-weight: 700;
		box-shadow: 0 10px 20px rgba(16, 42, 72, 0.24);
	}

	.syllabus-actions .button:hover {
		filter: brightness(1.06);
	}

	@media (max-width: 768px) {
		.syllabus-list .committeeTitle,
		.syllabus-list .usersDetHeader {
			grid-template-columns: 1fr;
			gap: 8px;
		}

		.syllabus-list .committeeTitle .action-col,
		.syllabus-list .usersDetHeader .eventCandName:last-child {
			text-align: left;
			justify-content: flex-start;
		}
	}
</style>

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
