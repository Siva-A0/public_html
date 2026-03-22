<?php require_once(__DIR__ . '/../../config.php');
	include_once('../layout/main_header.php');
	include_once('../layout/core_forms_style.php');
	
   require_once(LIB_PATH . '/functions.class.php');

   $fcObj			= new DataFunctions();
	
	$tbEvents		= TB_EVENTS;
	
	
	$tbEventTypes	= TB_EVENT_TYPES;
	
	$eventTypes		= $fcObj->getEventTypes( $tbEventTypes );
?>
		<style type="text/css">
			.events-add-page {
				--ep-primary: #173d69;
				--ep-primary-deep: #13345a;
				--ep-accent: #f0b323;
				--ep-accent-deep: #d79a12;
				--ep-surface: #eef4fa;
				--ep-border: #d9e3ef;
				--ep-border-strong: #c8d6e6;
				--ep-muted: #6b819c;
			}

			#content_left {
				display: none;
			}

			#content {
				grid-template-columns: 1fr;
				gap: 0;
			}

			#page {
				max-width: none;
			}

			.events-add-page {
				background: linear-gradient(180deg, #f3f7fb 0%, var(--ep-surface) 100%);
				border-radius: 24px;
				padding: 24px;
			}

			.events-add-hero {
				position: relative;
				overflow: hidden;
				border: 1px solid var(--ep-border);
				border-radius: 22px;
				padding: 22px 24px;
				background: linear-gradient(135deg, #f9fbfe 0%, var(--ep-surface) 100%);
				box-shadow: 0 14px 30px rgba(15, 23, 42, 0.08);
				margin-bottom: 16px;
			}

			.events-add-hero::before {
				content: "";
				position: absolute;
				inset: 0 auto 0 0;
				width: 6px;
				background: linear-gradient(180deg, var(--ep-accent), var(--ep-accent-deep));
			}

			.events-add-hero h1 {
				margin: 0 0 6px;
				font-size: 32px;
				font-weight: 800;
				letter-spacing: -0.6px;
				color: var(--ep-primary-deep);
			}

			.events-add-hero p {
				margin: 0;
				font-size: 15px;
				color: var(--ep-muted);
			}

			#content_right #eventDetails {
				background: #ffffff;
				padding: 24px;
				border: 1px solid var(--ep-border);
				border-radius: 18px;
				box-shadow: 0 10px 24px rgba(15, 23, 42, 0.07);
			}

			#addEvent .form_label label {
				color: var(--ep-primary);
				font-weight: 700;
			}

			#addEvent .form_field textarea {
				width: 100%;
				min-height: 110px;
				border: 1px solid var(--ep-border-strong);
				border-radius: 12px;
				padding: 10px 12px;
				background: #f7f9fc;
				font-size: 15px;
				outline: none;
				resize: vertical;
			}

			#addEvent .form_field textarea:focus {
				border-color: #87a6cb;
				background: #ffffff;
				box-shadow: 0 0 0 4px rgba(23, 61, 105, 0.12);
			}

			#addEvent .form_field input[type="date"] {
				width: 100%;
				min-height: 48px;
				border: 1px solid var(--ep-border-strong);
				border-radius: 12px;
				padding: 10px 12px;
				background: #f7f9fc;
				font-size: 15px;
				outline: none;
			}

			#addEvent .form_field input[type="date"]:focus {
				border-color: #87a6cb;
				background: #ffffff;
				box-shadow: 0 0 0 4px rgba(23, 61, 105, 0.12);
			}

			#addEvent .button {
				border: 0;
				border-radius: 12px;
				padding: 11px 20px;
				background: linear-gradient(135deg, var(--ep-primary-deep), var(--ep-primary));
				font-weight: 700;
				box-shadow: 0 10px 20px rgba(19, 52, 90, 0.24);
				color: #fff;
			}
		</style>

			<div id="page">
				<div id="content">
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
									} else {
										$addEvent = false;
										$eventMessage = 'Please fill required fields (event type, event name, event date).';
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
							<form id='addEvent' action='events.php' method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
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
								<div class="form_row">
									<div class="form_label">
										
									</div>
									<div class="form_field">
										<input type='submit' name='addNewEvent' class="button" value='Add Event' />
									</div>
								</div>
								<div class="form_row">
									<div class="form_label">
										
									</div>
									<div class="form_field">
										<a href="view_events.php" ><input type='button' name='' class="button" value='View Events' /></a>
									</div>
								</div>
								
							</form>
							
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

<?php 
	include_once('../layout/footer.php');
?>
