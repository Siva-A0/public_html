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
		
		$noOfUsers	= sizeof( $users );
		?>

			<div class="committeeTitle">
				<div class="checkBox">
					
				</div>
				<div class='sno'>
					S. No
				</div>
				<div  class="userNameApr">
					User Name
				</div>
				<div  class="userNameApr">
					Roll No
				</div>
				<div  class="userNameApr">
					E Mail
				</div>
			</div>
			<br class="clearfix" />
		<?php
			for($i=0;$i<$noOfUsers;$i++){
			?>
				<div class="usersDetHeader">
					<div class="checkBox">
						<input type="checkbox" name="users[]" value="<?php echo $users[$i]['id'];?>"  />
					</div>
					<div class='sno'>
					<?php 
						echo $i+1;
					?>
					</div>
					<div  class="userNameApr">
						<?php
							echo $users[$i]['username'];
						?>
					</div>
					<div  class="userNameApr">
						<?php
							echo $users[$i]['admission_id'];
						?>
					</div>
					<div  class="userNameApr">
						<?php
							echo $users[$i]['mail_id'];
						?>
					</div>
				</div>
				
				<br class="clearfix" />
			<?php							
			}
		?>
			<div class="form_row">
				<div class="form_field">
					<a href="deleteusers.php" >
						<input type='submit' name='deleteusers' class="button" value='Delete' />
					</a>
				</div>
			</div>

		<?php
}

?>

<script type="text/javascript" language="javascript">
	
	$(document).ready(function() {
		
		$('#sectionId').change( function(){

			var sectionId	= $('#sectionId').val();
			$('#users').load('../Section/sectionusers.php?sectionId=' + sectionId);
		});
	});
</script>
