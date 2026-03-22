<?php 
   require_once("../libraries/functions.class.php") ;

   $fcObj		= new DataFunctions();
   
  $tbComments	= TB_COMMENTS;
   
  if ( isset ( $_POST['addNewComment'] ) ){
   	
		$varArray['comType']	= $_POST['commentatorType'];
		$varArray['comName']	= $_POST['commentatorName'];
		$varArray['comQualif']	= str_replace(',','\,',$_POST['commentatorQualif']);
		$varArray['comDesig']	= $_POST['commentatorDesig'];
		$varArray['comComment']	= $_POST['commentatorComment'];
		
		$fileName	= $_POST['commentatorType'].'.png';
		
		if ((move_uploaded_file($_FILES['commentatorImage']['tmp_name'], "../images/".$fileName))){
								
			$fileName 	= $fileName;
		}else{
		
			$fileName 	= '';
		}
		
		$varArray['image']	= $fileName;

		$changeComments	= $fcObj->changeComments ( $tbComments, $varArray );
		
		if( $changeComments ){
			
			header('Location: index.php');
			return false;
		}else{
			$msg	= 'Sorry, Please try again';
		}
   }
 
   $itHodComment	= $fcObj->getComment( $tbComments, HOD );
   
   $princComment	= $fcObj->getComment( $tbComments, PRINCIPAL );
   
 	include_once('header.php');
	
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
	.legacy-admin-form .comteeMem,
	.legacy-admin-form form {
		border: 1px solid var(--la-border);
		border-radius: 18px;
		background: #ffffff;
		box-shadow: 0 10px 22px rgba(15, 23, 42, 0.06);
		padding: 24px;
	}
	.legacy-admin-form .form_label label { color: var(--la-primary); font-weight: 700; }
	.legacy-admin-form input[type="text"],
	.legacy-admin-form input[type="file"],
	.legacy-admin-form input[type="password"],
	.legacy-admin-form select,
	.legacy-admin-form textarea {
		width: 100%;
		min-height: 52px;
		border: 1px solid var(--la-border-strong);
		border-radius: 12px;
		padding: 11px 14px;
		background: #f7f9fc;
	}
	.legacy-admin-form textarea { min-height: 120px; }
	.legacy-admin-form .button {
		border: 0;
		border-radius: 12px;
		padding: 11px 20px;
		background: linear-gradient(135deg, var(--la-primary-deep), var(--la-primary));
		font-weight: 700;
		color: #fff;
		box-shadow: 0 10px 20px rgba(16, 42, 72, 0.24);
	}
</style>

			<div id="page">
				<div id="content">
					<div class="post">
						<h2>Welcome to AIML Department</h2>
						<p class="mainContent">
							
						</p>
					</div>
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
					<div class="legacy-admin-form">
					<div class="hero">
						<h3>Change Comments</h3>
						<p>Update chairman, principal, and HOD comment blocks using the same school-branded admin styling.</p>
					</div>
					<form id='addComment' action='changecomments.php' method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
						<div class="form_row">
							<div class="form_label">
								<label for='commentator' >Commentator:</label>
							</div>
							<div class="form_field">
								<select name="commentatorType" id="commentatorType" class="commentatorType">
									<option value="">SELECT</option>
									<option value="<?php echo CHAIRMAN;?>"><?php echo CHAIRMAN;?></option>
									<option value="<?php echo PRINCIPAL;?>"><?php echo PRINCIPAL;?></option>
									<option value="<?php echo HOD;?>"><?php echo HOD;?></option>
								</select>
							</div>
						</div>
						<div class="form_row">
							<div class="form_label">
								<label for='name' >Commentator Name:</label>
							</div>
							<div class="form_field">
								<input type="text" name="commentatorName" id="commentatorName" class="commentatorName" value="" />
							</div>
						</div>
						<div class="form_row">
							<div class="form_label">
								<label for='commentatorQualif' >Qualification:</label>
							</div>
							<div class="form_field"> 
								<input type="text" name="commentatorQualif" id="commentatorQualif" class="commentatorQualif" value="" />
							</div>
						</div>
						<div class="form_row">
							<div class="form_label">
								<label for='commentatorDesig' >Designation:</label>
							</div>
							<div class="form_field"> 
								<input type="text" name="commentatorDesig" id="commentatorDesig" class="commentatorDesig" value="" />
							</div>
						</div>
						<div class="form_row">
							<div class="form_label">
								<label for='eventVenue' >Comment:</label>
							</div>
							<div class="form_field">
								<textarea rows="5" cols="17" name="commentatorComment" id="commentatorComment" class="commentatorComment"></textarea>
							</div>
						</div>
						<div class="formRow">
							<div class="form_label">
								<label for="userImage">Image :</label>
							</div>
							<div class="form_field">
								<input type="file" name="commentatorImage" id="commentatorImage" />
							</div>
						</div>
									
						<div class="form_row">
							<div class="form_label">
								
							</div>
							<div class="form_field">
								<input type='submit' name='addNewComment' class="button" value='Add Comment' />
							</div>
						</div>						
					</form>
					</div>
				</div>
				<?php 
					include_once('sidebar.php');
				?>
				<br class="clearfix" />
			</div>
		</div>

<?php 
	include_once('footer.php');
?>
