<?php
function smarty_modifier_cs_vip($log)
{
  // $query = "
// SELECT count(*)  FROM SC_customers
// WHERE vip = 1 AND Login = '".$log."' ";  

$query = "
SELECT vip  FROM SC_customers
WHERE  Login = '".$log."' ";

  $res   = mysql_query($query) or die(mysql_error()."<br>$query");
  $row = mysql_fetch_row($res);
  return intval($row[0]);
}
?>