<?php 
	
	if (!isset($adminExtraStyles) || !is_array($adminExtraStyles)) {
    $adminExtraStyles = array();
}
$adminExtraStyles[] = BASE_URL . '/public/assets/css/admin/admin_misc_pages.css';

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
