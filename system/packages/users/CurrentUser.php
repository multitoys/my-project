<?php
	class CurrentUser {
		
		static private $U_ID;
		/**
		 * Instanse of current user
		 *
		 * @var WbsUser or PublicUser
		 */
		static private $instance = null;
		
		static function load($U_ID) {
			$user = new WbsUser ($U_ID);
			
			if ($timezoneId = $user->getSetting("TIME_ZONE_ID")) {
				$timezoneDst = $user->getSetting("TIME_ZONE_DST");
				$timeZone = new CTimeZone($timezoneId, $timezoneDst);
				CDateTime::setDefaultDisplayTimeZone($timeZone);
				$user->setTimeZone($timeZone);
			    WbsDateTime::init($timeZone);
			} else {
				WbsDateTime::init();
			}
			
			self::$instance = $user;
		}
		
		static function loadPublic() {
			self::$instance = new PublicUser();
		}
		
		/**
		 * @return WbsUser
		 */
		static function getInstance() {
			if (!self::$instance) {
				return false;
				throw new RuntimeException ("First you need to load CurrentUser with method load");
			}
			return self::$instance;
		}
		
		static function getId () {
			$instance = self::getInstance();
			return $instance->getId();
		}
		
		static function getLanguage() {
			$instance = self::getInstance();
			return $instance->getLanguage();
		}
		
		static function getRow() {
		}
		
		static function getName() {
			$instance = self::getInstance();
			return $instance->getDisplayName();			
		}		
		
		static function tryAuthorizeApp($appId) {
			$instanse = self::getInstance();
			if (self::getId()) {
				if (!$instanse->hasAccessToApp($appId)) {
					Url::go('/AA/html/scripts/blank.php');
				}			
				$instanse->updateLastVisit($appId);
			}
			return true;
		}
		
		/**
		 * Returns last time of visit to the application
		 *
		 * @param string $appId 
		 * @return int - unix timestamp
		 */
		static function getLastVisit($appId) {
			$instanse = self::getInstance();
			return $instanse->getLastVisit($appId);
		}
	}
?>