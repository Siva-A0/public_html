<?php 
	
	include_once('header.php');

   require_once("../libraries/functions.class.php") ;

   $fcObj	= new DataFunctions();

   $tbPlacements = TB_PLACEMENTS;

   if( isset ( $_GET['placement'] ) ){
   		
		$placementId	= $_GET['placement'];
		
   		$placementDet		= $fcObj->deletePlacement($tbPlacements,$placementId);
		
		if( $placementDet ){
		
			header('Location: placements.php');
			return false;
		}else{
		
			header('Location: placements.php');
			return false;
		}
   }
   
?>