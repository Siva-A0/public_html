<?php require_once(__DIR__ . '/../../config.php');
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

<style type="text/css">
	.event-candidates-page {
		background: linear-gradient(180deg, #f3f7fb 0%, #eef4fa 100%);
		border-radius: 24px;
		padding: 24px;
	}
	.event-shell-hero {
		position: relative; overflow: hidden; border: 1px solid #d9e3ef; border-radius: 22px; padding: 22px 24px;
		background: linear-gradient(135deg, #f9fbfe 0%, #eef4fa 100%); box-shadow: 0 14px 30px rgba(15,23,42,.08); margin-bottom: 16px;
	}
	.event-shell-hero::before { content: ""; position: absolute; inset: 0 auto 0 0; width: 6px; background: linear-gradient(180deg, #f0b323, #d79a12); }
	.event-shell-title { margin: 0; font-size: 32px; font-weight: 800; letter-spacing: -0.6px; color: #13345a; }
	.event-shell-subtitle { margin: 8px 0 0; font-size: 15px; color: #6b819c; }
</style>

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
								<p class="event-shell-subtitle">Review registered candidates and shortlist them inside the school-branded event workflow.</p>
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
											<div class="candidate-cell" role="cell">No registered candidates found for this event.</div>
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
<style type="text/css">
	#content_right {
		align-self: start;
	}

	#content .post {
		margin-bottom: 8px;
	}

	#content_right .event-candidates-shell {
		margin-top: 0;
	}

	.event-candidates-shell {
		--evt-bg: #ffffff;
		--evt-border: #d9e3ef;
		--evt-header: #eef4fa;
		--evt-text: #13345a;
		--evt-subtext: #6b819c;
		--evt-accent: #173d69;
		--evt-line: #d9e3ef;
		background: var(--evt-bg);
		border: 1px solid var(--evt-border);
		border-radius: 18px;
		box-shadow: 0 14px 26px rgba(15, 23, 42, 0.08);
		padding: 20px;
	}

	.event-summary {
		display: flex;
		align-items: center;
		gap: 12px;
		background: var(--evt-header);
		border: 1px solid #d9e3ef;
		border-radius: 12px;
		padding: 14px 16px;
		margin-bottom: 14px;
	}

	.summary-label {
		font-size: 16px;
		font-weight: 700;
		color: #1e293b;
	}

	.summary-value {
		margin: 0;
		font-size: 18px;
		font-weight: 700;
		letter-spacing: 0;
		line-height: 1.25;
		color: var(--evt-accent);
	}

	.candidate-table {
		border: 1px solid var(--evt-border);
		border-radius: 12px;
		overflow: hidden;
		margin-top: 6px;
	}

	.candidate-row {
		display: grid;
		grid-template-columns: 90px minmax(210px, 1.2fr) minmax(170px, 0.9fr) minmax(220px, 1.2fr);
		gap: 0;
		align-items: center;
		padding: 0;
		border-bottom: 1px solid var(--evt-line);
		background: #ffffff;
	}

	.candidate-row:last-child {
		border-bottom: 0;
	}

	.candidate-head {
		background: var(--evt-header);
		color: var(--evt-accent);
		font-size: 16px;
		font-weight: 700;
		letter-spacing: 0;
	}

	.candidate-cell {
		font-size: 15px;
		font-weight: 600;
		color: var(--evt-text);
		padding: 14px 16px;
		line-height: 1.35;
		display: flex;
		align-items: center;
	}

	.candidate-head .candidate-cell {
		color: var(--evt-accent);
		font-weight: 700;
		min-height: 58px;
	}

	.candidate-cell.select-col {
		display: flex;
		justify-content: center;
		padding-left: 0;
		padding-right: 0;
	}

	.candidate-cell input[type="checkbox"] {
		width: 19px;
		height: 19px;
		accent-color: #173d69;
		cursor: pointer;
	}

	.candidate-empty .candidate-cell {
		grid-column: 1 / -1;
		text-align: center;
		justify-content: center;
		color: var(--evt-subtext);
		font-weight: 600;
		padding: 18px 16px;
	}

	.candidate-actions {
		margin-top: 20px;
	}

	.candidate-actions .button {
		border: 0;
		border-radius: 13px;
		padding: 12px 22px;
		background: linear-gradient(135deg, #13345a, #173d69);
		color: #fff;
		font-weight: 800;
		font-size: 15px;
		letter-spacing: 0.2px;
		box-shadow: 0 10px 18px rgba(16, 42, 72, 0.24);
	}

	.candidate-actions .button:hover {
		filter: brightness(1.05);
		transform: translateY(-1px);
	}

	@media (max-width: 980px) {
		.event-summary {
			flex-direction: column;
			align-items: flex-start;
			gap: 8px;
		}

		.summary-value {
			font-size: 17px;
		}

		.candidate-head {
			display: none;
		}

		.candidate-row {
			grid-template-columns: 1fr;
			gap: 0;
			padding: 8px 0;
		}

		.candidate-cell {
			font-size: 14px;
			padding: 8px 14px;
		}

		.candidate-cell.select-col {
			justify-content: flex-start;
		}

		.candidate-row .candidate-cell::before {
			content: attr(data-label);
			display: block;
			font-size: 12px;
			font-weight: 700;
			text-transform: uppercase;
			letter-spacing: 0.4px;
			color: #64748b;
			margin-bottom: 2px;
		}
	}
</style>

<?php 
	include_once('../layout/footer.php');
?>
