<?php
function smarty_function_usd($params, &$smarty){

	$query = "SELECT * FROM SC_currency_types WHERE CID=10";
	$currency=mysql_fetch_object(mysql_query($query));
	
	$usd = $currency->currency_value;
	$usd = 1/$usd;
	$usd = number_format($usd, 2);
	
	return $usd;
}
?>