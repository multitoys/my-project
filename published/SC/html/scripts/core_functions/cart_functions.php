<?php

    // compare two configuration

    function CompareConfiguration($variants1, $variants2)

    {

        if (count($variants1) != count($variants2))

            return false;

        foreach ($variants1 as $variantID) {

            $count1 = 0;

            $count2 = 0;

            for ($i = 0; $i < count($variants1); $i++)

                if ((int)$variants1[$i] == (int)$variantID)

                    $count1++;

            for ($i = 0; $i < count($variants1); $i++)

                if ((int)$variants2[$i] == (int)$variantID)

                    $count2++;

            if ($count1 != $count2)

                return false;
        }

        return true;
    }

    // search configuration in session variable

    function SearchConfigurationInSessionVariable($variants, $productID)

    {

        foreach ($_SESSION["configurations"] as $key => $value) {

            if ((int)$_SESSION["gids"][$key] != (int)$productID)

                continue;

            if (CompareConfiguration($variants, $value))

                return $key;
        }

        return -1;
    }

    // search configuration in database

    function SearchConfigurationInDataBase($variants, $productID)

    {
        $customerID = (int)$_SESSION['cs_id'];
        
        $q = db_query("SELECT itemID FROM ".SHOPPING_CARTS_TABLE.

                      " WHERE customerID='".$customerID."'");

        while ($r = db_fetch_row($q)) {

            $q1 = db_query("SELECT COUNT(itemID) FROM ".SHOPPING_CART_ITEMS_TABLE.

                           " WHERE productID='".$productID."' AND itemID='".$r["itemID"]."'");

            $r1 = db_fetch_row($q1);

            if ($r1[0] != 0) {

                $variants_from_db = GetConfigurationByItemId($r["itemID"]);

                if (CompareConfiguration($variants, $variants_from_db))

                    return $r["itemID"];
            }
        }

        return -1;
    }

    function GetConfigurationByItemId($itemID)

    {
        static $variants_cache = array();
        $itemID = intval($itemID);
        if (!isset($variants_cache[$itemID])) {
            $variants_cache[$itemID] = array();
            if ($q = db_phquery("SELECT variantID FROM ?#SHOPPING_CART_ITEMS_CONTENT_TABLE where itemID=?", $itemID)) {
                while ($r = db_fetch_row($q)) {
                    $variants_cache[$itemID][] = $r["variantID"];
                }
            }
        }

        return $variants_cache[$itemID];
    }

    function InsertNewItem(

        $variants,

        $productID)

    {

        db_query("INSERT INTO ".SHOPPING_CART_ITEMS_TABLE.

                 "(productID) VALUES('".$productID."')");

        $itemID = db_insert_id();

        foreach ($variants as $var) {

            db_query("INSERT INTO ".

                     SHOPPING_CART_ITEMS_CONTENT_TABLE."(itemID, variantID) ".

                     "VALUES( '".$itemID."', '".$var."')");
        }

        return $itemID;
    }

    function InsertItemIntoCart($itemID)

    {
        $customerID = (int)$_SESSION['cs_id'];
        db_query("insert ".SHOPPING_CARTS_TABLE.

                 "(customerID, itemID, Quantity)".

                 "values( '".$customerID."', '".$itemID."', 1 )");
    }

    function GetStrOptions($variants)

    {

        static $res_cache = array();
        $variants = array_map('intval', $variants);
        $dbq = 'SELECT '.LanguagesManager::sql_prepareField('option_value', true).
               ',variantID FROM ?#PRODUCTS_OPTIONS_VALUES_VARIANTS_TABLE WHERE variantID IN(?@)';

        $is_cached = true;
        $non_cached_variants = array();
        foreach ($variants as $variantID) {
            if (!isset($res_cache[$variantID])) {
                $non_cached_variants[] = $variantID;
            }
        }
        if (count($non_cached_variants)) {
            if ($q = db_phquery($dbq, $non_cached_variants)) {
                while ($r = db_fetch_row($q)) {
                    if ($r['option_value']) {
                        $res_cache[$r['variantID']] = $r['option_value'];
                    }
                }
            }
        }

        $result = array();
        foreach ($variants as $variantID) {
            $result[] = $res_cache[$variantID];
        }

        return count($result)?implode(', ', $result):"";
    }

    function CodeItemInClient($variants, $productID)

    {

        $array = array();

        $array[] = $productID;

        foreach ($variants as $var)

            $array[] = $var;

        return implode("_", $array);
    }

    function DeCodeItemInClient($str)

    {

        // $variants, $productID

        $array = explode("_", $str);

        $productID = $array[0];

        $variants = array();

        for ($i = 1; $i < count($array); $i++)

            $variants[] = $array[$i];

        $res = array();

        $res["productID"] = $productID;

        $res["variants"] = $variants;

        return $res;
    }

    function GetProductInStockCount($productID)

    {

        $q = db_query("SELECT in_stock FROM ".

                      PRODUCTS_TABLE.

                      " WHERE productID='".$productID."'");

        $is = db_fetch_row($q);

        return $is[0];
    }

    function GetPriceProductWithOption($variants, $productID)

    {
        $q = db_query("SELECT Price, skidka, ukraine FROM ".PRODUCTS_TABLE." WHERE productID='".$productID."'");
        $r = db_fetch_row($q);

        $full_price = priceDiscount($r["Price"], $r["skidka"], $r["ukraine"]);

        return $full_price;
    }

    function GetProductIdByItemId($itemID)
    {

        $q = db_phquery('SELECT productID FROM '.SHOPPING_CART_ITEMS_TABLE.' WHERE itemID=?', $itemID);

        $r = db_fetch_assoc($q);

        return $r['productID'];
    }

    // *****************************************************************************

    // Purpose	clear cart content

    // Inputs

    // Remarks

    // Returns

    function cartClearCartContet($mode = 'succes')
    {

        $customerID = (int)$_SESSION['cs_id'];

        if (!is_null($customerID)) {
            if ($mode == 'erase') {
                $itemIDs = db_phquery_fetch(DBRFETCH_FIRST_ALL, 'SELECT itemID FROM ?#SHOPPING_CARTS_TABLE WHERE customerID=?', $customerID);
                if (is_array($itemIDs) && count($itemIDs)) {
                    db_phquery("DELETE FROM ?#SHOPPING_CART_ITEMS_CONTENT_TABLE WHERE itemID IN (?@)", $itemIDs);
                    db_phquery("DELETE FROM ?#SHOPPING_CART_ITEMS_TABLE WHERE itemID IN (?@)", $itemIDs);
                }
            }
            if ($mode != 'recalculate')
                db_phquery("DELETE FROM ?#SHOPPING_CARTS_TABLE WHERE customerID=?", $customerID);
            else
                db_phquery("UPDATE ?#SHOPPING_CARTS_TABLE SET Quantity=0 WHERE customerID=?", $customerID);
        }
    }

    /**
     * @return array
     */
    function cartGetCartContent($sort = 'name', $direction = 'ASC')
    {

        $cart_content = array();
        $total_price = 0;
        $freight_cost = 0;
        $variants = '';

        $currencyEntry = Currency::getSelectedCurrencyInstance();
        $customerID = (int)$_SESSION['cs_id'];

        //if(!is_null($customerEntry)){//get cart content from the database
        //$query         = "SELECT skidka FROM SC_customers WHERE customerID = $customerEntry->customerID";
        //$skidka        = mysql_fetch_object(mysql_query($query))->skidka;

        if (isset($customerID)) {

            $skidka = (int)$_SESSION['cs_skidka'];

            switch ($sort) {
                case 'name':
                    $sort_field = 't3.name_ru';
                    break;
                case 'Price':
                    $sort_field = 't3.Price';
                    break;
                case 'ostatok':
                    $sort_field = 't3.ostatok';
                    break;
                case 'Bonus':
                    $sort_field = 't3.Bonus';
                    break;
                case 'count':
                    $sort_field = 't1.Quantity';
                    break;
                default:
                    $sort_field = 't3.name_ru';
                    break;
            }

            $q = db_phquery('
			SELECT t3.productID, t3.name_ru, t3.Price, t3.product_code, t3.Bonus, t3.ostatok,
			       t1.itemID, t1.Quantity, t4.thumbnail, t4.filename 
			    FROM ?#SHOPPING_CARTS_TABLE t1
				LEFT JOIN ?#SHOPPING_CART_ITEMS_TABLE t2 ON t1.itemID=t2.itemID
				LEFT JOIN ?#PRODUCTS_TABLE t3 ON t2.productID=t3.productID
				LEFT JOIN ?#PRODUCT_PICTURES t4 ON t3.default_picture=t4.photoID
			WHERE customerID=? ORDER BY '.$sort_field.' '.$direction, $customerID);

            while ($cart_item = db_fetch_assoc($q)) {

                // get variants
                $variants = GetConfigurationByItemId($cart_item['itemID']);

                LanguagesManager::ml_fillFields(PRODUCTS_TABLE, $cart_item);

                $maxskidka = $cart_item['skidka'];
                $oneskidka = min($maxskidka, $skidka);

                $costUC = GetPriceProductWithOption($variants, $cart_item['productID']);
                // $costUC = doubleval (str_replace(",", "", $costUC));
                $Bonus = ($cart_item['Bonus'])?(int)$costUC:'';

                $tmp = array(
                    'productID'         => $cart_item['productID'],
                    'slug'              => $cart_item['slug'],
                    'id'                => $cart_item['itemID'],
                    'name'              => $cart_item['name'],
                    'thumbnail_url'     => $cart_item['thumbnail'] && file_exists(DIR_PRODUCTS_PICTURES.'/'.$cart_item['thumbnail'])?URL_PRODUCTS_PICTURES.'/'.$cart_item['thumbnail']:'',
                    'picture_url'       => $cart_item['filename'] && file_exists(DIR_PRODUCTS_PICTURES.'/'.$cart_item['filename'])?URL_PRODUCTS_PICTURES.'/'.$cart_item['filename']:'',
                    'ostatok'           => $cart_item['ostatok'],
                    'brief_description' => $cart_item['brief_description'],
                    'quantity'          => $cart_item['Quantity'],
                    'free_shipping'     => $cart_item['free_shipping'],
                    'costUC'            => show_price($costUC),
                    //'optprice'  => $cart_item['optprice'],
                    //'doza'  => $cart_item['doza'],
                    'Bonus'             => $Bonus * $cart_item['Quantity'],
                    'cost'              => show_price($cart_item['Quantity'] * $costUC),
                    'product_code'      => $cart_item['product_code'],
                );

                if ($tmp['thumbnail_url']) {

                    list($thumb_width, $thumb_height) = getimagesize(DIR_PRODUCTS_PICTURES.'/'.$cart_item['thumbnail']);
                    list($tmp['thumbnail_width'], $tmp['thumbnail_height']) = shrink_size($thumb_width, $thumb_height, round(CONF_PRDPICT_THUMBNAIL_SIZE / 2), round(CONF_PRDPICT_THUMBNAIL_SIZE / 2));
                }

                $freight_cost += $cart_item['Quantity'] * $cart_item['shipping_freight'];

                $strOptions = GetStrOptions(GetConfigurationByItemId($tmp['id']));
                if (trim($strOptions) !== '')
                    $tmp['name'] .= "  (".$strOptions.")";

                if ($cart_item['min_order_amount'] > $cart_item['Quantity'])
                    $tmp['min_order_amount'] = $cart_item['min_order_amount'];

                $total_price += $cart_item['Quantity'] * $costUC;

                $cart_content[] = $tmp;
            }
        }

        return array(
            'cart_content' => $cart_content,
            'total_price'  => $total_price,
            'freight_cost' => $freight_cost
        );
    }

    function cartCheckMinOrderAmount()
    {
        $cart_content = cartGetCartContent();
        $cart_content = $cart_content['cart_content'];

        foreach ($cart_content as $cart_item) {
            if (isset($cart_item['min_order_amount'])) {
                return false;
            }
        }

        return true;
    }

    function cartCheckMinTotalOrderAmount()
    {
        $res = cartGetCartContent();
        $d = oaGetDiscountValue($res, '');

        $order['order_amount'] = $res['total_price'] - $d;

        $check = true;

        if ($order['order_amount'] < CONF_MINIMAL_ORDER_AMOUNT) {
            $check = false;
        }

        return $check;
    }

    function cartUpdateAddCounter($productID)
    {
        db_phquery("UPDATE ?#PRODUCTS_TABLE SET add2cart_counter=(add2cart_counter+1) WHERE productID=?", $productID);
        //TODO: add_metric_code
        /*
         include_once('class.metric.php');
        $metric = metric::getInstance();
        
        $metric->addAction($DB_KEY, $currentUser, 'SC', _ACTION_, _CLIENT_, _DATA_);
        
        _ACTION_ - DOWNLOAD/UPLOAD/ADDCONTACT/etc...
        _CLIENT_ - FLASH/JAVA/etc.. (default WA)
        _DATA_ - данные поясняющие действие.
         */
        if (SystemSettings::is_hosted() && file_exists(WBS_DIR.'/kernel/classes/class.metric.php')) {
            include_once(WBS_DIR.'/kernel/classes/class.metric.php');

            $DB_KEY = strtoupper(SystemSettings::get('DB_KEY'));
            $U_ID = sc_getSessionData('U_ID');

            $metric = metric::getInstance();
            $metric->addAction($DB_KEY, $U_ID, 'SC', 'ADD2CART', isset($_GET['widgets'])?'WIDGET':'STOREFRONT', '');
        }
    }

    function cartMinimizeCart()
    {
        $customerID = (int)$_SESSION['cs_id'];

        if ($customerID) {
            $itemIDs = db_phquery_fetch(DBRFETCH_FIRST_ALL, 'SELECT itemID FROM ?#SHOPPING_CARTS_TABLE WHERE Quantity=0 AND customerID=?', $customerID);
            if (is_array($itemIDs) && count($itemIDs)) {
                db_phquery("DELETE FROM ?#SHOPPING_CART_ITEMS_CONTENT_TABLE WHERE itemID IN (?@)", $itemIDs);
                db_phquery("DELETE FROM ?#SHOPPING_CART_ITEMS_TABLE WHERE itemID IN (?@)", $itemIDs);
            }
            db_phquery("DELETE FROM ?#SHOPPING_CARTS_TABLE WHERE Quantity=0 AND customerID=?", $customerID);
        }
    }

    /**
     * Add to cart product with options
     *
     * @param int   $productID
     * @param array $variants - row is variantID
     * @param int   $qty
     */

    function cartAddToCart($productID, $variants, $qty = 0)
    {
        //if($qty === ''){$qty = 0;}
        $qty = max(0, (int)$qty);

        $productID = (int)$productID;

        $product_data = GetProduct($productID);

        if (!$product_data['ordering_available']) return false;

        if (!$product_data['enabled']) return false;

        $is = (int)$product_data['in_stock'];

        $min_order_amount = $product_data['min_order_amount'];

        //$min_order_amount = db_phquery_fetch(DBRFETCH_FIRST, "SELECT min_order_amount FROM ?#PRODUCTS_TABLE WHERE productID=?", $productID );

        if (isset($_SESSION['log'])) { //authorized customer - get cart from database

            $itemID = SearchConfigurationInDataBase($variants, $productID);
            $customerID = (int)$_SESSION['cs_id'];
            if (is_null($customerID)) return false;

            if ($itemID != -1) { // if this configuration exists in database
                $quantity = db_phquery_fetch(DBRFETCH_FIRST, "SELECT Quantity FROM ?#SHOPPING_CARTS_TABLE WHERE customerID=? AND itemID=?", $customerID, $itemID);
                if (CONF_CHECKSTOCK != 0) {
                    $qty = min($qty, $is - $quantity);
                }
                $qty = max($qty, 0);
                if (CONF_CHECKSTOCK == 0 || ($quantity + $qty <= $is && $is)) {
                    db_phquery("UPDATE ?#SHOPPING_CARTS_TABLE SET Quantity=Quantity+? WHERE customerID=? AND itemID=?", $qty, $customerID, $itemID);
                } else {
                    return $quantity;
                }
            } else { //insert new item

                $qty = max($qty, $min_order_amount);
                if (CONF_CHECKSTOCK != 0 && $qty > $is) {
                    $qty = min($qty, $is);
                }
                if ((CONF_CHECKSTOCK == 0 || $is >= $qty) && $qty > 0) {
                    $itemID = InsertNewItem($variants, $productID);
                    InsertItemIntoCart($itemID);
                    db_phquery("UPDATE ?#SHOPPING_CARTS_TABLE SET Quantity=Quantity+? WHERE customerID=? AND itemID=?",
                               $qty - 1, $customerID, $itemID);
                    cartUpdateAddCounter($productID);
                } else {
                    return 0;
                }
            }
        }

        //db_phquery("UPDATE ?#PRODUCTS_TABLE SET add2cart_counter=(add2cart_counter+1) WHERE productID=?",$productID);
        return true;
    }

    // *****************************************************************************

    // Purpose
    // Inputs	$customerID - customer ID
    // Remarks
    // Returns	returns true if cart is empty for this customer

    function cartCartIsEmpty($log)
    {
        $customerID = $_SESSION['cs_id'];

        if ((int)$customerID > 0) {

            $customerID = (int)$customerID;

            $q_count = db_query("SELECT count(*) FROM ".SHOPPING_CARTS_TABLE." WHERE customerID=".$customerID);
            $count = db_fetch_row($q_count);
            $count = $count[0];

            return ($count == 0);
        } else {
            return true;
        }
    }