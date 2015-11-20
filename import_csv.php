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
    echo("<div id='extract'>Файлы ($zip->numFiles) успешно извлечены!</div><br>");
    
    //----------- Импорт покупателей -----------
    $filename0 = 'clients.csv';
    $filename = $dest_dir.$filename0;
    $file = file($filename);
    $rowcount = count($file);
    if (!$rowcount) {
        showError("CSV-файл ($filename0) не содержит данных! (rowcount = $rowcount)");
    }
    if (($handle = fopen($filename, 'r')) !== false) {
        echo('<h1>Импорт покупателей ...('.$rowcount.')</h1><hr><br>');
        $query = 'UPDATE SC_customers SET  1C = 0';
        $res = mysql_query($query) or die(mysql_error()."<br>$query");
        $success = 0;
        $row = 0;
        $no = 0;
        
        while (($data = fgetcsv($handle, 255, ';')) !== false) {
            set_time_limit(0);
            $row++;
            $no++;
            $id = $data[0];
            $login = $data[1];
            $bonus = $data[2];
            $skidka = $data[3];
            $skidka_ua = $data[4];
            $fam = $data[5];
            $login = mysql_real_escape_string(decodeCodepage1251($login));
            $bonus = (is_numeric($bonus))?$bonus:0;
            $skidka = (is_numeric($skidka))?$skidka:0;
            $skidka_ua = (is_numeric($skidka_ua))?$skidka_ua:0;
            $fam = decodeCodepage1251($fam);
            $est = getValue('Login', 'SC_customers', "Login = '$login'");
            if (!$est) {
                echo('<small>'.$no.') '.$fam.' <span style="color:green;">'.$login.'</span><span style="color:red;"> не найден!<br></span></small>');
                echo str_repeat(' ', 1024 * 64);
                flush();
            } else {
                $query = "
                     UPDATE SC_customers
                     SET
                     1C = '$bonus',
                     skidka = $skidka,
                     skidka_ua = $skidka_ua
                     WHERE Login = '$login';
                ";
                $res = mysql_query($query) or die(mysql_error()."<br>$query");
                $success++;
            }
        }
        echo('<br><span style="color:blue;">Обработано '.$success.' клиентов<br></span><br>');
        fclose($handle);
    }
    
    //----------- Импорт категорий -----------
    $filename0 = 'categories.csv';
    $filename = $dest_dir.$filename0;
    $file = file($filename);
    $rowcount = count($file);
    echo('<h1>Импорт категорий ...('.$rowcount.')</h1><hr><br>');
    echo(<<<TAG
				<div id='categories' >
					<div style='width:0px;'>&nbsp;</div>
				</div>
TAG
    );
    if (!$rowcount) {
        die(showError("CSV-файл ($filename0) не содержит данных! (rowcount = $rowcount)"));
    }
    if (($handle = fopen($filename, 'r')) !== false) {
        // Идентификатор категории "новинки"
        define('CAT_NOVINKI_ID', getValue('categoryID', 'SC_categories', "name_ru = 'Новинки'"));
        // Идентификатор категории "акция"
        define('CAT_AKCIA_ID', getValue('categoryID', 'SC_categories', "name_ru = 'Акция'"));
        // Идентификатор категории "акция "Баллы"
        define('CAT_AKCIA_BALLY_ID', getValue('categoryID', 'SC_categories', "name_ru = 'Суперцена'"));
        // Идентификатор категории "Все бонусные товары"
        define('CAT_BONUS_ID', getValue('categoryID', 'SC_categories', "name_ru = 'Все бонусные товары'"));
        // Идентификатор категории "Все товары под заказ"
        define('CAT_ZAKAZ_ID', getValue('categoryID', 'SC_categories', "name_ru = 'Все товары под заказ'"));
        
        $query = 'SELECT categoryID FROM SC_categories WHERE allow_products_comparison';
        $res = mysql_query($query) or die(mysql_error()."<br>$query");
        $cids = array();
        $cids[] = -1;
        while ($row = mysql_fetch_object($res)) {
            $cids[] = $row->categoryID;
        }
        $cids = implode(',', $cids);
        $query
            =
            "UPDATE SC_categories SET show_subcategories_products = 0 WHERE categoryID IN ($cids) AND parent IS NOT NULL AND categoryID NOT IN ("
            .CAT_NOVINKI_ID.", ".CAT_AKCIA_ID.", ".CAT_BONUS_ID.", ".CAT_ZAKAZ_ID.", ".CAT_AKCIA_BALLY_ID.")";
        $res = mysql_query($query) or die(mysql_error()."<br>$query");
        
        $no = 0;
        $row = 0;
        $percent = 0;
        $categories = array();
        while (($data = fgetcsv($handle, 255, ';')) !== false) {
            $row++;
            $cid = $data[1];
            $parent = ($parent === 'NULL')?1:$parent;
            $parent = ($parent)?1:$parent;
            $parent = $data[2];
            $name = mysql_real_escape_string(decodeCodepage1251($data[3]));
            $slug = str2Url("$name").'-'.$cid;
            if (is_numeric($cid) && is_numeric($parent)) {
                $est = getValue('categoryID', 'SC_categories', "categoryID = $cid");
                if (!$est) {
                    $query
                        = "INSERT INTO SC_categories (
                                                    slug,
                                                    categoryID,
                                                    parent,
                                                    name_ru,
                                                    allow_products_comparison,
                                                    show_subcategories_products

                                                ) VALUES (
                                                    '$slug',
                                                    $cid,
                                                    $parent,
                                                    '$name',
                                                    1,
                                                    1
                                                )";
                    $res = mysql_query($query) or die(mysql_error()."<br>$query");
                } else {
                    $query
                        = "UPDATE SC_categories SET
                                                slug = '$slug',
                                                show_subcategories_products = 1,
                                                parent = $parent,
                                                name_ru = '$name',
                                                slug = '$slug'
                                                WHERE categoryID = $est";
                    $res = mysql_query($query) or die(mysql_error()."<br>$query");
                }
                $categories[$cid] = $name;
                $progress = round(($no / ($rowcount - 1) * 100), 0, PHP_ROUND_HALF_DOWN);
                if ($progress > $percent) {
                    $percent = $progress.'%';
                    progressBar('categories', $percent);
                }
                buferOut();
                $no++;
            } else {
                echo(showError("Неверный id ($cid) или parent ($parent) в строке $row (категории)"));
            }
        }
        fclose($handle);
        
        $query = "DELETE FROM SC_categories 
                   WHERE show_subcategories_products = FALSE 
                   AND categoryID IN ($cids) and parent IS NOT NULL";
        $res = mysql_query($query) or die(mysql_error()."<br>$query");
        echo('<span style="color:blue;">Обработано '.$no.' категорий<br></span><br><br>Ожидайте...<br><br>');
        progressBar('categories', $percent, false, true);
        buferOut();
    } else {
        die("<br>'Ошибка в при открытии файла: $filename0");
    }
    
    //----------- Импорт товаров -----------
    $filename0 = 'products.csv';
    $filename = $dest_dir.$filename0;
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
    $no = 0;
    $row = 0;
    $error = 0;
    $new_id = 0;
    $percent = 0;
    $query_time = 0;
    $query_conc = 0;
    if (($handle = fopen($filename, 'r')) !== false) {
        $table = 'Conc__analogs';
        $new_ua = array();
        while (($data = fgetcsv($handle, 1000, ';')) !== false) {
            set_time_limit(0);
            if ($row !== 0) {
                $no++;
                $ua = $data[0];
                $id = $data[1];
                $code = mysql_real_escape_string(decodeCodepage1251($data[2]));
                $catid = is_numeric($data[3])?$data[3]:1;
                $price = $data[4];
                $bonus = $data[6];
                $name = mysql_real_escape_string(trim(decodeCodepage1251($data[7])));
                $skidka = $data[8];
                $hit = $data[9];
                $new = $data[10];
                $new_postup = $data[11];
                $akcia = $data[12];
                $ostatok = mysql_real_escape_string(decodeCodepage1251($data[13]));
                $doza = $data[15];
                $box = $data[18];
                $minorder = $data[21];
                $oldprice = $data[22];
                $zakaz = $data[26];
                $brand = mysql_real_escape_string($data[27]);
                $purchase = $data[28];
                
                // Исправление цен
                if (!is_numeric($price)) {
                    $price = preg_replace('/[^0-9.]/', '', $price);
                }
                if (!is_numeric($oldprice)) {
                    $oldprice = preg_replace('/[^0-9.]/', '', $oldprice);
                }
                if (!is_numeric($purchase)) {
                    $purchase = preg_replace('/[^0-9.]/', '', $purchase);
                }
                $ua = ($ua > 1)?1:0;
                $skidka = is_numeric($skidka)?$skidka:0;
                $bonus = is_numeric($bonus)?$bonus:0;
                $hit = ($hit > 0)?$hit:0;
                $new = ($new > 0)?7:5;
                $new_postup = ($new_postup > 0)?$new_postup:0;
                $akcia = ($akcia > 0)?1:0;
                $akcia_skidka = ($akcia > 0)?(1 - $price / $oldprice) * 100:0;
                $akcia_skidka = is_numeric($akcia_skidka)?$akcia_skidka:0;
                $akcia_bally = $bonus / $price;
                $akcia_bally = is_numeric($akcia_bally)?$akcia_bally:0;
                $akcia_bally = ($akcia_bally > 2)?1:0;
                $doza = is_numeric($doza)?$doza:0;
                $box = is_numeric($box)?$box:0;
                $oldprice = ($oldprice > $price)?$oldprice:0;
                $minorder = ($minorder > 0)?1:0;
                $zakaz = ($zakaz > 0)?1:0;
                $slug = str2Url("$name").'-'.$id;
                if (is_numeric($id) && strlen($id) > 4) {
                    $productID = getValue('productID', 'SC_products', "code_1c = '$id'");
                    if (!$productID) {
                        $query = "
                                INSERT INTO SC_products
                                       (
                                            categoryID, 
                                            purchase, 
                                            Price, 
                                            Bonus, 
                                            in_stock, 
                                            items_sold, 
                                            list_price, 
                                            akcia, 
                                            akcia_skidka, 
                                            enabled, 
                                            product_code, 
                                            sort_order , 
                                            ordering_available, 
                                            slug , 
                                            name_ru, 
                                            skidka , 
                                            code_1c, 
                                            ostatok, 
                                            ukraine, 
                                            doza,  
                                            box, 
                                            minorder, 
                                            zakaz, 
                                            brand, 
                                            eproduct_available_days
                                        )
                                VALUES
                                        (
                                            $catid, 
                                            $purchase, 
                                            $price, 
                                            $bonus, 
                                            200, 
                                            $hit, 
                                            $oldprice,
                                            $akcia, 
                                            $akcia_skidka, 
                                            1, 
                                            '$code', 
                                            $new_postup, 
                                            1, 
                                            '$slug',
                                            '$name',  
                                            $skidka, 
                                            '$id',
                                            '$ostatok',
                                            $ua,
                                            $doza,
                                            $box, 
                                            $minorder, 
                                            $zakaz, 
                                            '$brand', 
                                            $new
                                        )
                        ";
                        $res = mysql_query($query) or die(mysql_error()."<br>$query");
                        $productID = mysql_insert_id();
                        $new_id++;
                        
                        // добавление товара в таблицу сравнения конкурентов
                        //                        if ($ostatok !== 'под заказ') {
                            $margin = round((100 * ($price / $purchase) - 100), 0);
                            $query
                                = "
                                INSERT INTO $table
                                       (productID, categoryID, category, code_1c, product_code, name_ru, brand, purchase, usd_purchase, margin, Price, usd_Price, ukraine)
                                VALUES
                                       ($productID, $catid, '$categories[$catid]', '$id', '$code', '$name', '$brand', $purchase, ($purchase/$usd), $margin, $price, ($price/$usd), $ua)";
                            $res = mysql_query($query) or die(mysql_error()."<br>$query");
                        //                        }
                    } else {
                        $query
                            = "
                                UPDATE SC_products
                                SET categoryID              = $catid,
                                    purchase                = $purchase,
                                    Price                   = $price,
                                    Bonus                   = $bonus,
                                    in_stock                = 200,
                                    items_sold              = $hit,
                                    enabled                 = 1,
                                    list_price              = $oldprice,
                                    akcia                   = $akcia,
                                    akcia_skidka            = $akcia_skidka,
                                    product_code            = '$code',
                                    sort_order              = $new_postup,
                                    ordering_available      = 1,
                                    slug                    = '$slug',
                                    name_ru                 = '$name',
                                    skidka                  = $skidka ,
                                    ostatok                 = '$ostatok',
                                    ukraine                 = $ua,
                                    doza                    = $doza,
                                    box                     = $box,
                                    zakaz                   = $zakaz,
                                    brand                   = '$brand',
                                    eproduct_available_days = $new
                                WHERE
                                    productID               = $productID
                            ";
                        $res = mysql_query($query) or die(mysql_error()."<br>$query");
                        
                        $query = "DELETE FROM SC_category_product WHERE productID = $productID";
                        $res = mysql_query($query) or die(mysql_error()."<br>$query");
                        
                        $query = "DELETE FROM SC_product_list_item WHERE productID = $productID";
                        $res = mysql_query($query) or die(mysql_error()."<br>$query");
    
                        //                        if ($ostatok !== 'под заказ') {
                            $margin = round((100 * ($price / $purchase) - 100), 0);
                            $query
                                = "
                                UPDATE $table
                                SET enabled                 = 1,
                                    categoryID              = $catid,
                                    category                = '$categories[$catid]',
                                    product_code            = '$code',
                                    name_ru                 = '$name',
                                    brand                   = '$brand',
                                    purchase                = $purchase,
                                    usd_purchase            = ($purchase/$usd),
                                    margin                  = $margin ,
                                    Price                   = $price,
                                    usd_Price               = ($price/$usd),
                                    ukraine                 = $ua
                                WHERE
                                    productID               = $productID
                            ";
                            $res = mysql_query($query) or die(mysql_error()."<br>$query");
                        //                        }
                    }
                    //if ($new === 7) {
                    //    //$query = "INSERT INTO SC_category_product VALUES ($productID, ".CAT_NOVINKI_ID.", 1)";
                    //    //$res   = mysql_query($query) or die(mysql_error()."<br>$query");
                    //    $query = "INSERT INTO SC_product_list_item (list_id, productID, priority) VALUES ('newitems', $productID, 1)";
                    //    $res = mysql_query($query) or die(mysql_error()."<br>$query");
                    //}
                    //if ($akcia_bally) {
                    //    $query = "INSERT INTO SC_category_product VALUES ($productID, ".CAT_AKCIA_BALLY_ID.", 1)";
                    //    $res = mysql_query($query) or die(mysql_error()."<br>$query");
                    //}
                    //--------- Дополнительные категории и Списки продуктов---------//
                    if ($akcia) {
                        $query = "INSERT INTO SC_category_product 
                                  VALUES ($productID, ".CAT_AKCIA_ID.", 1)";
                        $res = mysql_query($query) or die(mysql_error()."<br>$query");
                        $query = "INSERT INTO SC_product_list_item (list_id, productID, priority) 
                                  VALUES ('akcia', $productID, 1)";
                        $res = mysql_query($query) or die(mysql_error()."<br>$query");
                    }
                    if ($new === 7) {
                        $query = "INSERT INTO SC_product_list_item (list_id, productID, priority) 
                                  VALUES ('newitems', $productID, 1)";
                        $res = mysql_query($query) or die(mysql_error()."<br>$query");
                        $query = "INSERT INTO SC_category_product 
                                  VALUES ($productID, ".CAT_AKCIA_BALLY_ID.", 1)";
                        $res = mysql_query($query) or die(mysql_error()."<br>$query");
                    }
                    if ($bonus) {
                        $query = "INSERT INTO SC_category_product 
                                  VALUES ($productID, ".CAT_BONUS_ID.", 1)";
                        $res = mysql_query($query) or die(mysql_error()."<br>$query");
                    }
                    if ($zakaz) {
                        $query = "INSERT INTO SC_category_product 
                                  VALUES ($productID, ".CAT_ZAKAZ_ID.", 1)";
                        $res = mysql_query($query) or die(mysql_error()."<br>$query");
                    }
                    if ($hit) {
                        $query = "INSERT INTO SC_product_list_item (list_id, productID, priority) 
                                  VALUES ('hitu', $productID, 1)";
                        $res = mysql_query($query) or die(mysql_error()."<br>$query");
                    }
                    if ($new_postup) {
                        if ($ua) {
                            $new_ua[$productID] = $new_postup;
                        } else {
                            $query = "INSERT INTO SC_product_list_item (list_id, productID, priority, date) 
                                  VALUES ('newitemspostup', $productID, 1, $new_postup)";
                            $res = mysql_query($query) or die(mysql_error()."<br>$query");
                        }
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
    
    $query = "SELECT productID FROM SC_products WHERE  in_stock =100";
    $res = mysql_query($query) or die('Ошибка в запросе: '.mysql_error().'<br>'.$query);
    $products_disabled = array();
    while ($enabled = mysql_fetch_row($res)) {
        $products_disabled[] = $enabled[0];
    }
    $products_disabled = implode(',', $products_disabled);
    
    //    $query = 'UPDATE SC_products SET enabled = FALSE, items_sold = 0 WHERE in_stock = 100';
    $query = 'UPDATE SC_products SET enabled = FALSE, items_sold = 0 WHERE productID IN ('.$products_disabled.')';
    $res = mysql_query($query) or die(mysql_error()."<br>$query");
    
    $query = "UPDATE $table SET enabled = 0 WHERE productID IN ($products_disabled)";
    $res = mysql_query($query) or die(mysql_error()."<br>$query");
    
    $query = 'UPDATE SC_products SET in_stock =100 WHERE in_stock = 200';
    $res = mysql_query($query) or die(mysql_error()."<br>$query");
    
    $query = 'UPDATE SC_products SET `min_order_amount` = `box` WHERE `zakaz` = 1 OR `minorder` = 1';
    $res = mysql_query($query) or die(mysql_error()."<br>$query");
    
    $query = 'DELETE FROM SC_category_product WHERE priority = 0';
    $res = mysql_query($query) or die(mysql_error()."<br>$query");
    
    $query = 'UPDATE SC_category_product SET priority = 0';
    $res = mysql_query($query) or die(mysql_error()."<br>$query");
    
    $query = 'DELETE FROM SC_product_list_item WHERE priority = 0';
    $res = mysql_query($query) or die(mysql_error()."<br>$query");
    $query = 'UPDATE SC_product_list_item SET priority = 0';
    $res = mysql_query($query) or die(mysql_error()."<br>$query");
    
    $new_count = 500;
    
    $query = "SELECT productID FROM SC_products WHERE enabled = 1 AND in_stock =100 ORDER BY code_1c DESC LIMIT $new_count";
    $res = mysql_query($query) or die('Ошибка в запросе: '.mysql_error().'<br>'.$query);
    
    while ($ids = mysql_fetch_row($res)) {
        $id = $ids[0];
        $query1 = "INSERT INTO SC_category_product VALUES (".$id.", ".CAT_NOVINKI_ID.", 0)";
        $result = mysql_query($query1) or die(mysql_error()."<br>$query1");
        if ($new_ua[$id]) {
            $query2
                = "INSERT INTO SC_product_list_item (list_id, productID, priority, date) 
                   VALUES ('newitemspostup', $id, 1, $new_ua[$id])";
            $res2 = mysql_query($query2) or die(mysql_error()."<br>$query2");
        }
    }
    $query = "DELETE FROM `SC_auth_log` WHERE `Login` = 'sales'";
    $res = mysql_query($query) or die(mysql_error()."<br>$query");
    
    $query = 'TRUNCATE TABLE  Search_products';
    $res = mysql_query($query) or die(mysql_error()."<br>$query");
    
    $query = 'INSERT INTO Search_products (categoryID, code_1c, product_code, name_ru, Price, enabled)
              SELECT  categoryID, code_1c, product_code, name_ru, Price, enabled FROM SC_products  WHERE in_stock = 100';
    $res = mysql_query($query) or die(mysql_error()."<br>$query");
    
    //    $query = 'DELETE FROM `SC_shopping_cart_items`
    //              WHERE `productID` IS NULL';
    //    $res = mysql_query($query) or die(mysql_error()."<br>$query");
    
    // Оптимизация таблиц
    $query = 'OPTIMIZE TABLE 
                    `SC_auth_log`, `SC_categories`, `SC_category_product`, `SC_currency_types`, `SC_customers`, 
                    `SC_customer_addresses`, `SC_customer_reg_fields_values`, `SC_ordered_carts`, `SC_orders`, 
                    `SC_order_status_changelog`, `SC_products`, `SC_product_list_item`, `SC_product_pictures`, 
                    `SC_shopping_carts`, `SC_shopping_cart_items`, `SC_subscribers`, `Search_products`';
    $res = mysql_query($query) or die(mysql_error()."<br>$query");
    
    // добавление цен конкурентов в таблицу сравнения конкурентов
    $query = 'SELECT competitor, currency_value FROM Conc__competitors';
    $res = mysql_query($query) or die(mysql_error()."<br>$query");
    
    $competitors = array();
    $concs = array();
    
    while ($Currs = mysql_fetch_object($res)) {
        $competitors[$Currs->competitor] = $Currs->currency_value;
        $concs[] = $Currs->competitor;
    }
    
    $diff_conc = array();
    
    foreach ($concs as $unic_conc) {
        
        $diff_conc[] = 'diff_'.$unic_conc;
        
        $query = "SELECT code, code_1c FROM Conc_search__$unic_conc";
        $res = mysql_query($query) or die(mysql_error().$query);
    
        $usd_conc = $competitors[$unic_conc];
        
        while ($Codes = mysql_fetch_object($res)) {
    
            $query2
                = "SELECT
                        price_uah
                    FROM
                        Conc__$unic_conc
                    WHERE
                        code = '$Codes->code' AND enabled=1";
            $res2 = mysql_query($query2) or die(mysql_error().$query2);
            
            if ($analog = mysql_fetch_row($res2)) {
    
                $query3
                    = "UPDATE $table
                            SET    enabled         = 2,
                                   $unic_conc      = $analog[0],
                                   usd_$unic_conc  = $analog[0]/$usd_conc,
                                   diff_$unic_conc = ROUND((Price/$analog[0]-1)*100, 1)
                            WHERE  code_1c         = '$Codes->code_1c'
                                   AND enabled > 0";
                $res3 = mysql_query($query3) or die(mysql_error()."<br>$query");
            }
        }
    
        optimizeTable('Conc__'.$unic_conc);
        optimizeTable('Conc_search__'.$unic_conc);
    }
    
    //            $query = "DELETE FROM $table WHERE 1 $delete_null";
    //            $res = mysql_query($query) or die(mysql_error().$query);
    
    $diff_conc = implode(',', $diff_conc);
    $query = "UPDATE $table SET max_diff = GREATEST($diff_conc) WHERE enabled = 2";
    $res = mysql_query($query) or die(mysql_error().$query);
    
    if ($res) {
        optimizeTable($table);
    }
    mysql_close();
    
    // Удаление временных файлов
    removeDirRec($_SERVER['DOCUMENT_ROOT'].'/upload/');
    echo(<<<TAG
	<br>
	<div id='end'>Импорт завершен!</div>
TAG
    );
    debugging($start);