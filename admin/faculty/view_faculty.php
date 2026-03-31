<?php 
	include_once('header.php');
	
   require_once("../libraries/functions.class.php") ;

   $fcObj			= new DataFunctions();
	
	$staffId		= isset($_GET['faculty']) ? (int)$_GET['faculty'] : 0;
	
   $tbStaff		 	= TB_STAFF;
	
	$staffDetails	= $fcObj->getStaffDetailsById( $tbStaff , $staffId );	
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
							include_once('departleftnav.php');
						?>						
					</div>
					<div id='content_right' class='content_right'>
						<div class="faculty-view-page">
						<div class="faculty-profile-hero">
							<h3 class="faculty-profile-title">Faculty Profile</h3>
							<!-- <p class="faculty-profile-subtitle">View department faculty details in the same school-branded department workspace.</p> -->
						</div>
						<div class="eventDetails" >
							<div class="faculty-identity">
								<img src="../../public/assets/images/faculty/<?php echo $staffDetails[0]['image'];?>" alt="<?php echo $staffDetails[0]['first_name'].' '.$staffDetails[0]['last_name'];?>" title="<?php echo $staffDetails[0]['first_name'].' '.$staffDetails[0]['last_name'];?>" />
								<div class="faculty-identity-text">
									<h2><?php echo  $staffDetails[0]['first_name'].' '.$staffDetails[0]['last_name']; ?></h2>
									<p><?php echo  $staffDetails[0]['designation']; ?></p>
								</div>
							</div>
							<br class="clearfix" />
							<div class="eventHead">
								Faculty Name :
							</div>
							<div class="eventDes">
								<?php
									echo  $staffDetails[0]['first_name'].' '.$staffDetails[0]['last_name'];
								?>
							</div>
							<br class="clearfix" />
							<div class="eventHead">
								Faculty Qualification :
							</div>
							<div class="eventDes">
								<?php
									echo  str_replace('\,',',',$staffDetails[0]['qualification']);
								?>
							</div>
							<br class="clearfix" />
							<div class="eventHead">
								Faculty Designation :
							</div>
							<div class="eventDes">
								<?php
									echo  $staffDetails[0]['designation'];
								?>
							</div>
							<br class="clearfix" />
							<div class="eventHead">
								Faculty E-Mail :
							</div>
							<div class="eventDes">
								<?php
									echo  $staffDetails[0]['e_mail'];
								?>
							</div>
							<br class="clearfix" />
							<?php 
							if( $staffDetails[0]['staff_categ_id'] == TEACHING ){
							?>
								<div class="eventHead">
									Industry Experience :
								</div>
								<div class="eventDes">
									<?php
										echo  $staffDetails[0]['industry_exp'];
									?>
								</div>
								<br class="clearfix" />
								<div class="eventHead">
									Teaching Experience :
								</div>
								<div class="eventDes">
									<?php
										echo  $staffDetails[0]['teach_exp'];
									?>
								</div>
								<br class="clearfix" />
								<div class="eventHead">
									Research :
								</div>
								<div class="eventDes">
									<?php
										echo  $staffDetails[0]['research'];
									?>
								</div>
								<br class="clearfix" />
								<div class="eventHead bold">
									Publications
								</div>
								<br class="clearfix" />
								<div class="eventHead">
									National :
								</div>
								<div class="eventDes">
									<?php
										echo  $staffDetails[0]['publ_national'];
									?>
								</div>
								<br class="clearfix" />
								<div class="eventHead">
									Inter National :
								</div>
								<div class="eventDes">
									<?php
										echo  $staffDetails[0]['publ_international'];
									?>
								</div>
								<br class="clearfix" />
								<div class="eventHead bold">
									Conferences
								</div>
								<br class="clearfix" />
								<div class="eventHead">
									National :
								</div>
								<div class="eventDes">
									<?php
										echo  $staffDetails[0]['conf_national'];
									?>
								</div>
								<br class="clearfix" />
								<div class="eventHead">
									Inter National :
								</div>
								<div class="eventDes">
									<?php
										echo  $staffDetails[0]['conf_international'];
									?>
								</div>
							<?php
							}
							?>
							<br class="clearfix" />
							<div class="eventHead">
								
							</div>
							
						</div>
						<a class="faculty-back-link" href="../Department/department.php">Back to Department</a>
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
		
<script type="text/javascript" language="javascript">
	
	$(document).ready(function() {
		
	});
</script>

<?php 
	include_once('footer.php');
?>

