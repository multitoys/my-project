<?php
    /**
     * Created by PhpStorm.
     * User: Gololobov
     * Date: 14.09.2015
     * Time: 10:06
     */
    function postAuth($login_url, $auth_data, array $headers)
    {
        $curl = curl_init();
        if (strtolower((substr($login_url, 0, 5)) === 'https')) { // если соединяемся с https
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        }
        curl_setopt($curl, CURLOPT_URL, $login_url);
        if (is_array($headers) && count($headers)) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }
        // откуда пришли на эту страницу
        curl_setopt($curl, CURLOPT_REFERER, $login_url);

        // cURL будет выводить подробные сообщения о всех производимых действиях
        curl_setopt($curl, CURLOPT_VERBOSE, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $auth_data);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (Windows; U; Windows NT 5.0; En; rv:1.8.0.2) Gecko/20070306 Firefox/1.0.0.4');
        //        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.85 Safari/537.36');
        //        curl_setopt($ch, CURLOPT_USERAGENT, "SuperBot!!!");
        curl_setopt($curl, CURLOPT_HEADER, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        //сохранять полученные COOKIE в файл
        curl_setopt($curl, CURLOPT_COOKIEJAR, $_SERVER['DOCUMENT_ROOT'].'/curl/cookie.txt');
        $html = curl_exec($curl);

        // Убеждаемся что произошло перенаправление после авторизации
//        if (strpos($curl, "Location: ru/user/login") == false) die('Login incorrect');

        curl_close($curl);

        return $html;
    }

    // чтение страницы после авторизации

    /**
     * @param        $url
     * @param        $filename
     * @param string $refferer
     *
     * @param array  $headers
     *
     * @return mixed
     */
    function readUrl($url, $filename = '', $refferer = '', array $headers)
    {
        $refferer = ($refferer)? :$url;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        // откуда пришли на эту страницу
        curl_setopt($curl, CURLOPT_REFERER, $refferer);

        if (is_array($headers) && count($headers)) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }
        //запрещаем делать запрос с помощью POST и соответственно разрешаем с помощью GET
        curl_setopt($curl, CURLOPT_POST, 0);
//        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        //отсылаем серверу COOKIE полученные от него при авторизации
        curl_setopt($curl, CURLOPT_COOKIEFILE, $_SERVER['DOCUMENT_ROOT'].'/curl/cookie.txt');
//        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0; WOW64; Trident/4.0; SLCC1)');
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (Windows; U; Windows NT 5.0; En; rv:1.8.0.2) Gecko/20070306 Firefox/1.0.0.4');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $html = curl_exec($curl);
        curl_close($curl);

        if ($filename) {
            $fh = fopen($filename, 'w');
            fwrite($fh, $html);
            fclose($fh);
        }

        return $html;
    }

    //    function postAuth($login_url, $auth_data, $refferer = '', array $headers)
    //    {
    //        $curl = curl_init();
    //        if (strtolower((substr($login_url, 0, 5)) === 'https')) {
    //            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    //            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    //        }
    //        if (is_array($headers) && count($headers)) {
    //            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    //        }
    //        curl_setopt($curl, CURLOPT_URL, $login_url);
    ////        curl_setopt($curl, CURLOPT_REFERER, $refferer);
    //        curl_setopt($curl, CURLOPT_VERBOSE, true);
    //        curl_setopt($curl, CURLOPT_POST, true);
    //        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    ////        curl_setopt($curl, CURLOPT_MAXREDIRS, 5);
    //        curl_setopt($curl, CURLOPT_POSTFIELDS, $auth_data);
    //        curl_setopt($curl, CURLOPT_HEADER, true);
    //        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    //        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.85 Safari/537.36');
    //        curl_setopt($curl, CURLOPT_COOKIEJAR, $_SERVER['DOCUMENT_ROOT'].'/popup/search_by_conc/cookie.txt');
    //
    //        $html = curl_exec($curl);
    //        curl_close($curl);
    //
    //        return $html;
    //    }
    //
    //    function readUrl($url, $filename = '', $refferer = '', array $headers)
    //    {
    //        $refferer = ($refferer) ?: $url;
    //
    //        $curl = curl_init();
    //        if (is_array($headers) && count($headers)) {
    //            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    //        }
    //        curl_setopt($curl, CURLOPT_URL, $url);
    //        curl_setopt($curl, CURLOPT_COOKIEFILE, $_SERVER['DOCUMENT_ROOT'].'/popup/search_by_conc/cookies.txt');
    //        //        curl_setopt($curl, CURLOPT_REFERER, $refferer);
    //        //        curl_setopt($curl, CURLOPT_AUTOREFERER, true);
    //        curl_setopt($curl, CURLOPT_POST, false);
    //        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    //        //        curl_setopt($curl, CURLOPT_MAXREDIRS, 5);
    //        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    //        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.85 Safari/537.36');
    //
    //        $html = curl_exec($curl);
    //        curl_close($curl);
    //
    //        if ($filename) {
    //            $fh = fopen($filename, 'w');
    //            fwrite($fh, $html);
    //            fclose($fh);
    //        }
    //    }

    /*--------- Функции ---------*/
    function showError($msg)
    {
        return print("<div style='color:red; font-size:16px;'>$msg</div>");
    }

    function removeDir($directory)
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

    function deleteRow($table, $condition = '')
    {
        $condition = ($condition)?"WHERE $condition":'';
        $query = "DELETE FROM $table $condition";
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

    function decodeCodeArray1251($array)
    {
        $array_new = array();
        foreach ($array as $arr) {
            $s = 'windows-1251';
            $q = iconv($s, 'UTF-8', $arr);
            $array_new[] = $q;
        }

        return $array_new;
    }

    function rus2Translit($string)
    {
        $converter = array(
            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'e', 'ж' => 'zh', 'з' => 'z',
            'и' => 'i', 'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r',
            'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch', 'ш' => 'sh',
            'щ' => 'sch', 'ь' => '', 'ы' => 'y', 'ъ' => '', 'э' => 'e', 'ю' => 'yu', 'я' => 'ya', 'А' => 'A',
            'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'E', 'Ж' => 'Zh', 'З' => 'Z', 'И' => 'I',
            'Й' => 'Y', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O', 'П' => 'P', 'Р' => 'R', 'С' => 'S',
            'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C', 'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sch',
            'Ь' => '', 'Ы' => 'Y', 'Ъ' => '', 'Э' => 'E', 'Ю' => 'Yu', 'Я' => 'Ya'
        );

        return strtr($string, $converter);
    }

    function str2Url($str)
    {
        $str = rus2Translit($str);
        $str = strtolower($str);
        $str = preg_replace('~[^-a-z0-9_]+~u', '-', $str);
        $str = str_replace("'", '', $str);
        $str = str_replace(" ", '-', $str);
        $str = preg_replace('~\-{2,}~', '-', $str);
        $str = trim($str, '-');

        return $str;
    }
    function progressBar($import_items, $percent, $full = '')
    {
        if ($full === true) {
            $full = 'background-image:linear-gradient(to bottom, #6AFF7D, #00DC08)';
        }
        echo "<script language='javascript'>
                    document.getElementById('$import_items').innerHTML=\"<div style='width:$percent;$full'>$percent</div>\"
              </script>
        ";
    }

    function buferOut($delay_min = 0, $delay_max = 0)
    {
        echo str_repeat(' ', 1024 * 128);
        flush();

        $delay = 0;

        if ($delay_min > 0) {

            $delay = $delay_min;

            if ($delay_max > $delay_min) {

                $delay = mt_rand($delay_min, $delay_max);
            }
        }

        if ($delay) {
            sleep($delay);
        }
    }

    function debugging($start)
    {
        $memoscript = memory_get_usage(true) / 1048576;
//        $memoscript = memory_get_peak_usage(true) / 1048576;
        $time = microtime(true) - $start;
        printf('<br>Time: %.2F seconds', $time);
        printf('<br>Memory: %.2F MB.', $memoscript);
    }