<?php
	$currentUrl	= $_SERVER['REQUEST_URI'];

	$curUrl	= explode('/',$currentUrl);
	
	$urlLength	= sizeof($curUrl);

	$urlLength--;

	$curUrl	= explode('?',$curUrl[$urlLength]); 
	
?>	
					
	<div id='lefNav1' <?php if( $curUrl[0] == 'department.php' || $curUrl[0] == 'view_faculty.php') { ?> class='navigation_current' <?php }else{ ?> class='navigation' <?php } ?> >
		<a href='<?php echo BASE_URL; ?>/public/pages/department/department.php'>Faculty</a>
	</div>
	
	<div id='lefNav2' <?php if( $curUrl[0] == 'syllabus.php' ) { ?> class='navigation_current' <?php }else{ ?> class='navigation' <?php } ?> >
		<a href='<?php echo BASE_URL; ?>/public/pages/Academics/syllabus.php'>Syllabus</a>
	</div>
	
	<div id='lefNav3' <?php if( $curUrl[0] == 'materials.php' ) { ?> class='navigation_current' <?php }else{ ?> class='navigation' <?php } ?> >
		<a href='<?php echo BASE_URL; ?>/public/pages/Academics/materials.php'>Materials</a>
	</div>
	
	<div id='lefNav4' <?php if( $curUrl[0] == 'previouspapers.php' ) { ?> class='navigation_current' <?php }else{ ?> class='navigation' <?php } ?> >
		<a href='<?php echo BASE_URL; ?>/public/pages/Academics/previouspapers.php'>Previous Papers</a>
	</div>
	<div id='lefNav5' <?php if( $curUrl[0] == 'achievements.php' ) { ?> class='navigation_current' <?php }else{ ?> class='navigation' <?php } ?> >
		<a href='<?php echo BASE_URL; ?>/public/pages/People/achievements.php'>Achievements</a>
	</div>
	
	<div id='lefNav6' <?php if( $curUrl[0] == 'placements.php' ) { ?> class='navigation_current' <?php }else{ ?> class='navigation' <?php } ?> >
		<a href='<?php echo BASE_URL; ?>/public/pages/placements.php'>Placements</a>
	</div>

	<div id='lefNav7' <?php if( $curUrl[0] == 'alumni.php' ) { ?> class='navigation_current' <?php }else{ ?> class='navigation' <?php } ?> >
		<a href='<?php echo BASE_URL; ?>/public/pages/People/alumni.php'>Alumni</a>
	</div>
