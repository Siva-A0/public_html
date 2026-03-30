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
   
	$tbEventRes		= TB_EVENT_RESULT;

   if ( isset ( $_POST['announceResult'] ) ){
   
		$eventId		= isset($_POST['eventId']) ? intval($_POST['eventId']) : 0;
		$eventName		= isset($_POST['eventName']) ? $_POST['eventName'] : '';
		$eventRes		= array();
		$selectedCount	= 0;

		foreach( $_POST as $key => $value ){
			if( is_array($value) && isset($value['user_id']) ){
				$userDet = array(
					'user_id'	=> intval($value['user_id']),
					'award'		=> isset($value['award']) ? trim($value['award']) : ''
				);
				if( $userDet['user_id'] > 0 ){
					$selectedCount++;
					$eventRes[] = $fcObj->eventResult( $tbEventRes, $userDet, $eventId );
				}
			}
		}

		if( $selectedCount == 0 ){
			$msg	= 'Please select at least one user as winner.';
		}else if( !empty( $eventRes ) ){
			$msg	= 'Results announced successfully for event "'.$eventName.'".';
		}else{
			$msg	= 'No new results were added.';
		}
   }
	
   $tbEvents		= TB_EVENTS;
   $tbEventReg		= TB_EVENT_REG;
	
   $curEvents		= $fcObj->getResultedEvents( $tbEvents, anu );
   
   $noOfCEvents		= sizeof( $curEvents );
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
						<div class="legacy-admin-page">
							<div class="legacy-admin-hero">
								<h3 class="legacy-admin-title">Result Announcements</h3>
								<p class="legacy-admin-subtitle">Review announced results and continue managing them in the branded event workspace.</p>
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
									for( $i = 0; $i < $noOfCEvents; $i++){
										
									?>
										<div class="eventDet">
											<div class="sno">
												<?php echo $i+1; ?>
											</div>
											<div class="eventName">
												<a href="eventresult.php?event=<?php echo $curEvents[$i]['id'];?>"><?php echo $curEvents[$i]['event_name']; ?></a>
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
				<?php include_once('../layout/sidebar.php'); ?>
				<br class="clearfix" />
			</div>
		</div>

<?php 
	include_once('../layout/footer.php');
?>

