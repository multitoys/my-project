<?php

/*
 * Smarty plugin
 * -------------------------------------------------------------
 * Type:     modifier
 * Name:     count
 * Purpose:  Returns the number of elements in var
 * -------------------------------------------------------------
 */
function smarty_modifier_withSkidka($productID)
{
  if(isset($_SESSION["log"])) $log = $_SESSION["log"];
  else return 0;
  // GetCustomer
  // $query = "SELECT * FROM SC_customers WHERE Login='".$log."'";
  // $Customer=mysql_fetch_object(mysql_query($query));
  // Get Product
  $query = "SELECT * FROM SC_products WHERE productID = $productID";
  $Product = mysql_fetch_object(mysql_query($query));

  $price = ZCalcPrice($Product->Price, $Product->SpecialPrice, $Product->skidka);

  $price = number_format($price, 2);

	return $price;
}

?>