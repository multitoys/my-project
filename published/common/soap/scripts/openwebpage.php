<?php
	$init_required = false;
	$language = "eng";
	$AA_APP_ID = "AA";

	ini_set( 'session.cookie_lifetime', 2592000 );

	$sessionID = null;
	if ( isset( $_GET['SESSION_ID'] ) )
		$sessionID = $_GET['SESSION_ID'];

	if ( !strlen($sessionID) )
		die( 'Session not found' );

	$page = null;
	if ( isset( $_GET['page'] ) )
		$page = $_GET['page'];

	if ( !strlen($page) )
		die( 'Page is not specified' );

	$session_started = ini_get( 'session.auto_start' );
	if ( $session_started )
		@session_write_close();

	@session_id( $sessionID );
	if ( !@session_start() )
		die( 'Error starting session' );

	$session_started_earlier = true;

	$userID = $_SESSION['U_ID'];
	$DB_KEY = $_SESSION['DB_KEY'];
	$password = $_SESSION['U_PASSWORD'];

//!	session_destroy();

	require_once( "../../../common/html/includes/httpinit.php" );

	$localizationPath = "../../../../published/AA/localization";
	$appStrings = loadLocalizationStrings( $localizationPath, strtolower($AA_APP_ID) );
	$locStrings = $appStrings[$language];

	$userdata = array();
	$userdata['U_ID'] = $userID;
	$userdata['DB_KEY'] = $DB_KEY;
	$userdata['U_PASSWORD'] = $password;

	$loginData = $userdata;
//	$loginData["U_PASSWORD"] = md5($loginData["U_PASSWORD"]);

	$res = host_login( $loginData, $locStrings, $_SERVER["REMOTE_ADDR"], "web", true );
	if ( PEAR::isError($res) )
		die($res->getMessage());

	setcookie ( "wbs_login_host", $userdata["DB_KEY"], time()+COOKIE_TO_LONG, "/" );

	html_login( $userdata, $locStrings );
	$_SESSION['NOEXPIRE'] = 1;
	$_SESSION['HIDENAVIGATION'] = 1;

	$URI = "redirect.php?page=$page";

	if ( ini_get('session.use_trans_sid') )
		$URI .= "&".ini_get( 'session.name' ).'='.session_id();

?>
<html>
	<meta http-equiv="refresh" content="0;url=<?php echo $URI ?>">
</html>