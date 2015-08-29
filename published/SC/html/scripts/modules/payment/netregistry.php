<?php
// NetRegistry payment module
// http://www.netregistry.com.au

// NetRegistry constants
define( 'NR_ERR_CURLINIT', 1000 );
define( 'NR_ERR_CURLEXEC', 1001 );
define( 'NR_RESPONSE_APPROVED', 1 );
define( 'NR_RESPONSE_DECLINED', 2 );
define( 'NR_RESPONSE_ERROR', 3 );

// NetRegistry url
define( 'NR_ENDPOINT_URL', 'https://4tknox.au.com/cgi-bin/themerchant.au.com/ecom/external2.pl' );

function NR_sendData( $variables ) //send data to NetRegistry server (CURL)
{
	if ( !($ch = curl_init()) ){
		
		CNetRegistry::_writeLogMessage(MODULE_LOG_CURL, 'Local error: '.translate("err_curlinit"));
		return NR_ERR_CURLINIT;
	}

	if ( curl_errno($ch) != 0 ){
		
		CNetRegistry::_writeLogMessage(MODULE_LOG_CURL, 'Curl error: '.curl_errno($ch).' '.curl_error($ch));
		return NR_ERR_CURLINIT;
	}

	$vars = "";
	foreach( $variables as $key => $value )
		$vars .= "$key=$value&";

	$vars = substr($vars, 0, strlen($vars) - 1);
	$url = NR_ENDPOINT_URL;

	@curl_setopt( $ch, CURLOPT_URL, $url );
	@curl_setopt( $ch, CURLOPT_POST, 1);
	@curl_setopt( $ch, CURLOPT_POSTFIELDS, $vars );
	@curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
	@curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0 );
	@curl_setopt( $ch, CURLOPT_TIMEOUT, 10 );
	initCurlProxySettings($ch);

	$result = @curl_exec($ch);
	if ( curl_errno($ch) != 0){
		
		CNetRegistry::_writeLogMessage(MODULE_LOG_CURL, 'Curl error: '.curl_errno($ch).' '.curl_error($ch));
		return NR_ERR_CURLEXEC;
	}

	curl_close($ch);
	
	return $result;
}

function NR_transaction( $variables )
	// sends data to NR gateway and returns array
	//	[0] - transactions result = approved / declined / failare
	//	[1] - response text (description)
{
	$NR_error = null;

	$variables['COMMAND'] = 'purchase';

	$response = NR_sendData( $variables );
	if ( !$response || $response == NR_ERR_CURLINIT || $response == NR_ERR_CURLEXEC )
	{
		$NR_error = $response;
		return CNETREGISTRY_TXT_NR_TRANSACTION_1;
	}
//var_dump($response);

	$response_array = explode( "\n", $response );
	$result = array();

	//result status
	$result[0] = trim($response_array[0]);
	//response text
	if (!strcmp($response_array[0],"failed"))
	{
		if (strlen( trim ( $response_array[1] ) )>0)
			$result[1] = $response_array[1];
		else
			$result[1] = CNETREGISTRY_TXT_NR_TRANSACTION_2;
	}
	else //approved
	{
		$response_start = strpos($response, "response_text=");
		if ($response_start > 0)
		{
			$temp_code = substr($response, $response_start);
			$response_end = strpos($temp_code, "\n");
			$temp_code = substr($temp_code, 0, $response_end);
			$temp_code = str_replace("response_text=", "", trim($temp_code) );
			$result[1] = $temp_code;
		}
		else
		{
			$result[1] = CNETREGISTRY_TXT_NR_TRANSACTION_3;
		}
	}

	return $result;
}	



/**
 * @connect_module_class_name CNetRegistry
 * @package DynamicModules
 * @subpackage Payment
 */
class CNetRegistry extends PaymentModule {

	var $type = PAYMTD_TYPE_CC;
	
	function _initVars(){
		
		parent::_initVars();
		$this->title 		= CNETREGISTRY_TTL;
		$this->description 	= CNETREGISTRY_DSCR;
		$this->sort_order 	= 2;
		
		$this->Settings = array( 
				"CONF_PAYMENTMODULE_NETREGISTRY_LOGIN",
				"CONF_PAYMENTMODULE_NETREGISTRY_PASSWORD",
				"CONF_PAYMENTMODULE_NETREGISTRY_DOLLAR_CURRENCY",
				"CONF_PAYMENTMODULE_NETREGISTRY_SAVE_CC_INFORMATION"
			);
	}

	function _initSettingFields(){

		$this->SettingsFields['CONF_PAYMENTMODULE_NETREGISTRY_LOGIN'] = array(
			'settings_value' 		=> '', 
			'settings_title' 			=> CNETREGISTRY_CFG_LOGIN_TTL, 
			'settings_description' 	=> CNETREGISTRY_CFG_LOGIN_DSCR, 
			'settings_html_function' 	=> 'setting_TEXT_BOX(0,', 
			'sort_order' 			=> 1,
		);
		$this->SettingsFields['CONF_PAYMENTMODULE_NETREGISTRY_PASSWORD'] = array(
			'settings_value' 		=> '', 
			'settings_title' 			=> CNETREGISTRY_CFG_PASSWORD_TTL, 
			'settings_description' 	=> CNETREGISTRY_CFG_PASSWORD_DSCR, 
			'settings_html_function' 	=> 'setting_TEXT_BOX(0,', 
			'sort_order' 			=> 1,
		);
		$this->SettingsFields['CONF_PAYMENTMODULE_NETREGISTRY_DOLLAR_CURRENCY'] = array(
			'settings_value' 		=> '0', 
			'settings_title' 			=> CNETREGISTRY_CFG_DOLLAR_CURRENCY_TTL, 
			'settings_description' 	=> CNETREGISTRY_CFG_DOLLAR_CURRENCY_DSCR, 
			'settings_html_function' 	=> 'setting_CURRENCY_SELECT(', 
			'sort_order' 			=> 1,
		);
		$this->SettingsFields['CONF_PAYMENTMODULE_NETREGISTRY_SAVE_CC_INFORMATION'] = array(
			'settings_value' 		=> '0', 
			'settings_title' 			=> CNETREGISTRY_CFG_SAVE_CC_INFORMATION_TTL, 
			'settings_description' 	=> CNETREGISTRY_CFG_SAVE_CC_INFORMATION_DSCR, 
			'settings_html_function' 	=> 'setting_CHECK_BOX(', 
			'sort_order' 			=> 1,
		);
	}

	function payment_form_html()
	{
		$ccnumber = isset($_POST["mNetReg_cc_number"]) ? str_replace("\"","&quot;",$_POST["mNetReg_cc_number"]) : "";
		$ccmonth = isset($_POST["mNetReg_exp_month"]) ? (int) $_POST["mNetReg_exp_month"] : 0;
		$ccyear = isset($_POST["mNetReg_exp_year"]) ? (int) $_POST["mNetReg_exp_year"] : 0;

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
		<tr><td>".CNETREGISTRY_TXT_PAYMENT_FORM_HTML_1.":</td><td><input type=text name=mNetReg_cc_number value=\"$ccnumber\"></td></tr>
		<tr><td>".CNETREGISTRY_TXT_PAYMENT_FORM_HTML_2.":</td><td>
			<select name=mNetReg_exp_month>
			<option value=\"0\">".CNETREGISTRY_TXT_PAYMENT_FORM_HTML_3."</option>
			$exp_months
			</select> /
			<select name=mNetReg_exp_year>
			<option value=\"0\">".CNETREGISTRY_TXT_PAYMENT_FORM_HTML_4."</option>
			$exp_years
			</select>
		</td></tr>";

		$text .= "</table>";

		return $text;
	}

	function payment_process($order){

		//verify input

		if (!isset($_POST["mNetReg_cc_number"]) || strlen( trim($_POST["mNetReg_cc_number"]) ) == 0)
		{
			return CNETREGISTRY_TXT_PAYMENT_PROCESS_1;
		}

		if (!isset($_POST["mNetReg_exp_month"]) || ((int) $_POST["mNetReg_exp_month"]) == 0)
		{
			return CNETREGISTRY_TXT_PAYMENT_PROCESS_2;
		}

		if (!isset($_POST["mNetReg_exp_year"]) || ((int) $_POST["mNetReg_exp_year"]) == 0)
		{
			return CNETREGISTRY_TXT_PAYMENT_PROCESS_3;
		}


		if ($this->_getSettingValue('CONF_PAYMENTMODULE_NETREGISTRY_DOLLAR_CURRENCY') > 0)
		{
			$NRcurr = currGetCurrencyByID($this->_getSettingValue('CONF_PAYMENTMODULE_NETREGISTRY_DOLLAR_CURRENCY'));
		}
		else
		{
			$NRcurr = array( "currency_value" => 1 );
		}
		$order_amount = round(100*$order["order_amount"] * $NRcurr["currency_value"])/100;


		// data provided correctly. send it to Authorize.Net web site
		$variables = array();

		$variables['LOGIN'] = $this->_getSettingValue('CONF_PAYMENTMODULE_NETREGISTRY_LOGIN')."/".$this->_getSettingValue('CONF_PAYMENTMODULE_NETREGISTRY_PASSWORD');
		$variables['AMOUNT'] = $order["order_amount"];
		$variables['CCNUM'] = $_POST["mNetReg_cc_number"];
		$variables['CCEXP'] = $_POST["mNetReg_exp_month"]."/".$_POST["mNetReg_exp_year"];
		$variables['COMMENT'] = CONF_SHOP_NAME." order (".$order["billing_info"]["first_name"]." ".$order["billing_info"]["last_name"].")";

		$response = @NR_transaction( $variables );
		
		if ( !isset($response) || !is_array($response) ) //request hasn't been sent
		{
			return CNETREGISTRY_TXT_PAYMENT_PROCESS_4;
		}
		else if (strcmp($response[0], "approved")) //transaction failed
		{
			return $response[1];
		}

		// success! :)
		
		return 1;
	}

	function after_processing_php($orderID)
	{
		if ($this->_getSettingValue('CONF_PAYMENTMODULE_NETREGISTRY_SAVE_CC_INFORMATION')) // save credit card data
		{
			$orderID = (int)$orderID;
			if ($orderID)
			{
				$order = ordGetOrder( $orderID ); //order information
				
				$expires = (string) $_POST["mNetReg_exp_month"];
				$expires.= (string) $_POST["mNetReg_exp_year"];

				db_query("update ".ORDERS_TABLE." set cc_number = '".Crypt::CCNumberCrypt($_POST["mNetReg_cc_number"],null)."', cc_holdername = '".Crypt::CCHoldernameCrypt($order["billing_firstname"]." ".$order["billing_lastname"],null)."', cc_expires = '".Crypt::CCExpiresCrypt($expires,null)."' where orderID=$orderID") or die (db_error());
			}
		}
		return "";
	}
}
?>