<?php
    /**
     * Created by PhpStorm.
     * User: multi
     * Date: 20.09.2015
     * Time: 21:58
     */
    ini_set('display_errors', true);
    $start = microtime(true);

    define('DIR_ROOT', $_SERVER['DOCUMENT_ROOT'].'/published/SC/html/scripts');
    define('DIR_COMPETITORS', $_SERVER['DOCUMENT_ROOT'].'/popup/search_by_conc');

    include_once(DIR_ROOT.'/includes/init.php');
    include_once(DIR_CFG.'/connect.inc.wa.php');
    include(DIR_FUNC.'/setting_functions.php');
    include_once(DIR_COMPETITORS.'/curl_competitors.php');
    include(DIR_COMPETITORS.'/grandtoys_categories.php');

    $a = false;

    if (isset($_SESSION) &&
        array_key_exists('log', $_SESSION) &&
        $_SESSION['log'] === 'sales'
    ) {
        $a = true;
    }

    if ($a) {
        $DB_tree = new DataBase();
        $DB_tree->connect(SystemSettings::get('DB_HOST'), SystemSettings::get('DB_USER'), SystemSettings::get('DB_PASS'));
        $DB_tree->selectDB(SystemSettings::get('DB_NAME'));

        echo(<<<TAG

			<html>
				<head>
					<link rel='stylesheet' type='text/css' href='http://multitoys.com.ua/css/import.css'>
				</head>
				<body>
                <div id='products'>
                    <div style='width:0;'>&nbsp;</div>
                </div>

TAG
        );
        $usd = getValue('currency_value', 'Conc__competitors', 'CCID = 5');

        define('SLASH', '|');
        define('NAME_PATTERN', '<div\s+class="block[0-9]*?"[^<>]*?>[^<>]*?<div\s+class="product-title"[^<>]*?>[^<>]*?<a[^<>]*?>[\s]*([^<>]+?)[\s]*</a>[^<>]*?</div>[^<>]*?<div\s+class="block_border"[^<>]*?>[^<>]*?<div\s+class="product-overview-image"[^<>]*?>[^<>]*?<div\s+id="img-radius"[^<>]*?>[^<>]*?<a[^<>]*?>[^<>]*?</a>[^<>]*?</div>[^<>]*?</div>[^<>]*?(<div[^<>]*?>[^<>]*?<img[^<>]*?>[^<>]*?</div>[^<>]*?)?');
        define('PRICE_PATTERN', '<div\s+class="product-price"[^<>]*?>[\s]+([0-9.]+?)[^<>]*?');
        define('CODE_PATTERN', '<div\s+class="[^"]*?"[^<>]*?>[^<>]*?</div>[^<>]*?<form[^<>]*?>[^<>]*?<input\s+type="[^"]*?"\s+value="([0-9]+?)"[^<>]*?>');

        $headers = array
        (
            ''
        );

        define('URL_COMPETITORS', 'http://gtoys.com.ua');
        define('URL_POSTFIX', '/page_size');
        define('URL_PREFIX', '/ru/');
        define('EXT', '.html');

        define('LOGIN', 'Elenna');
        define('PASSWORD', '0675230623');
//        define('LOGIN', 'rusmol');
//        define('PASSWORD', '333');
//        define('LOGIN', '973846984');
//        define('PASSWORD', '973846984');

        $price_number = '';
        switch (LOGIN) {
            case 'rusmol':
                $price_number = 2;
                break;
            case '973846984':
                $price_number = 3;
                break;
        }
        
        $login_url = URL_COMPETITORS.URL_PREFIX.'user/login';
        $refferer = URL_COMPETITORS;
        postAuth($login_url, 'UserLogin[username]='.LOGIN.'&UserLogin[password]='.PASSWORD, $headers);

        updateValue('Conc__grandtoys', 'enabled = 0');

        $no = 0;
        $new = 0;
        $part = 0;
        $percent = 0;
        $products_cnt = 1000;
        $replace_name = array('&laquo;', '&raquo;', '&quot;', '\'', '.', '"');

//        $replace = array(',', '.', ')', '(', '\'');
//        $match_str = preg_replace('/\s\s+/', ' ', str_replace('|', ' ', str_replace($replace, ' ', $conc)));

        foreach ($categories as $parent => $cats) {

            set_time_limit(0);
            $category_urls = $cats;

            foreach ($category_urls as $category => $url) {

                $url_postfix = (strpos($url, 'page_size') === false) ? URL_POSTFIX.$products_cnt : '';
                $category_url = URL_COMPETITORS.URL_PREFIX.$url.$url_postfix;
                $filename = rus2Translit(trim($category));
                $filename = DIR_COMPETITORS.'/'.$filename.EXT;
                $products = '';

                readUrl($category_url, $filename, '', $headers);

                $html = file_get_contents($filename);
                preg_match_all(
                    SLASH.NAME_PATTERN.PRICE_PATTERN.CODE_PATTERN.SLASH.'U',
                    $html,
                    $products,
                    PREG_PATTERN_ORDER
                );

                $rowcount = count($products[1]);
                echo('<p>обновление цен категории <b>&laquo;'.$category.'&raquo;</b>...(<i>'.$rowcount.' товаров</i>)</p>');
                buferOut();

                $category = mysql_real_escape_string($category);

                for ($j = 0; $j < $rowcount; $j++) {
                    set_time_limit(0);
                    $name = mysql_real_escape_string(preg_replace('/\s\s+/', ' ', trim(str_replace($replace_name, ' ', decodeCodepage($products[1][$j])))));
                    $price = (double)$products[3][$j];
                    $code = mysql_real_escape_string(decodeCodepage($products[4][$j]));
                    $productID = getValue('productID', 'Conc__grandtoys', "code = '$code'");
                    $price_usd = $price / $usd;

                    if ($productID) {
                        $query
                            = "
                                        UPDATE  Conc__grandtoys
                                        SET     parent       = '$parent',
                                                category     = '$category',
                                                name         = '$name',
                                                price_uah$price_number    =  $price,
                                                price_usd$price_number    =  $price_usd,
                                                enabled      =  1
                                        WHERE   productID    =  $productID
                            ";
                        $res = mysql_query($query) or die(mysql_error()."<br>$query");
                    } else {
                        $query
                            = "
                                    INSERT INTO Conc__grandtoys
                                                (parent, category, code, name, price_uah$price_number, price_usd$price_number)
                                    VALUES      ('$parent', '$category', $code, '$name', $price, $price_usd)
                                  ";
                        $res = mysql_query($query) or die(mysql_error()."<br>$query");
                        $new++;
                    }
                    $no++;
                }
				unlink($filename);
                buferOut(5000);
            }
            $part++;
            $progress = round(($part / $parts * 100), 0, PHP_ROUND_HALF_DOWN);

            if ($progress > $percent) {
                $percent = $progress.'%';
                progressBar('products', $percent);
                buferOut();
            }
        }
        progressBar('products', $percent, true);
        echo('<hr><span style="color:blue;">Обработано '.$no.' товаров</span><br><br>Новых '.$new.' товаров</span><br>');

        // Оптимизация таблиц
        $query = "UPDATE Conc__grandtoys SET parent='', category='' WHERE enabled=0";
        $res = mysql_query($query) or die(mysql_error()."<br>$query");

        $query = 'OPTIMIZE TABLE `Conc__grandtoys`, `Conc_search__grandtoys`';
        $res = mysql_query($query) or die(mysql_error()."<br>$query");
        mysql_close();

        echo('
            <br>
              <div id=\'end\'>Импорт завершен!</div>
          ');

        debugging($start);
    } else {
        var_dump($_SESSION);
        die('NO LOGIN SESSION');
    }