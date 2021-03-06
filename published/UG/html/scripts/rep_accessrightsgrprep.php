<?php

	require_once( "../../../common/html/includes/httpinit.php" );

	//
	// Authorization
	//

	$fatalError = false;
	$errorStr = null;
	$SCR_ID = "UNG";

	pageUserAuthorization( $SCR_ID, UG_APP_ID, false );

	// 
	// Page variables setup
	//

	$kernelStrings = $loc_str[$language];
	$invalidField = null;

	switch ( true ) {
		case true :

				$groups = implode( ",", $included_groups );
				$data = array( UR_PATH=>'/ROOT', UR_ACTION=>UR_ACTION_VIEWGROUP, UR_ID=>$groups, UR_FIELD=>"data" );

				$ret = $UR_Manager->RenderPath( $data );
	}


	//
	// Page implementation
	//

	$preproc = new php_preprocessor( $templateName, $kernelStrings, $language, UG_APP_ID );
	
	$preproc->assign( PAGE_TITLE, $kernelStrings['rp_accessrightsgroups_title'] );
	$preproc->assign( FORM_LINK, PAGE_ACCESSRIGHTS_REP_GROUPS );
	$preproc->assign( INVALID_FIELD, $invalidField );
	$preproc->assign( ERROR_STR, $errorStr );
	$preproc->assign( FATAL_ERROR, $fatalError );
	$preproc->assign( "kernelStrings", $kernelStrings );

	if ( !$fatalError ) {
		$preproc->assign( "renderData", $ret );

	}

	$preproc->display( "rep_accessrightsgrprep.htm" );
?>