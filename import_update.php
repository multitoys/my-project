<?php
    /**
     * Created by PhpStorm.
     * User: Gololobov
     * Date: 02.11.2015
     * Time: 18:23
     */

    $start = microtime(true);
    ini_set('display_errors', true);
    define('DIR_ROOT', $_SERVER['DOCUMENT_ROOT'].'/published/SC/html/scripts');

    include_once(DIR_ROOT.'/includes/init.php');
    include_once(DIR_CFG.'/connect.inc.wa.php');
    include(DIR_FUNC.'/setting_functions.php');
    include(DIR_FUNC.'/import_functions.php');

    $DB_tree = new DataBase();
    $DB_tree->connect(SystemSettings::get('DB_HOST'), SystemSettings::get('DB_USER'), SystemSettings::get('DB_PASS'));
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

    //----------- Импорт товаров -----------
    $filename0 = 'products.csv';
    $filename = $archive_dir.$filename0;
    $file = file($filename);
    $rowcount = count($file);
    echo('<h1>Импорт товаров ...('.($rowcount - 1).')</h1><hr><br>');
    echo(<<<TAG
		<div id='products' >
			<div style='width:0px;'>&nbsp;</div>
		</div>
TAG
    );

    if (!$rowcount) {
        die(showError("CSV-файл ($filename0) не содержит данных! (rowcount = $rowcount)"));
    }
    
    // Идентификатор категории "Все бонусные товары"
    define('CAT_BONUS_ID', getValue('categoryID', 'SC_categories', "name_ru = 'Все бонусные товары'"));
    // Идентификатор категории "новинки"
    define('CAT_NOVINKI_ID', getValue('categoryID', 'SC_categories', "name_ru = 'Новинки'"));
    
    $no = 0;
    $row = 0;
    $error = 0;
    $new_id = 0;
    $percent = 0;
    $query_time = 0;
    $query_conc = 0;

    if (($handle = fopen($filename, 'r')) !== false) {
        while (($data = fgetcsv($handle, 1000, ';')) !== false) {
            set_time_limit(0);
            if ($row !== 0) {
                $no++;
                $ua = $data[0];
                $id = $data[1];
                $code = mysql_real_escape_string(decodeCodepage1251($data[2]));
                $catid = is_numeric($data[3]) ? $data[3] : 1;
                $price = $data[4];
                $bonus = $data[5];
                $name = mysql_real_escape_string(trim(decodeCodepage1251($data[6])));
                $skidka = $data[7];
                $new_postup = $data[8];
                $ostatok = mysql_real_escape_string(decodeCodepage1251($data[9]));

                // Исправление цен
                if (!is_numeric($price)) {
                    $price = str_replace(',', '.', $price);
                    $price = preg_replace('/[^0-9.]/', '', $price);
                }

                $ua = ($ua > 1) ? 1 : 0;
                $skidka = is_numeric($skidka) ? $skidka : 0;
                $bonus = is_numeric($bonus) ? $bonus : 0;
                $new_postup = ($new_postup > 0) ? $new_postup : 0;
                $slug = str2Url("$name").'-'.$id;
                
                if (is_numeric($id) && strlen($id) > 4) {
                    $productID = getValue('productID', 'SC_products', "code_1c = '$id'");
                    if (!$productID) {
                        $query
                            = "
                                INSERT INTO SC_products
                                       (
                                           categoryID, 
                                           Price, 
                                           Bonus, 
                                           in_stock, 
                                           items_sold, 
                                           enabled, 
                                           product_code, 
                                           sort_order, 
                                           ordering_available, 
                                           slug, 
                                           name_ru, 
                                           skidka , 
                                           code_1c, 
                                           ostatok, 
                                           ukraine
                                       )
                                VALUES
                                       (
                                            $catid,
                                            $price,
                                            $bonus, 
                                            200, 
                                            0,
                                            1, 
                                            '$code', 
                                            $new_postup, 
                                            1, 
                                            '$slug', 
                                            '$name', 
                                            $skidka, 
                                            '$id', 
                                            '$ostatok',
                                            $ua
                                        )
                               ";
                        
                        $res = mysql_query($query) or die(mysql_error()."<br>$query");
                        $productID = mysql_insert_id();
                        $new_id++;
                    } else {
                        $query
                            = "
                                UPDATE SC_products
                                SET 
                                    categoryID              = $catid,
                                    Price                   = $price,
                                    Bonus                   = $bonus,
                                    in_stock                = 200,
                                    enabled                 = 1,
                                    product_code            = '$code',
                                    sort_order              = $new_postup,
                                    ordering_available      = 1,
                                    slug                    = '$slug',
                                    name_ru                 = '$name',
                                    skidka                  = $skidka ,
                                    ostatok                 = '$ostatok',
                                    ukraine                 = $ua
                                WHERE
                                    productID               = $productID
                            ";
                        $res = mysql_query($query) or die(mysql_error()."<br>$query");

                        $query = "DELETE FROM SC_category_product WHERE productID = $productID";
                        $res = mysql_query($query) or die(mysql_error()."<br>$query");

                        $query = "DELETE FROM SC_product_list_item WHERE list_id!='hitu' AND productID = $productID";
                        $res = mysql_query($query) or die(mysql_error()."<br>$query");
                    }

                    //--------- Дополнительные категории и Списки продуктов---------//

                    if ($bonus) {
                        $query = "INSERT INTO SC_category_product VALUES ($productID, ".CAT_BONUS_ID.", 1)";
                        $res = mysql_query($query) or die(mysql_error()."<br>$query");
                    }
                    if ($new_postup && !$ua) {
                        $query
                            = "INSERT INTO SC_product_list_item (list_id, productID, priority, date) VALUES ('newitemspostup', $productID, 1, $new_postup)";
                        $res = mysql_query($query) or die(mysql_error()."<br>$query");
                    }

                    $progress = round(($no / ($rowcount - 2) * 100), -1, PHP_ROUND_HALF_DOWN);
                    if ($progress > $percent) {
                        $percent = $progress.'%';
                        progressBar('products', $percent, false);
                    }
                    buferOut();
                } else {
                    echo(showError("Неверный id ($id) (строка $row) - <span style='color:red;'>позиция проигнорирована</span>"));
                    $error++;
                }
            } else {
                $usd = $data[0];
                if (!is_numeric($usd)) {
                    $usd = preg_replace('/[^0-9.]/', '', $usd);
                }
                echo('<span style="color:red;"><br>курс доллара - '.$usd.' грн</span>');
                $query = "UPDATE SC_currency_types SET currency_value = 1/$usd WHERE CID = 10";
                $res = mysql_query($query) or die(mysql_error()."<br>$query");
            }
            $row++;
        }
        fclose($handle);
    } else {
        die("<br>'Ошибка в при открытии файла: $filename0");
    }
    progressBar('products', $percent, false, true);
    echo('<span style="color:blue;"><br>Обработано '.($no - $error).' товаров</span><br><span style="color:green;">Новых '.$new_id.' товаров</span><br>');

    $query = 'UPDATE SC_products SET enabled = FALSE, items_sold = 0 WHERE in_stock = 100 AND ostatok NOT LIKE \'%заказ%\'';
    $res = mysql_query($query) or die(mysql_error()."<br>$query");

    $query = 'UPDATE SC_products SET in_stock =100 WHERE in_stock = 200';
    $res = mysql_query($query) or die(mysql_error()."<br>$query");

    $query = 'UPDATE SC_products SET `min_order_amount` = `box` WHERE `zakaz` = 1 OR `minorder` = 1';
    $res = mysql_query($query) or die(mysql_error()."<br>$query");

    $query = 'DELETE FROM SC_category_product WHERE categoryID != 100001 AND priority = 0';
    $res = mysql_query($query) or die(mysql_error()."<br>$query");

    $query = 'UPDATE SC_category_product SET priority = 0';
    $res = mysql_query($query) or die(mysql_error()."<br>$query");

    $query = 'DELETE FROM SC_product_list_item WHERE  list_id!=\'hitu\' AND priority = 0 ';
    $res = mysql_query($query) or die(mysql_error()."<br>$query");
    $query = 'UPDATE SC_product_list_item SET priority = 0';
    $res = mysql_query($query) or die(mysql_error()."<br>$query");

    $new_count = 500;
    $query = "SELECT productID FROM SC_products WHERE enabled = 1 ORDER BY code_1c DESC LIMIT $new_count";
    $res = mysql_query($query) or die('Ошибка в запросе: '.mysql_error().'<br>'.$query);

    while ($ids = mysql_fetch_object($res)) {
        $id = $ids->productID;
        $query = "INSERT INTO SC_category_product VALUES (".$id.", ".CAT_NOVINKI_ID.", 0)";
        $result = mysql_query($query) or die(mysql_error()."<br>$query");
        $new_count--;
    }
    
    $query = "DELETE FROM `SC_auth_log` WHERE `Login` = 'sales'";
    $res = mysql_query($query) or die(mysql_error()."<br>$query");
    
    $query = 'TRUNCATE TABLE  Search_products';
    $res = mysql_query($query) or die(mysql_error()."<br>$query");

    $query
        = 'INSERT INTO Search_products (categoryID, code_1c, product_code, name_ru, Price,enabled)
           SELECT  categoryID, code_1c, product_code, name_ru, Price, enabled FROM SC_products  WHERE in_stock = 100';
    $res = mysql_query($query) or die(mysql_error()."<br>$query");

    $query
        = 'DELETE FROM `SC_shopping_cart_items`
           WHERE `productID` IS NULL';
    $res = mysql_query($query) or die(mysql_error()."<br>$query");

    // Оптимизация таблиц
    $query
        = '
            OPTIMIZE TABLE 
                `SC_auth_log`, 
                `SC_categories`, 
                `SC_category_product`, 
                `SC_currency_types`, 
                `SC_customers`, 
                `SC_customer_addresses`, 
                `SC_customer_reg_fields_values`, 
                `SC_ordered_carts`, 
                `SC_orders`, 
                `SC_order_status_changelog`, 
                `SC_products`, 
                `SC_product_list_item`, 
                `SC_product_pictures`, 
                `SC_shopping_carts`, 
                `SC_shopping_cart_items`, 
                `SC_subscribers`, 
                `Search_products`, 
                `Conc__kindermarket`, 
                `Conc__divoland`, 
                `Conc__dreamtoys`, 
                `Conc__mixtoys`, 
                `Conc_search__kindermarket`, 
                `Conc_search__divoland`, 
                `Conc_search__dreamtoys`, 
                `Conc_search__mixtoys`
          '
    ;
    $res = mysql_query($query) or die(mysql_error()."<br>$query");

    mysql_close();
    
    // Удаление временных файлов
//    removeDirRec($_SERVER['DOCUMENT_ROOT'].'/upload/');

    echo(<<<TAG
	<br>
	<div id='end'>Импорт завершен!</div>
TAG
    );
    debugging($start);