<?php
	
	class WbsUser implements IUser {
		private $settings = array();
		private $U_ID;
		private $language;
		private $settingsXml;
		private $data;
		private $timeZone;
		/**
		private $screens;
		 * User's Contacts
		 *
		 * @var WbsContact
		 */
		private $contact;
		public $screens = array();
		
		public function __construct($U_ID) {
			$this->U_ID = $U_ID;
		}
		
		/**
		 * Returns class WbsContcat for user
		 *
		 * @return WbsContact
		 */
		public function getContact()
		{
			if (!$this->contact) { 
				$data = $this->getData();
				$this->contact = new WbsContact($data['C_ID'], $data);
			}
			return $this->contact;
		}
		
		public function getData() {
			if (!$this->data) {
				if ($this->isAdmin()) {
					$this->data = array ("C_LASTNAME" => "ADMINISTRATOR");
				} else {
					$sql = new CSelectSqlQuery ("WBS_USER", "U");
					$sql->innerJoin("CONTACT", "C", "U.C_ID=C.C_ID");
					$sql->addConditions("U.U_ID", $this->U_ID);
					$this->data = Wdb::getRow($sql);
					if (!$this->data)
						throw new RuntimeException ("Not found user: " . $this->U_ID);
				}
			}
			return $this->data;
		}
		
		public function getAppSettings($appId)
		{
			$sql = new CSelectSqlQuery("USER_SETTINGS", "US");
			$sql->addConditions("U_ID", $this->getId());
			$sql->addConditions("APP_ID", $appId);
			$sql->setSelectFields(array("NAME", "VALUE"));
			return Wdb::getData($sql);
		}
		
		public function getSetting($name, $defaultValue = null)
		{
			return $this->getAppSetting("", $name, $defaultValue);
		}
		
		
		public function getAppSetting($appId, $name, $defaultValue = null)
		{
			
			if (isset($this->settings[$appId ? $appId : "ALL"][$name])) {
				$value = $this->settings[$appId ? $appId : "ALL"][$name];
			} else {
				$sql = new CSelectSqlQuery("USER_SETTINGS", "US");
				$sql->addConditions("U_ID", $this->getId());
				$sql->addConditions("APP_ID", $appId);
				$sql->addConditions("NAME", $name);
				$sql->setSelectFields("VALUE");
				$value =  Wdb::getFirstField($sql);
				$this->settings[$appId ? $appId : "ALL"][$name] = $value;
			}
			if (!strlen($value)) {
				return $defaultValue;
			}
			return $value;
		}
		
		
		public function setSetting($appId, $name, $value)
		{
			$sql = new CReplaceSqlQuery("USER_SETTINGS", "US");
			$sql->addFields(array("U_ID" => $this->getId(), "APP_ID" => $appId, "NAME" => $name, "VALUE" => $value), array("U_ID", "APP_ID", "NAME", "VALUE"));
			Wdb::runQuery($sql);
		}
		

		/**
		public function getSettingsXml() {
			if (!$this->settingsXml) {
				$data = $this->getData();
				$this->settingsXml = simplexml_load_string ($data["U_SETTINGS"]);
			}
			return $this->settingsXml;
		}
		
		public function getAppSettingsXml($appId) {
			$settingsXml = $this->getSettingsXml();
			print_r($settingsXml); exit;
			$appSettingsXml = (empty($settingsXml->$appId)) ?
				$settingsXml->addChild ($appId) :
				$settingsXml->$appId;
			return $appSettingsXml;
		}
		
		public function saveSettingsXml() {
			$xmlstr = (string)$this->settingsXml->asXml();
			$sql = new CUpdateSqlQuery("WBS_USER");
			$sql->addConditions("U_ID", $this->U_ID);
			$sql->addFields(array("U_SETTINGS" => $xmlstr), array("U_SETTINGS"));
			Wdb::runQuery($sql);
		}
		
		public function getSetting($param) {
			$settingsXml = $this->getSettingsXML();
			
			return (string)$settingsXml[$param];
		}
		
		public function setSetting($param, $value) {
			if ($this->getSetting($param) == $value)
				return true;
			
			$settingsXml = $this->getSettingsXml();
			$settingsXml[$param] = $value;
			
			$this->saveSettingsXml();			
		}
		
		public function getAppSetting($appId, $param, $defaultValue = null) {
			$settingsXml = $this->getSettingsXml();
			$xmlstr = (array)$this->settingsXml;
			print_R(array_keys($xmlstr));
			if (!isset($settingsXml->$appId))
				return $defaultValue;
			$appXml = $settingsXml->$appId;
			if (!isset($appXml[$param]))
				return $defaultValue;
			return (string)$appXml[$param];
		}
		
		public function setAppSetting($appId, $param, $value) {
			$appXml = $this->getAppSettingsXml($appId);
			$appXml[$param] = $value;
			$this->saveSettingsXml();						
		}
		*/		

		public function setPassword($password) {
			$new_password = md5($password);
			$sql = new CUpdateSqlQuery("WBS_USER");
			$sql->addConditions("U_ID", $this->U_ID);
			$sql->addFields(array('U_PASSWORD' => $new_password), array("U_PASSWORD"));
			Wdb::runQuery($sql);
		}
		
		public function getId () {
			return $this->U_ID;
		}
		
		/**
		 * Returns user's language
		 *
		 * @return string
		 */
		public function getLanguage() 
		{
			if ($this->language)
				return $this->language;
			
			$language = $this->getSetting("language");
			if (empty($language))
				$language = DEF_LANGUAGE;
			return $language;
		}
		
		public function setTimeZone($timeZone) {
			$this->timeZone = $timeZone;
		}
		
		/**
		 * Returns user's timezone
		 *
		 * @return array
		 */
		public function getTimeZone() {
			return $this->timeZone;
		}
		
	
		function getUserRightsSql() {
			$userSql = new CSelectSqlQuery("WBS_USER", "U");
			$userSql->innerJoin("U_ACCESSRIGHTS", "UA", "UA.AR_ID=U.U_ID");
			$userSql->setSelectFields("UA.AR_PATH, UA.AR_OBJECT_ID");
			$userSql->addConditions("U.U_ID", $this->getId());
			$userSql->addConditions("UA.AR_VALUE>0");
			return $userSql;
		}
		
		function getGroupRightsSql() {
			$groupSql = new CSelectSqlQuery("WBS_USER", "U");
			$groupSql->innerJoin("UGROUP_USER", "UGU", "U.U_ID=UGU.U_ID");
			$groupSql->innerJoin("UG_ACCESSRIGHTS", "UGA", "UGA.AR_ID=UGU.UG_ID");
			$groupSql->setSelectFields("UGA.AR_PATH, UGA.AR_OBJECT_ID");
			$groupSql->addConditions("UGA.AR_VALUE>0");
			$groupSql->addConditions("U.U_ID", $this->getId());
			return $groupSql;
		}
		
		/*function getRights($path, $objectId) {
			$userSql = $this->getUserRightsSql();
			$userSql->addConditions("AR_PATH", $path);
			$userSql->addConditions("AR_OBJECT_ID", $object_id);
			
			$groupSql = $this->getGroupRightsSql();
			$groupSql->addConditions("AR_PATH", $path);
			$groupSql->addConditions("AR_OBJECT_ID", $object_id);									
		}*/
		
		function isAdmin() {
			return strtoupper($this->U_ID) == "ADMINISTRATOR";
		}
		
		public function isPublic() {
			return false;
		}
		
		public function getRightValue($path, $objectId) {
			// todo: rewrite with rights manager
			$value = 0;
			
			$userSql = $this->getUserRightsSql();
			$userSql->addConditions ("UA.AR_PATH='$path'"); 
			$userSql->addConditions ("UA.AR_OBJECT_ID='$objectId'");
			$userSql->setSelectFields("UA.AR_VALUE");
			
			$userRow = Wdb::getRow($userSql);
			$userVal = $userRow["AR_VALUE"];
			
			$groupSql = $this->getGroupRightsSql();
			$groupSql->setSelectFields("UGA.AR_VALUE");
			$groupSql->addConditions ("UGA.AR_PATH='$path'"); 
			$groupSql->addConditions ("UGA.AR_OBJECT_ID='$objectId'");
			$groupData = Wdb::getData($groupSql);
			
			$maxVal = $userVal;
			foreach ($groupData as $cRow) 
				$maxVal = max($maxVal, $cRow["AR_VALUE"]);
			
			return $maxVal;
		}
		
		
		function getAvailableScreens($language = false) {
			if ($this->screens)
				return $this->screens;
						
			if ($this->isAdmin()) {
				$language = $language ? $language : $this->getLanguage();
				$screens = array();
				$screens["AA"] = new MainAppScreen("AA", "CP", $language);
				$screens["UG"] = new MainAppScreen("UG", "UNG", $language);
				return $screens;				
			}
			$userSql = $this->getUserRightsSql();
			$userSql->addConditions ("UA.AR_PATH LIKE '/ROOT/%/SCREENS'"); 
			$userScreens = Wdb::getData($userSql);
			
			$groupSql = $this->getGroupRightsSql();
			$groupSql->addConditions ("UGA.AR_PATH LIKE '/ROOT/%/SCREENS'"); 
			$groupScreens = Wdb::getData($groupSql);
			
			$data = array_merge($userScreens, $groupScreens);
			
			// Get the all dbkey applications
			$dbkeyApps = Wbs::getDbkeyObj()->getApplicationsList();
			
			// Intersect dbkey applications and getted from user rights apps
			$screens = array ();
			foreach ($data as $cRow) {
				$appId = preg_replace("#/ROOT/(.*)/SCREENS#", "$1", $cRow["AR_PATH"]);
				$screenId = $cRow["AR_OBJECT_ID"];
				
				if (in_array($appId, $dbkeyApps) && empty($screens[$appId])) {
					$screens[$appId] = new MainAppScreen($appId, $screenId, $this->getLanguage());
					if ($appId == "DD") {
						$screens[$appId]->Url = "../../2.0/backend.php";
					}
					
					/*
					if ($appId == "PD") {
						$screen2 = new MainAppScreen($appId, $screenId, $this->getLanguage());
						$screen2->Name .= " 2.0";
						$screen2->Url = "../../2.0/backend.php";
						$screens[$appId .  "2"] = $screen2;
					}
					if ($appId == "UG") {
						$screen2 = new MainAppScreen($appId, $screenId, $this->getLanguage());
						$screen2->Name .= " 2.0";
						$screen2->Url = "../../../UG2/index.php";
						$screens[$appId .  "2"] = $screen2;
					}
					*/					

					
				}
			}
			$this->screens = $screens;
			
			uasort($screens, "wbsUserSortScreens");
			
			return $screens;
		}
		
		function hasAccessToApp($app) {
			$screens = $this->getAvailableScreens();
			foreach ($screens as $cApp => $cScreen) {
				if ($cApp == $app)
					return true;
			}
			return false;
		}
		
		function getDisplayName($short = true, $forceEmail = false, $addLineBreaks = false) {
			$data = $this->getData();
			
			$name = Users::getUserDisplayName ($data, $short, $forceEmail, $addLineBreaks);
			
			return $name;
		}
		
		public function getFirstPage() {
			$page = $this->getSetting(START_PAGE);
			if ($page == USE_LAST)
				$page = trim($this->getSetting(PAGE_LAST));
			
			if ( !strlen($page) || $page == USE_BLANK || $page == USE_TIPSANDTRICKS )
				return array("app" => "AA", "url" => PAGE_BLANK);

			$pageData = explode( "/", $page );
			$APP_ID = strtoupper( $pageData[0] );
			
			return array("app" => $APP_ID);
		}
		
		
		/**
		 * Returns user's groups
		 *
		 * @return array
		 */
		public function getGroups()
		{
			$sql = new CSelectSqlQuery ("UGROUP_USER", "U");
			$sql->innerJoin("UGROUP", "G", "U.UG_ID=G.UG_ID");
			$sql->setSelectFields("G.UG_ID, G.UG_NAME");
			$sql->addConditions("U.U_ID", $this->U_ID);
			return Wdb::getData($sql);
		}
		
		
		public function updateLastVisit($appId) 
		{
			$last_time = $this->getLastVisit($appId);
			if ($appId && time() - $_SESSION['APP_VISITS'][$appId]['CURRENT'] > LAST_VISIT_OFFSET) {
				if (time() - $_SESSION['APP_VISITS'][$appId]['CURRENT'] > LAST_VISIT_BIGOFFSET) {
					$_SESSION['APP_VISITS'][$appId]['LAST'] = Users::getLastVisit($this->getId(), $appId);					
				}
				$_SESSION['APP_VISITS'][$appId]['CURRENT'] = time();
				$this->setSetting($appId, "LAST_TIME", time()); 
			}
			$screens = $this->getAvailableScreens();
			if ($screen = $screens[$appId]) {
				$this->setSetting("", "LAST_PAGE", $screen->AppId."/".$screen->ScreenId);	
			}
			 			
		}
		
		public function getLastVisit($appId = false) 
		{
			if (isset($_SESSION['APP_VISITS'][$appId]['LAST'])) {
				return $_SESSION['APP_VISITS'][$appId]['LAST'];
			} else {
				$last_time = Users::getLastVisit($this->getId(), $appId); 
				if ($appId) {
					$_SESSION['APP_VISITS'][$appId]['CURRENT'] = time();				
					$_SESSION['APP_VISITS'][$appId]['LAST'] = $last_time;

					$this->setSetting($appId, "LAST_TIME", time());
				}
				return $last_time;
			}
		}
		
		
	}
	
	function wbsUserSortScreens($a, $b) {
		return $a->Sorting > $b->Sorting;
	}
	
?>
