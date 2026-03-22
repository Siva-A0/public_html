<?php
	
    if( session_id() == '' ){
	 	session_start();
	 
	 }
  
   require_once("../libraries/functions.class.php") ;
   require_once("../libraries/security.php");

   $fcObj	= new DataFunctions();

   if( isset( $_SESSION['adminId'] ) ){

		header('Location: main_home.php');
		return false;
	 }
	 
 	 if( isset( $_POST['username'] ) ) {
		if (!app_validate_csrf_token($_POST['csrf_token'] ?? '')) {
			$_SESSION['err_msg'] = 'Your session expired. Please try again.';
			header('Location: index.php');
			return false;
		}

		$uName	= $_POST['username'];
		$pass	= $_POST['password'];
		
		$tbAdmin	 = ADMIN_TABLE;
	   
		$userDet = $fcObj->adminLogin($tbAdmin,$uName);
		
		if( empty($userDet) || !$fcObj->verifyPassword($pass, $userDet[0]['password']) ){
			
			$_SESSION['err_msg']	= 'Either Username Or Password Is Incorrect';
			header('Location: index.php');
			return false;
			
		}else{
			session_regenerate_id(true);
			
			$_SESSION['adminId']		= $userDet[0]['id'];
			$_SESSION['adminName']		= $uName;
			$_SESSION['adminFirstName']	= $userDet[0]['firstname'];
			$_SESSION['adminImage']		= $userDet[0]['image'];

			if ($fcObj->passwordNeedsRehash($userDet[0]['password'])) {
				$fcObj->changeAdminPassWord($tbAdmin, array(
					'admin_name' => $uName,
					'pass_word' => $fcObj->hashPassword($pass)
				));
			}

			header('Location: main_home.php');
			return false;
		}
   }else{
		
	include_once('adminheader.php');
		?>
			
			<div id="main_page">
				<div id="content">
					<div class="post">
						<span class="alignCenter">
							
						</span>
						<p>
							
						</p>
					</div>
					<div id='content_left' class='content_left'>
											
					</div>
					<div id='content_right' class='content_right'>
						<div class="login">
							<form id='login' action='index.php' method='POST' accept-charset='UTF-8'>
								<input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(app_get_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>" />
								<fieldset >
									<legend>Login</legend>
										<div class="form_row">
											<div class="form_label">
												<label for='username' >UserName*:</label>
											</div>
											<div class="form_field">
												<input type='text' name='username' id='username'  maxlength="50" />
											</div>
										</div>
										<div class="form_row">
											<div class="form_label">
												<label for='password' >Password*:</label>
											</div>
											<div class="form_field">
												<input type='password' name='password' id='password' maxlength="50" />
											</div>
										</div>
										<?php 
											if ( isset ( $_SESSION['err_msg'] ) ){
										?>
												<div class="form_row" id="error">
													<div class="form_label">
														<label for='password' >Error*:</label>
													</div>
													<div class="form_field" id="err">
														<?php echo $_SESSION['err_msg']; ?>
													</div>
												</div>
										<?php
												unset( $_SESSION['err_msg'] );
											}
										?>
										<div class="form_row">
											<div class="form_label">
												
											</div>
											<div class="form_field">
												<input type='submit' name='submit' class="button" value='Login' />
											</div>
										</div>
								</fieldset>
							</form>
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
