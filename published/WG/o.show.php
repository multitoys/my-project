<?
	if (!isset($_GET["q"]))
		exit;

	$onServer = false;
	list($dbkey, $code) = split('-',$_GET["q"]);
	if (!$code) {
		$code = $dbkey;
		//if ($_SERVER['HTTP_HOST'] == "webasyst.webasyst.net") {
		//	$dbkey = "WEBASYST";
		//} else {
			$domainName = getDomainName ();
			$dbkey = getDBKeyByAccountName ($domainName);
		//}
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
	header("Location: $script?DB_KEY=" . $dbkey . "&code=" . $code . "&" . $gstr);
	
	//require_once("./html/scripts/swidget.php");	
	
	
	
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