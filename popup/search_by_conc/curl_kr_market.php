<?php
    /**
     * Created by PhpStorm.
     * User: Gololobov
     * Date: 16.09.2015
     * Time: 22:10
     */

    function postAuth($login_url, $auth_data, array $headers)
    {
        $curl = curl_init();
        if (strtolower((substr($login_url, 0, 5)) === 'https')) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        }
        curl_setopt($curl, CURLOPT_URL, $login_url);
        if (is_array($headers) && count($headers)) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }
        curl_setopt($curl, CURLOPT_REFERER, $login_url);
        curl_setopt($curl, CURLOPT_VERBOSE, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_MAXREDIRS, 5);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $auth_data);
        //        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (Windows; U; Windows NT 5.0; En; rv:1.8.0.2) Gecko/20070306 Firefox/1.0.0.4');
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.85 Safari/537.36');
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_COOKIEJAR, $_SERVER['DOCUMENT_ROOT'].'/popup/cookie.txt');
        $html = curl_exec($curl);
        curl_close($curl);

        return $html;
    }

    function readUrl($url, $filename = '', $refferer = '', array $headers)
    {
        $refferer = ($refferer) ?: $url;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_REFERER, $refferer);
        curl_setopt($curl, CURLOPT_AUTOREFERER, true);

        if (is_array($headers) && count($headers)) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }
        curl_setopt($curl, CURLOPT_POST, false);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_MAXREDIRS, 5);
        curl_setopt($curl, CURLOPT_COOKIEFILE, $_SERVER['DOCUMENT_ROOT'].'/popup/cookies.txt');
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.85 Safari/537.36');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $html = curl_exec($curl);
        curl_close($curl);

        if ($filename) {
            $fh = fopen($filename, 'w');
            fwrite($fh, $html);
            fclose($fh);
        }
    }

    $headers = array
    (
        ''
    );
    $html_dir = $_SERVER['DOCUMENT_ROOT'].'/popup/search_by_conc/';
    $login_url = 'http://kr-kindermarket.com.ua/auth';
    $refferer = 'http://kr-kindermarket.com.ua/';
    $url = 'http://kr-kindermarket.com.ua/category';
//    $url = 'http://kr-kindermarket.com.ua/category/novie_postupleniya';
    $url = 'http://kr-kindermarket.com.ua/category/tehnok&count_panel=5000';
    $filename = $html_dir.'category.html';
    $html = file_get_contents($filename);

    define('CODE_PATTERN', '<h2>[^<>]*?<a[^<>]*?[\s]+href="/item/[\w]+/([\d])+?)"[^<>]*?>([^<>]*?)</a>[^<>]*?</h2>[^<>]*?</div>[^<>]*?<div[\s]+class="forimgmain"[^<>]*?>[^<>]*?<a[^<>]*?>[^<>]*?<img[^<>]*?>[^<>]*?</a>[^<>]*?</div>[^<>]*?<div[^<>]*?>[^<>]*?</div>[^<>]*?<div[^<>]*?>[^<>]*?<div[\s]+class="price"[^<>]*?>[\s]*?([0-9.]+).*?</b>[\s]+([^<>]+)</small>');

    define('CATEGORY_PATTERN', '<a[^<>]*?class=""[\s]+href="(/category/[\w]+?)"[^<>]*?>([^<>]*?)</a>');

    define('SLASH', '|');

    preg_match_all(
        SLASH.CODE_PATTERN.SLASH.'U',
        $html,
        $categories,
        PREG_PATTERN_ORDER
    );

    $category_count = count($categories[1]);
    echo $category_count;
    var_dump($categories[1]);
    //for ($i = 0; $i < $category_count; $i++) {
    //    set_time_limit(0);
    //    $category = mysql_real_escape_string(DecodeCodepage($categories[2][$i]));
    //readUrl($url, $filename, $refferer, $headers);
    //    $name
    //        = mysql_real_escape_string(trim(str_replace($replace_name, '', DecodeCodepage($products[2][$i]))));
    //    $price     = (double)$products[3][$i];
    //    $price_usd = $price / 21.60;
    //    $productID = GetValue('productID', 'Conc__divoland', "code = '$code'");
    //
    //    if ($productID) {
    //        $query
    //            = "
    //                            UPDATE  Conc__divoland
    //                            SET     parent    = '$parent',
    //                                    category  = '$category',
    //                                    name      = '$name',
    //                                    price_uah = $price,
    //                                    price_usd = $price_usd,
    //                                    enabled   = 1
    //                            WHERE   productID =  $productID
    //                ";
    //        $res = mysql_query($query) or die(mysql_error()."<br>$query");
    //    } else {
    //        $query
    //            = "
    //                            INSERT INTO Conc__divoland
    //                                     (parent, category, code, name, price_uah, price_usd)
    //                            VALUES   ('$parent', '$category', '$code', '$name', $price, $price_usd)
    //                ";
    //        $res = mysql_query($query) or die(mysql_error()."<br>$query");
    //        $error++;
    //    }
    //
    //}