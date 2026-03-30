<?php 
	include_once('header.php');

   require_once("../libraries/functions.class.php") ;

   $fcObj			= new DataFunctions();
	
   $tbEvents		= TB_EVENTS;
   $tbEventReg		= TB_EVENT_REG;
	
   $curEvents		= $fcObj->getCurrentEvents(	$tbEvents, anu );
   
   $noOfCEvents		= sizeof( $curEvents );
	
?>

<div id="page">
				<div id="content">
					<div class="post">
						<span class="alignCenter">
							<h4>AIML Association </h4>
						</span>
						<p>
							
						</p>
					</div>
					<div id='content_left' class='content_left'>
						<?php 
							include_once('leftnav.php');
						?>						
					</div>
					<div id='content_right' class='content_right'>
						<div class="legacy-admin-page">
							<div class="legacy-admin-hero">
								<h3 class="legacy-admin-title">Shortlisted Events</h3>
								<p class="legacy-admin-subtitle">Review current shortlisted-event flows from the same branded admin workspace.</p>
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
				<?php 
					include_once('sidebar.php');
				?>
				<br class="clearfix" />
			</div>
		</div>

<?php 
	include_once('footer.php');
?>

