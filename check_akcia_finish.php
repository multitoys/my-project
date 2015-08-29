<?php

	define('DIR_ROOT', $_SERVER['DOCUMENT_ROOT']."/published/SC/html/scripts");
  $DebugMode = true;
  $Warnings = array();
  include_once(DIR_ROOT.'/includes/init.php');
  include_once(DIR_CFG.'/connect.inc.wa.php');
  include(DIR_FUNC.'/setting_functions.php' );
  $DB_tree = new DataBase();
  $DB_tree->connect(SystemSettings::get('DB_HOST'), SystemSettings::get('DB_USER'), SystemSettings::get('DB_PASS'));

  $DB_tree->selectDB(SystemSettings::get('DB_NAME'));
  define('VAR_DBHANDLER','DBHandler');

  $query = "SELECT * FROM SC_akcia where end_date<now() and enabled=1 and (isnull(sending) OR sending<>1)";
  $res   = mysql_query($query) or die(mysql_error()."<br>$query");

 	$r = array();
 	$count = 0;
  while($row = mysql_fetch_assoc($res)) {
    $r[] = $row;
    $count++;
  }

  $headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

	$query = "SELECT settings_value FROM SC_settings where settings_constant_name='CONF_GENERAL_EMAIL'";
  $res = mysql_query($query) or die(mysql_error()."<br>$query");
  $row = mysql_fetch_assoc($res);
  $addr = $row["settings_value"];

  $subject = "Закончен период активности акции";
	$body = "У следующих акций закончен период действия:\r\n";
  $ids = array();
  foreach ($r as $key => $value) {
   	$body.=$value["name_ru"]." - дата начала: ".date("d.m.Y H:i", strtotime($value["start_date"]))." - дата окончания: ".date("d.m.Y H:i", strtotime($value["end_date"]))."\r\n";
  	$ids[] = $value["akciaID"];
  }

  if($count>0) {
  	$ids = implode(',', $ids);
  	$query = "UPDATE SC_akcia SET sending=1 where akciaID in (".$ids.")";
  	mysql_query($query) or die(mysql_error()."<br>$query");
  	mail($addr, $subject, $body, $headers);
  }
  
?>