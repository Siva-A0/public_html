<?php require_once(__DIR__ . '/../../config.php');
	if (!isset($adminExtraStyles) || !is_array($adminExtraStyles)) {
    $adminExtraStyles = array();
}
$adminExtraStyles[] = BASE_URL . '/public/assets/css/admin/admin_misc_pages.css';

include_once('../layout/main_header.php');
	include_once('../layout/core_forms_style.php');
	include_once('../layout/events_list_style.php');
	
   require_once(LIB_PATH . '/functions.class.php');

   $fcObj			= new DataFunctions();
	
	if( isset( $_GET['event'] )){
		$eventId		= (int)$_GET['event'];
	}else{
		$eventId		= 0;
	}
	
	$tbEvents		= TB_EVENTS;
	$tbEventReg		= TB_EVENT_REG;
	
	$eventRegCandDet = $fcObj->getEventRegCand( $tbEventReg , $eventId );
	
	$eventDetails	= $fcObj->getEventDetails( $tbEvents , $eventId );	
	
	$noOfRegCand	= sizeof( $eventRegCandDet );
	$eventName		= ( !empty($eventDetails) && isset($eventDetails[0]['event_name']) ) ? $eventDetails[0]['event_name'] : 'Unknown Event';
	$eventIdValue	= ( !empty($eventDetails) && isset($eventDetails[0]['id']) ) ? $eventDetails[0]['id'] : $eventId;
?>

<div id="page">
				<div id="content" class="single-panel-layout">
					<div class="post">
						<span class="alignCenter">
							<h4>AIML Association </h4>
						</span>
						<p>
							
						</p>
					</div>
					<div id='content_right' class='content_right'>
							<div class="event-candidates-page">
							<div class="event-shell-hero">
								<h3 class="event-shell-title">Shortlist Candidates</h3>
								<!-- <p class="event-shell-subtitle">Review registered candidates and shortlist them inside the school-branded event workflow.</p> -->
							</div>
						<section class="event-candidates-shell">
							<header class="event-summary">
								<span class="summary-label">Event Title</span>
								<h5 class="summary-value"><?php echo $eventName; ?></h5>
							</header>

							<form action="eventregcand.php" method="post" enctype="multipart/form-data" class="candidate-form">
								<div class="candidate-table" role="table" aria-label="Registered candidates">
									<div class="candidate-row candidate-head" role="row">
										<div class="candidate-cell select-col" role="columnheader">Select</div>
										<div class="candidate-cell" role="columnheader">Candidate Name</div>
										<div class="candidate-cell" role="columnheader">Roll No</div>
										<div class="candidate-cell" role="columnheader">Candidate Details</div>
									</div>

									<?php if( $noOfRegCand == 0 ) { ?>
										<div class="candidate-row candidate-empty" role="row">
											<div class="candidate-cell" role="cell"></div>
										</div>
									<?php } ?>

									<?php for( $i = 0 ; $i < $noOfRegCand ; $i++ ) { ?>
										<div class="candidate-row" role="row">
											<div class="candidate-cell select-col" role="cell" data-label="Select">
												<input type="checkbox" name="event_<?php echo $eventRegCandDet[$i]['id'];?>" value="<?php echo $eventRegCandDet[$i]['id'];?>" />
											</div>
											<div class="candidate-cell" role="cell" data-label="Candidate Name">
												<?php echo $eventRegCandDet[$i]['firstname'].' '.$eventRegCandDet[$i]['lastname']; ?>
											</div>
											<div class="candidate-cell" role="cell" data-label="Roll No">
												<?php echo $eventRegCandDet[$i]['admission_id']; ?>
											</div>
											<div class="candidate-cell" role="cell" data-label="Candidate Details">
												<?php echo $eventRegCandDet[$i]['stream_code'].' '.$eventRegCandDet[$i]['class_name'].' '.$eventRegCandDet[$i]['section_name']; ?>
											</div>
										</div>
									<?php } ?>
								</div>

								<input type="hidden" name="eventName" value="<?php echo $eventName;?>" />
								<input type="hidden" name="eventId" value="<?php echo $eventIdValue;?>" />

								<div class="candidate-actions">
									<button type="submit" class="button" name="approveUser">Short List Selected</button>
								</div>
							</form>
						</section>						
						</div>
					</div>
					<br class="clearfix" />
				</div>
				                <div class="mt-3">
                    <a href="../settings/department_option.php?option=event_candidates" class="btn btn-outline-secondary">Back</a>
                </div><?php 
					include_once('../layout/sidebar.php');
				?>
				<br class="clearfix" />
			</div>
		</div>

<?php 
	include_once('../layout/footer.php');
?>

