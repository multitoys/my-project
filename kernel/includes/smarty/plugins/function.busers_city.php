<?php

/**
 * Smarty plugin
 * -------------------------------------------------------------
 * Type:     function
 * Name:     busers_city
 * Purpose:  return a city from customers addresess
 * -------------------------------------------------------------
 */
 
function smarty_function_busers_city($params, &$smarty)
{
	$idad = $smarty->get_template_vars('custforadid');
	$query = "
				SELECT city FROM SC_customer_addresses
				WHERE customerID = $idad
				";

	$res = mysql_query($query) or die(mysql_error()."<br>$query");

	$row = mysql_fetch_row($res);
	
	$color = 'blue';
	$u_city = $row[0];
	if ($u_city == "Днепропетровск" or $u_city == "днепропетровск" or $u_city == "ДНЕПРОПЕТРОВСК")
		$color = 'red';
		
	echo "<span style='color:$color'>$u_city</span>";
}
?>