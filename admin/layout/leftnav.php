<?php
    require_once(__DIR__ . '/../../config.php');
	$currentUrl	= $_SERVER['REQUEST_URI'];

	$curUrl	= explode('/',$currentUrl);
	
	$urlLength	= sizeof($curUrl);

	$urlLength--;

	$curUrl	= explode('?',$curUrl[$urlLength]);
	
?>	
					
	<div id='lefNav3' <?php if( $curUrl[0] == 'slcandidates.php' || $curUrl[0] == 'eventslcandidates.php' || $curUrl[0] == 'eventregcand.php' ) { ?> class='navigation_current' <?php }else{ ?> class='navigation' <?php } ?> >
		<a href='<?php echo BASE_URL; ?>/admin/events/eventregcand.php'>Registered Candidates</a>
	</div>
