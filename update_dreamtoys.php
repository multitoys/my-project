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

    echo(<<<TAG

            <html>
                <head>
                    <link rel='stylesheet' type='text/css' href='css/import.css'>
                </head>
                <body>

TAG
    );

    $archive_dir = $_SERVER['DOCUMENT_ROOT'].'/upload/';

//----------- Импорт товаров ----------- 
    $filename = $archive_dir.'dreamtoys.csv';
    $file     = file($filename);
    $rowcount = count($file);

    echo('<h1>Импорт товаров ...('.$rowcount.')</h1><hr><br>');
    echo(<<<'TAG'

            <div id='products' >
                <div style='width:0px;'></div>
            </div>

TAG
    );

    if (!$rowcount) {
        die(ShowError("CSV-файл ($filename) не содержит данных! (rowcount = $rowcount)"));
    }
    $no           = 0;
    $row          = 0;
    $percent      = 0;
    $replace_name = array('\'', '"');
    $non_insert   = array(
        'Батарейки', 'Карнавальные костюмы, акс', 'Новогодние игрушки', 'Одежда', 'Спортивные товары VD-sport',
        'Хоз. товары', 'Товары для праздников'
    );
    if (($handle = fopen($filename, 'r')) !== false) {
        DeleteRow('Conc__dreamtoys', "category='Новинки!'");
        DeleteRow('Conc_search__dreamtoys', "code LIKE '99%'");

        UpdateValue('Conc__dreamtoys', 'enabled = 0');

        while (($data = fgetcsv($handle, 1000, ';')) !== false) {
            set_time_limit(0);

            if ($row === 0) {
                for ($i = 0; $i <= 6; $i++) {
                    $column = DecodeCodepage1251($data[$i]);

                    switch ($column) {
                        case  'категория':
                            $pa = $i;
                            break;
                        case   'Группа':
                            $ca = $i;
                            break;
                        case   'Код':
                            $co = $i;
                            break;
                        case   'наименование':
                            $na = $i;
                            break;
                        case   'цена':
                            $pr = $i;
                            break;
                    }
                }
                $row++;
                continue;
            }
            $parent   = str_replace($non_insert, '', DecodeCodepage1251($data[$pa]));
            $category = mysql_real_escape_string(DecodeCodepage1251($data[$ca]));
            $pos      = strpos($parent, 'ТМ ');
            $parent   = mysql_real_escape_string($parent);
            if ($pos !== false && $parent) {
                $category = $parent;
                $parent   = mysql_real_escape_string('По Брендам');
            }
            $code         = mysql_real_escape_string(DecodeCodepage1251($data[$co]));
            $name_pr_code = explode('|', DecodeCodepage1251($data[$na]));
            $non_name     = $name_pr_code[0];
            $name         = '';
            $product_code = '';
            $price        = (double)$data[$pr];
            if ($non_name[0] !== '<') {
                $name         = mysql_real_escape_string(trim(str_replace($replace_name, '', $name_pr_code[0])));
                $product_code = mysql_real_escape_string(trim($name_pr_code[1]));
            }

            if ($parent && $name) {
                // Исправление цен
                if (!is_numeric($price)) {
                    $price = preg_replace('/[^0-9.]/', '', $price);
                }
                $price_usd = $price / 21.50;

                $productID = GetValue('productID', 'Conc__dreamtoys', "code = '$code'");

                if (!$productID) {
                    $query
                        = "
                            INSERT INTO Conc__dreamtoys
                                        (parent, category, code, product_code, name, price_uah, price_usd)
                            VALUES      ('$parent', '$category', '$code', '$product_code', '$name', $price, $price_usd)
                          ";
                    $res = mysql_query($query) or die(mysql_error()."<br>$query");
                    $productID = mysql_insert_id();
                } else {
                    $query
                        = "
                            UPDATE  Conc__dreamtoys

                            SET     parent = '$parent',
                                    category = '$category',
                                    product_code = '$product_code',
                                    name = '$name',
                                    price_uah = $price,
                                    price_usd = $price_usd,
                                    enabled   = 1

                            WHERE  productID = $productID";
                    $res = mysql_query($query) or die(mysql_error()."<br>$query");
                }
            }
            $row++;
            $no++;
            $progress = round(($no / ($rowcount - 1) * 100), 0, PHP_ROUND_HALF_DOWN);
            if ($progress > $percent) {
                $percent = $progress.'%';
                ProgressBar('products', $percent, $start2);
                BuferOut();
            }
        }
        fclose($handle);
    }
    echo('<span style="color:blue;"><br>Обработано '.$no.' товаров</span><br>');

// Оптимизация таблиц
//DeleteRow('Conc__dreamtoys', 'price_uah = 0.00');
    $query = "UPDATE Conc__dreamtoys SET parent='', category='' WHERE enabled=0";
    $res = mysql_query($query) or die(mysql_error()."<br>$query");
	
    $query = 'OPTIMIZE TABLE Conc__dreamtoys';
    $res = mysql_query($query) or die(mysql_error()."<br>$query");
    mysql_close();

    ProgressBar('products', $percent, false, true);
    echo('
        <br>
          <div id=\'end\'>Импорт завершен!</div>
      ');
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
                if (is_dir($directory.'/'.$file) && ($file !== '.' || $file !== '..')) {
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
        $condition = ($condition)?"WHERE $condition":'';
        $query     = "UPDATE $table SET $new_value $condition";
        $result = mysql_query($query) or die('Ошибка в запросе: '.mysql_error().'<br>'.$query);
    }

    function DeleteRow($table, $condition = '')
    {
        $condition = ($condition)?"WHERE $condition":'';
        $query     = "DELETE FROM $table $condition";
        $result = mysql_query($query) or die('Ошибка в запросе: '.mysql_error().'<br>'.$query);
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

    function DecodeCodeArray1251($array)
    {
        $array_new = array();
        foreach ($array as $arr) {
            $s           = 'windows-1251';
            $q           = iconv($s, 'UTF-8', $arr);
            $array_new[] = $q;
        }

        return $array_new;
    }

    function Rus2Translit($string)
    {
        $converter = array(
            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'e', 'ж' => 'zh', 'з' => 'z',
            'и' => 'i', 'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r',
            'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch', 'ш' => 'sh',
            'щ' => 'sch', 'ь' => '\'', 'ы' => 'y', 'ъ' => '\'', 'э' => 'e', 'ю' => 'yu', 'я' => 'ya', 'А' => 'A',
            'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'E', 'Ж' => 'Zh', 'З' => 'Z', 'И' => 'I',
            'Й' => 'Y', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O', 'П' => 'P', 'Р' => 'R', 'С' => 'S',
            'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C', 'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sch',
            'Ь' => '\'', 'Ы' => 'Y', 'Ъ' => '\'', 'Э' => 'E', 'Ю' => 'Yu', 'Я' => 'Ya'
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

    function ProgressBar($import_items, $percent, $full = '')
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
        echo str_repeat(' ', 1024 * 128);
        flush();
        usleep($delay);
    }

    function Debugging($start)
    {
        // $memoscript = memory_get_usage(true)/1048576;
        $memoscript_peak = memory_get_peak_usage(true) / 1048576;
        $time            = microtime(true) - $start;
        printf('<br>Скрипт выполнялся: %.2F сек.', $time);
        // printf('<br>Использовано оперативной памяти: %.2F МБ.', $memoscript);
        printf('<br>Пик оперативной памяти: %.2F МБ.', $memoscript_peak);
    }