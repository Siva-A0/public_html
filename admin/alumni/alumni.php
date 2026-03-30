<?php 
	
	include_once('header.php');

   require_once("../libraries/functions.class.php") ;

   $fcObj	= new DataFunctions();
   
   $tbAlumni	 = TB_ALUMNI;
   
   $tbBatch		 = TB_BATCH;
   
   $batches		 = $fcObj->getBatches( $tbBatch );
  
   $batchCnt	 = sizeof($batches);
   
   
   for($i=0;$i<$batchCnt;$i++){
   		
		$batchId		= $batches[$i]['id'];
		$alumniDet[$i]	= $fcObj->getAlumniDetails( $tbAlumni , $batchId);
  }

   
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
						<div class="legacy-admin-page">
							<div class="legacy-admin-hero">
								<h3 class="legacy-admin-title">Alumni Gallery</h3>
								<p class="legacy-admin-subtitle">Manage alumni images and descriptions by batch in the same branded admin workspace.</p>
							</div>
						<div class="comteeMem">
							<?php
								
							for($i=0; $i< $batchCnt; $i++){
								
							?>
								<div class="committeeTitle">
									<div  class="achievemnts">
										<?php echo $batches[$i]['batch'];?>
									</div>
								</div>
								<ul class="gallery clearfix">
								<?php
								$alumniDetCnt	= sizeof($alumniDet[$i]);	
								
								for($j=0; $j< $alumniDetCnt; $j++){
								?>
									<div class="alumniImage">
										<div class='achievemnts'>
												<li>
													<a href="<?php echo '../images/alumni/'.$alumniDet[$i][$j]['alumni_img']; ?>"  rel="image[<?php echo  $batches[$i]['batch']; ?>]">
														<img src="<?php echo '../images/alumni/'.$alumniDet[$i][$j]['alumni_img']; ?>" alt="<?php echo $batches[$i]['batch'];?>" width="520" height="130"/>
													</a>
												</li>
										</div>
									</div>
									<div class="alumniDesc">
										<div  class="achievemnts">
											<?php
												echo $alumniDet[$i][$j]['alumni_desc'];
											?>
										</div>
									</div>
									<div  class="alumniDelete">
										<a href="delete_alumni.php?alumni=<?php echo $alumniDet[$i][$j]['id'];?>" >
											<input type="button" class="button" id="delete" value="Delete"/>
										</a>
									</div>
									<br class="clearfix" />
							<?php 
								}	
								?>
								</ul>	
								<?php
							} 
							?>
							</div>
							<div  class="alumniAdd">
								<a href="add_alumni.php" >
									<input type="button" class="button" value="Add Alumni" />
								</a>
							</div>
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
?>


