<?php
	/**
	 * @connect_module_class_name StreamlineDO
	 * @package DynamicModules
	 * @subpackage Payment
	 */
	class StreamlineDO extends PaymentModule {

		var $type = PAYMTD_TYPE_CC;
		var $language = 'eng';
		
		function _initVars(){
			
			parent::_initVars();
			$this->title = STREAMLINEDO_TTL;
			$this->description = STREAMLINEDO_DSCR;
			$this->sort_order = 1;
			
			$this->Settings = array( 
					'CONF_STREAMLINEDO_TEST',
					'CONF_STREAMLINEDO_MERCHANTCODE',
					'CONF_STREAMLINEDO_XMLPASSWORD',
					'CONF_STREAMLINEDO_TRANSCURR',
					'CONF_STREAMLINEDO_ORDERSTATUS',
				);
		}
	
		function _initSettingFields(){
	
			$this->SettingsFields['CONF_STREAMLINEDO_TEST'] = array(
				'settings_value' 		=> '', 
				'settings_title' 			=> STREAMLINEDO_CFG_TEST_TTL,
				'settings_description' 	=> STREAMLINEDO_CFG_TEST_DSCR,
				'settings_html_function' 	=> 'setting_CHECK_BOX(', 
				'sort_order' 			=> 1,
			);
	
			$this->SettingsFields['CONF_STREAMLINEDO_MERCHANTCODE'] = array(
				'settings_value' 		=> '', 
				'settings_title' 			=> STREAMLINEDO_CFG_MERCHANTCODE_TTL,
				'settings_description' 	=> STREAMLINEDO_CFG_MERCHANTCODE_DSCR,
				'settings_html_function' 	=> 'setting_TEXT_BOX(0,', 
				'sort_order' 			=> 1,
			);
			$this->SettingsFields['CONF_STREAMLINEDO_XMLPASSWORD'] = array(
				'settings_value' 		=> '', 
				'settings_title' 			=> STREAMLINEDO_CFG_XMLPASSWORD_TTL,
				'settings_description' 	=> STREAMLINEDO_CFG_XMLPASSWORD_DSCR,
				'settings_html_function' 	=> 'setting_TEXT_BOX(0,', 
				'sort_order' 			=> 1,
			);
			$this->SettingsFields['CONF_STREAMLINEDO_ORDERSTATUS'] = array(
				'settings_value' 		=> '', 
				'settings_title' 			=> STREAMLINEDO_CFG_ORDERSTATUS_TTL, 
				'settings_description' 	=> STREAMLINEDO_CFG_ORDERSTATUS_DSCR, 
				'settings_html_function' 	=> 'setting_SELECT_BOX(PaymentModule::_getStatuses(),', 
				'sort_order' 			=> 1,
			);
			$this->SettingsFields['CONF_STREAMLINEDO_TRANSCURR'] = array(
				'settings_value' 		=> '', 
				'settings_title' 			=> STREAMLINEDO_CFG_TRANSCURR_TTL, 
				'settings_description' 	=> STREAMLINEDO_CFG_TRANSCURR_DSCR, 
				'settings_html_function' 	=> 'setting_CURRENCY_SELECT(', 
				'sort_order' 			=> 1,
			);
		}
	
		function payment_form_html($_Params = null){
		
			if(xDataExists('_STREAMLINEDO')){
				
				$_tParams = xPopData('_STREAMLINEDO');
			}
			
			$res = '';
			
			$CurrYear4d = date('Y');
			$ExpYears = '';
			for($_Y = 0; $_Y<10; $_Y++){
			
				$_Selected = isset($_tParams['year'])?($_tParams['year']==($CurrYear4d+$_Y)):0;
				$ExpYears .= '<option value="'.($CurrYear4d+$_Y).'"'.($_Selected?' selected="selected"':'').'>'.($CurrYear4d+$_Y).'</option>';
			}
			
			global $rMonths;
			$ExpMonths = '';
			for($_M = 1; $_M<=12; $_M++){
			
				$_Selected = isset($_tParams['month'])?($_tParams['month']==$_M):0;
				$ExpMonths .= '<option value="'.sprintf('%02d',$_M).'"'.($_Selected?' selected="selected"':'').'>'.$rMonths[$_M].'</option>';
			}
			
			$cctype_options_html = '';
			$cctypes = array(
				'AMEX-SSL' => 'American Express',
				'VISA-SSL' => 'VISA',
				'ECMC-SSL' => 'MasterCard',
				'DINERS-SSL' => 'Diners',
				'LASER-SSL' => 'Laser Card',
				'JCB-SSL' => 'Japanese Credit Bank',
				);
			foreach ($cctypes as $k=>$name){
				
				$_Selected = isset($_tParams['cctype'])?($_tParams['cctype']==$k):0;
				$cctype_options_html .= '<option value="'.xHtmlSpecialChars($k).'"'.($_Selected?' selected="selected"':'').'>'.xHtmlSpecialChars($name).'</option>';
			}
			
			$res = '
				<table>
				<tr>
					<td align="right">'.STREAMLINEDO_TXT_CCTYPE.'</td>
					<td>
						<select name="cctype">'.$cctype_options_html.'</select>
					</td>
				</tr>
				<tr>
					<td align="right">'.STREAMLINEDO_TXT_CCNUMBER.'</td>
					<td>
						<input type="text" name="ccnumber" value="'.xHtmlSpecialChars(isset($_tParams['ccnumber'])?$_tParams['ccnumber']:'').'" />
					</td>
				</tr>
				<tr>
					<td align="right">'.STREAMLINEDO_TXT_CVC.'</td>
					<td>
						<input type="text" name="cvc" value="'.xHtmlSpecialChars(isset($_tParams['cvc'])?$_tParams['cvc']:'').'" />
					</td>
				</tr>
				<tr>
					<td align="right">'.STREAMLINEDO_TXT_EXPDATE.'</td>
					<td>
					<select name="month">'.$ExpMonths.'</select>&nbsp;<select name="year">'.$ExpYears.'</select>
					</td>
				</tr>
				</table>
				';
			return $res;
		}

		function payment_process($order){

			$currency = currGetCurrencyByID($this->_getSettingValue('CONF_STREAMLINEDO_TRANSCURR'));
			$order_amount = PaymentModule::_convertCurrency($order['order_amount'],0, $currency['currency_iso_3']);
			
			$xnPaymentService = new xmlNodeX('paymentService');
			$xnPaymentService->attribute('version','1.4');
			$xnPaymentService->attribute('merchantCode',$this->_getSettingValue('CONF_STREAMLINEDO_MERCHANTCODE'));
			
			$xnSubmit = &$xnPaymentService->child('submit');
			
			$xnOrder = &$xnSubmit->child('order', array('orderCode'=>md5(time())));
			$xnOrder->child('description', null, CONF_SHOP_NAME);
			
			$xnAmount = &$xnOrder->child('amount');
			$xnAmount->attribute('value',RoundFloatValue($order_amount)*100);
			$xnAmount->attribute('currencyCode',$currency['currency_iso_3']);
			$xnAmount->attribute('exponent','2');
			
			$cart_content_html = '';
			$cart_content = cartGetCartContent();
			foreach ($cart_content['cart_content'] as $cart_item){
				
				$cart_content_html .= '<tr><td>'.xHtmlSpecialChars($cart_item['name']).'</td></tr>';
			}
			
			$xnOrder->child('orderContent', null,'<table align="center">'.$cart_content_html.'</table>');
			
			$xnPaymentDetails = &$xnOrder->child('paymentDetails');
			$xnPaymentDetails->attribute('action', 'AUTHORISE');
			
			$xnCCTYPE = &$xnPaymentDetails->child($_POST['cctype']);
			
			$xnCardNumber = &$xnCCTYPE->child('cardNumber');
			$xnCardNumber->setData($_POST['ccnumber']);
			$xnExpiryDate = &$xnCCTYPE->child('expiryDate');
			$xnDate = &$xnExpiryDate->child('date');
			$xnDate->attribute('month',$_POST['month']);
			$xnDate->attribute('year',$_POST['year']);
			$xnCardHolderName = &$xnCCTYPE->child('cardHolderName');
			$xnCardHolderName->setData($order['billing_info']['first_name'].' '.$order['billing_info']['last_name']);
			$xnCVC = &$xnCCTYPE->child('cvc');
			$xnCVC->setData($_POST['cvc']);

			$xnShopper = &$xnOrder->child('shopper');
			$xnShopperEmailAddress = &$xnShopper->child('shopperEmailAddress');
			$xnShopperEmailAddress->setData($order['customer_email']);
			
			$reqxml = 
'<?xml version="1.0"?>
<!DOCTYPE paymentService PUBLIC "-//streamline-esolutions//DTD Streamline PaymentService v1//EN" "http://dtd.streamline-esolutions.com/paymentService_v1.dtd">
'.$xnPaymentService->getNodeXML();

//print nl2br(xHtmlSpecialChars($xnPaymentService->getNodeXML(-1, true)));die;
			
			$req_url = $this->_getSettingValue('CONF_STREAMLINEDO_TEST')?
				'https://secure-test.streamline-esolutions.com/jsp/merchant/xml/paymentService.jsp':
				'https://secure.streamline-esolutions.com/jsp/merchant/xml/paymentService.jsp';
			
			$ch=curl_init();
			if(!$ch){
				
				xSaveData('_STREAMLINEDO', $_POST);
				return (sprintf('Error1 [%d]: %s',curl_errno($ch),curl_error($ch)));
			}
			
			curl_setopt ($ch, CURLOPT_URL,$req_url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Authorization: Basic '.base64_encode(
				$this->_getSettingValue('CONF_STREAMLINEDO_MERCHANTCODE').':'.$this->_getSettingValue('CONF_STREAMLINEDO_XMLPASSWORD')),
				'Content-Type: text/xml'));
			curl_setopt ($ch, CURLOPT_POST, 1); 
			curl_setopt ($ch, CURLOPT_POSTFIELDS, $reqxml);
			if ($this->_getSettingValue('CONF_STREAMLINEDO_TEST')) {
				
				curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
			}
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
			initCurlProxySettings($ch);
			
			$http_response = curl_exec($ch);
			
			if(!$http_response){
				
				xSaveData('_STREAMLINEDO', $_POST);
				return (sprintf('Error2 [%d]: %s',curl_errno($ch),curl_error($ch)));
			}
			curl_close($ch);
			
			$xnResponse = new xmlNodeX();
			$xnResponse->renderTreeFromInner($http_response);
			$r_xnError = $xnResponse->xPath('/paymentService/reply/error');
			if(!count($r_xnError)){
				
				$r_xnError = $xnResponse->xPath('/paymentService/reply/orderStatus/error');
			}
			if(count($r_xnError)){
				
				xSaveData('_STREAMLINEDO', $_POST);
				list($xnError) = $r_xnError;
				/* @var $xnError xmlNodeX */
				return $xnError->getData()?($xnError->getData().($xnError->attribute('code')?('('.$xnError->attribute('code').')'):'')):$xnError->attribute('code');
			}
			
			list($xnLastEvent) = $xnResponse->xPath('/paymentService/reply/orderStatus/payment/lastEvent');
			/* @var $xnLastEvent xmlNodeX */
			if($xnLastEvent->getData()!='AUTHORISED'){
				
				xSaveData('_STREAMLINEDO', $_POST);
				return sprintf(STREAMLINEDO_TXT_AUTHDECLINED, $xnLastEvent->getData());
			}
			
			return 1;
		}
	
		function after_processing_php($_OrderID){
		
			if($this->_getSettingValue('CONF_STREAMLINEDO_ORDERSTATUS') != -1){
			
				ostSetOrderStatusToOrder($_OrderID, $this->_getSettingValue('CONF_STREAMLINEDO_ORDERSTATUS'));
			}
		}
	}
?>