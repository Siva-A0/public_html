<?php

   require_once(__DIR__ . '/../../config.php');
   require_once(LIB_PATH . '/functions.class.php');

   $fcObj	= new DataFunctions();
   
   if(isset($_GET['classId'])){
    		
		$classId	= (int)$_GET['classId'];
		$batchId	= isset($_GET['batchId']) ? (int)$_GET['batchId'] : 0;
		
		$tbSubject	= TB_SUBJECTS;
		
		$subjects	= $fcObj->getSubjectsForClass( $tbSubject, $classId, $batchId);
		
		?>
			<div class="form_field">
				<select name="subjId" id="subjId" class="subjId">
					<option value="">SELECT</option>
					<?php
						$subjsCnt	= sizeof( $subjects );
						
						for( $i=0; $i< $subjsCnt ; $i++){
					?>
							<option value="<?php echo (int)$subjects[$i]['id']; ?>">
								<?php
								$code = trim((string)($subjects[$i]['sub_code'] ?? ''));
								$name = trim((string)($subjects[$i]['sub_name'] ?? ''));
								$label = $code;
								if ($name !== '') {
									$label = ($code !== '') ? ($code . ' - ' . $name) : $name;
								}
								echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8');
								?>
							</option>
					<?php
						}
					?>
				</select>
			</div>
		<?php
   }


?>

<script type="text/javascript" language="javascript">
	
	$(document).ready(function() {
		
		$('#sectionId').change( function(){

			var sectionId	= $('#sectionId').val();
			$('#users').load('section.php?sectionId='+sectionId);
		});
	});
</script>
