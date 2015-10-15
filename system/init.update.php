<?php

//header('Content-Type: text/html; charset=UTF-8;');

if (!defined("WBS_ROOT_PATH")) {
    define ("WBS_ROOT_PATH", realpath(dirname(__FILE__). "/.."));
}
if (!defined("WBS_DIR")) {
    define ("WBS_DIR", WBS_ROOT_PATH . "/");
}
if (!defined("SYSTEM_PATH")) {
    define("SYSTEM_PATH", realpath(dirname(__FILE__)));
}

include_once("autoload.php");

/**
*	Kernel class for manage code including.
**/
class Kernel {
	static function isHosted () {
		return file_exists(WBS_ROOT_PATH . "/kernel/hosting_plans.php");
	}
}
$update = false;
if (Kernel::isHosted()) {
	WebQuery::initialize();
	
	// If cannot load dbkey settings
	try {
		@session_start();

		
		if (Wbs::loadCurrentDBKey()) {
			$update = true;
		}
		

	} catch (Exception $ex) {
		trigger_error($ex->getMessage (), E_USER_ERROR);
	}
}