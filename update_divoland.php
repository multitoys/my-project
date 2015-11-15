<?php

    $start = microtime(true);
    ini_set('display_errors', true);
    define('DIR_ROOT', $_SERVER['DOCUMENT_ROOT'].'/published/SC/html/scripts');

    include_once(DIR_ROOT.'/includes/init.php');
    include_once(DIR_CFG.'/connect.inc.wa.php');
    include(DIR_FUNC.'/setting_functions.php');
    include(DIR_FUNC.'/import_functions.php');
    
    $a = false;

    if (isset($_SESSION) &&
        array_key_exists('log', $_SESSION) &&
        $_SESSION['log'] === 'sales'
    ) {
        $a = true;
    }

    if ($a) {

        echo(<<<TAG

			<html>
				<head>
					<link rel='stylesheet' type='text/css' href='css/import.css'>
				</head>
				<body>
                <div id='products'>
                    <div style='width:0;'>&nbsp;</div>
                </div>

TAG
        );

        $DB_tree = new DataBase();
        $DB_tree->connect(SystemSettings::get('DB_HOST'), SystemSettings::get('DB_USER'), SystemSettings::get('DB_PASS'));
        $DB_tree->selectDB(SystemSettings::get('DB_NAME'));

        define('CODE_PATTERN', '<a[^<>]*?class="zoom[\s]+nyroModal"[\s]+href="/prodimages/normal/[\w]+/([\w]+?)\.jpg"[^<>]*?>[^<>]*?</a>[^<>]*?</div>[^<>]*?');
        define('NAME_PATTERN', '<a[\s]+class="name"[^<>]*?>(.*)</a>[^<>]*?');
        define('PRICE_PATTERN', '<a[^<>]*?class="price[\s]+nyroModal">[\s]*?([^<>]*?)<small>');
        define('SLASH', '|');

        $usd = getValue('currency_value', 'Conc__currency', 'CCID = 1');
        updateValue('Conc__divoland', 'enabled = 0');

        $html_dir = $_SERVER['DOCUMENT_ROOT'].'/popup/search_by_conc/divoland/';
        $folders = array_slice(scandir($html_dir), 2);
        $parts = count($folders);
        $files = array();
        $new = 0;
        $part = 0;
        $no = 0;

        $percent = 0;
        $replace_name = array('\'', '"');

        foreach ($folders as $folder) {
            $files = array_slice(scandir($html_dir.$folder), 2);
            $parent = mysql_real_escape_string(decodeCodepage1251($folder));
            echo('<hr><h3>Категория &laquo;'.$parent.'&raquo;:</h3>');
            buferOut();

            foreach ($files as $file) {
                $products = '';
                $html = file_get_contents($html_dir.$folder.'/'.$file);
                $category = mysql_real_escape_string(decodeCodepage1251(substr($file, 0, -5)));

                preg_match_all(
                    SLASH.CODE_PATTERN.NAME_PATTERN.PRICE_PATTERN.SLASH.'U',
                    $html,
                    $products,
                    PREG_PATTERN_ORDER
                );

                $rowcount = count($products[1]);
                echo('<p>обновление цен категории <b>&laquo;'.$category.'&raquo;</b>...(<i>'.$rowcount.' товаров</i>)</p>');
                buferOut();

                for ($i = 0; $i < $rowcount; $i++) {
                    set_time_limit(0);
                    $code = mysql_real_escape_string(decodeCodepage($products[1][$i]));
                    $name
                        = mysql_real_escape_string(trim(str_replace($replace_name, '', decodeCodepage($products[2][$i]))));
                    $price = (double)$products[3][$i];
                    $price_usd = $price / $usd;
                    $productID = getValue('productID', 'Conc__divoland', "code = '$code'");

                    if ($productID) {
                        $query
                            = "
                                UPDATE  Conc__divoland
                                SET     parent    = '$parent',
                                        category  = '$category',
                                        name      = '$name',
                                        price_uah = $price,
                                        price_usd = $price_usd,
                                        enabled   = 1
                                WHERE   productID =  $productID
                    ";
                        $res = mysql_query($query) or die(mysql_error()."<br>$query");
                    } else {
                        $query
                            = "
                                INSERT INTO Conc__divoland
                                         (parent, category, code, name, price_uah, price_usd)
                                VALUES   ('$parent', '$category', '$code', '$name', $price, $price_usd)
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
                progressBar('products', $percent);
                buferOut();
            }
        }
        progressBar('products', $percent, true);
        echo('<hr><span style="color:blue;">Обработано '.$no.' товаров</span><br><br>Новых '.$new.' товаров</span><br>');

        // Оптимизация таблиц
        $query = "UPDATE Conc__divoland SET parent='', category='' WHERE enabled=0";
        $res = mysql_query($query) or die(mysql_error()."<br>$query");

        $query = 'OPTIMIZE TABLE `Conc__divoland`, `Conc_search__divoland`';
        $res = mysql_query($query) or die(mysql_error()."<br>$query");
        mysql_close();

        echo('
        <br>
          <div id=\'end\'>Импорт завершен!</div>
      ');
        debugging($start);
    } else {
//        var_dump($_SESSION);
        die('NO LOGIN SESSION');
    }