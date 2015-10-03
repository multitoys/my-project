<?php
/**
 * Created by PhpStorm.
 * User: Gololobov
 * Date: 15.06.2015
 * Time: 17:39
 */
    ini_set('display_errors', true);
    define('DIR_ROOT', $_SERVER['DOCUMENT_ROOT'].'/published/SC/html/scripts');
    $DebugMode = false;
    $Warnings = array();
    include_once(DIR_ROOT . '/includes/init.php');
    include_once(DIR_FUNC . '/functions.php');
    include_once(DIR_CFG . '/connect.inc.wa.php');
    include(DIR_FUNC . '/setting_functions.php');
    $DB_tree = new DataBase();
    $DB_tree->connect(SystemSettings::get('DB_HOST'), SystemSettings::get('DB_USER'), SystemSettings::get('DB_PASS'));
    $DB_tree->selectDB(SystemSettings::get('DB_NAME'));
    define('VAR_DBHANDLER','DBHandler');
    
    $concs = array('divoland', 'mixtoys', 'dreamtoys', 'alliance');
    $table = 'Conc__analogs';
    
    deleteRow($table);
    
    $usd0 = 1 / getValue('currency_value', 'CID = 10', 'SC_currency_types');

    $query
        = "INSERT INTO $table
                      (categoryID, category, code_1c, product_code, name_ru, brand, purchase, usd_purchase,   Price, usd_Price,   ukraine)
          SELECT       categoryID AS ID, (SELECT name_ru FROM  SC_categories WHERE categoryID=ID), code_1c, product_code, name_ru, brand, purchase, purchase/$usd0, Price, Price/$usd0, ukraine
          FROM SC_products
          WHERE in_stock = 100 AND enabled AND Price <> 0.00";
    $res = mysql_query($query) or die(mysql_error()."<br>$query");

    foreach ($concs as $conc) {
        $query = "SELECT code, code_1c FROM Conc_search__$conc";
        $res = mysql_query($query) or die(mysql_error().$query);
		
		$usd = $usd0;
		
		if ($conc == 'divoland') {
			$usd = $usd0 + 0.10;
		}
		
        while ($Codes = mysql_fetch_object($res)) {
            $query2
                = "SELECT
                        price_uah
                    FROM
                        Conc__$conc
                    WHERE
                        code = '$Codes->code' AND enabled=1";
            $res2 = mysql_query($query2) or die(mysql_error().$query2);
            if ($analog = mysql_fetch_row($res2)) {
                $query3
                    = "UPDATE $table
                            SET    $conc      = $analog[0],
                                   usd_$conc  = $analog[0]/$usd,
                                   diff_$conc = ROUND((Price/$analog[0]-1)*100)
                            WHERE  code_1c    = '$Codes->code_1c'";
                $res3 = mysql_query($query3) or die(mysql_error()."<br>$query");
            }
        }
    }
    $query = "UPDATE $table SET max_diff = GREATEST(diff_alliance, diff_divoland, diff_dreamtoys, diff_mixtoys)";
    $res = mysql_query($query) or die(mysql_error().$query);
    optimizeTable($table);

    function getValue($what, $condition, $table='')
    {
        $query = "SELECT $what FROM $table WHERE $condition LIMIT 1";
        $result = mysql_query($query) or die('Ошибка в запросе: '.mysql_error().'<br>'.$query);
        $row = mysql_fetch_row($result);
        return $row[0];
    }

    function deleteRow($table, $condition='')
    {
        $condition = ($condition) ? "WHERE $condition" : "";
        $query = "DELETE FROM $table $condition";
        $result = mysql_query($query) or die('Ошибка в запросе: ' . mysql_error() . '<br>' . $query);
    }

    function optimizeTable($table)
    {
        $query = "OPTIMIZE TABLE $table";
        $res = mysql_query($query) or die(mysql_error()."<br>$query");
        mysql_close();
    }