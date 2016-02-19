<?php
ini_set('display_errors',"0");
include('function.php');
include('demo/CToys.php');

if(file_exists("../dblist/MULTITOYS.xml")){
	$xml= simplexml_load_file("../dblist/MULTITOYS.xml");
	$DB_NAME=(string)$xml->DBSETTINGS['DB_NAME'];
	$DB_PASSWORD=(string)$xml->DBSETTINGS['DB_PASSWORD'];
	$DB_USER=(string)$xml->DBSETTINGS['DB_USER'];
	$SQLSERVER=(string)$xml->DBSETTINGS['SQLSERVER'];
	
	$nDB_LINK=mysql_connect($SQLSERVER,$DB_USER,$DB_PASSWORD) or die("xled: ".mysql_error());
    mysql_select_db($DB_NAME) or die('No connect BD');
	mysql_set_charset('utf8');
	
}else{
	die('No connect BD');
}

include('router.php');