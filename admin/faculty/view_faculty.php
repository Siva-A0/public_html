<?php 
	include_once('header.php');
	
   require_once("../libraries/functions.class.php") ;

   $fcObj			= new DataFunctions();
	
	$staffId		= isset($_GET['faculty']) ? (int)$_GET['faculty'] : 0;
	
   $tbStaff		 	= TB_STAFF;
	
	$staffDetails	= $fcObj->getStaffDetailsById( $tbStaff , $staffId );	
?>
<style type="text/css">
	.faculty-view-page {
		--fv-primary: #173d69;
		--fv-primary-deep: #13345a;
		--fv-accent: #f0b323;
		--fv-accent-deep: #d79a12;
		--fv-surface: #eef4fa;
		--fv-border: #d9e3ef;
		--fv-muted: #6b819c;
		background: linear-gradient(180deg, #f3f7fb 0%, var(--fv-surface) 100%);
		border-radius: 24px;
		padding: 24px;
	}

	.faculty-view-page .eventDetails {
		border: 1px solid var(--fv-border);
		border-radius: 18px;
		background: #ffffff;
		box-shadow: 0 10px 22px rgba(15, 23, 42, 0.06);
		padding: 22px;
	}

	.faculty-profile-hero {
		position: relative;
		overflow: hidden;
		border: 1px solid var(--fv-border);
		border-radius: 22px;
		padding: 22px 24px;
		background: linear-gradient(135deg, #f9fbfe 0%, var(--fv-surface) 100%);
		box-shadow: 0 14px 30px rgba(15, 23, 42, 0.08);
		margin-bottom: 16px;
	}

	.faculty-profile-hero::before {
		content: "";
		position: absolute;
		inset: 0 auto 0 0;
		width: 6px;
		background: linear-gradient(180deg, var(--fv-accent), var(--fv-accent-deep));
	}

	.faculty-profile-title {
		margin: 0;
		font-size: 32px;
		font-weight: 800;
		letter-spacing: -0.6px;
		color: var(--fv-primary-deep);
	}

	.faculty-profile-subtitle {
		margin: 8px 0 0;
		font-size: 15px;
		color: var(--fv-muted);
	}

	.faculty-identity {
		display: flex;
		align-items: center;
		gap: 18px;
		margin-bottom: 22px;
		padding-bottom: 18px;
		border-bottom: 1px solid #e4edf7;
	}

	.faculty-identity img {
		width: 120px;
		height: 120px;
		object-fit: cover;
		border-radius: 20px;
		border: 3px solid #ffffff;
		box-shadow: 0 12px 22px rgba(19, 52, 90, 0.18);
		background: #edf3fa;
	}

	.faculty-identity-text h2 {
		margin: 0;
		font-size: 28px;
		font-weight: 800;
		color: var(--fv-primary-deep);
	}

	.faculty-identity-text p {
		margin: 8px 0 0;
		color: var(--fv-muted);
		font-size: 15px;
	}

	.faculty-view-page .eventHead,
	.faculty-view-page .eventDes {
		float: none;
		width: auto;
	}

	.faculty-view-page .eventHead {
		font-size: 14px;
		font-weight: 800;
		letter-spacing: 0.4px;
		text-transform: uppercase;
		color: var(--fv-primary);
		margin-bottom: 6px;
	}

	.faculty-view-page .eventDes {
		font-size: 16px;
		line-height: 1.7;
		color: #1f324b;
		margin-bottom: 18px;
		padding: 14px 16px;
		border: 1px solid #dfe8f2;
		border-radius: 14px;
		background: #f9fbfe;
		overflow-wrap: anywhere;
	}

	.faculty-view-page .eventHead.bold {
		display: inline-flex;
		align-items: center;
		gap: 8px;
		font-size: 15px;
		color: var(--fv-primary-deep);
		margin: 12px 0 10px;
	}

	.faculty-view-page .eventHead.bold::before {
		content: "";
		width: 10px;
		height: 10px;
		border-radius: 999px;
		background: linear-gradient(135deg, var(--fv-accent), var(--fv-accent-deep));
	}

	.faculty-back-link {
		display: inline-flex;
		align-items: center;
		margin-top: 18px;
		padding: 10px 16px;
		border-radius: 12px;
		border: 1px solid #c8d8ea;
		background: #ffffff;
		color: var(--fv-primary);
		font-weight: 700;
		text-decoration: none;
	}

	.faculty-back-link:hover {
		color: var(--fv-primary-deep);
	}

	@media (max-width: 768px) {
		.faculty-profile-title {
			font-size: 26px;
		}

		.faculty-identity {
			flex-direction: column;
			align-items: flex-start;
		}

		.faculty-identity img {
			width: 100px;
			height: 100px;
		}

		.faculty-identity-text h2 {
			font-size: 24px;
		}
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
							include_once('departleftnav.php');
						?>						
					</div>
					<div id='content_right' class='content_right'>
						<div class="faculty-view-page">
						<div class="faculty-profile-hero">
							<h3 class="faculty-profile-title">Faculty Profile</h3>
							<p class="faculty-profile-subtitle">View department faculty details in the same school-branded department workspace.</p>
						</div>
						<div class="eventDetails" >
							<div class="faculty-identity">
								<img src="../images/staff/<?php echo $staffDetails[0]['image'];?>" alt="<?php echo $staffDetails[0]['first_name'].' '.$staffDetails[0]['last_name'];?>" title="<?php echo $staffDetails[0]['first_name'].' '.$staffDetails[0]['last_name'];?>" />
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
