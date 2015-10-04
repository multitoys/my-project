<?php

	require_once( "../../../common/html/includes/httpinit.php" );

	$page = null;
	if ( isset( $_GET['page'] ) )
		$page = $_GET['page'];

	if ( !strlen($page) )
		die( 'Page is not specified' );
//	$page = base64_decode( $page );

	$pageData = explode( "/", $page );
	$APP_ID = strtoupper( $pageData[0] );
	$SCR_ID = strtoupper( $pageData[1] );

	$public = false;
	if ( $APP_ID == "MW" )
		$public = true;

	pageUserAuthorization( $SCR_ID, $APP_ID, $public );

	$userScreens = listUserScreens( $currentUser );

	if ( !array_key_exists( $APP_ID, $userScreens ) )
		die( "Required page is not found" );

	$app_screens = $userScreens[$APP_ID];
	if ( !in_array( $SCR_ID, $app_screens ) )
		die( "Required page is not found" );

	$URI = sprintf( "../../../%s/html/scripts/%s", $APP_ID, getScreenPage( $APP_ID, $SCR_ID ) );

	if ( ini_get('session.use_trans_sid') )
		$URI .= "?".ini_get( 'session.name' ).'='.session_id();
?>
<html>
	<meta http-equiv="refresh" content="0;url=<?php echo $URI ?>">
</html>