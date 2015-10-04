<?php
	$MW = true;

	global $_POST;
	if (isset($_POST["contactData"]["language"])) {
		define ("SET_USER_LANGUAGE", $_POST["contactData"]["language"]);
	}
		
	
	@chdir("../../../UG/html/scripts/");
	require_once( "../../../UG/html/scripts/addmoduser.php" );
	
	exit;

	require_once( "../../../common/html/includes/httpinit.php" );

	//
	// Authorization
	//

	$errorStr = null;
	$fatalError = false;
	$SCR_ID = "PF";

	pageUserAuthorization( $SCR_ID, $MW_APP_ID, true );

	//
	// Page variables setup
	//
	$kernelStrings = $loc_str[$language];
	$mw_locStrings = $mw_loc_str[$language];
	$invalidField = null;
	$page_ids = array();
	$page_names = array();

	switch (true) {
		case true : {
						if ( $fatalError )
							break;

						$timeZones = array();
						foreach( $GLOBALS['_DATE_TIMEZONE_DATA']  as $key=>$val )
							$timeZones[] = array(
											'ID' => $key,
											'NAME' => sprintf("%s %s", $val['shortname'], $val['longname'] ),
											'DST' => $val['hasdst']
							 );

						if ( !isset($edited) || !$edited ) {
							$userdata["U_ID"] = $currentUser;

							$res = exec_sql( $qr_selectUser, $userdata, $userdata, true );
							if ( PEAR::isError( $res ) ) {
								$errorStr = $kernelStrings[ERR_QUERYEXECUTING];
								$fatalError = true;

								break;
							}

							$userdata["timezone"] = USER_TIME_ZONE_ID;
							$userdata["tzdst"] = USER_TIME_ZONE_DST;
							$userdata[LANGUAGE] = $userGlobalSettings[LANGUAGE];
							$userdata[START_PAGE] = $userGlobalSettings[START_PAGE];
							$userdata[MAILFORMAT] = $userGlobalSettings[MAILFORMAT];
							$userdata[U_RECEIVESMESSAGES] = $userGlobalSettings[U_RECEIVESMESSAGES];

							$prevLanguage = $userdata[LANGUAGE];

							$prevName = base64_encode(getUserName($currentUser, false));
						}
						$userdata["U_ID"] = $currentUser;

						$language_names = array();
						$language_ids = array();

						foreach( $wbs_languages as $key=>$value ) {
							$language_names[] = $value["NAME"];
							$language_ids[] = $value["ID"];
						}

						$pages = sortAppScreenList( listUserScreens( $currentUser ) );

						$page_ids[] = USE_BLANK;
						$page_names[] = sprintf( "&lt;%s&gt;", $kernelStrings['amu_blank_item'] );

						if ( SHOW_TIPSANDTRICKS ) {
							$page_ids[] = USE_TIPSANDTRICKS;
							$page_names[] = sprintf( "&lt;%s&gt;", $kernelStrings['tt_pagetitle_item'] );
						}

						$page_ids[] = USE_LAST;
						$page_names[] = sprintf( "&lt;%s&gt;", $kernelStrings['amu_lastopened_item'] );


						foreach( $pages as $APP_ID=>$appScreens ) {
							$app_name = getAppName( $APP_ID, $language, true );

							for ( $i = 0; $i < count($appScreens); $i++ ) {
								$SCR_ID = $appScreens[$i];
								$page_ids[] = sprintf( "%s/%s", $APP_ID, $SCR_ID );
								$page_names[] = sprintf( "%s: %s", $app_name, getScreenName( $APP_ID, $SCR_ID, $language ) );
							}
						}

						$formatNames = array();
						$formatIDs = array();
						foreach( $mail_formats as $format_id=>$format_name ) {
							$formatIDs[] = $format_id;
							$formatNames[] = $kernelStrings[$format_name];
						}

						$allowChangeName = checkUserFunctionsRights( $currentUser, $MW_APP_ID, 'NC', $kernelStrings );
						$allowDisableEmail = checkUserFunctionsRights( $currentUser, $MW_APP_ID, 'EMAIL', $kernelStrings );
				}
	}

	//
	// Form handling
	//
	$btnIndex = getButtonIndex( array(BTN_CANCEL, BTN_SAVE), $_POST );

	switch ( $btnIndex ) {
		case 0 : {
			redirectBrowser( PAGE_SIMPLEREPORT, array( INFO_STR=>urlencode(base64_encode($mw_locStrings['pf_changecanceled_message'])), "reportType"=>2 ) );

			break;
		}
		case 1 : {
			$userdata = trimArrayData( $userdata );

			$lang = $userdata[LANGUAGE];
			$userdata[WBS_ENCODING] = $wbs_languages[$lang][WBS_ENCODING];

			$userdata = rescueElement( $userdata, U_RECEIVESMESSAGES, 0 );

			$res = modifyPersonalSettings( prepareArrayToStore($userdata), $kernelStrings, $language );

			if ( PEAR::isError( $res ) ) {
				$errorStr = $res->getMessage();

				if ( $res->getCode() == ERRCODE_INVALIDFIELD ) {
					$invalidField = $res->getUserInfo();
					$invalidField = explode( "|", $invalidField );
					$invalidField = $invalidField[0];
				}

				break;
			}

			$res = writeUserCommonSetting( $currentUser, START_PAGE, $userdata[START_PAGE], $kernelStrings );
			if ( PEAR::isError($res) ) {
				$errorStr = $kernelStrings[ERR_SAVINGUSERSETTINGS];

				break;
			}

			if ( SERVER_TZ )
			{
				$res = writeUserCommonSetting( $currentUser, 'TIME_ZONE_ID', $userdata['timezone'], $kernelStrings );
				if ( PEAR::isError($res) ) {
					$errorStr = $kernelStrings[ERR_SAVINGUSERSETTINGS];
					break;
				}

				$res = writeUserCommonSetting( $currentUser, 'TIME_ZONE_DST', intval( $userdata['tzdst']), $kernelStrings );
				if ( PEAR::isError($res) ) {
					$errorStr = $kernelStrings[ERR_SAVINGUSERSETTINGS];
					break;
				}
			}

			$infoStr = $mw_loc_str[$userdata["language"]]['pf_changeaccepted_message'];

			$params = array( INFO_STR=>urlencode(base64_encode($infoStr)) );

			$curName = getUserName($currentUser, false);

			if ( $prevLanguage != $userdata[LANGUAGE] || base64_encode($curName) != $prevName )
				$params["updateAll"] = 1;

			redirectBrowser( PAGE_SIMPLEREPORT, $params );
		}
	}


	//
	// Page implementation
	//
	$SCR_ID = "PF";
	$preproc = new php_preprocessor( $templateName, $kernelStrings, $language, $MW_APP_ID  );

	$preproc->assign( PAGE_TITLE, $mw_locStrings['pf_screen_long_name'] );
	$preproc->assign( FORM_LINK, PAGE_PREFERENCES );
	$preproc->assign( INVALID_FIELD, $invalidField );
	$preproc->assign( ERROR_STR, $errorStr );
	$preproc->assign( FATAL_ERROR, $fatalError );
	$preproc->assign( HELP_TOPIC, "preferences.htm");
	$preproc->assign( "mw_loc_str", $mw_locStrings );

	$preproc->assign( "timeZones", $timeZones );

	if ( !$fatalError )
	{
		$preproc->assign( "enableTZ",  SERVER_TZ ? true : false );
		$preproc->assign( "userdata", $userdata );

		$preproc->assign( "language_names", $language_names );
		$preproc->assign( "language_ids", $language_ids );

		$preproc->assign( "page_ids", $page_ids );
		$preproc->assign( "page_names", $page_names );

		$preproc->assign( "prevLanguage", $prevLanguage );
		$preproc->assign( "prevName", $prevName );

		$preproc->assign( "formatIDs", $formatIDs );
		$preproc->assign( "formatNames", $formatNames );

		$preproc->assign( "allowChangeName", $allowChangeName );
		$preproc->assign( "allowDisableEmail", $allowDisableEmail );

		$preproc->assign( "localtime", displayDateTime( convertTimestamp2Local( time() ) ) );
	}

	$preproc->display("preferences.htm");
?>