<?php
// PSiGate HTML Posting payment module
// http://www.psigate.com

/**
 * @connect_module_class_name CPSiGateHTML
 * @package DynamicModules
 * @subpackage Payment
 */
class CPSiGateHTML extends PaymentModule {

	var $type = PAYMTD_TYPE_CC;
	
	function _initVars(){
		
		parent::_initVars();
		$this->title 		= CPSIGATEHTML_TTL;
		$this->description 	= CPSIGATEHTML_DSCR;
		$this->sort_order 	= 1;
		
		$this->Settings = array( 
				"CONF_PAYMENTMODULE_PSIGATEHTML_MERCHANTID",
				"CONF_PAYMENTMODULE_PSIGATEHTML_CHARGETYPE",
				"CONF_PAYMENTMODULE_PSIGATEHTML_TESTMODE",
				"CONF_PAYMENTMODULE_PSIGATEHTML_REQUEST_CC_INFO",
				"CONF_PAYMENTMODULE_PSIGATEHTML_USD_CURRENCY"
			);
	}

	function _initSettingFields(){

		$this->SettingsFields['CONF_PAYMENTMODULE_PSIGATEHTML_MERCHANTID'] = array(
			'settings_value' 		=> '', 
			'settings_title' 			=> CPSIGATEHTML_CFG_MERCHANTID_TTL, 
			'settings_description' 	=> CPSIGATEHTML_CFG_MERCHANTID_DSCR, 
			'settings_html_function' 	=> 'setting_TEXT_BOX(0,', 
			'sort_order' 			=> 1,
		);
		$this->SettingsFields['CONF_PAYMENTMODULE_PSIGATEHTML_CHARGETYPE'] = array(
			'settings_value' 		=> '1', 
			'settings_title' 			=> CPSIGATEHTML_CFG_CHARGETYPE_TTL, 
			'settings_description' 	=> CPSIGATEHTML_CFG_CHARGETYPE_DSCR, 
			'settings_html_function' 	=> 'setting_SELECT_BOX(CPSiGateHTML::getChargeTypeOptions(),', 
			'sort_order' 			=> 1,
		);
		$this->SettingsFields['CONF_PAYMENTMODULE_PSIGATEHTML_TESTMODE'] = array(
			'settings_value' 		=> '1', 
			'settings_title' 			=> CPSIGATEHTML_CFG_TESTMODE_TTL, 
			'settings_description' 	=> CPSIGATEHTML_CFG_TESTMODE_DSCR, 
			'settings_html_function' 	=> 'setting_CHECK_BOX(', 
			'sort_order' 			=> 1,
		);
		$this->SettingsFields['CONF_PAYMENTMODULE_PSIGATEHTML_REQUEST_CC_INFO'] = array(
			'settings_value' 		=> '0', 
			'settings_title' 			=> CPSIGATEHTML_CFG_REQUEST_CC_INFO_TTL, 
			'settings_description' 	=> CPSIGATEHTML_CFG_REQUEST_CC_INFO_DSCR, 
			'settings_html_function' 	=> 'setting_CHECK_BOX(', 
			'sort_order' 			=> 1,
		);
		$this->SettingsFields['CONF_PAYMENTMODULE_PSIGATEHTML_USD_CURRENCY'] = array(
			'settings_value' 		=> '0', 
			'settings_title' 			=> CPSIGATEHTML_CFG_USD_CURRENCY_TTL, 
			'settings_description' 	=> CPSIGATEHTML_CFG_USD_CURRENCY_DSCR, 
			'settings_html_function' 	=> 'setting_CURRENCY_SELECT(', 
			'sort_order' 			=> 1,
		);
	}

	function getChargeTypeOptions(){
		
		return array(
			array(
				'title' => CPSIGATEHTML_TXT_GETCHARGETYPEOPTIONS_1,
				'value' => '1',
				),
			array(
				'title' => CPSIGATEHTML_TXT_GETCHARGETYPEOPTIONS_2,
				'value' => '0',
				),
			);
	}
	
	function payment_form_html()
	{
		if ($this->_getSettingValue('CONF_PAYMENTMODULE_PSIGATEHTML_REQUEST_CC_INFO') == 1)
		{
			$ccnumber = isset($_POST["mPSiGHTML_cc_number"]) ? str_replace("\"","&quot;",$_POST["mPSiGHTML_cc_number"]) : "";
			$ccholder = isset($_POST["mPSiGHTML_cc_holder"]) ? str_replace("\"","&quot;",$_POST["mPSiGHTML_cc_holder"]) : "";
			$ccmonth = isset($_POST["mPSiGHTML_exp_month"]) ? (int) $_POST["mPSiGHTML_exp_month"] : 0;
			$ccyear = isset($_POST["mPSiGHTML_exp_year"]) ? (int) $_POST["mPSiGHTML_exp_year"] : 0;

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
			<tr><td>".CPSIGATEHTML_TXT_PAYMENT_FORM_HTML_1."</td><td><input type=text name=mPSiGHTML_cc_number value=\"$ccnumber\"></td></tr>
				<!--<tr><td>Cardholder name:</td><td><input type=text name=mPSiGHTML_cc_holder value=\"$ccholder\"></td></tr>-->
			<tr><td>".CPSIGATEHTML_TXT_PAYMENT_FORM_HTML_2."</td><td>
				<select name=mPSiGHTML_exp_month>
				<option value=\"0\">".CPSIGATEHTML_TXT_PAYMENT_FORM_HTML_3."</option>
				$exp_months
				</select> /
				<select name=mPSiGHTML_exp_year>
				<option value=\"0\">".CPSIGATEHTML_TXT_PAYMENT_FORM_HTML_4."</option>
				$exp_years
				</select>
			</td></tr>";

			$text .= "</table>";

			return $text;
		}
		else
		{
			return "";
		}
	}

	function payment_process($order)
	{
		if ($this->_getSettingValue('CONF_PAYMENTMODULE_PSIGATEHTML_REQUEST_CC_INFO') == 1)
		{

			//verify input

			if (!isset($_POST["mPSiGHTML_cc_number"]) || strlen( trim($_POST["mPSiGHTML_cc_number"]) ) == 0)
			{
				return CPSIGATEHTML_TXT_PAYMENT_PROCESS_1;
			}

			if (!isset($_POST["mPSiGHTML_exp_month"]) || ((int) $_POST["mPSiGHTML_exp_month"]) == 0)
			{
				return CPSIGATEHTML_TXT_PAYMENT_PROCESS_2;
			}

			if (!isset($_POST["mPSiGHTML_exp_year"]) || ((int) $_POST["mPSiGHTML_exp_year"]) == 0)
			{
				return CPSIGATEHTML_TXT_PAYMENT_PROCESS_3;
			}

		}

		return 1;
	}

	function after_processing_php($orderID)
	{
		if ($this->_getSettingValue('CONF_PAYMENTMODULE_PSIGATEHTML_REQUEST_CC_INFO') == 1)
		{
			$orderID = (int)$orderID;
			if ($orderID)
			{
				$expires = (string) $_POST["mPSiGHTML_exp_month"];
				$expires.= (string) $_POST["mPSiGHTML_exp_year"];
				$cvv = isset($_POST["mPSiGHTML_cvv"]) ? $_POST["mPSiGHTML_cvv"] : "";

				db_query("update ".ORDERS_TABLE." set cc_number = '".Crypt::CCNumberCrypt($_POST["mPSiGHTML_cc_number"],null)."', cc_expires = '".Crypt::CCExpiresCrypt($expires,null)."', cc_cvv = '".Crypt::CCNumberCrypt($cvv,null)."' where orderID=$orderID") or die (db_error());
			}

		}

		return "";
	}

	function after_processing_html( $orderID ) 
	{
		$order = ordGetOrder( $orderID );

		//get order amount
		if ( $this->_getSettingValue('CONF_PAYMENTMODULE_PSIGATEHTML_USD_CURRENCY') > 0 )
		{
			$curr = currGetCurrencyByID ( $this->_getSettingValue('CONF_PAYMENTMODULE_PSIGATEHTML_USD_CURRENCY') );
			$curr_rate = $curr["currency_value"];
		}
		if (!isset($curr) || !$curr)
		{
			$curr_rate = 1;
		}
		$order_amount = RoundFloatValue( $order["order_amount"] * $curr_rate );


		$res = "";

		$testmode = $this->_getSettingValue('CONF_PAYMENTMODULE_PSIGATEHTML_TESTMODE') ? 1 : 0; //you may change 1 to 2 or 3 to change test mode (e.g. to simulate declined transactions. Refer to PSiGate HTML Posting API for more information

		$res .= 
			"<table width='100%'>\n".
			"	<tr>\n".
			"		<td align='center'>\n".
			"<form method='POST' name='psigateform' action='https://order.psigate.com/psigate.asp'>\n".
			"<input type=\"hidden\" name=\"MerchantID\" value=\"".$this->_getSettingValue('CONF_PAYMENTMODULE_PSIGATEHTML_MERCHANTID')."\">\n".
			"<input type=\"hidden\" name=\"Oid\" value=\"".$orderID."\">\n".

			"<input type=\"hidden\" name=\"FullTotal\" value=\"".$order_amount."\">\n".
			"<input type=\"hidden\" name=\"TaxOn\" value=\"0\">\n". //tax calculation is performed on shopping cart side
			"<input type=\"hidden\" name=\"ShipOn\" value=\"0\">\n". //shipping rate calculation is performed on shopping cart side
			"<input type=\"hidden\" name=\"Items\" value=\"1\">\n". //1 items - order #$orderID
			"<input type=\"hidden\" name=\"State\" value=\"".$order["shipping_state"]."\">\n".
			"<input type=\"hidden\" name=\"Country\" value=\"".$order["shipping_country"]."\">\n".
			"<input type=\"hidden\" name=\"Language\" value=\"EN\">\n".

			"<input type=\"hidden\" name=\"Bname\" value=\"".$order["billing_firstname"]." ".$order["billing_lastname"]."\">\n".
			"<input type=\"hidden\" name=\"Baddr1\" value=\"".$order["billing_address"]."\">\n".
			"<input type=\"hidden\" name=\"Bcity\" value=\"".$order["billing_city"]."\">\n".
			"<input type=\"hidden\" name=\"Bstate\" value=\"".$order["billing_state"]."\">\n".
			"<input type=\"hidden\" name=\"Bzip\" value=\"".$order["billing_zip"]."\">\n".
			"<input type=\"hidden\" name=\"Bcountry\" value=\"".$order["billing_country"]."\">\n".
//				"<input type=\"hidden\" name=\"Phone\" value=\"".$order["billing_phone"]."\">\n".

			"<input type=\"hidden\" name=\"Email\" value=\"".$order["customer_email"]."\">\n".
			"<input type=\"hidden\" name=\"IP\" value=\"".$order["customer_ip"]."\">\n".
			"<input type=\"hidden\" name=\"Comments\" value=\"Order #".$orderID."\">\n".

			"<input type=\"hidden\" name=\"Sname\" value=\"".$order["shipping_firstname"]." ".$order["shipping_lastname"]."\">\n".
			"<input type=\"hidden\" name=\"Saddr1\" value=\"".$order["shipping_address"]."\">\n".
			"<input type=\"hidden\" name=\"Scity\" value=\"".$order["shipping_city"]."\">\n".
			"<input type=\"hidden\" name=\"Sstate\" value=\"".$order["shipping_state"]."\">\n".
			"<input type=\"hidden\" name=\"Szip\" value=\"".$order["shipping_zip"]."\">\n".
			"<input type=\"hidden\" name=\"Scountry\" value=\"".$order["shipping_country"]."\">\n".

			"<input type=\"hidden\" name=\"ShipType\" value=\"".$order["shipping_type"]."\">\n";

		if ($this->_getSettingValue('CONF_PAYMENTMODULE_PSIGATEHTML_REQUEST_CC_INFO') == 1) //pass CC information to PSiGate
		{
			$exp = $order["cc_expires"];
			$expM = substr($exp,0,2);
			$expY = substr($exp,2,2);

			if (strlen($order["cc_number"])>0 && strlen($exp)>0)
			{
				$res .=
					"<input type=\"hidden\" name=\"CardNumber\" value=\"".$order["cc_number"]."\">\n".
					"<input type=\"hidden\" name=\"ExpMonth\" value=\"".$expM."\">\n".
					"<input type=\"hidden\" name=\"ExpYear\" value=\"".$expY."\">\n";
			}
		}

		$res .=

			"<input type=\"hidden\" name=\"ChargeType\" value=\"".$this->_getSettingValue('CONF_PAYMENTMODULE_PSIGATEHTML_CHARGETYPE')."\">\n".
			"<input type=\"hidden\" name=\"Result\" value=\"".$testmode."\">\n". //0 for live; 1,2,3 for testing
//				"<input type=\"hidden\" name=\"Addrnum\" value=\"\">\n".

			"<input type=\"submit\" value=\"".CPSIGATEHTML_TXT_AFTER_PROCESSING_HTML_1."\">\n".

			"		</form>\n".

			"		</td>\n".
			"	</tr>\n".
			"</table>";

		return $res;
	}
}
?>