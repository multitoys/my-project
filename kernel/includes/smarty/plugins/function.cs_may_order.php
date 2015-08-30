<?php

/**
 * Smarty plugin
 * -------------------------------------------------------------
 * Type:     function
 * Name:     count
 * Purpose:  assign a number of array members to 'item' param
 * -------------------------------------------------------------
 */
function smarty_function_cs_may_order($params, &$smarty)
{
  $log = $_SESSION['log'];
  $query = "
SELECT * FROM SC_customers
WHERE may_order_until > CURRENT_TIMESTAMP AND Login = '$log' ";

  $res   = mysql_query($query) or die(mysql_error()."<br>$query");

  if (mysql_num_rows($res)>0)
  {
    $row = mysql_fetch_object($res);
    return $row->may_order_until;
  }
  else
  {
    return 0;
  }
}
?>