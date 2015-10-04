<?php

header('Content-Type: text/html; charset=UTF-8;');

include_once("kernel.php");

define("WBS_SMARTY_DIR", WBS_ROOT_PATH . "/kernel/includes/smarty/");

include_once("autoload.php");

Registry::set('time', microtime(true));

if (file_exists(dirname(__FILE__)."/app_mode.php")) {
   include("app_mode.php");
}

include_once("functions/__functions.php");

if (Wbs::isHosted()) {
	include_once("const.php");	
}

WebQuery::initialize();

// If cannot load dbkey settings
try {
	session_start();
	
	if (!Wbs::loadCurrentDBKey()) {
		Wbs::logout();
	}
	
	Wbs::connectDb();			
} catch (Exception $ex) {
	trigger_error($ex->getMessage (), E_USER_ERROR);
}

if (Wbs::isHosted()) {
	$updater = new WbsUpdater("SYSTEM");
	$updater->check();
}	

?>