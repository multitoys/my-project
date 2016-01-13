<?php
/**
 * define types of interfaces
 */
define('INTCALLER', 100);
define('INTDIVAVAILABLE', 101);
define('INTHIDDEN', 102);
define('INTCOMPONENT', 104);
define('SETTING_NUMBER', 1001);
define('SETTING_CUSTOM', 1002);
define('INIT_GLOBAL', 1001);
define('INIT_LOCAL', 1002);
/**
 * @package Modules
 */
class Module{

	/* @var $dbHandler DataBase */

	public $Interfaces = array();
	public $Settings = array();
	public $Version = 1.00;
	public $ConfigID = 0;
	public $ID = 0;
	public $SingleInstallation = false;
	public $GenerateConstants = false;
	public $ConfigKey = '';
	public $ConfigTitle = '';
	public $ConfigDescr = '';
	public $InitType = '';
	public $dbHandler;
	public $ModuleDir='';

	public $__instarface_stack = array();

	public function __clearInterfaceStack(){

		$this->__instarface_stack = array();
	}

	public function __pushToStack($key, $data){

		$this->__instarface_stack[$key] = $data;
	}

	public function __getFromStack($key){

		return $this->__instarface_stack[$key];
	}

	public function __construct( $_ConfigID = 0 ){
		$this_class_name = get_class($this);
		$this->dbHandler = &Core::getdbHandler();

		$this->ConfigID = $_ConfigID;

		$sql = '
			SELECT ModuleID, ConfigKey, ConfigTitle, ConfigDescr, ConfigInit FROM ?#TBL_MODULE_CONFIGS
			WHERE ModuleConfigID=?
		';
		$Result = $this->dbHandler->ph_query($sql, $this->ConfigID);
		if(!$Result->getNumRows()){
				
			$sql = '
				SELECT ModuleID FROM ?#TBL_MODULES WHERE 
				ModuleVersion=? AND ModuleClassName=?
			';
				
			$Result = $this->dbHandler->ph_query($sql, $this->Version, $this_class_name);
			if($Result->getNumRows()){
				list($this->ID) = $Result->fetchRow();
			}elseif($this_class_name == 'sc_Abstract'){

				$this_class_name = 'Abstract';
			}
		}else {
				
			list($this->ID, $this->ConfigKey, $this->ConfigTitle, $this->ConfigDescr, $this->InitType) = $Result->fetchRow();
		}
		$sql = '
			SELECT ModuleClassFile FROM ?#TBL_MODULES WHERE LOWER(ModuleClassName)=?
		';
		$Result = $this->dbHandler->ph_query($sql, strtolower($this_class_name));
		if(!$Result->getNumRows() && $this_class_name == 'sc_Abstract'){
				
			$this_class_name = 'Abstract';
			$Result = $this->dbHandler->ph_query($sql, strtolower($this_class_name));
		}
		list($this->ModuleDir) = $Result->fetchRow();
		$this->ModuleDir = dirname($this->ModuleDir);

		$this->initInterfaces();

		foreach ($this->Interfaces as $_Key=>$_Val){
				
			$this->Interfaces[$_Key]['key'] = $_Key;
			if(!isset($this->Interfaces[$_Key]['type']))$this->Interfaces[$_Key]['type'] = INTDIVAVAILABLE;
		}
		$this->initSettings();
	}

	/*
	 * abstract methods
	 */

	public function initInterfaces()
	{
		;
	}

	public function __registerInterface($key, $name, $type = INTCALLER, $method = ''){

		$this->Interfaces[$key] = array(
		'name' => $name,
		'type' => $type,
		'method' => $method,
		);
	}

	public function __prepend_interface($interface_key, &$params){

	}

	public function initSettings(){

		$sql = '
			SELECT SettingName, SettingValue FROM ?#TBL_CONFIG_SETTINGS
			WHERE ModuleConfigID=?
		';
		$Result = $this->dbHandler->ph_query($sql, $this->getConfigID());
		while ($_Row = $Result->fetchAssoc()){
				
			$this->Settings[$_Row['SettingName']]['value'] = $_Row['SettingValue'];
		}
	}

	public function callFromInstallConfig(){;}

	public function callFromUninstallConfig(){;}
	/*
	 * general methods
	 */

	public function installConfig( $FilePath, $_Key,$_Title, $_Descr, $_InitType ){

		if($this->InitType)$_InitType = $this->InitType;
		$ModuleID = 0;
		/* @var $Result DBResource */

		if(!$this->ID){
				
			$sql = '
				INSERT INTO ?#TBL_MODULES
				(ModuleVersion, ModuleClassName, ModuleClassFile)
				VALUES(?,?,?)
			';
				
			$this->dbHandler->ph_query($sql, $this->Version, get_class($this), $FilePath);
			$this->ID = $this->dbHandler->getInsertedID();
		}

		$sql = '
			INSERT INTO ?#TBL_MODULE_CONFIGS
			(ModuleID, ConfigInit, ConfigKey, ConfigTitle, ConfigDescr)
			VALUES(?,?,?,?,?)
		';
		$this->dbHandler->ph_query($sql, $this->ID, $_InitType, $_Key, $_Title, $_Descr);

		$this->ConfigID = $this->dbHandler->getInsertedID();

		foreach ($this->Settings as $_Setting){
				
			$sql = '
				INSERT INTO ?#TBL_CONFIG_SETTINGS
				(ModuleConfigID, SettingName, SettingValue, SettingType)
				VALUES(?,?,?,?)
			';
			$this->dbHandler->ph_query($sql, $this->ConfigID, $_Setting['name'], $_Setting['value'], $_Setting['type']);
		}

		$this->callFromInstallConfig();
	}

	public function uninstallConfig(){

		$this->callFromUninstallConfig();

		$sql = 'DELETE FROM ?#TBL_MODULE_CONFIGS WHERE ModuleConfigID=?';
		$this->dbHandler->ph_query($sql, $this->ConfigID);

		$sql = 'DELETE FROM ?#TBL_CONFIG_SETTINGS WHERE ModuleConfigID=?';
		$this->dbHandler->ph_query($sql, $this->ConfigID);

		DivisionModule::disconnectInterfaces(array($this->getConfigID()=>array_keys($this->getInterfacesParams())));
	}

	public function getInstalledConfigsInfo(){

		/* @var $Result DBResource */
		/* @var $dbHandler DataBase */
		$ConfigsInfo = array();

		$sql = 'SELECT * FROM ?#TBL_MODULE_CONFIGS WHERE ModuleID=?';
		$Result = $this->dbHandler->ph_query($sql, $this->ID);
		while ($_Row = $Result->fetchAssoc()) {
			$ConfigsInfo[] = $_Row;
		}
		return $ConfigsInfo;
	}

	/**
	 * Enter description here...
	 *
	 * @param string $interface_key
	 * @return mixed
	 */
	public function getInterface(){
		global $ConnectedModules;
		/**
		 * @features My
		 */
		$Register = &Register::getInstance();
		$GetVars = &$Register->get(VAR_GET);
		$debug_mode = isset($GetVars['debug_interfaces']);
		/**
		 * @features
		 */
		$Args = func_get_args();
		$_InterfaceName = array_shift($Args);
		$Results = '';

		$this->__prepend_interface($_InterfaceName, $Args);
		if($debug_mode){
			
			$memoryUsed = function_exists('memory_get_usage')?memory_get_usage():0;
			$timeUsed = microtime(true);
		}

		if(isset($this->Interfaces[$_InterfaceName])){
				
			$EvalParams = $Args;
			$InterfaceParams = $this->getInterfaceParams($_InterfaceName);
			if(isset($InterfaceParams['type'])&& ($InterfaceParams['type']&INTCALLER)){
				$IInterfaces = $this->getInterfaceInterfaces($_InterfaceName);
				if($IInterfaces&&isset($IInterfaces['main'])&&is_array($IInterfaces['main'])){
					foreach ($IInterfaces['main'] as $IInterface){
						if(isset($ConnectedModules[$IInterface['module_config_id']])){
							$tModule = &$ConnectedModules[$IInterface['module_config_id']];
						}else{
							$tModule = ModulesFabric::getModuleObj($IInterface['module_config_id']);
						}

						if(!is_object($tModule))continue;

						if(isset($IInterface['key'])){
							$EvalParams = array_merge($EvalParams,is_array($IInterface['key'])?$IInterface['key']:array($IInterface['key']));
						}
						call_user_func_array(array(&$tModule,'getInterface'),$EvalParams);
					}
				}
				//old code version
				/*$TC = count($IInterfaces);

				for ($j=0;$j<$TC;$j++){
					
				if(isset($ConnectedModules[$IInterfaces[$j]['module_config_id']]))$tModule = &$ConnectedModules[$IInterfaces[$j]['module_config_id']];
				else $tModule = ModulesFabric::getModuleObj($IInterfaces[$j]['module_config_id']);
				if(!is_object($tModule))continue;
				//4.10 PHP			call_user_method_array('getInterface', $tModule, $EvalParams);
				if(isset($IInterfaces[$j]['key'])){
				$EvalParams = array_merge($EvalParams,$IInterfaces[$j]['key']);
				}
				call_user_func_array(array(&$tModule,'getInterface'),$EvalParams);
					
				//call_user_func_array(array(&$obj,$method),array(&$arg1,$arg2,$arg3))
				//			call_user_method_array  ( string $method_name  , object $obj  [, array $paramarr  ] )

				//					eval('$tModule->getInterface(\''.$IInterfaces[$j]['key'].'\','.implode(',', $EvalParams).');');
				}*/
			}

			/**
			 * @features My
			 */
			if($debug_mode){
				print "<p><strong>Class:</strong> ".get_class($this);
			}
			/**
			 * @features
			 */

			$this->__clearInterfaceStack();
			$this->__pushToStack('info', $this->Interfaces[$_InterfaceName]);
			$this->__pushToStack('call_params', $Args);
			if(isset($this->Interfaces[$_InterfaceName]['method']) && $this->Interfaces[$_InterfaceName]['method']){

				/**
				 * @features My
				 */
				if($debug_mode){
					print "<br /><strong>Method:</strong> ".$this->Interfaces[$_InterfaceName]['method'].'</p>';
				}
				/**
				 * @features
				 */
				//PEAR::raiseError(get_class($this));
				//PEAR::raiseError('$Results = $this->'.$this->Interfaces[$_InterfaceName]['method'].'('.implode(',', $EvalParams).');');
				array_shift($EvalParams);
				//PHP 4.10		$Results = call_user_method_array($this->Interfaces[$_InterfaceName]['method'],$this,$Args);
				$Results = call_user_func_array(array(&$this,$this->Interfaces[$_InterfaceName]['method']),$Args);

				//				eval('$Results = $this->'.$this->Interfaces[$_InterfaceName]['method'].'('.implode(',', $EvalParams).');');
			}else {

				/**
				 * @features My
				 */
				$method_path = DIR_MODULES.'/'.$this->ModuleDir.'/'.'_methods/'.$_InterfaceName.'.php';

				if($debug_mode){
					print "<br /><strong>File:</strong> {$method_path}</p>";
					$path_count = count(get_included_files());
					print "<br /><strong>Files count:</strong> {$path_count}</p>";
					if(function_exists('memory_get_usage')){
						$method_memory = memory_get_usage();
						print "<br/><strong>Memory:</strong> {$method_memory} </p>";
					}
						
				}
				/**
				 * @features
				 */
				//or use include_once ?
				include($method_path);
			}
		}else {
				
			/**
			 * @features My
			 */
			if($debug_mode){
				print "<br /><strong>File:</strong> ".DIR_MODULES.'/'.$this->ModuleDir.'/'.'_methods/'.$_InterfaceName.'.php'.'</p>';
				$path_count = count(get_included_files());
				print "<br /><strong>Files count:</strong> {$path_count}</p>";
				if(function_exists('memory_get_usage')){
					$method_memory = memory_get_usage();
					print "<br/><strong>Memory:</strong> {$method_memory} </p>";
				}
			}
			/**
			 * @features
			 */
			if(file_exists(DIR_MODULES.'/'.$this->ModuleDir.'/'.'_methods/'.$_InterfaceName.'.php'))
			include(DIR_MODULES.'/'.$this->ModuleDir.'/'.'_methods/'.$_InterfaceName.'.php');
		}
		if($debug_mode){
			$currentMemoryUsed = function_exists('memory_get_usage')?memory_get_usage():0;
			$currentTimeUsed = microtime(true);
			print "<p><strong>Memory:</strong> ".sprintf('Total: %2.2fMb Function: %2.2fKb',$currentMemoryUsed/1048576,($currentMemoryUsed-$memoryUsed)/1024);
			print "<p><strong>Time:</strong> ".sprintf('Function: %2.3fus',($currentTimeUsed-$timeUsed)*1000);
			print "<br /><strong>Method executed:</strong> {$this->Interfaces[$_InterfaceName]['method']} <br>args:{$Args}/";
			print_r($Args);
			print "<br>Result:{$Results} /";print_r($Results);print "</p>";
		}
		return $Results;
	}

	public function getInterfacesParams($_Type = 0){

		if(!$_Type)return $this->Interfaces;

		$Interfaces = array();
		foreach ($this->Interfaces as $_Key=>$_Interface){
				
			if($_Interface['type']&$_Type)$Interfaces[$_Key] = $_Interface;
		}
		return $Interfaces;
	}

	public function getInterfaceParams($_int){

		if(isset($this->Interfaces[$_int]))return $this->Interfaces[$_int];
		return null;
	}

	public function getSettings(){

		return $this->Settings;
	}

	public function getSettingValue($_Key){

		if(isset($this->Settings[$_Key]))return $this->Settings[$_Key]['value'];
		else return '';
	}

	public function getConfigID(){

		return $this->ConfigID;
	}

	public function getConfigKey(){

		return $this->ConfigKey;
	}

	public function getConfigTitle(){

		return $this->ConfigTitle;
	}

	public function getConfigDescr(){

		return $this->ConfigDescr;
	}

	public function getInitType(){

		return $this->InitType;
	}

	public function saveConfigKey( $_ConfigKey){

		$this->ConfigKey = $_ConfigKey;

		$sql = '
			UPDATE ?#TBL_MODULE_CONFIGS
			SET ConfigKey=?
			WHERE ModuleConfigID=?
		';
		$this->dbHandler->ph_query($sql, $this->ConfigKey, $this->ConfigID);
	}

	public function saveConfigDescr( $_ConfigDescr){

		$this->ConfigDescr = $_ConfigDescr;

		$sql = '
			UPDATE ?#TBL_MODULE_CONFIGS
			SET ConfigDescr=?
			WHERE ModuleConfigID=?
		';
		$this->dbHandler->ph_query($sql, $this->ConfigDescr, $this->ConfigID);
	}

	public function saveInitType( $_InitType ){

		$this->InitType = $_InitType;

		$sql = '
			UPDATE ?#TBL_MODULE_CONFIGS
			SET ConfigInit=?
			WHERE ModuleConfigID=?
		';
		$this->dbHandler->ph_query($sql, $this->InitType, $this->ConfigID);
	}

	/**
	 * register another module interface for current module interface
	 *
	 * @param string $_InterfaceCaller
	 * @param integer $_InterfaceCalledModConfID - another module config id
	 * @param string $_InterfaceCalled
	 * @param integer $_Priority
	 */
	public function registerInterface2Interface($_InterfaceCaller, $_InterfaceCalledModConfID, $_InterfaceCalled, $_Priority=0){

		$sql = '
			INSERT ?#TBL_INTERFACE_INTERFACES (xInterfaceCaller,xInterfaceCalled,xPriority)
			VALUES(?,?,?)
		';
		$this->dbHandler->ph_query($sql, $this->getConfigID().'_'.$_InterfaceCaller, $_InterfaceCalledModConfID.'_'.$_InterfaceCalled, $_Priority);
	}

	public function getInterfaceInterfaces($_Interface){

		$cache_key_part = 'interfaces_interfaces_15112007::';
		$cache_key = $cache_key_part.$this->getConfigID().'_'.$_Interface;
		if(CCache::is_set($cache_key_part)){
			return CCache::is_set($cache_key)?CCache::get($cache_key):array('main'=>array());
		}
		$sql = '
			SELECT xInterfaceCaller,xInterfaceCalled FROM ?#TBL_INTERFACE_INTERFACES ORDER BY xPriority DESC
		';
		$Result = $this->dbHandler->ph_query($sql);
		$Interfaces = array();
		while ($_Row = $Result->fetchAssoc()) {
				
			$_T = explode('_', $_Row['xInterfaceCalled'], 2);
			if(!isset($_T[1]))continue;

			$Interfaces[$_Row['xInterfaceCaller']]['main'][] = array(
			'module_config_id' => $_T[0],
			'key' => $_T[1],
			);
		}

		foreach ($Interfaces as $_caller => $_interfaces){

			CCache::set($cache_key_part.$_caller, $_interfaces);
		}
		CCache::set($cache_key_part, true);

		return CCache::get($cache_key);
	}

	public function getTemplatePath($_Tpl){

		return DIR_TPLS.'/'.$_Tpl;
	}

	public function assignSubTemplate($_SubTemplate){

		/* @var $smarty Smarty */
		$smarty = &Core::getSmarty();
		$smarty->assign('sub_template',$this->getTemplatePath($_SubTemplate));
	}

	public function assign2template($_Var, $_Value){

		/* @var $smarty Smarty */
		$smarty = &Core::getSmarty();
		$smarty->assign($_Var, $_Value);
	}
}