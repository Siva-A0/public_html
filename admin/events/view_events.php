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
	
	$tbEvents		= TB_EVENTS;

	$allEvents		= $fcObj->getEventDetails($tbEvents);
	$pastEvents		= array();
	$curEvents		= array();
	$futureEvents	= array();

	$month			= date("M Y");
	$startDate		= strtotime(date('Y-m-01', strtotime($month)));
	$endDate		= strtotime(date('Y-m-t', strtotime($month)));

	foreach ($allEvents as $event) {
		$eventTime = strtotime((string)$event['event_date']);
		if ($eventTime === false) {
			continue;
		}

		if ($eventTime < $startDate) {
			$pastEvents[] = $event;
		} elseif ($eventTime > $endDate) {
			$futureEvents[] = $event;
		} else {
			$curEvents[] = $event;
		}
	}

	usort($pastEvents, function ($a, $b) {
		return strtotime((string)$b['event_date']) <=> strtotime((string)$a['event_date']);
	});

	usort($curEvents, function ($a, $b) {
		return strtotime((string)$a['event_date']) <=> strtotime((string)$b['event_date']);
	});

	usort($futureEvents, function ($a, $b) {
		return strtotime((string)$a['event_date']) <=> strtotime((string)$b['event_date']);
	});

	$noOfPEvents	= sizeof( $pastEvents );
	$noOfCEvents	= sizeof( $curEvents );
	$noOfFEvents	= sizeof( $futureEvents );
	
?>

<div id="page">
				<div id="content" class="single-panel-layout">
					<div class="post">
						<span class="alignCenter"></span>
						<p></p>
					</div>
					<div id='content_left' class='content_left'></div>
					<div id='content_right' class='content_right'>
						<div class="events-view-page">
						<div class="events-view-hero">
							<h1>Manage Events</h1>
							<p>Browse past, current, and future events with quick edit and delete actions.</p>
						</div>
						<div class="eventHeader">
							<div class="eventCateg" id="pastEvent">
								<a href="#pastevents" class='pastEvent'>Past Events</a>
							</div>
							<div class="eventCateg" id="currentEvent">
								<a href="#currentevents" class='currentEvent'>Current Events</a>
							</div>
							<div class="eventCateg" id="futureEvent">
								<a href="#futureevents" class='futureEvent'>Future Events</a>
							</div>
						</div>
						<div id="eventDetails">
							<div id="pastevents" class="pastevents">
								<div class="eventDetHeader">
									<div class="checkBox">
										
									</div>
									<div class="sno">
										S NO
									</div>
									<div class="eventName">
										Event Name
									</div>
									<div class="eventDate">
										Event Date
									</div>
									<div class="eventRegisDates">
										Registration Dates
									</div>		
								</div>
								<form action="view_events.php" method="POST" enctype="multipart/form-data">
								<?php
									for( $i = 0; $i < $noOfPEvents; $i++){
										
									?>
										<div class="eventDet">
											<div class="checkBox">
											
											</div>
											<div class="sno">
												<?php echo $i+1; ?>
											</div>
											<div class="eventName">
												<a href="eventdetails.php?event=<?php echo $pastEvents[$i]['id'];?>"><?php echo $pastEvents[$i]['event_name']; ?></a>
											</div>
											<div class="eventDate">
												<?php echo date("d-m-Y", strtotime($pastEvents[$i]['event_date'])); ?>
											</div>
											<div class="eventRegisDates">
												<span class="eventRegisRange"><?php echo date("d-m-Y", strtotime($pastEvents[$i]['reg_frm_date'])).' to '.date("d-m-Y", strtotime($pastEvents[$i]['reg_to_date'])); ?></span>
												<a href="delete_event.php?event=<?php echo $pastEvents[$i]['id'];?>" >
													<input type="button" class="button" id="delete" value="Delete"/>
												</a>
											</div>
										</div>
									<?php
									}
								?>
							</div>
							<div id="currentevents" class="currentevents">
								<div class="eventDetHeader">
									<div class="checkBox">
										
									</div>
									<div class="sno">
										S NO
									</div>
									<div class="eventName">
										Event Name
									</div>
									<div class="eventDate">
										Event Date
									</div>
									<div class="eventRegisDates">
										Registration Dates
									</div>		
								</div>
								<form action="view_events.php" name="currentEventForm" id="currentEventForm" class="currentEventForm" method="POST" enctype="multipart/form-data">
								<?php
									for( $i = 0; $i < $noOfCEvents; $i++){
										
									?>
										<div class="eventDet">
											<div class="checkBox">
											<?php
												
												$todayDate	= strtotime( date('Y-m-d') );
												$eventRegD1	= strtotime( $curEvents[$i]['reg_frm_date'] );
												$eventRegD2	= strtotime( $curEvents[$i]['reg_to_date'] );
												
											?>
											</div>
											<div class="sno">
												<?php echo $i+1; ?>
											</div>
											<div class="eventName">
												<a href="eventdetails.php?event=<?php echo $curEvents[$i]['id'];?>"><?php echo $curEvents[$i]['event_name']; ?></a>
											</div>
											<div class="eventDate">
												<?php echo date("d-m-Y", strtotime($curEvents[$i]['event_date'])); ?>
											</div>
											<div class="eventRegisDates">
												<span class="eventRegisRange"><?php echo date("d-m-Y", strtotime($curEvents[$i]['reg_frm_date'])).' to '.date("d-m-Y", strtotime($curEvents[$i]['reg_to_date'])); ?></span>
												<a href="edit_event.php?event=<?php echo $curEvents[$i]['id'];?>" >
													<input type="button" class="button" id="edit" value="Edit"/>
												</a>
												<a href="delete_event.php?event=<?php echo $curEvents[$i]['id'];?>" >
													<input type="button" class="button" id="delete" value="Delete"/>
												</a>
											</div>
										</div>
									<?php
									}
								?>
									
								</form>
							</div>
							<div id="futureevents" class="futureevents">
								<div class="eventDetHeader">
									<div class="checkBox">
										
									</div>
									<div class="sno">
										S NO
									</div>
									<div class="eventName">
										Event Name
									</div>
									<div class="eventDate">
										Event Date
									</div>
									<div class="eventRegisDates">
										Registration Dates
									</div>		
								</div>
								<form action="view_events.php" name="futureEventForm" id="futureEventForm" class="futureEventForm" method="POST" enctype="multipart/form-data">			<?php
									for( $i = 0; $i < $noOfFEvents; $i++){
										
									?>
										<div class="eventDet">
											<div class="checkBox">
											<?php
												
												$todayDate	= strtotime( date('Y-m-d') );
												$eventRegD1	= strtotime( $futureEvents[$i]['reg_frm_date'] );
												$eventRegD2	= strtotime( $futureEvents[$i]['reg_to_date'] );
												
											?>
											</div>
											<div class="sno">
												<?php echo $i+1; ?>
											</div>
											<div class="eventName">
												<a href="eventdetails.php?event=<?php echo $futureEvents[$i]['id'];?>" ><?php echo $futureEvents[$i]['event_name']; ?></a>
											</div>
											<div class="eventDate">
												<?php echo date("d-m-Y", strtotime($futureEvents[$i]['event_date'])); ?>
											</div>
											<div class="eventRegisDates">
												<span class="eventRegisRange"><?php echo date("d-m-Y", strtotime($futureEvents[$i]['reg_frm_date'])).' to '.date("d-m-Y", strtotime($futureEvents[$i]['reg_to_date'])); ?></span>
												<a href="delete_event.php?event=<?php echo $futureEvents[$i]['id'];?>" >
													<input type="button" class="button" id="delete" value="Delete"/>
												</a>
											</div>
										</div>
									<?php
									}
								?>
									
								</form>
							</div>
						</div>
						</div>
					</div>
					<br class="clearfix" />
				</div>
				                <div class="mt-3">
                    <a href="../settings/department_option.php?option=events" class="btn btn-outline-secondary">Back</a>
                </div><?php 
					include_once('../layout/sidebar.php');
				?>
				<br class="clearfix" />
			</div>
		</div>
		
<script type="text/javascript" language="javascript">
	document.addEventListener('DOMContentLoaded', function () {
		var sections = {
			past: document.getElementById('pastevents'),
			current: document.getElementById('currentevents'),
			future: document.getElementById('futureevents')
		};

		var tabs = {
			past: document.getElementById('pastEvent'),
			current: document.getElementById('currentEvent'),
			future: document.getElementById('futureEvent')
		};

		function showSection(key) {
			Object.keys(sections).forEach(function (k) {
				if (sections[k]) {
					sections[k].style.display = (k === key) ? 'block' : 'none';
				}
				if (tabs[k]) {
					tabs[k].className = (k === key) ? 'eventCategCurrent' : 'eventCateg';
				}
			});
		}

		var pastLink = document.querySelector('.pastEvent');
		var currentLink = document.querySelector('.currentEvent');
		var futureLink = document.querySelector('.futureEvent');

		if (pastLink) {
			pastLink.addEventListener('click', function (event) {
				event.preventDefault();
				showSection('past');
			});
		}

		if (currentLink) {
			currentLink.addEventListener('click', function (event) {
				event.preventDefault();
				showSection('current');
			});
		}

		if (futureLink) {
			futureLink.addEventListener('click', function (event) {
				event.preventDefault();
				showSection('future');
			});
		}

		showSection('current');

		var deleteButtons = document.querySelectorAll('input#delete');
		deleteButtons.forEach(function (btn) {
			btn.addEventListener('click', function (event) {
				var ok = confirm('Do You Want To Continue To Delete');
				if (!ok) {
					event.preventDefault();
				}
			});
		});
	});
</script>

<?php 
	include_once('../layout/footer.php');
?>

