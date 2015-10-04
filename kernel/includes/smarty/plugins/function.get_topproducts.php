<?php

function smarty_function_get_topproducts( $params, &$smarty )
{
	extract($params);
    if( !empty($params['var']) )
    {
        $where = '';
        $limit = isset($params['limit']) ? 'LIMIT '.(int)$params['limit'] : '';
        $top = $params['var'];

        if( CONF_CHECKSTOCK ) {
            $where .= ' AND ';
            $where .= 'in_stock > 0';
        }

        $sql = "
            SELECT productID, name_ru AS name,
            Price, list_price, items_sold, sort_order, akcia, akcia_skidka, skidka, ukraine, default_picture, slug FROM ".PRODUCTS_TABLE."
            WHERE enabled != 0 AND categoryID > 1 AND $top > 0 AND zakaz <> 1 ".$where."
            ORDER BY  RAND() ".$limit."
        ";
        $q = db_query($sql);
        $products=array();
        while( $row = db_fetch_assoc($q) )
        {
            $price = ZCalcPrice($row['Price'], $row['skidka'], $row['ukraine']);
            $row['price_str'] = show_price($price);
            $row['list_price_str'] = show_price($row['list_price']);
            $products[] = $row;
        }

        _setPictures($products);

        $smarty->assign('top', $top);
        $smarty->assign($params['var'], $products);
    }
}