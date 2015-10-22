<?php

    ini_set('display_errors', true);
    define('DIR_ROOT', $_SERVER['DOCUMENT_ROOT'].'/published/SC/html/scripts');

    include_once(DIR_ROOT.'/includes/init.php');
    include_once(DIR_CFG.'/connect.inc.wa.php');
    include(DIR_FUNC.'/setting_functions.php');
    include(DIR_FUNC.'/import_functions.php');

    header('Content-Encoding: none', true);
    echo(<<<'TAG'
			<html>
				<head>
					<link rel='stylesheet' type='text/css' href='css/import.css'>
				</head>
				<body>
TAG
    );

    $dest_dir = $_SERVER['DOCUMENT_ROOT'].'/temp/import/';
    $filename = $dest_dir.'products.csv';


    $file = file($filename);
    $rowcount = count($file);

    $start = microtime(true);
    $csv = csvToArray($filename, 1000);
//    $csv = array_map('str_getcsv', file($filename));
    var_dump($csv);
    debugging($start);
