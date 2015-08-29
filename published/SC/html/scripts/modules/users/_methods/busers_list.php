<?php
global $smarty;
if($page<1)$page=1;

if ( isset($_GET['deleteCustomerID']) ){

	if (CONF_BACKEND_SAFEMODE){ //this action is forbidden when SAFE MODE is ON
		RedirectSQ('&safemode=yes&deleteCustomerID=');
	}

	regDeleteCustomer( $_GET['deleteCustomerID'] );
	RedirectSQ('deleteCustomerID=');
}

if(isset($_GET['activateID'])){

	if (CONF_BACKEND_SAFEMODE){ //this action is forbidden when SAFE MODE is ON
		RedirectSQ('&activateID=&safemode=yes');
	}
	regActivateCustomer($_GET['activateID']);
	RedirectSQ('activateID=');
}

$CustomerGroups = GetAllCustGroups();
$_CGroups = array();
foreach ($CustomerGroups as $f_Ind=>$f_Group){
	$_CGroups[$f_Group['custgroupID']] = $f_Group;
}
$CustomerGroups = &$_CGroups;

if(!isset($_GET['search'])&& !isset($_GET['export_to_excel'])){
	$_GET['search'] = '';
}

if(isset($_GET['search'])||isset($_GET['export_to_excel'])){


	$Users = array();
	$ActiveState = '';
	// if (isset($_GET["ActState"]) ){
		// switch ($_GET["ActState"]){
			// case 1://#activated
				// $ActiveState = "AND (ActivationCode='' OR ActivationCode IS NULL)";
				// break;
			// case 0://#not activated
				// $ActiveState = "AND ActivationCode<>''";
				// break;
		// }
	// }
	if (isset($_GET["ActState"]) ){
		switch ($_GET["ActState"]){
			case 1://#activated
			// $timeact=date("Y-m-d H:i:s");
				$ActiveState = "AND (may_order_until>NOW() OR unlimited_order)";
				break;
			case 0://#not activated
				$ActiveState = "AND (may_order_until = '0000-00-00 00:00:00')";
				break;
		}
	}

	$gridEntry = new Grid();

	$gridEntry->query_total_rows_num = 'SELECT COUNT(*) FROM ?#TBL_USERS'.
	' WHERE 1 '.
	(isset($_GET['login'])?' AND Login LIKE "%'.xEscapeSQLstring($_GET['login']).'%"':'').
	( ( isset($_GET['custgroupID']) and $_GET['custgroupID'] > 0 ) ? ' AND custgroupID = '.xEscapeSQLstring($_GET['custgroupID']) : '').
	(isset($_GET['email'])?' AND Email LIKE "%'.xEscapeSQLstring($_GET['email']).'%"':'').
	(isset($_GET['last_name'])?' AND last_name LIKE "%'.xEscapeSQLstring($_GET['last_name']).'%"':'').
	(isset($_GET['first_name'])?' AND first_name LIKE "%'.xEscapeSQLstring($_GET['first_name']).'%"':'').
	$ActiveState;

	$gridEntry->query_select_rows = 'SELECT * FROM ?#TBL_USERS'.
	' WHERE 1 '.
	(isset($_GET['login'])?' AND Login LIKE "%'.xEscapeSQLstring($_GET['login']).'%"':'').
	( ( isset($_GET['custgroupID']) and $_GET['custgroupID'] > 0 ) ? ' AND custgroupID = '.xEscapeSQLstring($_GET['custgroupID']) : '').
	(isset($_GET['email'])?' AND Email LIKE "%'.xEscapeSQLstring($_GET['email']).'%"':'').
	(isset($_GET['last_name'])?' AND last_name LIKE "%'.xEscapeSQLstring($_GET['last_name']).'%"':'').
	(isset($_GET['first_name'])?' AND first_name LIKE "%'.xEscapeSQLstring($_GET['first_name']).'%"':'').
	$ActiveState;
	
	$gridEntry->setRowHandler('$row[\'reg_datetime\'] = Time::standartTime($row[\'reg_datetime\']);return $row;');

	$gridEntry->registerHeader(translate("usr_custinfo_login"), 'Login');
	$gridEntry->registerHeader(translate("usr_custinfo_first_name"), 'first_name');
	$gridEntry->registerHeader(translate("usr_custinfo_last_name"), 'last_name');
	$gridEntry->registerHeader(translate("usr_custinfo_email"), 'Email');
	$gridEntry->registerHeader(translate("str_city"));
/* 	$gridEntry->registerHeader(translate("str_group")); */
	$gridEntry->registerHeader(translate("usr_custinfo_regtime"), 'reg_datetime', true, 'desc');
	$gridEntry->registerHeader(translate("usr_trade_form"));
/* 	$gridEntry->registerHeader(translate("usr_account_state")); */
	$gridEntry->prepare();

	if ( isset($_GET['export_to_excel']) ){

		//serExportCustomersToExcel( $smarty->get_template_vars('GridRows'), $_GET['charset'] );
		serExportCustomersToExcel( $gridEntry->exportRows(), $_GET['charset'] );
		$smarty->assign( 'customers_has_been_exported_succefully', 1 );
		$smarty->assign('MessageBlock',"<div class='success_block' ><span class='success_message'>".translate('msg_customers_exported_to_file').'<br><br>'.
		'<a href="get_file.php?getFileParam='.Crypt::FileParamCrypt( 'GetCustomerExcelSqlScript', null ).'">'.translate('btn_download').'</a>'.sprintf(' (%3.2f Kb)',filesize(DIR_TEMP.'/customers.csv')/1024).'</span></div>');
	}

}
$smarty->assign('TotalFound',str_replace('{N}',$gridEntry->total_rows_num,translate('msg_n_customers_found')));
if(SystemSettings::is_hosted()){
	$session_id = session_id();
	session_write_close();

	$messageClient = new WbsHttpMessageClient($db_key, 'wbs_msgserver.php');
	$messageClient->putData('action', 'ALLOW_VIEW_ORDER_DETAILS');
	$messageClient->putData('language',(LanguagesManager::getCurrentLanguage()->iso2));
	$res=$messageClient->send();

	session_id($session_id);
	session_start();

	if($res&&$messageClient->getResult('msg')!=''){
		$msg_type=$messageClient->getResult('msg_type');
		if($msg_type=='error'){
			$smarty->assign('MessageBlock',"<div class='error_block' ><span class='error_message'>".$messageClient->getResult('msg').'</span></div>');
		}else{
			$smarty->assign('MessageBlock',"<div class='comment_block' ><span class='success_message'>".$messageClient->getResult('msg').'</span></div>');
		}
	}
}else{
	$res = false;
}

if(!$res||$messageClient->getResult('success')===true){
	$smarty->assign('page_enabled','1');
}

$smarty->assign( 'customer_groups', $CustomerGroups );
global $file_encoding_charsets;
$smarty->assign('charsets', $file_encoding_charsets);
$smarty->assign('default_charset', translate('prdine_default_charset'));

$smarty->assign( 'sub_template', $this->getTemplatePath('backend/users_list.html'));
?>