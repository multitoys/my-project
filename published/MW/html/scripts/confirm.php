<?php

	require_once( "../../../common/html/includes/httpinit.php" );

	//
	// Authorization
	//
	
	$errorStr = null;
	$fatalError = false;

	pageUserAuthorization( null, $MW_APP_ID, true );
	// 
	// Page variables setup
	//

	$kernelStrings = $loc_str[$language];
	$mwStrings = $mw_loc_str[$language];

	//
	// Page implementation
	//
	$preproc = new php_preprocessor( $templateName, $kernelStrings, $language, $MW_APP_ID );

	$preproc->assign( PAGE_TITLE, $mwStrings['cnf_page_title'] );
	$preproc->assign( "messageStr", $mwStrings['cnf_account_confirmed_text'] );
	
	$preproc->display( "confirm.htm" );
?>