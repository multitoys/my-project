<?php
/**
 * @connect_module_class_name CSecurePay
 * @package DynamicModules
 * @subpackage Payment
 */

class CSecurePay extends PaymentModule{

	var $type = PAYMTD_TYPE_CC;
	var $language = 'eng';
	
	var $avsResponses;
	
	function _initVars(){
		
		parent::_initVars();
		$this->title 		= CSECUREPAY_TTL;
		$this->description 	= CSECUREPAY_DSCR;
		$this->sort_order 	= 2;

		$this->Settings = array( 
				'CONF_SECUREPAY_MERCH_ID',
				'CONF_SECUREPAY_USD',
				'CONF_SECUREPAY_AVS',
			);
			
		$this->avsResponses = array(
			'A' => CSECURE_AVS_A,
			'E' => CSECURE_AVS_E,
			'G' => CSECURE_AVS_G,
			'N' => CSECURE_AVS_N,
			'R' => CSECURE_AVS_R,
			'S' => CSECURE_AVS_S,
			'U' => CSECURE_AVS_U,
			'W' => CSECURE_AVS_W,
			'X' => CSECURE_AVS_X,
			'Y' => CSECURE_AVS_Y,
			'Z' => CSECURE_AVS_Z,
		);
	}

	function _initSettingFields(){

		$this->SettingsFields['CONF_SECUREPAY_MERCH_ID'] = array(
			'settings_value' 		=> '', 
			'settings_title' 		=> CSECUREPAY_CFG_MERCH_ID_TTL, 
			'settings_description' 	=> CSECUREPAY_CFG_MERCH_ID_DSCR, 
			'settings_html_function'=> 'setting_TEXT_BOX(0,', 
			'sort_order' 			=> 1,
		);
		$this->SettingsFields['CONF_SECUREPAY_USD'] = array(
			'settings_value' 		=> 0, 
			'settings_title' 		=> CSECUREPAY_CFG_USD_TTL, 
			'settings_description' 	=> CSECUREPAY_CFG_USD_DSCR, 
			'settings_html_function'=> 'setting_CURRENCY_SELECT(', 
			'sort_order' 			=> 2,
		);
		$this->SettingsFields['CONF_SECUREPAY_AVS'] = array(
			'settings_value' 		=> 0, 
			'settings_title' 		=> CSECUREPAY_CFG_AVS_TTL, 
			'settings_description' 	=> CSECUREPAY_CFG_AVS_DSCR, 
			'settings_html_function'=> 'setting_RADIOGROUP(CSecurePay::_getAVS(),', 
			'sort_order' 			=> 3,
		);
	}
	
	function payment_process($order){
		
		if(isset($_POST['sp_select_country'])){
			
			xSaveData('CSECUREPAY_INFO', $_POST);
			return false;
		}
		$debug=0;
		$timeout=20;
		$objSPCharge = new SecurePayCharge($debug , $timeout );
		
		$objSPCharge->merchID=$this->_getSettingValue('CONF_SECUREPAY_MERCH_ID'); #enter securepay id
		$objSPCharge->amount=RoundFloatValue($this->_convertCurrency($order['order_amount'],0,$this->_getSettingValue('CONF_SECUREPAY_USD')));
		$objSPCharge->custName=$_POST['sp_name'];
		$objSPCharge->street=$_POST['sp_street'];
		$objSPCharge->city=$_POST['sp_city'];
		$objSPCharge->zip=$_POST['sp_zip'];
		$objSPCharge->state = znGetSingleZoneById($_POST['sp_state']);
		$objSPCharge->state = $objSPCharge->state['zone_code'];
		$objSPCharge->country = cnGetCountryById($_POST['sp_country']);
		$objSPCharge->country = $objSPCharge->country['country_iso_3'];
		$objSPCharge->custEmail=$order['customer_email'];
		$objSPCharge->avsreq=$this->_getSettingValue('CONF_SECUREPAY_AVS');
		$objSPCharge->transType="SALE";
		$objSPCharge->cvv2="123";
		$objSPCharge->ccMethod="DATAENTRY";
		
		$objSPCharge->ccNum=$_POST['sp_cc_number'];
		$objSPCharge->month=$_POST['sp_month'];
		$objSPCharge->year=$_POST['sp_year'];
		
		$objSPCharge->SubmitCharge();
		if($objSPCharge->returnCode == 'N'){
			
			xSaveData('CSECUREPAY_INFO', $_POST);
			return 
		"<div style='padding-left:15px;font-weight:normal!important;'>".CSECURE_RESPONSE_FLD_APPROVAL_NUMBER.": " . $objSPCharge->approvNum . "<BR>".
		CSECURE_RESPONSE_FLD_CARD_RESPONSE.": " . $objSPCharge->cardResponse . "<BR>".
		($objSPCharge->avsResponse&&isset($this->avsResponses[$objSPCharge->avsResponse])?CSECURE_RESPONSE_FLD_AVS_DATA."AVS Data: " .$this->avsResponses[$objSPCharge->avsResponse]. "<BR>":'').
		'</div>'
			;
		}
		return 1;
	}
	
	function payment_form_html($_rawParams = null){
		
		global $rMonths;
		
		if(isset($_rawParams['BillingAddressID']))$_rawParams = regGetAddress($_rawParams['BillingAddressID']);
		
		if(isset($_rawParams['last_name'])&&isset($_rawParams['first_name']))
			$_Params['sp_name'] = $_rawParams['last_name'].' '.$_rawParams['first_name'];
		if(isset($_rawParams['address'])) 
			$_Params['sp_street'] = $_rawParams['address']; 
		if(isset($_rawParams['city'])) 
			$_Params['sp_city'] = $_rawParams['city'];
		if(isset($_rawParams['zoneID'])) 
			$_Params['sp_state'] = $_rawParams['zoneID']; 
		if(isset($_rawParams['zip'])) 
			$_Params['sp_zip'] = $_rawParams['zip']; 
		if(isset($_rawParams['countryID'])) 
			$_Params['sp_country'] = $_rawParams['countryID'];
			 
		if(isset($_POST['sp_select_country']))
			$_Params = $_POST;
			
		if(xDataExists('CSECUREPAY_INFO')){
			
			$_Params = xPopData('CSECUREPAY_INFO');
		}
		$CurrYear = intval(date('y')); 
		$ExpYears = '';
		for($_Y = 0; $_Y<10; $_Y++){
		
			$_Selected = isset($_Params['sp_year'])?($_Params['sp_year']==($CurrYear+$_Y)):0;
			$ExpYears .= '<option value="'.sprintf('%02d',$CurrYear+$_Y).'"'.($_Selected?' selected="selected"':'').'>'.(date('Y')+$_Y).'</option>';
		}
		
		$ExpMonths = '';
		for($_M = 1; $_M<=12; $_M++){
		
			$_Selected = isset($_Params['sp_month'])?($_Params['sp_month']==($_M)):0;
			$ExpMonths .= '<option value="'.sprintf('%02d',$_M).'"'.($_Selected?' selected="selected"':'').'>'.$rMonths[$_M].'</option>';
		}
		
		$slctCountries = '<option value="0"></option>';
		$CountriesNum = 0;
		$Countries = cnGetCountries(array('raw data'=>true), $CountriesNum );
		foreach ($Countries as $_Country){
			
			$_countrySelected = false;
			if(isset($_Params['sp_country']))$_countrySelected = ($_Params['sp_country'] == $_Country['countryID']);
			$slctCountries .= '<option value="'.$_Country['countryID'].'"'.($_countrySelected?' selected="selected"':'').'>'.xEscapeSQLstring($_Country['country_name']).'</option>';
		}
		
		
		$slctStates = '<option value="0"></option>';
		if(isset($_Params['sp_country'])){
			$States = znGetZones($_Params['sp_country'] );
		}else{
			
			$States = array();
		}
		foreach ($States as $_State){
			
			$_stateSelected = false;
			if(isset($_Params['sp_state']))$_stateSelected = ($_Params['sp_state'] == $_State['zoneID']);
			$slctStates .= '<option value="'.$_State['zoneID'].'"'.($_stateSelected?' selected="selected"':'').'>'.xEscapeSQLstring($_State['zone_name']).'</option>';
		}
		
		$_Params = xEscapeSQLstring($_Params);
		$html = '
			<fieldset style="border-width:1px;border-color:#'.CONF_DARK_COLOR.';border-style:solid;width:60%;">
			<legend>
				<strong>'.CSECURE_FORM_TTL.'</strong>
			</legend>
			<table>
				<tr><td>'.CSECURE_FORM_SP_NAME_TTL.':</td>
					<td><input type="text" name="sp_name" value="'.(isset($_Params['sp_name'])?$_Params['sp_name']:'').'" /></td></tr>
				<tr><td>'.CSECURE_FORM_SP_CC_NUMBER_TTL.':</td>
					<td><input type="text" name="sp_cc_number" value="'.(isset($_Params['sp_cc_number'])?$_Params['sp_cc_number']:'').'" /></td></tr>
				<tr><td>'.CSECURE_FORM_SP_MONTH_TTL.':</td>
					<td><select name="sp_month">'.$ExpMonths.'</select></td></tr>
				<tr><td>'.CSECURE_FORM_SP_YEAR_TTL.':</td>
					<td><select name="sp_year">'.$ExpYears.'</select></td></tr>
				<tr><td>'.CSECURE_FORM_SP_STREET_TTL.':</td>
					<td><input type="text" name="sp_street" value="'.(isset($_Params['sp_street'])?$_Params['sp_street']:'').'" /></td></tr>
				<tr><td>'.CSECURE_FORM_SP_CITY_TTL.':</td>
					<td><input type="text" name="sp_city" value="'.(isset($_Params['sp_city'])?$_Params['sp_city']:'').'" /></td></tr>
				<tr><td>'.CSECURE_FORM_SP_STATE_TTL.':</td>
					<td><select name="sp_state">'.$slctStates.'</select></td></tr>
				<tr><td>'.CSECURE_FORM_SP_ZIP_TTL.':</td>
					<td><input type="text" name="sp_zip" value="'.(isset($_Params['sp_zip'])?$_Params['sp_zip']:'').'" /></td></tr>
				<tr><td colspan="2">'.CSECURE_FORM_SP_COUNTRY_TTL.':<br /><br />
					<select name="sp_country">'.$slctCountries.'</select>&nbsp;<input type="submit" value="'.xEscapeSQLstring(translate("btn_select")).'" name="sp_select_country" /></td></tr>
			</table>
			</fieldset>
		';
		return $html;
	}
	
	function _getAVS(){

		return array(
			array('title'=>CSECUREPAY_AVS0_DSCR,'value'=>0),
			array('title'=>CSECUREPAY_AVS1_DSCR,'value'=>1),
			array('title'=>CSECUREPAY_AVS1_DSCR,'value'=>1),
			array('title'=>CSECUREPAY_AVS2_DSCR,'value'=>2),
			array('title'=>CSECUREPAY_AVS3_DSCR,'value'=>3),
			array('title'=>CSECUREPAY_AVS4_DSCR,'value'=>4),
			);
	}
}

;
class SecurePayCharge{
	
	####################################
	## Class Properties
	####################################
	
	var $custName; 			#The name on the credit card.		
	var $street; 			#The street address in the billing address for the credit card.		
	var $city; 				#The City in the billing address for the credit card.		
	var $state; 			#The State in the billing address for the credit card.		
	var $zip;				#The Zip code in the billing address for the credit card.		
	var $country;			#The Country in the billing address for the credit card.
	var $avsreq; 			#AVS type being requested.		
	var $custEmail; 		#The email of the customer making the purchase.
	var $merchID; 			#A unique Merchant identifier assigned to the Merchant by SecurePay.Com.		
	
	var $ccNum; 			#The account number of the credit card. No dashes or spaces.		
	var $month; 			#The 2 digit designation for the month of expiration on the card.
								#Example: 01 is January, 12 is December.		
	var $year;   			#The 2 digit designation for the year of expiration on the card.
								#Example: 00 is 2000, 01 is 2001, 02 is 2002.
	var $recurring;			#Add this transaction to recurring database. Valid values 'YES' or 'NO'
	var $recamount;			#Recurring Amount, as it may be different that initial charge
								#Follow this format xxxx.xx. No dollar sign eg 32.24
	var $timeframe;			#Time frame for recurring activity:
	var $cvv2;
							/*	
								Values:  
										  "MONTH" - monthly [default]
										  "WEEK" - weekly
										  "BIMONTH" - twice a month
										  "QUARTER"  - once a quarter
										  "6MONTH" - semi annually
										  "YEAR" - annually
										  "1AND15" - first and fifteenth of every month
										  "MANUAL" - save this transaction and you do it when you want
								
								Any time frame value that does not match the above list
								is interpreted as a monthly recurring entry.
						    */
	
	
	var $transType; 		#The type of transaction being processed. This variable can have two values. 
								/**************************************************************
									transType Explanation
										SALE - Indicates a charge to be placed against 
												a credit card account.
		
										CREDIT - Indicates a refund or "credit" to be 
												placed against a credit card account.
		
										PREAUTH - Indicates a pre-authorization on a 
												Credit Card. This is a temporary block on
												an Amount submitted by the Merchant. This 
												is not a qualified transaction and if not 
												closed with a FORCE transaction it will 
												release the block after 5-7 days depending
												on the card issuer. This can have a negative 
												impact on the funds availability of the 
												card holder and should be used appropriately.
		
										FORCE - Indicates a closure of a previously 
												PREAUTH (pre-authorized) transaction. A 
												FORCE  requires all data fields submitted 
												with the original pre-authorization plus the 
												additional variable named APPROVNUMBER. The 
												value of this field is the original 
												transaction Approval Number returned as the 
												value of Approv_Num.
			
										VOID - Indicates a reversal of a transaction conducted
												on the same business day. An additional variable 
												must be passed with a VOID transaction. That 
												variable is the original record number assigned 
												to the transaction. This number is passed with 
												each successful transaction as the "VoidRecNum".
										**************************************************************/

	var $ccMethod; 			#The type of transaction being presented. Either "DataEntry" or "Swiped"
	var $amount; 			#The amount of the charge to be processed. Follow this format xxxx.xx. No dollar sign eg 32.24
	var $swipeData; 		#SwipeData is the Magnetic Stripe Data from Track 1 on the Credit Card. Blank if not swiped transaction.


	#OPTIONAL
	var $comment1; 			#An optional field used by the Merchant to aid in managing transactions.
	var $comment2; 			#An second optional field used by the Merchant to aid in managing transactions.
	var $voidRecNum; 		#The VoidRecNum is passed back with the Approval Code and other return variables on each approved transaction. If the transaction is to be reversed on the same business day as the original transaction a VOID transaction type may be initiated provided the original Record Number is passed to SecurePay with all other original transaction data. The value of the Record Number to be passed to SecurePay is the value of the "VoidRecNum" assigned to the original transaction. The name of both the receiving variable and send variable is the same.

	var $origApprovNumber; 	#This is passed as a required variable when conducting a FORCE transaction. The value of the variable ApprovNumber is the original Approval Code from the pre-authorization. It is passed back in the response string from the transaction request sent to the COM object.

	
	#RETURN Variables
	
	var $returnCode;       	#Y= approved, N= host decline
	var $approvNum;        	#The Approv_Num can have 2 possible responses
						        #1. "XXXXXX", the Approval number of the transaction.
								#2. "NONE", When a transaction is declined.
	var $cardResponse; 		#Verbose text from processor: APPROVED, INVALID CARD NUMBER. 
							#The Card_Response can have many possible values. These are 
							#usually verbose descriptions of why a card was declined or 
							#the word "Approved" when transactions are accepted for processing.
	var $avsResponse;      #See documentation.
	var $recordNumber;     #Internal Securepay record number of transaciton used for 
								#transaction identification purposes. Uniquely 
								#identifies transaction.
	
	
	var $_errorNo;
	var $_errorData;
	var $_timeout;
	var $_debug;
	var $_sURL;
	

	####################################
	## END - Class Properties
	####################################
	
	/*
		Function: Constructor
		Purpose: Strat class off with proper settings
		Description: Pass any valid numeric value to set a timeout
						value other than 120 seconds
		Parameters: newTimeOut - Timeout of HTTP call
			
	*/
	function SecurePayCharge($testingOnly=0,$timeout=120,$URL=""){
		if(is_numeric($timeout))
			$this->_timeout=$timeout;
		else
			$this->_timeout=120;
		$this->returnCode="N";
		$this->approvNum="NOT APPROVED";
		$this->cardResponse="NOT APPROVED - ERROR";
		$this->avsResponse="NO DATA";
		$this->recordNumber="-1";
		$this->recurring="NO";
		$this->timeframe="MONTH";
		$this->_debug=$testingOnly;
		if($URL=="")
			$this->_sURL="https://www.securepay.com/secure1/index.asp";
		else
			$this->_sURL=$URL;
	}//END Constructor SecurePayCharge
	
	/*
		Function: BuildPostData
		Purpose: String together all passed variables
		Parameters: NONE
	*/
	function BuildPostData(){			
		$postData= "merch_id=" . urlencode($this->merchID);
		$postData.= "&amount=" . urlencode($this->amount);
		$postData.= "&name=" . urlencode($this->custName);
		$postData.= "&street=" . urlencode($this->street);
		$postData.= "&city=" . urlencode($this->city);
		$postData.= "&state=" . urlencode($this->state);
		$postData.= "&zip=" . urlencode($this->zip);
		$postData.= "&country=" . urlencode($this->country);
		$postData.= "&email=" . urlencode($this->custEmail);
		$postData.= "&avsreq=" . urlencode($this->avsreq);
		$postData.= "&tr_type=" . urlencode($this->transType);
		$postData.= "&cc_method=" . urlencode($this->ccMethod);
		$postData.= "&cvv2=" . urlencode($this->cvv2);
		
		if(strtoupper($this->recurring)=="YES"){
			$postData.= "&recurring=" . urlencode($this->recurring);
			$postData.= "&time_frame=" . urlencode($this->timeframe);
			$postData.= "&rec_amount=" . urlencode($this->recamount);
		}
		
		if(strtoupper($this->ccMethod)!="DATAENTRY"){
			$postData.= "&swipeData=" . urlencode($this->swipeData);
		}
		else{
			$postData.= "&cc_number=" . urlencode($this->ccNum);
			$postData.= "&month=" . urlencode($this->month);
			$postData.= "&year=" . urlencode($this->year);
		}
		if(strtoupper($this->transType)=="VOID"){				
			$postData.= "&voidRecNum=" . urlencode($this->voidRecNum);
		}
		if(strtoupper($this->transType)=="FORCE"){
			$postData.= "&app_num=" . urlencode($this->origApprovNumber);
		}
		
		$postData.= "&comment1=" . urlencode($this->comment1);
		$postData.= "&comment2=" . urlencode($this->comment2);
		if($this->_debug)echo "<BR><BR><b>Data posted to server:</b><BR><BR>$postData";
		return $postData;
	}// END BuildPostData		
	
	/*
		Function: TransmitHTTPRequest
		Purpose: Make actual post request wait on and return the result
		Parameters: postRequest
	*/
	function TransmitHTTPRequest($postRequest){	
		$ch=curl_init($this->_sURL);
		if(!$ch)die(sprintf('Error1 [%d]: %s',curl_errno($ch),curl_error($ch)));
		
		curl_setopt($ch, CURLOPT_POST, 1); 
		curl_setopt($ch, CURLOPT_POSTFIELDS,$postRequest);
		curl_setopt($ch, CURLOPT_HEADER,0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);	
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt( $ch, CURLOPT_TIMEOUT, 20 );
		initCurlProxySettings($ch);
		
		$http_response = curl_exec($ch);
		
		if(!$http_response)die(sprintf('Error2 [%d]: %s',curl_errno($ch),curl_error($ch)));
		curl_close($ch);
		
		if($this->_debug)echo "<BR><BR><b>Data received from server:</b><BR><BR>$http_response";
		return $http_response;
	} //END TransmitHTTPRequest
	
	/*
		Function: InterpretResponse
		Purpose: interprest result of http call
		Parameters: the http response
	*/
	function InterpretResponse($http_response){
		
		if($http_response=="" || substr_count($http_response,",")!=5){
			$this->errorData="Unrecognizable response received.";
			$this->errorNo="400";
		}
		else{
			$retval=explode(",",$http_response);
			$this->returnCode=$retval[0];
			$this->approvNum=$retval[1];
			$this->cardResponse=$retval[2];
			$this->avsResponse=$retval[3];
			$this->recordNumber=$retval[4];
		}
	}
	
	/*
		Function: SubmitCharge
		Purpose: Manage Charge
		Parameters: NONE
	*/
	function SubmitCharge(){
		$this->InterpretResponse($this->TransmitHTTPRequest($this->BuildPostData()));
	}
	
} //END Class SecurePay
?>
