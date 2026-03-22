<?php 
	require_once(__DIR__ . '/../../../config.php');

	include_once('header.php');

   require_once(LIB_PATH . '/functions.class.php');

   $fcObj	= new DataFunctions();
   
   $tbAchevments = TB_ACHIEVEMENTS;
   
   $cat_id		 = NON_DOCUMENT;
   
   $acheivemnts	 = $fcObj->getAchievements( $tbAchevments , $cat_id);
  
   $acheivemntsCnt	 = sizeof($acheivemnts);
  
   $cat_id		 = DOCUMENT;
   
   $acheiveDocs		 = $fcObj->getAchievements( $tbAchevments , $cat_id);
  
   $acheiveDocsCnt	 = sizeof($acheiveDocs);
   
?>
 <div class="box1">
        <div class="wrapper">
          <article class="col1">
				<div id="index_cont">
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
										<div  class="achievemnts">
											<?php
												echo $acheivemnts[$i]['achievement_desc'];
											?>
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
										<div class='eventCandName'>
											View Full Details
										</div>
										<div  class="eventCandClass">
											<a href="<?php echo BASE_URL; ?>/public/assets/images/achievements/<?php echo rawurlencode($achieveDocs[1]); ?>" target="_blank">
												<?php 
													echo $achieveDocs[0];
												?>
											</a>
										</div>
									</div>
							<?php
								}
							?>
							
						</div>
					</div>
					<br class="clearfix" />
				</div>
					</article>
					<article class="col2 pad_left2">
					<?php 
						include_once('sidebar.php');
					?>
					</article>
</div>
</div>

<?php 
	include_once('footer.php');
?>
