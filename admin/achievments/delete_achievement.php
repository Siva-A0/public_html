<?php 
	
	include_once('header.php');

   require_once("../libraries/functions.class.php") ;

   $fcObj	= new DataFunctions();

   $tbAchievement	= TB_ACHIEVEMENTS;

   if( isset ( $_GET['achievement'] ) ){
   		
		$achieveId		= $_GET['achievement'];
		
   		$achieveDet		= $fcObj->deleteAchievement($tbAchievement,$achieveId);
		
		if( $achieveDet ){
		
			header('Location: achievements.php');
			return false;
		}else{
		
			header('Location: achievements.php');
			return false;
		}
   }
   
?>