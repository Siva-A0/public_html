<?php 
	include_once('header.php');
	
   require_once("../libraries/functions.class.php") ;

   $fcObj		= new DataFunctions();
   
   $tbClass		= TB_CLASS;
      
   $classes		= $fcObj->getClasses( $tbClass );
  
   $classesCnt	= sizeof($classes);

   $tbUsers		= TB_USERS;

   $tbBatch      = TB_BATCH;
   $batches      = $fcObj->getBatches($tbBatch);
   $batchesCnt   = sizeof($batches);
   
 ?>
			<div id="page">
				<div id="content">
					<div class="post">
						<center><h2>View Users</h2></center>
						
					</div>
					<div id='content_left' class='content_left'>
						<?php 
							include_once('user_leftnav.php');
						?>					
					</div>
					<div id='content_right' class='content_right'>
						<form id='viewUsers' action='userstatus.php' method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
							<div class="form_row">
								<div class="form_label">
									<label for='batch' >Batch:</label>
								</div>
								<div class="form_field">
									<select name="batchId" id="batchId" class="batchId">
										<option value="">SELECT</option>
										<?php for($i=0;$i<$batchesCnt;$i++){ ?>
											<option value="<?php echo (int)$batches[$i]['id']; ?>"><?php echo htmlspecialchars((string)$batches[$i]['batch'], ENT_QUOTES, 'UTF-8'); ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form_row">
								<div class="form_label">
									<label for='class' >Class:</label>
								</div>
								<div class="form_field">
									<select name="classId" id="classId" class="classId">
										<option value="">SELECT</option>
										<?php
											$classCnt	= sizeof( $classes );
											
											for( $i=0; $i< $classCnt ; $i++){
										?>
												<option value="<?php echo $classes[$i]['id']; ?>"><?php echo $classes[$i]['class_name']?></option>
										<?php
											}
										?>
									</select>
								</div>
							</div>
							<div class="form_row">
								<div class="form_label">
									<label for='section' >Section:</label>
								</div>
								<div class="form_field" id="section">
									<select name="sectionId" id="sectionId" class="sectionId">
										<option value="">SELECT</option>
										
									</select>
								</div>
							</div>
							<div class="form_row" id='users'>
			
							</div>
						</form>
					</div>
				<br class="clearfix" />
				</div>
				<?php 
					include_once('sidebar.php');
				?>
				<br class="clearfix" />
			</div>
		</div>

<script type="text/javascript" language="javascript">
	
	$(document).ready(function() {
		
		function loadSections(){
			$('#users').html('');
			var classId = $('#classId').val();
			var batchId = $('#batchId').val();
			if (!classId || !batchId) {
				$('#section').html('<select name="sectionId" id="sectionId" class="sectionId"><option value=\"\">SELECT</option></select>');
				return;
			}
			$('#section').load('sectionusers.php?classId=' + classId + '&batchId=' + batchId);
		}

		$('#classId').change(loadSections);
		$('#batchId').change(loadSections);
	});
</script>

<?php 
	include_once('footer.php');
?>
