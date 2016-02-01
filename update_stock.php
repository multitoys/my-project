<?php
    
    if (isset($_GET['pause'])) {
        $pause = (int)$_GET['pause'];
        sleep($pause);
    }
    $start = microtime(true);
    ini_set('display_errors', true);
    define('DIR_ROOT', $_SERVER['DOCUMENT_ROOT'].'/published/SC/html/scripts');
    
    include_once(DIR_ROOT.'/includes/init.php');
    include_once(DIR_CFG.'/connect.inc.wa.php');
    include(DIR_FUNC.'/setting_functions.php');
    include(DIR_FUNC.'/import_functions.php');
    
    $DB_tree = new DataBase();
    $DB_tree->connect(
        SystemSettings::get('DB_HOST'),
        SystemSettings::get('DB_USER'),
        SystemSettings::get('DB_PASS')
    );
    $DB_tree->selectDB(SystemSettings::get('DB_NAME'));
    
    header('Content-Encoding: none', true);
    echo(<<<'TAG'
			<html>
				<head>
					<link rel='stylesheet' type='text/css' href='css/import.css'>
				</head>
				<body>
TAG
    );
    
    //Распаковка архива
    $archive_dir = $_SERVER['DOCUMENT_ROOT'].'/upload/';
    $dest_dir = $_SERVER['DOCUMENT_ROOT'].'/temp/import/';
    
    $zip = new ZipArchive();
    $fileName = $archive_dir.'multi.zip';
    
    if ($zip->open($fileName) !== true) {
        echo('Error while openning archive file: multi.zip');
        exit(1);
    }
    
    removeDirRec($dest_dir);
    
    $zip->extractTo($dest_dir);
    echo("<div id='extract'>Файл ($zip->numFiles) успешно извлечен!</div><br>");
    buferOut();

    //----------- Обновление остатков -----------
    $filename0 = 'products.csv';
    $filename = $dest_dir.$filename0;
    $file = file($filename);
    $rowcount = count($file);
    
    echo('<h1>Обновление остатков ...('.$rowcount.')</h1><hr><br>');
    echo(<<<TAG
		<div id='products' >
			<div style='width:0px;'>&nbsp;</div>
		</div>
TAG
    );
    
    if (!$rowcount) {
        die(showError("CSV-файл ($filename0) не содержит данных! (rowcount = $rowcount)"));
    }
    $no = 0;
    $row = 0;
    $error = 0;
    $percent = 0;
    
    if (($handle = fopen($filename, 'r')) !== false) {
        
        while (($data = fgetcsv($handle, 25, ';')) !== false) {
            
            set_time_limit(0);

            $no++;
            $id = $data[0];
            $ostatok = mysql_real_escape_string(decodeCodepage1251($data[1]));
            
            if (is_numeric($id) && strlen($id) > 4) {
                
                $productID = getValue('productID', 'SC_products', "code_1c = '$id'");
                
                if (!$productID) {
                    
                    echo(showError("Товара с кодом 1С \"$id\" (строка $row) - <span style='color:blue;'>еще нет на сайте!</span>"));
                    $error++;
                    
                } else {
                    
                    $query = "
                                UPDATE SC_products
                                SET in_stock                = 200,
                                    enabled                 = 1,
                                    ostatok                 = '$ostatok'
                                WHERE
                                    productID               = $productID
                            ";
                    $res = mysql_query($query) or die(mysql_error()."<br>$query");
                }

                $progress = round(($no / ($rowcount - 2) * 100), 0, PHP_ROUND_HALF_DOWN);
                
                if ($progress > $percent) {
                    
                    $percent = $progress.'%';
                    progressBar('products', $percent, false);
                }
                
                buferOut();
                
            } else {
                
                echo(showError("Неверный код 1С \"$id\" (строка $row) - <span style='color:red;'>позиция проигнорирована</span>"));
                $error++;
            }
            
            $row++;
        }
        
        fclose($handle);
        
    } else {
        die("<br>'Ошибка в при открытии файла: $filename0");
    }
    
    progressBar('products', $percent, false, true);
    echo('<span style="color:blue;"><br>Обработано '.($no - $error).' товаров</span><br>');
    
    $query = "SELECT productID FROM SC_products WHERE  in_stock=100 AND enabled=1 AND zakaz!=1";
    $res = mysql_query($query) or die('Ошибка в запросе: '.mysql_error().'<br>'.$query);
    
    $products_for_disable = array();
    
    while ($enabled = mysql_fetch_row($res)) {
        $products_for_disable[] = $enabled[0];
    }

    if (count($products_for_disable) > 1) {
        
        $products_for_disable = implode(',', $products_for_disable);
        $query = 'UPDATE SC_products SET enabled = FALSE, items_sold = 0 WHERE productID IN (' . $products_for_disable . ')';
        $res = mysql_query($query) or die(mysql_error() . "<br>$query");

        $query = 'DELETE FROM SC_category_product WHERE productID IN (' . $products_for_disable . ')';
        $res = mysql_query($query) or die(mysql_error() . "<br>$query");

        $query = 'DELETE FROM SC_product_list_item WHERE productID IN (' . $products_for_disable . ')';
        $res = mysql_query($query) or die(mysql_error() . "<br>$query");
    }

    $query = 'UPDATE SC_products SET in_stock =100 WHERE in_stock = 200';
    $res = mysql_query($query) or die(mysql_error()."<br>$query");

    $query = 'DELETE FROM `SC_shopping_cart_items`
              WHERE `productID` IS NULL';
    $res = mysql_query($query) or die(mysql_error()."<br>$query");
    
    // Оптимизация таблиц
    $query = 'OPTIMIZE TABLE 
                    `SC_auth_log`, `SC_categories`, `SC_category_product`, `SC_currency_types`, `SC_customers`, 
                    `SC_customer_addresses`, `SC_customer_reg_fields_values`, `SC_ordered_carts`, `SC_orders`, 
                    `SC_order_status_changelog`, `SC_products`, `SC_product_list_item`, `SC_product_pictures`, 
                    `SC_shopping_carts`, `SC_shopping_cart_items`, `SC_subscribers`, `Search_products`';
    $res = mysql_query($query) or die(mysql_error()."<br>$query");

    mysql_close();

    // Удаление временных файлов
//    removeDirRec($_SERVER['DOCUMENT_ROOT'].'/upload/');
    
    echo(<<<TAG
	<br>
	<div id='end'>Обновление остатков завершено!</div>
TAG
    );
    debugging($start);