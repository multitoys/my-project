<?php
    /**
     * Created by PhpStorm.
     * User: Gololobov
     * Date: 16.09.2015
     * Time: 22:10
     */

    $start = microtime(true);
    ini_set('display_errors', true);

    define('DIR_ROOT', $_SERVER['DOCUMENT_ROOT'].'/published/SC/html/scripts');
    define('DIR_COMPETITORS', $_SERVER['DOCUMENT_ROOT'].'/popup/search_by_conc');

    include_once(DIR_ROOT.'/includes/init.php');
    include_once(DIR_CFG.'/connect.inc.wa.php');
    include(DIR_FUNC.'/setting_functions.php');
    include_once(DIR_COMPETITORS.'/curl_competitors.php');

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

        define('SLASH', '|');
        define('CATEGORY_PATTERN', '<a[^<>]*?class=""[\s]+href="(/category/[^"]+?)"[^<>]*?>\s+([^<>]*?)\s*</a>');
        define('CODE_PATTERN', '<div[\s]+class="forimgmain">[^<>]*?<a[\s]+href="/item/([\d]+?)"[^<>]*?>[^<>]*?');
        define('NAME_PATTERN', '<img[\s]+alt="([^"]*?)"[^<>]*?>[^<>]*?</a>[^<>]*?</div>[^<>]*?<div[\s]+class="h5[\s]+m0"[^<>]*?>[^<>]*?</div>[^<>]*?<div[\s]+class="fll[\s]+wpc50"[^<>]*?>[^<>]*?');
        define('PRICE_PATTERN', '<div[\s]+class="price"[^<>]*?>[\s]+([0-9.]+?)');
        define('ART_PATTERN', '[^<>]*?<div[\s]+class="green-color"[^<>]*?>[^<>]*?</div>[^<>]*?</div>[^<>]*?</div>[^<>]*?<div[\s]+class="flr[\s]+wpc50[\s]+tar"[^<>]*?>[^<>]*?<small>[^<>]*?<b>[^<>]*?</b>[\s]+([^<>]*?)</small>');

        $headers = array
        (
            ''
        );

        $login_url = 'http://kr-kindermarket.com.ua/auth';
        $refferer = 'http://kr-kindermarket.com.ua/';
        postAuth($login_url, 'email=alenkiselev%40mail.ru&password=bondarenko&login=', $headers);

        $url = 'http://kr-kindermarket.com.ua/category';
        $filename = DIR_COMPETITORS.'/category.html';
        readUrl($url, $filename, $refferer, $headers);

        updateValue('Conc__kindermarket', 'enabled = 0');

        $html = file_get_contents($filename);

        preg_match_all(
            SLASH.CATEGORY_PATTERN.SLASH.'U',
            $html,
            $categories,
            PREG_PATTERN_ORDER
        );
        $category_count = count($categories[1]);

        define('URL_COMPETITORS', 'http://kr-kindermarket.com.ua');
        define('URL_POSTFIX', '&count_panel=5000');
        define('EXT', '.html');

        //    DeleteRow('Conc__kindermarket');
        //    DeleteRow('Conc_search__kindermarket');
        updateValue('Conc__kindermarket', 'enabled = 0');
        $no = 0;
        $new = 0;
        $part = 0;
        $percent = 0;
        $replace_name = array('&laquo;', '&raquo;', '&rdquo;', '&ldquo;', '&quot;', '&#039;', '\'', '"', '.');

        for ($i = 0; $i < $category_count; $i++) {

            set_time_limit(0);
            $category_url = $categories[1][$i];
            $category_url = URL_COMPETITORS.$category_url.URL_POSTFIX;
            $category = trim(decodeCodepage($categories[2][$i]));
            $category_file = rus2Translit($category);
            $filename = DIR_COMPETITORS.'/'.$category_file.EXT;
            $products = '';

            readUrl($category_url, $filename, '', $headers);

            $html = file_get_contents($filename);
            preg_match_all(
                SLASH.CODE_PATTERN.NAME_PATTERN.PRICE_PATTERN.ART_PATTERN.SLASH.'U',
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
                $code = mysql_real_escape_string(decodeCodepage($products[1][$j]));
                $name
                    = mysql_real_escape_string(trim(str_replace($replace_name, '', decodeCodepage($products[2][$j]))));
                $price = (double)$products[3][$j];
                $price_usd = $price / 21.40;
                $product_code = mysql_real_escape_string(trim($products[4][$j]));
                $productID = getValue('productID', 'Conc__kindermarket', "code = '$code'");

                if ($productID) {
                    $query
                        = "
                                UPDATE  Conc__kindermarket
                                SET     parent       = '$category',
                                        category     = '$category',
                                        product_code = '$product_code',
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
                            INSERT INTO Conc__kindermarket
                                        (parent, category, code, product_code, name, price_uah, price_usd)
                            VALUES      ('$category', '$category', '$code', '$product_code', '$name', $price, $price_usd)
                          ";
                    $res = mysql_query($query) or die(mysql_error()."<br>$query");
                    $new++;
                }
                $no++;
            }
            $part++;
            $progress = round(($part / $parts * 100), 0, PHP_ROUND_HALF_DOWN);

            if ($progress > $percent) {
                $percent = $progress.'%';
                progressBar('products', $percent);
            }
            unlink($filename);
            buferOut(10000);
        }
        progressBar('products', $percent, true);
        echo('<hr><span style="color:blue;">Обработано '.$no.' товаров</span><br><br>Новых '.$new.' товаров</span><br>');

        // Оптимизация таблиц
        $query = "UPDATE Conc__kindermarket SET parent='', category='' WHERE enabled=0";
        $res = mysql_query($query) or die(mysql_error()."<br>$query");

        $query = 'OPTIMIZE TABLE `Conc__kindermarket`, `Conc_search__kindermarket`';
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