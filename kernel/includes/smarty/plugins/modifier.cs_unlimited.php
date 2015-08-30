<?php

/*
 * Smarty plugin
 * -------------------------------------------------------------
 * Type:     modifier
 * Name:     basename
 * Purpose:  see basename() in php manual
 * -------------------------------------------------------------
 */
function smarty_modifier_cs_unlimited($log)
{
  $query = "
SELECT count(*)  FROM SC_customers
WHERE unlimited_order = 1 AND Login = '".$log."' ";

  $res   = mysql_query($query) or die(mysql_error()."<br>$query");
  $row = mysql_fetch_row($res);
  return intval($row[0]);
}

/* vim: set expandtab: */

?>