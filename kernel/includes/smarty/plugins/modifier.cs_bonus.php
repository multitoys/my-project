<?php

/*
 * Smarty plugin
 * -------------------------------------------------------------
 * Type:     modifier
 * Name:     basename
 * Purpose:  see basename() in php manual
 * -------------------------------------------------------------
 */
function smarty_modifier_cs_bonus($log)
{
  $query = "
SELECT  1C  FROM SC_customers
WHERE  Login = '$log' ";

  $res   = mysql_query($query) or die(mysql_error()."<br>$query");
  $row = mysql_fetch_row($res);
  return $row[0];
}

/* vim: set expandtab: */

?>