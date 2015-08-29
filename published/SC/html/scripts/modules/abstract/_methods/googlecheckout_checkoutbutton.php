<?php
$Register = &Register::getInstance();
$smarty = &$Register->get(VAR_SMARTY);
/* @var $smarty Smarty */
$PostVars = &$Register->get(VAR_POST);
$GetVars = &$Register->get(VAR_GET);

$processCheckout = isset($GetVars['googlecheckout2']);
renderURL('googlecheckout2=', '', true);

if(!defined('CONF_GOOGLECHECKOUT2_ENABLED')||!CONF_GOOGLECHECKOUT2_ENABLED)return '';

list($GoogleCheckout2Info) = modGetModuleConfigs('googlecheckout2');
if($GoogleCheckout2Info){
	$GoogleCheckout2 = PaymentModule::getInstance($GoogleCheckout2Info['ConfigID']);
	/* @var $GoogleCheckout2 GoogleCheckout2 */

	$error_message = '';

	if($processCheckout){

		$error_message = $GoogleCheckout2->processCheckout();

	}
	if($error_message){
		Message::raiseMessageRedirectSQ(MSG_ERROR, '', $error_message);
	}

	$smarty->assign('GoogleCheckout_CheckoutButton', is_object($GoogleCheckout2)?$GoogleCheckout2->getCheckoutButton():'<font color="red">Error loading GoogleCheckout2 module</font>');
}
?>