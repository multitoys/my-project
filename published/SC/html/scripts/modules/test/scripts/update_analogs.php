<?php
    /**
     * Created by PhpStorm.
     * User: Gololobov
     * Date: 15.06.2015
     * Time: 17:39
     */

    $start = microtime(true);
    ini_set('display_errors', true);
    define('DIR_ROOT', $_SERVER['DOCUMENT_ROOT'].'/published/SC/html/scripts');

    include_once(DIR_ROOT.'/includes/init.php');
    include_once(DIR_CFG.'/connect.inc.wa.php');
    include(DIR_FUNC.'/setting_functions.php');
    include(DIR_FUNC.'/import_functions.php');

    $DB_tree = new DataBase();
    $DB_tree->connect(
        SystemSettings::get('DB_HOST'),
        SystemSettings::get('DB_USER'),
        SystemSettings::get('DB_PASS')
    );
    $DB_tree->selectDB(SystemSettings::get('DB_NAME'));

    $table = 'Conc__analogs';

    $query = 'SELECT * FROM Conc__competitors';
    $res = mysql_query($query) or die(mysql_error()."<br>$query");

    $competitors = array();

    while ($Currs = mysql_fetch_object($res)) {
        $competitors[$Currs->competitor] = $Currs->currency_value;
        $concs[] = $Currs->competitor;
    }

    $delete_null = '';
    $diff_conc = array();

    foreach ($concs as $unic_conc) {

        $query = 'UPDATE '.$table.' SET '.$unic_conc.'=NULL';
        $res = mysql_query($query) or die(mysql_error().$query);

        $delete_null .= 'AND '.$unic_conc.' IS NULL ';
        $diff_conc[] = 'diff_'.$unic_conc;

        $query = "SELECT code, code_1c FROM Conc_search__$unic_conc";
        $res = mysql_query($query) or die(mysql_error().$query);

        $usd_conc = $competitors[$unic_conc];

        while ($Codes = mysql_fetch_object($res)) {

            $query2
                = "SELECT
                        price_uah
                    FROM
                        Conc__$unic_conc
                    WHERE
                        code = '$Codes->code' AND enabled=1";
            $res2 = mysql_query($query2) or die(mysql_error().$query2);

            if ($analog = mysql_fetch_row($res2)) {

                $query3
                    = "UPDATE $table
                            SET    $unic_conc      = $analog[0],
                                   usd_$unic_conc  = $analog[0]/$usd_conc,
                                   diff_$unic_conc = ROUND((Price/$analog[0]-1)*100, 1)
                            WHERE  code_1c         = '$Codes->code_1c'";
                $res3 = mysql_query($query3) or die(mysql_error()."<br>$query");
            }
        }

        optimizeTable('Conc__'.$unic_conc);
        optimizeTable('Conc_search__'.$unic_conc);
    }

    $query = "DELETE FROM $table WHERE 1 $delete_null";
    $res = mysql_query($query) or die(mysql_error().$query);

    $diff_conc = implode(',', $diff_conc);
    $query = "UPDATE $table SET max_diff = GREATEST($diff_conc)";
    $res = mysql_query($query) or die(mysql_error().$query);

    optimizeTable($table);

    mysql_close();

    // Удаление временных файлов
    removeDirRec($_SERVER['DOCUMENT_ROOT'].'/upload/');
    echo(<<<TAG
	<div id='end'>Импорт завершен!</div>
TAG
    );
    debugging($start);