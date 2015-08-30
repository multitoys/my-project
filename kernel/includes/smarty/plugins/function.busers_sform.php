<?php

/**
 * Smarty plugin
 * -------------------------------------------------------------
 * Type:     function
 * Name:     busers_sform
 * Purpose:  return a tradeform from customers regfilds
 * -------------------------------------------------------------
 */
 
function smarty_function_busers_sform($params, &$smarty)
{
	$idad = $smarty->get_template_vars('custforadid');
	$query = "
				SELECT reg_field_value FROM SC_customer_reg_fields_values
				WHERE customerID = $idad AND reg_field_ID = 7
				";

	$res = mysql_query($query) or die(mysql_error()."<br>$query");

	$row = mysql_fetch_row($res);
	
	$u_sale = $row[0];
		
	echo "<span style='color:green'>$u_sale</span>";
}
?>