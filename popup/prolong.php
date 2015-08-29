<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2010
 */

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

  if($time==999){
    //Постоянный доступ
    $query = "UPDATE SC_customers SET unlimited_order = 1, may_order_until = DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 3653 DAY) WHERE customerID = $id";
    $res = mysql_query($query) or die(mysql_error()."<br>$query");
    echo("Пользователь имеет постоянный доступ для оформления заказов.");
  } elseif($time==998) {
    //Отмена постоянного доступа
    $query = "UPDATE SC_customers SET unlimited_order = 0, may_order_until = DATE_ADD(CURRENT_TIMESTAMP, INTERVAL -1 HOUR) WHERE customerID = $id";
    $res = mysql_query($query) or die(mysql_error()."<br>$query");
    echo("Постоянный доступ для оформления заказов отменен.");
  } else {
    $query = "UPDATE SC_customers SET may_order_until = DATE_ADD(CURRENT_TIMESTAMP, INTERVAL $time HOUR) WHERE customerID = $id";
    $res = mysql_query($query) or die(mysql_error()."<br>$query");
    echo("Пользователь имеет $time час (-а, -ов) для оформления заказов.");
  }