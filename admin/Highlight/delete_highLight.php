<?php 
	require_once(__DIR__ . '/../../config.php');
    require_once(LIB_PATH . '/constants.php');
    require_once(LIB_PATH . '/functions.class.php');

if (session_id() == '') {
	session_start();
}

if (!isset($_SESSION['adminId'])) {
	header('Location: ' . BASE_URL . '/admin/index.php');
	exit;
}

   $fcObj	= new DataFunctions();

	$tbHighLights		= TB_HIGHLIGHTS;

   if( isset ( $_GET['highlight'] ) ){
   		
		$highLightId		= (int)$_GET['highlight'];
		
   		$highLightDet		= $highLightId > 0 ? $fcObj->deleteHighLight($tbHighLights,$highLightId) : false;
		
		if( $highLightDet ){
		
			header('Location: highlights.php');
			exit;
		}else{
		
			header('Location: highlights.php');
			exit;
		}
   }

   header('Location: highlights.php');
   exit;
   
?>
