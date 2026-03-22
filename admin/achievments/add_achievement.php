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
<style type="text/css">
	.legacy-admin-form {
		--la-primary: #173d69;
		--la-primary-deep: #13345a;
		--la-accent: #f0b323;
		--la-accent-deep: #d79a12;
		--la-surface: #eef4fa;
		--la-border: #d9e3ef;
		--la-border-strong: #c8d6e6;
		--la-muted: #6b819c;
		background: linear-gradient(180deg, #f3f7fb 0%, var(--la-surface) 100%);
		border-radius: 24px;
		padding: 24px;
	}
	.legacy-admin-form .hero {
		position: relative;
		overflow: hidden;
		border: 1px solid var(--la-border);
		border-radius: 22px;
		padding: 22px 24px;
		background: linear-gradient(135deg, #f9fbfe 0%, var(--la-surface) 100%);
		box-shadow: 0 14px 30px rgba(15, 23, 42, 0.08);
		margin-bottom: 16px;
	}
	.legacy-admin-form .hero::before {
		content: "";
		position: absolute;
		inset: 0 auto 0 0;
		width: 6px;
		background: linear-gradient(180deg, var(--la-accent), var(--la-accent-deep));
	}
	.legacy-admin-form .hero h3 {
		margin: 0;
		font-size: 32px;
		font-weight: 800;
		letter-spacing: -0.6px;
		color: var(--la-primary-deep);
	}
	.legacy-admin-form .hero p {
		margin: 8px 0 0;
		color: var(--la-muted);
	}
	.legacy-admin-form .comteeMem {
		border: 1px solid var(--la-border);
		border-radius: 18px;
		background: #ffffff;
		box-shadow: 0 10px 22px rgba(15, 23, 42, 0.06);
		padding: 24px;
	}
	.legacy-admin-form .form_label label {
		color: var(--la-primary);
		font-weight: 700;
	}
	.legacy-admin-form input[type="text"],
	.legacy-admin-form input[type="file"],
	.legacy-admin-form select,
	.legacy-admin-form textarea {
		width: 100%;
		min-height: 52px;
		border: 1px solid var(--la-border-strong);
		border-radius: 12px;
		padding: 11px 14px;
		background: #f7f9fc;
	}
	.legacy-admin-form textarea {
		min-height: 120px;
	}
	.legacy-admin-form .button {
		border: 0;
		border-radius: 12px;
		padding: 11px 20px;
		background: linear-gradient(135deg, var(--la-primary-deep), var(--la-primary));
		font-weight: 700;
		box-shadow: 0 10px 20px rgba(16, 42, 72, 0.24);
	}
</style>

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
								<p>Create a new achievement entry or upload an achievement document with the same school-branded styling.</p>
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