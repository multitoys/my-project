<?php
    $authScript = true;

    require_once("../includes/soapinit.php");
    require_once("../includes/soapclient_funcs.php");

    require_once("SOAP/Value.php");
    require_once("SOAP/Fault.php");

    define("SETUP_FILENAME", "webasystwinsetup.exe");
    define("CUSTOM_SETUP_FILENAME", "winclient.exe");

    class SOAP_Plugins_Server
    {
        var $__typedef = array();
        var $__dispatch_map = array();

        function SOAP_Plugins_Server()
        {
            $this->__dispatch_map['plf_getFileInfo'] =
                //
                //	function plf_getFileInfo();
                //
                //	Description
                //		Get information about file
                //
                //	Parameters
                //		path - path to compressed file that will be downloaded and contains additional information
                //		realfile - path to original (uncompressed) file
                //
                //	Returns
                //		result[return] - CDownloadInfo record
                //
                array(
                    'in'  => array(
                        'path'     => 'string',
                        'realfile' => 'string'),
                    'out' => array(
                        'return' => '{urn:PluginsServer}CDownloadInfo'
                    )
                );

            $this->__dispatch_map['plf_getClientFileInfo'] =
                //
                //	function plf_getFileInfo();
                //
                //	Description
                //		Get information about setup file
                //
                //	Parameters
                //		path - path to compressed file that will be downloaded and contains additional information
                //		setup_filename - setup file name (base64)
                //
                //	Returns
                //		result[return] - CDownloadInfo record,
                //
                array(
                    'in'  => array(
                        'path' => 'string'),
                    'out' => array(
                        'return'         => '{urn:PluginsServer}CDownloadInfo',
                        'builddate'      => '{urn:PluginsServer}CExDownloadInfo',
                        'setup_filename' => 'string'
                    )
                );

            $this->__dispatch_map['plf_getPlugins'] =
                //
                //	function plf_getPlugins();
                //
                //	Description
                //		Returns an information about plugin files
                //
                //	Parameters
                //		lang - language (to select appropriated localization files)
                //
                //	Returns
                //		result[return] - array of PluginInfo records
                //
                array(
                    'in'  => array('lang' => 'string'),
                    'out' => array(
                        'return' => '{urn:PluginsServer}PluginsList'
                    )
                );

            //
            //	PluginsList array
            //
            //	Description
            //		Array of PluginInfo records
            //
            $this->__typedef['PluginsList'] =
                array(
                    array('item' => '{urn:PluginsServer}PluginInfo')
                );
            //
            //	PluginInfo record
            //
            //	Description
            //		Information about plugin file
            //
            //	Fields
            //		source - url to download
            //		dest - destination path where plugin should be plased on client side
            //		m_crcHigh, m_crcLow - checksum of file
            //		m_size - size of file to download
            //
            $this->__typedef['PluginInfo'] =
                array(
                    'source'    => 'string',
                    'dest'      => 'string',
                    'm_crcHigh' => 'int',
                    'm_crcLow'  => 'int',
                    'm_size'    => 'int'
                );
            //
            //	CDownloadInfo record
            //
            //	Description
            //		Download information about file
            //
            //	Fields
            //		m_crcHigh, m_crcLow - checksum of file
            //		m_size - size of file to download
            //
            $this->__typedef['CDownloadInfo'] =
                array(
                    'm_crcHigh' => 'int',
                    'm_crcLow'  => 'int',
                    'm_size'    => 'int'
                );

            //	CExDownloadInfo record
            //
            //	Description
            //		Additional information about file
            //
            //	Fields
            //		year, month, date - file build date
            //
            $this->__typedef['CExDownloadInfo'] =
                array(
                    'year'  => 'int',
                    'month' => 'int',
                    'date'  => 'int'
                );
        }

        function __dispatch($methodname)
        {
            if (isset($this->__dispatch_map[$methodname]))
                return $this->__dispatch_map[$methodname];

            return null;
        }

        function GetCRCFromFile($desc)
        {
            $crc = "";
            for ($i = 0; $i < 4; $i++)
                $crc = (bin2hex(fread($desc, 1))).$crc;

            return str_pad($crc, 8, '0', STR_PAD_LEFT);
        }

        function GetBuildDate($desc)
        {
            $builddate = "";
            for ($i = 0; $i < 6; $i++)
                $builddate = (bin2hex(fread($desc, 1))).$builddate;

            return str_pad($builddate, 12, '0', STR_PAD_LEFT);
        }

        function plf_getClientFileInfo($path)
        {
            $crc = "00000000";
            $builddate = "000000000000";
            $size = 0;
            $path = base64_decode($path);

            $realfile = "../../win/".CUSTOM_SETUP_FILENAME;
            $customFileExists = file_exists($realfile);
            if (!$customFileExists)
                $realfile = "../../win/".SETUP_FILENAME;

            if (file_exists($path)) {
                $size = filesize($path);
                $desc = fopen($path, "r");
                if ($desc) {
                    $crc = $this->GetCRCFromFile($desc);
                    fread($desc, 8);
                    $builddate = $this->GetBuildDate($desc);
                    fclose($desc);
                }
            }
            if (strlen($realfile)) {
                if (file_exists($realfile))
                    $size = filesize($realfile);
            }

            $returnFileName = $customFileExists ? CUSTOM_SETUP_FILENAME : SETUP_FILENAME;

            return array(
                new SOAP_Value('return', '{urn:PluginsServer}CDownloadInfo',
                    array(
                        "m_crcHigh" => hexdec(substr($crc, 0, 4)),
                        "m_crcLow"  => hexdec(substr($crc, 4, 4)),
                        "m_size"    => $size
                    )
                ),
                new SOAP_Value('builddate', '{urn:PluginsServer}CExDownloadInfo',
                    array(
                        "day"   => hexdec(substr($builddate, 0, 4)),
                        "month" => hexdec(substr($builddate, 4, 4)),
                        "year"  => hexdec(substr($builddate, 8, 4))
                    )
                ),
                new SOAP_Value('setup_filename', 'string', base64_encode($returnFileName))
            );
        }

        function plf_getFileInfo($path, $realfile)
        {
            $crc = "00000000";
            $size = 0;
            $path = base64_decode($path);
            $realfile = base64_decode($realfile);
            if (file_exists($path)) {
                $size = filesize($path);
                $desc = fopen($path, "r");
                if ($desc) {
                    $crc = $this->GetCRCFromFile($desc);
                    fclose($desc);
                }
            }
            if (strlen($realfile)) {
                if (file_exists($realfile))
                    $size = filesize($realfile);
            }

            return new SOAP_Value('return', '{urn:PluginsServer}CDownloadInfo',
                array(
                    "m_crcHigh" => hexdec(substr($crc, 0, 4)),
                    "m_crcLow"  => hexdec(substr($crc, 4, 4)),
                    "m_size"    => $size
                )
            );
        }

        function getZipFileInfo($src, $dest)
        {
            $crc = "00000000";
            $size = 0;
            if (file_exists($src)) {
                $size = filesize($src);
                $desc = fopen($src, "r");
                if ($desc) {
                    $crc = $this->GetCRCFromFile($desc);
                    fclose($desc);
                }
            }

            return array(
                "source"    => base64_encode($src),
                "dest"      => base64_encode($dest),
                "m_crcHigh" => hexdec(substr($crc, 0, 4)),
                "m_crcLow"  => hexdec(substr($crc, 4, 4)),
                "m_size"    => $size
            );
        }

        function getFileInfo($src, $dest)
        {
            $crc = "00000000";
            $size = 0;
            $checksum = 0;
            if (file_exists($src)) {
                $size = filesize($src);
                $crc = dechex(crc32(file_get_contents($src)));
            }

            return array(
                "source"    => base64_encode($src),
                "dest"      => base64_encode($dest),
                "m_crcHigh" => hexdec(substr($crc, 0, 4)),
                "m_crcLow"  => hexdec(substr($crc, 4, 4)),
                "m_size"    => $size
            );
        }

        function plf_getPlugins($lang)
        {
            global $DB_KEY;
            $res = array();
            $base_path = "../../win/";
            $res[] = $this->getFileInfo($base_path."localization.".$lang, "localization".".".$lang);

            $path = sprintf("../../../../dblist/%s.xml", $DB_KEY);
            $base_path = "../../../";
            if (file_exists($path)) {
                $dom = domxml_open_file(realpath($path));
                $elems = $dom->get_elements_by_tagname("APPLICATION");
                for ($i = 0; $i < count($elems); $i++) {
                    $elem = $elems[$i];
                    $app_id = $elem->get_attribute("APP_ID");
                    $res[] = $this->getZipFileInfo($base_path.$app_id."/win/".strtolower($app_id).".bpl.zip", "Plugins/".$app_id."/".strtolower($app_id).".bpl");
                    $res[] = $this->getFileInfo($base_path.$app_id."/win/".strtolower($app_id).".".$lang, "Plugins/".$app_id."/".strtolower($app_id).".".$lang);
//					$res[] = $this->getFileInfo($base_path.$app_id."/win/".strtolower($app_id)."_".$lang.".dll.zip", "Plugins/".$app_id."/".strtolower($app_id)."_".$lang.".dll");
//					$res[] = $this->getFileInfo($base_path.$app_id."/win/".strtolower($app_id).".chm.zip", "Help/".$lang."/".strtolower($app_id).".chm");
                }
            }

            $app_id = "AA";
            $res[] = $this->getZipFileInfo($base_path.$app_id."/win/".strtolower($app_id).".bpl.zip", "Plugins/".$app_id."/".strtolower($app_id).".bpl");
            $res[] = $this->getFileInfo($base_path.$app_id."/win/".strtolower($app_id).".".$lang, "Plugins/".$app_id."/".strtolower($app_id).".".$lang);
//			$res[] = $this->getFileInfo($base_path.$app_id."/win/".strtolower($app_id)."_".$lang.".dll.zip", "Plugins/".$app_id."/".strtolower($app_id)."_".$lang.".dll");
//			$res[] = $this->getFileInfo($base_path.$app_id."/win/".strtolower($app_id).".chm.zip", "Help/".$lang."/".strtolower($app_id).".chm");
            $app_id = "MW";
            $res[] = $this->getZipFileInfo($base_path.$app_id."/win/".strtolower($app_id).".bpl.zip", "Plugins/".$app_id."/".strtolower($app_id).".bpl");
            $res[] = $this->getFileInfo($base_path.$app_id."/win/".strtolower($app_id).".".$lang, "Plugins/".$app_id."/".strtolower($app_id).".".$lang);
//			$res[] = $this->getFileInfo($base_path.$app_id."/win/".strtolower($app_id).".chm.zip", "Help/".$lang."/".strtolower($app_id).".chm");
//			$res[] = $this->getFileInfo($base_path."common/win/index.chm.zip", "Help/".$lang."/index.chm");
            return new SOAP_Value('return', '{urn:PluginsServer}PluginsList', $res);
        }
    }

    require_once 'SOAP/Server.php';

    $server = new SOAP_Server;

    $soapclass = new SOAP_Plugins_Server();
    $server->_auto_translation = true;
    $server->addObjectMap($soapclass, 'urn:SOAP_Plugins_Server');

    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $server->service($HTTP_RAW_POST_DATA);
    } else {
        require_once 'SOAP/Disco.php';
        $disco = new SOAP_DISCO_Server($server, 'PluginsServer');
        header("Content-type: text/xml");
        echo $disco->getWSDL();
        exit;
    }

?>