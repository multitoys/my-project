<?php
    /**
     * Created by PhpStorm.
     * User: multi
     * Date: 20.09.2015
     * Time: 21:58
     */
    ini_set('display_errors', true);
    $start = microtime(true);

    define('DIR_ROOT', $_SERVER['DOCUMENT_ROOT'] . '/published/SC/html/scripts');
    define('DIR_COMPETITORS', $_SERVER['DOCUMENT_ROOT'] . '/popup/search_by_conc');
    define('DIR_CURL', $_SERVER['DOCUMENT_ROOT'] . '/curl');

    include_once(DIR_ROOT . '/includes/init.php');
    include_once(DIR_CFG . '/connect.inc.wa.php');
    include(DIR_FUNC . '/setting_functions.php');
    include_once(DIR_COMPETITORS . '/curl_competitors.php');

    $auth_multi = false;

    if (isset($_SESSION) &&
        array_key_exists('log', $_SESSION) &&
        $_SESSION['log'] === 'sales'
    ) {
        $auth_multi = true;
    }

    if (!$auth_multi) {
        die('NO LOGIN SESSION');
    } else {

        $only_new = true;
        $cat_file = 'add';

        $DB_tree = new DataBase();
        $DB_tree->connect(SystemSettings::get('DB_HOST'), SystemSettings::get('DB_USER'), SystemSettings::get('DB_PASS'));
        $DB_tree->selectDB(SystemSettings::get('DB_NAME'));
        $TABLE_CONC = 'Conc__grandtoys';
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

        $usd = getValue('currency_value', 'Conc__competitors', 'CCID = 4');

        define('SLASH', '|');
        define('NAME_PATTERN', '<div\s+class="block[0-9]*?"[^<>]*?>[^<>]*?<span\s+class="sticker"[^<>]*?>[^<>]*?</span>[^<>]*?<div\s+class="product-overview-image"[^<>]*?>[^<>]*?<div\s+id="img-radius"[^<>]*?>[^<>]*?<a[^<>]*?>(<img[^<>]*?>[^<>]*?)?[^<>]*?</a>[^<>]*?</div>[^<>]*?</div>[^<>]*?(<div[^<>]*?>[^<>]*?<img[^<>]*?>[^<>]*?</div>[^<>]*?)?<div\s+class="product-title"[^<>]*?>[^<>]*?<a[^<>]*?>[\s]*([^<>]+?)[\s]*</a>[^<>]*?</div>[^<>]*?<div\s+class="block_border"[^<>]*?>[^<>]*?');
        define('PRICE_PATTERN', '<div\s+class="product-price"[^<>]*?>[^<>]*?<h2>([0-9.]+?)[^<>]*?</h2>[^<>]*?(<span\s+class="price_usd">[^<>]*?</span>)?');
        define('CODE_PATTERN', '<div\s+class="[^"]*?"[^<>]*?>[^<>]*?</div>[^<>]*?<form[^<>]*?>[^<>]*?<input\s+type="[^"]*?"\s+value="([0-9]+?)"[^<>]*?>');

        $headers = array
        (
            ''
        );
        
        define('EXT', '.html');

        $no = 0;
        $new = 0;
        $part = 0;
        $percent = 0;
        $products_cnt = 1000;
        $replace_name = array('&laquo;', '&raquo;', '&rdquo;', '&ldquo;', '&quot;', '&#039;', '\'', '.', '"', '„', '&amp;');
        $categories = include(DIR_COMPETITORS . '/grandtoys_' . $cat_file . '.php');
        $parts = count($categories);

        foreach ($categories as $category => $url) {

            set_time_limit(0);

            $filename = DIR_CURL . '/' . $category . EXT;
            $products = '';
            $html = file_get_contents($filename);

            preg_match_all(
                SLASH . NAME_PATTERN . PRICE_PATTERN . CODE_PATTERN . SLASH . 'U',
                $html,
                $products,
                PREG_PATTERN_ORDER
            );


            $rowcount = count($products[1]);

            if (!$rowcount) {

                showError($category);

            } else {

                echo('<p>обновление цен категории <b>&laquo;' . $category . '&raquo;</b>...(<i>' . $rowcount . ' товаров</i>)</p>');
                buferOut();

                $category = mysql_real_escape_string($category);

                for ($j = 0; $j < $rowcount; $j++) {
                    set_time_limit(0);
                    $name = mysql_real_escape_string(preg_replace('/\s\s+/', ' ', trim(str_replace($replace_name, ' ', decodeCodepage($products[3][$j])))));
                    $price = (double)$products[4][$j];
                    $code = mysql_real_escape_string(decodeCodepage($products[6][$j]));
                    $price_usd = $price / $usd;
//                        $productID = getValue('productID', $TABLE_CONC, "code = '$code'");
                    $values = 'productID, price_uah, price_usd';
                    $dataDB = getValues($values, $TABLE_CONC, "code = '$code'");
                    $date_modified = date("Y-m-d");

                    if ($dataDB->productID) {

                        $date_modified = ($dataDB->price_uah == $price || $dataDB->price_usd == $price_usd) ? '' : ", date_modified='$date_modified'";

                        $query
                            = "
                                        UPDATE  $TABLE_CONC
                                        SET     parent       = '$category',
                                                category     = '$category',
                                                name         = '$name',
                                                price_uah$price_number    =  $price,
                                                price_usd$price_number    =  $price_usd,
                                                enabled      =  1
                                                $date_modified
                                        WHERE   productID    =  $dataDB->productID
                            ";
                        $res = mysql_query($query) or die(mysql_error() . "<br>$query");
                    } else {
                        $query
                            = "
                                    INSERT INTO $TABLE_CONC
                                                (parent, category, code, name, price_uah$price_number, price_usd$price_number, date_modified)
                                    VALUES      ('$category', '$category', '$code', '$name', $price, $price_usd, '$date_modified')
                                  ";
                        $res = mysql_query($query) or die(mysql_error() . "<br>$query");
                        $new++;
                    }
                    $no++;
                }
                buferOut(0);
                $part++;
                $progress = round(($part / $parts * 100), 0, PHP_ROUND_HALF_DOWN);

                if ($progress > $percent) {
                    $percent = $progress . '%';
                    progressBar('products', $percent);
                    buferOut();
                }
            }
        }
        progressBar('products', $percent, true);
        echo('<hr><span style="color:blue;">Обработано ' . $no . ' товаров</span><br><br>Новых ' . $new . ' товаров</span><br>');

        // Оптимизация таблиц
        $query = "OPTIMIZE TABLE $TABLE_CONC, `Conc_search__grandtoys`";
        $res = mysql_query($query) or die(mysql_error() . "<br>$query");
        mysql_close();

        echo('
            <br>
              <div id=\'end\'>Импорт завершен!</div>
          ');

        debugging($start);
    }