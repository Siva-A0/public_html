<?php
     if( session_id() == '' ){
	 	session_start();
	 
	 }

	 if (!defined('BASE_URL')) {
		require_once(__DIR__ . '/../config.php');
	 }
	 
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<link rel="shortcut icon" href="<?php echo BASE_URL; ?>/public/assets/images/wise_fav.ico"/>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<title>AIML Department</title>
		<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
		<link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/assets/css/site-refresh.css" />
		<link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/assets/css/admin-refresh.css" />

		<!-- image slide show -->
		<script type="text/javascript" src="<?php echo BASE_URL; ?>/public/assets/js/jquery.min.js"></script>
		<script type="text/javascript" src="<?php echo BASE_URL; ?>/public/assets/js/coin-slider.min.js"></script>
		<link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/assets/css/coin-slider-styles.css" type="text/css" />

		<!-- pretty photo -->
		<link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/assets/css/prettyPhoto.css" type="text/css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
		<script src="<?php echo BASE_URL; ?>/public/assets/js/jquery.prettyPhoto.js" type="text/javascript" charset="utf-8"></script>


		<script type="text/javascript">
			$(document).ready(function() {
				
				$("area[rel^='image']").prettyPhoto();
				
					$(".gallery:first a[rel^='image']").prettyPhoto({animation_speed:'normal',theme:'facebook',slideshow:5000, autoplay_slideshow: true});
					$(".gallery:gt(0) a[rel^='image']").prettyPhoto({animation_speed:'normal',theme:'facebook',slideshow:5000, autoplay_slideshow: true});

				
			});
		</script>

		<script type="text/javascript">
			$(document).ready(function() {
				$('#coin-slider').coinslider({ height: 300, opacity: 0.7 ,effect: 'straight'});
			});
		</script>

	</head>
	<body>
		<div id="wrapper">
			<div id="header">
				<div id="logo">
					<h1><a href="index.php"><img src="<?php echo BASE_URL; ?>/public/assets/images/wise.png" alt="WISE" title="WISE"  /></a></h1>
				</div>
				<!--
				<div id="search">
					<form action="search.php" method="post">
						<div>
							<input class="form-text" name="search" size="32" maxlength="64" /><input class="form-submit" type="submit" value="Search" />
						</div>
					</form>
				</div>
				-->
				<div id="menu">
					<ul>
						<li class="first current_page_item"><a href="main_home.php">Home</a></li>
						<li><a href="assoc.php">Pragya AI</a></li>
						<li><a href="department.php">AIML Department</a></li>
						<li><a href="users.php">Users</a></li>
						<li><a href="gallery.php">Gallery</a></li>								
						<li class="last"><a href="otheroperations.php">Core</a></li>
					</ul>
					<ul id="logDetails">
						<?php if(!isset($_SESSION['adminId'])){ ?>
									<li>	<a href="index.php">Login</a>
							<?php }else{ ?> 
									<li style="padding:0">	
										<div class="userImage">
											<img src="<?php echo BASE_URL; ?>/public/assets/images/admin/<?php echo $_SESSION['adminImage']?>" width="50px" height="50px" />
										</div>
										<div class="userName">
											<span class="sessionName">
												<?php echo $_SESSION['adminFirstName']?>
											</span>
											<span class="changePass">
												<a href="changepassword.php">Change Password </a>
											</span>
										</div>
							<?php } ?> 									
						</li>
						<li class="last"><?php if(!isset($_SESSION['adminId'])){ ?>
										
							<?php } else{ ?> 
										<a href="logout.php">Logout</a>
							<?php } ?>  	
						</li>
					</ul>
					<br class="clearfix" />
				</div>
			</div>
