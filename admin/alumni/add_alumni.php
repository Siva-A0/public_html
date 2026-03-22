<?php 
	
   require_once("../../libraries/functions.class.php");

   $fcObj	= new DataFunctions();

   $tbAlumni	 = TB_ALUMNI;

   $tbBatch		 = TB_BATCH;
   
   $batches		 = $fcObj->getBatches( $tbBatch );
  
   $batchCnt	 = sizeof($batches);

   if ( isset ( $_POST['addNewAlumni'] ) ){
   				
		$varArray['typeId']		 = $_POST['typeId'];

		$varArray['alumni_desc'] = $_POST['alumniName'];
		
		$fileName	= $_FILES['alumniFile']['name'];
	
		if ((move_uploaded_file($_FILES['alumniFile']['tmp_name'], "../../images/alumni/".$fileName))){
								
			$fileName 	= $fileName;
		}else{
		
			$fileName 	= '';
		}
		$varArray['image']	= $fileName;
		
		$addAlumni	= $fcObj->addAlumniDetails ( $tbAlumni, $varArray );
		
		if( $addAlumni ){
			
			header('Location: alumni.php');
			return false;
		}else{
			$msg	= 'Sorry, Please try again';
		}
   }

	include_once('../header.php');

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
							include_once('../leftnav.php');
						?>						
					</div>
					<div id='content_right' class='content_right'>
						<div class="legacy-admin-form">
							<div class="hero">
								<h3>Add Alumni</h3>
								<p>Upload alumni images and descriptions with the same school-branded styling used across admin.</p>
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
							<form id='addAchievement' action='add_alumni.php' method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
								<div class="form_row">
									<div class="form_label">
										<label for='type' >Batch :</label>
									</div>
									<div class="form_field">
										<select name="typeId" id="typeId" class="typeId">
											<option value="">SELECT</option>
											<?php
												for($i=0;$i<$batchCnt;$i++){
													?>
														<option value="<?php echo $batches[$i]['id']; ?>"><?php echo $batches[$i]['batch']; ?></option>
													<?php
												}
											?>
										</select>
									</div>
								</div>
								<div class="form_row">
									<div class="form_label">
										<label for="alumniFile">Alumni Image :</label>
									</div>
									<div class="form_field">
										<input type="file" name="alumniFile" id="alumniFile" />
									</div>
								</div>
								<div class="form_row">
									<div class="form_label">
										<label for='alumniName' >Alumni Description :</label>
									</div>
									<div class="form_field" >
										<textarea name="alumniName" id="alumniName" class="alumniName" ></textarea>
									</div>
								</div>
								<div class="form_row">
									<div class="form_label">
										
									</div>
									<div class="form_field">
										<input type='submit' name='addNewAlumni' id="addNewAlumni" class="button" value='Add Alumni' />
									</div>
								</div>
							</form>
						</div>
					</div>
					</div>
					<br class="clearfix" />
				</div>
				<?php 
					include_once('../sidebar.php');
				?>
				<br class="clearfix" />
			</div>
		</div>

<?php 
	include_once('../footer.php');
?>
