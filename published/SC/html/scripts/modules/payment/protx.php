<?php
// PROTX payment module
// http://www.protx.com

/**
 * @connect_module_class_name CProtx
 * @package DynamicModules
 * @subpackage Payment
 */
class CProtx extends PaymentModule {


	var $type = PAYMTD_TYPE_CC;
	var $default_logo = 'http://www.webasyst.net/collections/design/payment-icons/protx.gif';
	
	function _initVars(){
		
		parent::_initVars();
		$this->title 		= CPROTX_TTL;
		$this->description 	= CPROTX_DSCR;
		$this->sort_order 	= 1;
		
		$this->Settings = array( 
				"CONF_PAYMENTMODULE_PROTX_VENDORNAME",
				"CONF_PAYMENTMODULE_PROTX_ENCPASSWORD",
				"CONF_PAYMENTMODULE_PROTX_MODE"
			);
	}

	function _initSettingFields(){

		$this->SettingsFields['CONF_PAYMENTMODULE_PROTX_VENDORNAME'] = array(
			'settings_value' 		=> '', 
			'settings_title' 			=> CPROTX_CFG_VENDORNAME_TTL, 
			'settings_description' 	=> CPROTX_CFG_VENDORNAME_DSCR, 
			'settings_html_function' 	=> 'setting_TEXT_BOX(0,', 
			'sort_order' 			=> 1,
		);
		$this->SettingsFields['CONF_PAYMENTMODULE_PROTX_ENCPASSWORD'] = array(
			'settings_value' 		=> '', 
			'settings_title' 			=> CPROTX_CFG_ENCPASSWORD_TTL, 
			'settings_description' 	=> CPROTX_CFG_ENCPASSWORD_DSCR, 
			'settings_html_function' 	=> 'setting_TEXT_BOX(0,', 
			'sort_order' 			=> 1,
		);
		$this->SettingsFields['CONF_PAYMENTMODULE_PROTX_MODE'] = array(
			'settings_value' 		=> '1', 
			'settings_title' 			=> CPROTX_CFG_MODE_TTL, 
			'settings_description' 	=> CPROTX_CFG_MODE_DSCR, 
			'settings_html_function' 	=> 'setting_SELECT_BOX(CProtx::getModeOptions(),', 
			'sort_order' 			=> 1,
		);
	}
	
	function getModeOptions(){
		
		return array(
			array(
				'title' => CPROTX_TXT_GETMODEOPTIONS_1,
				'value' => '0',
				),
			array(
				'title' => CPROTX_TXT_GETMODEOPTIONS_2,
				'value' => '1',
				),
			array(
				'title' => CPROTX_TXT_GETMODEOPTIONS_3,
				'value' => '2',
				),
			);
	}

	function after_processing_html( $orderID ) 
	{
		$orderID = (int) $orderID;

		$order = ordGetOrder( $orderID );
		if (!$order) return "";

		$order_amount = round(100*$order["order_amount"] * $order["currency_value"])/100;
		//make sure there are 2 numbers after point, e.g. .00 (and not .0) 
		if (round($order_amount*10) == $order_amount*10 && round($order_amount)!=$order_amount) 
			$order_amount = "$order_amount"."0";

		switch ( (int) $this->_getSettingValue('CONF_PAYMENTMODULE_PROTX_MODE'))
		{
			case 1: //test
				$submitURL = "https://ukvpstest.protx.com/vspgateway/service/vspform-register.vsp"; //"https://ukvpstest.protx.com/vps2form/submit.asp";
			break;
			case 2: //live
				$submitURL = "https://ukvps.protx.com/vspgateway/service/vspform-register.vsp"; //"https://ukvps.protx.com/vps2form/submit.asp";
			break;
			default: //simulator
				$submitURL = "https://ukvpstest.protx.com/VSPSimulator/vspform-register.vsp"; //"https://ukvpstest.protx.com/VSPSimulator/VSPFormGateway.asp";
			break;
		}


		//prepare 'order_info' descriptive string
//		$txcode = $orderID."-".str_replace( " ","", (microtime(true)) );
		$txcode = $orderID."".time();

		$order_info = "";
		$order_info .= "VendorTxCode=" . $txcode . "&";
		$order_info .= "Amount=" . $order_amount . "&";
		$order_info .= "Currency=" . $order["currency_code"] . "&";
		$order_info .= "Description=Order_$orderID&";
		$order_info .= "SuccessURL=".getTransactionResultURL('success')."&";
		$order_info .= "FailureURL=".getTransactionResultURL('failure')."&";
		$order_info .= "CustomerEmail=".$order["customer_email"]."&";
		$order_info .= "VendorEmail=".CONF_GENERAL_EMAIL."&";
		$order_info .= "CustomerName=".$order["billing_firstname"]." ".$order["billing_lastname"]."&";
		$order_info .= "BillingAddress=".$order["billing_address"]." ".$order["billing_city"]."&";
		$order_info .= "BillingPostCode=".$order["billing_zip"]."&";
		$order_info .= "DeliveryAddress=".$order["shipping_address"]." ".$order["shipping_city"]."&";
		$order_info .= "DeliveryPostCode=".$order["shipping_zip"]."&";

		$order_info .= "EMailMessage=".CPROTX_TXT_AFTER_PROCESSING_HTML_1;

		//prepared 'Crypt' parameter
		$crypt = base64_encode( $this->PROTXSimpleXor($order_info, $this->_getSettingValue('CONF_PAYMENTMODULE_PROTX_ENCPASSWORD')) );

		//'proceed to PROTX' button
		$res = "<FORM ACTION=\"".$submitURL."\" METHOD=\"post\" ID=\"form1\">".
	        "<INPUT TYPE=\"hidden\" NAME=\"VPSProtocol\" VALUE=\"2.22\">".
	        "<INPUT TYPE=\"hidden\" NAME=\"TxType\" VALUE=\"PAYMENT\">".
		    "<INPUT TYPE=\"hidden\" NAME=\"Vendor\" VALUE=\"".$this->_getSettingValue('CONF_PAYMENTMODULE_PROTX_VENDORNAME')."\">".
			"<INPUT TYPE=\"hidden\" NAME=\"Crypt\" VALUE=\"".$crypt."\">".
	        "<INPUT TYPE=\"submit\" VALUE=\"".CPROTX_TXT_AFTER_PROCESSING_HTML_2."\" ALIGN=\"right\">".
            "</FORM>";

		return $res;
	}
	
	function PROTXsimpleXor($InString, $Key) {
	  // Initialise key array
	  $KeyList = array();
	  // Initialise out variable
	  $output = "";
	  
	  // Convert $Key into array of ASCII values
	  for($i = 0; $i < strlen($Key); $i++){
	    $KeyList[$i] = ord(substr($Key, $i, 1));
	  }
	
	  // Step through string a character at a time
	  for($i = 0; $i < strlen($InString); $i++) {
	    // Get ASCII code from string, get ASCII code from key (loop through with MOD), XOR the two, get the character from the result
	    // % is MOD (modulus), ^ is XOR
	    $output.= chr(ord(substr($InString, $i, 1)) ^ ($KeyList[$i % strlen($Key)]));
	  }
	
	  // Return the result
	  return $output;
	}
}
?>
