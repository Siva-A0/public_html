<?php require_once(__DIR__ . '/../../config.php');
	if (!isset($adminExtraStyles) || !is_array($adminExtraStyles)) {
    $adminExtraStyles = array();
}
$adminExtraStyles[] = BASE_URL . '/public/assets/css/admin/admin_misc_pages.css';

include_once('../layout/main_header.php');
	include_once('../layout/core_forms_style.php');
	
   require_once(LIB_PATH . '/functions.class.php');

   $fcObj			= new DataFunctions();
	
	$tbEvents		= TB_EVENTS;
	
	
	$tbEventTypes	= TB_EVENT_TYPES;
	
	$eventTypes		= $fcObj->getEventTypes( $tbEventTypes );
?>

<div id="page">
				<div id="content" class="single-panel-layout">
					<div class="post">
						<span class="alignCenter"></span>
						<p></p>
					</div>
					<div id='content_left' class='content_left'></div>
					<div id='content_right' class='content_right'>
						<div class="events-add-page">
						<div class="events-add-hero">
							<h1>Add Event</h1>
							<p>Create event records, dates, venues, and registration windows.</p>
						</div>
						<div id="eventDetails">
							<?php
								if( isset ( $_POST['addNewEvent'] ) ){

									$typedEventType = trim((string)($_POST['eventType'] ?? ''));
									$eventTypeId = $fcObj->getOrCreateEventTypeId($tbEventTypes, $typedEventType);
									$varArray['event_type_id']	= $eventTypeId;
									$varArray['event_name']		= $_POST['eventName'];
									$varArray['event_desc']		= $_POST['eventDesc'];
									$varArray['event_address']	= $_POST['eventVenue'];
									$varArray['event_date']		= $_POST['eventDate'];
									$varArray['reg_frm_date']	= $_POST['eventRegDate1'];
									$varArray['reg_to_date']	= $_POST['eventRegDate2'];
									
									if( isset ( $_POST['isReg'] ) ){
										$varArray['is_registration']	= 1;
									}else{
										$varArray['is_registration']	= 0;
									}
									
									if ($eventTypeId > 0 && trim((string)$varArray['event_name']) !== '' && trim((string)$varArray['event_date']) !== '') {
										$addEvent = $fcObj->addNewEvent ( $tbEvents, $varArray );
										$eventMessage = $addEvent ? 'Event Added Successfully' : 'Sorry, Please Try Again';
										if (!$addEvent) {
											$saveError = trim((string)$fcObj->getLastError());
											if ($saveError === '' && method_exists($fcObj->dbObj, 'getLastError')) {
												$saveError = trim((string)$fcObj->dbObj->getLastError());
											}
											if ($saveError !== '') {
												$eventMessage .= '. ' . $saveError;
											}
										}
									} else {
										$addEvent = false;
										$eventMessage = 'Please fill required fields (event type, event name, event date).';
										$typeError = trim((string)$fcObj->getLastError());
										if ($typeError !== '') {
											$eventMessage .= ' ' . $typeError;
										}
									}
									?>
									<div class="comteeMemRow">
										<div class="usersDetHeader">
									<?php
										echo htmlspecialchars((string)$eventMessage, ENT_QUOTES, 'UTF-8');
									?>
										</div>
									</div>
									<?php
								}
								
							?>
							<form id='addEvent' class="core-form" action='events.php' method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
								<div class="form_row">
									<div class="form_label">
										<label for='eventType' >Event Type:</label>
									</div>
									<div class="form_field">
										<input type="text" name="eventType" id="eventType" class="eventTypeId" value="" placeholder="Type event type" required />
									</div>
								</div>
								<div class="form_row">
									<div class="form_label">
										<label for='eventname' >Event Name:</label>
									</div>
									<div class="form_field">
										<input type="text" name="eventName" id="eventName" class="eventName" value="" />
									</div>
								</div>
								<div class="form_row">
									<div class="form_label">
										<label for='eventDesc' >Event Description:</label>
									</div>
									<div class="form_field" id="section"> 
										<textarea rows="5" cols="17" name="eventDesc" id="eventDesc" class="eventDesc"></textarea>
									</div>
								</div>
								<div class="form_row">
									<div class="form_label">
										<label for='eventVenue' >Event Venue:</label>
									</div>
									<div class="form_field">
										<textarea rows="5" cols="17" name="eventVenue" id="eventVenue" class="eventVenue"></textarea>
									</div>
								</div>
								<div class="form_row">
									<div class="form_label">
										<label for='eventVenue' >Event Date:</label>
									</div>
									<div class="form_field"> 
										<input type="date" name="eventDate" id="eventDate" value=""/>
									</div>
								</div>
								<div class="form_row">
									<div class="form_label">
										<label for='eventVenue' >Registration Start Date:</label>
									</div>
									<div class="form_field"> 
										<input type="date" name="eventRegDate1" id="eventRegDate1" value=""/>
									</div>
								</div>
								<div class="form_row">
									<div class="form_label">
										<label for='eventVenue' >Registration End Date:</label>
									</div>
									<div class="form_field"> 
										<input type="date" name="eventRegDate2" id="eventRegDate2" value=""/>
									</div>
								</div>
								<div class="form_row">
									<div class="form_label">
										<input type="checkbox" name="isReg" id="isReg" class="isReg" />
									</div>
									<div class="form_field"> 
										Is Registration Allowed
									</div>
								</div>
								<div class="form_row form_actions">
									<div class="form_label">
										
									</div>
									<div class="form_field">
										<input type='submit' name='addNewEvent' class="button" value='Add Event' />
									</div>
								</div>
								<div class="form_row form_actions">
									<div class="form_label">
										
									</div>
									<div class="form_field">
										<a href="view_events.php" class="button secondary-action">View Events</a>
									</div>
								</div>
								
							</form>
							
						</div>
						</div>
						<div class="mt-3 events-add-back">
                    <a href="../settings/department_option.php?option=events" class="btn btn-outline-secondary">Back</a>
                </div>
					</div>
					<br class="clearfix" />
				</div>
				<?php 
					include_once('../layout/sidebar.php');
				?>
				<br class="clearfix" />
			</div>
		</div>

<?php 
	include_once('../layout/footer.php');
?>

