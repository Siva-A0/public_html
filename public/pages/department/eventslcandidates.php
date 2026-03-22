<?php 
	require_once(__DIR__ . '/../../../config.php');

    include_once(INCLUDES_PATH . '/header.php');
    require_once(LIB_PATH . '/functions.class.php');

   $fcObj			= new DataFunctions();
	
	$eventId		= isset($_GET['event']) ? (int)$_GET['event'] : 0;
	
	$tbEvents		= TB_EVENTS;
	$tbEventReg		= TB_EVENT_REG;
	
	$eventSLCandDet	= $fcObj->getEventSLCand( $tbEventReg , $eventId );
	
	$eventDetails	= $fcObj->getEventDetails( $tbEvents , $eventId );	
	
	$noOfSLCand		= sizeof( $eventSLCandDet );
?>
 <div class="box1">
        <div class="wrapper">
          <article class="col1">
				<div id="index_cont">
				<div id="content">
					<div class="post">
						<span class="alignCenter">
							<h4>WISE Association </h4>
						</span>
						<p>
							
						</p>
					</div>
					<div id='content_left' class='content_left'>
						<?php 
							include_once('wise_leftnav.php');
						?>						
					</div>
					<div id='content_right' class='content_right'>
						<div class="eventDetails" >
							<div class="eventTitle">
								<div class="eventHead">
									Event Title :
								</div>
								<div class="eventDes">
									<?php
										echo  $eventDetails[0]['event_name'];
									?>
								</div>
							</div>
							<br class="clearfix" />
					<!--	<div class="eventHead">
								Event Description :
							</div>
							<div class="eventDes">
								<?php
									echo  $eventDetails[0]['event_desc'];
								?>
							</div>
							<br class="clearfix" />
					-->
							<div class="eventTitle">
								<div class="eventCandName">
									Candidate Name
								</div>
								<div class="eventCandName">
									Roll No
								</div>
								<div class="eventCandClass">
									Candidate Details
								</div>
								<br class="clearfix" />
							</div>
							
							<?php
								for( $i = 0 ; $i < $noOfSLCand ; $i++ ){
							?>
								<div class="eventDet">
									<div class="eventCandName">
										<?php
											echo  $eventSLCandDet[$i]['firstname'].' '.$eventSLCandDet[$i]['lastname'];
										?>
									</div>
									<div class="eventCandName">
										<?php
											echo  $eventSLCandDet[$i]['admission_id'];
										?>
									</div>
									<div class="eventCandClass">
										<?php
											echo  BRANCH.' '.$eventSLCandDet[$i]['class_name'].' '.$eventSLCandDet[$i]['section_name'];
										?>
									</div>
								</div>
								<br class="clearfix" />
							<?php
								}
							?>
							<br class="clearfix" />
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
		
<script type="text/javascript" language="javascript">
	
	$(document).ready(function() {
		
	});
</script>

<?php include_once(INCLUDES_PATH . '/footer.php'); ?>

