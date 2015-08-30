<?php
/* function smarty_modifier_usd($log) */
/* {
  $query = "
SELECT  skidka  FROM SC_customers
WHERE  Login = '$log' ";

  $res   = mysql_query($query) or die(mysql_error()."<br>$query");
  $row = mysql_fetch_row($res);
  return $row[0];
} */
function smarty_modifier_usd($log){

if(isset($_SESSION["log"])) $log = $_SESSION["log"];
  else return 0;
  // GetCustomer
  $query = "SELECT * FROM SC_customers WHERE Login='".$log."'";
  $Customer=mysql_fetch_object(mysql_query($query));
  $usd2=$Customer->vip;
  
  
  switch ($usd2) {
    case 1:
        $query = "SELECT * FROM SC_currency_types WHERE CID=11";
        break;
    case 2:
        $query = "SELECT * FROM SC_currency_types WHERE CID=12";
        break;
    case 3:
        $query = "SELECT * FROM SC_currency_types WHERE CID=13";
        break;
    case 4:
        $query = "SELECT * FROM SC_currency_types WHERE CID=14";
        break;
	 default:
		  $query = "SELECT * FROM SC_currency_types WHERE CID=10";
}

  // if ($usd2){
  // $query = "SELECT * FROM SC_currency_types WHERE CID=10";}
  // else{
  // $query = "SELECT * FROM SC_currency_types WHERE CID=11";}
  
  $currency=mysql_fetch_object(mysql_query($query));

  $usd = $currency->currency_value;
  $usd = 1/$usd;
  $usd = number_format($usd, 2);

	return $usd;
}



?>