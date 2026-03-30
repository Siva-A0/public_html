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

