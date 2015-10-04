<?
	if (@$_GET["DB_KEY"] && @$mode != "preview") {
		$get_key_from_url = true;
	}
	
	if (!defined("BASE_SRC")) {
		define("BASE_SRC", "../../../");
		define("WG_SRC", "../../");
	}
	
	require_once("../../../common/html/includes/httpinit.php");
	require_once( WBS_DIR."/published/WG/wg.php" );
	require_once( WBS_DIR."/published/WG/wg_widgets.php" );
	$DB_KEY = base64_decode($DB_KEY);
	
	$language = "eng";
	$kernelStrings = &$loc_str[$language];
	if (!isset($code))
		$code = "";
	
	if (!$code && !$mode == "preview")
		die ("Error: empty code");
	
	// Load widget
	require_once ("swidget_load.php");	
	
	if (!$widgetData)
		die("Error: widget not found");
	
	if (!$typeObj)
		die("Error: widget not found");
	
	if ($widgetData["WG_LANG"]) {
		$language = $widgetData["WG_LANG"];
		$kernelStrings = &$loc_str[$language];
		$wgStrings = &$wg_loc_str[$language];
	}
	
	$mode = @$_GET["mode"];
	if ($mode == "edit") {
		require_once( "_WidgetsPageBuilder.php" );
		$dataManager = new WidgetManager ($kernelStrings, $wgStrings);
		$pageBuilder = new WidgetsPageBuilder($WG_APP_ID, $dataManager);		
		$pageBuilder->pageState->params["WG_ID"] = base64_encode($widgetData["WG_ID"]);
					
		$pageBuilder->prepareWidgetPage ();
		$pageBuilder->prepareEditPage ();
		$pageBuilder->displayPage ("edit.htm");
	} else {
		$typeObj->display ($widgetData);
	}
?>