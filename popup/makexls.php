<?php
    /**
     * Created by PhpStorm.
     * User: multi
     * Date: 22.10.2015
     * Time: 21:09
     */

    ini_set('display_errors', true);
    define('DIR_ROOT', $_SERVER['DOCUMENT_ROOT'].'/published/SC/html/scripts');
    $DebugMode = false;
    $Warnings = array();
    include_once(DIR_ROOT.'/includes/init.php');
    include_once(DIR_CFG.'/connect.inc.wa.php');
    include(DIR_FUNC.'/setting_functions.php');

    $DB_tree = new DataBase();
    $DB_tree->connect(SystemSettings::get('DB_HOST'), SystemSettings::get('DB_USER'), SystemSettings::get('DB_PASS'));

    $DB_tree->selectDB(SystemSettings::get('DB_NAME'));
    define('VAR_DBHANDLER', 'DBHandler');

    $file_content = MakeXLS(19859, 0);

    $filename = "19859.xls";
    $handle = fopen($filename, 'a');
    fwrite($handle, $file_content);
    fclose($handle);
    $filename1 = my_translit($filename);


    //if ($file_content) {
    //    header('Content-Type: application/xls');
    //    header("Content-Disposition: attachment; filename=$filename");
    //    readfile($filename);
    //    unlink($filename);
    //}

    function MakeXLS($orderID, $per)
    {
        $textout = xlsBOF();

        $sql = "SELECT shipping_firstname,shipping_lastname,shipping_country,shipping_state,shipping_zip,shipping_city,shipping_address
FROM SC_orders
WHERE orderID = $orderID ";
        $res = mysql_query($sql) or die(mysql_error()."<br>$sql");
        $row = mysql_fetch_object($res);
        $strana = $row->shipping_country;
        $obl = $row->shipping_state;
        $gorod = $row->shipping_city;
        $adres = $row->shipping_address;

        if ($per == 0) {
            $mod = 'SC_products.zakaz=0  AND SC_categories.allow_products_comparison';
        } else {
            $mod = 'NOT SC_categories.allow_products_comparison OR SC_products.zakaz!=0';
        }
        $query = "
SELECT
SC_products.code_1c, SC_products.product_code, SC_ordered_carts.name,
SC_ordered_carts.Price, SC_ordered_carts.Quantity,
SC_categories.name_ru as catname
FROM SC_ordered_carts
LEFT JOIN SC_shopping_cart_items ON SC_shopping_cart_items.itemID = SC_ordered_carts.itemID
LEFT JOIN SC_products ON SC_products.productID = SC_shopping_cart_items.productID
LEFT JOIN SC_categories ON SC_categories.categoryID = SC_products.categoryID
WHERE SC_ordered_carts.orderID = $orderID
 AND ($mod)

";
        $res = mysql_query($query) or die(mysql_error()."<br>$query");
        if (mysql_num_rows($res)) {
            $custID = GetValue('customerID', 'SC_orders', "orderID = $orderID");
            if ($custID) {
                $custCod = GetValue('Login', 'SC_customers', "customerID = $custID");
                $custFIO = GetValue('CONCAT(first_name, \' \', last_name)', 'SC_customers', "customerID = $custID");
            } else {
                $custCod = '';
                $custFIO = '';
            }

            $textout .= xlsWriteLabel(0, 9, "Адрес");
            $textout .= xlsWriteLabel(2, 9, "Страна");
            $textout .= xlsWriteLabel(2, 10, $strana);

            $textout .= xlsWriteLabel(3, 9, "Область");
            $textout .= xlsWriteLabel(3, 10, $obl);

            $textout .= xlsWriteLabel(4, 9, "Город");
            $textout .= xlsWriteLabel(4, 10, $gorod);

            $textout .= xlsWriteLabel(5, 9, "Адрес");
            $textout .= xlsWriteLabel(5, 10, $adres);

            $textout .= xlsWriteLabel(0, 1, "Дата заявки");
            $textout .= xlsWriteLabel(0, 2, date('d/m/Y'));

            $textout .= xlsWriteLabel(1, 1, "Код контрагента");
            $textout .= xlsWriteLabel(1, 2, $custCod);

            $textout .= xlsWriteLabel(1, 4, "Наименование контгагента:");
            $textout .= xlsWriteLabel(1, 5, $custFIO);

            $textout .= xlsWriteLabel(3, 0, "№");
            $textout .= xlsWriteLabel(3, 1, "Код категории");
            $textout .= xlsWriteLabel(3, 2, "Артикул");
            $textout .= xlsWriteLabel(3, 3, "Код товара");
            $textout .= xlsWriteLabel(3, 4, "Товар");
            $textout .= xlsWriteLabel(3, 5, "Количество");
            $textout .= xlsWriteLabel(3, 6, "Цена");
            $textout .= xlsWriteLabel(3, 7, "Сумма");

            $no = 3;
            while ($row = mysql_fetch_object($res)) {
                $no++;
                $textout .= xlsWriteLabel($no, 0, $no - 3);
                $textout .= xlsWriteLabel($no, 1, $row->catname);
                $textout .= xlsWriteLabel($no, 2, $row->product_code);
                $textout .= xlsWriteLabel($no, 3, $row->code_1c);
                $textout .= xlsWriteLabel($no, 4, $row->name);
                $textout .= xlsWriteLabel($no, 5, $row->Quantity);
                $textout .= xlsWriteLabel($no, 6, str_replace('.', ',',$row->Price));
                $textout .= xlsWriteNumber($no, 7, number_format($row->Price * $row->Quantity, 2, ',', ''));
            }

            $textout .= xlsEOF();
        } else {
            $textout = false;
        }

        return $textout;
    }

    function GetValue($what, $table, $condition)
    {
        $query = "SELECT $what FROM $table WHERE $condition LIMIT 1";
        $result = mysql_query($query) or die('Ошибка в запросе: '.mysql_error().'<br>'.$query);
        $row = mysql_fetch_row($result);

        return $row[0];
    }

    function my_translit($cyr_str)
    {
        $tr = array("Ґ" => "G", "Ё" => "YO", "Є" => "E", "Ї" => "YI", "І" => "I",
                    "і" => "i", "ґ" => "g", "ё" => "yo", "№" => "_", "є" => "e",
                    "ї" => "yi", "А" => "A", "Б" => "B", "В" => "V", "Г" => "G",
                    "Д" => "D", "Е" => "E", "Ж" => "ZH", "З" => "Z", "И" => "I",
                    "Й" => "Y", "К" => "K", "Л" => "L", "М" => "M", "Н" => "N",
                    "О" => "O", "П" => "P", "Р" => "R", "С" => "S", "Т" => "T",
                    "У" => "U", "Ф" => "F", "Х" => "H", "Ц" => "TS", "Ч" => "CH",
                    "Ш" => "SH", "Щ" => "SCH", "Ъ" => "'", "Ы" => "YI", "Ь" => "",
                    "Э" => "E", "Ю" => "YU", "Я" => "YA", "а" => "a", "б" => "b",
                    "в" => "v", "г" => "g", "д" => "d", "е" => "e", "ж" => "zh",
                    "з" => "z", "и" => "i", "й" => "y", "к" => "k", "л" => "l",
                    "м" => "m", "н" => "n", "о" => "o", "п" => "p", "р" => "r",
                    "с" => "s", "т" => "t", "у" => "u", "ф" => "f", "х" => "h",
                    "ц" => "ts", "ч" => "ch", "ш" => "sh", "щ" => "sch", "ъ" => "",
                    "ы" => "yi", "ь" => "", "э" => "e", "ю" => "yu", "я" => "ya",
                    " " => "_", "," => "", "#" => "_"
        );

        return strtr($cyr_str, $tr);
    }

    function xlsBOF()
    {
        return pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0);
    }

    function xlsEOF()
    {
        return pack("ss", 0x0A, 0x00);
    }

    function xlsWriteNumber($Row, $Col, $Value)
    {
        return pack("sssss", 0x203, 14, $Row, $Col, 0x0).pack("d", $Value);
    }

    function xlsWriteLabel($Row, $Col, $Value)
    {
        $Value = perevod($Value);
        $L = strlen($Value);

        return pack("ssssss", 0x204, 8 + $L, $Row, $Col, 0x0, $L).$Value;
    }

    function perevod($p)
    {
        return iconv('UTF-8', 'WINDOWS-1251//IGNORE', $p);
    }
    //echo        php_strip_whitespace(__FILE__);

    function highlight_num($file)
    {
        $lines = implode(range(1, count(file($file))), '<br />');
        $content = highlight_file($file, true);


        echo '
    <style type="text/css">
        .num {
        float: left;
        color: gray;
        font-size: 13px;
        font-family: monospace;
        text-align: right;
        margin-right: 6pt;
        padding-right: 6pt;
        border-right: 1px solid gray;}

        body {margin: 0; margin-left: 5px;}
        td {vertical-align: top;}
        code {white-space: nowrap;}
    </style>';



        echo "<table><tr><td class=\"num\">\n$lines\n</td><td>\n$content\n</td></tr></table>";
    }
    //highlight_num(__FILE__);
    if (isset($_GET['code'])) { die(highlight_num(__FILE__)); }