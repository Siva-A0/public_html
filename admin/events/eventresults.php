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
	
   $curEvents		= $fcObj->getResultedEvents( $tbEvents, anu );
   
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
								<h3 class="legacy-admin-title">Event Results</h3>
								<p class="legacy-admin-subtitle">Choose resulted events and continue into the branded result-announcement flow.</p>
							</div>
						<div id="currentevents" class="currentevents">
							<div id="eventDetails">
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
				                <div class="mt-3">
                    <a href="../settings/department_option.php?option=event_results" class="btn btn-outline-secondary">Back</a>
                </div><?php 
					include_once('../layout/sidebar.php');
				?>
				<br class="clearfix" />
			</div>
		</div>

<?php 
	include_once('../layout/footer.php');
?>

