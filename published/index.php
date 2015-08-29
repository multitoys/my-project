<?php

	include_once("../system/init.php");
	
	Wbs::loadCurrentUser ();
	
	Locale::init(CurrentUser::getLanguage());
	Locale::loadFile(WBS_PUBLISHED_DIR . "common/templates/localization/", "index");
	
	// Load screens
	$screens = CurrentUser::getInstance()->getAvailableScreens();
	$mwScreen = false; $aaScreen = false;
	foreach ($screens as $appId => $screen) {
		if ($appId == "MW") $mwScreen = $screen;
		if ($appId == "AA") $aaScreen = $screen;
	}
	
	// Check logo exists
	$logoFilename = Wbs::getDbkeyObj()->files()->getAppAttachmentPath("AA", "logo.gif");
	$logoExists = file_exists($logoFilename);
	$logoTime = ($logoExists) ? filemtime($logoFilename) : null;
	
	
	// Get company name or account name
	$accountName = (Kernel::isHosted()) ? WebQuery::getSubdomain() : "WebAsyst"; // temporary, will be fixed
		
	// Load viewsettings
	$dbkeyObj = Wbs::getDbkeyObj();
	$viewsettings["showLogo"] = ($dbkeyObj->getAdvancedParam("show_company_top") == "yes") && $logoExists;
	$viewsettings["showCompanyName"] = ($dbkeyObj->getAdvancedParam("show_company_name_top") != "no");
	$viewsettings["theme"] = (string)($dbkeyObj->getAdvancedParam("theme"));
	if (!$viewsettings["theme"])
		$viewsettings["theme"] = ($viewsettings["showLogo"]) ? "1albino" : "darkblue";
	
	// GetCompany Name
	$companyName = (string)($dbkeyObj->getAdvancedParam("company_name"));
	if (!$companyName)
		$companyName = $accountName;
	
	$currentPage = CurrentUser::getInstance()->getFirstPage();
	if (WebQuery::getParam("app")) {
		$currentPage = array ("app" => WebQuery::getParam("app"));
	}
	if (WebQuery::getParam("url"))
	{
		$currentPage = array("app" => "", "url" => WebQuery::getParam("url"));
		if (isset($_POST['LMI_PAYMENT_NO']))
		    $currentPage["url"].="?LMI_PAYMENT_NO=".$_POST['LMI_PAYMENT_NO'].
					"&LMI_SYS_TRANS_NO=".$_POST['LMI_SYS_TRANS_NO'].
					"&LMI_SYS_INVS_NO=".$_POST['LMI_SYS_INVS_NO'].
					"&LMI_SYS_TRANS_DATE=".$_POST['LMI_SYS_TRANS_DATE'].
					"&wm_mtc_id=".$_POST['wm_mtc_id'].
					"&orderID=".$_POST['orderID'];
		elseif (isset($_POST['INTERNAL_PAYMENT'])) {
	    $currentPage["url"].="?orderID=".$_POST['orderID'];
		}
	}
	
	// Read account params
	$accountIsUnconfirmed = sizeof($dbkeyObj->getXmlParam("//LOGINHASH[@UNCONFIRMED=1]")) > 0;
	$needBillingAlert = $dbkeyObj->needBillingAlert();
	
	$preproc = new Preproc();
	$preproc->assign ("screens", $screens);
	$preproc->assign ("currentPage", $currentPage);
	$preproc->assign ("logoExists", $logoExists);
	$preproc->assign ("logoTime", $logoTime);
	$preproc->assign ("viewsettings", $viewsettings);
	$preproc->assign ("companyName", $companyName);
	$preproc->assign ("accountName", $accountName);
	$preproc->assign ("controlPanelScreen", $aaScreen);
	$preproc->assign ("myAccountScreen", $mwScreen);
	$preproc->assign ("currentUser", CurrentUser::getInstance());
	$preproc->assign ("accountIsUnconfirmed", $accountIsUnconfirmed);
	$preproc->assign ("needBillingAlert", $needBillingAlert);
	
	$preproc->display ("index.html");
?>