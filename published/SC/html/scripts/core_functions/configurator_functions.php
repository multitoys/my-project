<?php
// Purpose	gets all options
function cfgGetOptions()
{
	$options = db_query("SELECT optionID, ".LanguagesManager::sql_prepareField('name')." AS name FROM ".PRODUCT_OPTIONS_TABLE);
	$data = array();
	while( $option_row = db_fetch_row($options) )
		$data[] = $option_row;
	return $data;	
}

function cfgGetProductOptionValue( $productID ){
	
	//FIXME: minimize quiries here
	$data = array();
	$options = db_query("SELECT *, ".LanguagesManager::sql_constractSortField(PRODUCT_OPTIONS_TABLE, 'name')." FROM ".PRODUCT_OPTIONS_TABLE." ORDER BY sort_order, ".LanguagesManager::sql_getSortField(PRODUCT_OPTIONS_TABLE, 'name'));
	while( $option_row = db_fetch_row($options) )
	{
		$item = array();
		LanguagesManager::ml_fillFields(PRODUCT_OPTIONS_TABLE, $option_row);
		$item["option_row"]		= $option_row;
		$item["option_value"]	= null;
		$value = db_phquery("SELECT * FROM ?#PRODUCT_OPTIONS_VALUES_TABLE WHERE optionID=? AND productID=?", $option_row["optionID"], $productID);
		if (   !($value_row=db_fetch_row($value))   ) {
			
			$value_row["option_value"] = null;
			$value_row["option_type"] = 0;
			$value_row["option_show_times"] = 1;
		}
		LanguagesManager::ml_fillFields(PRODUCT_OPTIONS_VALUES_TABLE, $value_row);
		
		$item["option_value"] = $value_row;

		$item['value_count'] = 1;
		$item["value_count"] = db_phquery_fetch(DBRFETCH_FIRST,"SELECT COUNT(*) FROM ?#PRODUCTS_OPTIONS_SET_TABLE WHERE optionID=? AND productID=?", $option_row["optionID"], $productID );
		$data[] = $item;
	}
	return $data;
}

function cfgSet_N_VALUES_OptionType( $productID, $optionID ){
	
	$q = db_phquery( "SELECT COUNT(*) FROM ?#PRODUCT_OPTIONS_VALUES_TABLE WHERE optionID=? AND productID=?", $optionID, $productID );
	$count = db_fetch_row($q);
	$count = $count[0];

	if ( $count == 0 ){
		$dbq = "
			INSERT ?#PRODUCT_OPTIONS_VALUES_TABLE
			( optionID, productID, option_type, option_show_times )
			VALUES( ?optionID, ?productID, '', 2, 1 )
		";
	}
	else{
		$dbq = "
			UPDATE ?#PRODUCT_OPTIONS_VALUES_TABLE SET OPTION_TYPE=1
			WHERE productID=?productID AND optionID=?optionID
		";
	}
	db_phquery($dbq, array('optionID' => $optionID, 'productID' => $productID));
}

function cfgUpdateOptionValue( $productID, $updatedValues ){
	
	foreach( $updatedValues as $key => $value ){
		
		if ( $updatedValues[$key]["option_radio_type"] == "UN_DEFINED" || $updatedValues[$key]["option_radio_type"] == "ANY_VALUE" ) {
			$option_type=0;
		}
		else{
			$option_type=1;
		}
		if ( $updatedValues[$key]["option_radio_type"] == "UN_DEFINED" ){
			
			$option_value = null;
		}else{
			
			$option_value = $updatedValues[$key];
		}

		$where_clause = " WHERE optionID='".xEscapeSQLstring($key)."' AND productID='".xEscapeSQLstring($productID)."'";

		$q=db_query("SELECT COUNT(*) FROM ".PRODUCT_OPTIONS_VALUES_TABLE." ".$where_clause );
		$r = db_fetch_row($q);

		if ( $r[0]==1 ){ // if row exists

			$dbq = '
				UPDATE ?#PRODUCT_OPTIONS_VALUES_TABLE
				SET '.LanguagesManager::sql_prepareFieldUpdate('option_value', $option_value).', option_type=? '.$where_clause.'
			';
			db_phquery($dbq, $option_type);
		}else{ // insert query

			$dbq_inj = LanguagesManager::sql_prepareFieldInsert('option_value', $option_value);
			$dbq = '
				INSERT ?#PRODUCT_OPTIONS_VALUES_TABLE (optionID, productID, '.$dbq_inj['fields'].', option_type)
				VALUES(?, ?, '.$dbq_inj['values'].', ?)
			';
			db_phquery($dbq, $key, $productID, $option_type);
		}
	}
}

// Purpose	this function updates product option that can be configurated by customer
// Inputs     		$option_show_times - how many times do show in user part
//			$variantID_default - option id (FK) refers to 
//				PRODUCTS_OPTIONS_VALUES_VARIANTS_TABLE (PK)
//			$setting - structure
//				$setting[ <optionID> ]["switchOn"] - if true show this 
//						value in user part
//				$setting[ <optionID> ]["price_surplus"] - price surplus when 
//						this option is selected by user
// Returns		nothing
function UpdateConfiguriableProductOption($optionID, $productID, $option_show_times, $variantID_default, $setting ){
	
	$where_clause = " WHERE optionID='".xEscapeSQLstring($optionID)."' AND productID='".xEscapeSQLstring($productID)."'";
	
	$q = db_query( "SELECT COUNT(*) FROM ".PRODUCT_OPTIONS_VALUES_TABLE.$where_clause );
	$r=db_fetch_row($q);
	if ( $r[0]!=0 ){
		
		$option_value = array();
		 db_phquery("
		 	UPDATE ?#PRODUCT_OPTIONS_VALUES_TABLE
		 	SET ".LanguagesManager::sql_prepareFieldUpdate('option_value', $option_value).", option_show_times=?, variantID=? ".$where_clause, 
		 	$option_show_times, $variantID_default );
	}else{
		
		 db_phquery("
		 	INSERT ?#PRODUCT_OPTIONS_VALUES_TABLE
		 	(optionID, productID, option_type, option_show_times, variantID) 
		 	VALUES(?optionID, ?productID, 0, ?option_show_times,  ?variantID_default)", 
		 	array('optionID' => $optionID, 'productID'=>$productID, 'option_show_times' => $option_show_times, 'variantID_default' => $variantID_default));
	}

	$q1=db_phquery("SELECT variantID FROM ?#PRODUCTS_OPTIONS_VALUES_VARIANTS_TABLE WHERE optionID=?", $optionID);
	$if_only = false;
	while( $r1=db_fetch_row($q1) ){
		
		$key = $r1["variantID"];
		$where_clause=" WHERE productID='".xEscapeSQLstring($productID)."' AND optionID='".xEscapeSQLstring($optionID)."' AND variantID='".xEscapeSQLstring($key)."'";
		if ( !isset($setting[$key]["switchOn"]) ){
			
			db_query( "DELETE FROM ".PRODUCTS_OPTIONS_SET_TABLE.$where_clause );
		}
		else
		{
			$q=db_query("SELECT COUNT(*) FROM ".PRODUCTS_OPTIONS_SET_TABLE.$where_clause);
			$r=db_fetch_row($q);
			if ( $r[0]!=0 ){
				
				db_query("UPDATE ".PRODUCTS_OPTIONS_SET_TABLE." SET price_surplus='".(float)$setting[$key]["price_surplus"]."'".$where_clause );
				$if_only = true;
			}else{
				
				db_phquery("
					INSERT ?#PRODUCTS_OPTIONS_SET_TABLE (productID, optionID, variantID, price_surplus)
					VALUES( '".xEscapeSQLstring($productID)."', '".xEscapeSQLstring($optionID)."', '".xEscapeSQLstring($key)."', '". (float)$setting[$key]["price_surplus"]."' )"
				 );
				$if_only = true;
			}
		}
	}
	
	if ( !$if_only ){
		
		db_phquery("UPDATE ?#PRODUCT_OPTIONS_VALUES_TABLE SET option_show_times=0 WHERE optionID=? AND productID=?", $optionID, $productID);
	}
}
?>