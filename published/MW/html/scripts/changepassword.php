<?php

	require_once( "../../../common/html/includes/httpinit.php" );

	//	
	// Authorization
	//

	$errorStr = null;	
	$fatalError = false;
	$SCR_ID = "CP";

	pageUserAuthorization( $SCR_ID, $MW_APP_ID, true );

	// Page variables setup
	//
	$kernelStrings = $loc_str[$language];
	$mw_locStrings = $mw_loc_str[$language];
	$invalidField = null;

	$btnIndex = getButtonIndex( array(BTN_CANCEL, BTN_SAVE), $_POST );

	switch ( $btnIndex ) {
		case 0 : {
			redirectBrowser( PAGE_SIMPLEREPORT, array( INFO_STR=>urlencode(base64_encode($mw_locStrings['cp_changecanceled_message'])), "reportType"=>2 ) );
			break;
		}
		case 1 : {				
			$passwordData["U_ID"] = $currentUser;
			$res = updateUserPassword( $passwordData, $kernelStrings );
			if ( PEAR::isError( $res ) ) {
				$errorStr = $res->getMessage();

				if ( $res->getCode() == ERRCODE_INVALIDFIELD )
					$invalidField = $res->getUserInfo();

				break;
			}

			redirectBrowser( PAGE_SIMPLEREPORT, array( INFO_STR=>urlencode(base64_encode($mw_locStrings['cp_changeaccepted_message'])) ) );
			break;
		}
	}
		
	// Page implementation
	//
	$preproc = new php_preprocessor( $templateName, $kernelStrings, $language, $MW_APP_ID );
	$preproc->assign( PAGE_TITLE, $mw_locStrings['cp_screen_short_name'] );
	$preproc->assign( FATAL_ERROR, $fatalError );	
	$preproc->assign( ERROR_STR, $errorStr );
	$preproc->assign( INVALID_FIELD, $invalidField );
	$preproc->assign( HELP_TOPIC, "changepassword.htm");
	$preproc->assign( 'mw_locStrings', $mw_locStrings );

	$preproc->display( "changepassword.htm" );
?>