<?php

    if (file_exists("common/soap/scripts"))
        @chdir("common/soap/scripts");

    global $_GET;

    if (!isset($_GET["DB_KEY"])) {
        $init_required = false;
        $DB_NAME = null;
    } else {
        $init_required = true;
    }

    $authScript = true;

    require_once("../includes/soapinit.php");
    require_once("../includes/soapclient_funcs.php");

    require_once("SOAP/Value.php");
    require_once("SOAP/Fault.php");

    class SOAP_Login_Server
    {
        var $__dispatch_map = array();
        var $__typedef = array();

        function SOAP_Login_Server()
        {

            global $NAMED_ITEM;

            $this->__dispatch_map['CheckScreenRights'] = array(
                //
                // Проверяет права пользователя на экран
                //
                // 	Параметры
                //		APP_ID - идентификатор приложения (base64)
                //		SCR_ID - идентификатор экрана (base64)
                //		U_ID - идентификатор пользователя (base64)
                //		pwd - пароль пользователя (md5, base64)
                //		lang - язык сообщений
                //
                //	Возвращает
                //		сообщение об ошибке (base64) или пустую строку
                //
                'in'  => array('APP_ID' => 'string', 'SCR_ID' => 'string', 'U_ID' => 'string', 'pwd' => 'string', 'lang' => 'string'),
                'out' => array('return' => 'string')
            );

            $this->__dispatch_map['UserAuthorize'] =
                //
                // Проверяет возможность пользователя работать с системой,
                // возвращает его настройки, создает сессию
                //
                // 	Параметры
                //		U_ID - идентификатор пользователя (base64)
                //		pwd - пароль (md5, base64)
                //
                // 	Возвращает
                //		return - сообщение об ошибке (base64)
                //		language - язык пользователя
                //		Company - название компании (base64)
                //		LogoPath - путь к файлу logo.gif с логотипом или пустая строка (base64)
                //		User - полное имя пользователя (base64)
                //		ServiceParams - строка с набором параметров, передаваемых через URL в вебслужбы (HOST_ID, HOST_NAME, DB_KEY)
                //
                array(
                    'in'  => array('U_ID' => 'string', 'pwd' => 'string'),
                    'out' => array(
                        'return'        => 'string',
                        'language'      => 'string',
                        'Company'       => 'string',
                        'LogoPath'      => 'string',
                        'User'          => 'string',
                        'ServiceParams' => 'string'
                    )
                );

            $this->__dispatch_map['GetUserInfo'] =
                //
                // возвращает настройки пользователя
                //
                // 	Параметры
                //		U_ID - идентификатор пользователя (base64)
                //
                // 	Возвращает
                //		return - сообщение об ошибке (base64)
                //		language - язык пользователя
                //		Company - название компании (base64)
                //		LogoPath - путь к файлу logo.gif с логотипом или пустая строка (base64)
                //		User - полное имя пользователя (base64)
                //
                array(
                    'in'  => array('U_ID' => 'string'),
                    'out' => array(
                        'return'   => 'string',
                        'language' => 'string',
                        'Company'  => 'string',
                        'LogoPath' => 'string',
                        'User'     => 'string'
                    )
                );

            $this->__dispatch_map['GetLoginMessage'] =
                //
                // Проверяет является ли это первым входом в систему и возвращает сообещение для пользователя
                //
                // 	Параметры
                //		U_ID - идентификатор пользователя (base64)
                //		pwd - пароль (md5, base64)
                //		$lang - язык пользователя
                //
                // 	Возвращает
                //		return - сообщение при входе в систему для пользователя (base64)
                //		error - сообщение об ошибке или пустая строка (base64)
                //		FirstLogin - true если это первое подключение пользователя
                //
                array(
                    'in'  => array('U_ID' => 'string', 'pwd' => 'string', 'lang' => 'string'),
                    'out' => array(
                        'return'     => 'string',
                        'error'      => 'string',
                        'FirstLogin' => 'boolean',
                        'IsAdmin'    => 'boolean'
                    )
                );

            $this->__dispatch_map['listScreens'] =
                //
                // Получает список экранов указанного пользователя
                //
                // 	Параметры
                //		U_ID - идентификатор пользователя (base64)
                //
                // 	Возвращает
                //		return - перечисление всех приложений и экранов. к которым
                //		есть доступ в виде XML (base64)
                //
                array(
                    'in'  => array('U_ID' => 'string'),
                    'out' => array('return' => 'string')
                );

            $this->__dispatch_map['InitSession'] =
                //
                // Создает новую сессию для указанного пользователя (пока используется
                // вместе с отчетами)
                //
                // 	Параметры
                //		U_ID - идентификатор пользователя (base64)
                //		PASSWORD - его пароль (md5, base64)
                //		lang - язык сообщений
                //
                // 	Возвращает
                //		return - идентификатор сессии
                //		error - сообщение об ошибке или пустую строку (base64)
                //
                array(
                    'in'  => array('U_ID' => 'string', 'PASSWORD' => 'string', 'lang' => 'string'),
                    'out' => array(
                        'return' => 'string',
                        'error'  => 'string'
                    )
                );

            $this->__dispatch_map['CloseSession'] =
                //
                // Создает новую сессию для указанного пользователя (пока используется
                // вместе с отчетами)
                //
                // 	Параметры
                //		SID - session id
                //		U_ID - идентификатор пользователя (base64)
                //		PASSWORD - его пароль (md5, base64)
                //		lang - язык сообщений
                //
                // 	Возвращает
                //		return - сообщение об ошибке или пустую строку (base64)
                //
                array(
                    'in'  => array('SID' => 'string', 'U_ID' => 'string', 'PASSWORD' => 'string', 'lang' => 'string'),
                    'out' => array(
                        'return' => 'string'
                    )
                );

            $this->__typedef['LocStrings'] =
                //
                // массив из LocString
                //
                array(
                    array(
                        'item' => '{urn:LoginServer}LocString'
                    )
                );

            //
            // LocString - класс имеюший два свойства
            //		Id - идентификатор строки (base64)
            //		Value - строка (base64)
            //
            $this->__typedef['LocString'] = $NAMED_ITEM;
        }

        function __dispatch($methodname)
        {
            if (isset($this->__dispatch_map[$methodname]))
                return $this->__dispatch_map[$methodname];

            return null;
        }

        function CloseSession($SID, $UID, $PASSWORD, $lang)
        {
            global $DB_KEY;
            $res = CheckSoapUser(array(
                "U_ID"       => $U_ID,
                "U_PASSWORD" => $PASSWORD,
                "LANGUAGE"   => $lang
            ));

            if (PEAR::isError($res))
                return base64_encode($res->getMessage());

            $session_started = ini_get('session.auto_start');

            session_id($SID);

            if (!$session_started)
                @session_start();

            @session_unset();
            @session_destroy();

            return "";

        }

        function InitSession($U_ID, $PASSWORD, $lang)
        {
            global $DB_KEY;

            $res = CheckSoapUser(array(
                "U_ID"       => $U_ID,
                "U_PASSWORD" => $PASSWORD,
                "LANGUAGE"   => $lang
            ));

            if (PEAR::isError($res))
                return array(
                    new SOAP_Value('return', 'string', ""),
                    new SOAP_Value('error', 'string', base64_encode($res->getMessage()))
                );

            ini_set('session.cookie_lifetime', 2592000);

            $session_started = ini_get('session.auto_start');

            if (!$session_started)
                @session_start();

            $id = session_id();

            session_register(WBS_USERNAME);
            session_register('U_ID');
            session_register('DB_KEY');
            session_register('U_PASSWORD');

            $_SESSION[WBS_USERNAME] = strtoupper(base64_decode($U_ID));
            $_SESSION['U_ID'] = strtoupper(base64_decode($U_ID));
            $_SESSION['DB_KEY'] = $DB_KEY;
            $_SESSION['U_PASSWORD'] = strtolower(base64_decode($PASSWORD));
            $_SESSION['timestamp'] = time();

            return array(
                new SOAP_Value('return', 'string', $id),
                new SOAP_Value('error', 'string', "")
            );
        }

        function listScreens($U_ID)
        {
            global $userGlobalSettings;
            global $loc_str;
            global $global_applications;

            $UID = strtoupper(base64_decode($U_ID));
            $language = readUserCommonSetting($UID, LANGUAGE);
            $userGlobalSettings = readUserSummaryCommonSysSettings($UID, $loc_str[$language]);
            $result = sprintf("<%sxml version=\"1.0\" encoding=\"windows-1251\"%s>", "?", "?");
            $result .= "<applications>";

            $app_list = sortApplicationList($global_applications);
//			$app_list = getHostApplications();

            $list = sortAppScreenList(listUserScreens($UID));

            /*			$app_id = "MW";
                        $app_name = getAppName($app_id, $language);
                        $result .= @sprintf( "<app ID = \"%s\" NAME = \"%s\">", base64_encode($app_id), base64_encode($app_name));
                        if (array_key_exists($app_id, $list)) {
                            foreach ($list[$app_id] as $k => $item)
                            {
                                $scr_name = getScreenName($app_id, $item, $language);
                                $result .= @sprintf( "<screen NAME = \"%s\" ID = \"%s\" />", base64_encode($scr_name), base64_encode($item));
                            }
                        }
                        $result .= "</app>";
                        $app_id = "AA";
                        $app_name = getAppName($app_id, $language);
                        $result .= @sprintf( "<app ID = \"%s\" NAME = \"%s\">", base64_encode($app_id), base64_encode($app_name));
                        if (array_key_exists($app_id, $list)) {
                            foreach ($list[$app_id] as $k => $item)
                            {
                                $scr_name = getScreenName($app_id, $item, $language);
                                $result .= @sprintf( "<screen NAME = \"%s\" ID = \"%s\" />", base64_encode($scr_name), base64_encode($item));
                            }
                        }
                        $result .= "</app>";*/

            foreach ($app_list as $app_id => $app_data) {
                $app_name = getAppName($app_id, $language);
                $result .= @sprintf("<app ID = \"%s\" NAME = \"%s\">", base64_encode($app_id), base64_encode($app_name));
                if (array_key_exists($app_id, $list)) {
                    foreach ($list[$app_id] as $k => $item) {
                        $scr_name = getScreenName($app_id, $item, $language);
                        $result .= @sprintf("<screen NAME = \"%s\" ID = \"%s\" />", base64_encode($scr_name), base64_encode($item));
                    }
                }
                $result .= "</app>";
            }

            /*			foreach ( $list as $app_id => $app_screens )
                        {
                            if ( in_array($app_id, array("AA", "MW")) )
                                continue;

                            $app_name = getAppName($app_id, $language);
                            $result .= @sprintf( "<app ID = \"%s\" NAME = \"%s\">", base64_encode($app_id), base64_encode($app_name));

                            foreach ( $app_screens as $k => $item)
                            {
                                $scr_name = getScreenName($app_id, $item, $language);
                                $result .= @sprintf( "<screen NAME = \"%s\" ID = \"%s\" />", base64_encode($scr_name), base64_encode($item));
                            }

                            $result .= "</app>";
                        }
            */
            /*			$list = sortAppScreenList(listUserScreens($UID));

                        foreach ($list as $app_id => $app)
                        {
                            $app_name = getAppName($app_id, $language);
                            $result .= @sprintf( "<app ID = \"%s\" NAME = \"%s\">", $app_id, $app_name);
                            foreach ($app as $k => $item)
                            {
                                $scr_name = getScreenName($app_id, $item, $language);
                                $result .= @sprintf( "<screen NAME = \"%s\" ID = \"%s\" />", $scr_name, $item);
                            }
                            $result .= "</app>";
                        }*/
            $result .= "</applications>";

            return base64_encode($result);
        }

        function CheckScreenRights($APP_ID, $SCR_ID, $U_ID, $pwd, $lang)
        {
            $res = CheckSoapUser(array(
                "U_ID"       => $U_ID,
                "U_PASSWORD" => $pwd,
                "LANGUAGE"   => $lang,
                "APP_ID"     => base64_decode($APP_ID),
                "SCR_ID"     => base64_decode($SCR_ID)
            ));
            if (PEAR::isError($res))
                return base64_encode($res->getMessage());

            return "";
        }

        function GetLoginMessage($U_ID, $pwd, $lang)
        {
            global $loc_str;

            $UID = strtoupper(base64_decode($U_ID));
            $res = CheckSoapUser(array(
                "U_ID"       => $U_ID,
                "U_PASSWORD" => $pwd,
                "LANGUAGE"   => $lang
            ));
            if (PEAR::isError($res))
                return array(
                    new SOAP_Value('return', 'string', ""),
                    new SOAP_Value('error', 'string', base64_encode($res->getMessage())),
                    new SOAP_Value('FirstLogin', 'boolean', false),
                    new SOAP_Value('IsAdmin', 'boolean', isAdministratorID($UID))
                );

            $infoStr = "";
            loginMessageRequired($UID, $loc_str[$lang], $infoStr);
            $firstLogin = isSystemFirstLogin();
            if (!isAdministratorID($UID))
                writeHostDataFileParameter("/".HOST_DATABASE."/".HOST_DBSETTINGS, HOST_FIRSTLOGIN, 1, $loc_str[$lang]);

            return array(
                new SOAP_Value('return', 'string', base64_encode($infoStr)),
                new SOAP_Value('error', 'string', ""),
                new SOAP_Value('FirstLogin', 'boolean', $firstLogin),
                new SOAP_Value('IsAdmin', 'boolean', isAdministratorID($UID))
            );
        }

        function UserAuthorize($U_ID, $pwd)
        {
            global $_GET;
            global $loc_str;
//			global $DB_NAME;
            global $DB_KEY;

            $UID = strtoupper(base64_decode($U_ID));
            $PASSWORD = strtolower(base64_decode($pwd));

            $userdata = array(
                "U_ID"       => $UID,
                "U_PASSWORD" => $PASSWORD,
                "DB_KEY"     => $DB_KEY
            );

            $res = host_login($userdata, $loc_str[LANG_ENG], $_SERVER["REMOTE_ADDR"], "soap");

            if (PEAR::isError($res)) {
                return array(
                    new SOAP_Value('return', 'string', base64_encode($res->getMessage())),
                    new SOAP_Value('language', 'string', ""),
                    new SOAP_Value('Company', 'string', ""),
                    new SOAP_Value('LogoPath', 'string', ""),
                    new SOAP_Value('User', 'string', ""),
                    new SOAP_Value('ServiceParams', 'string', "")
                );
            }

            if (!isAdministratorID($UID)) {
                $language = readUserCommonSetting($UID, LANGUAGE);
            } else {
                $adminInfo = loadAdminInfo();
                $language = $adminInfo[LANGUAGE];
            }

            if (!strlen($language)) $language = LANG_ENG;
            $name = getUserName($UID, true);
            $company = getCompanyName();

            $fileName = "logo.gif";
            $filePath = getKernelAttachmentsDir();
            $filePath .= "/".$fileName;

            if (!file_exists($filePath)) {
                $filePath = "";
            }

            return array(
                new SOAP_Value('return', 'string', ""),
                new SOAP_Value('language', 'string', $language),
                new SOAP_Value('Company', 'string', base64_encode($company)),
                new SOAP_Value('LogoPath', 'string', base64_encode($filePath)),
                new SOAP_Value('User', 'string', base64_encode($name)),
                new SOAP_Value('ServiceParams', 'string', "DB_KEY=".$DB_KEY)
            );
        }

        function GetUserInfo($U_ID)
        {
            global $_GET;

            $UID = strtoupper(base64_decode($U_ID));

            if (!isAdministratorID($UID)) {
                $language = readUserCommonSetting($UID, LANGUAGE);
            } else {
                $adminInfo = loadAdminInfo();
                $language = $adminInfo[LANGUAGE];
            }
            if (!strlen($language)) $language = LANG_ENG;
            $name = getUserName($UID, true);
            $company = getCompanyName();

            $fileName = "logo.gif";
            $filePath = getKernelAttachmentsDir();
            $filePath .= "/".$fileName;

            if (!file_exists($filePath)) {
                $filePath = "";
            }

            return array(
                new SOAP_Value('return', 'string', ""),
                new SOAP_Value('language', 'string', $language),
                new SOAP_Value('Company', 'string', base64_encode($company)),
                new SOAP_Value('LogoPath', 'string', base64_encode($filePath)),
                new SOAP_Value('User', 'string', base64_encode($name))
            );
        }

    }

    require_once 'SOAP/Server.php';
    $server = new SOAP_Server;

    $soapclass = new SOAP_Login_Server();
    $server->_auto_translation = true;
    $server->addObjectMap($soapclass, 'urn:SOAP_Login_Server');

    if (isset($_SERVER['REQUEST_METHOD']) &&
        $_SERVER['REQUEST_METHOD'] == 'POST'
    ) {
        $server->service($HTTP_RAW_POST_DATA);
    } else {
        require_once 'SOAP/Disco.php';
        $disco = new SOAP_DISCO_Server($server, 'LoginServer');
        header("Content-type: text/xml");
        echo $disco->getWSDL();
        exit;
    }
?>