<?php
	$Register = &Register::getInstance();
	$PostVars = &$Register->get(VAR_POST);
	$GetVars = &$Register->get(VAR_GET);
	
	if(isset($GetVars['success_order'])){
		
		cartClearCartContet();
		Redirect(getTransactionResultURL('success'));
	}
	
	list($GoogleCheckout2Info) = modGetModuleConfigs('googlecheckout2');
	$GooglePaymentModule = PaymentModule::getInstance($GoogleCheckout2Info['ConfigID']);
	/* @var $GooglePaymentModule GoogleCheckout2 */
	
	$merchant_id = $GooglePaymentModule->_getSettingValue('CONF_GOOGLECHECKOUT2_MERCHANTID');
	$merchant_key = $GooglePaymentModule->_getSettingValue('CONF_GOOGLECHECKOUT2_MERCHANTKEY');
	$sandbox = $GooglePaymentModule->_getSettingValue('CONF_GOOGLECHECKOUT2_SANDBOX');
	$currency = currGetCurrencyByID($GooglePaymentModule->_getSettingValue('CONF_GOOGLECHECKOUT2_TRANSCURR'));
	
	$reqXML = $GLOBALS['HTTP_RAW_POST_DATA'];
	$AuthOK = false;
	
	if(isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])){
		
		if($_SERVER['PHP_AUTH_USER']==$merchant_id&& $_SERVER['PHP_AUTH_PW']==$merchant_key){
			
			$AuthOK = true;
		}
	}
	
	if(!$AuthOK)die;
	
	$Request = new xmlNodeX();
	$Request->renderTreeFromInner($reqXML);
	
	switch ($Request->getName()){
		case 'new-order-notification':
			$GooglePaymentModule->hndl_NewOrderNotification($Request);
			break;
		case 'merchant-calculation-callback':
			$GooglePaymentModule->hndl_MerchantCalculationCallback($Request);
			break;
		case 'order-state-change-notification':
			$GooglePaymentModule->hndl_OrderStateChangeNotification($Request);
			break;
	}
	
	die;
?>