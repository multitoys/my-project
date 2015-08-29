<?php
/**
 * @connect_module_class_name ChronopayDirect
 * @package DynamicModules
 * @subpackage Payment
 * 
 */
class ChronopayDirect extends PaymentModule {

	var $type = PAYMTD_TYPE_CC;
	var $debug = true;

	//var $processing_url = 'https://secure.chronopay.com/gateway.cgi';
	var $processing_url = 'https://secure.chronopay.com/index_shop.cgi';
	var $default_logo = 'http://www.webasyst.net/collections/design/payment-icons/chronopay.gif';

	function _initVars(){
			
		parent::_initVars();
		$this->title = CHRONOPAYDIRECT_TTL;
		$this->description = CHRONOPAYDIRECT_DSCR;
		$this->sort_order = 1;
		$this->log_mode = MODULE_LOG_CURL;
			
		$this->Settings = array(
		'CONF_CHRONOPAYDIRECT_PRODUCT_ID',
		'CONF_CHRONOPAYDIRECT_CURCODE',
		'CONF_CHRONOPAYDIRECT_SHARED_SECRET',
		'CONF_CHRONOPAYDIRECT_ORDERSTATUS',
		);
	}

	function _initSettingFields(){

		$this->SettingsFields['CONF_CHRONOPAYDIRECT_PRODUCT_ID'] = array(
		'settings_value' 		=> '',
		'settings_title' 			=> CHRONOPAYDIRECT_CFG_PRODUCT_ID_TTL,
		'settings_description' 	=> CHRONOPAYDIRECT_CFG_PRODUCT_ID_DSCR,
		'settings_html_function' 	=> 'setting_TEXT_BOX(0,',
		'sort_order' 			=> 1,
		);
		$this->SettingsFields['CONF_CHRONOPAYDIRECT_CURCODE'] = array(
		'settings_value' 		=> '',
		'settings_title' 			=> CHRONOPAYDIRECT_CFG_CURCODE_TTL,
		'settings_description' 	=> CHRONOPAYDIRECT_CFG_CURCODE_DSCR,
		'settings_html_function' 	=> 'setting_CURRENCY_SELECT(',
		'sort_order' 			=> 1,
		);
		$this->SettingsFields['CONF_CHRONOPAYDIRECT_SHARED_SECRET'] = array(
		'settings_value' 		=> '',
		'settings_title' 			=> CHRONOPAYDIRECT_CFG_SHAREDSECRET_TTL,
		'settings_description' 	=> CHRONOPAYDIRECT_CFG_SHAREDSECRET_DSCR,
		'settings_html_function' 	=> 'setting_TEXT_BOX(0,',
		'sort_order' 			=> 1,
		);
		$this->SettingsFields['CONF_CHRONOPAYDIRECT_ORDERSTATUS'] = array(
			'settings_value' 		=> '-1', 
			'settings_title' 			=> CHRONOPAYDIRECT_CFG_ORDERSTATUS_TTL, 
			'settings_description' 	=> CHRONOPAYDIRECT_CFG_ORDERSTATUS_DSCR, 
			'settings_html_function' 	=> 'setting_SELECT_BOX(PaymentModule::_getStatuses(),', 
			'sort_order' 			=> 1,
		);
	}


	function payment_form_html($billingAddress = array()){
		/*
		 Field Function Present Required

		 fname Customer’s first name
		 lname Customer’s last name
		 cardholder Cardholder’s name *
		 zip ZIP code *
		 street Address
		 city City
		 state State of residence (US/Canada only) *
		 country Country
		 email Email
		 phone Phone number *
		 card_no Card number
		 cvv CVV value
		 expirey Card expiration year (YYYY)
		 expirem Card’s expiration month(MM)
		 ----------
		 opcode Operation code
		 product_id Product id 1-8 1-8
		 ip User IP address 1,4 1,4
		 amount Amount to charge 1-4,8 1,3,4,8
		 currency Currency (ISO3) 1,4,8 1,4,8
		 */
		if(xDataExists('_CHRONOPAYDIRECT')){
			$_Params = xPopData('_CHRONOPAYDIRECT');
		}else{
			$_Params = array();
			$_Params['cardholder'] = strtoupper(translit($billingAddress['last_name'].' '.$billingAddress['first_name']));
			$Customer = new Customer();
			$Customer->loadByID($billingAddress['customerID']);
			$_Params['email'] = $Customer->Email;
			unset($Customer);
			
		}
		
		$resHtml ="\n<table>";
		$resHtml .="\n<tr><td>".CHRONOPAYDIRECT_TXT_CARDHOLDER."</td><td><input name=\"cardholder\" value=\"{$_Params['cardholder']}\" size=\"35\" /></td></tr>";
		$resHtml .="\n<tr><td>".CHRONOPAYDIRECT_TXT_PHONE."</td><td><input name=\"phone\" value=\"{$_Params['phone']}\" size=\"20\" /></td></tr>";
	//	$resHtml .="\n<tr><td>email</td><td><input name=\"email\" value=\"{$_Params['email']}\" size=\"35\" /></td></tr>";
		$resHtml .="\n<tr><td>".CHRONOPAYDIRECT_TXT_CARD_NUMBER."</td><td><input name=\"card_no\" value=\"{$_Params['card_no']}\" size=\"20\" /></td></tr>";
		$resHtml .="\n<tr><td>".CHRONOPAYDIRECT_TXT_EXPIRATION."</td><td>".$this->inputCCMonth('expirem','Month',$_Params['expirem']).'/'.$this->inputCCYear('expirey','Year',$_Params['expirey'])."</td></tr>";
		$resHtml .="\n<tr><td>".CHRONOPAYDIRECT_TXT_CVV."</td><td><input name=\"cvv\" value=\"{$_Params['cvv']}\" size=\"6\" /></td></tr>";
		$resHtml .="\n</table>";
		

		return $resHtml;

	}

	function payment_process(&$order){
		//validate input data
		$user_fields = array(
		'cardholder'	=> 'text',
		'phone'			=> 'phone',
		'card_no'		=> 'number',
		'cvv'			=> 'number',
		'expirey'		=> 'text',
		'expirem'		=> 'text',
		);

		$vars = array();
		foreach ($user_fields as $name => $type){
			if(isset($_POST[$name])){
				$vars[$name] = $_POST[$name];
			}else{

			}
		}
		$vars['cardholder'] = $vars['cardholder'];
		if(isset($vars[$name])&&intval($vars[$name])<10){
			$vars[$name] = "0".intval($vars[$name]);
		}
		

		$order_fields = array(
		//field 		=> source
		'fname'			=> 'billing_info/first_name',
		'lname'			=> 'billing_info/last_name',
		
		'zip'			=> 'billing_info/zip',
		'street'		=> 'billing_info/address',
		'city'			=> 'billing_info/city',
		'country'		=> 'billing_info/countryID',
		'email'			=> 'customer_email',
		
		'product_id' 	=> 'orderID',
		'amount' 		=> 'order_amount',
		'ip'			=> 'customer_ip',
		'currency' 		=> 'currency_code',
		);
		

		foreach ($order_fields as $name => $source){
			if(strpos($source,'/')){
				$source = explode('/',$source);
				if(isset($order[$source[0]][$source[1]])){
					$vars[$name] = translit($order[$source[0]][$source[1]]);
				}				
			}else{
				if(isset($order[$source])){
					$vars[$name] = translit($order[$source]);
				}
			}
		}
		/*
		 opcode Operation code
		 product_id Product id 1-8 1-8
		 +ip User IP address 1,4 1,4
		 +amount Amount to charge 1-4,8 1,3,4,8
		 +currency Currency (ISO3) 1,4,8 1,4,8
		 */
		$vars['opcode'] = 1;
		$vars['country'] = cnGetCountryById($vars['country']);
		if(is_array($vars['country'])){
			$vars['country'] = $vars['country']['country_iso_3'];
		}else{
			$vars['country']='';
		}
		
		if($vars['country'] == 'USA'||$vars['country'] == 'USA'){
			$vars['state'] = $order['billing_info']['zoneID'];
			$vars['state'] = znGetSingleZoneById($vars['state']);
			
			if(is_array($vars['state'])){
				$vars['state'] = $vars['state']['zone_code'];
			}else{
				$vars['state'] = $order['billing_info']['state'];
			}
		}
		

		
		$vars['product_id'] = $this->_getSettingValue('CONF_CHRONOPAYDIRECT_PRODUCT_ID');
		
		$vars['user_agent'] = isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:'unknown';
		$vars['show_transaction_id'] = 'Y';
		
		foreach($vars as $key => $value){
			if(!strlen($value))unset($vars[$key]);
		}
		
		//shared secret + opcode + product_id + fname + lname + street + ip +card_no + amount
		$vars['hash'] = md5($this->_getSettingValue('CONF_CHRONOPAYDIRECT_SHARED_SECRET').
							$vars['opcode'].
							$vars['product_id'].
							$vars['fname'].
							$vars['lname'].
							$vars['street'].
							$vars['ip'].
							$vars['card_no'].
							$vars['amount']);


		//$vars['sign'] = $vars['hash'];
		//send request
		//var_dump($vars);
		if(!function_exists('curl_init')){
			return 'error using payment module: Curl requered';
		}
		$ch = curl_init();
		@curl_setopt( $ch, CURLOPT_URL, $this->processing_url );
		@curl_setopt( $ch, CURLOPT_POST, 1);
		@curl_setopt( $ch, CURLOPT_POSTFIELDS, $vars );
		@curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		@curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
		@curl_setopt( $ch, CURLOPT_TIMEOUT, 120 );
		@curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 120 );
		@curl_setopt( $ch, CURLE_OPERATION_TIMEOUTED, 120 );
		
		
		initCurlProxySettings($ch);

		$result = curl_exec($ch);
		$_result = $result;

		

		//parse responce
		if(curl_errno($ch)!=0){
			$this->_log(LOGTYPE_ERROR, 'Curl error #'.curl_errno($ch).' '.curl_error($ch));
			return CHRONOPAYDIRECT_TXT_ERROR_PROCESSING;
		}
		curl_close ($ch);
		$result = explode("\r\n",$result);
		foreach($result as &$line){
			$line = array_map('trim',explode('|',$line));
		}
		//var_dump($result);
		switch(isset($result[0])&&isset($result[0][0])?$result[0][0]:null){
			case 'Y':
				xPopData('_CHRONOPAYDIRECT');
				$this->_setOrderStatus($order,'-');
				break;
			case 'N':
				xSaveData('_CHRONOPAYDIRECT', $_POST);
				return $result[0][1].($this->debug?var_export(array($vars,$_result,$result),true):'');
				break;
			case 'T':
				switch(isset($result[1])&&isset($result[1][0])?$result[1][0]:null){
					case 'Y':
						xPopData('_CHRONOPAYDIRECT');
						$this->_setOrderStatus($order,$result[1][1]);
						break;
					case 'N':
						xSaveData('_CHRONOPAYDIRECT', $_POST);
						return nl2br($result[1][1]).($this->debug?var_export(array($vars,$_result,$result),true):'');
						break;
					default:
						xSaveData('_CHRONOPAYDIRECT', $_POST);
						return CHRONOPAYDIRECT_INVALID_SERVER_RESPONCE.($this->debug?var_export(array($_result,$result),true):'');
						break;
						
						
				}
				break;
			default:
				xSaveData('_CHRONOPAYDIRECT', $_POST);
				return CHRONOPAYDIRECT_INVALID_SERVER_RESPONCE.($this->debug?var_export(array($_result,$result),true):'');
				break;
				
				
		}

		// save data only if not success result
		
		return 1;
	}
	
	function _setOrderStatus(&$order,$details = '')
	{
		//parent::_getSettingValue();
		$statusID = $this->_getSettingValue('CONF_CHRONOPAYDIRECT_ORDERSTATUS');
		if($statusID){
			$order['statusID'] = $statusID;
			$order["customers_comment"] .= translate('ordr_comment_orderplaced')." ID:{$details}";
			//var_dump(array($statusID,$order,$details));
			//stChangeOrderStatus($order['statusID'],$statusID,translate('ordr_comment_orderplaced')." ID:{$details}");
			//$orderID, $statusID, $comment = '', $notify = 0, $sendCommentOnly = false ){
		}
	}

	function transactionResultHandler($transaction_result = ''){
		//print "<br><h2>{$this->title}</h2><hr><pre>";
		//var_dump($_POST);
		//print "</pre><br><hr>";
		//$this->validateResultKey(array($orderID,$order['customer_email']));
		return parent::transactionResultHandler($transaction_result);
	}
}
?>