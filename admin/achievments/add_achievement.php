<?php 
	
   require_once("Department/libraries/functions.class.php") ;

   $fcObj	= new DataFunctions();

   
   $tbAchievement	= TB_ACHIEVEMENTS;

   if ( isset ( $_POST['addNewAchievement'] ) ){
   				
		$varArray['typeId']		= $_POST['typeId'];

		if(  $_POST['typeId'] == DOCUMENT ){
			$docTitle	= $_POST['documentTitle'];
			
			$fileName	= $_FILES['achievementFile']['name'];
		
			if ((move_uploaded_file($_FILES['achievementFile']['tmp_name'], "../../public/assets/images/".$fileName))){
									
				$fileName 	= $fileName;
			}else{
			
				$fileName 	= '';
			}
			
			$varArray['achievement_desc']	= $docTitle.'$$'.$fileName;
			
		}else if(  $_POST['typeId'] == NON_DOCUMENT ){
			$varArray['achievement_desc']	= $_POST['documentName'];
			
		}
		
		$addAchieve	= $fcObj->addAchievement ( $tbAchievement, $varArray );
		
		if( $addAchieve ){
			
			header('Location: achievements.php');
			return false;
		}else{
			$msg	= 'Sorry, Please try again';
		}
   }

	include_once('admin/header.php');


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
							include_once('admin/Department/departleftnav.php');
						?>						
					</div>
					<div id='content_right' class='content_right'>
						<div class="legacy-admin-form">
							<div class="hero">
								<h3>Add Achievement</h3>
								<!-- <p>Create a new achievement entry or upload an achievement document with the same school-branded styling.</p> -->
							</div>
						<div class="comteeMem">
							<?php
								if( isset ( $msg ) ){
							?>
								<div class="comteeMemRow">
									<div class="usersDetHeader">
										<?php echo $msg;?>
									</div>
								</div>
							<?php
								}
							?>
							<form id='addAchievement' action='add_achievement.php' method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
								<div class="form_row">
									<div class="form_label">
										<label for='type' >Type:</label>
									</div>
									<div class="form_field">
										<select name="typeId" id="typeId" class="typeId">
											<option value="">SELECT</option>
											<option value="<?php echo DOCUMENT;?>"><?php echo 'DOCUMENT';?></option>
											<option value="<?php echo NON_DOCUMENT;?>"><?php echo 'NON_DOCUMENT';?></option>
										</select>
									</div>
								</div>
								<div class="form_row" id="doc">
									<div class="form_label">
										<label for='achieveTitle' >Achievement Title:</label>
									</div>
									<div class="form_field" id="subject">
										<input type="text" name="documentTitle" id="documentTitle" class="documentTitle" />
									</div>
								</div>
								<div class="form_row" id="docFile">
									<div class="form_label">
										<label for="achieveFile">Achievement File:</label>
									</div>
									<div class="form_field">
										<input type="file" name="achievementFile" id="achievementFile" />
									</div>
								</div>
								<div class="form_row" id="non_doc">
									<div class="form_label">
										<label for='achieveName' >Achievement :</label>
									</div>
									<div class="form_field" id="subject">
										<textarea name="documentName" id="documentName" class="documentName" ></textarea>
									</div>
								</div>
								<div class="form_row">
									<div class="form_label">
										
									</div>
									<div class="form_field">
										<input type='submit' name='addNewAchievement' id="addNewAchievement" class="button" value='Add Achievement' />
									</div>
								</div>
							</form>
						</div>
					</div>
					</div>
					<br class="clearfix" />
				</div>
				<?php 
					include_once('admin/sidebar.php');
				?>
				<br class="clearfix" />
			</div>
		</div>

<?php 
	include_once('admin/footer.php');
?>

<script type="text/javascript">
	$('.document').ready(function(){
		
		$('#doc').hide();
		$('#docFile').hide();
		$('#non_doc').hide();
		
		$('#typeId').change( function(){

			var typeId	= $('#typeId').val();

			if( typeId	== '<?php echo DOCUMENT; ?>'){
				$('#non_doc').hide();
				$('#doc').show();
				$('#docFile').show();
			}else if( typeId	== '<?php echo NON_DOCUMENT; ?>'){
				$('#doc').hide();
				$('#docFile').hide();
				$('#non_doc').show();
			}else{
				$('#doc').hide();
				$('#docFile').hide();
				$('#non_doc').hide();
			}
		});
	});
</script>
