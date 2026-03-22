<?php 
	require_once(__DIR__ . '/../../../config.php');

    include_once(INCLUDES_PATH . '/header.php');
    require_once(LIB_PATH . '/functions.class.php');


   $fcObj			= new DataFunctions();
	
	$tbEventReg		= TB_EVENT_REG;
	
	$tbEvents		= TB_EVENTS;
	
	if(!isset($_SESSION['userId'])){
		
		echo 'Please Login To Continue...';
	}else{
		
		array_pop( $_POST );
		
		$eventReqs	= $_POST;
		
		$noOfEvents	= sizeof( $eventReqs );

		$userAlreadyReg	= array();
		$userRegistered	= array();
		$userRegistProb	= array();
		
		for($s=0;$s<$noOfEvents;$s++){
			
			$eventIds[$s]	= array_shift( $eventReqs );
			$result			= $fcObj->eventRegCheck( $tbEventReg, $eventIds[$s], $_SESSION['userId'] );
			
			if(!empty($result)){
			
				$userAlreadyReg[]	= $result[0];	
			}else{
				
				$regis		= $fcObj->eventRegister( $tbEventReg, $eventIds[$s], $_SESSION['userId'] );
				
				if($regis){
					
					$usrReg				= $fcObj->getEventDetails( $tbEvents , $eventIds[$s] );
					$userRegistered[]	= $usrReg[0];
				}else{
					$usrRegProb			= $fcObj->getEventDetails( $tbEvents , $eventIds[$s] );
					$userRegistProb[]	= $usrRegProb[0];
				}
			}
		}
		
?>
 <div class="box1">
        <div class="wrapper">
          <article class="col1">
				<div id="index_cont">
				<div id="content">
					<div class="post">
						<span class="alignCenter">
							<h4>WISE Association </h4>
						</span>
						<p>
							
						</p>
					</div>
					<div id='content_left' class='content_left'>
						<?php 
							include_once('wise_leftnav.php');
						?>						
					</div>
					<div id='content_right' class='content_right'>
						
						<?php 
							if(!empty($userAlreadyReg)){
								$alrRegCnt	= sizeof( $userAlreadyReg );
						?>
								<div class="eventDetails" >
									<div class="eventHeader">
										You Have Already Registered For:
									</div>
									<?php 
										for($i=0;$i<$alrRegCnt;$i++){
									?>
											<div class="eventD">
												<?php
													echo $userAlreadyReg[$i]['event_name'];
												?>
											</div>
									<?php
										}
									?>
								</div>	
						<?php 
							} 
						?>
						<?php 
							if(!empty($userRegistered)){
								$RegCnt	= sizeof( $userRegistered );
						?>
								<div class="eventDetails" >
									<div class="eventHeader">
										You Are Successfully Registered For:
									</div>
									<?php 
										for($i=0;$i<$RegCnt;$i++){
									?>
											<div class="eventD">
												<?php
													echo $userRegistered[$i]['event_name'];
												?>
											</div>
									<?php
										}
									?>
								</div>
						<?php 
							}
						?>	
						<?php 
							if(!empty($userRegistProb)){
								$RegProbCnt	= sizeof( $userRegistProb );
						?>
								<div class="eventDetails" >
									<div class="eventHeader">
										Registration For Following Events Not SuccessFul, Please Try Again:
									</div>
									<?php 
										for($i=0;$i<$RegProbCnt;$i++){
									?>
											<div class="eventD">
												<?php
													echo $userRegistProb[$i]['event_name'];
												?>
											</div>
									<?php
										}
									?>
								</div>	
						<?php
							}
						?>
											
					</div>
					<br class="clearfix" />
								</div>
					</article>
					<article class="col2 pad_left2">
					<?php 
						include_once('sidebar.php');
					?>
					</article>
</div>
</div>

		
<script type="text/javascript" language="javascript">
	
	$(document).ready(function() {
		
	});
</script>

<?php 
	}
	
include_once(INCLUDES_PATH . '/footer.php');
?>
