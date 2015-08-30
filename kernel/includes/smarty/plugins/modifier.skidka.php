<?php

/*
 * Smarty plugin
 * -------------------------------------------------------------
 * Type:     modifier
 * Name:     basename
 * Purpose:  see basename() in php manual
 * -------------------------------------------------------------
 */
function smarty_modifier_skidka($log)
{
  $query = "
SELECT  skidka  FROM SC_customers
WHERE  ignore_skidka != 1 AND Login = '$log' ";

  $res   = mysql_query($query) or die(mysql_error()."<br>$query");
  if ($row = mysql_fetch_row($res)) {
	echo "<div style='text-align: center; color: #ffff00;'><b>скидка -&nbsp;".$row[0]."%</b></div>";
	return ;
	}else
	{return false;}
  
}
?>