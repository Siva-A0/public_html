<?php 
	require_once(__DIR__ . '/../../../config.php');

	include_once('header.php');

   require_once(LIB_PATH . '/functions.class.php');

   $fcObj	= new DataFunctions();
   
   $tbAlumni	 = TB_ALUMNI;
   
   $tbBatch		 = TB_BATCH;
   
   $batches		 = $fcObj->getBatches( $tbBatch );
  
   $batchCnt	 = sizeof($batches);
   
   
   for($i=0;$i<$batchCnt;$i++){
   		
		$batchId		= $batches[$i]['id'];
		$alumniDet[$i]	= $fcObj->getAlumniDetails( $tbAlumni , $batchId);
  }
  $tbStaffCateg = TB_STAFF_CATEGORY;
   $tbStaff		 = TB_STAFF;
   
   $staffCateg		= $fcObj->getStaffCategories($tbStaffCateg);
   $categoryCnt		= sizeof($staffCateg);
   
   for($i=0; $i<$categoryCnt;$i++){
  		
		$categoryId	= $staffCateg[$i]['id'];
		
		$staffDetails[$i]	= $fcObj->getStaffDetails($tbStaff,$categoryId);
	}

   
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
									
								<input type="submit" name="currentEventReg" class='button' value="Register Old Students" />
							
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
													<a href="<?php echo BASE_URL; ?>/public/assets/images/alumni/<?php echo rawurlencode($alumniDet[$i][$j]['alumni_img']); ?>" rel="image[<?php echo  $batches[$i]['batch']; ?>]">
														<img src="<?php echo BASE_URL; ?>/public/assets/images/alumni/<?php echo rawurlencode($alumniDet[$i][$j]['alumni_img']); ?>" alt="<?php echo $batches[$i]['batch'];?>" width="505" height="130"/>
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
									
									<br class="clearfix" />
							<?php 
								}	
								?>
								</ul>	
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
