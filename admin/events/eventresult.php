<?php require_once(__DIR__ . '/../../config.php');
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

<style type="text/css">
	.event-result-page { background: linear-gradient(180deg, #f3f7fb 0%, #eef4fa 100%); border-radius: 24px; padding: 24px; }
	.event-shell-hero { position: relative; overflow: hidden; border: 1px solid #d9e3ef; border-radius: 22px; padding: 22px 24px; background: linear-gradient(135deg, #f9fbfe 0%, #eef4fa 100%); box-shadow: 0 14px 30px rgba(15,23,42,.08); margin-bottom: 16px; }
	.event-shell-hero::before { content: ""; position: absolute; inset: 0 auto 0 0; width: 6px; background: linear-gradient(180deg, #f0b323, #d79a12); }
	.event-shell-title { margin: 0; font-size: 32px; font-weight: 800; letter-spacing: -0.6px; color: #13345a; }
	.event-shell-subtitle { margin: 8px 0 0; font-size: 15px; color: #6b819c; }
</style>

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

<style type="text/css">
	#content_right {
		align-self: start;
	}

	#content .post {
		margin-bottom: 8px;
	}

	.event-result-card {
		background: #ffffff;
		border: 1px solid #d9e3ef;
		border-radius: 14px;
		box-shadow: 0 10px 24px rgba(15, 23, 42, 0.07);
		padding: 16px;
	}

	.event-result-card .event-meta-row,
	.event-result-card .event-grid-header,
	.event-result-card .event-grid-row,
	.event-result-card .eventDet {
		display: grid;
		grid-template-columns: 80px 1fr 180px 1.2fr;
		gap: 10px;
		align-items: center;
		padding: 10px 12px;
		border-bottom: 1px solid #d9e3ef;
	}

	.event-result-card .event-meta-row {
		grid-template-columns: 130px 1fr;
		background: #f8fafc;
		border: 1px solid #e2e8f0;
		border-radius: 10px;
		margin-bottom: 10px;
	}

	.event-result-card .event-grid-header {
		background: #eef4fa;
		border: 1px solid #d9e3ef;
		border-radius: 10px;
		color: #173d69;
		font-weight: 700;
		margin-bottom: 8px;
	}

	.event-result-card .event-grid-row:last-of-type {
		border-bottom: 0;
	}

	.event-result-card .eventHead {
		font-weight: 700;
		color: #173d69;
	}

	.event-result-card .eventDes,
	.event-result-card .eventName,
	.event-result-card .eventRegisDates {
		word-break: break-word;
	}

	.event-result-card input[type="checkbox"] {
		width: 18px;
		height: 18px;
	}

	.event-result-card input[type="text"] {
		width: 100%;
		min-height: 42px;
		padding: 9px 12px;
		border: 1px solid #c8d6e6;
		border-radius: 10px;
		background: #f8fafc;
		outline: none;
	}

	.event-result-card input[type="text"]:focus {
		border-color: #87a6cb;
		background: #ffffff;
		box-shadow: 0 0 0 3px rgba(23, 61, 105, 0.12);
	}

	.event-result-card .button {
		margin-top: 16px;
		border: 0;
		border-radius: 12px;
		padding: 10px 20px;
		background: linear-gradient(135deg, #13345a, #173d69);
		color: #fff;
		font-weight: 700;
		box-shadow: 0 8px 16px rgba(16, 42, 72, 0.24);
	}

	.event-result-card .no-data {
		display: flex;
		grid-template-columns: none;
		justify-content: center;
		align-items: center;
		width: 100%;
		font-weight: 600;
		color: #6b819c;
		padding: 18px 12px;
		border-bottom: 0;
		text-align: center;
		line-height: 1.4;
		white-space: normal;
	}

	#content_right .event-result-card .eventDet.no-data {
		display: flex;
	}

	@media (max-width: 980px) {
		.event-result-card .event-grid-header,
		.event-result-card .event-grid-row,
		.event-result-card .eventDet {
			grid-template-columns: 1fr;
		}

		.event-result-card .event-meta-row {
			grid-template-columns: 1fr;
		}
	}
</style>

<?php include_once('../layout/footer.php'); ?>
