<?php

	require_once( "../../../common/html/includes/httpinit.php" );

	$loginPageURL = $_SESSION[LOGIN_PAGE_URL];
	if ( !strlen($loginPageURL) )
		$loginPageURL = "../../../login.php";

	session_set_cookie_params (0);
	session_regenerate_id (true);
	@session_unset();
	@session_destroy();

	setcookie ( WBS_USERNAME, "", time()-3600, "/" );
	setcookie ( WBS_DBKEY, "", time()-3600, "/" );
	
	redirectBrowser( $loginPageURL, array(), null, false, true );
?>