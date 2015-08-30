<?php

function smarty_function_get_topview( $params, &$smarty )
{
    if( !empty($params["var"]) )
    {
        $where = "";
        $limit = isset($params["limit"]) ? "LIMIT ".(int)$params["limit"] : "";

        if( CONF_CHECKSTOCK ) {
            $where .= " AND ";
            $where .= "in_stock > 0";
        }

        // $sql = "
            // SELECT productID, ".LanguagesManager::sql_prepareField("name")." AS name,
            // Price, default_picture, slug FROM ".PRODUCTS_TABLE."
            // WHERE enabled != 0 AND categoryID > 1 ".$where."
            // ORDER BY viewed_times DESC ".$limit."
        // ";        
		  $sql = "
            SELECT * FROM SC_product_list_item
LEFT JOIN SC_products ON SC_products.productID = SC_product_list_item.productID
LEFT JOIN SC_product_pictures ON SC_product_pictures.productID = SC_products.productID
WHERE list_id = 'hitu'
ORDER BY RAND() $limit";
        
        $q = db_query($sql);
        $products=array();
        while( $row = db_fetch_row($q) )
        {
            $row["price_str"] = show_price($row["Price"]);
            $products[] = $row;
        }

        _setPictures($products);

        $smarty->assign($params["var"], $products);
    }
}

