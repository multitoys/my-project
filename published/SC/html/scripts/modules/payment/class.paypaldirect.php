<?php
/**
 * @connect_module_class_name CPayPalDirect
 * @package DynamicModules
 * @subpackage Payment
 */
//commented out use kernel PEAR
//ini_set('include_path', ini_get('include_path').PATH_DELIMITER.'./modules/payment/pppro/pear');
error_reporting(E_ALL & ~E_NOTICE);

class CPayPalDirect extends PaymentModule {

	var $type = PAYMTD_TYPE_CC;
	var $language = 'eng';
	var $default_logo = 'http://www.webasyst.net/collections/design/payment-icons/paypal.gif';
	
	var $CertsPath = '';
	
	function _initVars(){
		
		parent::_initVars();
		$this->CertsPath = DIR_TEMP;
		$this->title 		= CPAYPALDIRECT_TTL;
		$this->description 	= CPAYPALDIRECT_DSCR;
		$this->sort_order 	= 1;
		
		$this->Settings = array( 
				"CONF_CPAYPALDIRECT_USERNAME",
				"CONF_CPAYPALDIRECT_PASSWORD",
				'CONF_CPAYPALDIRECT_CERTPATH',
				'CONF_CPAYPALDIRECT_MODE',
				'CONF_CPAYPALDIRECT_PAYMENTACTION',
				'CONF_CPAYPALDIRECT_ORDERSTATUS',
				'CONF_CPAYPALDIRECT_CURRENCY',
			);
	}

	function _initSettingFields(){

		$this->SettingsFields['CONF_CPAYPALDIRECT_USERNAME'] = array(
			'settings_value' 		=> '', 
			'settings_title' 			=> CPAYPALDIRECT_CFG_USERNAME_TTL, 
			'settings_description' 	=> CPAYPALDIRECT_CFG_USERNAME_DSCR, 
			'settings_html_function' 	=> 'setting_TEXT_BOX(0,', 
			'sort_order' 			=> 1,
		);
		$this->SettingsFields['CONF_CPAYPALDIRECT_PASSWORD'] = array(
			'settings_value' 		=> '', 
			'settings_title' 			=> CPAYPALDIRECT_CFG_PASSWORD_TTL, 
			'settings_description' 	=> CPAYPALDIRECT_CFG_PASSWORD_DSCR, 
			'settings_html_function' 	=> 'setting_TEXT_BOX(0,', 
			'sort_order' 			=> 1,
		);
		$this->SettingsFields['CONF_CPAYPALDIRECT_CERTPATH'] = array(
			'settings_value' 		=> '', 
			'settings_title' 			=> CPAYPALDIRECT_CFG_CERTPATH_TTL, 
			'settings_description' 	=> CPAYPALDIRECT_CFG_CERTPATH_DSCR, 
			'settings_html_function' 	=> 'setting_SINGLE_FILE(DIR_TEMP,',
			'sort_order' 			=> 1,
		);
		$this->SettingsFields['CONF_CPAYPALDIRECT_MODE'] = array(
			'settings_value' 		=> 'Sandbox', 
			'settings_title' 			=> CPAYPALDIRECT_CFG_MODE_TTL, 
			'settings_description' 	=> CPAYPALDIRECT_CFG_MODE_DSCR, 
			'settings_html_function' 	=> 'setting_RADIOGROUP(CPAYPALDIRECT_TXT_TEST.":Sandbox,".CPAYPALDIRECT_TXT_LIVE.":Live",', 
			'sort_order' 			=> 1,
		);
		$this->SettingsFields['CONF_CPAYPALDIRECT_PAYMENTACTION'] = array(
			'settings_value' 		=> 'Sale', 
			'settings_title' 			=> CPAYPALDIRECT_CFG_PAYMENTACTION_TTL, 
			'settings_description' 	=> CPAYPALDIRECT_CFG_PAYMENTACTION_DSCR, 
			'settings_html_function' 	=> 'setting_RADIOGROUP("Sale:Sale,Authorization:Authorization",', 
			'sort_order' 			=> 1,
		);
		
		$this->SettingsFields['CONF_CPAYPALDIRECT_ORDERSTATUS'] = array(
			'settings_value' 		=> '-1', 
			'settings_title' 			=> CPAYPALDIRECT_CFG_ORDERSTATUS_TTL, 
			'settings_description' 	=> CPAYPALDIRECT_CFG_ORDERSTATUS_DSCR, 
			'settings_html_function' 	=> 'setting_SELECT_BOX(PaymentModule::_getStatuses(),', 
			'sort_order' 			=> 1,
		);
		$this->SettingsFields['CONF_CPAYPALDIRECT_CURRENCY'] = array(
			'settings_value' 		=> '', 
			'settings_title' 			=> CPAYPALDIRECT_CFG_CURRENCY_TTL, 
			'settings_description' 	=> CPAYPALDIRECT_CFG_CURRENCY_DSCR, 
			'settings_html_function' 	=> 'setting_CURRENCY_SELECT(', 
			'sort_order' 			=> 1,
		);
	}
	
	function payment_form_html($_Params = null){
	
		global $rMonths;
		
		if(isset($_Params['BillingAddressID'])){
		
			$_Params = regGetAddress($_Params['BillingAddressID']);
		}
		
		$_Params['countryID'] = cnGetCountryById($_Params['countryID']);
		$_Params['countryID'] = $_Params['countryID']['country_iso_2'];
		$_Params['zoneID'] = znGetSingleZoneById($_Params['zoneID']);
		$_Params['zoneID'] = $_Params['zoneID']['zone_code'];
		
		if(xDataExists('PPDIRECT_INFO')){
			
			$_Params = xPopData('PPDIRECT_INFO');
			$_Params['countryID'] = $_Params['ppdirect_country_iso2'];
			$_Params['zip'] = $_Params['ppdirect_zip'];
			$_Params['zoneID'] = $_Params['ppdirect_state_iso2'];
			$_Params['city'] = $_Params['ppdirect_city'];
			$_Params['address'] = $_Params['ppdirect_address'];
			$_Params['first_name'] = $_Params['ppdirect_first_name'];
			$_Params['last_name'] = $_Params['ppdirect_last_name'];
		}
		
		$CurrYear = date('Y');
		$ExpYears = '';
		for($_Y = 0; $_Y<10; $_Y++){
		
			$_Selected = isset($_Params['ppdirect_expyear'])?($_Params['ppdirect_expyear']==($CurrYear+$_Y)):0;
			$ExpYears .= '<option value="'.($CurrYear+$_Y).'"'.($_Selected?' selected="selected"':'').'>'.($CurrYear+$_Y).'</option>';
		}
		
		$ExpMonths = '';
		for($_M = 1; $_M<=12; $_M++){
		
			$_Selected = isset($_Params['ppdirect_expmonth'])?($_Params['ppdirect_expmonth']==($_M)):0;
			$ExpMonths .= '<option value="'.$_M.'"'.($_Selected?' selected="selected"':'').'>'.$rMonths[$_M].'</option>';
		}
		
		$CCTypesOptions = '';
		$CCTypes = array(
			'Visa' => 'Visa',
			'MasterCard' => 'Master Card',
			'Discover' => 'Discover',
			'Amex' => 'Amex',
			);
		
		foreach ($CCTypes as $_CCTCode=>$_CCTDescr){
			
			$_Selected = isset($_Params['ppdirect_cctype'])&&$_Params['ppdirect_cctype']==$_CCTCode?true:false;
			$CCTypesOptions .= '<option value="'.xHtmlSpecialChars($_CCTCode).'" '.($_Selected?' selected="selected"':'').' >'.xHtmlSpecialChars($_CCTDescr).'</option>';
		}
		
		return $this->_getPPECButtonHTML(array('shipping_address'=>$this->_getShippingAddress())).'
		<input type="hidden" name="ppdirect_country_iso2" value="'.(isset($_Params["countryID"])?xHtmlSpecialChars($_Params["countryID"]):'').'" />
		<input type="hidden" name="ppdirect_zip" value="'.(isset($_Params["zip"])?xHtmlSpecialChars($_Params["zip"]):'').'" />
		<input type="hidden" name="ppdirect_state_iso2" value="'.(isset($_Params["zoneID"])?xHtmlSpecialChars($_Params["zoneID"]):'').'" />
		<input type="hidden" name="ppdirect_city" value="'.(isset($_Params["city"])?xHtmlSpecialChars($_Params["city"]):'').'" />
		<input type="hidden" name="ppdirect_address" value="'.(isset($_Params["address"])?xHtmlSpecialChars($_Params["address"]):'').'" />
		<table>
		<tr>
			<td>'.CPAYPALDIRECT_TXT_FNAME.'</td>
			<td><input type="text" name="ppdirect_first_name" value="'.(isset($_Params["first_name"])?xHtmlSpecialChars($_Params["first_name"]):'').'" /></td>
		</tr>
		<tr>
			<td>'.CPAYPALDIRECT_TXT_LNAME.'</td>
			<td><input type="text" name="ppdirect_last_name" value="'.(isset($_Params["last_name"])?xHtmlSpecialChars($_Params["last_name"]):'').'" /></td>
		</tr>
		<tr>
			<td>'.CPAYPALDIRECT_TXT_CCTYPE.'</td>
			<td><select name="ppdirect_cctype">
				'.$CCTypesOptions.'
			</select></td>
		</tr>
		<tr>
			<td>'.CPAYPALDIRECT_TXT_CCNUMBER.'</td>
			<td><input type="text" name="ppdirect_ccnumber" value="'.(isset($_Params['ppdirect_ccnumber'])?$_Params['ppdirect_ccnumber']:'').'" /></td>
		</tr>
		<tr>
			<td>'.CPAYPALDIRECT_TXT_CVV2.'</td>
			<td><input type="text" name="ppdirect_cvv2" value="'.(isset($_Params['ppdirect_cvv2'])?$_Params['ppdirect_cvv2']:'').'" /></td>
		</tr>
		<tr>
			<td>'.CPAYPALDIRECT_TXT_EXPDATE.'</td>
			<td><select name="ppdirect_expmonth">'.$ExpMonths.'</select>&nbsp;<select name="ppdirect_expyear">'.$ExpYears.'</select></td>
		</tr>
		</table>
		';
	}

	function _getPPECButtonHTML($params = array()){
		
		$html_ppec_button = '';
		
		$mod_configs = modGetModuleConfigs('ppexpresscheckout');
		if(is_array($mod_configs) && count($mod_configs)){do{
			
			if(!defined('CONF_PPEXPRESSCHECKOUT_ENABLED')||!CONF_PPEXPRESSCHECKOUT_ENABLED)break;
			$PPECheckout = PaymentModule::getInstance($mod_configs[0]['ConfigID']);
			/* @var $PPECheckout ppexpresscheckout */
			if(!is_object($PPECheckout))break;
			
			$error_message = '';
			
			if(isset($_POST['ppexpresscheckout2']) && $_POST['ppexpresscheckout2'] == 1){
				
				if(isset($_GET['shippingMethodID']))xSaveData('_PPECHECKOUT_SHIPPINGMETHOD_ID', $_GET['shippingMethodID']);
				if(isset($_GET['shServiceID']))xSaveData('_PPECHECKOUT_SHIPPINGSERVICE_ID', $_GET['shServiceID']);
				
				$ecr_params = array();
				
				$address = &Services_PayPal::getType('AddressType');
				/* @var $address AddressType */
				@$address->setCountry($params['shipping_address']['countryID'], translate("str_default_charset"));
				@$address->setName($params['shipping_address']['first_name'].' '.$params['shipping_address']['last_name'], translate("str_default_charset"));
				@$address->setStreet1($params['shipping_address']['address'], translate("str_default_charset"));
				@$address->setCityName($params['shipping_address']['city'], translate("str_default_charset"));
				@$address->setStateOrProvince($params['shipping_address']['zoneID'], translate("str_default_charset"));
				@$address->setPostalCode($params['shipping_address']['zip'], translate("str_default_charset"));
				$ecr_params['AddressType'] = &$address;
//				$ecr_params['AddressOverride'] = 1;
//				$ecr_params['NoShipping'] = 1;
//				$ecr_params['useraction'] = 'commit';
//				$ecr_params['success_url_query'] = '&useraction=commit';
				
				$error_message = $PPECheckout->doSetExpressCheckoutRequest($ecr_params);
				if(Services_PayPal::isError($error_message)){
					$error_message = $error_message->getMessage();
				}
			}elseif (xDataExists('_PPECHECKOUT_ERROR')){
				$error_message = xPopData('_PPECHECKOUT_ERROR');
			}
	
			$html_ppec_button = 
				'<!-- ppec_btn_begin --->
				<p><a href="'.xHtmlSetQuery('ppexpresscheckout2=1').'"><image src="'.$PPECheckout->_getCheckoutButtonURL().'" /></a></p>
				'.($error_message?'
				<div style="color:red;">'.$error_message.'</div>
				':'').'
				<!-- ppec_btn_end --->
				';
		}while (0);}
		
		return str_replace("\n",'',$html_ppec_button);
	}
	
	function payment_process($order){

		$order_amount = RoundFloatValue($this->_convertCurrency($order["order_amount"], 0, $this->_getSettingValue('CONF_CPAYPALDIRECT_CURRENCY')));

		$shipping_amount = RoundFloatValue($this->_convertCurrency($order["shipping_cost"], 0, $this->_getSettingValue('CONF_CPAYPALDIRECT_CURRENCY')));
		
		require_once 'Services/PayPal.php';
		require_once 'Services/PayPal/Profile/Handler/Array.php';
		require_once 'Services/PayPal/Profile/API.php';

		// Settings.
		$certfile = $this->CertsPath.'/'.$this->_getSettingValue('CONF_CPAYPALDIRECT_CERTPATH');
		$certpass = '';
		$apiusername = $this->_getSettingValue('CONF_CPAYPALDIRECT_USERNAME');
		$apipassword = $this->_getSettingValue('CONF_CPAYPALDIRECT_PASSWORD');
		$subject = null;
		$environment = $this->_getSettingValue('CONF_CPAYPALDIRECT_MODE');
		
		$handler =& ProfileHandler_Array::getInstance(array(
			'username' => $apiusername,
			'certificateFile' => $certfile,
			'subject' => $subject,
			'environment' => $environment));
		
		$profile =& APIProfile::getInstance($apiusername, $handler);
		$profile->setAPIPassword($apipassword);
		
		$caller =& Services_PayPal::getCallerServices($profile);
		
		if(Services_PayPal::isError($caller))
		{
			print "Could not create CallerServices instance: ". $caller->getMessage();
			exit;
		}
		
		
		
		$name =& Services_PayPal::getType('PersonNameType');
		$name->setFirstName($_POST['ppdirect_first_name']);
		$name->setLastName($_POST['ppdirect_last_name']);
		
		$address =& Services_PayPal::getType('AddressType');
		$address->setStreet1($_POST['ppdirect_address']);
		$address->setCityName($_POST['ppdirect_city']);
		$address->setStateOrProvince($_POST['ppdirect_state_iso2']);
		$address->setCountry($_POST['ppdirect_country_iso2']);
		$address->setPostalCode($_POST['ppdirect_zip']);
		
		$payer =& Services_PayPal::getType('PayerInfoType');
		$payer->setPayer($order['customer_email']);
		$payer->setPayerID($order['customer_email']);
		$payer->setPayerStatus('verified');
		$payer->setPayerName($name);
		$payer->setPayerCountry($_POST['ppdirect_country_iso2']);
		$payer->setAddress($address);

		$cc =& Services_PayPal::getType('CreditCardDetailsType');
		$cc->setCreditCardNumber($_POST['ppdirect_ccnumber']);
		$cc->setCVV2($_POST['ppdirect_cvv2']);
		$cc->setCreditCardType($_POST['ppdirect_cctype']);
		$cc->setExpMonth($_POST['ppdirect_expmonth']);
		$cc->setExpYear($_POST['ppdirect_expyear']);
		$cc->setCardOwner($payer);
		
		$amount =& Services_PayPal::getType('BasicAmountType');
		$amount->setval(floatval($order_amount));
		$amount->setattr('currencyID', 'USD');

		/**
		 * Shipping
		 */
		$shippingamount =& Services_PayPal::getType('BasicAmountType');
		$shippingamount->setval(floatval($shipping_amount));
		$shippingamount->setattr('currencyID', 'USD');
		$itemamount =& Services_PayPal::getType('BasicAmountType');
		$itemamount->setval(floatval($order_amount - $shipping_amount));
		$itemamount->setattr('currencyID', 'USD');
		$shaddress =& Services_PayPal::getType('AddressType');

		$_country = cnGetCountryById($order["shipping_info"]["countryID"]);
		$_country = $_country['country_iso_2'];
		$_state = znGetSingleZoneById($order["shipping_info"]["zoneID"]);
		$_state = $_state['zone_code'];
		$shaddress->setName($order["shipping_info"]["first_name"]." ".$order["shipping_info"]["last_name"]);
		$shaddress->setStreet1($order["shipping_info"]["address"]);
		$shaddress->setCityName($order["shipping_info"]["city"]);
		$shaddress->setPostalCode($order["shipping_info"]["zip"]);
		$shaddress->setStateOrProvince($_state);
		$shaddress->setCountry($_country);

		
		$pdt =& Services_PayPal::getType('PaymentDetailsType');
		$pdt->setOrderTotal($amount);
		
		$pdt->setItemTotal($itemamount);
		$pdt->setShippingTotal($shippingamount);
		$pdt->setShipToAddress($shaddress);
		
		$pdt->setButtonSource('webasyst');
		
		$details =& Services_PayPal::getType('DoDirectPaymentRequestDetailsType');
		$details->setPaymentAction($this->_getSettingValue('CONF_CPAYPALDIRECT_PAYMENTACTION'));
		$details->setPaymentDetails($pdt);
		$details->setCreditCard($cc);		
		$details->setIPAddress($order['customer_ip']);
		$details->setMerchantSessionId('merchantId');
		
		$ddp =& Services_PayPal::getType('DoDirectPaymentRequestType');
		$ddp->setDoDirectPaymentRequestDetails($details);
		
		$response = $caller->DoDirectPayment($ddp);
		/* @var $response DoDirectPaymentResponseType */
		if($response->Ack != 'Success'){
		
			xSaveData('PPDIRECT_INFO', $_POST);
			$ErrorMessage = ' ';
			if(is_array($response->Errors)){
			
				foreach($response->Errors as $_Error){
				
					$ErrorMessage .= $_Error->ErrorCode.'- '.$_Error->ShortMessage.' ( '.$_Error->LongMessage.' )';
					break;
				}
			}elseif(isset($response->Errors)){
			
					$ErrorMessage .= $response->Errors->ErrorCode.'- '.$response->Errors->ShortMessage.' ( '.$response->Errors->LongMessage.' )';
			}else{
				$ErrorMessage = ' '.$response->message;
			}
			return $ErrorMessage;

		}else{
			
			xSaveData('PPEC_TRANSACTION_ID', $response->getTransactionID());
			return 1;
		}
	}

	function after_processing_php($_OrderID){
	
		if($this->_getSettingValue('CONF_CPAYPALDIRECT_ORDERSTATUS') != -1){
		
			ostSetOrderStatusToOrder($_OrderID, $this->_getSettingValue('CONF_CPAYPALDIRECT_ORDERSTATUS'));
		}
		
		xSaveData('PPEC_ORDER_ID', $_OrderID);
		Redirect(set_query('?ukey=ppec_order_success'));
	}
	
	function _filterPaymentMethod($method_info){

		$method_info['description'] = '<br />'.$this->_getPPECButtonHTML(array('shipping_address'=>$this->_getShippingAddress()));
		return $method_info;
	}
	
	function _getShippingAddress(){
		
		$shipping_address = array();
		if(isset($_GET['shippingAddressID'])){
			
			$shipping_address = regGetAddress($_GET['shippingAddressID']);
			$shipping_address['countryID'] = cnGetCountryById($shipping_address['countryID']);
			$shipping_address['countryID'] = $shipping_address['countryID']['country_iso_2'];
			$shipping_address['zoneID'] = znGetSingleZoneById($shipping_address['zoneID']);
			$shipping_address['zoneID'] = $shipping_address['zoneID']['zone_code'];
		}else{
			
			$shipping_address["first_name"] = $_SESSION["receiver_first_name"];
			$shipping_address["last_name"] = $_SESSION["receiver_last_name"];
			$shipping_address["countryID"] = $_SESSION["receiver_countryID"];
			$shipping_address["state"] = $_SESSION["receiver_state"];
			$shipping_address["zip"] = $_SESSION["receiver_zip"];
			$shipping_address["city"] = $_SESSION["receiver_city"];
			$shipping_address["address"] = $_SESSION["receiver_address"];
			$shipping_address["zoneID"] = $_SESSION["receiver_zoneID"];
			
			$shipping_address['countryID'] = cnGetCountryById($shipping_address['countryID']);
			$shipping_address['countryID'] = $shipping_address['countryID']['country_iso_2'];
			$shipping_address['zoneID'] = znGetSingleZoneById($shipping_address['zoneID']);
			$shipping_address['zoneID'] = $shipping_address['zoneID']['zone_code'];
		}

		return $shipping_address;
	}
}
?>