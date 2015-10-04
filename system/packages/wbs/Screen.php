<?php
	class MainAppScreen {
		public $Url;
		public $AppId;
		public $ScreenId;
		public $Name;
		public $Sorting;
		private $isAvailable;
		private $forMenu;
		private $mainColor;
		
		public function __construct($appId, $screenId, $lang) {
			$this->AppId = $appId;
			$this->ScreenId = $screenId;
			
			$screensFilename = Wbs::getSystemObj()->files()->getAppPath($appId, "_screens.php");
			if (file_exists($screensFilename))
			{
				include($screensFilename);
				$info = $global_screens[$appId][$screenId];
				
				$this->Name = (!empty($info["NAME"][$lang])) ? $info["NAME"][$lang] : $info["NAME"][DEF_LANG];
				
				$this->Url = $info["PAGE"];
				$this->Sorting = isset($info["SORTORDER"]) ? $info["SORTORDER"] : 0;
				$this->isAvailable = true;
				$this->forMenu = empty($info["NOT_MENU"]);
				$this->mainColor = (!empty($info["MAINCOLOR"])) ? $info["MAINCOLOR"] : "orange";					
			} else {
				$this->isAvailable = false;
			}
		}
		
		public function getMainColor () {
			return $this->mainColor;
		}
		
		public function forMenu () {
			return $this->forMenu;
		}
		
		public function getUrl() {
			return WebQuery::getPublishedUrl ($this->AppId . "/html/scripts/" . $this->Url);
		}
		
		public function getEncodedUrl() {
			return base64_encode($this->getUrl());
		}
		
		public function getIconUrl() {
			$url = WebQuery::getPublishedUrl (sprintf("%s/html/img/%s40.gif", $this->AppId, $this->AppId));
			return $url;
		}
	}
?>