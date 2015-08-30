<?php

/*
 * Smarty plugin
 * -------------------------------------------------------------
 * Type:     modifier
 * Name:     basename
 * Purpose:  see basename() in php manual
 * -------------------------------------------------------------
 */
function smarty_modifier_cs_import_lock($import)
{
  $query = "
SELECT *  FROM SC_import_lock where `lock` = 1";

  $res   = mysql_query($query) or die(mysql_error()."<br>$query");

  if (mysql_num_rows($res)>0)
  {
    return true;
  }
  else
  {
    return false;
  }
}

/* vim: set expandtab: */

?>