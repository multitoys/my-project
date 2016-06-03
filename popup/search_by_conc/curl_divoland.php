<?php
    /**
     * Created by PhpStorm.
     * User: multi
     * Date: 02.12.2015
     * Time: 22:58
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
        $TABLE_CONC = 'Conc__divoland';
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
        $usd = getValue('currency_value', 'Conc__competitors', 'CCID = 1');
        //$usd = 22.45;

        define('CODE_PATTERN', '<a[^<>]*?class="zoom[\s]+nyroModal"[\s]+href="/prodimages/normal/[\w]+/([\w]+?)\.jpg"[^<>]*?>[^<>]*?</a>[^<>]*?</div>[^<>]*?');
        define('NAME_PATTERN', '<a[\s]+class="name"[^<>]*?>(.*)</a>[^<>]*?');
        define('PRICE_PATTERN', '<a[^<>]*?class="price[\s]+nyroModal">[\s]*?([^<>]*?)<small>');
        define('SLASH', '|');

        $headers = array
        (
            ''
        );

        define('URL_COMPETITORS', 'http://divoland.dp.ua');
        define('URL_PREFIX', '/catalog/');
        define('URL_POSTFIX', '/1/0/name/1');
        define('EXT', '.html');

        define('LOGIN', 'ykolora');
        define('PASSWORD', '4247');
        define('CITY', 'Киев');
        define('TOKEN', '84b835d35a292e18153d942cec17a542');

        $login_url = URL_COMPETITORS . '/default/login';
        $refferer = URL_COMPETITORS;

//        postAuth($login_url, 'fdata[username]=' . LOGIN . '&fdata[password]=' . PASSWORD . '&fdata[city]=' . CITY . '&fdata[_csrf_token]=' . TOKEN, $headers);
        updateValue($TABLE_CONC, 'enabled = 0');
//        updateValue('Conc__divoland', 'enabled = 0');

        $no = 0;
        $new = 0;
        $part = 0;
        $percent = 0;
        $replace_name = array('&laquo;', '&raquo;', '&rdquo;', '&ldquo;', '&quot;', '&#039;', '\'', '.', '"', '„');
        $refferer = '';
        $categories = include(DIR_COMPETITORS . '/divoland_categories.php');
        $parts = count($categories);
        foreach ($categories as $parent => $cats) {

            set_time_limit(0);
            $category_urls = $cats;

            foreach ($category_urls as $category) {
                $url = str2Url(rus2translit($category));
                $category_url = URL_COMPETITORS . URL_PREFIX . $url . URL_POSTFIX;
                $filename = rus2Translit(trim($category));
                $filename = DIR_CURL . '/' . $filename . EXT;
                $products = '';

                readUrl($category_url, $filename, $refferer, $headers);
                $refferer = $category_url;

                $html = file_get_contents($filename);
                preg_match_all(
                    SLASH . CODE_PATTERN . NAME_PATTERN . PRICE_PATTERN . SLASH . 'U',
                    $html,
                    $products,
                    PREG_PATTERN_ORDER
                );

                $rowcount = count($products[1]);

                if (!$rowcount || $rowcount > 5000) {

                    showError($category);

                } else {

//                    updateValue($TABLE_CONC, 'enabled = 0');


                    echo('<p>обновление цен категории <b>&laquo;' . $category . '&raquo;</b>...(<i>' . $rowcount . ' товаров</i>)</p>');
                    buferOut();

                    for ($i = 0; $i < $rowcount; $i++) {
                        set_time_limit(0);
                        $code = mysql_real_escape_string(decodeCodepage($products[1][$i]));
                        $name
                            = mysql_real_escape_string(trim(str_replace($replace_name, '', decodeCodepage($products[2][$i]))));
                        $price = (double)$products[3][$i];
                        $price_usd = $price / $usd;
//                        $productID = getValue('productID', $TABLE_CONC, "code = '$code'");
                        $values = array('productID', 'price_uah', 'price_usd');
                        $data = getValues($values, $TABLE_CONC, "code = '$code'");
                        $date_modified = date("Y-m-d");

                        if ($data->productID) {

                            $date_modified = ($data->price_uah == $price || $data->price_usd == $price_usd) ? '' : ", date_modified='$date_modified'";

                            $query
                                = "
                                UPDATE  $TABLE_CONC
                                SET     parent    = '$parent',
                                        category  = '$category',
                                        name      = '$name',
                                        price_uah = $price,
                                        price_usd = $price_usd,
                                        enabled   = 1
                                        $date_modified
                                WHERE   productID    =  $data->productID
                                ";
                            $res = mysql_query($query) or die(mysql_error() . "<br>$query");
                        } else {
                            $query
                                = "
                                        INSERT INTO $TABLE_CONC
                                                    (parent, category, code, name, price_uah, price_usd, date_modified)
                                        VALUES      ('$parent', '$category', '$code', '$name', $price, $price_usd, '$date_modified')
                    ";
                            $res = mysql_query($query) or die(mysql_error() . "<br>$query");
                            $new++;
                        }
                        $no++;
                    }
//                    unlink($filename);
                    buferOut(2, 5);
                }
            }
            $part++;
            $progress = round(($part / $parts * 100), 0, PHP_ROUND_HALF_DOWN);

            if ($progress > $percent) {
                $percent = $progress . '%';
                progressBar('products', $percent);
                buferOut();
            }
        }
        progressBar('products', $percent, true);
        echo('<hr><span style="color:blue;">Обработано ' . $no . ' товаров</span><br><br>Новых ' . $new . ' товаров</span><br>');

        // Оптимизация таблиц
        $query = "UPDATE $TABLE_CONC SET parent='', category='' WHERE enabled=0";
        $res = mysql_query($query) or die(mysql_error() . "<br>$query");

        $query = "OPTIMIZE TABLE $TABLE_CONC, `Conc_search__divoland`";
        $res = mysql_query($query) or die(mysql_error() . "<br>$query");
        mysql_close();

        echo('
        <br>
          <div id=\'end\'>Импорт завершен!</div>
      ');
        debugging($start);
    } else {
        die('NO LOGIN SESSION');
    }