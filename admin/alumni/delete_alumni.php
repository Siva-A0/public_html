<?php 
	
	include_once('header.php');

   require_once("../libraries/functions.class.php") ;

   $fcObj	= new DataFunctions();

   $tbAlumni	 = TB_ALUMNI;

   if( isset ( $_GET['alumni'] ) ){
   		
		$alumniId	= $_GET['alumni'];
		
   		$alumniDet		= $fcObj->deleteAlumni($tbAlumni,$alumniId);
		
		if( $alumniDet ){
		
			header('Location: alumni.php');
			return false;
		}else{
		
			header('Location: alumni.php');
			return false;
		}
   }
   
?>