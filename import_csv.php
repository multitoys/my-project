<?php

    $start = microtime(true);
    //    apache_setenv('no-gzip', '1');
    ini_set('display_errors', true);
    define('DIR_ROOT', $_SERVER['DOCUMENT_ROOT'].'/published/SC/html/scripts');

    include_once(DIR_ROOT.'/includes/init.php');
    include_once(DIR_CFG.'/connect.inc.wa.php');
    include(DIR_FUNC.'/setting_functions.php');

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
    $dest_dir    = $_SERVER['DOCUMENT_ROOT'].'/temp/import/';

    $zip      = new ZipArchive();
    $fileName = $archive_dir.'multi.zip';

    if ($zip->open($fileName) !== true) {
        echo('Error while openning archive file: multi.zip');
        exit(1);
    }

    RemoveDir($dest_dir);
    $zip->extractTo($dest_dir);

    echo("<div id='extract'>Файлы ($zip->numFiles) успешно извлечены!</div><br>");

    //----------- Импорт покупателей -----------
    // echo('Импорт покупателей ...<hr>');

    // $filename = $dest_dir.'clients.csv';
    // $data = new Spreadsheet_Excel_Reader($filename);
    // $rowcount = $data->rowcount();
    // if ($rowcount < 1) {
    // echo('<span style="color:red;">Файл '.$filename.' не содержит данных!<br></span>');
    // }
    // else {
    // $colcount = $data->colcount();
    // if ($colcount < 4) {
    // echo('<span style="color:red;">В файле '.$filename.' количество столбцов меньше четырех!');
    // }
    // else {
    // $query = "UPDATE SC_customers SET  1C = 0";
    // $res   = mysql_query($query) or die(mysql_error()."<br>$query");

    // $no = 0;
    // for ($row=2; $row<$rowcount+1; $row++) {
    // set_time_limit(90);
    // $no++;
    // $id             = $data[]'A');
    // $login          = $data[]'B');
    // $bonus          = $data[]'C');
    // $special_price  = $data[]'D');
    // $antiskidka     = $data[]'E');
    // $skidka         = $data[]'F');
    // $fam            = $data[]'G');
    // $login          = mysql_real_escape_string(DecodeCodepage($login));
    // $bonus          = mysql_real_escape_string($bonus);
    // $special_price  = ($special_price==1)?1:0;
    // $antiskidka     = ($antiskidka==1)?1:0;
    // $skidka         = (is_numeric($skidka))?$skidka:0;
    // $fam            = mysql_real_escape_string(DecodeCodepage($fam));
    // $est   = GetValue('Login', 'SC_customers', "Login LIKE '$login'");

    // if (!$est) {
    // echo ('<small>'.$no.') '.$fam.' <span style="color:green;">'.$login.'</span><span style="color:red;"> не найден!<br></span></small>');
    // echo str_repeat(' ',1024*64);
    // flush();
    // // usleep(100000);
    // }
    // else {
    // $query = "
    // UPDATE SC_customers
    // SET
    // 1C = '$bonus',
    // is_special_price='$special_price',
    // ignore_skidka = '$antiskidka',
    // skidka = $skidka
    // WHERE Login = '$login';
    // ";
    // $res   = mysql_query($query) or die(mysql_error()."<br>$query");
    // }
    // }
    // }
    // }
    // echo('<br><span style="color:blue;">Обработано '.$no.' клиентов<br></span><br>');


    //----------- Импорт категорий -----------
    $filename = $dest_dir.'categories.csv';
    $file     = file($filename);
    $rowcount = count($file);
    echo('<h1>Импорт категорий ...('.$rowcount.')</h1><hr><br>');
    echo(<<<TAG
				<div id='categories' >
					<div style='width:0px;'>&nbsp;</div>
				</div>
TAG
);
    if (!$rowcount) {
        die(ShowError("CSV-файл ($filename) не содержит данных! (rowcount = $rowcount)"));
    }
    if (($handle = fopen($filename, 'r')) !== false) {

        // Идентификатор категории "новинки"
        define('CAT_NOVINKI_ID', GetValue('categoryID', 'SC_categories', "name_ru = 'Новинки'"));

        // Идентификатор категории "акция"
        define('CAT_AKCIA_ID', GetValue('categoryID', 'SC_categories', "name_ru = 'Акция'"));

        // Идентификатор категории "акция "Баллы"
        define('CAT_AKCIA_BALLY_ID', GetValue('categoryID', 'SC_categories', "name_ru = 'Суперцена'"));

        // Идентификатор категории "Все бонусные товары"
        define('CAT_BONUS_ID', GetValue('categoryID', 'SC_categories', "name_ru = 'Все бонусные товары'"));

        // Идентификатор категории "Все товары под заказ"
        define('CAT_ZAKAZ_ID', GetValue('categoryID', 'SC_categories', "name_ru = 'Все товары под заказ'"));

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

        $no      = 0;
        $row     = 0;
        $percent = 0;

        $categories = array();
        
        while (($data = fgetcsv($handle, 255, ';')) !== false) {
            $row++;
            $cid = $data[1];
            $parent = ($parent === 'NULL')?1:$parent;
            $parent = ($parent)?1:$parent;
            $parent = $data[2];
            $name   = mysql_real_escape_string(DecodeCodepage1251($data[3]));

            $slug = Str2Url("$name").'-'.$cid;

            if (is_numeric($cid) && is_numeric($parent)) {
                $est = GetValue('categoryID', 'SC_categories', "categoryID = $cid");
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
                    ProgressBar('categories', $percent);
                }
                BuferOut();
                $no++;
            } else {
                echo(ShowError("Неверный id ($cid) или parent ($parent) в строке $row (категории)"));
            }
        }

        fclose($handle);

        $query
            = "DELETE FROM SC_categories WHERE show_subcategories_products = FALSE AND categoryID IN ($cids) and parent IS NOT NULL";
        $res = mysql_query($query) or die(mysql_error()."<br>$query");

        // echo '<img src="css/images/preloader-01.gif"/>';
        echo('<span style="color:blue;">Обработано '.$no.' категорий<br></span><br><br>Ожидайте...<br><br>');
        ProgressBar('categories', $percent, false, true);
        BuferOut();
    } else {
        die("<br>'Ошибка в при открытии файла: $filename");
    }


    //----------- Импорт товаров -----------
    $filename = $dest_dir.'products.csv';
    $file     = file($filename);
    $rowcount = count($file);
    echo('<h1>Импорт товаров ...('.($rowcount - 1).')</h1><hr><br>');
    echo(<<<TAG
		<div id='products' >
			<div style='width:0px;'>&nbsp;</div>
		</div>
TAG
);

    if (!$rowcount) {
        die(ShowError("CSV-файл ($filename) не содержит данных! (rowcount = $rowcount)"));
    }
    
    $no      = 0;
    $row     = 0;
    $error = 0;
    $new_id = 0;
    $percent = 0;

    if (($handle = fopen($filename, 'r')) !== false) {
        
        $start2 = microtime(true);

        $table = 'Conc__analogs';
        deleteRow($table);

        while (($data = fgetcsv($handle, 1000, ';')) !== false) {
            
            set_time_limit(0);

            if ($row !== 0) {
                $no++;
                $ua = $data[0];
                $id = $data[1];
                $code = mysql_real_escape_string(DecodeCodepage1251($data[2]));
                $catid = is_numeric($data[3])?$data[3]:1;
                $price = $data[4];
                $special_price = $data[5];
                $bonus = $data[6];
                $name = mysql_real_escape_string(trim(DecodeCodepage1251($data[7])));
                $skidka = $data[8];
                $hit = $data[9];
                $new = $data[10];
                $new_postup = $data[11];
                $akcia = $data[12];
                $ostatok = mysql_real_escape_string(DecodeCodepage1251($data[13]));
                //                $optprice      = $data[14];
                $doza = $data[15];
                //                $optprice_usd  = $data[16];
                $box = $data[18];
                $minorder = $data[21];
                $oldprice = $data[22];
                $zakaz = $data[26];
                $brand = mysql_real_escape_string(DecodeCodepage1251($data[27]));
                $purchase = $data[28];

                // Исправление цен
                if (!is_numeric($price)) {
                    $price = preg_replace('/[^0-9.]/', '', $price);
                }
                if (!is_numeric($oldprice)) {
                    $oldprice = preg_replace('/[^0-9.]/', '', $oldprice);
                }
                if (!is_numeric($special_price)) {
                    $special_price = preg_replace('/[^0-9.]/', '', $special_price);
                }
                //                if (!is_numeric($optprice)) {
                //                    $optprice = preg_replace('/[^0-9.]/', '', $optprice);
                //                }
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

                //                $optprice = is_numeric($optprice)?$optprice:0;
                $doza = is_numeric($doza)?$doza:0;
                $box = is_numeric($box)?$box:0;
                $oldprice = ($oldprice > $price)?$oldprice:0;
                $minorder = ($minorder > 0)?1:0;
                $zakaz = ($zakaz > 0)?1:0;
                $slug = Str2Url("$name").'-'.$id;
                // $akcia        = ($oldprice > 0)?1:0;

                if (is_numeric($id) && strlen($id) > 4) {

                    $productID = GetValue('productID', 'SC_products', "code_1c = '$id'");

                    if (!$productID) {

                        $query
                            = "
                                INSERT INTO SC_products
                                       (categoryID, purchase, Price, SpecialPrice,   Bonus, in_stock, items_sold, list_price, akcia,   akcia_skidka,   enabled, product_code, sort_order , ordering_available, slug , name_ru, skidka , code_1c, ostatok  , ukraine  , doza  ,  box,   minorder   , zakaz, brand, eproduct_available_days)
                                VALUES
                                        ($catid    , $purchase, $price,$special_price, $bonus, 200 , $hit  , '$oldprice','$akcia', '$akcia_skidka', 1     , '$code'     , $new_postup, 1                 , '$slug','$name',  $skidka, '$id'  ,'$ostatok',$ua,'$doza','$box', '$minorder', '$zakaz', '$brand', $new)";
                        $res = mysql_query($query) or die(mysql_error()."<br>$query");
                        $productID = mysql_insert_id();
                        $new_id++;
                    } else {

                        $query
                            = "
                                UPDATE SC_products
                                SET categoryID              = $catid,
                                    purchase                = $purchase,
                                    Price                   = $price,
                                    SpecialPrice            = $special_price,
                                    Bonus                   = $bonus,
                                    in_stock                = 200,
                                    items_sold              = $hit,
                                    enabled                 = 1,
                                    list_price              = '$oldprice',
                                    akcia                   = '$akcia',
                                    akcia_skidka            = '$akcia_skidka',
                                    product_code            = '$code',
                                    sort_order              = $new_postup,
                                    ordering_available      = 1,
                                    slug                    = '$slug',
                                    name_ru                 = '$name',
                                    skidka                  = $skidka ,
                                    ostatok                 = '$ostatok',
                                    ukraine                 = $ua,
                                    doza                    = '$doza',
                                    box                     = '$box',
                                    zakaz                   = '$zakaz',
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
                        $query = "INSERT INTO SC_category_product VALUES ($productID, ".CAT_AKCIA_ID.", 1)";
                        $res = mysql_query($query) or die(mysql_error()."<br>$query");
                        $query
                            = "INSERT INTO SC_product_list_item (list_id, productID, priority) VALUES ('akcia', $productID, 1)";
                        $res = mysql_query($query) or die(mysql_error()."<br>$query");
                    }
                    if ($new === 7) {
                        $query = "INSERT INTO SC_product_list_item (list_id, productID, priority) VALUES ('newitems', $productID, 1)";
                        $res = mysql_query($query) or die(mysql_error()."<br>$query");

                        $query = "INSERT INTO SC_category_product VALUES ($productID, ".CAT_AKCIA_BALLY_ID.", 1)";
                        $res = mysql_query($query) or die(mysql_error()."<br>$query");
                    }
                    if ($bonus) {
                        $query = "INSERT INTO SC_category_product VALUES ($productID, ".CAT_BONUS_ID.", 1)";
                        $res = mysql_query($query) or die(mysql_error()."<br>$query");
                    }
                    if ($zakaz) {
                        $query = "INSERT INTO SC_category_product VALUES ($productID, ".CAT_ZAKAZ_ID.", 1)";
                        $res = mysql_query($query) or die(mysql_error()."<br>$query");
                    }
                    if ($hit) {
                        $query
                            = "INSERT INTO SC_product_list_item (list_id, productID, priority) VALUES ('hitu', $productID, 1)";
                        $res = mysql_query($query) or die(mysql_error()."<br>$query");
                    }
                    if ($new_postup) {
                        $query
                            = "INSERT INTO SC_product_list_item (list_id, productID, priority, date) VALUES ('newitemspostup', $productID, 1, $new_postup)";
                        $res = mysql_query($query) or die(mysql_error()."<br>$query");
                    }

                    // добавление товара в таблицу сравнения конкурентов
                    if ($ostatok !== 'под заказ') {
                        $margin = round((100 * ($price / $purchase) - 100), 0);
                        $query
                            = "
                                INSERT INTO $table
                                       (productID, categoryID, category, code_1c, product_code, name_ru, brand, purchase, usd_purchase, margin, Price, usd_Price, ukraine)
                                VALUES 
                                       ($productID, $catid, '$categories[$catid]', '$id', '$code', '$name', '$brand', $purchase, ($purchase/$usd), $margin, $price, ($price/$usd), $ua)";
                        $res = mysql_query($query) or die(mysql_error()."<br>$query");
                    }
                    // -----------
                    
                    $progress = round(($no / ($rowcount - 2) * 100), 0, PHP_ROUND_HALF_DOWN);

                    if ($progress > $percent) {
                        $percent = $progress.'%';
                        $start = ($progress > 20)?$start2:false;
                        ProgressBar('products', $percent, $start);
                    }
                    BuferOut();
                } else {
                    echo(ShowError("Неверный id ($id) (строка $row) - <span style='color:red;'>позиция проигнорирована</span>"));
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
        die("<br>'Ошибка в при открытии файла: $filename");
    }
    ProgressBar('products', $percent, false, true);
    echo('<span style="color:blue;"><br>Обработано '.($no - $error).' товаров</span><br><span>Новых '.$new_id.' товаров</span><br>');

    $query = 'UPDATE SC_products SET enabled = FALSE, items_sold = 0 WHERE in_stock = 100';
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
    $query     = "SELECT productID FROM SC_products WHERE enabled = 1 ORDER BY code_1c DESC LIMIT $new_count";
    $res = mysql_query($query) or die('Ошибка в запросе: '.mysql_error().'<br>'.$query);

    while ($ids = mysql_fetch_object($res)) {
        $id     = $ids->productID;

        $query  = "INSERT INTO SC_category_product VALUES (".$id.", ".CAT_NOVINKI_ID.", 0)";
        $result = mysql_query($query) or die(mysql_error()."<br>$query");

        //$query  = "INSERT INTO SC_product_list_item (list_id, productID, priority) VALUES ('newitems', ".$id.", 0)";
        //$result = mysql_query($query) or die(mysql_error()."<br>$query");

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

    // Оптимизация таблиц
    $query
        = 'OPTIMIZE TABLE `SC_auth_log`, `SC_categories`, `SC_category_product`, `SC_currency_types`, `SC_customers`, `SC_customer_addresses`, `SC_customer_reg_fields_values`, `SC_ordered_carts`, `SC_orders`, `SC_order_status_changelog`, `SC_products`, `SC_product_list_item`, `SC_product_pictures`, `SC_shopping_carts`, `SC_shopping_cart_items`, `SC_subscribers`, `Search_products`, `Conc__kindermarket`, `Conc__divoland`, `Conc__dreamtoys`, `Conc__mixtoys`, `Conc_search__kindermarket`, `Conc_search__divoland`, `Conc_search__dreamtoys`, `Conc_search__mixtoys`';
    $res = mysql_query($query) or die(mysql_error()."<br>$query");

    /*------------------------------------*/

    //    $usd0 = $usd;
    //    $query
    //        = "INSERT INTO $table
    //                      (categoryID, category, code_1c, product_code, name_ru, brand, purchase, usd_purchase,   Price, usd_Price,   ukraine)
    //          SELECT       categoryID AS ID, (SELECT name_ru FROM  SC_categories WHERE categoryID=ID), code_1c, product_code, name_ru, brand, purchase, purchase/$usd0, Price, Price/$usd0, ukraine
    //          FROM SC_products
    //          WHERE in_stock = 100 AND enabled AND Price <> 0.00 AND ostatok NOT LIKE 'под заказ'";
    //    $res = mysql_query($query) or die(mysql_error()."<br>$query");

    $concs = array('divoland', 'mixtoys', 'dreamtoys', 'kindermarket', 'grandtoys');
    $currency_table = 'Conc__currency';

    $query = 'SELECT * FROM Conc__competitors';
    $res = mysql_query($query) or die(mysql_error()."<br>$query");
    $competitors = array();
    while ($Currs = mysql_fetch_object($res)) {
        $competitors[$Currs->competitor] = $Currs->currency_value;
    }
    
    foreach ($concs as $conc) {
        $query = "SELECT code, code_1c FROM Conc_search__$conc";
        $res = mysql_query($query) or die(mysql_error().$query);

        $usd_conc = $usd;

        if (array_key_exists($conc, $competitors)) {
            $usd_conc = $competitors[$conc];
        }

        while ($Codes = mysql_fetch_object($res)) {
            $query2
                = "SELECT
                        price_uah
                    FROM
                        Conc__$conc
                    WHERE
                        code = '$Codes->code' AND enabled=1";
            $res2 = mysql_query($query2) or die(mysql_error().$query2);
            if ($analog = mysql_fetch_row($res2)) {
                $query3
                    = "UPDATE $table
                            SET    $conc      = $analog[0],
                                   usd_$conc  = $analog[0]/$usd_conc,
                                   diff_$conc = ROUND((Price/$analog[0]-1)*100)
                            WHERE  code_1c    = '$Codes->code_1c'";
                $res3 = mysql_query($query3) or die(mysql_error()."<br>$query");
            }
        }
    }
    $query = "UPDATE $table SET max_diff = GREATEST(diff_kindermarket, diff_divoland, diff_dreamtoys, diff_mixtoys, diff_grandtoys)";
    $res = mysql_query($query) or die(mysql_error().$query);
    optimizeTable($table);

    function get($what, $condition, $table='')
    {
        $query = "SELECT $what FROM $table WHERE $condition LIMIT 1";
        $result = mysql_query($query) or die('Ошибка в запросе: '.mysql_error().'<br>'.$query);
        $row = mysql_fetch_row($result);
        return $row[0];
    }

    function deleteRow($table, $condition='')
    {
        $condition = ($condition) ? "WHERE $condition" : "";
        $query = "DELETE FROM $table $condition";
        $result = mysql_query($query) or die('Ошибка в запросе: ' . mysql_error() . '<br>' . $query);
    }

    function optimizeTable($table)
    {
        $query = "OPTIMIZE TABLE $table";
        $res = mysql_query($query) or die(mysql_error()."<br>$query");
        mysql_close();
    }

    /*----------- Фото ----------*/
    $zip = new ZipArchive();
    $fileName = $archive_dir."pics.zip";

    if ($zip->open($fileName) == true) {
        include($_SERVER['DOCUMENT_ROOT'].'/curl_pics.php');
    }
    
    mysql_close();
    // Удаление временных файлов
    RemoveDir($_SERVER['DOCUMENT_ROOT'].'/upload/');

    echo(<<<TAG
	<br>
	<div id='end'>Импорт завершен!</div>
TAG
);
    Debugging($start);

    /*--------- Функции ---------*/
    function ShowError($msg)
    {
        return "<div style='color:red; font-size:16px;'>$msg</div>";
    }

    function RemoveDir($directory)
    {
        $dir = opendir($directory);
        while (($file = readdir($dir))) {
            if (is_file($directory.'/'.$file)) {
                unlink($directory.'/'.$file);
            } else {
                if (is_dir($directory.'/'.$file) && ($file != '.') && ($file != '..')) {
                    RemoveDir($directory.'/'.$file);
                }
            }
        }
        closedir($dir);

        return true;
    }

    function GetValue($what, $table, $condition)
    {
        $query = "SELECT $what FROM $table WHERE $condition LIMIT 1";
        $result = mysql_query($query) or die('Ошибка в запросе: '.mysql_error().'<br>'.$query);
        $row = mysql_fetch_row($result);

        return $row[0];
    }

    function UpdateValue($table, $new_value, $condition = '')
    {
        $condition = ($condition) ? "WHERE $condition" : '';
        $query     = "UPDATE $table SET $new_value $condition";
        $result = mysql_query($query) or die('Ошибка в запросе: '.mysql_error().'<br>'.$query);
    }

//    function DeleteRow($table, $condition = '')
//    {
//        $condition = ($condition) ? "WHERE $condition" : '';
//        $query     = "DELETE FROM $table $condition";
//        $result = mysql_query($query) or die('Ошибка в запросе: '.mysql_error().'<br>'.$query);
//    }

    function DecodeCodepage($text)
    {
        $s = mb_detect_encoding($text);
        $q = iconv($s, 'UTF-8', $text);

        return $q;
    }

    function DecodeCodepage1251($text)
    {
        $s = 'windows-1251';
        $q = iconv($s, 'UTF-8', $text);

        return $q;
    }

    function Rus2Translit($string)
    {
        $converter = array(
            'а' => 'a', 'б' => 'b', 'в' => 'v',
            'г' => 'g', 'д' => 'd', 'е' => 'e',
            'ё' => 'e', 'ж' => 'zh', 'з' => 'z',
            'и' => 'i', 'й' => 'y', 'к' => 'k',
            'л' => 'l', 'м' => 'm', 'н' => 'n',
            'о' => 'o', 'п' => 'p', 'р' => 'r',
            'с' => 's', 'т' => 't', 'у' => 'u',
            'ф' => 'f', 'х' => 'h', 'ц' => 'c',
            'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch',
            'ь' => '\'', 'ы' => 'y', 'ъ' => '\'',
            'э' => 'e', 'ю' => 'yu', 'я' => 'ya',

            'А' => 'A', 'Б' => 'B', 'В' => 'V',
            'Г' => 'G', 'Д' => 'D', 'Е' => 'E',
            'Ё' => 'E', 'Ж' => 'Zh', 'З' => 'Z',
            'И' => 'I', 'Й' => 'Y', 'К' => 'K',
            'Л' => 'L', 'М' => 'M', 'Н' => 'N',
            'О' => 'O', 'П' => 'P', 'Р' => 'R',
            'С' => 'S', 'Т' => 'T', 'У' => 'U',
            'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
            'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sch',
            'Ь' => '\'', 'Ы' => 'Y', 'Ъ' => '\'',
            'Э' => 'E', 'Ю' => 'Yu', 'Я' => 'Ya'
        );

        return strtr($string, $converter);
    }

    function Str2Url($str)
    {
        $str = Rus2Translit($str);
        $str = strtolower($str);
        $str = preg_replace('~[^-a-z0-9_]+~u', '-', $str);
        $str = trim($str, '-');

        return $str;
    }

    function ProgressBar($import_items, $percent, $start = false, $full = false)
    {
        $end = '';
        if ($start) {
            $time = round(((100 - $percent) * (microtime(true) - $start) / $percent), 0);
            $end = ' - <small>Осталось: '.$time.' сек.</small>';
        }

        $success = '';
        if ($full === true) {
            $success = 'background-image:linear-gradient(to bottom, #6AFF7D, #00DC08)';
        }
        echo "<script language='javascript'>
                    document.getElementById('$import_items').innerHTML=\"<div style='width:$percent;$success'>$percent $end</div>\"
              </script>
        ";
    }

    function BuferOut($delay = 0)
    {
        echo str_repeat(' ', 1024 * 4);
        flush();
        usleep($delay);
    }

    function Debugging($start)
    {
        // $memoscript = memory_get_usage(true)/1048576;
        $memoscript_peak = memory_get_peak_usage(true) / 1048576;
        $time            = microtime(true) - $start;
        printf('<br>Скрипт выполнялся: %.2F сек.', $time);
        printf('<br>Пик оперативной памяти: %.2F МБ.', $memoscript_peak);
        // printf('<br>Использовано оперативной памяти: %.2F МБ.', $memoscript);
    }