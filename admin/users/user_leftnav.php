<?php
	$currentUrl	= $_SERVER['REQUEST_URI'];

	$curUrl	= explode('/',$currentUrl);
	
	$urlLength	= sizeof($curUrl);

	$urlLength--;

	$curUrl	= explode('?',$curUrl[$urlLength]);
	
?>	
					
	<div id='lefNav1' <?php if( $curUrl[0] == 'users.php' ) { ?> class='navigation_current' <?php }else{ ?> class='navigation' <?php } ?> >
		<a href='users.php'>New Users</a>
	</div>
	<div id='lefNav2' <?php if( $curUrl[0] == 'view_users.php' ) { ?> class='navigation_current' <?php }else{ ?> class='navigation' <?php } ?> >
		<a href='view_users.php'>View Users</a>
	</div>
	<div id='lefNav3' <?php if( $curUrl[0] == 'changeuser.php' || $curUrl[0] == 'userDetails.php' ) { ?> class='navigation_current' <?php }else{ ?> class='navigation' <?php } ?> >
		<a href='changeuser.php'>Change User Password</a>
	</div>
