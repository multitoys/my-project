<?php
    /**
     * Created by PhpStorm.
     * User: Gololobov
     * Date: 14.09.2015
     * Time: 10:06
     */

    function login($url, $login, $pass, $enter = '')
    {
        $ch = curl_init();
        if (strtolower((substr($url, 0, 5)) == 'https')) { // если соединяемся с https
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        }
        curl_setopt($ch, CURLOPT_URL, $url);

        // откуда пришли на эту страницу
        curl_setopt($ch, CURLOPT_REFERER, $url);

        // cURL будет выводить подробные сообщения о всех производимых действиях
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $login.$pass.$enter);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (Windows; U; Windows NT 5.0; En; rv:1.8.0.2) Gecko/20070306 Firefox/1.0.0.4");
        //        curl_setopt($ch, CURLOPT_USERAGENT, "SuperBot!!!");
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        //сохранять полученные COOKIE в файл
        curl_setopt($ch, CURLOPT_COOKIEJAR, $_SERVER['DOCUMENT_ROOT'].'/cookie.txt');
        $result = curl_exec($ch);

        // Убеждаемся что произошло перенаправление после авторизации
        //        if (strpos($result, "Location: home.php") === false) die('Login incorrect');

        curl_close($ch);

        return $result;
    }

    // чтение страницы после авторизации
    function Read($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        // откуда пришли на эту страницу
        curl_setopt($ch, CURLOPT_REFERER, $url);
        //запрещаем делать запрос с помощью POST и соответственно разрешаем с помощью GET
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        //отсылаем серверу COOKIE полученные от него при авторизации
        curl_setopt($ch, CURLOPT_COOKIEFILE, $_SERVER['DOCUMENT_ROOT'].'/cookie.txt');
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (Windows; U; Windows NT 5.0; En; rv:1.8.0.2) Gecko/20070306 Firefox/1.0.0.4");

        $result = curl_exec($ch);

        curl_close($ch);

        return $result;
    }

    login('http://multitoys.com.ua', 'user_login=sales', '&user_pw=172092', '&enter=1');
    Read('http://multitoys.com.ua/auxpage_new_items');
    //    login('http://dreamtoys.com.ua/index.php?option=com_user&task=login', '&username=detkikonfetki', '&passwd=7777777');
    //    Read('http://dreamtoys.com.ua/index.php?page=shop.browse&category=&option=com_virtuemart&Itemid=1');