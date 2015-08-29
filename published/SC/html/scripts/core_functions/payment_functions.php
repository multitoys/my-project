<?php
//TODO: move methods to class PaymentModulesManager or PaymentModule

// *****************************************************************************
// Purpose  delete payment method
// Inputs   
// Remarks  
// Returns  nothing
function payDeletePaymentMethod( $PID )
{
	db_query("delete from ".PAYMENT_TYPES_TABLE." where PID=$PID");
}


// *****************************************************************************
// Purpose  get payment methods by module
// Inputs   
// Remarks  
// Returns  
function payGetPaymentMethodsByModule( $paymentModule )
{
	$moduleID = $paymentModule->get_id();
	if ( $moduleID == "" )return array();
	
	$q = db_phquery("SELECT * FROM ?#PAYMENT_TYPES_TABLE WHERE module_id=?", $moduleID );	
	$data = array();
	while( $row = db_fetch_row($q) ){
		
		LanguagesManager::ml_fillFields(PAYMENT_TYPES_TABLE, $row);
		$data[] = $row;
	}
	return $data;		
}


// *****************************************************************************
// Purpose  get payment module by ID
// Inputs   
// Remarks  
// Returns  
function payGetPaymentModuleById( $PID, $paymentModulesFiles )
{
	$paymentModules = modGetModules( $paymentModulesFiles );
	$currentPaymentModule = null;
	foreach( $paymentModules as $paymentModule )
	{
		if ( (int)$paymentModule->get_id()==(int)$PID )
		{
			$currentPaymentModule = $paymentModule;
			break;
		}
	}
	return $currentPaymentModule;
}


// *****************************************************************************
// Purpose  get payment method by ID
// Inputs   
// Remarks  
// Returns  
function payGetPaymentMethodById( $PID ){
	
	$row = db_phquery_fetch(DBRFETCH_ROW, "SELECT * FROM ?#PAYMENT_TYPES_TABLE WHERE PID=?", $PID);
	LanguagesManager::ml_fillFields(PAYMENT_TYPES_TABLE, $row);
	return $row;
}


// *****************************************************************************
// Purpose  get all payment methods
// Inputs   
// Remarks  
// Returns  nothing
function payGetAllPaymentMethods( $enabledOnly = false, $filter = true ){
	
	$whereClause = $enabledOnly?$whereClause = " WHERE Enabled=1 ":'';
	
	$q = db_phquery("SELECT * FROM ?#PAYMENT_TYPES_TABLE {$whereClause} ORDER BY sort_order") or die (db_error());
	$data = array();
	while( $row = db_fetch_assoc($q) ){
		
		LanguagesManager::ml_fillFields(PAYMENT_TYPES_TABLE, $row);
		$row["ShippingMethodsToAllow"] = _getShippingMethodsToAllow( $row["PID"] );
		if($filter)	$data[] = PaymentModule::filterPaymentMethod($row);
		else $data[] = $row;
	}
	
	return $data;
}


// *****************************************************************************
// Purpose  get all installed payment modules
// Inputs   
// Remarks  
// Returns  nothing
function payGetInstalledPaymentModules()
{
	$moduleFiles = GetFilesInDirectory( "./modules/payment", "php" );
	$payment_modules = array();
	foreach( $moduleFiles as $fileName )
	{
		$className = GetClassName( $fileName );
		if(!$className)continue;
		eval( "\$payment_module = new ".$className."();" );
		if ( $payment_module->is_installed() )
			$payment_modules[] = $payment_module;
	}
	return $payment_modules;
}


// *****************************************************************************
// Purpose  add payment method
// Inputs   
// Remarks  
// Returns  nothing	
function payAddPaymentMethod( $Name, $description, $Enabled, $sort_order, $email_comments_text, $module_id, $calculate_tax, $logo = '' ){
	
	$name_inj = LanguagesManager::sql_prepareFieldInsert('Name', $Name);
	$descr_inj = LanguagesManager::sql_prepareFieldInsert('description', $description);
	$ecomments_inj = LanguagesManager::sql_prepareFieldInsert('email_comments_text', $email_comments_text);
	$sql = '
		INSERT ?#PAYMENT_TYPES_TABLE ( '.$name_inj['fields'].', '.$descr_inj['fields'].', '.$ecomments_inj['fields'].', Enabled, module_id, sort_order, calculate_tax, logo )
		VALUES ('.$name_inj['values'].', '.$descr_inj['values'].', '.$ecomments_inj['values'].',?,?,?,?,?)
	';
	db_phquery($sql,$Enabled,$module_id,$sort_order,$calculate_tax,$logo);
	return db_insert_id();
}


// *****************************************************************************
// Purpose  update payment method
// Inputs   
// Remarks  
// Returns  nothing	
function payUpdatePaymentMethod($PID, $Name, $description, $Enabled, $sort_order,$module_id, $email_comments_text, $calculate_tax, $logo = '' ){

	$sql = '
		UPDATE ?#PAYMENT_TYPES_TABLE SET '.LanguagesManager::sql_prepareFieldUpdate('Name', $Name).', '.LanguagesManager::sql_prepareFieldUpdate('description', $description).', '.LanguagesManager::sql_prepareFieldUpdate('email_comments_text', $email_comments_text).',Enabled=?,module_id=?,sort_order=?,calculate_tax=?, logo=? WHERE PID=?
	';
	db_phquery($sql,$Enabled,$module_id,$sort_order,$calculate_tax,$logo,$PID);	
}

// *****************************************************************************
// Purpose  
// Inputs   
// Remarks  
// Returns  nothing	
function payResetPaymentShippingMethods( $PID )
{
	db_query("delete from ".SHIPPING_METHODS_PAYMENT_TYPES_TABLE." where PID=$PID");
}


// *****************************************************************************
// Purpose  
// Inputs   
// Remarks  
// Returns  nothing	
function _getShippingMethodsToAllow( $PID ){
	
	$PID = (int)$PID;
	$res = array();
	$shipping_methods = shGetAllShippingMethods();
	$dbq = '
		SELECT COUNT(*) FROM ?#SHIPPING_METHODS_PAYMENT_TYPES_TABLE
		WHERE SID=? AND PID=?
	';
	for($i=0; $i<count($shipping_methods); $i++){
		
		$q = db_phquery($dbq, $shipping_methods[$i]['SID'], $PID);
		$row = db_fetch_row($q);
		$item['SID'] = $shipping_methods[$i]['SID'];
		$item['allow'] = $row[0];
		$item['name']  = $shipping_methods[$i]['Name'];
		$res[] = $item;
	}
	return $res;
}

// *****************************************************************************
// Purpose  
// Inputs   
// Remarks  
// Returns  nothing	
function paySetPaymentShippingMethod( $PID, $SID ){
	
	db_phquery( "INSERT ?#SHIPPING_METHODS_PAYMENT_TYPES_TABLE ( PID, SID ) VALUES(?,?)", $PID, $SID);
}



// *****************************************************************************
// Purpose  
// Inputs   
// Remarks  
// Returns  nothing	
function payPaymentMethodIsExist( $paymentMethodID ){
	
	return db_phquery_fetch(DBRFETCH_FIRST, "SELECT COUNT(*) FROM ?#PAYMENT_TYPES_TABLE WHERE PID=? AND Enabled=1", $paymentMethodID)>0;	
}

/**
 * Return url for transaction result
 *
 * @param string $_Type - success or failure
 * @return string
 */
function getTransactionResultURL($_Type, $paymentModuleID = 0, $_Key = null){
	
	$scURL = trim( CONF_FULL_SHOP_URL );
	$scURL = str_replace("http://",  "", $scURL);
	$scURL = str_replace("https://", "", $scURL);
	$scURL = "http://".$scURL;
	$_Key = is_array($_Key)?$_Key:$_Key?array($_Key):null;
	if($paymentModuleID&&$_Key)$_Key[] = $paymentModuleID;
	return set_query('ukey=transaction_result&transaction_result='.$_Type.($paymentModuleID?'&modConfID='.$paymentModuleID:'').($_Key?'&secure_key='.generateSecureKey($_Key):''), $scURL);
}

function generateSecureKey($params){
	if(!is_array($params)){
		$params = array($params);
	}
	$params[] = SystemSettings::get('DB_USER');
	$params[] = SystemSettings::get('DB_PASS');
	return md5(implode('%',$params));
}

function payGetMaxSortOrder(){
	
	return db_phquery_fetch(DBRFETCH_FIRST, 'SELECT MAX(sort_order) FROM ?#PAYMENT_TYPES_TABLE');
}
?>