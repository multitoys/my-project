<?php
    /**
     * Created by PhpStorm.
     * User: multi
     * Date: 20.09.2015
     * Time: 21:58
     */

    $start = microtime(true);
    ini_set('display_errors', true);

    define('DIR_ROOT', $_SERVER['DOCUMENT_ROOT'].'/published/SC/html/scripts');
    define('DIR_COMPETITORS', $_SERVER['DOCUMENT_ROOT'].'/popup/search_by_conc');

    include_once(DIR_ROOT.'/includes/init.php');
    include_once(DIR_CFG.'/connect.inc.wa.php');
    include(DIR_FUNC.'/setting_functions.php');
    include_once(DIR_COMPETITORS.'/curl_competitors.php');
    include(DIR_COMPETITORS.'/grandtoys_categories.php');

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
    $usd = GetValue('currency_value', 'Conc__currency', 'CCID = 5');

    define('SLASH', '|');
    define('NAME_PATTERN', '<div\s+class="block[0-9]*?"[^<>]*?>[^<>]*?<div\s+class="product-overview-image"[^<>]*?>[^<>]*?<div\s+id="img-radius"[^<>]*?>[^<>]*?<a[^<>]*?>[^<>]*?</a>[^<>]*?</div>[^<>]*?</div>[^<>]*?<div\s+class="product-title"[^<>]*?>[^<>]*?<a[^<>]*?>[\s]*([^<>]+?)[\s]*</a>[^<>]*?</div>[^<>]*?');
    define('PRICE_PATTERN', '<div\s+class="product-price"[^<>]*?>[\s]+([0-9.]+?)[^<>]*?');
    define('CODE_PATTERN', '<div\s+class="available"[^<>]*?>[^<>]*?</div>[^<>]*?<form[^<>]*?>[^<>]*?<input\s+type="[^"]*?"\s+value="([0-9]+?)"[^<>]*?>');

    $headers = array
    (
        ''
    );

    define('URL_COMPETITORS', 'http://gtoys.com.ua');
    define('URL_POSTFIX', '/page_size');
    define('EXT', '.html');

    $login_url = URL_COMPETITORS.'/ru/user/login';
    $refferer = URL_COMPETITORS;
    postAuth($login_url, 'UserLogin[username]=Elenna&UserLogin[password]=0675230623', $headers);

    UpdateValue('Conc__grandtoys', 'enabled = 0');

    //    DeleteRow('Conc__grandtoys');
    //    DeleteRow('Conc_search__grandtoys');

    $no = 0;
    $new = 0;
    $part = 0;
    $percent = 0;
    $products_cnt = 2000;
    $replace_name = array('&quot;', '\'', '"');

    foreach ($categories as $parent => $cats) {

        set_time_limit(0);
        $category_urls = $cats;

        foreach ($category_urls as $category => $url) {

            $category_url = URL_COMPETITORS.$url.URL_POSTFIX.$products_cnt;
            $filename = Rus2Translit(trim($category));
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
            BuferOut();

            $category = mysql_real_escape_string($category);

            for ($j = 0; $j < $rowcount; $j++) {
                set_time_limit(0);
                $name = mysql_real_escape_string(trim(str_replace($replace_name, '', DecodeCodepage($products[1][$j]))));
                $price = (double)$products[2][$j];
                $code = mysql_real_escape_string(DecodeCodepage($products[3][$j]));
                $productID = GetValue('productID', 'Conc__grandtoys', "code = '$code'");
                $price_usd = $price / $usd;

                if ($productID) {
                    $query
                        = "
                                    UPDATE  Conc__grandtoys
                                    SET     parent       = '$parent',
                                            category     = '$category',
                                            name         = '$name',
                                            price_uah    = $price,
                                            price_usd    = $price_usd,
                                            enabled      = 1
                                    WHERE   productID    =  $productID
                        ";
                    $res = mysql_query($query) or die(mysql_error()."<br>$query");
                } else {
                    $query
                        = "
                                INSERT INTO Conc__grandtoys
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
    $query = "UPDATE Conc__grandtoys SET parent='', category='' WHERE enabled=0";
    $res = mysql_query($query) or die(mysql_error()."<br>$query");

    $query = 'OPTIMIZE TABLE `Conc__grandtoys`, `Conc_search__grandtoys`';
    $res = mysql_query($query) or die(mysql_error()."<br>$query");
    mysql_close();

    echo('
        <br>
          <div id=\'end\'>Импорт завершен!</div>
      ');

    Debugging($start);