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
                <div id='products'>
                    <div style='width:0;'>&nbsp;</div>
                </div>

TAG
    );

    define('CODE_PATTERN', '<a[^<>]*?class="zoom[\s]+nyroModal"[\s]+href="/prodimages/normal/[\w]+/([\w]+?)\.jpg"[^<>]*?>[^<>]*?</a>[^<>]*?</div>[^<>]*?');
    define('NAME_PATTERN', '<a[\s]+class="name"[^<>]*?>(.*)</a>[^<>]*?');
    define('PRICE_PATTERN', '<a[^<>]*?class="price[\s]+nyroModal">[\s]*?([^<>]*?)<small>');
    define('SLASH', '|');

    //$query = 'DELETE FROM Conc__divoland';
    //$res = mysql_query($query) or die(mysql_error()."<br>$query");
    UpdateValue('Conc__divoland', 'enabled = 0');

    $html_dir = $_SERVER['DOCUMENT_ROOT'].'/popup/search_by_conc/divoland/';
    $folders  = array_slice(scandir($html_dir), 2);
    $parts    = count($folders);
    $files    = array();
    $error    = 0;
    $part     = 0;
    $no       = 0;

    $percent  = 0;
    $replace_name = array('\'', '"');

    foreach ($folders as $folder) {
        $files  = array_slice(scandir($html_dir.$folder), 2);
        $parent = mysql_real_escape_string(DecodeCodepage1251($folder));
        echo('<hr><h3>Категория &laquo;'.$parent.'&raquo;:</h3>');
        BuferOut();


        foreach ($files as $file) {
            $products = '';
            $html     = file_get_contents($html_dir.$folder.'/'.$file);
            $category = mysql_real_escape_string(DecodeCodepage1251(substr($file, 0, -5)));

            preg_match_all(
                SLASH.CODE_PATTERN.NAME_PATTERN.PRICE_PATTERN.SLASH.'U',
                $html,
                $products,
                PREG_PATTERN_ORDER
            );

            $rowcount = count($products[1]);
            echo('<p>обновление цен категории <b>&laquo;'.$category.'&raquo;</b>...(<i>'.$rowcount.' товаров</i>)</p>');
            BuferOut();

            for ($i = 0; $i < $rowcount; $i++) {
                set_time_limit(0);
                $code = mysql_real_escape_string(DecodeCodepage($products[1][$i]));
                $name
                           = mysql_real_escape_string(trim(str_replace($replace_name, '', DecodeCodepage($products[2][$i]))));
                $price     = (double)$products[3][$i];
                $price_usd = $price / 21.60;
                $productID = GetValue('productID', 'Conc__divoland', "code = '$code'");

                if ($productID) {
                    $query
                        = "
                                UPDATE  Conc__divoland
                                SET     parent    = '$parent',
                                        category  = '$category',
                                        name      = '$name',
                                        price_uah = $price,
                                        price_usd = $price_usd,
                                        enabled   = 1
                                WHERE   productID =  $productID
                    ";
                    $res = mysql_query($query) or die(mysql_error()."<br>$query");
                } else {
                    $query
                        = "
                                INSERT INTO Conc__divoland
                                         (parent, category, code, name, price_uah, price_usd)
                                VALUES   ('$parent', '$category', '$code', '$name', $price, $price_usd)
                    ";
                    $res = mysql_query($query) or die(mysql_error()."<br>$query");
                    $error++;
                }

            }
            $no++;
        }
        $part++;
        $progress = round(($part / $parts * 100), 0, PHP_ROUND_HALF_DOWN);

        if ($progress > $percent) {
            $percent = $progress.'%';
            ProgressBar('products', $percent);
            BuferOut();
        }
    }
    ProgressBar('products', $percent, true);
    echo('<hr><span style="color:blue;">Обработано '.$no.' товаров</span><br><br>Новых '.$error.' товаров</span><br>');

    // Оптимизация таблиц
	$query = 'UPDATE `Conc__divoland` SET `parent`='', `category`='' WHERE `enabled`=0';
    $res = mysql_query($query) or die(mysql_error()."<br>$query");
	
    $query = 'OPTIMIZE TABLE `Conc__divoland`, `Conc_search__divoland`';
    $res = mysql_query($query) or die(mysql_error()."<br>$query");
    mysql_close();

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