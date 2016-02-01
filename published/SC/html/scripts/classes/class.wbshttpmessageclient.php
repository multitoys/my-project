<?php
ClassManager::includeClass('HttpMessageClient');

class WbsHttpMessageClient extends HttpMessageClient {

	function __construct($dbkey, $server_url){

		$this->putData('dbkey', $dbkey);
		$this->putData('session.name',ini_get('session.name'));
		parent::__construct($server_url);
	}
}