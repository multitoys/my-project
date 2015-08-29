<?php
/**
 * @connect_module_class_name Paymentech
 * @package DynamicModules
 * @subpackage Payment
 */

class Paymentech extends PaymentModule {

	var $type = PAYMTD_TYPE_CC;
	var $language = 'eng';
	var $default_logo = 'http://www.webasyst.net/collections/design/payment-icons/chase.gif';
	
	function _initVars(){
		
		parent::_initVars();
		$this->title = PAYMENTECH_TTL;
		$this->description = PAYMENTECH_DSCR;
//		$this->log_mode = LOGTYPE_DEBUG;
		$this->log_mode = LOGTYPE_ERROR;
		
		$this->Settings = array( 
				'CONF_PAYMENTECH_TESTMODE',
				'CONF_PAYMENTECH_MERCHANTID',
				'CONF_PAYMENTECH_MESSAGETYPE',
				'CONF_PAYMENTECH_TZCODE',
				'CONF_PAYMENTECH_CURRISO',
				'CONF_PAYMENTECH_CURREXP',
				'CONF_PAYMENTECH_CURRSHOP',
				'CONF_PAYMENTECH_ORDERSTATUS',
				'CONF_PAYMENTECH_PLATFORM'
			);
	}

	function _initSettingFields(){

		$this->SettingsFields['CONF_PAYMENTECH_ORDERSTATUS'] = array(
			'settings_value' 		=> '', 
			'settings_title' 			=> PAYMENTECH_CFG_ORDERSTATUS_TTL, 
			'settings_description' 	=> PAYMENTECH_CFG_ORDERSTATUS_DSCR, 
			'settings_html_function' 	=> 'setting_SELECT_BOX(PaymentModule::_getStatuses(),', 
		);
		$this->SettingsFields['CONF_PAYMENTECH_MESSAGETYPE'] = array(
			'settings_value' 		=> '', 
			'settings_title' 			=> PAYMENTECH_CFG_MESSAGETYPE_TTL, 
			'settings_description' 	=> PAYMENTECH_CFG_MESSAGETYPE_DSCR, 
			'settings_html_function' 	=> 'setting_SELECT_BOX(Paymentech::_getTransactionTypeOptions(),', 
		);
		$this->SettingsFields['CONF_PAYMENTECH_TZCODE'] = array(
			'settings_value' 		=> '', 
			'settings_title' 			=> PAYMENTECH_CFG_TZCODE_TTL, 
			'settings_description' 	=> PAYMENTECH_CFG_TZCODE_DSCR, 
			'settings_html_function' 	=> 'setting_TEXT_BOX(0,', 
		);
		$this->SettingsFields['CONF_PAYMENTECH_MERCHANTID'] = array(
			'settings_value' 		=> '', 
			'settings_title' 			=> PAYMENTECH_CFG_MERCHANTID_TTL, 
			'settings_description' 	=> PAYMENTECH_CFG_MERCHANTID_DSCR, 
			'settings_html_function' 	=> 'setting_TEXT_BOX(0,', 
		);
		$this->SettingsFields['CONF_PAYMENTECH_CURRISO'] = array(
			'settings_value' 		=> '840', 
			'settings_title' 			=> PAYMENTECH_CFG_CURRISO_TTL, 
			'settings_description' 	=> PAYMENTECH_CFG_CURRISO_DSCR, 
			'settings_html_function' 	=> 'setting_TEXT_BOX(0,', 
		);
		$this->SettingsFields['CONF_PAYMENTECH_CURREXP'] = array(
			'settings_value' 		=> '2', 
			'settings_title' 			=> PAYMENTECH_CFG_CURREXP_TTL, 
			'settings_description' 	=> PAYMENTECH_CFG_CURREXP_DSCR, 
			'settings_html_function' 	=> 'setting_TEXT_BOX(0,', 
		);
		$this->SettingsFields['CONF_PAYMENTECH_TESTMODE'] = array(
			'settings_value' 		=> '1', 
			'settings_title' 			=> PAYMENTECH_CFG_TESTMODE_TTL, 
			'settings_description' 	=> PAYMENTECH_CFG_TESTMODE_DSCR, 
			'settings_html_function' 	=> 'setting_CHECK_BOX(', 
		);
		$this->SettingsFields['CONF_PAYMENTECH_CURRSHOP'] = array(
			'settings_value' 		=> '', 
			'settings_title' 			=> PAYMENTECH_CFG_CURRSHOP_TTL, 
			'settings_description' 	=> PAYMENTECH_CFG_CURRSHOP_DSCR, 
			'settings_html_function' 	=> 'setting_CURRENCY_SELECT(', 
		);
		$this->SettingsFields['CONF_PAYMENTECH_PLATFORM'] = array(
			'settings_value' 		=> '', 
			'settings_title' 			=> PAYMENTECH_CFG_PLATFORM_TTL, 
			'settings_description' 	=> PAYMENTECH_CFG_PLATFORM_DSCR, 
			'settings_html_function' 	=> 'setting_SELECT_BOX(Paymentech::_getPlatformOptions(),', 
		);
	}

	function payment_form_html($_Params = null){
		
		if(xDataExists('_PAYMENTECH')){
			
			$_Params = xPopData('_PAYMENTECH');
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
				<td align="right">'.PAYMENTECH_TXT_CCNUMBER.'</td>
				<td>
					<input type="text" name="ccnumber" value="'.xHtmlSpecialChars(isset($_Params['ccnumber'])?$_Params['ccnumber']:'').'" />
				</td>
			</tr>
			<tr>
				<td align="right">'.PAYMENTECH_TXT_CSV.'</td>
				<td>
					<input type="text" name="csv" value="'.xHtmlSpecialChars(isset($_Params['csv'])?$_Params['csv']:'').'" />
				</td>
			</tr>
			<tr>
				<td align="right">'.PAYMENTECH_TXT_EXPDATE.'</td>
				<td>
				<select name="month">'.$ExpMonths.'</select>&nbsp;<select name="year">'.$ExpYears.'</select>
				</td>
			</tr>
			</table>
			';
  		
		return $res;
	}

	function payment_process($order){

		xSaveData('_PAYMENTECH', $_POST);
		
		$order_amount = RoundFloatValue($this->_convertCurrency($order["order_amount"], 0, $this->_getSettingValue('CONF_PAYMENTECH_CURRSHOP')));
		
		$order_id = substr(md5(time()),0,22);
		
		$rqRequest = new xmlNodeX('Request');
			$rqAC = &$rqRequest->child('AC');
				$rqCommonData = &$rqAC->child('CommonData');
		
					$rqCommonMandatory = &$rqCommonData->child('CommonMandatory');
					$rqCommonMandatory->attribute('AuthOverrideInd', 'N');
					$rqCommonMandatory->attribute('LangInd', '00');
					$rqCommonMandatory->attribute('CardHolderAttendanceInd', '01');
					$rqCommonMandatory->attribute('HcsTcsInd', 'T');
					$rqCommonMandatory->attribute('TxCatg', '7');
					$rqCommonMandatory->attribute('MessageType', $this->_getSettingValue('CONF_PAYMENTECH_MESSAGETYPE'));
					$rqCommonMandatory->attribute('Version', '2');
					$rqCommonMandatory->attribute('TzCode', $this->_getSettingValue('CONF_PAYMENTECH_TZCODE'));
		
						$rqAccountNum = &$rqCommonMandatory->child('AccountNum');
						$rqAccountNum->attribute('AccountTypeInd', '91');
						$rqAccountNum->setData($_POST['ccnumber']);
						
						$rqPOSDetails = &$rqCommonMandatory->child('POSDetails');
						$rqPOSDetails->attribute('POSEntryMode', '01');
						
						$rqMerchantID = &$rqCommonMandatory->child('MerchantID');
						$rqMerchantID->setData($this->_getSettingValue('CONF_PAYMENTECH_MERCHANTID'));
						
						$rqTerminalID = &$rqCommonMandatory->child('TerminalID');
						$rqTerminalID->attribute('TermEntCapInd', "05");
						$rqTerminalID->attribute('CATInfoInd', "06");
						$rqTerminalID->attribute('TermLocInd', "01");
						$rqTerminalID->attribute('CardPresentInd', "N");
						$rqTerminalID->attribute('POSConditionCode', "59");
						$rqTerminalID->attribute('AttendedTermDataInd', "01");
						$rqTerminalID->setData('001');
						
						$rqBIN = &$rqCommonMandatory->child('BIN');
						$rqBIN->setData($this->_getSettingValue('CONF_PAYMENTECH_PLATFORM')?$this->_getSettingValue('CONF_PAYMENTECH_PLATFORM'):'000001');
						
						$rqOrderID = &$rqCommonMandatory->child('OrderID');
						$rqOrderID->setData($order_id);
						
						$rqAmountDetails = &$rqCommonMandatory->child('AmountDetails');
							$rqAmount = &$rqAmountDetails->child('Amount');
							$rqAmount->setData($order_amount*100);
							
						$rqTxTypeCommon = &$rqCommonMandatory->child('TxTypeCommon');
						$rqTxTypeCommon->attribute('TxTypeID', 'G');
						
						$rqCurrency = &$rqCommonMandatory->child('Currency');
						$rqCurrency->attribute('CurrencyCode', $this->_getSettingValue('CONF_PAYMENTECH_CURRISO'));
						$rqCurrency->attribute('CurrencyExponent', $this->_getSettingValue('CONF_PAYMENTECH_CURREXP'));
						
						$rqCardPresence = &$rqCommonMandatory->child('CardPresence');
							$rqCardNP = &$rqCardPresence->child('CardNP');
								$rqExp = &$rqCardNP->child('Exp');
								$rqExp->setData(sprintf('%02d%02d', $_POST['month'],$_POST['year']));
								
					$rqTxDateTime = &$rqCommonMandatory->child('TxDateTime');
					$rqTxDateTime->setData(date('HismdY'));
								
					$rqCommonOptional = &$rqCommonData->child('CommonOptional');
						
						$rqCardSecVal = &$rqCommonOptional->child('CardSecVal');
						$rqCardSecVal->setData($_POST['csv']);
						
						$rqECommerceData = &$rqCommonOptional->child('ECommerceData');
						$rqECommerceData->attribute('ECSecurityInd', '07');
						
							$rqECOrderNum = &$rqECommerceData->child('ECOrderNum');
							$rqECOrderNum->setData($order_id);
							
				$rqAuth = &$rqAC->child('Auth');
				
					$rqAuthMandatory = &$rqAuth->child('AuthMandatory');
					$rqAuthMandatory->attribute('FormatInd', 'H');
					
				$rqAuthOptional = &$rqAuth->child('AuthOptional');
				
					$rqAVSextended = &$rqAuthOptional->child('AVSextended');

						$rqAVSextended->child('AVSname', array(), $this->_escapeAVSData($order['billing_info']['first_name'].' '.$order['billing_info']['last_name'], 30));
						$rqAVSextended->child('AVSaddress1', array(), $this->_escapeAVSData($order['billing_info']['address'],30));
						$rqAVSextended->child('AVScity', array(), $this->_escapeAVSData($order['billing_info']['city'], 20));
						$billing_zone = znGetSingleZoneById($order['billing_info']['zoneID']);
						if(is_array($billing_zone) && isset($billing_zone['zone_code'])){
						$rqAVSextended->child('AVSstate', array(), $this->_escapeAVSData($billing_zone['zone_code'], 2));
						}
						$rqAVSextended->child('AVSzip', array(), $this->_escapeAVSData($order['billing_info']['zip'], 10));
						$countries = cnGetCountries(array('offset'=>0,'CountRowOnPage'=>1000000), $count_row);
			
						$billing_country = null;
						foreach ($countries as $country){
							
							if($country['country_name'] == $order['billing_info']['country_name']){
								$billing_country = $country;
								break;
							}
						}
						if(is_array($billing_country) && isset($billing_country['country_iso_2']) && in_array($billing_country['country_iso_2'], array('US','CA','GB','UK'))){
						$rqAVSextended->child('AVScountryCode', array(), $this->_escapeAVSData($billing_country['country_iso_2'], 2));
						}
						
				$rqCap = &$rqAC->child('Cap');
				
					$rqCapMandatory = &$rqCap->child('CapMandatory');
					
						$rqEntryDataSrc = &$rqCapMandatory->child('EntryDataSrc');
						$rqEntryDataSrc->setData('02');
						
					$rqCapOptional = &$rqCap->child('CapOptional');
		$reqXML = '<?xml version="1.0" encoding="UTF-8"?>'.$rqRequest->getNodeXML(-1, false, true);
		
		$this->_log(LOGTYPE_DEBUG, "Sending request: \n".$reqXML);
		
		$ch = curl_init();
		if(!$ch){
			$this->_log(LOGTYPE_ERROR, 'Curl init error');
			return PAYMENTECH_TXT_ERROR_PROCESSING;
		}
		
		if ($this->_getSettingValue('CONF_PAYMENTECH_TESTMODE')) {
			$url = 'https://orbitalvar1.paymentech.net'; // for certification process only
		} else {
			$url = 'https://epayhip.paymentech.net'; // for actual production
		}
		
		if(!curl_setopt($ch, CURLOPT_URL, $url)) {
			$this->_log(LOGTYPE_ERROR, 'CURLOPT URL Error');
			return PAYMENTECH_TXT_ERROR_PROCESSING;
		}
		if(!curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'POST /AUTHORIZE HTTP/1.0', 
			'MIME-Version: 1.0', 
			'Content-type: application/PTI34',
			'Content-transfer-encoding: text', 
			'Request-number: 1', 
			'Document-type: Request'))) {
			$this->_log(LOGTYPE_ERROR, 'CURLOPT HTTPHEADER Error');
			return PAYMENTECH_TXT_ERROR_PROCESSING;
			}
		if(!curl_setopt($ch, CURLOPT_POST, 1)) {
			$this->_log(LOGTYPE_ERROR, 'CURLOPT POST Error');
			return PAYMENTECH_TXT_ERROR_PROCESSING;
		}
		if(!curl_setopt($ch, CURLOPT_POSTFIELDS, $reqXML)) {
			$this->_log(LOGTYPE_ERROR, 'CURLOPT TIMEOUT Error');
			return PAYMENTECH_TXT_ERROR_PROCESSING;
		}
		if(!curl_setopt($ch, CURLOPT_TIMEOUT, 25)) {
			$this->_log(LOGTYPE_ERROR, 'CURLOPT HTTPHEADER Error');
			return PAYMENTECH_TXT_ERROR_PROCESSING;
		} 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // gives error, but keeps xml formatting
		
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		initCurlProxySettings($ch);

		$result=curl_exec ($ch);
		
		if(curl_errno($ch)!=0){
			$this->_log(LOGTYPE_ERROR, 'Curl error #'.curl_errno($ch).' '.curl_error($ch));
			return PAYMENTECH_TXT_ERROR_PROCESSING;
		}
		curl_close ($ch); 
		
		$this->_log(LOGTYPE_DEBUG, "Transaction response: \n".$result);
		
		$rsResponse = new xmlNodeX();
		$rsResponse->renderTreeFromInner($result);
		
		$rsQuickResponse = &$rsResponse->getFirstChildByName('QuickResponse');
		
		if(!is_null($rsQuickResponse)){
			
			$rsProcStatus = &$rsQuickResponse->getFirstChildByName('ProcStatus');
			$rsStatusMsg = &$rsQuickResponse->getFirstChildByName('StatusMsg');
			$error_str = '';
			
			if (!is_null($rsStatusMsg) && $rsStatusMsg->getData()){
				
				$error_str .= PAYMENTECH_TXT_ERROR_MSG.$rsStatusMsg->getData().'. ';
			}
			
			if(!is_null($rsProcStatus) && $rsProcStatus->getData()){
				
				$error_str .= PAYMENTECH_TXT_ERROR_CODE.$rsProcStatus->getData().'. ';
			}
			
			$this->_log(LOGTYPE_ERROR, $error_str);
			return $error_str?$error_str:PAYMENTECH_TXT_ERROR_UKNOWN;
		}
		
		$r_rsCommonMandatoryResponse = &$rsResponse->xPath('/Response/ACResponse/CommonDataResponse/CommonMandatoryResponse');
		if(!is_array($r_rsCommonMandatoryResponse) && !count($r_rsCommonMandatoryResponse)){
			return PAYMENTECH_TXT_ERROR_PROCESSING;
		}
		$rsCommonMandatoryResponse = $r_rsCommonMandatoryResponse[0];
		/* @var $rsCommonMandatoryResponse xmlNodeX */

		if($rsCommonMandatoryResponse->getChildData('ProcStatus')){
			
			$rsProcStatus = &$rsCommonMandatoryResponse->getFirstChildByName('ProcStatus');
			$rsStatusMsg = &$rsCommonMandatoryResponse->getFirstChildByName('StatusMsg');
			$error_str = '';
			
			if (!is_null($rsStatusMsg) && $rsStatusMsg->getData()){
				
				$error_str .= PAYMENTECH_TXT_ERROR_MSG.$rsStatusMsg->getData().'. ';
			}
			
			if(!is_null($rsProcStatus) && $rsProcStatus->getData()){
				
				$error_str .= PAYMENTECH_TXT_ERROR_CODE.$rsProcStatus->getData().'. ';
			}
			
			$this->_log(LOGTYPE_ERROR, $error_str);
			return $error_str?$error_str:PAYMENTECH_TXT_ERROR_UKNOWN;
		}
		
		$rsApprovalStatus = &$rsCommonMandatoryResponse->getFirstChildByName('ApprovalStatus');
		switch ($rsApprovalStatus->getData()){
			case 1:
				xPopData('_PAYMENTECH', $_POST);
				return 1;
			case 0:
				return PAYMENTECH_TXT_DECLINED;
			default:
			case 2:
				return PAYMENTECH_TXT_ERROR_PROCESSING;
		}
	}

	function after_processing_php($_OrderID){
	
		if($this->_getSettingValue('CONF_PAYMENTECH_ORDERSTATUS') != -1){
		
			ostSetOrderStatusToOrder($_OrderID, $this->_getSettingValue('CONF_PAYMENTECH_ORDERSTATUS'));
		}
	}
	
	function _getTransactionTypeOptions(){
		
		$options = array();
		
		$options[] = array(
			'title' => PAYMENTECH_TXT_AUTH,
			'value' => 'A',
			);
		$options[] = array(
			'title' => PAYMENTECH_TXT_AUTHCAPTURE,
			'value' => 'AC',
			);
		
		return $options;
	}
	function _getPlatformOptions(){
		
		$options = array();
		
		$options[] = array(
			'title' => 'Salem (000001)',
			'value' => '000001',
			);
		$options[] = array(
			'title' => 'Tampa (000002)',
			'value' => '000002',
			);
		
		return $options;
	}
	
	function _escapeAVSData($data, $length = null){
		
		$data = str_replace(array('%','|','^', '\\', '/'), '', $data);
		return is_null($length)?$data:substr($data, 0, $length);
	}
}
?>