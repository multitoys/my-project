<?php

    ini_set('display_errors', false);
    define('DIR_ROOT', $_SERVER['DOCUMENT_ROOT'].'/published/SC/html/scripts');
    $DebugMode = false;
    $Warnings = array();
    include_once(DIR_ROOT.'/includes/init.php');
    include_once(DIR_CFG.'/connect.inc.wa.php');
    include(DIR_FUNC.'/setting_functions.php');
//  include(DIR_ROOT.'/popup/class.phpmailer.php');
    $DB_tree = new DataBase();
    $DB_tree->connect(SystemSettings::get('DB_HOST'), SystemSettings::get('DB_USER'), SystemSettings::get('DB_PASS'));

    $DB_tree->selectDB(SystemSettings::get('DB_NAME'));
    define('VAR_DBHANDLER', 'DBHandler');


    $prefix = GetValue("settings_value", "SC_settings", "settings_constant_name = 'CONF_ORDERID_PREFIX'");
    $email_to = GetValue("settings_value", "SC_settings", "settings_constant_name = 'CONF_ORDERS_EMAIL'");

    $query = "SELECT orderID, customerID, order_time FROM SC_orders WHERE statusID = 2"; // Новые заказы
    $res = mysql_query($query) or die(mysql_error()."<br>$query");
    // echo("Num_orders:".mysql_num_rows($res)."<br><br>");

    while ($row = mysql_fetch_object($res)) {

        $file_content  = MakeXLS($row->orderID, 0);
        $file_content1 = MakeXLS($row->orderID, 1);
        $file_content3 = MakeXLS($row->orderID, 2);

        $name = GetValue("CONCAT(first_name, ' ', last_name)", 'SC_customers', "customerID = $row->customerID");

        $filename  = "$row->orderID-$name-$row->order_time.xls";
        $filename1 = my_translit($filename);
        $filename2 = my_translit('Cast-Zakaz'.$filename);
        $filename3 = my_translit('TILLY'.$filename);

        $thm = "Новый заказ $row->orderID-$name-$row->order_time";

        $msg1 = "Основной заказ $row->orderID-$name с MULTITOYS";
        $msg  = "Пазлы и товары под заказ $row->orderID-$name с MULTITOYS";
        $msg3 = "Тилли Супер-Акция $row->orderID-$name с MULTITOYS";

        // Отправляем почтовое сообщение
        $o = send_mail2($email_to, $thm, $msg1, $filename1, $file_content);

        if ($file_content1) {
            $o = send_mail2($email_to, $thm, $msg, $filename2, $file_content1);
        }

        if ($file_content3) {
            $o = send_mail2($email_to, $thm, $msg3, $filename3, $file_content3);
        }

        if ($o == 1) {
            $query = "UPDATE SC_orders SET statusID = 5 WHERE orderID = $row->orderID";
            $res2 = mysql_query($query) or die(mysql_error()."<br>$query");
        }

        $o = $o ? "Ваш заказ $prefix<b>$row->orderID</b> - <span style='color: red'>успешно отправлен</span>!<br>
				 В ближайшее время мы свяжемся с вами." :
            '<span style="color: red">К сожалению, произошла ошибка отправки!</span><br>
				 Свяжитесь, пожалуйста со своим менеджером или позвоните по номеру +38(056) 374-54-23';
        echo($o);
    }

    function GetNonAutomatic($orderID)
    {
        $text = '';
        $query = "
SELECT SC_products.code_1c, SC_products.product_code, SC_ordered_carts.name, 	SC_ordered_carts.Price, SC_ordered_carts.Quantity
FROM SC_ordered_carts
LEFT JOIN SC_shopping_cart_items ON SC_shopping_cart_items.itemID = SC_ordered_carts.itemID
LEFT JOIN SC_products ON SC_products.productID = SC_shopping_cart_items.productID
LEFT JOIN SC_categories ON SC_categories.categoryID = SC_products.categoryID
WHERE SC_ordered_carts.orderID = $orderID AND NOT SC_categories.allow_products_comparison
";
        $res = mysql_query($query) or die(mysql_error()."<br>$query");
        if (mysql_num_rows($res)) {
            $text = '
<h1>Товары в заказе без автоматической обработки:</h1>
<table cellspacing="0" cellpadding="4" border="1" align="center">
  <tr>
    <td>#</td>
    <td>Код 1С</td>
    <td>Артикул</td>
    <td>Наименование</td>
    <td>Цена</td>
    <td>Количество</td>
    <td>Стоимость</td>
  </tr>
';
            $no = 0;
            $q = 0;
            while ($row = mysql_fetch_object($res)) {
                $no++;
                $text .= "
  <tr>
    <td>$no</td>
    <td>$row->code_1c</td>
    <td>$row->product_code</td>
    <td>$row->name</td>
    <td>".number_format($row->Price, 2, ',', '')."</td>
    <td>$row->Quantity</td>
    <td>".number_format($row->Price * $row->Quantity, 2, ',', '')."</td>
  </tr>
";
                $q += $row->Price * $row->Quantity;
            }
            $q = number_format($q, 2, ',', '');
            $text .= "
  <tr>
    <td colspan='6'>Итого:</td>
    <td>$q</td>
  </tr>
</table>";
        } else {
            $text = 'В заказе отсутствуют товары без автоматической обработки.';
        }

        return $text;
    }

    function MakeXLS($orderID, $per)
    {
        $textout = xlsBOF();

        $sql = "SELECT shipping_firstname,shipping_lastname,shipping_country,shipping_state,shipping_zip,shipping_city,shipping_address
FROM SC_orders
WHERE orderID = $orderID ";
        $res = mysql_query($sql) or die(mysql_error()."<br>$sql");
        $row = mysql_fetch_object($res);
        /*$imy = $row->shipping_firstname;
        $family = $row->shipping_lastname;*/
        $strana = $row->shipping_country;
        $obl = $row->shipping_state;
        /*$ship_zip = $row->shipping_zip;*/
        $gorod = $row->shipping_city;
        $adres = $row->shipping_address;

        /* $mod = ($per==0)?
                         'SC_categories.allow_products_comparison OR SC_categories.allow_products_comparison IS NULL'
                        :'NOT SC_categories.allow_products_comparison'; */

        if ($per == 0) {
            $mod = 'SC_products.zakaz=0  AND SC_products.eproduct_available_days!=7  AND SC_categories.allow_products_comparison';
        } elseif ($per == 1){
            $mod = 'NOT SC_categories.allow_products_comparison OR SC_products.zakaz!=0 AND SC_products.eproduct_available_days!=7';
        } else {
            $mod = 'SC_products.eproduct_available_days=7';
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

            $textout .= xlsWriteLabel(0, 9, 'Адрес');
            $textout .= xlsWriteLabel(2, 9, 'Страна');
            $textout .= xlsWriteLabel(2, 10, $strana);

            $textout .= xlsWriteLabel(3, 9, 'Область');
            $textout .= xlsWriteLabel(3, 10, $obl);

            $textout .= xlsWriteLabel(4, 9, 'Город');
            $textout .= xlsWriteLabel(4, 10, $gorod);

            $textout .= xlsWriteLabel(5, 9, 'Адрес');
            $textout .= xlsWriteLabel(5, 10, $adres);

            $textout .= xlsWriteLabel(0, 1, 'Дата заявки');
            $textout .= xlsWriteLabel(0, 2, date('d/m/Y'));

            $textout .= xlsWriteLabel(1, 1, 'Код контрагента');
            $textout .= xlsWriteLabel(1, 2, $custCod);

            $textout .= xlsWriteLabel(1, 4, 'Наименование контгагента:');
            $textout .= xlsWriteLabel(1, 5, $custFIO);

            $textout .= xlsWriteLabel(3, 0, '№');
            $textout .= xlsWriteLabel(3, 1, 'Код категории');
            $textout .= xlsWriteLabel(3, 2, 'Артикул');
            $textout .= xlsWriteLabel(3, 3, 'Код товара');
            $textout .= xlsWriteLabel(3, 4, 'Товар');
            $textout .= xlsWriteLabel(3, 5, 'Количество');
            $textout .= xlsWriteLabel(3, 6, 'Цена');
            $textout .= xlsWriteLabel(3, 7, 'Сумма');

            $no = 3;
            $q = 0;
            while ($row = mysql_fetch_object($res)) {
                $no++;
                $textout .= xlsWriteNumber($no, 0, $no - 3);
                $textout .= xlsWriteLabel($no, 1, $row->catname);
                $textout .= xlsWriteLabel($no, 2, $row->product_code);
                $textout .= xlsWriteLabel($no, 3, $row->code_1c);
                $textout .= xlsWriteLabel($no, 4, $row->name);
                $textout .= xlsWriteNumber($no, 5, $row->Quantity);
                $textout .= xlsWriteNumber($no, 6, number_format($row->Price, 2, ',', ''));
                $textout .= xlsWriteNumber($no, 7, number_format($row->Price * $row->Quantity, 2, ',', ''));
                $q += $row->Price * $row->Quantity;
            }
            $textout .= xlsEOF();
        } else {
            $textout = false;
        }

        return $textout;
    }

    function send_mail2($mail_to, $thema, $html, $filename, $file_content)
    {
        $mailer = new PHPMailer();
        $mail_from = GetValue("settings_value", "SC_settings", "settings_constant_name = 'CONF_GENERAL_EMAIL'");;

        $mailer->Priority = 1;

        $mailer->From = $mail_from;
        $mailer->FromName = '';

        $mailer->AddAddress($mail_to);
        $mailer->Subject = $thema;
        $mailer->Body = $html;

        $mailer->CharSet = 'utf-8';
        $mailer->IsHTML(true);

        if (strlen($file_content) > 5) {
            $mailer->AddStringAttachment($file_content, $filename);
            $res = $mailer->Send();
        }
        return $res;
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
        $tr = array('Ґ' => 'G', 'Ё' => 'YO', 'Є' => 'E', 'Ї' => 'YI', 'І' => 'I',
                    'і' => 'i', 'ґ' => 'g', 'ё' => 'yo', '№' => '_', 'є' => 'e',
                    'ї' => 'yi', 'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G',
                    'Д' => 'D', 'Е' => 'E', 'Ж' => 'ZH', 'З' => 'Z', 'И' => 'I',
                    'Й' => 'Y', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N',
                    'О' => 'O', 'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T',
                    'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'TS', 'Ч' => 'CH',
                    'Ш' => 'SH', 'Щ' => 'SCH', 'Ъ' => '\'', 'Ы' => 'YI', 'Ь' => '',
                    'Э' => 'E', 'Ю' => 'YU', 'Я' => 'YA', 'а' => 'a', 'б' => 'b',
                    'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ж' => 'zh',
                    'з' => 'z', 'и' => 'i', 'й' => 'y', 'к' => 'k', 'л' => 'l',
                    'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r',
                    'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h',
                    'ц' => 'ts', 'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch', 'ъ' => '',
                    'ы' => 'yi', 'ь' => '', 'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
                    ' ' => '_', ',' => '', '#' => '_'
        );

        return strtr($cyr_str, $tr);
    }

    function xlsBOF()
    {
        return pack('ssssss', 0x809, 0x8, 0x0, 0x10, 0x0, 0x0);
    }

    function xlsEOF()
    {
        return pack('ss', 0x0A, 0x00);
    }

    function xlsWriteNumber($Row, $Col, $Value)
    {
        return pack('sssss', 0x203, 14, $Row, $Col, 0x0).pack('d', $Value);
    }

    function xlsWriteLabel($Row, $Col, $Value)
    {
        $Value = perevod($Value);
        $L = strlen($Value);

        return pack('ssssss', 0x204, 8 + $L, $Row, $Col, 0x0, $L).$Value;
    }

    function perevod($p)
    {
        return iconv('UTF-8', 'WINDOWS-1251//IGNORE', $p);
    }