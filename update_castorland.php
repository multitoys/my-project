<?php

    $start = microtime(true);
    ini_set('display_errors', true);
    define('DIR_ROOT', $_SERVER['DOCUMENT_ROOT'].'/published/SC/html/scripts');

    include_once(DIR_ROOT.'/includes/init.php');
    include_once(DIR_CFG.'/connect.inc.wa.php');
    include(DIR_FUNC.'/setting_functions.php');

    $DB_tree = new DataBase();
    $DB_tree->connect(SystemSettings::get('DB_HOST'), SystemSettings::get('DB_USER'), SystemSettings::get('DB_PASS'));
    $DB_tree->selectDB(SystemSettings::get('DB_NAME'));

    header('Content-Encoding: none', true);
    echo(<<<'TAG'
			<html>
				<head>
					<link rel='stylesheet' type='text/css' href='css/import.css'>
				</head>
				<body>
TAG
    );

    //Распаковка архива
    $archive_dir = $_SERVER['DOCUMENT_ROOT'].'/upload/';
    $dest_dir = $_SERVER['DOCUMENT_ROOT'].'/temp/import/';

    //----------- Импорт товаров -----------
    $filename = $archive_dir.'castorland.csv';
    $file = file($filename);
    $rowcount = count($file);
    echo('<h1>Импорт товаров ...('.$rowcount.')</h1><hr><br>');
    echo(<<<TAG
		<div id='products' >
			<div style='width:0px;'>&nbsp;</div>
		</div>
TAG
    );
    if (!$rowcount) die(ShowError("CSV-файл ($filename) не содержит данных! (rowcount = $rowcount)"));
    $no = 0;
    $row = 0;
    $percent = 0;

    if (($handle = fopen($filename, "r")) !== false) {
        $start2 = microtime(true);
        UpdateValue('SC_products', 'enabled = 0', 'in_stock = 0');
        while (($data = fgetcsv($handle, 1000, ";")) !== false) {
            set_time_limit(0);
            $row++;
            $id = (int)$data[0];
            $catid = (int)$data[1];
            $price = $data[2];
            $enabled = (int)$data[3];
            $cust_rate = (int)$data[4];

            // Исправление цен
            if (!is_numeric($price)) $price = preg_replace('/[^0-9.]/', '', $price);

            $productID = GetValue('productID', 'SC_products', "productID = '$id'");

            if (!$productID) {
                echo(ShowError("Неверный id ($id) (строка $row) - <span style='color:red;'>позиция проигнорирована</span>"));
            } else {
                $query
                    = "
                        UPDATE SC_products
                        SET categoryID = $catid,
                            Price = $price,
                            SpecialPrice = $price,
                            in_stock = 0,
                            enabled = $enabled,
                            ostatok = '100',
                            customers_rating = $cust_rate,
                            brand= 'Castorland'
                        WHERE
                            productID = $productID
                    ";
                $res = mysql_query($query) or die(mysql_error()."<br>$query");
                $no++;
                $progress = round(($no / ($rowcount - 1) * 100), 0, PHP_ROUND_HALF_DOWN);
                if ($progress > $percent) {
                    $percent = $progress.'%';
                    ProgressBar('products', $percent, $start2);
                    BuferOut();
                }
            }
        }
        fclose($handle);
    }
    echo('<span style="color:blue;"><br>Обработано '.$no.' товаров</span><br>');

    // Оптимизация таблиц
    $query
        = 'OPTIMIZE TABLE `SC_auth_log`, `SC_categories`, `SC_category_product`, `SC_currency_types`, `SC_customers`, `SC_customer_addresses`, `SC_customer_reg_fields_values`, `SC_ordered_carts`, `SC_orders`, `SC_order_status_changelog`, `SC_products`, `SC_product_list_item`, `SC_product_pictures`, `SC_shopping_carts`, `SC_shopping_cart_items`, `SC_subscribers`, `Search_products`, `Conc__alliance`, `Conc__divoland`, `Conc__dreamtoys`, `Conc__mixtoys`, `Conc_search__alliance`, `Conc_search__divoland`, `Conc_search__dreamtoys`, `Conc_search__mixtoys`';
    $res = mysql_query($query) or die(mysql_error()."<br>$query");

    function get($what, $condition, $table = '')
    {
        $query = "SELECT $what FROM $table WHERE $condition LIMIT 1";
        $result = mysql_query($query) or die('Ошибка в запросе: '.mysql_error().'<br>'.$query);
        $row = mysql_fetch_row($result);

        return $row[0];
    }

    function deleteRow($table, $condition = '')
    {
        $condition = ($condition) ? "WHERE $condition" : "";
        $query = "DELETE FROM $table $condition";
        $result = mysql_query($query) or die('Ошибка в запросе: '.mysql_error().'<br>'.$query);
    }

    function optimizeTable($table)
    {
        $query = "OPTIMIZE TABLE $table";
        $res = mysql_query($query) or die(mysql_error()."<br>$query");
        mysql_close();
    }

    /*-----------------------------------*/

    mysql_close();

    //echo('<span style="color:red;"><br>курс доллара - '.(1 / $usd).' грн</span>');

    // Удаление временных файлов
    //RemoveDir($_SERVER['DOCUMENT_ROOT'].'/upload/');

    ProgressBar('products', $percent, true);
    echo(<<<TAG
	<br>
	<div id='end'>Импорт завершен!</div>
TAG
    );
    Debugging($start);

    /*--------- Функции ---------*/
    function ShowError($msg)
    {
        return "<div style='color:red; font-size:16px;'>$msg</div>";
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
        $result = mysql_query($query) or die('Ошибка в запросе: '.mysql_error().'<br>'.$query);
        $row = mysql_fetch_row($result);

        return $row[0];
    }

    function UpdateValue($table, $new_value, $condition = '')
    {
        $condition = ($condition) ? "WHERE $condition" : '';
        $query = "UPDATE $table SET $new_value $condition";
        $result = mysql_query($query) or die('Ошибка в запросе: '.mysql_error().'<br>'.$query);
    }

//    function DeleteRow($table, $condition = '')
//    {
//        $condition = ($condition) ? "WHERE $condition" : '';
//        $query     = "DELETE FROM $table $condition";
//        $result = mysql_query($query) or die('Ошибка в запросе: '.mysql_error().'<br>'.$query);
//    }

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

    function ProgressBar($import_items, $percent, $full = false)
    {
        if ($full === true) {
            $full = 'background-image:linear-gradient(to bottom, #6AFF7D, #00DC08)';
        }
        echo "<script language='javascript'>
                    document.getElementById('$import_items').innerHTML=\"<div style='width:$percent;$full'>$percent</div>\"
              </script>
        ";
    }

    function BuferOut($delay = 0)
    {
        echo str_repeat(' ', 1024 * 64);
        flush();
        usleep($delay);
    }

    function Debugging($start)
    {
        // $memoscript = memory_get_usage(true)/1048576;
        $memoscript_peak = memory_get_peak_usage(true) / 1048576;
        $time = microtime(true) - $start;
        printf('<br>Скрипт выполнялся: %.2F сек.', $time);
        printf('<br>Пик оперативной памяти: %.2F МБ.', $memoscript_peak);
        // printf('<br>Использовано оперативной памяти: %.2F МБ.', $memoscript);
    }