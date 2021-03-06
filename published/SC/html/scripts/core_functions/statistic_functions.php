<?php
// Purpose	get remote customer computer IP address
// Inputs   	$log - login
// Returns	nothing

/**
 * @return string - client ip
 */
function stGetCustomerIP_Address(){
	if(function_exists("getallheaders")){
		$request_headers = getallheaders();
		if(isset($request_headers['X-Real-IP']))
			return $request_headers['X-Real-IP'];
	}
	return $_SERVER["REMOTE_ADDR"];
}

function stChangeOrderStatus( $orderID, $statusID, $comment = '', $notify = 0, $sendCommentOnly = false ){
	
	$status_name = ostGetOrderStatusName($statusID);
	$sql = '
		INSERT ?#ORDER_STATUS_CHANGE_LOG_TABLE ( orderID, status_name, status_change_time, status_comment ) values(?,?,?,?)
	';
	db_phquery($sql,$orderID,$status_name,Time::dateTime(),$comment);
	
	if($notify){
				
		$Order 		= ordGetOrder( $orderID );
		$Customer	= new Customer();
		$Customer->loadByID($Order['customerID']);

		$Email = $Customer->Email?$Customer->Email:$Order['customer_email'];
		$FirstName = $Customer->first_name?$Customer->first_name:$Order['customer_firstname'];
			
		xMailTxt($Email,str_replace('{ORDERID}', $Order['orderID_view'],translate($sendCommentOnly?"email_add_order_note_subject":"email_change_order_status_subject")), 'customer.order.change_status.txt', 
			array(
				'customer_firstname' => $FirstName,
				'order_status_url'=>str_replace('{URL}','http://'.CONF_SHOP_URL.'/index.php?ukey=order_status&orderID='.$orderID.'&code='.base64_encode($Order['customer_email']).'&hash='.md5(mb_strtolower($Order["customer_lastname"],'UTF-8')),translate('lbl_order_status_history_url')),
				'_MSG_CHANGE_ORDER_STATUS' =>str_replace(
					array('{STATUS}','{ORDERID}'),
					array(($status_name==translate("ordr_status_cancelled")?translate("ordr_status_cancelled"):$status_name), $Order['orderID_view']), translate($sendCommentOnly?"email_add_order_note_text":"email_change_order_status_text")),
				'_ADMIN_COMMENT' => xStripSlashesGPC(str_replace("\n",'<br>',$comment))
				));
	}		
}

function stGetOrderStatusReport( $orderID, $time = true){
	
	$q = db_phquery("SELECT * FROM ?#ORDER_STATUS_CHANGE_LOG_TABLE WHERE orderID=? ORDER BY status_change_time DESC", $orderID);
	$data = array();
	while( $row = db_fetch_row($q) ){
		
		$row["status_change_time"] = Time::standartTime( $row["status_change_time"],$time );
		$data[] = $row;
	}
	return $data;
}

function IncrementProductViewedTimes($productID)
{
	db_phquery("update ?#PRODUCTS_TABLE set viewed_times=viewed_times+1 where productID=?",$productID);
}

function GetProductViewedTimes($productID)
{
	$q=db_query("select viewed_times from ".
		PRODUCTS_TABLE." where productID='".$productID."'");
	$r=db_fetch_query($q);
	return $r["viewed_times"];
}

function GetProductViewedTimesReport($categoryID)
{
	if ( $categoryID != 0 )
	{
		$q=db_query("select name, viewed_times from ".
			PRODUCTS_TABLE." where categoryID='".$categoryID.
				"' order by viewed_times DESC ");
	}
	else
	{
		$q=db_query("select name, viewed_times from ".
			PRODUCTS_TABLE." order by viewed_times DESC ");
	}
	$data=array();
	while( $r=db_fetch_row($q) )
	{
		$row=array();
		$row["name"]=$r["name"];
		$row["viewed_times"]=$r["viewed_times"];
		$data[]=$row;
	}
	return $data;
}

function IncrementCategoryViewedTimes($categoryID){
	
	db_phquery("UPDATE ?#CATEGORIES_TABLE SET viewed_times=viewed_times+1 WHERE categoryID=?", $categoryID);
}

function GetCategoryViewedTimes($categoryID){
	
	return db_phquery_fetch(DBRFETCH_FIRST, "SELECT viewed_times FROM ?#CATEGORIES_TABLE WHERE categoryID=?", $categoryID);
}

function GetCategoryViewedTimesReport($num = null, $offset = 0){
	
	return db_phquery_fetch(DBRFETCH_ASSOC_ALL, 
		"SELECT ".LanguagesManager::sql_prepareField('name')." AS name, viewed_times 
		FROM ?#CATEGORIES_TABLE WHERE categoryID!=1 ORDER BY viewed_times DESC".(!is_null($num)?' LIMIT '.intval($offset).', '.$num:''));
}
?>