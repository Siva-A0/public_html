<?php
	$currentUrl	= $_SERVER['REQUEST_URI'];

	$curUrl	= explode('/',$currentUrl);
	
	$urlLength	= sizeof($curUrl);

	$urlLength--;

	$curUrl	= explode('?',$curUrl[$urlLength]);
	
?>	
					
	<div id='lefNav4' <?php if( $curUrl[0] == 'eventresults.php' || $curUrl[0] == 'eventresult.php' ) { ?> class='navigation_current' <?php }else{ ?> class='navigation' <?php } ?> >
		<a href='<?php echo BASE_URL; ?>/public/pages/Events/eventresults.php'>Event Results</a>
	</div>
