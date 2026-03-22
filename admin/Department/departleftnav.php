<?php
	$currentUrl	= $_SERVER['REQUEST_URI'];

	$curUrl	= explode('/',$currentUrl);
	
	$urlLength	= sizeof($curUrl);

	$urlLength--;

	$curUrl	= explode('?',$curUrl[$urlLength]);
	
?>	
					
	<div id='lefNav1' <?php if( $curUrl[0] == 'department.php' || $curUrl[0] == 'addfaculty.php' || $curUrl[0] == 'view_faculty.php' || $curUrl[0] == 'editfaculty.php' ) { ?> class='navigation_current' <?php }else{ ?> class='navigation' <?php } ?> >
		<a href='department.php'>Faculty</a>
	</div>
	
	<div id='lefNav2' <?php if( $curUrl[0] == 'syllabus.php' || $curUrl[0] == 'add_syllabus.php' || $curUrl[0] == 'edit_syllabus.php' ) { ?> class='navigation_current' <?php }else{ ?> class='navigation' <?php } ?> >
		<a href='syllabus.php'>Syllabus</a>
	</div>
	
	<div id='lefNav3' <?php if( $curUrl[0] == 'materials.php' || $curUrl[0] == 'add_materials.php' || $curUrl[0] == 'edit_materials.php' ) { ?> class='navigation_current' <?php }else{ ?> class='navigation' <?php } ?> >
		<a href='materials.php'>Materials</a>
	</div>
	
	<div id='lefNav4' <?php if( $curUrl[0] == 'previouspapers.php' || $curUrl[0] == 'add_papers.php' || $curUrl[0] == 'edit_papers.php' ) { ?> class='navigation_current' <?php }else{ ?> class='navigation' <?php } ?> >
		<a href='previouspapers.php'>Prevoius Papers</a>
	</div>
	<!--
	<div id='lefNav5' <?php if( $curUrl[0] == 'seminors.php' ) { ?> class='navigation_current' <?php }else{ ?> class='navigation' <?php } ?> >
		<a href='seminors.php'>Seminors</a>
	</div>
	-->
	<div id='lefNav5' <?php if( $curUrl[0] == 'achievements.php' || $curUrl[0] == 'add_achievement.php' ) { ?> class='navigation_current' <?php }else{ ?> class='navigation' <?php } ?> >
		<a href='achievements.php'>Achivements</a>
	</div>
	
	<div id='lefNav6' <?php if( $curUrl[0] == 'placements.php' || $curUrl[0] == 'add_placements.php' ) { ?> class='navigation_current' <?php }else{ ?> class='navigation' <?php } ?> >
		<a href='placements.php'>Placements</a>
	</div>

	<div id='lefNav7' <?php if( $curUrl[0] == 'alumni.php' || $curUrl[0] == 'add_alumni.php' ) { ?> class='navigation_current' <?php }else{ ?> class='navigation' <?php } ?> >
		<a href='alumni.php'>Alumni</a>
	</div>
