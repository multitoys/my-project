<?php
/**
 * @connect_module_class_name PayflowPro
 * @package DynamicModules
 * @subpackage Payment
 */
	class PayflowPro extends PaymentModule {

		var $type = PAYMTD_TYPE_CC;
		var $default_logo = 'http://www.webasyst.net/collections/design/payment-icons/paypal.gif';
		
		function _initVars(){
			
			parent::_initVars();
			$this->title = PAYFLOWPRO_TTL;
			$this->description = PAYFLOWPRO_DSCR;
			
			$this->Settings = array(
					'CONF_PAYFLOWPRO_TESTMODE',
					'CONF_PAYFLOWPRO_PARTNER',
					'CONF_PAYFLOWPRO_VENDOR',
					'CONF_PAYFLOWPRO_USER',
					'CONF_PAYFLOWPRO_PWD',
					'CONF_PAYFLOWPRO_TRANSTYPE',
					'CONF_PAYFLOWPRO_TRANSCURRENCY',
					'CONF_PAYFLOWPRO_SUCCESS_ORDERSTATUS',
				);
		}
	
		function _initSettingFields(){
	
			$this->SettingsFields['CONF_PAYFLOWPRO_TESTMODE'] = array(
				'settings_value' 		=> '', 
				'settings_title' 			=> PAYFLOWPRO_CFG_TESTMODE_TTL,
				'settings_description' 	=> PAYFLOWPRO_CFG_TESTMODE_DSCR,
				'settings_html_function' 	=> 'setting_CHECK_BOX(', 
			);
			$this->SettingsFields['CONF_PAYFLOWPRO_PARTNER'] = array(
				'settings_value' 		=> '', 
				'settings_title' 			=> PAYFLOWPRO_CFG_PARTNER_TTL,
				'settings_description' 	=> PAYFLOWPRO_CFG_PARTNER_DSCR,
				'settings_html_function' 	=> 'setting_TEXT_BOX(0,', 
			);
			$this->SettingsFields['CONF_PAYFLOWPRO_PWD'] = array(
				'settings_value' 		=> '', 
				'settings_title' 			=> PAYFLOWPRO_CFG_PWD_TTL,
				'settings_description' 	=> PAYFLOWPRO_CFG_PWD_DSCR,
				'settings_html_function' 	=> 'setting_TEXT_BOX(0,', 
			);
			$this->SettingsFields['CONF_PAYFLOWPRO_TRANSTYPE'] = array(
				'settings_value' 		=> 'S', 
				'settings_title' 			=> PAYFLOWPRO_CFG_TRANSTYPE_TTL,
				'settings_description' 	=> PAYFLOWPRO_CFG_TRANSTYPE_DSCR,
				'settings_html_function' 	=> 'setting_SELECT_BOX(PayflowPro::_optTransactionTypes(),', 
			);
			$this->SettingsFields['CONF_PAYFLOWPRO_USER'] = array(
				'settings_value' 		=> '', 
				'settings_title' 			=> PAYFLOWPRO_CFG_USER_TTL,
				'settings_description' 	=> PAYFLOWPRO_CFG_USER_DSCR,
				'settings_html_function' 	=> 'setting_TEXT_BOX(0,', 
			);
			$this->SettingsFields['CONF_PAYFLOWPRO_VENDOR'] = array(
				'settings_value' 		=> '', 
				'settings_title' 			=> PAYFLOWPRO_CFG_VENDOR_TTL,
				'settings_description' 	=> PAYFLOWPRO_CFG_VENDOR_DSCR,
				'settings_html_function' 	=> 'setting_TEXT_BOX(0,', 
			);
			$this->SettingsFields['CONF_PAYFLOWPRO_TRANSCURRENCY'] = array(
				'settings_value' 		=> '', 
				'settings_title' 			=> PAYFLOWPRO_CFG_TRANSCURRENCY_TTL,
				'settings_description' 	=> PAYFLOWPRO_CFG_TRANSCURRENCY_DSCR,
				'settings_html_function' 	=> 'PayflowPro::_settingCurrencySelect(', 
			);
			$this->SettingsFields['CONF_PAYFLOWPRO_SUCCESS_ORDERSTATUS'] = array(
				'settings_value' 		=> '', 
				'settings_title' 			=> PAYFLOWPRO_CFG_SUCCESS_ORDERSTATUS_TTL,
				'settings_description' 	=> PAYFLOWPRO_CFG_SUCCESS_ORDERSTATUS_DSCR,
				'settings_html_function' 	=> 'setting_ORDERSTATUS_SELECT(PayflowPro::_optOrderStatuses(),', 
			);
		}
		
		function payment_process($order){
			
			if($this->_getSettingValue('CONF_PAYFLOWPRO_TRANSCURRENCY')){
				
				$currency = currGetCurrencyByID($this->_getSettingValue('CONF_PAYFLOWPRO_TRANSCURRENCY'));
				$currency = $currency['currency_iso_3'];
				$order_amount = RoundFloatValue($this->_convertCurrency($order['order_amount'], 0, $currency));
			}else{
				
				$currency = $order['currency_code'];
				$order_amount = RoundFloatValue($order['order_amount']*$order['currency_value']);
			}
			
//			$HOSTADDRESS = $this->_getSettingValue('CONF_PAYFLOWPRO_TESTMODE')?'test-payflow.PayPal.com':'payflow.PayPal.com';
			$HOSTADDRESS = $this->_getSettingValue('CONF_PAYFLOWPRO_TESTMODE')?'test-payflow.verisign.com':'payflow.verisign.com';
			$HOSTPORT = 443;
			$TIMEOUT = 20;
			$PARMLIST = array(
				/*array(<param name>,<param value>, <param max length>) */
				array('USER', $this->_getSettingValue('CONF_PAYFLOWPRO_USER')?$this->_getSettingValue('CONF_PAYFLOWPRO_USER'):$this->_getSettingValue('CONF_PAYFLOWPRO_VENDOR'),64),
				array('VENDOR', $this->_getSettingValue('CONF_PAYFLOWPRO_VENDOR'),64),
				array('PARTNER', $this->_getSettingValue('CONF_PAYFLOWPRO_PARTNER'),12),
				array('PWD', $this->_getSettingValue('CONF_PAYFLOWPRO_PWD'),12),
				
				array('TENDER','C'),
				array('TRXTYPE',$this->_getSettingValue('CONF_PAYFLOWPRO_TRANSTYPE')),
				
				/*CC number*/
				array('ACCT', preg_replace('/[^\d]*/','',$_POST['accountnumber']),19),
				/*Expiration date of the credit card in mmyy*/
				array('EXPDATE', sprintf('%02d%02d',$_POST['month'],$_POST['year']), 4),
				array('CVV2', $_POST['cvv2']),
				/*Order amount*/
				array('AMT',$order_amount),
				/* USD (US dollar), EUR (Euro), GBP (UK pound), CAD (Canadian dollar), JPY (Japanese Yen), AUD (Australian dollar) */
				array('CURRENCY',$currency),
				
				/* Card holder info */
				array('NAME', $order['billing_info']['first_name'].' '.$order['billing_info']['last_name'], 30),
				array('STREET', $order['billing_info']['address'], 30),
				array('ZIP', preg_replace('/[^a-z\d]*/msi','',$order['billing_info']['zip']), 9)
			);
						
			$result = $this->process($HOSTADDRESS, $HOSTPORT, $PARMLIST, $TIMEOUT);
			if(!is_array($result)||!isset($result['RESULT'])||!array_key_exists('RESULT',$result)){
				
				xSaveData('_PAYFLOW_PRO', $_POST);
				$this->_log(LOGTYPE_ERROR, PAYFLOWPRO_TXT_NORES);
				return PAYFLOWPRO_TXT_NORES;
			}
			if($result['RESULT']!=0){
				
				xSaveData('_PAYFLOW_PRO', $_POST);
				$error_msg = PAYFLOWPRO_TXT_ERRORPROCESSING.' '.PAYFLOWPRO_TXT_RESCODE.': '.$result['RESULT'].'.'.(isset($result['RESPMSG'])&&$result['RESPMSG']?(' '.PAYFLOWPRO_TXT_RESMSG.': '.$result['RESPMSG'].'.'):'');
				$this->_log(LOGTYPE_ERROR, $error_msg);
				return $error_msg;
			}
			
			return 1;
		}
		
		function _optTransactionTypes(){
			
			return array(
				array(
					'title' => PAYFLOWPRO_TXT_SALE,
					'value' => 'S'
				),
				array(
					'title' => PAYFLOWPRO_TXT_AUTH,
					'value' => 'A'
				),
			);
		}
		
	
		function payment_form_html(){
		
			if(xDataExists('_PAYFLOW_PRO')){
				
				$_Params = xPopData('_PAYFLOW_PRO');
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
					<td align="right">'.PAYFLOWPRO_TXT_CCNUMBER.'</td>
					<td>
						<input type="text" name="accountnumber" value="'.xHtmlSpecialChars(isset($_Params['accountnumber'])?$_Params['accountnumber']:'').'" />
					</td>
				</tr>
				<tr>
					<td align="right">'.PAYFLOWPRO_TXT_CVV2.'</td>
					<td>
						<input type="text" name="cvv2" value="'.xHtmlSpecialChars(isset($_Params['cvv2'])?$_Params['cvv2']:'').'" />
					</td>
				</tr>
				<tr>
					<td align="right">'.PAYFLOWPRO_TXT_EXPDATE.'</td>
					<td>
					<select name="month">'.$ExpMonths.'</select>&nbsp;<select name="year">'.$ExpYears.'</select>
					</td>
				</tr>
				</table>
				';
			return $res;
		}
		
		function _settingCurrencySelect($setting_id){
			
			return setting_CURRENCY_SELECT(array(array('title'=>PAYFLOWPRO_TXT_CDCURRENCY, 'value'=>0,)), $setting_id);
		}
		
		function process($HOSTADDRESS, $HOSTPORT, $PARMLIST, $TIMEOUT){
			
			/*Bin processing*/
			
			$PARMLIST = $this->_prepareBinPARMLIST($PARMLIST);
			
			$bin_path = dirname(__FILE__).'/payflow_pro';
			if(isWindows()){
				
				$bin_file = 'pfpro.exe';
			}else{
				
				putenv("LD_LIBRARY_PATH={$bin_path}:{$original_ld_path}".getenv("LD_LIBRARY_PATH"));
				/*Linux*/
				$bin_file = 'pfpro';
			}
			
			$bin_file = realpath($bin_path.'/'.$bin_file);
			
			$this->_log(LOGTYPE_MSG, 'Trying process transaction by cmd: '."{$bin_file} {$HOSTADDRESS} {$HOSTPORT} {$PARMLIST} {$TIMEOUT}");
			$cmd = "{$bin_file} {$HOSTADDRESS} {$HOSTPORT} {$PARMLIST} {$TIMEOUT}";
			
			$curr_path = getcwd();
			chdir($bin_path);
			$result = exec($cmd);
			chdir($curr_path);
			
			$this->_log(LOGMODE_MSG, 'Transaction result: '.$result);

			return $this->_parseBinResult($result);
		}
		
		/**
		 * Parse raw result from bin program
		 *
		 * @param string $result
		 * @return array
		 */
		function _parseBinResult($result){
			
			$result = explode('&',$result);
			$parsed_result = array();
			foreach ($result as $param_value){
				
				$param_value = explode('=',$param_value,2);
				$parsed_result[$param_value[0]] = $param_value[1];
			}
			return $parsed_result;
		}
		
		function _prepareBinPARMLIST($params){
			
			$PARMLIST = '';
			foreach ($params as $param){
				/* @var $param array(<param name>,<param value>, <param max length>) */
				$param[1] = isset($param[2])?substr($param[1],0,$param[2]):$param[1];
				$PARMLIST .= "&{$param[0]}[".strlen($param[1])."]={$param[1]}";
			}
			$PARMLIST = substr($PARMLIST,1);
			return '"'.$PARMLIST.'"';
		}
		
		function _optOrderStatuses(){
			
			return array(
				array("title"=>PAYFLOWPRO_TXT_DONTCHANGE,"value"=>-1)
				);
		}
		
		function after_processing_php($order_id){
		
			if($this->_getSettingValue('CONF_PAYFLOWPRO_SUCCESS_ORDERSTATUS') != -1){
			
				ostSetOrderStatusToOrder($order_id, $this->_getSettingValue('CONF_PAYFLOWPRO_SUCCESS_ORDERSTATUS'));
			}
		}
	}
?>