<?
	$onServer = false;
	@list($dbkey, $code) = @split('-',$_GET["q"]);
	if (!$code) {
		$code = $dbkey;
		$domainName = getDomainName ();
		$dbkey = getDBKeyByAccountName ($domainName);
		if (!$dbkey)
			$dbkey = strtoupper($domainName);
		if (!$dbkey)
			die("Wrong query");
		$onServer = true;
		$dbkey = base64_encode($dbkey);
	}
	
	$gstrs = array ();
	foreach ($_GET as $cParam => $cValue) {
		if ($cParam == "q")
			continue;
		if ($cParam == "fparams") {
			$forwardParamsPairs = split ("&", base64_decode($cValue));
			foreach ($forwardParamsPairs as $pair) {
				list ($pkey, $pvalue) = split("=", $pair);
				$gstrs[] = $pkey . "=" . $pvalue;
				$_GET[$pkey] = $pvalue;
			}
			continue;
		}
		$gstrs[] = $cParam . "=" . $cValue;
	}
	
	$gstr = join("&", $gstrs);
	
	if ($onServer)
		$script = "/WG/html/scripts/swidget.php";
	else
		$script = "html/scripts/swidget.php";		
	//header("Location: $script?DB_KEY=" . $dbkey . "&code=" . $code . "&" . $gstr);
	
	$_GET["DB_KEY"] = $dbkey;
	$_GET["code"] = $code;
	
	if (!empty($_SERVER["PATH_TRANSLATED"]))
		$path = substr($_SERVER["PATH_TRANSLATED"],0,0-strlen("show.php"));
	else
		$path = substr($_SERVER["SCRIPT_FILENAME"],0,0-strlen("show.php"));
	
	$_SERVER["PATH_TRANSLATED"] = $path . "/html/scripts/swidget.php";
	if (isset($_SERVER["ORIG_PATH_TRANSLATED"]))
		$_SERVER["ORIG_PATH_TRANSLATED"] = $_SERVER["PATH_TRANSLATED"];
	$_SERVER["SCRIPT_FILENAME"] = $path . "/html/scripts/swidget.php";
	$_SERVER["PHP_SELF"] = substr($_SERVER["PHP_SELF"],0,0-strlen("show.php")) . "html/scripts/swidget.php";
	
	if (isset($_GET["fparams"]))
		define("BASE_SRC", "../../");
	else
		define("BASE_SRC", "../");
	define("WG_SRC", BASE_SRC . "WG/");

	chdir("./html/scripts/");
	require_once("./swidget.php");
	
	function getDBKeyByAccountName($account_name) {
		$accordance_file = '../../dblist/dbnames';
		$account_name = strtolower($account_name);
		if(!$account_name)return ;
		
		if(!file_exists($accordance_file)) return;

		$fp = fopen($accordance_file, 'r');
		$resDbKey = "";
		while (!feof($fp)) {
			$__t = explode(' ', trim(fgets($fp, 1024)), 2);
			$cname = $__t[0];
			$dbkey = isset($__t[1])?$__t[1]:'';
			if(strtolower($cname) !== strtolower($account_name))continue;
				
			$resDbKey = $dbkey;
			break;
		}
		fclose($fp);
		if (empty($resDbKey))
			$resDbKey = strtoupper($account_name);
		return $resDbKey;
	}
	
	function getDomainName(){
		if (preg_match('/(.*?)\.([a-z0-9\.\-]+)/ui', $_SERVER['HTTP_HOST'], $matches))
			$res=$matches[1];
		else
			$res='';
		return $res;
	}
?>