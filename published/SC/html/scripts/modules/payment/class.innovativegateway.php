<?php
/**
 * @connect_module_class_name InnovativeGateway
 * @package DynamicModules
 * @subpackage Payment
 */
	include_once(DIR_MODULES.'/payment/data.php');
	include_once(DIR_MODULES.'/payment/innovativegateway/PostGateway.function');
	
	class InnovativeGateway extends PaymentModule {

		var $type = PAYMTD_TYPE_CC;
		var $language = 'eng';
		
	
		function _initVars(){
			
			$this->title = INNOVATIVEGTW_TTL;
			$this->description = INNOVATIVEGTW_DSCR;
			$this->sort_order = 1;
			
			$this->Settings = array( 
					'CONF_PMNT_INNOVATIVEGTW_USERNAME',
					'CONF_PMNT_INNOVATIVEGTW_PWD',
					'CONF_PMNT_INNOVATIVEGTW_TRANTYPE',
					'CONF_PMNT_INNOVATIVEGTW_SHOPCUR',
					'CONF_PMNT_INNOVATIVEGTW_ORDERSTATUS',
				);
		}
	
		function _initSettingFields(){
	
			$this->SettingsFields['CONF_PMNT_INNOVATIVEGTW_USERNAME'] = array(
				'settings_value' 		=> '', 
				'settings_title' 			=> INNOVATIVEGTW_CFG_USERNAME_TTL, 
				'settings_description' 	=> INNOVATIVEGTW_CFG_USERNAME_DSCR, 
				'settings_html_function' 	=> 'setting_TEXT_BOX(0,', 
				'sort_order' 			=> 1,
			);
			$this->SettingsFields['CONF_PMNT_INNOVATIVEGTW_PWD'] = array(
				'settings_value' 		=> '', 
				'settings_title' 			=> INNOVATIVEGTW_CFG_PWD_TTL, 
				'settings_description' 	=> INNOVATIVEGTW_CFG_PWD_DSCR, 
				'settings_html_function' 	=> 'setting_TEXT_BOX(0,', 
				'sort_order' 			=> 1,
			);
			$this->SettingsFields['CONF_PMNT_INNOVATIVEGTW_TRANTYPE'] = array(
				'settings_value' 		=> 'preauth', 
				'settings_title' 			=> INNOVATIVEGTW_CFG_TRANTYPE_TTL, 
				'settings_description' 	=> INNOVATIVEGTW_CFG_TRANTYPE_DSCR, 
				'settings_html_function' 	=> 'setting_RADIOGROUP(INNOVATIVEGTW_TXT_PREAUTH.":preauth,".INNOVATIVEGTW_TXT_SALE.":sale",', 
				'sort_order' 			=> 1,
			);
			$this->SettingsFields['CONF_PMNT_INNOVATIVEGTW_ORDERSTATUS'] = array(
				'settings_value' 		=> '', 
				'settings_title' 			=> INNOVATIVEGTW_CFG_ORDERSTATUS_TTL, 
				'settings_description' 	=> INNOVATIVEGTW_CFG_ORDERSTATUS_DSCR, 
				'settings_html_function' 	=> 'setting_SELECT_BOX(PaymentModule::_getStatuses(),', 
				'sort_order' 			=> 1,
			);
			$this->SettingsFields['CONF_PMNT_INNOVATIVEGTW_SHOPCUR'] = array(
				'settings_value' 		=> '', 
				'settings_title' 			=> INNOVATIVEGTW_CFG_SHOPCUR_TTL, 
				'settings_description' 	=> INNOVATIVEGTW_CFG_SHOPCUR_DSCR, 
				'settings_html_function' 	=> 'setting_CURRENCY_SELECT(', 
				'sort_order' 			=> 1,
			);
		}

		function payment_form_html($_Params = null){
		
			global $rMonths;
			
			if(xDataExists('_INNOVATIVEGTW_POST')){
				
				$_Params = xPopData('_INNOVATIVEGTW_POST');
			}
			
			if(isset($_Params['BillingAddressID'])){
			
				$baddress = regGetAddress($_Params['BillingAddressID']);
			}else{
				$baddress = $_Params;
			}
			
			$_Params['innovativegtw_country'] = cnGetCountryById($baddress['countryID']);
			$_Params['innovativegtw_country'] = $_Params['innovativegtw_country']['country_iso_2'];
			$_Params['innovativegtw_state'] = znGetSingleZoneById($baddress['zoneID']);
			$_Params['innovativegtw_state'] = $_Params['innovativegtw_state']['zone_code'];
			
			$CurrYear2d = date('y');
			$CurrYear4d = date('Y');
			$ExpYears = '';
			for($_Y = 0; $_Y<10; $_Y++){
			
				$_Selected = isset($_Params['innovativegtw_expyear'])?($_Params['innovativegtw_expyear']==($CurrYear2d+$_Y)):0;
				$ExpYears .= '<option value="'.($CurrYear2d+$_Y).'"'.($_Selected?' selected="selected"':'').'>'.($CurrYear4d+$_Y).'</option>';
			}
			
			$ExpMonths = '';
			for($_M = 1; $_M<=12; $_M++){
			
				$_Selected = isset($_Params['innovativegtw_expmonth'])?($_Params['innovativegtw_expmonth']==($_M)):0;
				$ExpMonths .= '<option value="'.$_M.'"'.($_Selected?' selected="selected"':'').'>'.$rMonths[$_M].'</option>';
			}
			
			global $CardNames;
			/*
			visa, mc, amex, diners, discover, jcb
			*/
			$AvailableCreditCards = array(
				CCTYPE_VISA => 'visa',
				CCTYPE_MASTERCARD => 'mc',
				CCTYPE_AMEXPRESS => 'amex',
				CCTYPE_DINERS_CLUB => 'diners',
				CCTYPE_DISCOVER => 'discover',
				CCTYPE_JCB => 'jcb'
			);
			
			$ACreditCardsHTML = '<select name="innovativegtw_cctype">';
			foreach ($AvailableCreditCards as $CCTypeID=>$CCTypeCode){
				
				$_Selected = isset($_Params['innovativegtw_cctype'])?($_Params['innovativegtw_cctype']==$CCTypeCode?' selected':''):'';
				$ACreditCardsHTML .= '<option value="'.xHtmlSpecialChars($CCTypeCode).'"'.$_Selected.'>'.$CardNames[$CCTypeID].'</option>';
			}
			
			return '
			<input type="hidden" name="innovativegtw_country" value="'.xHtmlSpecialChars($_Params['innovativegtw_country']).'" />
			<input type="hidden" name="innovativegtw_state" value="'.xHtmlSpecialChars($_Params['innovativegtw_state']).'" />
			<table>
			<tr>
				<td>'.INNOVATIVEGTW_TXT_CCTYPE.'</td>
				<td>'.$ACreditCardsHTML.'</td>
			</tr>
			<tr>
				<td>'.INNOVATIVEGTW_TXT_CCNUMBER.'</td>
				<td><input type="text" name="innovativegtw_ccnumber" value="'.(isset($_Params['innovativegtw_ccnumber'])?$_Params['innovativegtw_ccnumber']:'').'" /></td>
			</tr>
			<tr>
				<td>'.INNOVATIVEGTW_TXT_CVV.'</td>
				<td><input type="text" name="innovativegtw_cvv" value="'.(isset($_Params['innovativegtw_cvv'])?$_Params['innovativegtw_cvv']:'').'" /></td>
			</tr>
			<tr>
				<td>'.INNOVATIVEGTW_TXT_EXPDATE.'</td>
				<td><select name="innovativegtw_expmonth">'.$ExpMonths.'</select>&nbsp;<select name="innovativegtw_expyear">'.$ExpYears.'</select></td>
			</tr>
			</table>
			';
		}

		function payment_process($order){
	
			$transaction = array();
			
			// Required variables for authorization gateway
			$transaction["target_app"] = "WebCharge_v5.06";
			$transaction["response_mode"] = "simple";
			$transaction["response_fmt"] = "delimited";
			$transaction["upg_auth"] = "zxcvlkjh";
			$transaction["delimited_fmt_field_delimiter"] = "=";
			$transaction["delimited_fmt_include_fields"] = "true";
		
			$transaction["delimited_fmt_value_delimiter"] = "|";
		
			// Your Gateway Authorization Credentials:
			$transaction["username"] = $this->_getSettingValue('CONF_PMNT_INNOVATIVEGTW_USERNAME');
			$transaction["pw"] = $this->_getSettingValue('CONF_PMNT_INNOVATIVEGTW_PWD');
		
      $transaction["trantype"] = $this->_getSettingValue('CONF_PMNT_INNOVATIVEGTW_TRANTYPE');
      $transaction["reference"] = "";
      $transaction["trans_id"] = "";
      $transaction["authamount"] = "";

			// Credit Card information
      $transaction["cardtype"] = $_POST['innovativegtw_cctype'];
      $transaction["ccnumber"] = $_POST['innovativegtw_ccnumber']; 
      $transaction["ccidentifier1"] = $_POST['innovativegtw_cvv']; 
			// CC# may include spaces or dashes.
      $transaction["month"] = sprintf('%02d',$_POST['innovativegtw_expmonth']); // Must be TWO DIGIT month.
      $transaction["year"] =  sprintf('%02d',$_POST['innovativegtw_expyear']); // Must be TWO or FOUR DIGIT year.

      $transaction["fulltotal"] = sprintf('%.2f',RoundFloatValue($this->_convertCurrency($order["order_amount"], 0, $this->_getSettingValue('CONF_PMNT_INNOVATIVEGTW_SHOPCUR'))));

      $transaction["ccname"] = $order['billing_info']['first_name'].' '.$order['billing_info']['last_name'];
			$transaction["baddress"] = $order['billing_info']['address'];
			$transaction["baddress1"] = "";
			$transaction["bcity"] = $order['billing_info']['city'];
			$transaction["bstate"] = $_POST['innovativegtw_state'];
			$transaction["bzip"] = $order['billing_info']['zip'];
			$transaction["bcountry"] = $_POST['innovativegtw_country']; // TWO DIGIT COUNTRY (United States = "US")
			$transaction["bphone"] = '';
			$transaction["email"] = $order['customer_email'];

      $response = PostTransaction($transaction);
		
			if ($response["approved"] != ""){
				
				return 1;
			} else {
				
				xSaveData('_INNOVATIVEGTW_POST', $_POST);
				return $response["error"];
			}
		}

		function after_processing_php($_OrderID){
		
			if($this->_getSettingValue('CONF_PMNT_INNOVATIVEGTW_ORDERSTATUS') != -1){
			
				ostSetOrderStatusToOrder($_OrderID, $this->_getSettingValue('CONF_PMNT_INNOVATIVEGTW_ORDERSTATUS'));
			}
		}
	}
?>