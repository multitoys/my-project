<?php

    ini_set('display_errors', false);
    define('DIR_ROOT', $_SERVER['DOCUMENT_ROOT']."/published/SC/html/scripts");
    $DebugMode = false;
    $Warnings = array();
    include_once(DIR_ROOT.'/includes/init.php');
    include_once(DIR_FUNC.'/functions.php');
    include_once(DIR_CFG.'/connect.inc.wa.php');
    include(DIR_FUNC.'/setting_functions.php');

    $DB_tree = new DataBase();
    $DB_tree->connect(SystemSettings::get('DB_HOST'), SystemSettings::get('DB_USER'), SystemSettings::get('DB_PASS'));

    $DB_tree->selectDB(SystemSettings::get('DB_NAME'));
    define('VAR_DBHANDLER', 'DBHandler');

    // $current_currency = $_SESSION['current_currency'];

    if (isset($_SESSION['cs_id'])) {
// $Customer = mysql_fetch_object($res);
        $cs_id = $_SESSION['cs_id'];
        $query = "
	SELECT SC_shopping_carts.Quantity, SC_shopping_carts.itemID, SC_products.Price, SC_products.skidka, SC_products.ukraine
      FROM SC_shopping_carts
      LEFT JOIN SC_shopping_cart_items ON SC_shopping_carts.itemID = SC_shopping_cart_items.itemID
      LEFT JOIN SC_products ON SC_shopping_cart_items.productID = SC_products.productID 
	WHERE SC_shopping_carts.customerID  = $cs_id";
        $res = mysql_query($query) or die(mysql_error().'<br>ERROR!');

        $no = 0;
        $esumma = 0;

        while ($Product = mysql_fetch_object($res)) {
            $price = ZCalcPrice($Product->Price, $Product->skidka, $Product->ukraine);
            // $price = show_price($price);
            $stoimost = $Product->Quantity * $price;
            $esumma += $stoimost;
            $no += $Product->Quantity;
        }
    }
    $current_currency = $_SESSION["current_currency"];
    $query = "
            SELECT * FROM SC_currency_types
            WHERE CID = $current_currency LIMIT 1";
    $currency = mysql_fetch_object(mysql_query($query));
    $currency_value = $currency->currency_value;
    $display_template_ru = $currency->display_template_ru;
    $cartsum = $esumma * $currency_value;
    $csumma = str_replace('{value}', number_format($cartsum, 2, ',', ' '), $display_template_ru);

    //$quantity  = number_format ($esumma, 2, ',', ' ')."&nbsp;&#8372;";
    if ($no != 0) {
        $quantity = $csumma;
        $quantity2 = "$no товар(ов):";
    } else {
        $quantity2 = 'В корзине';
        $quantity = 'нет товаров';
    }
    $link2cart = "<a href='/cart/' >";

    $cartin = "<div class='cpt_shopping_cart_info' id='cart_not_empty'>
<div id='shpcrtgc'>$link2cart$quantity2</a></div>
<div  class='shpcrtca'>$link2cart$quantity</a></div>
</div>";
    $cart = "
<a href='/cart/' class='my-button'>
$cartin
</a>
";
    echo $cartin;