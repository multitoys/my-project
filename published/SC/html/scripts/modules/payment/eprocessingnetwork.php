<?php
// eProcessingNetwork payment module
// http://www.eProcessingNetwork.com


function setting_EPN_AUTHTYPE_SELECT()
{
	$modsuff = (isset($_GET['setting_up'])?'_'.(int)$_GET['setting_up']:'');
	
	if ( isset($_POST["save"]) )
	{
		if ( isset($_POST["setting_EPN_AUTHTYPE_SELECT"]) )
		{
			_setSettingOptionValue( "CONF_PAYMENTMODULE_EPROCNETWORK_AUTHORIZATION_TYPE".$modsuff, 
				$_POST["setting_EPN_AUTHTYPE_SELECT"] );
		}
	}

	$res = "";
	$res = "<select name='setting_EPN_AUTHTYPE_SELECT'>";
	$selectedID = _getSettingOptionValue("CONF_PAYMENTMODULE_EPROCNETWORK_AUTHORIZATION_TYPE".$modsuff);

	$res .= "<option value='AUTH_ONLY'";
	if ( !strcmp("AUTH_ONLY",$selectedID) ) $res .= " selected";
	$res .= ">".CEPROCESSINGNETWORK_TXT_1."</option>";

	$res .= "<option value='AUTH_CAPTURE'";
	if ( !strcmp("AUTH_CAPTURE",$selectedID) ) $res .= " selected";
	$res .= ">".CEPROCESSINGNETWORK_TXT_2."</option>";

	$res .= "<option value='CAPTURE_ONLY'";
	if ( !strcmp("CAPTURE_ONLY",$selectedID) ) $res .= " selected";
	$res .= ">".CEPROCESSINGNETWORK_TXT_3."</option>";

	$res .= "</select>";
	return $res;
}


	// Authorize.Net constants
	define( 'EPN_ERR_CURLINIT', 1000 );
	define( 'EPN_ERR_CURLEXEC', 1001 );
	define( 'EPN_DELIMCHAR', ',' );
	define( 'EPN_RESPONSE_CODE', 'RESPONSE_CODE' );
	define( 'EPN_RESPONSE_SUBCODE', 'RESPONSE_SUBCODE' );
	define( 'EPN_RESPONSE_REASON_TEXT', 'RESPONSE_REASON_TEXT' );
	define( 'EPN_RESPONSE_REASON_CODE', 'RESPONSE_REASON_CODE' );
	define( 'EPN_RESPONSE_APPROVAL_CODE', 'RESPONSE_APPROVAL_CODE' );
	define( 'EPN_RESPONSE_AVS_CODE', 'RESPONSE_AVS_CODE' );
	define( 'EPN_RESPONSE_AVS_TEXT', 'RESPONSE_AVS_TEXT' );
	define( 'EPN_RESPONSE_TRANSACTION_ID', 'RESPONSE_TRANSACTION_ID' );	
	define( 'EPN_RESPONSE_APPROVED', 1 );
	define( 'EPN_RESPONSE_DECLINED', 2 );
	define( 'EPN_RESPONSE_ERROR', 3 );

	// authorize.net url
//	define( 'EPN_ENDPOINT_URL_TEST', 'https://certification.authorize.net/gateway/transact.dll' );
	define( 'EPN_ENDPOINT_URL_LIVE', 'https://www.eProcessingNetwork.Com/cgi-bin/an/order.pl' );

	function EPN_sendData( $variables ) //send data to Authorize.Net server (CURL)
	{
		if ( !($ch = curl_init()) ){
			
			CEProcessingNetwork::_writeLogMessage(MODULE_LOG_CURL, 'Local error: '.translate("err_curlinit"));
			return translate("err_curlinit");
		}

		if ( curl_errno($ch) != 0 ){
			
			CEProcessingNetwork::_writeLogMessage(MODULE_LOG_CURL, 'Curl error: '.curl_errno($ch).' '.curl_error($ch));
			return translate("err_curlinit");
		}
		$vars = "";
		foreach( $variables as $key => $value )
			$vars .= "$key=$value&";

		$vars = substr($vars, 0, strlen($vars) - 1);
		$url = EPN_ENDPOINT_URL_LIVE;

		@curl_setopt( $ch, CURLOPT_URL, $url );
		@curl_setopt( $ch, CURLOPT_POST, 1);
		@curl_setopt( $ch, CURLOPT_POSTFIELDS, $vars );
		@curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		@curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0 );
		@curl_setopt( $ch, CURLOPT_TIMEOUT, 10 );
		initCurlProxySettings($ch);

		$result = @curl_exec($ch);
		if ( curl_errno($ch) != 0){
			
			CEProcessingNetwork::_writeLogMessage(MODULE_LOG_CURL, 'Curl error: '.curl_errno($ch).' '.curl_error($ch));
			return translate("err_curlexec");
		}

		curl_close($ch);
		
		return $result;
	}

	function EPN_transaction( $variables, $type, $EPN_avs_results )
	{
		$EPN_error = null;

		$variables['x_version'] = '3.1';
		$variables['x_delim_data'] = 'True';
		$variables['x_delim_char'] = EPN_DELIMCHAR;

		$response = EPN_sendData( $variables );
		if ( $response == EPN_ERR_CURLINIT || $response == EPN_ERR_CURLEXEC )
		{
			$EPN_error = $response;
			return "Error processing transaction";
		}
//var_dump($response);
		$response = explode( EPN_DELIMCHAR, $response );

		$result = array();
		$result[EPN_RESPONSE_CODE] = $response[0];
		$result[EPN_RESPONSE_SUBCODE] = $response[1];
		$result[EPN_RESPONSE_REASON_CODE] = $response[2];
		$result[EPN_RESPONSE_REASON_TEXT] = $response[3];
		$result[EPN_RESPONSE_APPROVAL_CODE] = $response[4];
		$result[EPN_RESPONSE_AVS_CODE] = $response[5];
		$result[EPN_RESPONSE_AVS_TEXT] = $EPN_avs_results[$response[5]];

		$result[EPN_RESPONSE_TRANSACTION_ID] = $response[6];

		return $result;
	}	



/**
 * @connect_module_class_name CEProcessingNetwork
 * @package DynamicModules
 * @subpackage Payment
 */

class CEProcessingNetwork extends PaymentModule{

	var $type = PAYMTD_TYPE_CC;
	var $language = 'eng';
	
	function _initVars(){
		
		parent::_initVars();
		$this->title 		= CEPROCESSINGNETWORK_TTL;
		$this->description 	= CEPROCESSINGNETWORK_DSCR;
		$this->sort_order 	= 2;

		$this->avs_results = array(
						0 => null,
						'A' => 'Address (Street) matches, ZIP does not',
						'B' => 'Address information not provided for AVS check',
						'E' => 'AVS error',
						'G' => 'Non-U.S. Card Issuing Bank',
						'N' => 'No Match on Address (Street) or ZIP',
						'P' => 'AVS not applicable for this transaction',
						'R' => 'Retry – System unavailable or timed out',
						'S' => 'Service not supported by issuer',
						'U' => 'Address information is unavailable',
						'W' => '9 digit ZIP matches, Address (Street) does not',
						'X' => 'Address (Street) and 9 digit ZIP match',
						'Y' => 'Address (Street) and 5 digit ZIP match',
						'Z' => '5 digit ZIP matches, Address (Street) does not'
					);
		$this->Settings = array( 
			"CONF_PAYMENTMODULE_EPROCNETWORK_LOGIN",
			"CONF_PAYMENTMODULE_EPROCNETWORK_PASSWORD",
			"CONF_PAYMENTMODULE_EPROCNETWORK_SAVE_CC_INFORMATION",
//					"CONF_PAYMENTMODULE_EPROCNETWORK_TESTMODE",
			"CONF_PAYMENTMODULE_EPROCNETWORK_AUTHORIZATION_TYPE",
			);
	}

	function payment_form_html()
	{
		$ccnumber = isset($_POST["mEPN_cc_number"]) ? str_replace("\"","&quot;",$_POST["mEPN_cc_number"]) : "";
		$ccholder = isset($_POST["mEPN_cc_holder"]) ? str_replace("\"","&quot;",$_POST["mEPN_cc_holder"]) : "";
		$cvv = isset($_POST["mEPN_cvv"]) ? str_replace("\"","&quot;",$_POST["mEPN_cvv"]) : "";
		$ccmonth = isset($_POST["mEPN_exp_month"]) ? (int) $_POST["mEPN_exp_month"] : 0;
		$ccyear = isset($_POST["mEPN_exp_year"]) ? (int) $_POST["mEPN_exp_year"] : 0;

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
		<tr><td>".CEPROCESSINGNETWORK_TXT_8.":</td><td><input type=text name=mEPN_cc_number value=\"$ccnumber\"></td></tr>
			<!--<tr><td>".CEPROCESSINGNETWORK_TXT_9.":</td><td><input type=text name=mEPN_cc_holder value=\"$ccholder\"></td></tr>-->
		<tr><td>Expires:</td><td>
			<select name=mEPN_exp_month>
			<option value=\"0\">".CEPROCESSINGNETWORK_TXT_10."</option>
			$exp_months
			</select> /
			<select name=mEPN_exp_year>
			<option value=\"0\">".CEPROCESSINGNETWORK_TXT_11."</option>
			$exp_years
			</select>
		</td></tr>";

		$text .= "<tr><td>CVV:</td><td><input type=text name=mEPN_cvv value=\"$cvv\"></td></tr>";

		$text .= "</table>";

		return $text;
	}

	function payment_process($order)
	{

		//verify input

		if (!isset($_POST["mEPN_cc_number"]) || strlen( trim($_POST["mEPN_cc_number"]) ) == 0)
		{
			return CEPROCESSINGNETWORK_TXT_4;
		}
/*
		if (!isset($_POST["mEPN_cc_holder"]) || strlen( trim($_POST["mEPN_cc_holder"]) ) == 0)
		{
			return "Please input credit card holder name";
		}
*/
		if ((!isset($_POST["mEPN_cvv"]) || strlen( trim($_POST["mEPN_cvv"]) ) == 0))
		{
			return CEPROCESSINGNETWORK_TXT_5;
		}

		if (!isset($_POST["mEPN_exp_month"]) || ((int) $_POST["mEPN_exp_month"]) == 0)
		{
			return CEPROCESSINGNETWORK_TXT_6;
		}

		if (!isset($_POST["mEPN_exp_year"]) || ((int) $_POST["mEPN_exp_year"]) == 0)
		{
			return CEPROCESSINGNETWORK_TXT_7;
		}


		// data provided correctly. send it to Authorize.Net web site
		$variables = array();

		$variables['x_type'] = $this->_getSettingValue('CONF_PAYMENTMODULE_EPROCNETWORK_AUTHORIZATION_TYPE');
		$variables['x_login'] = $this->_getSettingValue('CONF_PAYMENTMODULE_EPROCNETWORK_LOGIN');
		$variables['x_password'] = $this->_getSettingValue('CONF_PAYMENTMODULE_EPROCNETWORK_PASSWORD');


		$variables['x_amount'] = $order["order_amount"];
		$variables['x_currency_code'] = $order["currency_code"];
		$variables['x_card_num'] = $_POST["mEPN_cc_number"];
		$variables['x_exp_date'] = $_POST["mEPN_exp_month"]."-".$_POST["mEPN_exp_year"];
		$variables['x_first_name'] = $order["billing_info"]["first_name"];
		$variables['x_last_name'] = $order["billing_info"]["last_name"];
		$variables['x_address'] = $order["billing_info"]["address"];
		$variables['x_city'] = $order["billing_info"]["city"];
		$variables['x_state'] = $order["billing_info"]["state"];
		$variables['x_zip'] = $order["billing_info"]["zip"];
			$country = cnGetCountryById($order["billing_info"]["countryID"]);
		$variables['x_country'] = $country["country_name"];
//			$variables['x_phone'] = $phone;
		$variables['x_email'] = $order["customer_email"];
		$variables['x_customer_ip'] = $_SERVER["REMOTE_ADDR"];
		$variables['x_card_code'] = $_POST["mEPN_cvv"];

		$response = @EPN_transaction( $variables, "", $this->avs_results );

		if ( !is_array($response) ) //request hasn't been sent
		{
			return "Couldn't connect to the Authorize.Net payment gateway";
		}
		else
		{
			if ( $response[EPN_RESPONSE_CODE] != EPN_RESPONSE_APPROVED ) //all ok - save order
			{
				return $response[EPN_RESPONSE_REASON_TEXT];
			}
		}

		// success! :)
		
		return 1;
	}

	function after_processing_php($orderID)
	{
		if ($this->_getSettingValue('CONF_PAYMENTMODULE_EPROCNETWORK_SAVE_CC_INFORMATION')) // save credit card data
		{
			$orderID = (int)$orderID;
			if ($orderID)
			{
				$expires = (string) $_POST["mEPN_exp_month"];
				$expires.= (string) $_POST["mEPN_exp_year"];
				$cvv = isset($_POST["mEPN_cvv"]) ? $_POST["mEPN_cvv"] : "";

				db_query("update ".ORDERS_TABLE." set cc_number = '".Crypt::CCNumberCrypt($_POST["mEPN_cc_number"],null)."', cc_holdername = '".Crypt::CCHoldernameCrypt($_POST["mEPN_cc_holder"],null)."', cc_expires = '".Crypt::CCExpiresCrypt($expires,null)."', cc_cvv = '".Crypt::CCNumberCrypt($cvv,null)."' where orderID=$orderID") or die (db_error());
			}
		}
		return "";
	}

	function _initSettingFields(){

		$this->SettingsFields['CONF_PAYMENTMODULE_EPROCNETWORK_LOGIN'] = array(
			'settings_value' 		=> '', 
			'settings_title' 			=> CEPROCESSINGNETWORK_CFG_LOGIN_TTL, 
			'settings_description' 	=> CEPROCESSINGNETWORK_CFG_LOGIN_DSCR, 
			'settings_html_function' 	=> 'setting_TEXT_BOX(0,', 
			'sort_order' 			=> 1,
		);
		$this->SettingsFields['CONF_PAYMENTMODULE_EPROCNETWORK_PASSWORD'] = array(
			'settings_value' 		=> '', 
			'settings_title' 			=> CEPROCESSINGNETWORK_CFG_PASSWORD_TTL, 
			'settings_description' 	=> CEPROCESSINGNETWORK_CFG_PASSWORD_DSCR, 
			'settings_html_function' 	=> 'setting_TEXT_BOX(0,', 
			'sort_order' 			=> 1,
		);
		/*
		$this->SettingsFields['CONF_PAYMENTMODULE_EPROCNETWORK_TESTMODE'] = array(
			'settings_value' 		=> '0', 
			'settings_title' 			=> 'Test mode', 
			'settings_description' 	=> '', 
			'settings_html_function' 	=> 'setting_CHECK_BOX(', 
			'sort_order' 			=> 1,
		);
		*/
		$this->SettingsFields['CONF_PAYMENTMODULE_EPROCNETWORK_SAVE_CC_INFORMATION'] = array(
			'settings_value' 		=> '0', 
			'settings_title' 			=> CEPROCESSINGNETWORK_CFG_SAVE_CC_INFORMATION_TTL, 
			'settings_description' 	=> CEPROCESSINGNETWORK_CFG_SAVE_CC_INFORMATION_DSCR, 
			'settings_html_function' 	=> 'setting_CHECK_BOX(', 
			'sort_order' 			=> 1,
		);
		$this->SettingsFields['CONF_PAYMENTMODULE_EPROCNETWORK_AUTHORIZATION_TYPE'] = array(
			'settings_value' 		=> 'AUTH_ONLY', 
			'settings_title' 			=> CEPROCESSINGNETWORK_CFG_AUTHORIZATION_TYPE_TTL, 
			'settings_description' 	=> CEPROCESSINGNETWORK_CFG_AUTHORIZATION_TYPE_DSCR, 
			'settings_html_function' 	=> 'setting_EPN_AUTHTYPE_SELECT(', 
			'sort_order' 			=> 1,
		);
	}
}
?>