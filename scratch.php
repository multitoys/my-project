<?php

    ini_set('display_errors', true);
    define('DIR_ROOT', $_SERVER['DOCUMENT_ROOT'].'/published/SC/html/scripts');

    include_once(DIR_ROOT.'/includes/init.php');
    include_once(DIR_CFG.'/connect.inc.wa.php');
    include(DIR_FUNC.'/setting_functions.php');

    $setings = 'SystemSettings';
    //$setings::get('DB_HOST');
    //$setings::get('DB_USER');
    //$setings::get('DB_PASS');
    //$setings::get('DB_NAME');
    //$setings_charset = 'utf8';

    $mysqli = new mysqli(
        $setings::get('DB_HOST'),
        $setings::get('DB_USER'),
        $setings::get('DB_PASS'),
        $setings::get('DB_NAME')
    );

    if ($mysqli->connect_errno) {
        echo 'Не удалось подключиться к MySQL: ('.$mysqli->connect_errno.') '.$mysqli->connect_error;
    }

    $result = GetAllRows($mysqli, 'Conc__currency', 'competitor');
    var_dump($result);

    function GetValue($mysqli, $table, $what, $condition)
    {
        $query = "SELECT $what FROM $table WHERE $condition LIMIT 1";
        $result = mysqli_query($mysqli, $query) or die('Ошибка в запросе: '.mysqli_error($mysqli).'<br>'.$query);
        $row = mysqli_fetch_row($result);

        return $row[0];
    }

    function GetAllColumns($mysqli, $table, $condition = '')
    {
        $condition = ($condition) ? 'WHERE '.$condition : '';
        $query = "SELECT * FROM $table $condition";
        $result = mysqli_query($mysqli, $query) or die('Ошибка в запросе: '.mysqli_error($mysqli).'<br>'.$query);
        $assoc = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $assoc[] = $row;
        }

        return $assoc;
    }

    function GetAllRows($mysqli, $table, $what = '')
    {
        $what = ($what) ?: '*';
        $query = "SELECT $what FROM $table";
        $result = mysqli_query($mysqli, $query) or die('Ошибка в запросе: '.mysqli_error($mysqli).'<br>'.$query);
        $rows = array();
        while ($row = mysqli_fetch_row($result)) {
            $rows[] = $row[0];
        }

        return $rows;
    }

    function deleteRow($mysqli, $table, $condition = '')
    {
        $condition = ($condition) ? "WHERE $condition" : '';
        $query = "DELETE FROM $table $condition";
        $result = mysqli_query($mysqli, $query) or die('Ошибка в запросе: '.mysqli_error($mysqli).'<br>'.$query);
    }

    function optimizeTable($mysqli, $table)
    {
        $query = "OPTIMIZE TABLE $table";
        $res = mysqli_query($mysqli, $query) or die(mysqli_error($mysqli)."<br>$query");
        mysqli_close($mysqli);
    }
