<?php

    /*
        * WebAsyst main application instance class
        *	contains functions working with DBKEY and common installation settings
        */

    class Wbs
    {
        /**
         * @var Dbkey
         */
        private static $dbkeyObj;
        private static $systemObj;

        public static function isHosted()
        {
            return file_exists(WBS_ROOT_PATH."/kernel/hosting_plans.php");
        }

        /**
         * Load current DBKey
         * @return bool successfully load dbkey
         **/
        public static function loadCurrentDbKey()
        {
            $dbkey = null;
            self::$systemObj = new WbsSystem();
            if (!empty($_SESSION["wbs_dbkey"]))
                $dbkey = $_SESSION["wbs_dbkey"];
            // todo: add determine DB_KEY from subdfomain for hosted-version instead this code
            if (defined("GET_DBKEY_FROM_URL")) {
                if (WebQuery::getParam("DB_KEY"))
                    $dbkey = base64_decode(WebQuery::getParam("DB_KEY"));
                elseif (self::isHosted()) {
                    $dbkeyStrs = file(Wbs::getSystemObj()->files()->getWbsPath("dblist/dbnames"));
                    $subdomain = WebQuery::getSubdomain();
                    foreach ($dbkeyStrs as $cStr) {
                        if (substr($cStr, 0, strlen($subdomain)) == $subdomain) {
                            list ($domain, $domainDbKey) = split(" ", trim($cStr));
                            if ($domain != $subdomain)
                                continue;
                            $dbkey = $domainDbKey;
                            break;
                        }
                    }
                    if (!$dbkey)
                        $dbkey = $subdomain;
                } else {
                    if (file_exists(WBS_ROOT_PATH."/kernel/wbs.xml")) {
                        $xml = simplexml_load_file(WBS_ROOT_PATH."/kernel/wbs.xml");
                        $dbkey = ($xml->FRONTEND['dbkey']) ? (string)$xml->FRONTEND['dbkey'] : null;
                    }
                }
            }
            if (!$dbkey)
                return false;
            self::$dbkeyObj = new Dbkey(self::$systemObj, strtoupper($dbkey));

            return self::$dbkeyObj->loadDBKey();
        }

        /**
         * @return Dbkey
         */
        public static function getDbkeyObj()
        {
            return self::$dbkeyObj;
        }

        /**
         * @return WbsSystem
         */
        public static function getSystemObj()
        {
            return self::$systemObj;
        }

        /**
         * Open Db connect to current DBKey database
         **/
        public static function connectDb()
        {
            $dbconf = self::$dbkeyObj->getDbConfig();
            Wdb::connect($dbconf["HOST"], $dbconf["PORT"], $dbconf["DB_USER"], $dbconf["DB_PASSWORD"], $dbconf["DB_NAME"]);
            Wdb::setCharset("utf8");
        }

        /**
         * Load current user
         **/
        public static function loadCurrentUser()
        {
            Kernel::incPackage("users");
            if (!self::checkCurrentUserSession())
                Wbs::logout(true);
            $userId = $_SESSION["wbs_username"];
            CurrentUser::load($userId);
        }

        public static function checkCurrentUserSession()
        {
            $noExpire = false;
            if (!isset($_SESSION['timestamp']))
                return false;
            $lastStamp = $_SESSION['timestamp'];
            if (isset($_SESSION['NOEXPIRE']))
                $noExpire = $_SESSION['NOEXPIRE'];
            $dbTimeout = 0;
            if (isset($_SESSION["SESSION_EXPIRE_PERIOD"]))
                $dbTimeout = $_SESSION["SESSION_EXPIRE_PERIOD"];
            if ($dbTimeout == "SYSTEM") {
                $sessionTimeout = Wbs::getSystemObj()->getSessionTimeout();
                if ($sessionTimeout > 0)
                    $dbTimeout = $sessionTimeout;
                else {
                    $dbTimeout = 0;
                    $noExpire = true;
                }
            }
            if (!$noExpire && strlen($dbTimeout) && (time() - $lastStamp) > $dbTimeout)
                return false;
            $_SESSION['timestamp'] = time();

            return true;
        }

        public static function logout($expired = false)
        {
            session_unset();
            setcookie(session_name(), '', time() - 42000, '/');
            session_destroy();
            $redirectUrl = ($expired) ? "/common/html/scripts/expired.php" : "/login.php";
            if (isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && $_SERVER["HTTP_X_REQUESTED_WITH"] == "XMLHttpRequest") {
                print "{errorStr: 'Session is expired', errorCode: 'SESSION_TIMEOUT', redirectUrl: '".WebQuery::getPublishedUrl($redirectUrl)."'}";
                exit();
            } else {
                header("Location: ".WebQuery::getPublishedUrl($redirectUrl));
                exit();
            }
        }

        public static function getFS()
        {
            return self::$dbkeyObj->getFS();
        }

        public static function authorizeUser($appId)
        {
            if ($appId != 'AA' && Wbs::getDbkeyObj()->getDaysToSuspend() < 0) {
                Url::go('/AA/html/scripts/suspended.php');
            }
            self::loadCurrentUser();
            CurrentUser::tryAuthorizeApp($appId);
            Locale::init(CurrentUser::getLanguage());
        }

        public static function publicAuthorize()
        {
            Kernel::incPackage("users");
            CurrentUser::loadPublic();
            Locale::init(CurrentUser::getLanguage());
        }

        public static function getPublishedPath($path)
        {
            return self::getSystemObj()->files()->getPublishedPath($path);
        }

        public static function getAppPath($app, $path, $newVersion = false)
        {
            return self::getSystemObj()->files()->getAppPath($app, $path, $newVersion);
        }
    }

?>
