<?php

	define ("LOOKANDFEEL_PREVIEW", true);
	require_once( "../../../common/html/includes/httpinit.php" );
	require_once( WBS_DIR."/published/AA/aa.php" );
	require_once( WBS_DIR."/published/UG/ug.php" );

	//
	// Authorization
	//

	$fatalError = false;
	$errorStr = null;
	$SCR_ID = "UNG";

	pageUserAuthorization( $SCR_ID, UG_APP_ID, true );

	// 
	// Page variables setup
	//

	$kernelStrings = $loc_str[$language];
	$ugStrings = $ug_loc_str[$language];
	
	$tabs[] = array( PT_NAME=> $ugStrings["pv_tab_title"] . " 1",
							PT_PAGE_ID=>"tab1",
							PT_FILE=>'preview_tab1.htm',
							PT_CUSTOM_ID=>"tab1"
						);
	$tabs[] = array( PT_NAME=> $ugStrings["pv_tab_title"] . " 2",
							PT_PAGE_ID=>"tab2",
							PT_FILE=>'preview_tab2.htm',
							PT_CUSTOM_ID=>"tab2"
						);
	
	if (empty($layout))
		$layout = "topmenu";
	
	$preproc = new php_preprocessor( $templateName, $kernelStrings, $language, UG_APP_ID );
	$preproc->assign( PAGE_TITLE, $kernelStrings['pcl_print_title'] );
	$preproc->assign( "tabs", $tabs );
	$preproc->assign( "kernelStrings", $kernelStrings );
	$preproc->assign( "ugStrings", $ugStrings);
	$preproc->assign ( "layout", $layout);

	$preproc->display( "preview.htm" );
?>