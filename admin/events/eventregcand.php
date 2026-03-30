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
   $tbEventReg		= TB_EVENT_REG;

  if (isset($_POST['approveUser'])) {
	   $eventId = isset($_POST['eventId']) ? (int)$_POST['eventId'] : 0;
	   $eventName = isset($_POST['eventName']) ? trim((string)$_POST['eventName']) : '';
	   $regUsers = array();
	   foreach ($_POST as $key => $value) {
	   		if (strpos((string)$key, 'event_') === 0) {
				$userId = (int)$value;
				if ($userId > 0) {
					$regUsers[] = $userId;
				}
			}
	   }

	   $noOfSLUsers = sizeof($regUsers);
	   $msg = '';

	   if ($noOfSLUsers == 0) {
	   		$msg = 'Please Select Atleast One User For Event " '. $eventName.' " To Approve';
	   }

	   for ($i = 0; $i < $noOfSLUsers; $i++) {
	   		$approveUser[$i] = $fcObj->approveUserForEvent($tbEventReg, $eventId, $regUsers[$i]);
	   }

	   if (!empty($approveUser)) {
	   		$msg = 'Users For Event " '. $eventName.' " Are Short Listed Successfully';
	   }
  }
	
   $curEvents		= $fcObj->getRegisteredCandidateEvents( $tbEvents, anu );
   
   $noOfCEvents		= sizeof( $curEvents );
	
?>



<div id="page">
				<div id="content">
					<div class="post">
						<span class="alignCenter"></span>
						<p></p>
					</div>
					<div id='content_left' class='content_left'></div>
					<div id='content_right' class='content_right'>
						<div class="legacy-admin-page">
							<div class="legacy-admin-hero">
								<h3 class="legacy-admin-title">Event Candidate Approval</h3>
								<p class="legacy-admin-subtitle">Open registration-enabled events and manage candidate shortlisting in the same branded flow.</p>
							</div>
						<div id="currentevents" class="currentevents">
							<div id="eventDetails">
								<?php
									if( isset ( $msg ) ){
								?>
									<div class="comteeMemRow">
										<div class="usersDetHeader">
											<?php echo $msg;?>
										</div>
									</div>
								<?php
									}
								?>
								<div class="eventDetHeader">
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
								
								<?php
									if( $noOfCEvents == 0 ){
								?>
									<div class="eventDet">
										<div class="eventName" style="grid-column: 1 / -1;">
											No registration-enabled events found.
										</div>
									</div>
								<?php
									}
									for( $i = 0; $i < $noOfCEvents; $i++){
								?>
									<div class="eventDet">
										<div class="sno">
											<?php echo $i+1; ?>
										</div>
										<div class="eventName">
											<a href="eventslcandidates.php?event=<?php echo $curEvents[$i]['id'];?>"><?php echo $curEvents[$i]['event_name']; ?></a>
										</div>
										<div class="eventDate">
											<?php echo date("d-m-Y", strtotime($curEvents[$i]['event_date'])); ?>
										</div>
										<div class="eventRegisDates">
											<?php echo date("d-m-Y", strtotime($curEvents[$i]['reg_frm_date'])).' to '.date("d-m-Y", strtotime($curEvents[$i]['reg_to_date'])); ?>
										</div>
									</div>
								<?php
									}
								?>
							</div>
						</div>
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

