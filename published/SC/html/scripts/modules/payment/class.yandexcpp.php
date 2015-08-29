<?php
/**
 * Модуль интеграции с платежной системой Яндекс.Деньги Центр Приема Платежей (ЦПП)
 * @link http://money.yandex.ru/doc.xml?id=157411
 * @connect_module_class_name YandexCPP
 * @package DynamicModules
 * @subpackage Payment
 */
class YandexCPP extends PaymentModule {

	var $type = PAYMTD_TYPE_ONLINE;
	var $language = 'rus';
	var $default_logo = 'http://www.webasyst.net/collections/design/payment-icons/yandexmoney.gif';

	function _initVars(){
			
		parent::_initVars();
		$this->title = YANDEXCPP_TTL;
		$this->description = YANDEXCPP_DSCR;
		$this->sort_order = 1;
			
		$this->Settings = array(
				'CONF_YANDEXCPP_SHOPID',
				'CONF_YANDEXCPP_BANKID',
				'CONF_YANDEXCPP_TARGETBANKID',
				'CONF_YANDEXCPP_MODE',
				'CONF_YANDEXCPP_TARGETCURRENCY',
				'CONF_YANDEXCPP_TRANSCURRENCY',
				'CONF_YANDEXCPP_SHOPPASSWORD',
			);
	}

	function _initSettingFields(){

		$this->SettingsFields['CONF_YANDEXCPP_SHOPID'] = array(
			'settings_value' 		=> '',
			'settings_title' 			=> YANDEXCPP_CFG_SHOPID_TTL,
			'settings_description' 	=> YANDEXCPP_CFG_SHOPID_DSCR,
			'settings_html_function' 	=> 'setting_TEXT_BOX(0,',
			'sort_order' 			=> 1,
		);
		$this->SettingsFields['CONF_YANDEXCPP_BANKID'] = array(
			'settings_value' 		=> '1001',
			'settings_title' 			=> YANDEXCPP_CFG_BANKID_TTL,
			'settings_description' 	=> YANDEXCPP_CFG_BANKID_DSCR,
			'settings_html_function' 	=> 'setting_TEXT_BOX(0,',
			'sort_order' 			=> 1,
		);
		$this->SettingsFields['CONF_YANDEXCPP_TARGETBANKID'] = array(
			'settings_value' 		=> '1001',
			'settings_title' 			=> YANDEXCPP_CFG_TARGETBANKID_TTL,
			'settings_description' 	=> YANDEXCPP_CFG_TARGETBANKID_DSCR,
			'settings_html_function' 	=> 'setting_TEXT_BOX(0,',
			'sort_order' 			=> 1,
		);
		$this->SettingsFields['CONF_YANDEXCPP_MODE'] = array(
			'settings_value' 		=> 'live',
			'settings_title' 			=> YANDEXCPP_CFG_MODE_TTL,
			'settings_description' 	=> YANDEXCPP_CFG_MODE_DSCR,
			'settings_html_function' 	=> 'setting_SELECT_BOX(YandexCPP::_getModes(),',
			'sort_order' 			=> 1,
		);
		$this->SettingsFields['CONF_YANDEXCPP_TARGETCURRENCY'] = array(
			'settings_value' 		=> '643',
			'settings_title' 			=> YANDEXCPP_CFG_TARGETCURRENCY_TTL,
			'settings_description' 	=> YANDEXCPP_CFG_TARGETCURRENCY_DSCR,
			'settings_html_function' 	=> 'setting_SELECT_BOX(YandexCPP::_getTargetCurrencies(),',
			'sort_order' 			=> 1,
		);
		$this->SettingsFields['CONF_YANDEXCPP_TRANSCURRENCY'] = array(
			'settings_value' 		=> '',
			'settings_title' 			=> YANDEXCPP_CFG_TRANSCURRENCY_TTL,
			'settings_description' 	=> YANDEXCPP_CFG_TRANSCURRENCY_DSCR,
			'settings_html_function' 	=> 'setting_CURRENCY_SELECT(',
			'sort_order' 			=> 1,
		);
		$this->SettingsFields['CONF_YANDEXCPP_SHOPPASSWORD'] = array(
			'settings_value' 		=> '',
			'settings_title' 			=> YANDEXCPP_CFG_SHOPPASSWORD_TTL,
			'settings_description' 	=> YANDEXCPP_CFG_SHOPPASSWORD_DSCR,
			'settings_html_function' 	=> 'setting_TEXT_BOX(0,',
			'sort_order' 			=> 1,
		);
	}

	function after_processing_html( $orderID ){

		$res = '';
			
		$order = ordGetOrder( $orderID );
			
		$order_amount = RoundFloatValue(PaymentModule::_convertCurrency($order['order_amount'],0,$this->_getSettingValue('CONF_YANDEXCPP_TRANSCURRENCY')));

		$post_1=array(
			'TargetCurrency' => $this->_getSettingValue('CONF_YANDEXCPP_TARGETCURRENCY'),
			'currencyID' => $this->_getSettingValue('CONF_YANDEXCPP_TARGETCURRENCY'),
			'wbp_InactivityPeriod' => '2',
			'wbp_ShopAddress' => 'wn1.paycash.ru:8828',
			'wbp_ShopEncryptionKey' => 'hAAAEicBAHV6wr3pySqE3thhKHbjvyf4XCMxKc2nSj2u8K46i0dMIP8Wd2KJHkZuhGMWZGmYAp6wsb3XqZW5HKVpamQt+t9rwGNsSaVfeZb9DM5aodCpIMHhLA8gGPDIiG4+Q15X/7Zm3MJNGavZ8+eWAnlvS1M7c6eeLTNJ0CKIYd1yHXfU',
			'wbp_ShopKeyID' => '4060341894',
			'wbp_Version' => '1.0',
			'wbp_CorrespondentID' => '8994748E663DE6B3C68D2D9931B079C74789D4B4',
			'BankID' => $this->_getSettingValue('CONF_YANDEXCPP_BANKID'),
			'TargetBankID' => $this->_getSettingValue('CONF_YANDEXCPP_TARGETBANKID'),
			'PaymentTypeCD' => 'PC',
			'ShopID' => $this->_getSettingValue('CONF_YANDEXCPP_SHOPID'),
	
			'CustomerNumber' => $orderID,
			'Sum' => $order_amount,
			'CustName' => $order['shipping_firstname'].' '.$order['shipping_lastname'],
			'CustAddr' => '',
			'CustEMail' => $order['customer_email'],
	
			'OrderDetails' => '',
		);

		$order_content = ordGetOrderContent( $orderID );
		foreach ($order_content as $item){

			$post_1['OrderDetails'] .= $item['name']."\r\n";
		}

			
		$implAddress = array('shipping_country', 'shipping_state', 'shipping_zip', 'shipping_city', 'shipping_address');
			
		foreach ($implAddress as $k){

			if($order[$k]){
				$post_1['CustAddr'] .= ', '.$order[$k];
			}
		}
		$post_1['CustAddr'] = substr($post_1['CustAddr'], 1);
			
		$hidden_fields_html = '';
		reset($post_1);

		while(list($k,$v)=each($post_1)){
    
			$hidden_fields_html .= '<input type="hidden" name="'.xHtmlSpecialChars($k).'" value="'.xHtmlSpecialChars($v).'" />'."\n";
		}

		$processing_url = $this->_getSettingValue('CONF_YANDEXCPP_MODE')=='test'?'http://demomoney.yandex.ru/select-wallet.xml':'http://money.yandex.ru/select-wallet.xml';

		$res = '
				<form method="post" action="'.xHtmlSpecialChars($processing_url).'" style="text-align:center;">
					'.$hidden_fields_html.'
					<input type="submit" value="'.xHtmlSpecialChars(YANDEXCPP_TXT_PROCESS).'" />
				</form>
				';
			
		return $res;
	}

	function _getModes(){
		return YANDEXCPP_TXT_TESTMODE.':test,'.YANDEXCPP_TXT_LIVEMODE.':live';
	}

	function _getTargetCurrencies(){
		return YANDEXCPP_TXT_RUR.':643,'.YANDEXCPP_TXT_DEMORUR.':10643';
	}

	function transactionResultHandler($transaction_result = '',$message = '',$source = 'frontend')
	{
		$orderID = 0;
		$log = '';
		if($orderID &&($order = _getOrderById($orderID))){
			$log = 'log';
			if($this->validateResultKey(array($orderID,$order['customer_email']))){
				//check callback sign

				// ...
				//
				if($transaction_result == 'success'){
					//change order status on setted at module settings
					$statusID = $this->_getSettingValue('CONF_CHRONOPAY_ORDERSTATUS');
					if($statusID!=-1){
						$comment = isset($_POST['transaction_id'])?sprintf('ChronoPay transaction ID: %d',intval($_POST['transaction_id'])):'auto status changed';
						ostSetOrderStatusToOrder( $orderID, $statusID,$comment,0);

					}
				}elseif($transaction_result == 'failure'){
					//log at order processing history
					$statusID = 3;
					//ostSetOrderStatusToOrder( $orderID, $statusID,$log,1,$force=true);
					//ostSetOrderStatusToOrder($orderID, $statusID, translate('ordr_added_comment').': '.$comment, ($this->getData('notify_customer')?1:0), true);
				}
			}else{
				$transaction_result = 'failure';
				$statusID = 3;
				//ostSetOrderStatusToOrder( $orderID, $statusID,$log,1,$force=true);
			}
		}else{
			$log = "Order with id {$orderID} not exists";
			$transaction_result = 'failure';
		}
		return parent::transactionResultHandler($transaction_result,$message.$log,$source);
	}
	
	private function xmlTransactionResultHadler()
	{
		//verify confirm payment

		//verify md5 and SSL

		//orderIsPaid;orderSumAmount;orderSumCurrencyPaycash;orderSumBankPaycash;shopId;invoiceId;customerNumber
		//В случае расчета криптографического хэша, в конце описанной выше строки добавляется «;shopPassword» 

		//or verify pgp

		//get payment transaction result

		$orderSumAmount;//Cумма заказа
		$CurrencyPaycash;//код валюты для суммы заказа

		$action = $_POST['action'];
		switch($action){
			case 'Check'://Проверка заказа
				//verify orderAmount and currencyID
				break;
			case 'PaymentSuccess'://Уведомления об оплате
				break;
			case 'PaymentFail'://после неуспешного платежа.
				break;
			default://unknown action
				break;
		}
	}
	private function sendResponce($code,$action,$shopId)
	{/*
		<xs:enumeration value = "0"/>	<!-- Success -->
		<xs:enumeration value = "1"/>	<!-- Authorization failed -->
		<xs:enumeration value = "100"/>	<!-- Payment refused by shop -->
		<xs:enumeration value = "200"/>	<!-- Bad request -->
		<xs:enumeration value = "1000"/><!-- Temporary technical problems -->
**/
		header("Content-type: text/xml; charset=windows-1251;");
		print "<?xml version=\"1.0\" encoding=\"windows-1251\"?>\n";
		print "\t<response performedDatetime={$performedDatetime}>\n";
		print "\t<result code={$code}";
		print " action={$action}";
		print " shopId={$shopId}";
		print " invoiceId={$invoiceId}";
		if($techMessage){
			print " techMessage={$techMessage}";
		}
		print "/>\n";
		print "</response>";
		exit;
	}
}
?>