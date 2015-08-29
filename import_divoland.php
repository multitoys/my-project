<?php

    $start = microtime(true);
    ini_set('display_errors', true);
    define('DIR_ROOT', $_SERVER['DOCUMENT_ROOT'] . "/published/SC/html/scripts");
    $DebugMode = false;
    $Warnings = array();
    include_once(DIR_ROOT . '/includes/init.php');
    include_once(DIR_CFG . '/connect.inc.wa.php');
    include(DIR_FUNC . '/setting_functions.php');
    $DB_tree = new DataBase();
    $DB_tree->connect(SystemSettings::get('DB_HOST'), SystemSettings::get('DB_USER'), SystemSettings::get('DB_PASS'));
    $DB_tree->selectDB(SystemSettings::get('DB_NAME'));
    define('VAR_DBHANDLER', 'DBHandler');

    echo("
                <html>
                    <head>
                        <link rel='stylesheet' type='text/css' href='css/import.css'>
                    </head>
                    <body>
           ");

    // Распаковка архива
    $archive_dir = $_SERVER['DOCUMENT_ROOT'] . '/upload/';
    $dest_dir = $_SERVER['DOCUMENT_ROOT'] . '/temp/import/';

    // $zip = new ZipArchive();
    // $fileName = $archive_dir."divoland.zip";

    // if ($zip->open($fileName) !== true) {
    // echo("Error while openning archive file: divoland.zip");
    // exit(1);
    // }

    // RemoveDir($dest_dir);
    // $zip->extractTo($dest_dir);

    // echo("<div id='extract'>Файлы ($zip->numFiles) успешно извлечены!</div><br>");

    //----------- Импорт товаров -----------
    $filename = $dest_dir . 'divoland.csv';
    $file = file($filename);
    $rowcount = count($file);
    echo('<h1>Импорт товаров ...(' . $rowcount . ')</h1><hr><br>');
    echo("
                <div id='products' >
                    <div style='width:0px;'>&nbsp;</div>
                </div>
             ");
    if (!$rowcount) die(ShowError("CSV-файл ($filename) не содержит данных! (rowcount = $rowcount)"));
    $no = 0;
    $row = 0;
    $percent = 0;
    if (($handle = fopen($filename, "r")) !== FALSE) {
        DeleteRow('Conc__divoland');
        while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
            set_time_limit(0);
            $row++;
            $parent = mysql_real_escape_string(DecodeCodepage1251($data[0]));
            $category = mysql_real_escape_string(DecodeCodepage1251($data[1]));
            $code = mysql_real_escape_string(DecodeCodepage1251($data[2]));
            $name = mysql_real_escape_string(trim(DecodeCodepage1251($data[3])));
            $price = (double)$data[4];
            // $special_price = $data[5];
            $url = mysql_real_escape_string(DecodeCodepage1251($data[5]));

            // Исправление цен
            if (!is_numeric($price)) $price = preg_replace('/[^0-9.]/', '', $price);
            // if (!is_numeric($special_price)) $special_price = preg_replace('/[^0-9.]/', '.', $special_price);


    // if (is_numeric($id) && strlen($id)>4){
            $productID = GetValue('productID', 'Conc__divoland', "code = '$code'");

            if (!$productID) {
                $query = "
                            INSERT INTO Conc__divoland
                                     (parent, category, name   , price_usd    , code   , foto   )
                            VALUES   ('$parent', '$category', '$name', $price   , '$code', '$url')";
                $res = mysql_query($query) or die(mysql_error() . "<br>$query");
                $productID = mysql_insert_id();
            } else {

                $query = "
                            UPDATE Conc__divoland
                            SET    parent      = '$parent',
                                   category      = '$category',
                                   name      = '$name',
                                   price_usd = $price,

                                   code      = '$code',
                                   foto       = '$url'
                            WHERE  productID = $productID";
                $res = mysql_query($query) or die(mysql_error() . "<br>$query");
            }


            $no++;
            $progress = round(($no / ($rowcount - 1) * 100), 0, PHP_ROUND_HALF_DOWN);
            if ($progress > $percent) {
                $percent = $progress . "%";
                ProgressBar('products', $percent, $start2);
                BuferOut();
            }
            // }else echo(ShowError("Неверный id ($id) (строка $row) - <span style='color:red;'>позиция проигнорирована</span>"));
        }
        fclose($handle);
    }
    echo('<span style="color:blue;"><br>Обработано ' . $no . ' товаров</span><br>');

    // Оптимизация таблиц
    $query = "OPTIMIZE TABLE `Conc__divoland`";
    $res = mysql_query($query) or die(mysql_error() . "<br>$query");
    mysql_close();

    // Удаление временных файлов
    RemoveDir($_SERVER['DOCUMENT_ROOT'] . '/upload/');

    ProgressBar('products', $percent, false, true);
    echo("
    <br>
      <div id='end'>Импорт завершен!</div>
    ");
    debugging($start);

    /*--------- Функции ---------*/
    function ShowError($msg)
    {
        return "<div style='color:red; font-size:16px;'>$msg</div>";
    }

    function RemoveDir($directory)
    {
        $dir = opendir($directory);
        while (($file = readdir($dir))) {
            if (is_file($directory . "/" . $file)) {
                unlink($directory . "/" . $file);
            } else if (is_dir($directory . "/" . $file) && ($file != ".") && ($file != "..")) {
                RemoveDir($directory . "/" . $file);
            }
        }
        closedir($dir);
        return TRUE;
    }

    function GetValue($what, $table, $condition)
    {
        $query = "SELECT $what FROM $table WHERE $condition LIMIT 1";
        $result = mysql_query($query) or die('Ошибка в запросе: ' . mysql_error() . '<br>' . $query);
        $row = mysql_fetch_row($result);
        return $row[0];
    }

    function UpdateValue($table, $new_value, $condition = false)
    {
        $condition = ($condition) ? "WHERE $condition" : "";
        $query = "UPDATE $table SET $new_value $condition";
        $result = mysql_query($query) or die('Ошибка в запросе: ' . mysql_error() . '<br>' . $query);
        return;
    }

    function DeleteRow($table, $condition = false)
    {
        $condition = ($condition) ? "WHERE $condition" : "";
        $query = "DELETE FROM $table $condition";
        $result = mysql_query($query) or die('Ошибка в запросе: ' . mysql_error() . '<br>' . $query);
        return;
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
            'Э' => 'E', 'Ю' => 'Yu', 'Я' => 'Ya',
        );
        return strtr($string, $converter);
    }

    function Str2Url($str)
    {
        $str = Rus2Translit($str);
        $str = strtolower($str);
        $str = preg_replace('~[^-a-z0-9_]+~u', '-', $str);
        $str = trim($str, "-");
        return $str;
    }

    function ProgressBar($import_items, $percent, $start = false, $full = false)
    {
        // if ($start) {
        // $time = round(((100-$percent)*(microtime(true) - $start)/$percent), 0);
        // // $end = printf('&nbsp;&nbsp;&nbsp;Осталось: %.2F сек.', $time);
        // $end = ' - <small>Осталось: '.$time.' сек.</small>';
        // }
        $full = ($full) ? 'background-image:linear-gradient(to bottom, #6AFF7D, #00DC08);' : '';
        echo '<script language="javascript">
                    document.getElementById("' . $import_items . '").innerHTML="<div style=\"width:' . $percent . ';' . $full . '\">' . $percent . '' . $end . '</div>";
                    </script>';
        return;
    }

    function BuferOut($delay = 0)
    {
        echo str_repeat(' ', 1024 * 128);
        flush();
        usleep($delay);
        return;
    }

    function Debugging($start)
    {
        // $memoscript = memory_get_usage(true)/1048576;
        $memoscript_peak = memory_get_peak_usage(true) / 1048576;
        $time = microtime(true) - $start;
        printf('<br>Скрипт выполнялся: %.2F сек.', $time);
        // printf('<br>Использовано оперативной памяти: %.2F МБ.', $memoscript);
        printf('<br>Пик оперативной памяти: %.2F МБ.', $memoscript_peak);
        return;
    }

?>
</body>
</html>