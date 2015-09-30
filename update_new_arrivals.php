<?php
    /**
     * Created by PhpStorm.
     * User: Gololobov
     * Date: 18.09.2015
     * Time: 17:07
     */

    define('DIR_ROOT', $_SERVER['DOCUMENT_ROOT'].'/published/SC/html/scripts');

    $DebugMode = true;
    $Warnings = array();

    include_once(DIR_ROOT.'/includes/init.php');
    include_once(DIR_CFG.'/connect.inc.wa.php');
    include(DIR_FUNC.'/setting_functions.php');

    $DB_tree = new DataBase();
    $DB_tree->connect(SystemSettings::get('DB_HOST'), SystemSettings::get('DB_USER'), SystemSettings::get('DB_PASS'));
    $DB_tree->selectDB(SystemSettings::get('DB_NAME'));
    define('VAR_DBHANDLER', 'DBHandler');

    $query = "UPDATE SC_product_list_item SET date=date+1 WHERE list_id='newitemspostup'";
    $res = mysql_query($query) or die(mysql_error()."<br>$query");

    $query = 'OPTIMIZE TABLE SC_product_list_item';
    $res = mysql_query($query) or die(mysql_error()."<br>$query");
    mysql_close();