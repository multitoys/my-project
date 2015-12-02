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

include_once(DIR_ROOT . '/includes/init.php');
include_once(DIR_CFG . '/connect.inc.wa.php');
include(DIR_FUNC . '/setting_functions.php');
include_once(DIR_COMPETITORS . '/curl_competitors.php');
include(DIR_COMPETITORS . '/divoland_categories.php');

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
//    $usd = getValue('currency_value', 'Conc__competitors', 'CCID = 1');
    $usd = 22.00;

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
    define('TOKEN', '042c624cbc7137b9031e3166ed72004d');

    $login_url = URL_COMPETITORS . '/default/login';
    $refferer = URL_COMPETITORS;
//    postAuth($login_url, 'fdata[username]=' . LOGIN . '&fdata[password]=' . PASSWORD . '&fdata[city]=' . CITY . '&fdata[_csrf_token]=' . TOKEN, $headers);

    updateValue('Conc__divoland0', 'enabled = 0');

    $no = 0;
    $new = 0;
    $part = 0;
    $percent = 0;
    $replace_name = array('&laquo;', '&raquo;', '&rdquo;', '&ldquo;', '&quot;', '&#039;', '\'', '.', '"', '„');

    foreach ($categories as $parent => $cats) {

        set_time_limit(0);
        $category_urls = $cats;

        foreach ($category_urls as $category) {
            $url = str2Url(rus2translit($category));
            $category_url = URL_COMPETITORS . URL_PREFIX . $url . URL_POSTFIX;
            $filename = rus2Translit(trim($category));
            $filename = DIR_COMPETITORS . '/' . $filename . EXT;
            $products = '';

            readUrl($category_url, $filename, '', $headers);

            $html = file_get_contents($filename);
            preg_match_all(
                SLASH . CODE_PATTERN . NAME_PATTERN . PRICE_PATTERN . SLASH . 'U',
                $html,
                $products,
                PREG_PATTERN_ORDER
            );

            $rowcount = count($products[1]);
            echo('<p>обновление цен категории <b>&laquo;' . $category . '&raquo;</b>...(<i>' . $rowcount . ' товаров</i>)</p>');
            buferOut();

            for ($i = 0; $i < $rowcount; $i++) {
                set_time_limit(0);
                $code = mysql_real_escape_string(decodeCodepage($products[1][$i]));
                $name
                    = mysql_real_escape_string(trim(str_replace($replace_name, '', decodeCodepage($products[2][$i]))));
                $price_usd = (double)$products[3][$i];
                $price = $price_usd / $usd;
                $productID = getValue('productID', 'Conc__divoland0', "code = '$code'");

                if ($productID) {
                    $query
                        = "
                                UPDATE  Conc__divoland0
                                SET     parent    = '$parent',
                                        category  = '$category',
                                        name      = '$name',
                                        price_uah = $price,
                                        price_usd = $price_usd,
                                        enabled   = 1
                                WHERE   productID =  $productID
                    ";
                    $res = mysql_query($query) or die(mysql_error() . "<br>$query");
                } else {
                    $query
                        = "
                                INSERT INTO Conc__divoland0
                                         (parent, category, code, name, price_uah, price_usd)
                                VALUES   ('$parent', '$category', '$code', '$name', $price, $price_usd)
                    ";
                    $res = mysql_query($query) or die(mysql_error() . "<br>$query");
                    $new++;
                }
                $no++;
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
    $query = "UPDATE Conc__divoland0 SET parent='', category='' WHERE enabled=0";
    $res = mysql_query($query) or die(mysql_error() . "<br>$query");

    $query = 'OPTIMIZE TABLE `Conc__divoland0`, `Conc_search__divoland`';
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