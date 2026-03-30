<?php require_once(__DIR__ . '/../../config.php');
	if (!isset($adminExtraStyles) || !is_array($adminExtraStyles)) {
    $adminExtraStyles = array();
}
$adminExtraStyles[] = BASE_URL . '/public/assets/css/admin/admin_misc_pages.css';

include_once('../layout/main_header.php');
	include_once('../layout/core_forms_style.php');
	include_once('../layout/events_list_style.php');

	require_once(LIB_PATH . '/functions.class.php');

	$fcObj = new DataFunctions();

	if (isset($_GET['event'])) {
		$eventId = intval($_GET['event']);
	} else {
		$eventId = 0;
	}

	$tbEvents = TB_EVENTS;
	$tbEventRes = TB_EVENT_RESULT;
	$tbEventReg = TB_EVENT_REG;

	$eventSLCandDet = $fcObj->getEventSLCand($tbEventReg, $eventId);
	$eventDetails = $fcObj->getEventDetails($tbEvents, $eventId);

	$noOfSLCand = sizeof($eventSLCandDet);
	$eventTitle = ($eventDetails && isset($eventDetails[0]['event_name'])) ? $eventDetails[0]['event_name'] : 'Unknown Event';
	$eventDbId = ($eventDetails && isset($eventDetails[0]['id'])) ? $eventDetails[0]['id'] : 0;
?>

<div id="page">
				<div id="content" class="single-panel-layout">
					<div class="post">
						<span class="alignCenter">
							<h4>AIML Association</h4>
						</span>
						<p></p>
					</div>
					<div id='content_right' class='content_right'>
							<div class="event-result-page">
							<div class="event-shell-hero">
								<h3 class="event-shell-title">Announce Event Result</h3>
								<p class="event-shell-subtitle">Select shortlisted candidates and assign awards inside the same branded event workflow.</p>
							</div>
						<div class="eventDetails event-result-card">
							<div class="eventTitle event-meta-row">
								<div class="eventHead">Event Title :</div>
								<div class="eventDes"><?php echo htmlspecialchars($eventTitle, ENT_QUOTES, 'UTF-8'); ?></div>
							</div>

							<div class="eventTitle event-grid-header">
								<div class="checkBox"></div>
								<div class="eventName">Candidate Name</div>
								<div class="eventName">Roll No</div>
								<div class="eventRegisDates">Award</div>
							</div>

							<form action="eventresannounce.php" method="post" enctype="multipart/form-data" class="event-result-form">
								<?php if ($noOfSLCand > 0) { ?>
									<?php for ($i = 0; $i < $noOfSLCand; $i++) { ?>
										<div class="eventDet event-grid-row">
											<div class="checkBox">
												<input type="checkbox" name="<?php echo $i; ?>[user_id]" value="<?php echo intval($eventSLCandDet[$i]['id']); ?>" />
											</div>
											<div class="eventName">
												<?php
													echo htmlspecialchars(
														$eventSLCandDet[$i]['firstname'] . ' ' . $eventSLCandDet[$i]['lastname'],
														ENT_QUOTES,
														'UTF-8'
													);
												?>
											</div>
											<div class="eventName">
												<?php echo htmlspecialchars($eventSLCandDet[$i]['admission_id'], ENT_QUOTES, 'UTF-8'); ?>
											</div>
											<div class="eventRegisDates">
												<input type="text" name="<?php echo $i; ?>[award]" value="" placeholder="Winner / Runner-up" />
											</div>
										</div>
									<?php } ?>
								<?php } else { ?>
									<div class="eventDet no-data">No users are shortlisted.</div>
								<?php } ?>

								<input type="hidden" name="eventName" value="<?php echo htmlspecialchars($eventTitle, ENT_QUOTES, 'UTF-8'); ?>" />
								<input type="hidden" name="eventId" value="<?php echo intval($eventDbId); ?>" />
								<input type="submit" class="button" name="announceResult" value="Announce Result" />
							</form>
						</div>
						</div>
					</div>
					<br class="clearfix" />
				</div>
				                <div class="mt-3">
                    <a href="../settings/department_option.php?option=event_results" class="btn btn-outline-secondary">Back</a>
                </div><?php include_once('../layout/sidebar.php'); ?>
				<br class="clearfix" />
			</div>
		</div>

<?php include_once('../layout/footer.php'); ?>

