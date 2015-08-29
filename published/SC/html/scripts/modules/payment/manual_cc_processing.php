<?php
	// Manual credit cards processing module

/**
 * @connect_module_class_name CManualCCProcessing
 * @package DynamicModules
 * @subpackage Payment
 */
class CManualCCProcessing extends PaymentModule {

	var $type = PAYMTD_TYPE_CC;
	var $default_logo = 'http://www.webasyst.net/collections/design/payment-icons/visa.gif';
	
	function _initVars(){
		
		parent::_initVars();
		$this->title 		= CMANUALCCPROCESSING_TTL;
		$this->description 	= CMANUALCCPROCESSING_DSCR;
		$this->sort_order 	= 1;
		$this->Settings = array( 
				"CONF_PAYMENTMODULE_MANUAL_CC_REQUESTCVV",
				"CONF_PAYMENTMODULE_MANUAL_CC_EMAIL4CCNUM"
			);
	}
	
	function _initSettingFields(){
		
		$this->SettingsFields['CONF_PAYMENTMODULE_MANUAL_CC_REQUESTCVV'] = array(
			'settings_value' 		=> '1', 
			'settings_title' 			=> CMANUALCCPROCESSING_CFG_REQUESTCVV_TTL, 
			'settings_description' 	=> CMANUALCCPROCESSING_CFG_REQUESTCVV_DSCR, 
			'settings_html_function' 	=> 'setting_CHECK_BOX(', 
			'sort_order' 			=> 1,
		);
		$this->SettingsFields['CONF_PAYMENTMODULE_MANUAL_CC_EMAIL4CCNUM'] = array(
			'settings_value' 		=> '', 
			'settings_title' 			=> CMANUALCCPROCESSING_CFG_EMAIL4CCNUM_TTL, 
			'settings_description' 	=> CMANUALCCPROCESSING_CFG_EMAIL4CCNUM_DSCR, 
			'settings_html_function' 	=> 'setting_TEXT_BOX(0,', 
			'sort_order' 			=> 2,
		);
	}

	function payment_form_html()
	{
		$ccnumber = isset($_POST["mccp_cc_number"]) ? str_replace("\"","&quot;",$_POST["mccp_cc_number"]) : "";
		$ccholder = isset($_POST["mccp_cc_holder"]) ? str_replace("\"","&quot;",$_POST["mccp_cc_holder"]) : "";
		$cvv = isset($_POST["mccp_cvv"]) ? str_replace("\"","&quot;",$_POST["mccp_cvv"]) : "";
		$ccmonth = isset($_POST["mccp_exp_month"]) ? (int) $_POST["mccp_exp_month"] : 0;
		$ccyear = isset($_POST["mccp_exp_year"]) ? (int) $_POST["mccp_exp_year"] : 0;

		$exp_months = "";
		for ($i=1; $i<=12; $i++)
		{
			$m = (string)$i;
			if ($i<10) $m = "0".$m;
			$exp_months .= "<option value=\"$m\"";
			if ($ccmonth == $i) $exp_months .= " selected";
			$exp_months .= ">$m</option>\n";
		}

		$curr_year = (int) strftime("%y",time());
		$exp_years = "";
		for ($i=$curr_year; $i<$curr_year+10; $i++)
		{
			$y = (string)$i;
			if ($i<10) $y = "0".$y;
			$exp_years .= "<option value=\"$y\"";
			if ($ccyear == $i) $exp_years .= " selected";
			$exp_years .= ">20$y</option>\n";
		}

		$text = "<table>
		<tr><td>".CMANUALCCPROCESSING_TXT_PAYMENT_FORM_HTML_1.":</td><td><input type=text name=mccp_cc_number value=\"$ccnumber\"></td></tr>
		<tr><td>".CMANUALCCPROCESSING_TXT_PAYMENT_FORM_HTML_2.":</td><td><input type=text name=mccp_cc_holder value=\"$ccholder\"></td></tr>
		<tr><td>".CMANUALCCPROCESSING_TXT_PAYMENT_FORM_HTML_3.":</td><td>
			<select name=mccp_exp_month>
			<option value=\"0\">".CMANUALCCPROCESSING_TXT_PAYMENT_FORM_HTML_4."</option>
			$exp_months
			</select> /
			<select name=mccp_exp_year>
			<option value=\"0\">".CMANUALCCPROCESSING_TXT_PAYMENT_FORM_HTML_5."</option>
			$exp_years
			</select>
		</td></tr>";

		if ($this->_getSettingValue('CONF_PAYMENTMODULE_MANUAL_CC_REQUESTCVV') == 1)
		{
			$text .= "<tr><td>CVV:</td><td><input type=text name=mccp_cvv value=\"$cvv\"></td></tr>";
		}

		$text .= "</table>";

		return $text;
	}

	function payment_process($order)
	{
		//verify input

		if (!isset($_POST["mccp_cc_number"]) || strlen( trim($_POST["mccp_cc_number"]) ) == 0)
		{
			return CMANUALCCPROCESSING_TXT_payment_process_1;
		}

		if (!isset($_POST["mccp_cc_holder"]) || strlen( trim($_POST["mccp_cc_holder"]) ) == 0)
		{
			return CMANUALCCPROCESSING_TXT_payment_process_2;
		}

		if (($this->_getSettingValue('CONF_PAYMENTMODULE_MANUAL_CC_REQUESTCVV') == 1) && (!isset($_POST["mccp_cvv"]) || strlen( trim($_POST["mccp_cvv"]) ) == 0))
		{
			return CMANUALCCPROCESSING_TXT_payment_process_3;
		}

		if (!isset($_POST["mccp_exp_month"]) || ((int) $_POST["mccp_exp_month"]) == 0)
		{
			return CMANUALCCPROCESSING_TXT_payment_process_4;
		}

		if (!isset($_POST["mccp_exp_year"]) || ((int) $_POST["mccp_exp_year"]) == 0)
		{
			return CMANUALCCPROCESSING_TXT_payment_process_5;
		}

		return 1;
	}

	function after_processing_php($orderID){
		
		$orderID = (int)$orderID;
		if (!$orderID)return '';

		$expires = (string) $_POST["mccp_exp_month"];
		$expires.= (string) $_POST["mccp_exp_year"];
		$cvv = isset($_POST["mccp_cvv"]) ? $_POST["mccp_cvv"] : "";

		if($this->_getSettingValue('CONF_PAYMENTMODULE_MANUAL_CC_EMAIL4CCNUM')){
			$ccnum_len = strlen($_POST["mccp_cc_number"]);
			$db_ccnum_len = ceil($ccnum_len/2); 
			$em_ccnum_len = floor($ccnum_len/2); 
			$db_card_part = substr($_POST["mccp_cc_number"], 0, $db_ccnum_len).str_repeat('X', $em_ccnum_len);
			$em_card_part = str_repeat('X', $db_ccnum_len).substr($_POST["mccp_cc_number"], $db_ccnum_len, $em_ccnum_len);
		}else{
			$db_card_part = $_POST["mccp_cc_number"];
		}
		db_query("update ".ORDERS_TABLE." set cc_number = '".Crypt::CCNumberCrypt($db_card_part,null)."', cc_holdername = '".Crypt::CCHoldernameCrypt($_POST["mccp_cc_holder"],null)."', cc_expires = '".Crypt::CCExpiresCrypt($expires,null)."', cc_cvv = '".Crypt::CCNumberCrypt($cvv,null)."' where orderID=$orderID") or die (db_error());
		
		if($this->_getSettingValue('CONF_PAYMENTMODULE_MANUAL_CC_EMAIL4CCNUM')&&$em_card_part){
			$order = ordGetOrder($orderID);
			ss_mail(trim($this->_getSettingValue('CONF_PAYMENTMODULE_MANUAL_CC_EMAIL4CCNUM')), str_replace('%ORDER_NUM%', $order['orderID_view'], CMANUALCCPROCESSING_TXT_EMAIL_SUBJECT), $em_card_part);
		}
		return "";
	}
}
?>