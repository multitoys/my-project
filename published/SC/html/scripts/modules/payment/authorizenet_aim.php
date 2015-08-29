<?php
	// Authorize.Net AIM payment module (Advanced Integration Method)
	// http://www.authorize.net


function setting_AN_AUTHTYPE_SELECT($module_id)
{
	/*$module_id = isset($_GET['setting_up'])?$_GET['setting_up']:0;*/
	$modsuff = ($module_id?'_'.$module_id:'');
	if ( isset($_POST["save"]) )
	{
		if ( isset($_POST["setting_AN_AUTHTYPE_SELECT"]) )
		{
			_setSettingOptionValue( "CONF_PAYMENTMODULE_AUTHNETAIM_AUTHORIZATION_TYPE".$modsuff, 
				$_POST["setting_AN_AUTHTYPE_SELECT"] );
		}
	}

	$res = "";
	$res = "<select name='setting_AN_AUTHTYPE_SELECT'>";
	$selectedID = _getSettingOptionValue("CONF_PAYMENTMODULE_AUTHNETAIM_AUTHORIZATION_TYPE".$modsuff);

	$res .= "<option value='AUTH_ONLY'";
	if ( !strcmp("AUTH_ONLY",$selectedID) ) $res .= " selected";
	$res .= ">".CAUTHORIZENETAIM_TXT_1."</option>";

	$res .= "<option value='AUTH_CAPTURE'";
	if ( !strcmp("AUTH_CAPTURE",$selectedID) ) $res .= " selected";
	$res .= ">".CAUTHORIZENETAIM_TXT_2."</option>";

	$res .= "<option value='PRIOR_AUTH_CAPTURE'";
	if ( !strcmp("PRIOR_AUTH_CAPTURE",$selectedID) ) $res .= " selected";
	$res .= ">".CAUTHORIZENETAIM_TXT_3."</option>";

	$res .= "</select>";
	return $res;
}


	// Authorize.Net constants
	define( 'AN_DELIMCHAR', ',' );
	define( 'AN_RESPONSE_CODE', 'RESPONSE_CODE' );
	define( 'AN_RESPONSE_SUBCODE', 'RESPONSE_SUBCODE' );
	define( 'AN_RESPONSE_REASON_TEXT', 'RESPONSE_REASON_TEXT' );
	define( 'AN_RESPONSE_REASON_CODE', 'RESPONSE_REASON_CODE' );
	define( 'AN_RESPONSE_APPROVAL_CODE', 'RESPONSE_APPROVAL_CODE' );
	define( 'AN_RESPONSE_AVS_CODE', 'RESPONSE_AVS_CODE' );
	define( 'AN_RESPONSE_AVS_TEXT', 'RESPONSE_AVS_TEXT' );
	define( 'AN_RESPONSE_TRANSACTION_ID', 'RESPONSE_TRANSACTION_ID' );	
	define( 'AN_RESPONSE_APPROVED', 1 );
	define( 'AN_RESPONSE_DECLINED', 2 );
	define( 'AN_RESPONSE_ERROR', 3 );

	// authorize.net url
	define( 'AN_ENDPOINT_URL_TEST', 'https://certification.authorize.net/gateway/transact.dll' );
	define( 'AN_ENDPOINT_URL_LIVE', 'https://secure.authorize.net/gateway/transact.dll' );

/**
 * @connect_module_class_name CAuthorizeNetAIM
 * @package DynamicModules
 * @subpackage Payment
 */

class CAuthorizeNetAIM extends PaymentModule {
	
	var $type = PAYMTD_TYPE_CC;
	var $default_logo = 'http://www.webasyst.net/collections/design/payment-icons/authorizenet.gif';
	
	function _initVars(){
		
		parent::_initVars();
		$this->title 		= CAUTHORIZENETAIM_TTL;
		$this->description 	= CAUTHORIZENETAIM_DSCR;
		$this->sort_order 	= 2;

		$this->avs_results = array(
						0 => null,
						'A' => 'Address (Street) matches, ZIP does not',
						'B' => 'Address information not provided for AVS check',
						'E' => 'AVS error',
						'G' => 'Non-U.S. Card Issuing Bank',
						'N' => 'No Match on Address (Street) or ZIP',
						'P' => 'AVS not applicable for this transaction',
						'R' => 'Retry пїЅ System unavailable or timed out',
						'S' => 'Service not supported by issuer',
						'U' => 'Address information is unavailable',
						'W' => '9 digit ZIP matches, Address (Street) does not',
						'X' => 'Address (Street) and 9 digit ZIP match',
						'Y' => 'Address (Street) and 5 digit ZIP match',
						'Z' => '5 digit ZIP matches, Address (Street) does not'
					);
		
		$this->Settings = array( 
				"CONF_PAYMENTMODULE_AUTHNETAIM_LOGIN",
				"CONF_PAYMENTMODULE_AUTHNETAIM_TRANKEY",
				"CONF_PAYMENTMODULE_AUTHNETAIM_SAVE_CC_INFORMATION",
				"CONF_PAYMENTMODULE_AUTHNETAIM_TESTMODE",
				"CONF_PAYMENTMODULE_AUTHNETAIM_AUTHORIZATION_TYPE",
				"CONF_PAYMENTMODULE_AUTHNETAIM_WFSS_MERCHANT",
				"CONF_PAYMENTMODULE_AUTHNETAIM_ORDERSTATUS",
				"CONF_PAYMENTMODULE_AUTHNETAIM_DECLINE_ECHECK"
			);
	}

	function payment_form_html()
	{
		$post = isset($_SESSION["order4confirmation_post"]) ? $_SESSION["order4confirmation_post"] : array();
		$_SESSION["order4confirmation_post"] = array();
		$ccnumber = isset($post["mANAIM_cc_number"]) ? str_replace("\"","&quot;",$post["mANAIM_cc_number"]) : "";
		$ccholder = isset($post["mANAIM_cc_holder"]) ? str_replace("\"","&quot;",$post["mANAIM_cc_holder"]) : "";
		$cvv = isset($post["mANAIM_cvv"]) ? str_replace("\"","&quot;",$post["mANAIM_cvv"]) : "";
		$ccmonth = isset($post["mANAIM_exp_month"]) ? (int) $post["mANAIM_exp_month"] : 0;
		$ccyear = isset($post["mANAIM_exp_year"]) ? (int) $post["mANAIM_exp_year"] : 0;
		$phone = isset($post["mANAIM_phone"]) ? str_replace("\"","&quot;",$post["mANAIM_phone"]) : "";
		$fax = isset($post["mANAIM_fax"]) ? str_replace("\"","&quot;",$post["mANAIM_fax"]) : "";
		$company = isset($post["mANAIM_company"]) ? str_replace("\"","&quot;",$post["mANAIM_company"]) : "";
		$custtype = isset($post["mANAIM_custtype"]) ? $post["mANAIM_custtype"] : "";
		if ($custtype == "B")
		{
			$custtypeB = " selected";
			$custtypeI = "";
		}
		else
		{
			$custtypeI = " selected";
			$custtypeB = "";
		}
		$transtype = isset($post["TRANS_TYPE"]) ? str_replace("\"","&quot;",$post["TRANS_TYPE"]) : "";
		if ($transtype == "CC")
		{
			$transtypeCC = " checked";
			$transtypeECHECK = "";
		}
		else
		{
			$transtypeECHECK = " checked";
			$transtypeCC = "";
		}
		$acctype = isset($post["mANAIM_echeck_bank_acct_type"]) ? str_replace("\"","&quot;",$post["mANAIM_echeck_bank_acct_type"]) : "";
		if ($acctype == "CHECKING")
		{
			$acctypeCH = " selected";
			$acctypeSA = "";
		}
		else
		{
			$acctypeSA = " selected";
			$acctypeCH = "";
		}
		$echeckconf = isset($post["mANAIM_echeckconfirmation"]) ? str_replace("\"","&quot;",$post["mANAIM_echeckconfirmation"]) : "";
		if ($echeckconf == "taxid")
		{
			$echeckconfTI = " checked";
			$echeckconfDL = "";
		}
		else
		{
			$echeckconfDL = " checked";
			$echeckconfTI = "";
		}
		$aba_code = isset($post["mANAIM_echeck_aba_code"]) ? str_replace("\"","&quot;",$post["mANAIM_echeck_aba_code"]) : "";
		$bank_name = isset($post["mANAIM_echeck_bank_name"]) ? str_replace("\"","&quot;",$post["mANAIM_echeck_bank_name"]) : "";
		$bank_acct_num = isset($post["mANAIM_echeck_bank_acct_num"]) ? str_replace("\"","&quot;",$post["mANAIM_echeck_bank_acct_num"]) : "";
		$bank_acct_name = isset($post["mANAIM_echeck_bank_acct_name"]) ? str_replace("\"","&quot;",$post["mANAIM_echeck_bank_acct_name"]) : "";
		$taxid = isset($post["mANAIM_taxid"]) ? str_replace("\"","&quot;",$post["mANAIM_taxid"]) : "";
		$dlnum = isset($post["mANAIM_dlnum"]) ? str_replace("\"","&quot;",$post["mANAIM_dlnum"]) : "";
		$dlstate = isset($post["mANAIM_dlstate"]) ? str_replace("\"","&quot;",$post["mANAIM_dlstate"]) : "";
		$dldob = isset($post["mANAIM_dldob"]) ? str_replace("\"","&quot;",$post["mANAIM_dldob"]) : "";

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

		$text = "
		<TABLE CELLSPACING=0>

			<TR>
			<TD COLSPAN=2>

				<table border=0>
				<tr>
					<td>".CAUTHORIZENETAIM_TXT_4.":</td>
					<td>
					<select name=mANAIM_custtype>
					 <option value=\"B\"$custtypeB>".CAUTHORIZENETAIM_TXT_5."</option>
					 <option value=\"I\"$custtypeI>".CAUTHORIZENETAIM_TXT_6."</option>
					</select>
					</td>
				</tr>
				<tr>
					<td>".CAUTHORIZENETAIM_TXT_7.":</td>
					<td><input type=text name=mANAIM_company value=\"$company\"></td>
				</tr>
				<tr>
					<td>".CAUTHORIZENETAIM_TXT_8.":</td>
					<td><input type=text name=mANAIM_phone value=\"$phone\"><br>(please specify valid phone number where we can reach you for payment verification)</td>
				</tr>

				<tr>
					<td>".CAUTHORIZENETAIM_TXT_9.":</td>
					<td><input type=text name=mANAIM_fax value=\"$fax\"><br>".CAUTHORIZENETAIM_TXT_10."</td>
				</tr>
				
				</table>
			</TD>
			</TR>


			<TR class=\"background1\">
			<TD><INPUT TYPE=RADIO CHECKED NAME=TRANS_TYPE VALUE=\"CC\" onclick='JavaScript:mANAIN_paymenttogglehandler();' $transtypeCC></TD>
			<TD><b>".CAUTHORIZENETAIM_TXT_11."</b></TD>
			</TR>

			<TR><TD>&nbsp;</TD>
			<TD>
		<table border=0>
		<tr><td>".CAUTHORIZENETAIM_TXT_12.":</td><td><input type=text name=mANAIM_cc_number value=\"$ccnumber\"></td></tr>
			<!--<tr><td>".CAUTHORIZENETAIM_TXT_13.":</td><td><input type=text name=mANAIM_cc_holder value=\"$ccholder\"></td></tr>-->
		<tr><td>Expires:</td><td>
			<select name=mANAIM_exp_month>
			<option value=\"0\">".CAUTHORIZENETAIM_TXT_14."</option>
			$exp_months
			</select> /
			<select name=mANAIM_exp_year>
			<option value=\"0\">".CAUTHORIZENETAIM_TXT_15."</option>
			$exp_years
			</select>
		</td></tr>";

		$text .= "<tr><td>CVV:</td><td><input type=text name=mANAIM_cvv value=\"$cvv\"></td></tr>";

		$text .= "</table>
		
			</TD></TR>";
		if(!$this->_getSettingValue('CONF_PAYMENTMODULE_AUTHNETAIM_DECLINE_ECHECK')){
		$text .="<TR class=\"background1\">
			<TD><INPUT TYPE=RADIO NAME=TRANS_TYPE VALUE=\"ECHECK\" onclick='JavaScript:mANAIN_paymenttogglehandler();' $transtypeECHECK></TD>
			<TD><b>".CAUTHORIZENETAIM_TXT_16."</b></TD>
			</TR>

			<TR><TD>&nbsp;</TD>
			<TD>

<table border=0>
		<tr><td>".CAUTHORIZENETAIM_TXT_17."</td><td><input type=text name=mANAIM_echeck_aba_code value=\"$aba_code\"></td></tr>
		<tr><td>".CAUTHORIZENETAIM_TXT_18."</td><td><input type=text name=mANAIM_echeck_bank_name value=\"$bank_name\"></td></tr>
		<tr><td>".CAUTHORIZENETAIM_TXT_19."</td><td>
			<select name=mANAIM_echeck_bank_acct_type>
				<option value=\"CHECKING\"$acctypeCH>".CAUTHORIZENETAIM_TXT_20."</option>
				<option value=\"SAVINGS\"$acctypeSA>".CAUTHORIZENETAIM_TXT_21."</option>
			</select>
		</td></tr>
		<tr><td>".CAUTHORIZENETAIM_TXT_22."</td><td><input type=text name=mANAIM_echeck_bank_acct_num value=\"$bank_acct_num\"></td></tr>
		<tr><td>".CAUTHORIZENETAIM_TXT_23."</td><td><input type=text name=mANAIM_echeck_bank_acct_name value=\"$bank_acct_name\"></td></tr>

		";

		if ( (int) $this->_getSettingValue('CONF_PAYMENTMODULE_AUTHNETAIM_WFSS_MERCHANT') == 1)
		{
			$text .= "

				<tr>
					<td colspan=2 align=center>".CAUTHORIZENETAIM_TXT_24."</td>
				</tr>

				<tr>
					<td><input type=radio name=mANAIM_echeckconfirmation value=\"taxid\" $echeckconfTI onclick='JavaScript:mANAIN_wfecheckhandler();'></td>
					<td><input type=text name=mANAIM_taxid value=\"$taxid\">".CAUTHORIZENETAIM_TXT_25."</td>
				</tr>

				<tr>
					<td><input type=radio name=mANAIM_echeckconfirmation $echeckconfDL value=\"dl\" onclick='JavaScript:mANAIN_wfecheckhandler();'>".CAUTHORIZENETAIM_TXT_26."</td>
					<td><input type=text name=mANAIM_dlnum value=\"$dlnum\"></td>
				</tr>

				<tr>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ".CAUTHORIZENETAIM_TXT_27."</td>
					<td><input type=text name=mANAIM_dlstate value=\"$dlstate\"></td>
				</tr>

				<tr>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ".CAUTHORIZENETAIM_TXT_28."<br>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (YYYY-MM-DD)</td>
					<td><input type=text name=mANAIM_dldob value=\"$dldob\"></td>
				</tr>

			";
		}

		$text .= "

</table>

			</TD></TR>";
	}
		$text .="
		</TABLE>
		

		<script language='JavaScript'>

		";

		if ((int) $this->_getSettingValue('CONF_PAYMENTMODULE_AUTHNETAIM_WFSS_MERCHANT') == 1)
		{
			$text .= "

				function mANAIN_wfecheckhandler()
				{
					if (document.MainForm.mANAIM_echeckconfirmation[0].disabled == false)
					{
						if ( document.MainForm.mANAIM_echeckconfirmation[0].checked )
						{
							document.MainForm.mANAIM_taxid.disabled = false;
							document.MainForm.mANAIM_dlnum.disabled = true;
							document.MainForm.mANAIM_dlstate.disabled = true;
							document.MainForm.mANAIM_dldob.disabled = true;
						}
						else if ( document.MainForm.mANAIM_echeckconfirmation[1].checked )
						{
							document.MainForm.mANAIM_taxid.disabled = true;
							document.MainForm.mANAIM_dlnum.disabled = false;
							document.MainForm.mANAIM_dlstate.disabled = false;
							document.MainForm.mANAIM_dldob.disabled = false;
						}
					}
				}
				
				mANAIN_wfecheckhandler();

			";
		}

		$text .= "

			function mANAIN_paymenttogglehandler()
			{
				
				if ( document.MainForm.TRANS_TYPE[0].checked )
				{
					document.MainForm.mANAIM_cc_number.disabled = false;
					document.MainForm.mANAIM_exp_month.disabled = false;
					document.MainForm.mANAIM_exp_year.disabled = false;
					document.MainForm.mANAIM_cvv.disabled = false;

					document.MainForm.mANAIM_echeck_aba_code.disabled = true;
					document.MainForm.mANAIM_echeck_bank_name.disabled = true;
					document.MainForm.mANAIM_echeck_bank_acct_type.disabled = true;
					document.MainForm.mANAIM_echeck_bank_acct_num.disabled = true;
					document.MainForm.mANAIM_echeck_bank_acct_name.disabled = true;
		";


			if ((int) $this->_getSettingValue('CONF_PAYMENTMODULE_AUTHNETAIM_WFSS_MERCHANT') == 1)
			{
				$text .= "
					document.MainForm.mANAIM_taxid.disabled = true;
					document.MainForm.mANAIM_dlnum.disabled = true;
					document.MainForm.mANAIM_dlstate.disabled = true;
					document.MainForm.mANAIM_dldob.disabled = true;
					document.MainForm.mANAIM_echeckconfirmation[0].disabled = true;
					document.MainForm.mANAIM_echeckconfirmation[1].disabled = true;
				";
			}


		$text .= "
				}
				else if ( document.MainForm.TRANS_TYPE[1].checked )
				{
					document.MainForm.mANAIM_cc_number.disabled = true;
					document.MainForm.mANAIM_exp_month.disabled = true;
					document.MainForm.mANAIM_exp_year.disabled = true;
					document.MainForm.mANAIM_cvv.disabled = true;

					document.MainForm.mANAIM_echeck_aba_code.disabled = false;
					document.MainForm.mANAIM_echeck_bank_name.disabled = false;
					document.MainForm.mANAIM_echeck_bank_acct_type.disabled = false;
					document.MainForm.mANAIM_echeck_bank_acct_num.disabled = false;
					document.MainForm.mANAIM_echeck_bank_acct_name.disabled = false;

		";


			if ((int) $this->_getSettingValue('CONF_PAYMENTMODULE_AUTHNETAIM_WFSS_MERCHANT') == 1)
			{
				$text .= "
					document.MainForm.mANAIM_taxid.disabled = false;
					document.MainForm.mANAIM_dlnum.disabled = false;
					document.MainForm.mANAIM_dlstate.disabled = false;
					document.MainForm.mANAIM_dldob.disabled = false;
					document.MainForm.mANAIM_echeckconfirmation[0].disabled = false;
					document.MainForm.mANAIM_echeckconfirmation[1].disabled = false;
				";
			}


		$text .= "

				}

		";


			if ((int) $this->_getSettingValue('CONF_PAYMENTMODULE_AUTHNETAIM_WFSS_MERCHANT') == 1)
			{
				$text .= "mANAIN_wfecheckhandler();";
			}


		$text .= "

			}


			mANAIN_paymenttogglehandler();

		</script>
		

		";

		return $text;
	}

	function payment_process($order)
	{
		//verify input

		if ((!isset($_POST["mANAIM_company"]) || strlen( trim($_POST["mANAIM_company"]) ) == 0) && $_POST["mANAIM_custtype"]=="B")
		{
			return CAUTHORIZENETAIM_TXT_29;
		}

		if ((!isset($_POST["mANAIM_phone"]) || strlen( trim($_POST["mANAIM_phone"]) ) == 0))
		{
			return CAUTHORIZENETAIM_TXT_30;
		}

		if (isset($_POST["mANAIM_fax"]) && strlen( trim($_POST["mANAIM_fax"]) ) > 0)
		{
			$fax_number = $_POST["mANAIM_fax"];
		}

		if (!strcmp($_POST["TRANS_TYPE"], "CC"))
		{

			if (!isset($_POST["mANAIM_cc_number"]) || strlen( trim($_POST["mANAIM_cc_number"]) ) == 0)
			{
				return CAUTHORIZENETAIM_TXT_31;
			}

			if ((!isset($_POST["mANAIM_cvv"]) || strlen( trim($_POST["mANAIM_cvv"]) ) == 0))
			{
				return CAUTHORIZENETAIM_TXT_32;
			}

			if (!isset($_POST["mANAIM_exp_month"]) || ((int) $_POST["mANAIM_exp_month"]) == 0)
			{
				return CAUTHORIZENETAIM_TXT_33;
			}

			if (!isset($_POST["mANAIM_exp_year"]) || ((int) $_POST["mANAIM_exp_year"]) == 0)
			{
				return CAUTHORIZENETAIM_TXT_34;
			}

		}
		elseif(!$this->_getSettingValue('CONF_PAYMENTMODULE_AUTHNETAIM_DECLINE_ECHECK')) //ECHECK
		{

			if ((!isset($_POST["mANAIM_echeck_aba_code"]) || strlen( trim($_POST["mANAIM_echeck_aba_code"]) ) == 0))
			{
				return CAUTHORIZENETAIM_TXT_35;
			}

			if ((!isset($_POST["mANAIM_echeck_bank_acct_num"]) || strlen( trim($_POST["mANAIM_echeck_bank_acct_num"]) ) == 0))
			{
				return CAUTHORIZENETAIM_TXT_36;
			}

			if ((!isset($_POST["mANAIM_echeck_bank_name"]) || strlen( trim($_POST["mANAIM_echeck_bank_name"]) ) == 0))
			{
				return CAUTHORIZENETAIM_TXT_37;
			}

			if ((!isset($_POST["mANAIM_echeck_bank_acct_name"]) || strlen( trim($_POST["mANAIM_echeck_bank_acct_name"]) ) == 0))
			{
				return CAUTHORIZENETAIM_TXT_38;
			}

			if ((int) $this->_getSettingValue('CONF_PAYMENTMODULE_AUTHNETAIM_WFSS_MERCHANT') == 1) //DL xor tax ID
			{

				if (!strcmp($_POST["mANAIM_echeckconfirmation"], "taxid")) //verify tax ID input
				{
					if ((!isset($_POST["mANAIM_taxid"]) || strlen( trim($_POST["mANAIM_taxid"]) ) == 0))
					{
						return CAUTHORIZENETAIM_TXT_39;
					}
				}
				else //verify DL data input
				{

					if ((!isset($_POST["mANAIM_dlnum"]) || strlen( trim($_POST["mANAIM_dlnum"]) ) == 0))
					{
						return CAUTHORIZENETAIM_TXT_40;
					}

					if ((!isset($_POST["mANAIM_dlstate"]) || strlen( trim($_POST["mANAIM_dlstate"]) ) == 0))
					{
						return CAUTHORIZENETAIM_TXT_41;
					}

					if ((!isset($_POST["mANAIM_dldob"]) || strlen( trim($_POST["mANAIM_dldob"]) ) == 0))
					{
						return CAUTHORIZENETAIM_TXT_42;
					}

				}
			}

		}else{
			return CAUTHORIZENETAIM_CFG_DECLINE_ECHECK_TTL;
		}

		// data provided correctly. send it to Authorize.Net web site
		$variables = array();
		
		$variables['x_type'] = $this->_getSettingValue('CONF_PAYMENTMODULE_AUTHNETAIM_AUTHORIZATION_TYPE');
		$variables['x_login'] = Crypt::CCNumberDeCrypt( $this->_getSettingValue('CONF_PAYMENTMODULE_AUTHNETAIM_LOGIN'), NULL );
		$variables['x_tran_key'] = Crypt::CCNumberDeCrypt( $this->_getSettingValue('CONF_PAYMENTMODULE_AUTHNETAIM_TRANKEY'), NULL );
		$variables['x_method'] = $_POST["TRANS_TYPE"];
		$variables['x_recurring_billing'] = "NO";
		$variables['x_relay_response'] = "FALSE";

		$variables['x_test_request'] = ((int)$this->_getSettingValue('CONF_PAYMENTMODULE_AUTHNETAIM_TESTMODE') == 1) ? TRUE : FALSE;

		$variables['x_amount'] = RoundFloatValue($order["order_amount"] * $order["currency_value"]);
		$variables['x_currency_code'] = $order["currency_code"];

		//billing address
		$variables['x_first_name'] = $order["billing_info"]["first_name"];
		$variables['x_last_name'] = $order["billing_info"]["last_name"];
		$variables['x_address'] = $order["billing_info"]["address"];
		$variables['x_city'] = $order["billing_info"]["city"];

		if ($order["billing_info"]["state"] && strlen($order["billing_info"]["state"])>0)
			$variables['x_state'] = $order["billing_info"]["state"];
		else if ( $order["billing_info"]["zoneID"] && (int)$order["billing_info"]["zoneID"] != 0 )
			{
				$nm = znGetSingleZoneById( (int)$order["billing_info"]["zoneID"] );
				$variables['x_state'] = $nm['zone_name'];
			}
			else $variables['x_state'] = '';

		$variables['x_zip'] = $order["billing_info"]["zip"];

		if ($order["billing_info"]["country_name"] && strlen($order["billing_info"]["country_name"])>0 )
			$variables['x_country'] = $order["billing_info"]["country_name"];
		else if ($order["billing_info"]["countryID"] && (int)$order["billing_info"]["countryID"]>0)
			{
				$nm = cnGetCountryById((int)$order["billing_info"]["countryID"]);
				$variables['x_country'] = $nm['country_name'];
			}
			else $variables['x_country'] = '';



		//shipping address

		$variables['x_ship_to_first_name'] = $order["shipping_info"]["first_name"];
		$variables['x_ship_to_last_name'] = $order["shipping_info"]["last_name"];
		$variables['x_ship_to_address'] = $order["shipping_info"]["address"];
		$variables['x_ship_to_city'] = $order["shipping_info"]["city"];

		if ($order["shipping_info"]["state"] && strlen($order["shipping_info"]["state"])>0)
			$variables['x_ship_to_state'] = $order["shipping_info"]["state"];
		else if ( $order["shipping_info"]["zoneID"] && (int)$order["shipping_info"]["zoneID"] != 0 )
			{
				$zone_name = znGetSingleZoneById( (int)$order["shipping_info"]["zoneID"] );
				$variables['x_ship_to_state'] = $zone_name['zone_name'];
			}
			else $variables['x_ship_to_state'] = '';
			
		$variables['x_ship_to_zip'] = $order["shipping_info"]["zip"];

		if ($order["shipping_info"]["country_name"] && strlen($order["shipping_info"]["country_name"])>0 )
			$variables['x_ship_to_country'] = $order["shipping_info"]["country_name"];
		else if ($order["shipping_info"]["countryID"] && (int)$order["shipping_info"]["countryID"]>0)
			{
				$nm = cnGetCountryById((int)$order["shipping_info"]["countryID"]);
				$variables['x_ship_to_country'] = $nm['country_name'];
			}
			else $variables['x_ship_to_country'] = '';

		

		//get orders count
		$q = db_query("select MAX(`orderID`) from ".ORDERS_TABLE);
		$r = db_fetch_row($q);
		$r[0]++;
		$oid = $r[0];

		$variables['x_freight'] = RoundFloatValue($order["shipping_cost"] * $order["currency_value"]);
		$variables['x_tax_exempt'] = "NO";
		$variables['x_duty'] = 0;
		$variables['x_tax'] = RoundFloatValue($order["order_tax"] * $order["currency_value"]);
		$variables['x_po_num'] = $oid;
		$variables['x_invoice_num'] = $oid;
		$variables['x_description'] = "Order #".$oid;

		$variables['x_phone'] = $_POST["mANAIM_phone"];
		if (isset($fax_number))
		{
			$variables['x_fax'] = $fax_number;
		}
		$variables['x_company'] = $_POST["mANAIM_company"];
		if (!strcmp($_POST["TRANS_TYPE"],"ECHECK"))
		{
			$variables['x_customer_organization_type'] = $_POST["mANAIM_custtype"];
		}
//			$variables['x_invoice_num'] = $order["orderID"];
//			$variables['x_description'] = "Order #".$order["orderID"];
		$variables['x_email'] = $order["customer_email"];
		$variables['x_email_customer'] = TRUE;
		$variables['x_merchant_email'] = CONF_ORDERS_EMAIL;
		$variables['x_customer_ip'] = $_SERVER["REMOTE_ADDR"];
		$variables['x_cust_id'] = $order["customer_email"];

		//payment data
		if (!strcmp($_POST["TRANS_TYPE"],"CC"))
		{
			$variables['x_card_num'] = $_POST["mANAIM_cc_number"];
			$variables['x_exp_date'] = $_POST["mANAIM_exp_month"]."-".$_POST["mANAIM_exp_year"];
			$variables['x_card_code'] = $_POST["mANAIM_cvv"];
		}
		else //echeck
		{
			$variables['x_bank_aba_code'] = $_POST["mANAIM_echeck_aba_code"];
			$variables['x_bank_acct_num'] = $_POST["mANAIM_echeck_bank_acct_num"];
			$variables['x_bank_acct_type'] = $_POST["mANAIM_echeck_bank_acct_type"];
			$variables['x_bank_name'] = $_POST["mANAIM_echeck_bank_name"];
			$variables['x_bank_acct_name'] = $_POST["mANAIM_echeck_bank_acct_name"];
			$variables['x_echeck_type'] = "WEB";
		}

		if ( (!strcmp($_POST["TRANS_TYPE"],"CC") && $this->_getSettingValue('CONF_PAYMENTMODULE_AUTHNETAIM_AUTHORIZATION_TYPE') == "PRIOR_AUTH_CAPTURE") )
		{
			$variables['x_trans_id'] = "000000";
		}

		if ((int)$this->_getSettingValue('CONF_PAYMENTMODULE_AUTHNETAIM_WFSS_MERCHANT') == 1 && !strcmp($_POST["TRANS_TYPE"],"ECHECK")) //tax ID and/or driver's license info
		{
			if (!strcmp($_POST["mANAIM_echeckconfirmation"],"taxid"))
			{
				$variables['x_customer_tax_id'] = $_POST["mANAIM_taxid"];
			}
			else
			{
				$variables['x_drivers_license_num'] = $_POST["mANAIM_dlnum"];
				$variables['x_drivers_license_state'] = $_POST["mANAIM_dlstate"];
				$variables['x_drivers_license_dob'] = $_POST["mANAIM_dldob"];
			}
		}
		
		$response = $this->AN_transaction( $variables, $this->avs_results );

		if ( !is_array($response) ) //request hasn't been sent
		{
			return "Couldn't connect to the Authorize.Net payment gateway. ". $response;
		}
		else
		{
			if ( $response[AN_RESPONSE_CODE] != AN_RESPONSE_APPROVED ) //all ok - save order
			{
				return $response[AN_RESPONSE_REASON_TEXT];
			}
		}

		// success! :)
		
		return 1;
	}

	function after_processing_php($orderID){
		
		if($this->_getSettingValue('CONF_PAYMENTMODULE_AUTHNETAIM_ORDERSTATUS') != -1){
		
			ostSetOrderStatusToOrder($orderID, $this->_getSettingValue('CONF_PAYMENTMODULE_AUTHNETAIM_ORDERSTATUS'));
		}
		
		if ((int)$this->_getSettingValue('CONF_PAYMENTMODULE_AUTHNETAIM_SAVE_CC_INFORMATION') == 1 && isset($_POST["mANAIM_exp_month"]) && isset($_POST["mANAIM_cc_number"])) // save credit card data
		{
			$orderID = (int)$orderID;
			if ($orderID)
			{
				$expires = (string) $_POST["mANAIM_exp_month"];
				$expires.= (string) $_POST["mANAIM_exp_year"];
				$cvv = isset($_POST["mANAIM_cvv"]) ? $_POST["mANAIM_cvv"] : "";

				db_query("update ".ORDERS_TABLE." set cc_number = '".Crypt::CCNumberCrypt($_POST["mANAIM_cc_number"],null)."', cc_holdername = '".Crypt::CCHoldernameCrypt($_POST["mANAIM_cc_holder"],null)."', cc_expires = '".Crypt::CCExpiresCrypt($expires,null)."', cc_cvv = '".Crypt::CCNumberCrypt($cvv,null)."' where orderID=$orderID") or die (db_error());
			}
		}
		return "";
	}

	function _initSettingFields(){

		
		$this->SettingsFields['CONF_PAYMENTMODULE_AUTHNETAIM_ORDERSTATUS'] = array(
			'settings_value' 		=> '-1', 
			'settings_title' 			=> CAUTHORIZENETAIM_CFG_ORDERSTATUS_TTL, 
			'settings_description' 	=> CAUTHORIZENETAIM_CFG_ORDERSTATUS_DSCR, 
			'settings_html_function' 	=> 'setting_SELECT_BOX(PaymentModule::_getStatuses(),', 
			'sort_order' 			=> 1,
		);
		$this->SettingsFields['CONF_PAYMENTMODULE_AUTHNETAIM_LOGIN'] = array(
			'settings_value' 		=> '', 
			'settings_title' 			=> CAUTHORIZENETAIM_CFG_LOGIN_TTL, 
			'settings_description' 	=> CAUTHORIZENETAIM_CFG_LOGIN_DSCR, 
			'settings_html_function' 	=> 'setting_TEXT_BOX_SECURE(0,', 
			'sort_order' 			=> 1,
		);
		$this->SettingsFields['CONF_PAYMENTMODULE_AUTHNETAIM_TRANKEY'] = array(
			'settings_value' 		=> '', 
			'settings_title' 			=> CAUTHORIZENETAIM_CFG_TRANKEY_TTL, 
			'settings_description' 	=> CAUTHORIZENETAIM_CFG_TRANKEY_DSCR, 
			'settings_html_function' 	=> 'setting_TEXT_BOX_SECURE(0,', 
			'sort_order' 			=> 1,
		);
		$this->SettingsFields['CONF_PAYMENTMODULE_AUTHNETAIM_TESTMODE'] = array(
			'settings_value' 		=> '', 
			'settings_title' 			=> CAUTHORIZENETAIM_CFG_TESTMODE_TTL, 
			'settings_description' 	=> CAUTHORIZENETAIM_CFG_TESTMODE_DSCR, 
			'settings_html_function' 	=> 'setting_CHECK_BOX(', 
			'sort_order' 			=> 1,
		);
		$this->SettingsFields['CONF_PAYMENTMODULE_AUTHNETAIM_SAVE_CC_INFORMATION'] = array(
			'settings_value' 		=> '0', 
			'settings_title' 			=> CAUTHORIZENETAIM_CFG_SAVE_CC_INFORMATION_TTL, 
			'settings_description' 	=> CAUTHORIZENETAIM_CFG_SAVE_CC_INFORMATION_DSCR, 
			'settings_html_function' 	=> 'setting_CHECK_BOX(', 
			'sort_order' 			=> 1,
		);
		$this->SettingsFields['CONF_PAYMENTMODULE_AUTHNETAIM_AUTHORIZATION_TYPE'] = array(
			'settings_value' 		=> 'AUTH_ONLY', 
			'settings_title' 			=> CAUTHORIZENETAIM_CFG_AUTHORIZATION_TYPE_TTL, 
			'settings_description' 	=> CAUTHORIZENETAIM_CFG_AUTHORIZATION_TYPE_DSCR, 
			'settings_html_function' 	=> 'setting_AN_AUTHTYPE_SELECT('.$this->ModuleConfigID.')', 
			'sort_order' 			=> 1,
		);
		
		$this->SettingsFields['CONF_PAYMENTMODULE_AUTHNETAIM_WFSS_MERCHANT'] = array(
			'settings_value' 		=> '0', 
			'settings_title' 			=> CAUTHORIZENETAIM_CFG_WFSS_MERCHANT_TTL, 
			'settings_description' 	=> CAUTHORIZENETAIM_CFG_WFSS_MERCHANT_DSCR, 
			'settings_html_function' 	=> 'setting_CHECK_BOX(', 
			'sort_order' 			=> 1,
		);
		$this->SettingsFields['CONF_PAYMENTMODULE_AUTHNETAIM_DECLINE_ECHECK'] = array(
			'settings_value' 		=> '0', 
			'settings_title' 			=> CAUTHORIZENETAIM_CFG_DECLINE_ECHECK_TTL, 
			'settings_description' 	=> CAUTHORIZENETAIM_CFG_DECLINE_ECHECK_DSCR, 
			'settings_html_function' 	=> 'setting_CHECK_BOX(', 
			'sort_order' 			=> 1,
		);
	}
	
	function AN_sendData( $variables ) //send data to Authorize.Net server (CURL)
	{
		if ( !($ch = curl_init()) ){
			
			$this->_writeLogMessage(MODULE_LOG_CURL, 'Local error: '.translate("err_curlinit"));
			return translate("err_curlinit");
		}

		if ( curl_errno($ch) != 0 ){
			
			$this->_writeLogMessage(MODULE_LOG_CURL, 'Curl error: '.curl_errno($ch).' '.curl_error($ch));
			return translate("err_curlinit");
		}
		
		$vars = "";
		foreach( $variables as $key => $value )
			$vars .= "$key=$value&";

		$vars = substr($vars, 0, strlen($vars) - 1);
		$url = $this->_getSettingValue('CONF_PAYMENTMODULE_AUTHNETAIM_TESTMODE') ? AN_ENDPOINT_URL_TEST : AN_ENDPOINT_URL_LIVE;

		@curl_setopt( $ch, CURLOPT_URL, $url );
		@curl_setopt( $ch, CURLOPT_POST, 1);
		@curl_setopt( $ch, CURLOPT_POSTFIELDS, $vars );
		@curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		@curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
		@curl_setopt( $ch, CURLOPT_TIMEOUT, 10 );
		initCurlProxySettings($ch);

		$result = @curl_exec($ch);
		if ( curl_errno($ch) != 0){
			
			$this->_writeLogMessage(MODULE_LOG_CURL, 'Curl error: '.curl_errno($ch).' '.curl_error($ch));
			return translate("err_curlexec");
		}

		curl_close($ch);
		
		return $result;
	}

	function AN_transaction( $variables, $AN_avs_results )
	{
		$AN_error = null;

		$variables['x_version'] = '3.1';
		$variables['x_delim_data'] = 'True';
		$variables['x_delim_char'] = AN_DELIMCHAR;

		$response = $this->AN_sendData( $variables );
		if ( is_array($response) )
		{
			$AN_error = $response;
			return "Error processing transaction. ".implode('. ', $response);
		}

		$response = explode( AN_DELIMCHAR, $response );

		$result = array();
		$result[AN_RESPONSE_CODE] = $response[0];
		$result[AN_RESPONSE_SUBCODE] = $response[1];
		$result[AN_RESPONSE_REASON_CODE] = $response[2];
		$result[AN_RESPONSE_REASON_TEXT] = $response[3];
		$result[AN_RESPONSE_APPROVAL_CODE] = $response[4];
		$result[AN_RESPONSE_AVS_CODE] = $response[5];
		$result[AN_RESPONSE_AVS_TEXT] = $AN_avs_results[$response[5]];

		$result[AN_RESPONSE_TRANSACTION_ID] = $response[6];

		return $result;
	}
	
	function CAuthorizeNetAIM($_ModID = 0){
		
		parent::__construct($_ModID);
		$this->update();
	}
	
	/*
	add new constants
	*/
	function update(){
		
		$this->_initSettingFields();
		
		if(!$this->ModuleConfigID)return 0;
		
		foreach ($this->Settings as $_SettingName){
			
			if(defined($_SettingName)) continue;
			$orName = preg_replace('/\_[0-9]*$/','', $_SettingName);
			$sql = "
				INSERT INTO ".SETTINGS_TABLE."
				(
					settings_groupID, settings_constant_name, 
					settings_value, 
					settings_title, 
					settings_description, 
					settings_html_function, 
					sort_order
				)
				VALUES (
					".settingGetFreeGroupId().", '".$_SettingName."',
					'".(isset($this->SettingsFields[$orName]['settings_value'])?$this->SettingsFields[$orName]['settings_value']:'')."',
					'".(isset($this->SettingsFields[$orName]['settings_title'])?$this->SettingsFields[$orName]['settings_title']:'')."',
					'".(isset($this->SettingsFields[$orName]['settings_description'])?$this->SettingsFields[$orName]['settings_description']:'')."',
					'".(isset($this->SettingsFields[$orName]['settings_html_function'])?$this->SettingsFields[$orName]['settings_html_function']:'')."',
					'".(isset($this->SettingsFields[$orName]['sort_order'])?$this->SettingsFields[$orName]['sort_order']:'')."'
				)";
			db_query($sql)	or die (db_error());
			$Is = true;
		}
		if(isset($Is))Redirect(set_query('__tt='));
	}
}
?>