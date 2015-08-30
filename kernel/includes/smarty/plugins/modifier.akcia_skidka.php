<?php

function smarty_modifier_akcia_skidka($productID)
{
  $query = "
SELECT akcia_skidka  FROM SC_products
WHERE  productID = '".$productID."' ";

  $res   = mysql_query($query) or die(mysql_error()."<br>$query");
  $row = mysql_fetch_row($res);
  return intval($row[0]);
}

/* vim: set expandtab: */

?>