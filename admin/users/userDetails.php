<?php 
   require_once("../libraries/functions.class.php") ;

   $fcObj		= new DataFunctions();
   
   $tbUsers		= TB_USERS;
   
   if( isset( $_GET['userName'] ) ){
   
	   $userName	= $_GET['userName'];
	   
	   $usrDetails	= $fcObj->userCheck( $tbUsers, $userName );
	 
		if( !empty( $usrDetails ) && ( $usrDetails[0]['status'] == 1 ) ){
 ?>
		<form action="userDetails.php" method="POST" enctype="multipart/form-data">
			<div class="committeeTitle">
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
			
			<div class="usersDetHeader">
				<div  class="userNameApr">
					<?php
						echo $usrDetails[0]['username'];
					?>
				</div>
				<div  class="userNameApr">
					<?php
						echo $usrDetails[0]['admission_id'];
					?>
				</div>
				<div  class="userNameApr">
					<?php
						echo $usrDetails[0]['mail_id'];
					?>
				</div>
			</div>
			<br class="clearfix" />
			<div class="form_row" >
				<div class="form_label">
					<label for='passWord' >Change Pass Word :</label>
				</div>
				<div class="form_field">
					<input type="password" name="usrPassWord" id="usrPassWord" class="usrPassWord" />
				</div>
			</div>
			<div class="form_row" >
				<div class="form_label">
					<label for='cpassWord' >Confirm Pass Word :</label>
				</div>
				<div class="form_field">
					<input type="password" name="usrCnfPassWord" id="usrCnfPassWord" class="usrCnfPassWord" />
				</div>
			</div>
			<br class="clearfix" />
			<div class="form_row" >
				<div class="form_label">
					
				</div>
				<div class="form_field">
					<input type="hidden" name="userName" id="userName" value="<?php	echo $usrDetails[0]['username']; ?>"/>
					<input type="submit" name="changePassWord" id="changePassWord" class="button" value="Change Pass Word" />
				</div>
			</div>
		</form>
<?php
		}else if( !empty( $usrDetails ) && ( $usrDetails[0]['status'] == 0 ) ){
			
			echo 'User Not Approved Yet';
		}else if( empty( $usrDetails ) ){
		
			echo 'User Not Found';
		}
	}
	if( isset( $_POST['changePassWord'] ) ){
	
		include_once('adminheader.php');
		
		?>
		<div id="page">
				<div id="content">
					<div class="post">
						<span class="alignCenter">
							<h4>IT Department </h4>
						</span>
						<p>
							
						</p>
					</div>
					<div id='content_left' class='content_left'>
						<?php 
							include_once('user_leftnav.php');
						?>						
					</div>
					<div id='content_right' class='content_right'>
						<div class="comteeMem">
							
							<?php
							$userName		=  $_POST['userName'];
							$userPassWord	=  $_POST['usrPassWord'];
							$userCPassWord	=  $_POST['usrCnfPassWord'];
							
							if( ( ($userPassWord != NULL) || ($userPassWord != '') ) && ( $userPassWord == $userCPassWord ) ){
								
								$varArray['user_name']	= $userName;
								$varArray['pass_word']	= sha1($userPassWord);
								
								$changePass	= $fcObj->changeUserPassWord( $tbUsers, $varArray );
								
								if( $changePass ){
									echo 'Pass Word Has Been Changed SuccessFully For user "'.$userName.'"';
								}else{
									echo 'Pass Word Not Changed SuccessFully Please Try Again';
								}
							}else if( ( ($userPassWord != NULL) || ($userPassWord != '') ) && ( $userPassWord != $userCPassWord ) ){
							
								echo 'Pass Word And Confirm Pass Word Are Not Same Please Try Again';
								
							}else{
								
								echo 'Please Enter Pass Word And Confirm Pass Word';
					
							}
							?>
						
						</div>
					</div>
					<br class="clearfix" />
				</div>
				<?php 
					include_once('sidebar.php');
				?>
				<br class="clearfix" />
			</div>
		</div>
		<?php
		include_once('footer.php');

	}
?>
