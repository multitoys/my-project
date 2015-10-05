<?php

    ignore_user_abort(true);
    set_time_limit(0);

    define('DIR_ROOT', $_SERVER['DOCUMENT_ROOT'].'/published/SC/html/scripts');

    include_once(DIR_ROOT.'/includes/init.php');
    include_once(DIR_CFG.'/connect.inc.wa.php');
    include(DIR_FUNC.'/setting_functions.php');

    $DB_tree = new DataBase();
    $DB_tree->connect(SystemSettings::get('DB_HOST'), SystemSettings::get('DB_USER'), SystemSettings::get('DB_PASS'));
    $DB_tree->selectDB(SystemSettings::get('DB_NAME'));
    define('VAR_DBHANDLER', 'DBHandler');

    $headers = 'MIME-Version: 1.0'."\r\n";
    $headers .= 'Content-type: text/html; charset=utf-8'."\r\n";


                    $query
                        = "
							UPDATE SC_products
							SET 
								Price = list_price,
								list_price = 0.00,
								akcia = 0,
								akcia_skidka = 0
							WHERE
								akcia = 1
						";
                    $res = mysql_query($query) or exit(1);


    // Оптимизация таблиц
    $query
        = 'OPTIMIZE TABLE `SC_auth_log`, `SC_categories`, `SC_category_product`, `SC_currency_types`, `SC_customers`, `SC_customer_addresses`, `SC_customer_reg_fields_values`, `SC_ordered_carts`, `SC_orders`, `SC_order_status_changelog`, `SC_products`, `SC_product_list_item`, `SC_product_pictures`, `SC_shopping_carts`, `SC_shopping_cart_items`, `SC_subscribers`, `Search_products`, `Conc__kindermarket`, `Conc__divoland`, `Conc__dreamtoys`, `Conc__mixtoys`, `Conc_search__kindermarket`, `Conc_search__divoland`, `Conc_search__dreamtoys`, `Conc_search__mixtoys`';
    $res = mysql_query($query) or exit(1);

    mysql_close();

    $subject = 'Акция завершена!';
    $body_end = $subject."\r\n";
    mail('multitoys.dp@gmail.com', $subject, $body_end, $headers);

//    exit(0);

    /*--------- Функции ---------*/
    function get($what, $condition, $table = '')
    {
        $query = "SELECT $what FROM $table WHERE $condition LIMIT 1";
        $result = mysql_query($query) or exit(1);
        $row = mysql_fetch_row($result);

        return $row[0];
    }

    function deleteRow($table, $condition = '')
    {
        $condition = ($condition) ? "WHERE $condition" : "";
        $query = "DELETE FROM $table $condition";
        $result = mysql_query($query) or exit(1);
    }

    function optimizeTable($table)
    {
        $query = "OPTIMIZE TABLE $table";
        $res = mysql_query($query) or exit(1);
        mysql_close();
    }

    function RemoveDir($directory)
    {
        $dir = opendir($directory);
        while (($file = readdir($dir))) {
            if (is_file($directory.'/'.$file)) {
                unlink($directory.'/'.$file);
            } else {
                if (is_dir($directory.'/'.$file) && ($file != '.') && ($file != '..')) {
                    RemoveDir($directory.'/'.$file);
                }
            }
        }
        closedir($dir);

        return true;
    }

    function GetValue($what, $table, $condition)
    {
        $query = "SELECT $what FROM $table WHERE $condition LIMIT 1";
        $result = mysql_query($query) or exit(1);
        $row = mysql_fetch_row($result);

        return $row[0];
    }

    function UpdateValue($table, $new_value, $condition = '')
    {
        $condition = ($condition) ? "WHERE $condition" : '';
        $query = "UPDATE $table SET $new_value $condition";
        $result = mysql_query($query) or exit(1);
    }

    function DecodeCodepage($text)
    {
        $s = mb_detect_encoding($text);
        $q = iconv($s, 'UTF-8', $text);

        return $q;
    }

    function DecodeCodepage1251($text)
    {
        $s = 'windows-1251';
        $q = iconv($s, 'UTF-8', $text);

        return $q;
    }

    function Rus2Translit($string)
    {
        $converter = array(
            'а' => 'a', 'б' => 'b', 'в' => 'v',
            'г' => 'g', 'д' => 'd', 'е' => 'e',
            'ё' => 'e', 'ж' => 'zh', 'з' => 'z',
            'и' => 'i', 'й' => 'y', 'к' => 'k',
            'л' => 'l', 'м' => 'm', 'н' => 'n',
            'о' => 'o', 'п' => 'p', 'р' => 'r',
            'с' => 's', 'т' => 't', 'у' => 'u',
            'ф' => 'f', 'х' => 'h', 'ц' => 'c',
            'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch',
            'ь' => '\'', 'ы' => 'y', 'ъ' => '\'',
            'э' => 'e', 'ю' => 'yu', 'я' => 'ya',

            'А' => 'A', 'Б' => 'B', 'В' => 'V',
            'Г' => 'G', 'Д' => 'D', 'Е' => 'E',
            'Ё' => 'E', 'Ж' => 'Zh', 'З' => 'Z',
            'И' => 'I', 'Й' => 'Y', 'К' => 'K',
            'Л' => 'L', 'М' => 'M', 'Н' => 'N',
            'О' => 'O', 'П' => 'P', 'Р' => 'R',
            'С' => 'S', 'Т' => 'T', 'У' => 'U',
            'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
            'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sch',
            'Ь' => '\'', 'Ы' => 'Y', 'Ъ' => '\'',
            'Э' => 'E', 'Ю' => 'Yu', 'Я' => 'Ya'
        );

        return strtr($string, $converter);
    }

    function Str2Url($str)
    {
        $str = Rus2Translit($str);
        $str = strtolower($str);
        $str = preg_replace('~[^-a-z0-9_]+~u', '-', $str);
        $str = trim($str, '-');

        return $str;
    }

    function DebuggingForMail($start)
    {
        $memoscript_peak = memory_get_peak_usage(true) / 1048576;
        $time = microtime(true) - $start;
        $res = 'Скрипт выполнялся: '.$time.".\r\n Пик оперативной памяти: ".$memoscript_peak."\r\n";

        return $res;
    }