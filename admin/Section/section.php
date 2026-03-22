<?php
require_once(__DIR__ . '/../../config.php');
require_once(LIB_PATH . '/functions.class.php');

$fcObj = new DataFunctions();

if (isset($_GET['classId'])) {
	$classId = (int)$_GET['classId'];
	$batchId = isset($_GET['batchId']) ? (int)$_GET['batchId'] : 0;
	
	$tbSection = TB_SECTION;
	
	$sections = $fcObj->getSections($tbSection, $classId, $batchId);
		
		?>
			<div class="form_field">
				<select name="sectionId" id="sectionId" class="sectionId">
					<option value="">SELECT</option>
					<?php
						$sectionCnt	= sizeof( $sections );
						
						for( $i=0; $i< $sectionCnt ; $i++){
					?>
							<option value="<?php echo $sections[$i]['id']; ?>"><?php echo $sections[$i]['section_name']?></option>
					<?php
						}
					?>
				</select>
			</div>
		<?php
   }

if (isset($_GET['sectionId'])) {
	$sectionId = (int)$_GET['sectionId'];
	
	$tbUsers = TB_USERS;
	
	$users = $fcObj->getUsersBySecId($tbUsers, $sectionId);
		
		?>
			<div class="form_label">
				<label for='user' >User:</label>
			</div>
			<div class="form_field">
				<select name="userId" id="userId" class="userId">
					<option value="">SELECT</option>
					<?php
						$usersCnt	= sizeof( $users );
						
						for( $i=0; $i< $usersCnt ; $i++){
					?>
							<option value="<?php echo $users[$i]['id']; ?>"><?php echo $users[$i]['username']?></option>
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
			$('#users').load('../Section/section.php?sectionId=' + sectionId);
		});
	});
</script>
