<?php
		ini_set('display_errors', true);
		define('DIR_ROOT', $_SERVER['DOCUMENT_ROOT']."/published/SC/html/scripts");
		$DebugMode = false;
		$Warnings = array();
		include_once(DIR_ROOT.'/includes/init.php');
		include_once(DIR_CFG.'/connect.inc.wa.php');
		include(DIR_FUNC.'/setting_functions.php' );
?>
<html>
	<head>
		<title>Названия и Артикулы по Коду</title>
		<link rel='stylesheet' type='text/css' href='../css/import.css'>
	</head>
	<body>
		<form action="" method="post">
			<p><b>Введите коды 1С, разделяя ЗАПЯТОЙ:</b></p>
			<p><textarea rows="10" cols="45" name="code"></textarea></p>
			<p><input type="submit" value="Отправить"></p>
		</form>
<?php
	if (isset($_POST["code"])) {
		
		$DB_tree = new DataBase();
		$DB_tree->connect(SystemSettings::get('DB_HOST'), SystemSettings::get('DB_USER'), SystemSettings::get('DB_PASS'));
		$DB_tree->selectDB(SystemSettings::get('DB_NAME'));
		define('VAR_DBHANDLER','DBHandler');
		
		$code = stripslashes(trim($_POST["code"]));
		$code = str_replace(' ', '', $code);
		$codes = explode(",", $code);
		$ids = array();
		foreach ($codes as $id) {
			$id = trim($id);
			if (strlen($id) == 5) {
				$ids[] = $id;
			}
		}
		if (count($ids) > 1) {
			$query = implode(",", $ids);
			$query_to_db = "SELECT code_1c, name_ru, product_code 
							FROM SC_products
							WHERE code_1c IN ($query)";
			$res = mysql_query($query_to_db) or die(mysql_error()."<br>$query");
		} else {
			$query = $ids[0];
			$query_to_db = "SELECT code_1c, name_ru, product_code 
							FROM SC_products
							WHERE code_1c = $query";
			$res = mysql_query($query_to_db) or die(mysql_error()."<br>$query");
		}
		$no = 0;
		while($row = mysql_fetch_object($res)) {
			$no++;
			echo $no.')&nbsp;&nbsp;'.$row->code_1c.'&nbsp;&nbsp;['.$row->product_code.']&nbsp;&nbsp;'.$row->name_ru.'<br/><br/>';
		}
		mysql_free_result($query);
	}
?>
	</body>
</html>