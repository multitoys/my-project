<?php

function smarty_function_get_top_ua( $params, &$smarty )
{
	extract($params);
    if( !empty($params['var']) )
    {
        $top_ua = $params['var'];

        $sql = '
            SELECT productID, name_ru AS name,
            Price, list_price, items_sold, sort_order, skidka, 
            ukraine, default_picture, slug 
            FROM SC_products
            WHERE enabled AND categoryID > 1 AND items_sold > 0 AND in_stock > 0 AND ukraine > 0 
            ORDER BY  items_sold DESC LIMIT 9
        ';
        $q = db_query($sql);
        $tops=array();
        while( $row = db_fetch_assoc($q) )
        {
            $price = ZCalcPrice($row['Price'], $row['skidka'], $row['ukraine']);
            $row['price_str'] = show_price($price);
            $tops[] = $row;
        }

        _setPictures($tops);

        $smarty->assign('top_ua', $top_ua);
        $smarty->assign($params['var'], $tops);
    }
}