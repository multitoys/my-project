<?php
    /**
     * Created by PhpStorm.
     * User: Gololobov
     * Date: 22.10.2015
     * Time: 9:32
     */

    function showError($msg)
    {
        return "<div style='color:red; font-size:16px;'>$msg</div>";
    }

    function removeDirRec($directory)
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

    function getValue($what, $table, $condition)
    {
        $query = "SELECT $what FROM $table WHERE $condition LIMIT 1";
        $result = mysql_query($query) or die('Ошибка в запросе: '.mysql_error().'<br>'.$query);
        $row = mysql_fetch_row($result);

        return $row[0];
    }

    function updateValue($table, $new_value, $condition = '')
    {
        $condition = ($condition)?"WHERE $condition":'';
        $query = "UPDATE $table SET $new_value $condition";
        $result = mysql_query($query) or die('Ошибка в запросе: '.mysql_error().'<br>'.$query);
    }

    function decodeCodepage($text)
    {
        $s = mb_detect_encoding($text);
        $q = iconv($s, 'UTF-8', $text);

        return $q;
    }

    function decodeCodepage1251($text)
    {
        $s = 'windows-1251';
        $q = iconv($s, 'UTF-8', $text);

        return $q;
    }

    function rus2Translit($string)
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
            'ь' => '', 'ы' => 'y', 'ъ' => '',
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
            'Ь' => '', 'Ы' => 'Y', 'Ъ' => '',
            'Э' => 'E', 'Ю' => 'Yu', 'Я' => 'Ya'
        );

        return strtr($string, $converter);
    }

    function str2Url($str)
    {
        $str = rus2Translit($str);
        $str = strtolower($str);
        $str = preg_replace('~[^-a-z0-9_]+~u', '-', $str);
        $str = str_replace("'", '', $str);
        $str = preg_replace('~\-{2,}~', '-', $str);
        $str = trim($str, '-');

        return $str;
    }

    function progressBar($import_items, $percent, $start = false, $full = false)
    {
        $end = '';
        if ($start) {
            $time = round(((100 - $percent) * (microtime(true) - $start) / $percent), 0);
            $end = ' - <small>Осталось: '.$time.' сек.</small>';
        }

        $success = '';
        if ($full === true) {
            $success = 'background-image:linear-gradient(to bottom, #6AFF7D, #00DC08)';
        }
        echo "<script language='javascript'>
                    document.getElementById('$import_items').innerHTML=\"<div style='width:$percent;$success'>$percent $end</div>\"
              </script>
        ";
    }

    function buferOut($delay = 0)
    {
        echo str_repeat(' ', 256);
        flush();
        usleep($delay);
    }

    function debugging($start)
    {
        $memoscript_peak = memory_get_peak_usage(true) / 1048576;
        $time = microtime(true) - $start;
        printf('<br>Time: %.4F sec.', $time);
        printf('<br>Memory: %.4F MB.<br>', $memoscript_peak);
    }

    function deleteRow($table, $condition = '')
    {
        $condition = ($condition)?"WHERE $condition":"";
        $query = "DELETE FROM $table $condition";
        $result = mysql_query($query) or die('Ошибка в запросе: '.mysql_error().'<br>'.$query);
    }

    function optimizeTable($table)
    {
        $query = "OPTIMIZE TABLE $table";
        $res = mysql_query($query) or die(mysql_error()."<br>$query");
    }

    function csvToArray($filename = '', $length = 0, $delimiter = ';')
    {
        if (!file_exists($filename) || !is_readable($filename))
            return false;

        $header = null;
        $csv = array();
        
        if (($handle = fopen($filename, 'r')) !== false) {
            $no = 0;
            while (($data = fgetcsv($handle, $length, $delimiter)) !== false) {
                if ($no !== 0) {
                    
                    $ua = $data[0];
                    $id = $data[1];
                    $code = mysql_real_escape_string(decodeCodepage1251($data[2]));
                    $catid = is_numeric($data[3]) ? $data[3] : 1;
                    $price = $data[4];
                    $bonus = $data[6];
                    $name = mysql_real_escape_string(trim(decodeCodepage1251($data[7])));
                    $skidka = $data[8];
                    $hit = $data[9];
                    $new = $data[10];
                    $new_postup = $data[11];
                    $akcia = $data[12];
                    $ostatok = mysql_real_escape_string(decodeCodepage1251($data[13]));
                    $doza = $data[15];
                    $box = $data[18];
                    $minorder = $data[21];
                    $oldprice = $data[22];
                    $zakaz = $data[26];
                    $brand = mysql_real_escape_string(decodeCodepage1251($data[27]));
                    $purchase = $data[28];

                    if (!is_numeric($price)) {
                        $price = preg_replace('/[^0-9.]/', '', $price);
                    }
                    if (!is_numeric($oldprice)) {
                        $oldprice = preg_replace('/[^0-9.]/', '', $oldprice);
                    }

                    if (!is_numeric($purchase)) {
                        $purchase = preg_replace('/[^0-9.]/', '', $purchase);
                    }

                    $ua = ($ua > 1) ? 1 : 0;
                    $skidka = is_numeric($skidka) ? $skidka : 0;
                    $bonus = is_numeric($bonus) ? $bonus : 0;
                    $hit = ($hit > 0) ? $hit : 0;
                    $new = ($new > 0) ? 7 : 5;
                    $new_postup = ($new_postup > 0) ? $new_postup : 0;
                    $akcia = ($akcia > 0) ? 1 : 0;
                    $akcia_skidka = ($akcia > 0) ? (1 - $price / $oldprice) * 100 : 0;
                    $akcia_skidka = is_numeric($akcia_skidka) ? $akcia_skidka : 0;
                    $akcia_bally = $bonus / $price;
                    $akcia_bally = is_numeric($akcia_bally) ? $akcia_bally : 0;
                    $akcia_bally = ($akcia_bally > 2) ? 1 : 0;
                    $doza = is_numeric($doza) ? $doza : 0;
                    $box = is_numeric($box) ? $box : 0;
                    $oldprice = ($oldprice > $price) ? $oldprice : 0;
                    $minorder = ($minorder > 0) ? 1 : 0;
                    $zakaz = ($zakaz > 0) ? 1 : 0;
                    $slug = str2Url("$name").'-'.$id;

                    $csv[$data[1]] = array(
                        'categoryID'              => $catid,
                        'purchase'                => $purchase,
                        'Price'                   => $price,
                        'Bonus'                   => $bonus,
                        'in_stock'                => 200,
                        'items_sold'              => $hit,
                        'enabled'                 => 1,
                        'list_price'              => $oldprice,
                        'akcia'                   => $akcia,
                        'akcia_skidka'            => $akcia_skidka,
                        'product_code'            => $code,
                        'sort_order'              => $new_postup,
                        'ordering_available'      => 1,
                        'slug'                    => $slug,
                        'name_ru'                 => $name,
                        'skidka'                  => $skidka ,
                        'ostatok'                 => $ostatok,
                        'ukraine'                 => $ua,
                        'doza'                    => $doza,
                        'box'                     => $box,
                        'zakaz'                   => $zakaz,
                        'brand'                   => $brand,
                        'eproduct_available_days' => $new
                    );
                }
                $no++;
            }
            fclose($handle);
        }

        return $csv;
    }