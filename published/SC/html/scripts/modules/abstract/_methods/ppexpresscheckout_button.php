<?php
	$Register = &Register::getInstance();
	$smarty = &$Register->get(VAR_SMARTY);
	/* @var $smarty Smarty */
	$PostVars = &$Register->get(VAR_POST);
	$GetVars = &$Register->get(VAR_GET);
	$CurrDivision = &$Register->get(VAR_CURRENTDIVISION);

	if(!defined('CONF_PPEXPRESSCHECKOUT_ENABLED')||!CONF_PPEXPRESSCHECKOUT_ENABLED)return '';
	
	include_once DIR_MODULES.'/payment/class.ppexpresscheckout.php';
	
	$PPExpressCheckout = &PPExpressCheckout::getModuleInstance();
	
	if($PPExpressCheckout){
	
	$processCheckout = isset($GetVars['ppexpresscheckout2']);
	renderURL('ppexpresscheckout2=', '', true);
	
	$error_message = '';
	
	if($processCheckout){
		
		$error_message = $PPExpressCheckout->doSetExpressCheckoutRequest();
		if(Services_PayPal::isError($error_message)){
			$error_message = $error_message->getMessage();
		}
	}elseif (xDataExists('_PPECHECKOUT_ERROR')){
		$error_message = xPopData('_PPECHECKOUT_ERROR');
	}
	
	if($error_message)Message::raiseMessageRedirectSQ(MSG_ERROR, '', $error_message);
	
	$smarty->assign('PPExpressCheckout_button', (is_object($PPExpressCheckout)?$PPExpressCheckout->getCheckoutButton():'<font color="red">Error init PPExpressCheckout</font>'));
	}
?>