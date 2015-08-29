<?php
	// Authorize.Net SIM payment module (Simple Integration Method)
	// http://www.authorize.net
/**
 * @connect_module_class_name CAuthorizeNetSIM
 * @package DynamicModules
 * @subpackage Payment
 */

class CAuthorizeNetSIM extends PaymentModule{

	var $type = PAYMTD_TYPE_CC;
	var $default_logo = 'http://www.webasyst.net/collections/design/payment-icons/authorizenet.gif';
	
	function _initVars(){
		
		parent::_initVars();
		$this->title 		= CAUTHORIZENETSIM_TTL;
		$this->description 	= CAUTHORIZENETSIM_DSCR;
		$this->sort_order 	= 2;
		
		$this->Settings = array( 
				"CONF_PAYMENTMODULE_AUTHNETSIM_LOGIN",
				"CONF_PAYMENTMODULE_AUTHNETSIM_TRAN_KEY",
				"CONF_PAYMENTMODULE_AUTHNETSIM_TESTMODE"
			);
	}

	function _initSettingFields(){
		
		$this->SettingsFields['CONF_PAYMENTMODULE_AUTHNETSIM_LOGIN'] = array(
			'settings_value' 		=> '', 
			'settings_title' 			=> CAUTHORIZENETSIM_CFG_LOGIN_TTL, 
			'settings_description' 	=> CAUTHORIZENETSIM_CFG_LOGIN_DSCR, 
			'settings_html_function' 	=> 'setting_TEXT_BOX(0,', 
			'sort_order' 			=> 1,
		);

		$this->SettingsFields['CONF_PAYMENTMODULE_AUTHNETSIM_TRAN_KEY'] = array(
			'settings_value' 		=> '', 
			'settings_title' 			=> CAUTHORIZENETSIM_CFG_TRAN_KEY_TTL, 
			'settings_description' 	=> CAUTHORIZENETSIM_CFG_TRAN_KEY_DSCR, 
			'settings_html_function' 	=> 'setting_TEXT_BOX(0,', 
			'sort_order' 			=> 1,
		);

		$this->SettingsFields['CONF_PAYMENTMODULE_AUTHNETSIM_TESTMODE'] = array(
			'settings_value' 		=> '', 
			'settings_title' 			=> CAUTHORIZENETSIM_CFG_TESTMODE_TTL, 
			'settings_description' 	=> CAUTHORIZENETSIM_CFG_TESTMODE_DSCR, 
			'settings_html_function' 	=> 'setting_CHECK_BOX(', 
			'sort_order' 			=> 1,
		);
	}
	
	// *****************************************************************************
	// Purpose	Makes HMAC MD5 hash of the $data
	// Inputs   
	// Remarks	Thank to lance @ http://www.php.net/manual/en/function.mhash.php
	// Returns	hashed string
	function hmac ($key, $data)
	{
	   // RFC 2104 HMAC implementation for php.
	   // Creates an md5 HMAC.
	   // Eliminates the need to install mhash to compute a HMAC
	   // Hacked by Lance Rushing

	   $b = 64; // byte length for md5
	   if (strlen($key) > $b) {
		   $key = pack("H*",md5($key));
	   }
	   $key  = str_pad($key, $b, chr(0x00));
	   $ipad = str_pad('', $b, chr(0x36));
	   $opad = str_pad('', $b, chr(0x5c));
	   $k_ipad = $key ^ $ipad ;
	   $k_opad = $key ^ $opad;

	   return md5($k_opad  . pack("H*",md5($k_ipad . $data)));
	}

	function after_processing_html( $orderID ) 
	{
		$order = ordGetOrder( $orderID );
		$order_amount = $order["order_amount"] * $order["currency_value"];

		$res = "";

		$fp_timestamp = time();
		$fp_sequence = $orderID;
		$currency_code = $order["currency_code"];

		$testmode = $this->_getSettingValue('CONF_PAYMENTMODULE_AUTHNETSIM_TESTMODE') ? 'TRUE' : 'FALSE';

		$fp_hash = $this->hmac(
			$this->_getSettingValue('CONF_PAYMENTMODULE_AUTHNETSIM_TRAN_KEY'), $this->_getSettingValue('CONF_PAYMENTMODULE_AUTHNETSIM_LOGIN')."^".$fp_sequence."^".$fp_timestamp."^".$order_amount."^".$currency_code );

		$res .= 
			"<table width='100%'>\n".
			"	<tr>\n".
			"		<td align='center'>\n".
			"<form method='POST' name='authSIMform' action='https://secure.authorize.net/gateway/transact.dll'>\n".
			"<input type=\"hidden\" name=\"x_login\" value=\"".$this->_getSettingValue('CONF_PAYMENTMODULE_AUTHNETSIM_LOGIN')."\">\n".
			"<input type=\"hidden\" name=\"x_test_request\" value=\"".$testmode."\">\n".
			"<input type=\"hidden\" name=\"x_show_form\" value=\"PAYMENT_FORM\">".
			"<input type=\"hidden\" name=\"x_fp_sequence\" value=\"".$fp_sequence."\">\n".
			"<input type=\"hidden\" name=\"x_fp_timestamp\" value=\"".$fp_timestamp."\">\n".
			"<input type=\"hidden\" name=\"x_fp_hash\" value=\"".$fp_hash."\">\n".
			"<input type=\"hidden\" name=\"x_amount\" value=\"".$order_amount."\">\n".
			"<input type=\"hidden\" name=\"x_currency_code\" value=\"".$currency_code."\">\n".
//				"<input type=\"hidden\" name=\"x_method\" value=\"CC\">\n".
//				"<input type=\"hidden\" name=\"x_type\" value=\"AUTH_ONLY\">\n".

//				"<input type=\"hidden\" name=\"x_card_num\" value=\"".Crypt::CCNumberDeCrypt($order["cc_number"],null)."\">\n".
//				"<input type=\"hidden\" name=\"x_exp_date\" value=\"".Crypt::CCNumberDeCrypt($order["cc_expires"],null)."\">\n".

			"<input type=\"hidden\" name=\"x_first_name\" value=\"".$order["billing_firstname"]."\">\n".
			"<input type=\"hidden\" name=\"x_last_name\" value=\"".$order["billing_lastname"]."\">\n".
			"<input type=\"hidden\" name=\"x_address\" value=\"".$order["billing_address"]."\">\n".
			"<input type=\"hidden\" name=\"x_city\" value=\"".$order["billing_city"]."\">\n".
			"<input type=\"hidden\" name=\"x_state\" value=\"".$order["billing_state"]."\">\n".
			"<input type=\"hidden\" name=\"x_zip\" value=\"".$order["billing_zip"]."\">\n".
			"<input type=\"hidden\" name=\"x_country\" value=\"".$order["billing_country"]."\">\n".
//				"<input type=\"hidden\" name=\"x_phone\" value=\"".$order["billing_phone"]."\">\n".
			"<input type=\"hidden\" name=\"x_email\" value=\"".$order["customer_email"]."\">\n".
			"<input type=\"hidden\" name=\"x_customer_ip\" value=\"".$order["customer_ip"]."\">\n".

			"<input type=\"hidden\" name=\"x_invoice_num\" value=\"".$orderID."\">\n".
			"<input type=\"hidden\" name=\"x_description\" value=\"Order #".$orderID."\">\n".
			"<input type=\"hidden\" name=\"x_ship_to_first_name\" value=\"".$order["shipping_firstname"]."\">\n".
			"<input type=\"hidden\" name=\"x_ship_to_last_name\" value=\"".$order["shipping_lastname"]."\">\n".
			"<input type=\"hidden\" name=\"x_ship_to_address\" value=\"".$order["shipping_address"]."\">\n".
			"<input type=\"hidden\" name=\"x_ship_to_city\" value=\"".$order["shipping_city"]."\">\n".
			"<input type=\"hidden\" name=\"x_ship_to_state\" value=\"".$order["shipping_state"]."\">\n".
			"<input type=\"hidden\" name=\"x_ship_to_zip\" value=\"".$order["shipping_zip"]."\">\n".
			"<input type=\"hidden\" name=\"x_ship_to_country\" value=\"".$order["shipping_country"]."\">\n".
			"<input type='hidden' name='x_relay_response' value='FALSE'>\n".

			"<input type=\"submit\" value=\"".CAUTHORIZENETSIM_TXT_1."\">\n".

			"		</form>\n".

			"		</td>\n".
			"	</tr>\n".
			"</table>";

		return $res;
	}

}
?>