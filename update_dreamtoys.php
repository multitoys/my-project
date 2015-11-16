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

    $b = false;

    if (isset($_GET) &&
        array_key_exists('new', $_GET) &&
        $_GET['new'] > 0
    ) {
        $b = true;
    }

    if ($a) {

        echo(<<<TAG

            <html>
                <head>
                    <link rel='stylesheet' type='text/css' href='css/import.css'>
                </head>
                <body>

TAG
        );

        $DB_tree = new DataBase();
        $DB_tree->connect(SystemSettings::get('DB_HOST'), SystemSettings::get('DB_USER'), SystemSettings::get('DB_PASS'));
        $DB_tree->selectDB(SystemSettings::get('DB_NAME'));
        
        $usd = getValue('currency_value', 'Conc__currency', 'CCID = 2');
        $archive_dir = $_SERVER['DOCUMENT_ROOT'].'/upload/';

        //----------- Импорт товаров ----------- 
        if ($b) {
            switch ($_GET['new']) {
                case '3':
                    $filename = $archive_dir . 'dreamtoys_akcia.csv';
                    break;
                case '2':
                    $filename = $archive_dir . 'dreamtoys_new_postup.csv';
                    break;
                case '1':
                    $filename = $archive_dir . 'dreamtoys_new.csv';
                    break;
            }
        } else {
            $filename = $archive_dir.'dreamtoys.csv';
        }
        $file = file($filename);
        $rowcount = count($file);

        echo('<h1>Импорт товаров ...('.$rowcount.')</h1><hr><br>');
        echo(<<<'TAG'

            <div id='products' >
                <div style='width:0px;'></div>
            </div>

TAG
        );

        if (!$rowcount) {
            die(showError("CSV-файл ($filename) не содержит данных! (rowcount = $rowcount)"));
        }
        $no = 0;
        $row = 0;
        $percent = 0;
        $replace_name = array('\'', '"');
        $non_insert = array(
            'Батарейки', 'Карнавальные костюмы, акс', 'Новогодние игрушки', 'Одежда', 'Спортивные товары VD-sport',
            'Хоз. товары', 'Товары для праздников'
        );
        if (($handle = fopen($filename, 'r')) !== false) {

            if (!$b) {
                updateValue('Conc__dreamtoys', 'enabled = 0');
            }

            while (($data = fgetcsv($handle, 1000, ';')) !== false) {
                set_time_limit(0);

                if ($row === 0) {
                    for ($i = 0; $i <= 6; $i++) {
                        $column = decodeCodepage1251($data[$i]);

                        switch ($column) {
                            case  'категория':
                                $pa = $i;
                                break;
                            case   'Группа':
                                $ca = $i;
                                break;
                            case   'Код':
                                $co = $i;
                                break;
                            case   'наименование':
                                $na = $i;
                                break;
                            case   'цена':
                                $pr = $i;
                                break;
                        }
                    }
                    $row++;
                    continue;
                }
                $parent = str_replace($non_insert, '', decodeCodepage1251($data[$pa]));
                $category = mysql_real_escape_string(decodeCodepage1251($data[$ca]));
                $pos = strpos($parent, 'ТМ ');
                $parent = mysql_real_escape_string($parent);
                if ($pos !== false && $parent) {
                    $category = $parent;
                    $parent = mysql_real_escape_string('По Брендам');
                }
                $code = mysql_real_escape_string(decodeCodepage1251($data[$co]));
                $name_pr_code = explode('|', decodeCodepage1251($data[$na]));
                $non_name = $name_pr_code[0];
                $name = '';
                $product_code = '';
                $price = (double)$data[$pr];
                if ($non_name[0] !== '<') {
                    $name = mysql_real_escape_string(trim(str_replace($replace_name, '', $name_pr_code[0])));
                    $product_code = mysql_real_escape_string(trim($name_pr_code[1]));
                }

                if ($parent && $name) {
                    // Исправление цен
                    if (!is_numeric($price)) {
                        $price = preg_replace('/[^0-9.]/', '', $price);
                    }
                    $price_usd = $price / $usd;

                    $productID = getValue('productID', 'Conc__dreamtoys', "code = '$code'");

                    if (!$productID) {
                        $query
                            = "
                            INSERT INTO Conc__dreamtoys
                                        (parent, category, code, product_code, name, price_uah, price_usd)
                            VALUES      ('$parent', '$category', '$code', '$product_code', '$name', $price, $price_usd)
                          ";
                        $res = mysql_query($query) or die(mysql_error()."<br>$query");
                        $no++;
                    } else {
                        $query
                            = "
                            UPDATE  Conc__dreamtoys

                            SET     parent = '$parent',
                                    category = '$category',
                                    product_code = '$product_code',
                                    name = '$name',
                                    price_uah = $price,
                                    price_usd = $price_usd,
                                    enabled   = 1

                            WHERE  productID = $productID";
                        $res = mysql_query($query) or die(mysql_error()."<br>$query");
                    }
                }
                $row++;
                $progress = round(($row / ($rowcount - 1) * 100), 0, PHP_ROUND_HALF_DOWN);
                if ($progress > $percent) {
                    $percent = $progress.'%';
                    progressBar('products', $percent, $start2);
                    buferOut();
                }
            }
            fclose($handle);
        }
        echo(
            '<span style="color:blue;">
         <br>Обработано '.$row.' товаров</span><br>
         <br>Новых '.$no.' товаров</span><br>
        ');

        // Оптимизация таблиц
        $query = "UPDATE Conc__dreamtoys SET parent='', category='' WHERE enabled=0";
        $res = mysql_query($query) or die(mysql_error()."<br>$query");

        $query = 'OPTIMIZE TABLE Conc__dreamtoys';
        $res = mysql_query($query) or die(mysql_error()."<br>$query");
        mysql_close();

        progressBar('products', $percent, true);
        echo('
        <br>
          <div id=\'end\'>Импорт завершен!</div>
      ');
        debugging($start);
    } else {
        //        var_dump($_SESSION);
        die('NO LOGIN SESSION');
    }