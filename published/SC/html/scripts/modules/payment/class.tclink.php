<?php
	/**
	 * @connect_module_class_name TCLink
	 * @package DynamicModules
	 * @subpackage Payment
	 */
	
	class TCLink extends PaymentModule {

		var $type = PAYMTD_TYPE_CC;
		var $language = 'eng';
		
		function _getActionOptions(){
			
			$options = array();
			$options[] = array(
				'title' => TCLINK_ACTION_PREAUTH,
				'value' => 'preauth',
				);
			$options[] = array(
				'title' => TCLINK_ACTION_SALE,
				'value' => 'sale',
				);
			return $options;
		}
		
		function _initVars(){
			
			parent::_initVars();
			$this->title = TCLINK_TTL;
			$this->description = TCLINK_DSCR;
			$this->sort_order = 1;
			
			$this->Settings = array( 
					'CONF_TCLINK_USERID',
					'CONF_TCLINK_PWD',
					'CONF_TCLINK_ACTION',/* preauth,sale */
					'CONF_TCLINK_DEMO',/* for demo y */
					'CONF_TCLINK_ORDERSTATUS',
				);
		}
	
		function _initSettingFields(){
	
			$this->SettingsFields['CONF_TCLINK_USERID'] = array(
				'settings_value' 		=> '', 
				'settings_title' 			=> TCLINK_CFG_USERID_TTL, 
				'settings_description' 	=> TCLINK_CFG_USERID_DSCR, 
				'settings_html_function' 	=> 'setting_TEXT_BOX(0,', 
				'sort_order' 			=> 1,
			);
			$this->SettingsFields['CONF_TCLINK_PWD'] = array(
				'settings_value' 		=> '', 
				'settings_title' 			=> TCLINK_CFG_PWD_TTL, 
				'settings_description' 	=> TCLINK_CFG_PWD_DSCR, 
				'settings_html_function' 	=> 'setting_TEXT_BOX(0,', 
				'sort_order' 			=> 1,
			);
			$this->SettingsFields['CONF_TCLINK_ACTION'] = array(
				'settings_value' 		=> 'preauth', 
				'settings_title' 			=> TCLINK_CFG_ACTION_TTL, 
				'settings_description' 	=> TCLINK_CFG_ACTION_DSCR, 
				'settings_html_function' 	=> 'setting_RADIOGROUP(TCLink::_getActionOptions(),', 
				'sort_order' 			=> 1,
			);
			$this->SettingsFields['CONF_TCLINK_DEMO'] = array(
				'settings_value' 		=> '', 
				'settings_title' 			=> TCLINK_CFG_DEMO_TTL, 
				'settings_description' 	=> TCLINK_CFG_DEMO_DSCR, 
				'settings_html_function' 	=> 'setting_CHECK_BOX(', 
				'sort_order' 			=> 1,
			);
			$this->SettingsFields['CONF_TCLINK_ORDERSTATUS'] = array(
				'settings_value' 		=> '', 
				'settings_title' 			=> TCLINK_CFG_ORDERSTATUS_TTL, 
				'settings_description' 	=> TCLINK_CFG_ORDERSTATUS_DSCR, 
				'settings_html_function' 	=> 'setting_SELECT_BOX(PaymentModule::_getStatuses(),', 
				'sort_order' 			=> 1,
			);
		}

		function payment_form_html($_Params = null){
		
			global $rMonths;
			
			if(xDataExists('_TCLINK_POST')){
				
				$_Params = xPopData('_TCLINK_POST');
			}
			
			if(isset($_Params['BillingAddressID'])){
			
				$baddress = regGetAddress($_Params['BillingAddressID']);
			}else{
				$baddress = $_Params;
			}
			
			$_Params['tclink_country'] = cnGetCountryById($baddress['countryID']);
			$_Params['tclink_country'] = $_Params['tclink_country']['country_iso_2'];
			$_Params['tclink_state'] = znGetSingleZoneById($baddress['zoneID']);
			$_Params['tclink_state'] = $_Params['tclink_state']['zone_code'];
			
			$CurrYear2d = date('y');
			$CurrYear4d = date('Y');
			$ExpYears = '';
			for($_Y = 0; $_Y<10; $_Y++){
			
				$_Selected = isset($_Params['tclink_expyear'])?($_Params['tclink_expyear']==($CurrYear2d+$_Y)):0;
				$ExpYears .= '<option value="'.($CurrYear2d+$_Y).'"'.($_Selected?' selected="selected"':'').'>'.($CurrYear4d+$_Y).'</option>';
			}
			
			$ExpMonths = '';
			for($_M = 1; $_M<=12; $_M++){
			
				$_Selected = isset($_Params['tclink_expmonth'])?($_Params['tclink_expmonth']==($_M)):0;
				$ExpMonths .= '<option value="'.$_M.'"'.($_Selected?' selected="selected"':'').'>'.$rMonths[$_M].'</option>';
			}
			
			return '
			<input type="hidden" name="tclink_country" value="'.xHtmlSpecialChars($_Params['tclink_country']).'" />
			<input type="hidden" name="tclink_state" value="'.xHtmlSpecialChars($_Params['tclink_state']).'" />
			<table>
			<tr>
				<td>'.TCLINK_TXT_CCNUMBER.'</td>
				<td><input type="text" name="tclink_ccnumber" value="'.(isset($_Params['tclink_ccnumber'])?$_Params['tclink_ccnumber']:'').'" /></td>
			</tr>
			<tr>
				<td>'.TCLINK_TXT_CVV.'</td>
				<td><input type="text" name="tclink_cvv" value="'.(isset($_Params['tclink_cvv'])?$_Params['tclink_cvv']:'').'" /></td>
			</tr>
			<tr>
				<td>'.TCLINK_TXT_EXPDATE.'</td>
				<td><select name="tclink_expmonth">'.$ExpMonths.'</select>&nbsp;<select name="tclink_expyear">'.$ExpYears.'</select></td>
			</tr>
			</table>
			';
		}

		function payment_process($order){

			$transaction = array();
			
			$transaction['custid'] = $this->_getSettingValue('CONF_TCLINK_USERID');
			$transaction['password'] = $this->_getSettingValue('CONF_TCLINK_PWD');
			$transaction['action'] = $this->_getSettingValue('CONF_TCLINK_ACTION');
			if($this->_getSettingValue('CONF_TCLINK_DEMO')){
				
				$transaction['demo'] = 'y';
			}
			
			$transaction['cc'] = $_POST['tclink_ccnumber'];
			$transaction['cvv'] = $_POST['tclink_cvv'];
			$transaction['checkcvv'] = 'y';
			$transaction['exp'] = sprintf('%02d',$_POST['tclink_expmonth']).sprintf('%02d',$_POST['tclink_expyear']);

			$transaction['amount'] = RoundFloatValue($this->_convertCurrency($order['order_amount'], 0, $order['currency_code']))*100;
			$transaction['currency'] = strtolower($order['currency_code']);
			
			$transaction['name'] = $order['billing_info']['first_name'].' '.$order['billing_info']['last_name'];
			$transaction['address1'] = $order['billing_info']['address'];
			$transaction['city'] = $order['billing_info']['city'];
			$transaction['state'] = $_POST['tclink_state'];
			$transaction['zip'] = $order['billing_info']['zip'];
			$transaction['country'] = $_POST['tclink_country']=='US'?'':$_POST['tclink_country'];
			$transaction['email'] = $order['customer_email'];
			$transaction['ip'] = $_SERVER['REMOTE_ADDR'];

			$debug = false;
			
			if($debug){
				
				print '<pre>';
				print_r($transaction);
				print '</pre>';
			}
			
			$response = $this->_curlTransaction($transaction);
			/*
				if you would like to use TrustCommerce using TCLink API, please comment the line above and uncomment following line:

				$response = $this->_protectedTransaction($transaction);

				please note that you have to manually install TCLink extension. installation depends on your OS version. please contact TrustCommerce to learn more about this extension
			*/
			
			$return = '';
			
			switch ($response['status']){
				case 'approved':
					$return = 1;
					break;
				case 'decline':
					$return = TCLINK_TXT_TRANSACTION_DECLINED.$response['declinetype'];
					break;
				case 'baddata':
					$return = TCLINK_TXT_BADDATA.$response['offenders'];
					break;
				default:
					$return = TCLINK_TXT_ERROR.$response['errortype'];
					break;
			}
			
			if ($return!==1) {
				
				xSaveData('_TCLINK_POST', $_POST);
			}
			
			if($debug){
				print $return;
				die;
			}
				
			return $return;
		}

		function after_processing_php($_OrderID){
		
			if($this->_getSettingValue('CONF_TCLINK_ORDERSTATUS') != -1){
			
				ostSetOrderStatusToOrder($_OrderID, $this->_getSettingValue('CONF_TCLINK_ORDERSTATUS'));
			}
		}
	
		/**
		 * Need extra soft
		 *
		 * @param array $transaction
		 * @return array
		 */
		function _protectedTransaction($transaction){
			
			if(isWindows()){/* Windows version */
				
				$tclink = new COM("TCLinkCOM.TClink");
				
				reset($transaction);
				while (list($k,$v) = each($transaction)){
					
					$tclink->PushNameValue("$k=$v");
				}
				
				$tclink->Submit();
				
				$response['status'] = $tclink->GetResponse("status");
				$response['offenders'] = $tclink->GetResponse('offenders');
				$response['declinetype'] = $tclink->GetResponse('declinetype');
				$response['errortype'] = $tclink->GetResponse('errortype');
			}else{/* Linux version */
				
				if (!extension_loaded("tclink")){
					
					$extfname = dirname(__FILE__). "/tclink/tclink.so";
					if (!dl($extfname)) {
						
						return TCLINK_ERROR_EXTENSION_LOADING;
					}
				}
			
				$response = tclink_send($transaction);
			}
			
			return $response;
		}
		
		function _curlTransaction($transaction){
			
			$postdata = '';
			reset($transaction);
			while (list($k,$v) = each($transaction)){
				
				$postdata .= "&$k=".urlencode($v);
			}
			$postdata = substr($postdata, 1);

			$ch=curl_init();
			if(!$ch)return (sprintf('Error1 [%d]: %s',curl_errno($ch),curl_error($ch)));
			
			curl_setopt ($ch, CURLOPT_URL,'https://vault.trustcommerce.com/trans/');
			curl_setopt ($ch, CURLOPT_POST, 1); 
			curl_setopt ($ch, CURLOPT_POSTFIELDS, $postdata);
			curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
			initCurlProxySettings($ch);
			
			$http_response = curl_exec($ch);
			
			if(!$http_response)return (sprintf('Error2 [%d]: %s',curl_errno($ch),curl_error($ch)));
			curl_close($ch);
			
			$http_response = explode("\n", $http_response);
			
			$response = array();
			
			foreach ($http_response as $expl){
				
				$expl = explode("=", $expl);
				$response[trim($expl[0])] = isset($expl[1])?trim($expl[1]):'';
			}
			
			return $response;
		}
	}
?>