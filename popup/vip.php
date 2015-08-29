<?php
  ini_set('display_errors', true);
  define('DIR_ROOT', $_SERVER['DOCUMENT_ROOT']."/published/SC/html/scripts");
  $DebugMode = false;
  $Warnings = array();
  include_once(DIR_ROOT.'/includes/init.php');
  include_once(DIR_CFG.'/connect.inc.wa.php');
  include(DIR_FUNC.'/setting_functions.php' );
  $DB_tree = new DataBase();
  $DB_tree->connect(SystemSettings::get('DB_HOST'), SystemSettings::get('DB_USER'), SystemSettings::get('DB_PASS'));

  $DB_tree->selectDB(SystemSettings::get('DB_NAME'));
  define('VAR_DBHANDLER','DBHandler');

  $id   = isset($_GET['id'])   ? $_GET['id']   : -1;
  $time = isset($_GET['time']) ? $_GET['time'] : -1;
  
  if($time==888){
    //SuperVIP-доступ
    $query = "UPDATE SC_customers SET vip = 2 WHERE customerID = $id";
    $res = mysql_query($query) or die(mysql_error()."<br>$query");
    echo("Пользователь имеет SuperVIP-доступ");
  }
	
  if($time==777){
    //VIP-доступ
    $query = "UPDATE SC_customers SET vip = 1 WHERE customerID = $id";
    $res = mysql_query($query) or die(mysql_error()."<br>$query");
    echo("Пользователь имеет доступ на VIP.MultiToys.com.ua");
  } 
  if($time==666) {
    //Отмена VIP-доступa
    $query = "UPDATE SC_customers SET vip = 0 WHERE customerID = $id";
    $res = mysql_query($query) or die(mysql_error()."<br>$query");
    echo("VIP-доступ для оформления заказов отменен");
  }