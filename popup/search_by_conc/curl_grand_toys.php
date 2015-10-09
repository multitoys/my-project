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
        $usd = GetValue('currency_value', 'Conc__currency', 'CCID = 7');

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
        define('EXT', '.html');

//        define('LOGIN', 'Elenna');
//        define('PASSWORD', '0675230623');
        define('LOGIN', '973846984');
        define('PASSWORD', '973846984');
        //define('LOGIN', 'rusmol');
        //define('PASSWORD', '333');

        $login_url = URL_COMPETITORS.'/ru/user/login';
        $refferer = URL_COMPETITORS;
        //postAuth($login_url, 'UserLogin[username]='.LOGIN.'&UserLogin[password]='.PASSWORD, $headers);

        UpdateValue('Conc__grandtoys3', 'enabled = 0');

        $no = 0;
        $new = 0;
        $part = 0;
        $percent = 0;
        $products_cnt = 2000;
        $replace_name = array('&laquo;', '&raquo;', '&quot;', '\'', '"');

        $replace = array(',', '.', ')', '(', '\'');
        $match_str = preg_replace('/\s\s+/', ' ', str_replace('|', ' ', str_replace($replace, ' ', $conc)));

        foreach ($categories as $parent => $cats) {

            set_time_limit(0);
            $category_urls = $cats;

            foreach ($category_urls as $category => $url) {

                $url_postfix = (strpos($url, 'shop') === false) ? URL_POSTFIX.$products_cnt : '';
                $category_url = URL_COMPETITORS.$url.$url_postfix;
                $filename = Rus2Translit(trim($category));
                $filename = DIR_COMPETITORS.'/'.$filename.EXT;
                $products = '';

                //readUrl($category_url, $filename, '', $headers);

                $html = file_get_contents($filename);
                preg_match_all(
                    SLASH.NAME_PATTERN.PRICE_PATTERN.CODE_PATTERN.SLASH.'U',
                    $html,
                    $products,
                    PREG_PATTERN_ORDER
                );

                $rowcount = count($products[1]);
                echo('<p>обновление цен категории <b>&laquo;'.$category.'&raquo;</b>...(<i>'.$rowcount.' товаров</i>)</p>');
                BuferOut();

                $category = mysql_real_escape_string($category);

                for ($j = 0; $j < $rowcount; $j++) {
                    set_time_limit(0);
                    $name = mysql_real_escape_string(preg_replace('/\s\s+/', ' ', trim(str_replace($replace_name, ' ', DecodeCodepage($products[1][$j])))));
                    $price = (double)$products[3][$j];
                    $code = mysql_real_escape_string(DecodeCodepage($products[4][$j]));
                    $productID = GetValue('productID', 'Conc__grandtoys3', "code = '$code'");
                    $price_usd = $price / $usd;

                    if ($productID) {
                        $query
                            = "
                                        UPDATE  Conc__grandtoys3
                                        SET     parent       = '$parent',
                                                category     = '$category',
                                                name         = '$name',
                                                price_uah    =  $price,
                                                price_usd    =  $price_usd,
                                                enabled      =  1
                                        WHERE   productID    =  $productID
                            ";
                        $res = mysql_query($query) or die(mysql_error()."<br>$query");
                    } else {
                        $query
                            = "
                                    INSERT INTO Conc__grandtoys3
                                                (parent, category, code, name, price_uah, price_usd)
                                    VALUES      ('$parent', '$category', $code, '$name', $price, $price_usd)
                                  ";
                        $res = mysql_query($query) or die(mysql_error()."<br>$query");
                        $new++;
                    }
                    $no++;
                }
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
        echo('<hr><span style="color:blue;">Обработано '.$no.' товаров</span><br><br>Новых '.$new.' товаров</span><br>');

        // Оптимизация таблиц
        $query = "UPDATE Conc__grandtoys3 SET parent='', category='' WHERE enabled=0";
        $res = mysql_query($query) or die(mysql_error()."<br>$query");

        $query = 'OPTIMIZE TABLE `Conc__grandtoys3`, `Conc_search__grandtoys3`';
        $res = mysql_query($query) or die(mysql_error()."<br>$query");
        mysql_close();

        echo('
            <br>
              <div id=\'end\'>Импорт завершен!</div>
          ');

        Debugging($start);
    } else {
        var_dump($_SESSION);
        die('NO LOGIN SESSION');
    }