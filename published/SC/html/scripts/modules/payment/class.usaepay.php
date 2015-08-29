<?php
	/**
	 * @connect_module_class_name USAePay
	 * @package DynamicModules
	 * @subpackage Payment
	 */
	class USAePay extends PaymentModule {

		var $type = PAYMTD_TYPE_CC;
		var $language = 'eng';
		
		function _initVars(){
			
			parent::_initVars();
			$this->title = USAEPAY_TTL;
			$this->description = USAEPAY_DSCR;
			
			$this->Settings = array( 
					'CONF_USAEPAY_TESTMODE',
					'CONF_USAEPAY_SOURCEKEY',
					'CONF_USAEPAY_USD',
					'CONF_USAEPAY_TRANSMODE',
					'CONF_USAEPAY_ORDERSTATUS',
				);
		}
	
		function _initSettingFields(){
	
			$this->SettingsFields['CONF_USAEPAY_ORDERSTATUS'] = array(
				'settings_value' 		=> '', 
				'settings_title' 			=> USAEPAY_CFG_ORDERSTATUS_TTL,
				'settings_description' 	=> USAEPAY_CFG_ORDERSTATUS_DSCR,
				'settings_html_function' 	=> 'setting_SELECT_BOX(PaymentModule::_getStatuses(),', 
			);
			$this->SettingsFields['CONF_USAEPAY_SOURCEKEY'] = array(
				'settings_value' 		=> '', 
				'settings_title' 			=> USAEPAY_CFG_SOURCEKEY_TTL,
				'settings_description' 	=> USAEPAY_CFG_SOURCEKEY_DSCR,
				'settings_html_function' 	=> 'setting_TEXT_BOX(0,', 
			);
			$this->SettingsFields['CONF_USAEPAY_USD'] = array(
				'settings_value' 		=> '', 
				'settings_title' 			=> USAEPAY_CFG_USD_TTL,
				'settings_description' 	=> USAEPAY_CFG_USD_DSCR,
				'settings_html_function' 	=> 'setting_CURRENCY_SELECT(', 
			);
			$this->SettingsFields['CONF_USAEPAY_TRANSMODE'] = array(
				'settings_value' 		=> '', 
				'settings_title' 			=> USAEPAY_CFG_TRANSMODE_TTL,
				'settings_description' 	=> USAEPAY_CFG_TRANSMODE_DSCR,
				'settings_html_function' 	=> 'setting_SELECT_BOX(USAePay::_getTransModeOptions(),', 
			);
			$this->SettingsFields['CONF_USAEPAY_TESTMODE'] = array(
				'settings_value' 		=> '', 
				'settings_title' 			=> USAEPAY_CFG_TESTMODE_TTL,
				'settings_description' 	=> USAEPAY_CFG_TESTMODE_DSCR,
				'settings_html_function' 	=> 'setting_CHECK_BOX(', 
			);
		}
	
		function payment_form_html($_Params = null){
		
			if(xDataExists('_USAEPAY')){
				
				$_Params = xPopData('_USAEPAY');
			}
			
			$res = '';
       
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
				<table>
				<tr>
					<td align="right">'.USAEPAY_TXT_CCNUMBER.'</td>
					<td>
						<input type="text" name="ccnumber" value="'.xHtmlSpecialChars(isset($_Params['ccnumber'])?$_Params['ccnumber']:'').'" />
					</td>
				</tr>
				<tr>
					<td align="right">'.USAEPAY_TXT_CVV.'</td>
					<td>
						<input type="text" name="cvv2" value="'.xHtmlSpecialChars(isset($_Params['cvv2'])?$_Params['cvv2']:'').'" />
					</td>
				</tr>
				<tr>
					<td align="right">'.USAEPAY_TXT_EXPDATE.'</td>
					<td>
					<select name="month">'.$ExpMonths.'</select>&nbsp;<select name="year">'.$ExpYears.'</select>
					</td>
				</tr>
				</table>
				';
			
			return $res;
		}

		function payment_process($order){

			xSaveData('_USAEPAY', $_POST);
			
			include_once(dirname(__FILE__).'/usaepay/usaepay.php');
			
			$tran=new umTransaction;
			
			$tran->key = $this->_getSettingValue('CONF_USAEPAY_SOURCEKEY');
			$tran->ip = $_SERVER['REMOTE_ADDR'];
			
			if($this->_getSettingValue('CONF_USAEPAY_TESTMODE')){
				
				$tran->testmode=1;
			}
			
			$tran->command = $this->_getSettingValue('CONF_USAEPAY_TRANSMODE');
			
			$tran->card = str_replace(array('-',' '),'', $_POST['ccnumber']);
			$tran->exp = sprintf('%02d%02d', $_POST['month'], $_POST['year']);
			$tran->amount = sprintf('%.2f',RoundFloatValue($this->_convertCurrency($order['order_amount'],0, $this->_getSettingValue('CONF_USAEPAY_USD'))));
			$tran->invoice = md5(time());
			$tran->cardholder = $order['billing_info']['first_name'].' '.$order['billing_info']['last_name'];
			$tran->street = $order['billing_info']['address'];
			$tran->zip = $order['billing_info']['zip'];
			$tran->description = USAEPAY_TXT_ONLINEOREDER;
			$tran->cvv2 = $_POST['cvv2'];
			
			$tran->ignoresslcerterrors = true;
			
			if($tran->Process()){
				
				return 1;
			} else {
				$res = 
					"<b>Card Declined</b> (" . $tran->result . ")<br>".
					"<b>Reason:</b> " . $tran->error . "<br>";	
				if($tran->curlerror) $res .= "<b>Curl Error:</b> " . $tran->curlerror . "<br>";
				
				return $res;
			}	
		}
	
		function after_processing_php($_OrderID){
		
			if($this->_getSettingValue('CONF_USAEPAY_ORDERSTATUS') != -1){
			
				ostSetOrderStatusToOrder($_OrderID, $this->_getSettingValue('CONF_USAEPAY_ORDERSTATUS'));
			}
		}
		
		function _getTransModeOptions(){
			
			$options = array(
				array(
					'title' => USAEPAY_TXT_AUTH,
					'value' => 'authonly'
					),
				array(
					'title' => USAEPAY_TXT_SALE,
					'value' => 'sale'
					),
				);
			return $options;
		}
	}
?>