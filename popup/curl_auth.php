<?php
    /**
     * Created by PhpStorm.
     * User: Gololobov
     * Date: 14.09.2015
     * Time: 10:06
     */

    include_once($_SERVER['DOCUMENT_ROOT'].'/published/SC/html/scripts/classes/class.simple_html_dom.php');

    /**
     * @param       $url
     * @param       $auth_data
     * @param array $headers
     *
     * @return mixed
     */
    function postAuth($url, $auth_data, array $headers)
    {
        $curl = curl_init();
        if (strtolower((substr($url, 0, 5)) === 'https')) { // если соединяемся с https
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        }
        curl_setopt($curl, CURLOPT_URL, $url);
        if (is_array($headers) && count($headers)) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }
        // откуда пришли на эту страницу
        curl_setopt($curl, CURLOPT_REFERER, $url);

        // cURL будет выводить подробные сообщения о всех производимых действиях
        curl_setopt($curl, CURLOPT_VERBOSE, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $auth_data);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (Windows; U; Windows NT 5.0; En; rv:1.8.0.2) Gecko/20070306 Firefox/1.0.0.4');
        //        curl_setopt($ch, CURLOPT_USERAGENT, "SuperBot!!!");
        curl_setopt($curl, CURLOPT_HEADER, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        //сохранять полученные COOKIE в файл
        curl_setopt($curl, CURLOPT_COOKIEJAR, $_SERVER['DOCUMENT_ROOT'].'/popup/cookie.txt');
        $html = curl_exec($curl);

        // Убеждаемся что произошло перенаправление после авторизации
        //        if (strpos($result, "Location: home.php") === false) die('Login incorrect');

        curl_close($curl);

        return $html;
    }

    // чтение страницы после авторизации

    /**
     * @param        $url
     * @param        $filename
     * @param string $referer
     *
     * @param array  $headers
     *
     * @return mixed
     */
    function readUrl($url, $filename = '', $referer = '', array $headers)
    {
        $referer = ($referer) ?: $url;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        // откуда пришли на эту страницу
        curl_setopt($curl, CURLOPT_REFERER, $referer);

        if (is_array($headers) && count($headers)) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }
        //запрещаем делать запрос с помощью POST и соответственно разрешаем с помощью GET
        curl_setopt($curl, CURLOPT_POST, 0);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        //отсылаем серверу COOKIE полученные от него при авторизации
        curl_setopt($curl, CURLOPT_COOKIEFILE, $_SERVER['DOCUMENT_ROOT'].'/popup/cookie.txt');
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

    //function curlInFile {
    //    $ch = curl_init('www.rambler.ru');
    //    $fp = fopen('tmp/page.002', "w");
    //    curl_setopt($ch, CURLOPT_URL, "http://www.rambler.ru");
    //    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.9) Gecko/20100824 Firefox/3.6.9");
    //    $headers = array
    //    (
    //        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
    //        'Accept-Language: ru,en-us;q=0.7,en;q=0.3',
    //        'Accept-Encoding: gzip,deflate',
    //        'Accept-Charset: windows-1251,utf-8;q=0.7,*;q=0.7'
    //    );
    //    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    //    curl_setopt($ch, CURLOPT_FILE, $fp);
    //    curl_setopt($ch, CURLOPT_HEADER, 0);
    //    curl_setopt($ch, CURLOPT_REFERER, "http://www.rambler.ru");
    //    curl_setopt($ch, CURLOPT_COOKIEFILE, "cookie.txt");
    //    curl_setopt($ch, CURLOPT_COOKIEJAR, "cookie.txt");
    //    $result = curl_exec($ch);
    //    curl_close($ch);
    //}

    //$headers = array
    //(
    //    'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
    //    'Accept-Language: ru,en-us;q=0.7,en;q=0.3',
    //    'Origin: http://multitoys.com.ua'
    //);
    $headers = array
    (
        ''
    );
    //    postAuth('http://gtoys.com.ua/ru/user/login', 'UserLogin[username]=Elenna&UserLogin[password]=0675230623', $headers);
    postAuth('http://multitoys.com.ua', 'user_login=sales&user_pw=172092&enter=1', $headers);
    //file_put_contents($_SERVER['DOCUMENT_ROOT'].'/popup/1.txt', readUrl('http://multitoys.com.ua/cart', $headers, $url));

    //    readUrl('http://gtoys.com.ua/ru/shop/order/create', $headers, $url);
    readUrl('http://multitoys.com.ua/cart', $_SERVER['DOCUMENT_ROOT'].'/popup/1/2.html', $url, $headers);
    //    postAuth('http://dreamtoys.com.ua/index.php?option=com_user&task=login', '&username=detkikonfetki', '&passwd=7777777');
    //    readUrl('http://dreamtoys.com.ua/index.php?page=shop.browse&category=&option=com_virtuemart&Itemid=1');