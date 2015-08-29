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

  if($time==555){
    //Сброс авторизации пользователя
    $query = "UPDATE SC_customers SET logged = '0000-00-00 00:00:00' = $id";
    $res = mysql_query($query) or die(mysql_error()."<br>$query");
    echo("Авторизация обнулена");
  }