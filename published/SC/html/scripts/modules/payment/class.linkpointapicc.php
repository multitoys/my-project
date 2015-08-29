<?php
/**
 * @connect_module_class_name LinkPointAPICC
 * @package DynamicModules
 * @subpackage Payment
 */

include_once(DIR_MODULES.'/payment/data.php');
include_once(DIR_MODULES.'/payment/linkpoitapicc/lphp.php');

class LinkPointAPICC extends PaymentModule {

	var $type = PAYMTD_TYPE_CC;
	var $language = 'eng';
	var $CertsPath = '';
	var $default_logo = 'http://www.webasyst.net/collections/design/payment-icons/linkpoint.gif';
	
	function _initVars(){
		
		$this->CertsPath = DIR_TEMP;
		$this->title 		= LPAPICC_TTL;
		$this->description 	= LPAPICC_DSCR;
		$this->sort_order 	= 1;
		
		$this->Settings = array( 
				'CONF_LPAPICC_MERCHNUMBER',
				'CONF_LPAPICC_CERTPATH',
				'CONF_LPAPICC_MODE',
				'CONF_LPAPICC_HOST',
				'CONF_LPAPICC_PAYMENTACTION',
				'CONF_LPAPICC_CVV',
				'CONF_LPAPICC_AVAILABLECREDITCARDS',
				'CONF_LPAPICC_ORDERSTATUS',
				'CONF_LPAPICC_CURRENCY',
			);
	}

	function _initSettingFields(){

		$this->SettingsFields['CONF_LPAPICC_MERCHNUMBER'] = array(
			'settings_value' 		=> '', 
			'settings_title' 			=> LPAPICC_CFG_MERCHNUMBER_TTL, 
			'settings_description' 	=> LPAPICC_CFG_MERCHNUMBER_DSCR, 
			'settings_html_function' 	=> 'setting_TEXT_BOX(0,', 
			'sort_order' 			=> 1,
		);
		$this->SettingsFields['CONF_LPAPICC_CERTPATH'] = array(
			'settings_value' 		=> '', 
			'settings_title' 			=> LPAPICC_CFG_CERTPATH_TTL, 
			'settings_description' 	=> LPAPICC_CFG_CERTPATH_DSCR, 
			'settings_html_function' 	=> 'setting_SINGLE_FILE(DIR_TEMP,',
			'sort_order' 			=> 1,
		);
		$this->SettingsFields['CONF_LPAPICC_MODE'] = array(
			'settings_value' 		=> 'GOOD', 
			'settings_title' 			=> LPAPICC_CFG_MODE_TTL, 
			'settings_description' 	=> LPAPICC_CFG_MODE_DSCR, 
			'settings_html_function' 	=> 'setting_RADIOGROUP(LPAPICC_TXT_LIVE.":LIVE,".LPAPICC_TXT_TESTGOOD.":GOOD,".LPAPICC_TXT_TESTDECLINE.":DECLINE",', 
			'sort_order' 			=> 1,
		);
		$this->SettingsFields['CONF_LPAPICC_HOST'] = array(
			'settings_value' 		=> 'staging.linkpt.net', 
			'settings_title' 			=> LPAPICC_CFG_HOST_TTL, 
			'settings_description' 	=> LPAPICC_CFG_HOST_DSCR, 
			'settings_html_function' 	=> 'setting_TEXT_BOX(0,', 
			'sort_order' 			=> 1,
		);
		$this->SettingsFields['CONF_LPAPICC_PAYMENTACTION'] = array(
			'settings_value' 		=> 'SALE', 
			'settings_title' 			=> LPAPICC_CFG_PAYMENTACTION_TTL, 
			'settings_description' 	=> LPAPICC_CFG_PAYMENTACTION_DSCR, 
			'settings_html_function' 	=> 'setting_RADIOGROUP("Sale:SALE,Authorization:PREAUTH",', 
			'sort_order' 			=> 1,
		);
		$this->SettingsFields['CONF_LPAPICC_CVV'] = array(
			'settings_value' 		=> '', 
			'settings_title' 			=> LPAPICC_CFG_CVV_TTL, 
			'settings_description' 	=> LPAPICC_CFG_CVV_DSCR, 
			'settings_html_function' 	=> 'setting_CHECK_BOX(', 
			'sort_order' 			=> 1,
		);
		$this->SettingsFields['CONF_LPAPICC_AVAILABLECREDITCARDS'] = array(
			'settings_value' 		=> '', 
			'settings_title' 			=> LPAPICC_CFG_AVAILABLECREDITCARDS_TTL, 
			'settings_description' 	=> LPAPICC_CFG_AVAILABLECREDITCARDS_DSCR, 
			'settings_html_function' 	=> 'setting_CHECKBOX_LIST(LinkPointAPICC::_getCardTypes(),', 
			'sort_order' 			=> 1,
		);
		$this->SettingsFields['CONF_LPAPICC_ORDERSTATUS'] = array(
			'settings_value' 		=> '-1', 
			'settings_title' 			=> LPAPICC_CFG_ORDERSTATUS_TTL, 
			'settings_description' 	=> LPAPICC_CFG_ORDERSTATUS_DSCR, 
			'settings_html_function' 	=> 'setting_SELECT_BOX(PaymentModule::_getStatuses(),', 
			'sort_order' 			=> 1,
		);
		$this->SettingsFields['CONF_LPAPICC_CURRENCY'] = array(
			'settings_value' 		=> '', 
			'settings_title' 			=> LPAPICC_CFG_CURRENCY_TTL, 
			'settings_description' 	=> LPAPICC_CFG_CURRENCY_DSCR, 
			'settings_html_function' 	=> 'setting_CURRENCY_SELECT(', 
			'sort_order' 			=> 1,
		);
	}
	

	
	/**
	 * @return array assoc array with card types (<card_id> => <card_name>)
	 */
	function _getCardTypes(){
		
		global $CardNames, $CardBinTypes;
		
		$CardTypes = array(
			$CardBinTypes[CCTYPE_VISA] => $CardNames[CCTYPE_VISA],
			$CardBinTypes[CCTYPE_MASTERCARD] => $CardNames[CCTYPE_MASTERCARD],
			$CardBinTypes[CCTYPE_AMEXPRESS] => $CardNames[CCTYPE_AMEXPRESS],
			$CardBinTypes[CCTYPE_DISCOVER] => $CardNames[CCTYPE_DISCOVER],
			$CardBinTypes[CCTYPE_JCB] => $CardNames[CCTYPE_JCB],
		);
		return $CardTypes;
	}

	function payment_form_html($_Params = null){
	
		global $rMonths;
		
		if(xDataExists('_LPAPICC_POST')){
			
			$_Params = xPopData('_LPAPICC_POST');
		}
		
		$CurrYear2d = date('y');
		$CurrYear4d = date('Y');
		$ExpYears = '';
		for($_Y = 0; $_Y<10; $_Y++){
		
			$_Selected = isset($_Params['lpapicc_expyear'])?($_Params['lpapicc_expyear']==($CurrYear2d+$_Y)):0;
			$ExpYears .= '<option value="'.($CurrYear2d+$_Y).'"'.($_Selected?' selected="selected"':'').'>'.($CurrYear4d+$_Y).'</option>';
		}
		
		$ExpMonths = '';
		for($_M = 1; $_M<=12; $_M++){
		
			$_Selected = isset($_Params['lpapicc_expmonth'])?($_Params['lpapicc_expmonth']==($_M)):0;
			$ExpMonths .= '<option value="'.$_M.'"'.($_Selected?' selected="selected"':'').'>'.$rMonths[$_M].'</option>';
		}
		
		global $CardNames;
		$CCImages = array(
			CCTYPE_VISA => 'ccvisa.gif',
			CCTYPE_MASTERCARD => 'ccmastercard.gif',
			CCTYPE_AMEXPRESS => 'ccamex.gif',
			CCTYPE_DISCOVER => 'ccdiscover.gif',
			CCTYPE_JCB => 'ccjcb.jpg',
			CCTYPE_AUSTRALIANBANKCARD => 'Australian BankCard'
		);
		$AvailableCreditCards = $this->_getSettingAVAILABLECREDITCARDS();
		$ACreditCardsHTML = '';
		foreach ($AvailableCreditCards as $ACreditCard){
			
			$ACreditCardsHTML .= '<img alt="'.xHtmlSpecialChars($CardNames[$ACreditCard]).'" src="./images/'.$CCImages[$ACreditCard].'" hspace="6" vspace="6" />';
		}
		
		return '
		'.LPAPICC_TXT_AVAILABLECREDITCARDS.'<br />
		'.$ACreditCardsHTML.'
		<table>
		<tr>
			<td>'.LPAPICC_TXT_CCNUMBER.'</td>
			<td><input type="text" name="lpapicc_ccnumber" value="'.(isset($_Params['lpapicc_ccnumber'])?$_Params['lpapicc_ccnumber']:'').'" /></td>
		</tr>'.($this->_getSettingValue('CONF_LPAPICC_CVV')?'
		<tr>
			<td>'.LPAPICC_TXT_CVV.'</td>
			<td><input type="text" name="lpapicc_cvv" value="'.(isset($_Params['lpapicc_cvv'])?$_Params['lpapicc_cvv']:'').'" /></td>
		</tr>':'').'
		<tr>
			<td>'.LPAPICC_TXT_EXPDATE.'</td>
			<td><select name="lpapicc_expmonth">'.$ExpMonths.'</select>&nbsp;<select name="lpapicc_expyear">'.$ExpYears.'</select></td>
		</tr>
		</table>
		';
	}

	function payment_process($order){

		$AvailableCreditCards = $this->_getSettingAVAILABLECREDITCARDS();
		$CreditCardType = $this->_recognizeCreditCardType($_POST['lpapicc_ccnumber']);
		if(!in_array($CreditCardType, $AvailableCreditCards)){
			
			xSaveData('_LPAPICC_POST', $_POST);
			if(is_null($CreditCardType)){
				
				return LPAPICC_MSG_UNKNOWNCCTYPE;
			}else {
				
				global $CardNames;
				return str_replace('%cardname%', $CardNames[$CreditCardType], LPAPICC_MSG_UNAVAILABLECCTYPE);
			}
		}
		
		$mylphp=new lphp;
		
		$myorder["host"]       = $this->_getSettingValue('CONF_LPAPICC_HOST');
		$myorder["port"]       = "1129";
		$myorder["keyfile"]    = $this->CertsPath.'/'.$this->_getSettingValue('CONF_LPAPICC_CERTPATH'); # Change this to the name and location of your certificate file 
		$myorder["configfile"] = $this->_getSettingValue('CONF_LPAPICC_MERCHNUMBER');        # Change this to your store number 
		$myorder["ordertype"]    = $this->_getSettingValue('CONF_LPAPICC_PAYMENTACTION');
		$myorder["result"]       = $this->_getSettingValue('CONF_LPAPICC_MODE'); # For a test, set result to GOOD, DECLINE, or DUPLICATE
		$myorder["cardnumber"]   = $_POST['lpapicc_ccnumber'];
		if($this->_getSettingValue('CONF_LPAPICC_CVV')){
			
			$myorder["cvmindicator"] = "provided";
			$myorder["cvmvalue"]     = $_POST['lpapicc_cvv'];
		}
		$myorder["cardexpmonth"] = sprintf('%02d',$_POST['lpapicc_expmonth']);
		$myorder["cardexpyear"]  = sprintf('%02d',$_POST['lpapicc_expyear']);
		$myorder["chargetotal"]  = RoundFloatValue($this->_convertCurrency($order["order_amount"], 0, $this->_getSettingValue('CONF_LPAPICC_CURRENCY')));

		# BILLING INFO
		$myorder["name"]     = $order['billing_info']['first_name'].' '.$order['billing_info']['last_name'];
		$myorder["address1"] = $order['billing_info']['address'];
		$myorder["city"]     = $order['billing_info']['city'];
		$myorder["state"]    = $order['billing_info']['state'];
		$myorder["zip"]      = $order['billing_info']['zip'];
		$myorder["country"]  = $order['billing_info']['country_name'];
		$myorder["email"]    = $order['customer_email'];
		
		# SHIPPING INFO
		$myorder["sname"]     = $order['shipping_info']['first_name'].' '.$order['shipping_info']['last_name'];
		$myorder["saddress1"] = $order['shipping_info']['address'];
		$myorder["scity"]     = $order['shipping_info']['city'];
		$myorder["sstate"]    = $order['shipping_info']['state'];
		$myorder["szip"]      = $order['shipping_info']['zip'];
		$myorder["scountry"]  = $order['shipping_info']['country_name'];

//		$myorder["debugging"] = "true";  # for development only - not intended for production use
		
		
		# Send transaction. Use one of two possible methods  #
		$result = $mylphp->curl_process($myorder);  # use curl methods
		
		if ($result["r_approved"] != "APPROVED")	// transaction failed, print the reason
		{	
			$Return = "<blockquote><div>{$result['r_approved']}</div><div>{$result['r_error']}</div></blockquote>";
			xSaveData('_LPAPICC_POST', $_POST);
		}
		else
		{	// success
//			print "Status: $result[r_approved]\n";
//			print "Code: $result[r_code]\n";
//			print "OID: $result[r_ordernum]\n\n";
			$Return = 1;
		}
		
		if($myorder["debugging"] == 'true'){
			print $Return;die;
		}
		
		return $Return;
	}

	function after_processing_php($_OrderID){
	
		if($this->_getSettingValue('CONF_LPAPICC_ORDERSTATUS') != -1){
		
			ostSetOrderStatusToOrder($_OrderID, $this->_getSettingValue('CONF_LPAPICC_ORDERSTATUS'));
		}
	}

	/**
	 * Recognize credit card type
	 *
	 * @param string $CardNumber
	 * @return string | null credit card type name
	 */
	function _recognizeCreditCardType($CardNumber){
		
    $CardNumber = ereg_replace('[^0-9]', '', $CardNumber);
    $CardType = null;
 
		if (ereg('^4[0-9]{12}([0-9]{3})?$', $CardNumber)) {
		
			$CardType = CCTYPE_VISA;
		} elseif (ereg('^5[1-5][0-9]{14}$', $CardNumber)) {
			
			$CardType = CCTYPE_MASTERCARD;
		} elseif (ereg('^3[47][0-9]{13}$', $CardNumber)) {
			
			$CardType = CCTYPE_AMEXPRESS;
		} elseif (ereg('^3(0[0-5]|[68][0-9])[0-9]{11}$', $CardNumber)) {
			
			$CardType = CCTYPE_DINERS_CLUB;
		} elseif (ereg('^6011[0-9]{12}$', $CardNumber)) {
			
			$CardType = CCTYPE_DISCOVER;
		} elseif (ereg('^(3[0-9]{4}|2131|1800)[0-9]{11}$', $CardNumber)) {
			
			$CardType = CCTYPE_JCB;
		} elseif (ereg('^5610[0-9]{12}$', $CardNumber)) {
			
			$CardType = CCTYPE_AUSTRALIANBANKCARD;
		}
      
		return $CardType;
	}
	
	/**
	 * @return array
	 */
	function _getSettingAVAILABLECREDITCARDS(){
		
		global $CardBinTypes;
		
		$CardBinTypesFliped = array_flip($CardBinTypes);
		
		$AvailableCCTypes = array();
		$CBinTypes = array_keys($this->_getCardTypes());
		$SettingValue = $this->_getSettingValue('CONF_LPAPICC_AVAILABLECREDITCARDS');

		foreach ($CBinTypes as $CBinType){
			
			if($SettingValue&pow(2,$CBinType)){
				
				$AvailableCCTypes[] = $CardBinTypesFliped[$CBinType];
			}
		}
		
		return $AvailableCCTypes;
	}
}
?>