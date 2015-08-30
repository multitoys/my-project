<?php

/*
 * Smarty plugin
 * -------------------------------------------------------------
 * Type:     modifier
 * Name:     basename
 * Purpose:  see basename() in php manual
 * -------------------------------------------------------------
 */
function smarty_modifier_cs_may_order($log)
{
  $query = "
SELECT may_order_until  FROM SC_customers
WHERE (may_order_until > CURRENT_TIMESTAMP OR unlimited_order = 1) AND Login = '$log' ";

  $res   = mysql_query($query) or die(mysql_error()."<br>$query");

  if (mysql_num_rows($res)>0)
  {
    $row = mysql_fetch_object($res);
    $date = date('H:i:s d/m/Y', strtotime($row->may_order_until));
    return $date;
  }
  else
  {
    return false;
  }
}

/* vim: set expandtab: */

?>