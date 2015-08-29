<?php
/**
 * @connect_module_class_name SkipJackDC
 * @package DynamicModules
 * @subpackage Payment
 */
	class SkipJackDC extends PaymentModule {

		var $type = PAYMTD_TYPE_CC;
		var $language = 'eng';
		var $default_logo = 'http://www.webasyst.net/collections/design/payment-icons/skipjack.gif';
		
		function _initVars(){
			
			parent::_initVars();
			$this->title = SKIPJACKDC_TTL;
			$this->description = SKIPJACKDC_DSCR;
			$this->sort_order = 1;
			
			$this->Settings = array( 
					'CONF_SKIPJACKDC_URL',
					'CONF_SKIPJACKDC_SERIAL',
					'CONF_SKIPJACKDC_ASKCVV',
					'CONF_SKIPJACKDC_ORDERSTATUS',
					'CONF_SKIPJACKDC_USD',
				);
		}
	
		function _initSettingFields(){
	
			$this->SettingsFields['CONF_SKIPJACKDC_URL'] = array(
//				'settings_value' 		=> 'https://developer.skipjackic.com/scripts/evolvcc.dll?AuthorizeAPI', 
				'settings_value' 		=> 'https://www.skipjackic.com/scripts/evolvcc.dll?AuthorizeAPI', 
				'settings_title' 			=> SKIPJACKDC_CFG_URL_TTL,
				'settings_description' 	=> SKIPJACKDC_CFG_URL_DSCR,
				'settings_html_function' 	=> 'setting_TEXT_BOX(0,', 
				'sort_order' 			=> 1,
			);
			$this->SettingsFields['CONF_SKIPJACKDC_SERIAL'] = array(
				'settings_value' 		=> '', 
				'settings_title' 			=> SKIPJACKDC_CFG_SERIAL_TTL, 
				'settings_description' 	=> SKIPJACKDC_CFG_SERIAL_DSCR, 
				'settings_html_function' 	=> 'setting_TEXT_BOX(0,', 
				'sort_order' 			=> 1,
			);
			$this->SettingsFields['CONF_SKIPJACKDC_ASKCVV'] = array(
				'settings_value' 		=> '', 
				'settings_title' 			=> SKIPJACKDC_CFG_ASKCVV_TTL, 
				'settings_description' 	=> SKIPJACKDC_CFG_ASKCVV_DSCR, 
				'settings_html_function' 	=> 'setting_CHECK_BOX(', 
				'sort_order' 			=> 1,
			);
			$this->SettingsFields['CONF_SKIPJACKDC_ORDERSTATUS'] = array(
				'settings_value' 		=> '-1', 
				'settings_title' 			=> SKIPJACKDC_CFG_ORDERSTATUS_TTL, 
				'settings_description' 	=> SKIPJACKDC_CFG_ORDERSTATUS_DSCR, 
				'settings_html_function' 	=> 'setting_SELECT_BOX(PaymentModule::_getStatuses(),', 
				'sort_order' 			=> 1,
			);
			$this->SettingsFields['CONF_SKIPJACKDC_USD'] = array(
				'settings_value' 		=> '', 
				'settings_title' 			=> SKIPJACKDC_CFG_USD_TTL, 
				'settings_description' 	=> SKIPJACKDC_CFG_USD_DSCR, 
				'settings_html_function' 	=> 'setting_CURRENCY_SELECT(', 
				'sort_order' 			=> 1,
			);
		}
	
		function payment_form_html($_Params = null){
		
			if(isset($_Params['BillingAddressID'])){
			
				$_Params = regGetAddress($_Params['BillingAddressID']);
			}
			
			if(xDataExists('_SKIPJACKDC')){
				
				$_tParams = xPopData('_SKIPJACKDC');
				$_Params['accountnumber'] = $_tParams['accountnumber'];
				if($this->_getSettingValue('CONF_SKIPJACKDC_ASKCVV')){
					
					$_Params['cvv2'] = $_tParams['cvv2'];
				}
				$_Params['month'] = $_tParams['month'];
				$_Params['year'] = $_tParams['year'];
			}
			
			$res = '';
			
			$post_1=array();
			
      $hidden_fields_html = '';
      reset($post_1);
      
      while(list($k,$v)=each($post_1)){
      	
				$hidden_fields_html .= '<input type="hidden" name="'.xHtmlSpecialChars($k).'" value="'.xHtmlSpecialChars($v).'" />'."\n";
      }
       
			$CurrYear2d = date('y');
			$CurrYear4d = date('Y');
			$ExpYears = '';
			for($_Y = 0; $_Y<10; $_Y++){
			
				$_Selected = isset($_Params['year'])?($_Params['year']==($CurrYear2d+$_Y)):0;
				$ExpYears .= '<option value="'.($CurrYear2d+$_Y).'"'.($_Selected?' selected="selected"':'').'>'.($CurrYear4d+$_Y).'</option>';
			}
			
			global $rMonths;
			$ExpMonths = '';
			for($_M = 1; $_M<=12; $_M++){
			
				$_Selected = isset($_Params['month'])?($_Params['month']==$_M):0;
				$ExpMonths .= '<option value="'.$_M.'"'.($_Selected?' selected="selected"':'').'>'.$rMonths[$_M].'</option>';
			}
			
			$res = '
				'.$hidden_fields_html.'
				<table>
				<tr>
					<td align="right">'.SKIPJACKDC_TXT_CCNUMBER.'</td>
					<td>
						<input type="text" name="accountnumber" value="'.xHtmlSpecialChars(isset($_Params['accountnumber'])?$_Params['accountnumber']:'').'" />
					</td>
				</tr>
				'.($this->_getSettingValue('CONF_SKIPJACKDC_ASKCVV')?'
				<tr>
					<td align="right">'.SKIPJACKDC_TXT_CVV.'</td>
					<td>
						<input type="text" name="cvv2" value="'.xHtmlSpecialChars(isset($_Params['cvv2'])?$_Params['cvv2']:'').'" />
					</td>
				</tr>
				':'').'
				<tr>
					<td align="right">'.SKIPJACKDC_TXT_EXPDATE.'</td>
					<td>
					<select name="month">'.$ExpMonths.'</select>&nbsp;<select name="year">'.$ExpYears.'</select>
					</td>
				</tr>
				</table>
				';
			return $res;
		}

		function payment_process($order){

			$order_amount = RoundFloatValue($this->_convertCurrency($order['order_amount'],0, $this->_getSettingValue('CONF_SKIPJACKDC_USD')));

			$country = cnGetCountryById($order['billing_info']['countryID']);
			$country = $country['country_name'];
			
			$state = znGetSingleZoneById($order['billing_info']['zoneID']);
			$state = $state['zone_name'];

			$shipping_country = cnGetCountryById($order['shipping_info']['countryID']);
			$shipping_country = $shipping_country['country_name'];
			
			$shipping_state = znGetSingleZoneById($order['shipping_info']['zoneID']);
			$shipping_state = $shipping_state['zone_name'];
			
			$post_1=array(
				
				'sjname' => substr($order['billing_info']['first_name'].' '.$order['billing_info']['last_name'],0,40),
				'email' => substr($order['customer_email'],0,40),
				'streetaddress' => substr($order['billing_info']['address'],0,40),
				'city' => substr($order['billing_info']['city'],0,40),
				'state' => substr($state,0,40),
				'zipcode' => substr($order['billing_info']['zip'],0,9),
				'country' => substr($country,0,40),

				'shiptostreetaddress' => substr($order['shipping_info']['address'],0,40),
				'shiptocity' => substr($order['shipping_info']['city'],0,40),
				'shiptostate' => substr($shipping_state,0,40),
				'shiptozipcode' => substr($order['shipping_info']['zip'],0,9),
				'shiptocountry' => substr($shipping_country,0,40),
				'shiptophone' => '1111111111111',

				'ordernumber' => 'no',
				'orderstring' => '0~0~0.00~0~N~||',
				'transactionamount' => sprintf('%.2f',$order_amount),
			
				'accountnumber' => $_POST['accountnumber'],
				'month' => sprintf('%02d', $_POST['month']),
				'year' => sprintf('%02d', $_POST['year']),
				
				'serialnumber' => $this->_getSettingValue('CONF_SKIPJACKDC_SERIAL'),
			);

			if($this->_getSettingValue('CONF_SKIPJACKDC_ASKCVV')){
				
				$post_1['cvv2'] = $_POST['cvv2'];
			}
			
			$postdata = '';
			reset($post_1);
			while (list($k,$v) = each($post_1)){
				
				$postdata .= "&$k=".urlencode($v);
			}
			$postdata = substr($postdata, 1);

			$ch=curl_init();
			if(!$ch)return (sprintf('Error1 [%d]: %s',curl_errno($ch),curl_error($ch)));
			
			curl_setopt ($ch, CURLOPT_URL,$this->_getSettingValue('CONF_SKIPJACKDC_URL'));
			curl_setopt ($ch, CURLOPT_POST, 1); 
			curl_setopt ($ch, CURLOPT_POSTFIELDS, $postdata);
			curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
			initCurlProxySettings($ch);
			
			$http_response = curl_exec($ch);
			
			if(!$http_response)return (sprintf('Error2 [%d]: %s',curl_errno($ch),curl_error($ch)));
			curl_close($ch);
			
			$response = array();
			$explode = explode("\n",$http_response);
			$explode[0] = substr(trim($explode[0]),1);
			$explode[0] = substr($explode[0],0, strlen($explode[0])-1);
			$explode[1] = substr(trim($explode[1]),1);
			$explode[1] = substr($explode[1],0, strlen($explode[1])-1);
			$r_var = explode('","',$explode[0]);
			$r_val = explode('","',$explode[1]);
			foreach ($r_var as $i=>$var){
				
				$response[$var] = trim($r_val[$i]);
			}

			if($response['szReturnCode']==1 && $response['szIsApproved']){
				
				return 1;
			}else{
				
				xSaveData('_SKIPJACKDC', $_POST);
				
				if($response['szReturnCode']==1){
					
					return $response['szAuthorizationDeclinedMessage'];
				}else{
					
					global $SKIPJACKDC_responsetexts;
					return $SKIPJACKDC_responsetexts[$response['szReturnCode']];
				}
			}
		}
	
		function after_processing_php($_OrderID){
		
			if($this->_getSettingValue('CONF_SKIPJACKDC_ORDERSTATUS') != -1){
			
				ostSetOrderStatusToOrder($_OrderID, $this->_getSettingValue('CONF_SKIPJACKDC_ORDERSTATUS'));
			}
		}
	}
?>