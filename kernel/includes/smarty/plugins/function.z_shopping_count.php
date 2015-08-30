<?php

	function smarty_function_z_shopping_count($params, &$smarty) {

		if(isset($params['productID'])) {	
			$productID = $params['productID'];
			$count = get_shop_cout($productID);
			return $count;
		} else {
			return '0';
		}
	}

	function get_shop_cout($productID) {

		$customerID = isset($_SESSION['cs_id'])?$_SESSION['cs_id']:'';
		
		if ($customerID) {
            $query = '
                      SELECT
                          a.Quantity
                      FROM
                          SC_shopping_carts a,
                          SC_shopping_cart_items b
                      WHERE
                          a.customerID = '.$customerID.'
                          AND
                          a.itemID = b.itemID
                          AND
                          b.productID = '.$productID
            ;
            $count = (int)mysql_fetch_object(mysql_query($query))->Quantity;
            return $count;
		} else {
				return '';
		}
	}