<?php
/**
 * Delete shipping method
 *
 * @param int $SID - method ID
 */
function shDeleteShippingMethod( $SID ){
	
	db_phquery("DELETE FROM ?#SHIPPING_METHODS_TABLE WHERE SID=?", $SID);
}

/**
 * Get shipping methods by module
 *
 * @param ShippingRateCalculator $shippingModule
 * @return array
 */
function shGetShippingMethodsByModule( $shippingModule ){

	$moduleID = $shippingModule->get_id();

	if ( strlen($moduleID) == 0 )return array();

	$q = db_phquery("select * FROM ?#SHIPPING_METHODS_TABLE WHERE module_id=?", $moduleID );	
	$data = array();
	while( $row = db_fetch_row($q) ){
		
		LanguagesManager::ml_fillFields(SHIPPING_METHODS_TABLE, $row);
		$data[] = $row;
	}
	return $data;		
}

/**
 * Get shipping method information by ID
 *
 * @param int $shippingMethodID
 * @return array
 */
function shGetShippingMethodById( $shippingMethodID ){

	$row = db_phquery_fetch(DBRFETCH_ROW, "SELECT * FROM ?#SHIPPING_METHODS_TABLE WHERE SID=?", $shippingMethodID );
	LanguagesManager::ml_fillFields(SHIPPING_METHODS_TABLE, $row);
	return $row;
}

/**
 * Get information about all shipping methods
 *
 * @param bool $enabledOnly - if true return only enabled shipping methods, else all
 * @return array
 */
function shGetAllShippingMethods( $enabledOnly = false )
{
	$whereClause = "";
	if ( $enabledOnly )
		$whereClause = " WHERE Enabled=1 ";
	$q = db_phquery("SELECT * FROM ?#SHIPPING_METHODS_TABLE {$whereClause} ORDER BY sort_order") or die (db_error());
	$data = array();
	while( $row = db_fetch_row($q) ){
		
		LanguagesManager::ml_fillFields(SHIPPING_METHODS_TABLE, $row);
		$data[] = $row;
	}
	return $data;
}


// *****************************************************************************
// Purpose  get all installed shipping modules
// Inputs   
// Remarks  
// Returns  nothing
function shGetInstalledShippingModules()
{
	$moduleFiles = GetFilesInDirectory( "./modules/shipping", "php" );
	$shipping_modules = array();
	foreach( $moduleFiles as $fileName )
	{
		$className = GetClassName( $fileName );
		if(!$className)continue;
		eval( "\$shipping_module = new ".$className."();" );
		if ( $shipping_module->is_installed() )
			$shipping_modules[] = $shipping_module;
	}
	return $shipping_modules;
}


// *****************************************************************************
// Purpose  add shipping method
// Inputs   
// Remarks  
// Returns  nothing	
function shAddShippingMethod( $Name, $description, $Enabled, $sort_order, $module_id, $email_comments_text, $logo = '' ){

	$Name_sqlinj = LanguagesManager::sql_prepareFieldInsert('Name', $Name);
	$description_sqlinj = LanguagesManager::sql_prepareFieldInsert('description', $description);
	$email_comments_text_sqlinj = LanguagesManager::sql_prepareFieldInsert('email_comments_text', $email_comments_text);
	$sql = "
		INSERT ?#SHIPPING_METHODS_TABLE ( {$Name_sqlinj['fields']}, {$description_sqlinj['fields']}, {$email_comments_text_sqlinj['fields']}, Enabled, module_id, sort_order, logo  ) 
		VALUES({$Name_sqlinj['values']}, {$description_sqlinj['values']}, {$email_comments_text_sqlinj['values']},?,?,?,?)
	";
	db_phquery($sql, $Enabled, $module_id, $sort_order,$logo);
	return db_insert_id();
}


// *****************************************************************************
// Purpose  update shipping method
// Inputs   
// Remarks  
// Returns  nothing	
function shUpdateShippingMethod($SID, $Name, $description, $Enabled, $sort_order,$module_id, $email_comments_text, $logo='' ){
	
	$sql = '
		UPDATE ?#SHIPPING_METHODS_TABLE SET '.LanguagesManager::sql_prepareFieldUpdate('Name', $Name).', '.LanguagesManager::sql_prepareFieldUpdate('description', $description).', '.LanguagesManager::sql_prepareFieldUpdate('email_comments_text', $email_comments_text).',Enabled=?,module_id=?,sort_order=?,logo=? WHERE SID=?
	';
	db_phquery($sql, $Enabled,$module_id,$sort_order,$logo,$SID);	
}

/**
 * Check shipping method existing
 *
 * @param int $shippingMethodID - method ID
 * @return bool
 */
function shShippingMethodIsExist( $shippingMethodID ){
	
	return (db_phquery_fetch( DBRFETCH_FIRST, "SELECT COUNT(*) FROM ?#SHIPPING_METHODS_TABLE WHERE Enabled=1 AND SID=?", $shippingMethodID )>0);
}

function shGetMaxSortOrder(){
	return db_phquery_fetch(DBRFETCH_FIRST, 'SELECT MAX(sort_order) FROM ?#SHIPPING_METHODS_TABLE');
}
?>