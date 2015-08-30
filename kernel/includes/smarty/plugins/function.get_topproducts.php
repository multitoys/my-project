<?php

function smarty_function_get_topproducts( $params, &$smarty )
{
	extract($params);
    if( !empty($params['var']) )
    {
        $where = '';
        $limit = isset($params['limit']) ? 'LIMIT '.(int)$params['limit'] : '';
        $top = $params['var'];
        // $cust = $params['cust_data'];
        // $skidka1 = $cust['cust_skidka'];
        // $special = $cust['cust_price'];
        // $margin = $cust['cust_margin'];

        if( CONF_CHECKSTOCK ) {
            $where .= " AND ";
            $where .= "in_stock > 0";
        }

        $sql = "
            SELECT productID, ".LanguagesManager::sql_prepareField("name")." AS name,
            Price, SpecialPrice, list_price, items_sold, sort_order, akcia, akcia_skidka, skidka, default_picture, slug FROM ".PRODUCTS_TABLE."
            WHERE enabled != 0 AND categoryID > 1 AND $top > 0 AND zakaz <> 1 ".$where."
            ORDER BY  RAND() ".$limit."
        ";
        $q = db_query($sql);
        $products=array();
        while( $row = db_fetch_assoc($q) )
        {
            // $row["productID_str"] = $row["productID"];
			$price = ZCalcPrice($row["Price"], $row["SpecialPrice"], $row["skidka"]);
            $row["price_str"] = show_price($price);
			$row["list_price_str"] = show_price($row["list_price"]);
			// $row["akcia_skidka_str"] = $row["akcia_skidka"];
            $products[] = $row;
        }

        _setPictures($products);

        $smarty->assign('top', $top);
        $smarty->assign($params["var"], $products);
    }
}
?>