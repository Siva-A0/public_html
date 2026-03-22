<?php 
	require_once(__DIR__ . '/../../../config.php');

    include_once(INCLUDES_PATH . '/header.php');
    require_once(LIB_PATH . '/functions.class.php');
   $fcObj		= new DataFunctions();
   
   $tbGallery	= TB_GALLERY;
   $tbEvents	= TB_EVENTS;
   

	$events[0]['id']			= -1;
	$events[0]['event_name']	= 'Press News';


   $noOfEvents	= sizeof($events);

   for($i=0;$i<$noOfEvents;$i++){

		$eventId			= $events[$i]['id'];
		$galleryImages[$i]	= $fcObj->getImagesForEvents( $tbGallery, $eventId );
   }
 ?>
  <div class="box1">
        <div class="wrapper">
          <article class="col1">
				<div id="index_cont">
				<div id="content_garrely">
					<?php
						for($i=0;$i<$noOfEvents;$i++){
   		
							$eventId			= $events[$i]['id'];
							?>
								<div class="eventDetHeaderGallery">
									<div class="eventName">
										<?php echo  $events[$i]['event_name']; ?>
									</div>
								</div>
								
								<div class="eventDetGallery">
										
									<ul class="gallery clearfix">
							<?php
								$noOfImages		= sizeof( $galleryImages[$i] );
								

								for($j=0;$j<$noOfImages;$j++){
								?>
									<div class="galleryImage">
											<li>
												<a href="<?php echo BASE_URL; ?>/gallery/<?php echo rawurlencode($galleryImages[$i][$j]['image_name']); ?>" rel="image[<?php echo  $events[$i]['event_name']; ?>]" >
													<img src="<?php echo BASE_URL; ?>/gallery/<?php echo rawurlencode($galleryImages[$i][$j]['image_name']); ?>" alt="<?php echo  $events[$i]['event_name']; ?>" width="150" height="200" />
												</a>
											</li>
									</div>
										
								<?php
								}
								?>
									</ul>
								</div>
								<?php							
					   }
					?>
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
<?php include_once(INCLUDES_PATH . '/footer.php'); ?>

