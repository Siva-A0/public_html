<?php 
	
	include_once('main_header.php');

   require_once("Department/libraries/functions.class.php") ;

   $fcObj	= new DataFunctions();
   
   $tbAchevments = TB_ACHIEVEMENTS;
   
   $cat_id		 = NON_DOCUMENT;
   
   $acheivemnts	 = $fcObj->getAchievements( $tbAchevments , $cat_id);
  
   $acheivemntsCnt	 = sizeof($acheivemnts);
  
   $cat_id		 = DOCUMENT;
   
   $acheiveDocs		 = $fcObj->getAchievements( $tbAchevments , $cat_id);
  
   $acheiveDocsCnt	 = sizeof($acheiveDocs);
   
?>
<style type="text/css">
	.legacy-admin-page {
		--la-primary: #173d69;
		--la-primary-deep: #13345a;
		--la-accent: #f0b323;
		--la-accent-deep: #d79a12;
		--la-surface: #eef4fa;
		--la-border: #d9e3ef;
		--la-muted: #6b819c;
		background: linear-gradient(180deg, #f3f7fb 0%, var(--la-surface) 100%);
		border-radius: 24px;
		padding: 24px;
	}
	.legacy-admin-hero {
		position: relative;
		overflow: hidden;
		border: 1px solid var(--la-border);
		border-radius: 22px;
		padding: 22px 24px;
		background: linear-gradient(135deg, #f9fbfe 0%, var(--la-surface) 100%);
		box-shadow: 0 14px 30px rgba(15, 23, 42, 0.08);
		margin-bottom: 16px;
	}
	.legacy-admin-hero::before {
		content: "";
		position: absolute;
		inset: 0 auto 0 0;
		width: 6px;
		background: linear-gradient(180deg, var(--la-accent), var(--la-accent-deep));
	}
	.legacy-admin-title {
		margin: 0;
		font-size: 32px;
		font-weight: 800;
		letter-spacing: -0.6px;
		color: var(--la-primary-deep);
	}
	.legacy-admin-subtitle {
		margin: 8px 0 0;
		font-size: 15px;
		color: var(--la-muted);
	}
	.legacy-admin-page .comteeMem {
		border: 1px solid var(--la-border);
		border-radius: 18px;
		background: #ffffff;
		box-shadow: 0 10px 22px rgba(15, 23, 42, 0.06);
		padding: 18px;
		margin-bottom: 16px;
	}
	.legacy-admin-page .committeeTitle,
	.legacy-admin-page .usersDetHeader {
		border: 1px solid #dbe6f3;
		border-radius: 14px;
		background: #fbfdff;
		padding: 12px 14px;
		margin-bottom: 10px;
	}
	.legacy-admin-page .committeeTitle {
		background: #f7faff;
		color: var(--la-primary);
		font-weight: 800;
		text-transform: uppercase;
	}
	.legacy-admin-page .button {
		border: 0;
		border-radius: 11px;
		padding: 9px 15px;
		background: linear-gradient(135deg, var(--la-primary-deep), var(--la-primary));
		color: #ffffff;
		font-weight: 700;
		box-shadow: 0 10px 20px rgba(16, 42, 72, 0.18);
	}
	.legacy-admin-page #delete {
		background: linear-gradient(135deg, #b91c1c, #dc2626);
	}
	.legacy-admin-page .alumniImage img {
		width: 100%;
		max-width: 520px;
		height: auto;
		border-radius: 16px;
		border: 1px solid #dbe6f3;
		box-shadow: 0 10px 20px rgba(16, 42, 72, 0.12);
	}
	.legacy-admin-page .alumniDesc {
		margin: 12px 0;
		padding: 14px 16px;
		border: 1px solid #e1e9f2;
		border-radius: 14px;
		background: #fbfdff;
		color: #1f324b;
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
						<div class="legacy-admin-page">
							<div class="legacy-admin-hero">
								<h3 class="legacy-admin-title">Achievements</h3>
								<p class="legacy-admin-subtitle">Manage department achievements and supporting documents in the school-branded admin workspace.</p>
							</div>
						<div class="comteeMem">
							<div class="committeeTitle">
								<div class='sno'>
									S. No
								</div>
								<div  class="achievemnts">
									Achievements
								</div>
							</div>
							<?php
								
								for($i=0; $i< $acheivemntsCnt; $i++){
								
							?>
									<div class="usersDetHeader">
										<div class='sno'>
										<?php 
											echo $i+1;
										?>
										</div>
										<div  class="achievementName">
											<?php
												echo $acheivemnts[$i]['achievement_desc'];
											?>
										</div>
										<div  class="eventCandName">
											<a href="delete_achievement.php?achievement=<?php echo $acheivemnts[$i]['id'];?>" >
												<input type="button" class="button" id="delete" value="Delete"/>
											</a>
										</div>
									</div>
									
									<br class="clearfix" />
							<?php 
								} 
							?>
							</div>
							<div class="comteeMem">
							<?php
								for($i=0; $i< $acheiveDocsCnt; $i++){
									
									$achieveDoc		= $acheiveDocs[$i]['achievement_desc'];

									$achieveDocs	= explode('$$',$achieveDoc);

							?>
									<div class="committeeTitle">
										<div class='eventCandName'>
											View Full Details
										</div>
										<div  class="eventCandClass">
											<a href="<?php echo '../../public/assets/images/'.$achieveDocs[1]; ?>" target="_blank">
												<?php 
													echo $achieveDocs[0];
												?>
											</a>
										</div>
										<div  class="eventCandName">
											<a href="delete_achievement.php?achievement=<?php echo $acheiveDocs[$i]['id'];?>" >
												<input type="button" class="button" id="delete" value="Delete"/>
											</a>
										</div>
									</div>
							<?php
								}
							?>
							
						</div>
						<div  class="eventCandName">
							<a href="add_achievements.php" >
								<input type="button" class="button" value="Add Achievement" />
							</a>
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
		$('#delete').click(function(){
			var conf	= confirm('Do You Want To Continue To Delete');
			if( conf ){
				
			}else{
				return false;
			}
		});
	});
</script>