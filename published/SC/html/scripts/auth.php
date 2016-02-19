<?php
	require_once( "../../../common/html/includes/httpinit.php" );
	require_once("../../sc.php" );
	//
	// Authorization
	//

	$fatalError = false;
	$popupStr = null;
	$errorStr = null;
	$SCR_ID = "FM";

	pageUserAuthorization( $SCR_ID, $SC_APP_ID, false );

	if(isset($_GET['redirect']))redirect(base64_decode($_GET['redirect']));
?>