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

TAG
        );

        $DB_tree = new DataBase();
        $DB_tree->connect(SystemSettings::get('DB_HOST'), SystemSettings::get('DB_USER'), SystemSettings::get('DB_PASS'));
        $DB_tree->selectDB(SystemSettings::get('DB_NAME'));

//        define('TABLE_CONC', 'Conc__mixtoys');
        $TABLE_CONC = 'Conc__mixtoys';
        $usd = getValue('currency_value', 'Conc__competitors', 'CCID = 3');
        $archive_dir = $_SERVER['DOCUMENT_ROOT'].'/upload/';
        //$dest_dir = $_SERVER['DOCUMENT_ROOT'].'/temp/import/';

        //----------- Импорт товаров -----------
        $filename = $archive_dir.'mixtoys.csv';
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
        $replace_pcode = array('Техно ', 'ПЦ ', 'BANBAO ', 'DEFA ', 'JIXIN ', 'JT ', 'PS ', 'T ', 'Дів ', 'Збр', 'Звi ', 'КВ ', 'Кон ', 'Лял ', 'Маш ', 'Муз ', 'Мяк ', 'Мяч ', 'Нас ', 'Нем ', 'ОР ', 'Різ ', 'Ст ', 'Хло ', 'ЮН ', 'ЯБ ');
        $replace_name = array('\'', '"');
        if (($handle = fopen($filename, 'r')) !== false) {
            //        DeleteRow($TABLE_CONC);
            updateValue($TABLE_CONC, 'enabled = 0');

            while (($data = fgetcsv($handle, 1000, ';')) !== false) {
                set_time_limit(0);
                if ($row === 0) {
                    for ($i = 0; $i <= 6; $i++) {
                        $column = decodeCodepage1251($data[$i]);

                        switch ($column) {
                            case  'Категория':
                                $pa = $i;
                                break;
                            case   'Группа':
                                $ca = $i;
                                break;
                            case   'Код':
                                $co = $i;
                                break;
                            case   'Артикул':
                                $ar = $i;
                                break;
                            case   'Наименование':
                                $na = $i;
                                break;
                            case   'Цена':
                                $pr = $i;
                                break;
                        }
                    }
                    $row++;
                    continue;
                }
                //        $row++;
                $parent = mysql_real_escape_string(decodeCodepage1251($data[$pa]));
                $category = mysql_real_escape_string(decodeCodepage1251($data[$ca]));
                if (!$category) {
                    $category = $parent;
                }
                $code = mysql_real_escape_string(decodeCodepage1251($data[$co]));
                $product_code = mysql_real_escape_string(str_replace($replace_pcode, '', decodeCodepage1251($data[$ar])));
                $name = mysql_real_escape_string(trim(str_replace($replace_name, '', decodeCodepage1251($data[$na]))));
                $price = (double)$data[$pr];

                // Исправление цен
                if (!is_numeric($price)) {
                    $price = preg_replace('/[^0-9.]/', '', $price);
                }
                $price_usd = $price / $usd;
                
                $values = 'productID, price_uah, price_usd';
                $dataDB = getValues($values, $TABLE_CONC, "code = '$code'");
                $date_modified = date("Y-m-d");
                
                if (!$dataDB->productID) {
                    
                    $query = "
                        INSERT INTO $TABLE_CONC
                                    (parent, category, code, product_code, name, price_uah, price_usd, date_modified)
                        VALUES      ('$parent', '$category', '$code', '$product_code', '$name', $price, $price_usd, '$date_modified')
                      ";
                    $res = mysql_query($query) or die(mysql_error()."<br>$query");
                    $no++;
                    
                } else {
                    
                    $date_modified = ($dataDB->price_uah == $price || $dataDB->price_usd == $price_usd )? '' : ", date_modified='$date_modified'";
                    
                    $query = "
                            UPDATE
                                    $TABLE_CONC

                            SET
                                    parent = '$parent',
                                    category = '$category',
                                    product_code = '$product_code',
                                    name = '$name',
                                    price_uah = $price,
                                    price_usd = $price_usd,
                                    enabled   = 1
                                    $date_modified

                            WHERE
                                    productID = $dataDB->productID
                          ";
                    $res = mysql_query($query) or die(mysql_error()."<br>$query");
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
        //DeleteRow($TABLE_CONC, 'price_uah = 0.00');
        $query = "UPDATE $TABLE_CONC SET parent='', category='' WHERE enabled=0";
        $res = mysql_query($query) or die(mysql_error()."<br>$query");

        $query = "OPTIMIZE TABLE $TABLE_CONC";
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