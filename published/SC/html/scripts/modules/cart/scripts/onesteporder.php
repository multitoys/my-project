<?php

/*Модуль AJAX Оформления заказа в 1 шаг (версия 2)
Разработано: © JOrange.ru*/


	$Register = &Register::getInstance();
	$smarty = &$Register->get(VAR_SMARTY);
	$Message = $Register->get(VAR_MESSAGE);
	if(Message::isMessage($Message) && $Message->is_set() && isset($Message->Data))$smarty->assign('POST', $Message->Data);

	
	if($_SESSION['log']){
		$checkoutEntry = &Checkout::getInstance(_CHECKOUT_INSTANCE_NAME);
		$shippingAddress = $checkoutEntry->shippingAddress();
		$shipping_address = $shippingAddress->getVars();
		$customerEntry = Customer::getAuthedInstance();
		$addresses = regGetAllAddressesByID($customerEntry->customerID);
		$customer_info_default = array();
		regGetContactInfo( $_SESSION['log'], $customer_info_default['cust_password'], $customer_info_default['Email'], $customer_info_default['first_name'], $customer_info_default['last_name'], $customer_info_default['subscribed4news'], $customer_info_default['additional_field_values'] );
		$smarty->assign('customer_info_default', $customer_info_default );	
		$smarty->assign("address",	$shipping_address);
		$smarty->assign("addresses",$addresses);		
	}	

	$CurrentCountryID = ($Message->Data['shipping_address']['countryID'])?$Message->Data['shipping_address']['countryID']:(($shipping_address['countryID'])?$shipping_address['countryID']:CONF_DEFAULT_COUNTRY);
	OneStepOrder_GetAllPricec($resCart, $resDiscount, $currencyEntry, $cart_discount_show, $coupon_discount_show, $isErrorMinimalAmount);	
	$smarty->assign('isErrorMinimalAmount' ,$isErrorMinimalAmount);
	$smarty->assign("TotalItemPriceWhthoutUnits",	$resDiscount['total']['cu']);
	$smarty->assign('additional_fields', GetRegFields());		
	$smarty->assign('zones', znGetZonesById( $CurrentCountryID) );
	$smarty->assign('countries', cnGetCountries( array(), $count_row, null));	
	$smarty->assign("FieldsStandart", json_decode(CONF_ONESTEPORDER_FIELDS_STANDART, true));
	$smarty->assign("FieldsFast", json_decode(CONF_ONESTEPORDER_FIELDS_FAST, true));
	$smarty->assign('subscribed4news' ,1);
	$smarty->assign('billing_as_shipping' ,1);
	
	
	
	//Получаем адресс яндекса
	if(isset($_POST['operation_id']) && (CONF_ONESTEPORDER_TYPES_ORDERING == "all" || CONF_ONESTEPORDER_TYPES_ORDERING == "standart") && CONF_ONESTEPORDER_YANDEX_ADRESS_ENABLE == 1 ){
		if(OneStepOrder_YandexAdress($_POST, $YandexAdress)){
			$smarty->assign("YandexAdress", $YandexAdress);
			if($YandexAdress['countryID']){
				$countryID = db_phquery_fetch(DBRFETCH_FIRST, "SELECT `countryID` FROM ?#COUNTRIES_TABLE WHERE `country_name_ru` LIKE ('%".$YandexAdress['countryID']."%')");
				$smarty->assign('zones', znGetZonesById( $countryID));
			}
		}
	}
	
	//Получаем способы доставки
	if(isset($_POST['GetBillingMethods'])){
		OneStepOrder_GetBillingMethods($_POST['SID'],1, $payment_methods);
		$smarty->assign('payment_methods',$payment_methods);	
		$smarty->display('onesteporder/billing.html');
		exit();
	}
	
	//Получаем способы доставки
	if(isset($_POST['GetShippingMethods'])){
		OneStepOrder_GetShippingMethods($_POST['countryID'], $_POST['zoneID'], $_POST['shippingAddressID'], $shipping_costs, $shipping_methods, $SID, $count_shipping_methods );
		$smarty->assign( "count_shipping_methods",	$count_shipping_methods );
		$smarty->assign( "shipping_costs",		$shipping_costs );
		$smarty->assign( "shipping_methods",	$shipping_methods );
		OneStepOrder_GetBillingMethods(($Message->Data['shippingMethodID'])?$Message->Data['shippingMethodID']:$SID, $count_shipping_methods, $payment_methods);
		$smarty->assign('payment_methods',$payment_methods);	
		$ReturnHTMLBilling = $smarty->fetch('onesteporder/billing.html');
		$ReturnHTMLShipping = $smarty->fetch('onesteporder/shipping.html');
		$RenturnData = array(
			'ReturnHTMLBilling'=>$ReturnHTMLBilling,
			'ReturnHTMLShipping'=>$ReturnHTMLShipping
		);
		echo json_encode($RenturnData);
		exit();
	}
	
	//Установка адреса + смена страны
	if (isset($_POST['SetAdress'])){
		$zones = znGetZonesById($_POST['countryID']);
		OneStepOrder_GetShippingMethods($_POST['countryID'], $_POST['zoneID'], false, $shipping_costs, $shipping_methods, $SID, $count_shipping_methods );
		$smarty->assign( "count_shipping_methods",	$count_shipping_methods );
		$smarty->assign( "shipping_costs",		$shipping_costs );
		$smarty->assign( "shipping_methods",	$shipping_methods );
		OneStepOrder_GetBillingMethods(($Message->Data['shippingMethodID'])?$Message->Data['shippingMethodID']:$SID, $count_shipping_methods, $payment_methods);
		$smarty->assign('payment_methods',$payment_methods);	
		$ReturnHTMLBilling = $smarty->fetch('onesteporder/billing.html');
		$ReturnHTMLShipping = $smarty->fetch('onesteporder/shipping.html');
		$RenturnData = array(
			'isEmpty'=>(count($zones)>0)?false:true,
			'zones'=>$zones,
			'ReturnHTMLBilling'=>$ReturnHTMLBilling,
			'ReturnHTMLShipping'=>$ReturnHTMLShipping
		);
		echo json_encode($RenturnData);
		exit();
	}	
	
	//Удаление всех элементов	
	if (isset($_POST['RemoveAllElements'])){
		$cartEntry = new ShoppingCart();
		$cartEntry->loadCurrentCart();
		$cartEntry->cleanCurrentCart('erase');
		ClassManager::includeClass('discount_coupon');
		discount_coupon::remove();
		exit();
	}		
	
	//Удаление элемента		
	if (isset($_POST['RemoveElement'])){
		$cartEntry = new ShoppingCart();
		$cartEntry->loadCurrentCart();
		$cartEntry->setItemQuantity($_POST['element'], 0);
		$cartEntry->saveCurrentCart();
		if($cartEntry->isEmpty()){
			ClassManager::includeClass('discount_coupon');
			discount_coupon::remove();
		};	
		OneStepOrder_GetAllPricec($resCart, $resDiscount, $currencyEntry, $cart_discount_show, $coupon_discount_show, $isErrorMinimalAmount);	
		OneStepOrder_GetShippingMethodsCost($_POST['countryID'], $_POST['zoneID'], $_POST['shippingAddressID'], $ShippingCosts);
		$ReturnData = array(
			'TotalItemPrice'=>$currencyEntry->getView($resDiscount['total']['cu']),
			'TotalItemPriceWhthoutUnits'=>$resDiscount['total']['cu'],
			'isEmpty'=>($cartEntry->isEmpty())?true:false,
			'CartDiscount'=>$cart_discount_show,
			'CartDiscountPersent'=>round($resDiscount['discount_percent'],2),
			'CouponDiscount'=>$coupon_discount_show,
			'ShippingCosts'=>$ShippingCosts,
			'isErrorMinimalAmount'=>$isErrorMinimalAmount
		);
		echo json_encode($ReturnData);
		exit();
	}

	//Перерасчет кол-ва	
	if (isset($_POST['RecalculateCart'])){
		$cartEntry = new ShoppingCart();
		$cartEntry->loadCurrentCart();
		$cartEntry->setItemQuantity($_POST['element'], intval($_POST['count']));
		$cartEntry->saveCurrentCart();
		OneStepOrder_GetAllPricec($resCart, $resDiscount, $currencyEntry, $cart_discount_show, $coupon_discount_show, $isErrorMinimalAmount);
		OneStepOrder_GetShippingMethodsCost($_POST['countryID'], $_POST['zoneID'], $_POST['shippingAddressID'], $ShippingCosts);
		$ReturnData = array(
			'ElementPrice'=>show_price($_POST['costUC']*$_POST['count']),
			'TotalItemPrice'=>$currencyEntry->getView($resDiscount['total']['cu']),
			'TotalItemPriceWhthoutUnits'=>$resDiscount['total']['cu'],
			'CartDiscount'=>$cart_discount_show,
			'CartDiscountPersent'=>round($resDiscount['discount_percent'],2),
			'CouponDiscount'=>$coupon_discount_show,
			'ShippingCosts'=>$ShippingCosts,
			'isErrorMinimalAmount'=>$isErrorMinimalAmount
			
		);
		echo json_encode($ReturnData);
		exit();
	}
	
	//Применить купон
	if (isset($_POST['ApplyCoupon'])){
		$coupon_code = $_POST['CouponCode'];
	    ClassManager::includeClass('discount_coupon');
	    $coupon_id = discount_coupon::check($coupon_code);
	    if($coupon_id !== null) discount_coupon::apply($coupon_id);
		OneStepOrder_GetShippingMethodsCost($_POST['countryID'], $_POST['zoneID'], $_POST['shippingAddressID'], $ShippingCosts);		
		OneStepOrder_GetAllPricec($resCart, $resDiscount, $currencyEntry, $cart_discount_show, $coupon_discount_show, $isErrorMinimalAmount);	
        $ReturnData = array(
			'Applied' => ($coupon_id != null ? 'Y' : 'N'),
			'NewCoupon' => $coupon_discount_show,
			'TotalItemPrice' => $currencyEntry->getView($resDiscount['total']['cu']),
			'TotalItemPriceWhthoutUnits'=>$resDiscount['total']['cu'],
			'ShippingCosts'=>$ShippingCosts,
			'isErrorMinimalAmount'=>$isErrorMinimalAmount
        );
		echo json_encode($ReturnData);
		exit();
	}
	
	//Удалить купон
	if (isset($_POST['DeleteCoupon'])){
		ClassManager::includeClass('discount_coupon');
	    discount_coupon::remove();     
		OneStepOrder_GetShippingMethodsCost($_POST['countryID'], $_POST['zoneID'], $_POST['shippingAddressID'], $ShippingCosts);
		OneStepOrder_GetAllPricec($resCart, $resDiscount, $currencyEntry, $cart_discount_show, $coupon_discount_show, $isErrorMinimalAmount);	
		$ReturnData = array(
            'TotalItemPrice' => $currencyEntry->getView($resDiscount['total']['cu']),
			'TotalItemPriceWhthoutUnits'=>$resDiscount['total']['cu'],
			'ShippingCosts'=>$ShippingCosts,
			'isErrorMinimalAmount'=>$isErrorMinimalAmount
        );
		echo json_encode($ReturnData);
		exit();
	}

	//Авторизация
	if (isset($_POST['auth'])){
		if ( regAuthenticate($_POST['auth']['Login'], $_POST['auth']['cust_password']) ){	
			$checkoutEntry = &Checkout::getInstance(_CHECKOUT_INSTANCE_NAME);
			$checkoutEntry->shippingAddress('');
			$checkoutEntry->billingAddress('');
			RedirectSQ('');
		}else{
			Message::raiseMessageRedirectSQ(MSG_ERROR, 'login_form=1', 'err_wrong_password', '', array('name' => 'auth', 'Data' => $_POST));
		}
	}
	
	//Проверка формы перед созданием заказа
	if (isset($_POST['CheckBeforeSubmit'])){
		OneStepOrder_SetPostData($_POST,$_POST);
		$checkoutEntry = &Checkout::getInstance(_CHECKOUT_INSTANCE_NAME);
		$checkoutEntry->formsData($_POST);
		$res = OneStepOrder_CheckFormData();
		$ReturnData = array(
            'Result' => (PEAR::isError($res))?translate($res->getMessage()):'noErrors'
        );
		echo json_encode($ReturnData);
		exit();
	}
	
	//Оформить заказ
	if (isset($_POST['ordering'])){
		OneStepOrder_SetPostData($_POST,$_POST);
		$checkoutEntry = &Checkout::getInstance(_CHECKOUT_INSTANCE_NAME);
		$checkoutEntry->formsData($_POST);
		$res = OneStepOrder_CheckFormData(true);
		if(PEAR::isError($res))Message::raiseMessageRedirectSQ(MSG_ERROR, '', $res->getMessage(), '', array('Data' => $_POST));
		
		$customerEntry = new Customer();	
		$customerEntry->loadFromArray($_POST['customer_info'], true);

		$shippingAddress = new Address();
		if($_POST['addressID']=='0'){
			$shippingAddress->loadFromArray($_POST['shipping_address'], true);
			if(CONF_ORDERING_REQUEST_BILLING_ADDRESS){
				$billingAddress = new Address();
				$billingAddress->loadFromArray($_POST['billing_as_shipping']?$_POST['shipping_address']:$_POST['billing_address'], true);
			}else{
				$billingAddress = clone($shippingAddress);
			}
			$checkoutEntry->shippingAddress($shippingAddress);
			$checkoutEntry->billingAddress($billingAddress);
		}else{
			$shippingAddress->loadByID($_POST['addressID']);
			$customerEntry = &$checkoutEntry->customer();
			if($shippingAddress->belong2Customer($customerEntry)){
				$checkoutEntry->shippingAddress($shippingAddress);
				$billingAddress = clone($shippingAddress);
				$checkoutEntry->billingAddress($billingAddress);
			}
		}
		

		if(!$_POST['permanent_registering']){
			$customerEntry->Login = '';
			$customerEntry->cust_password = '';
		}
		
		$checkoutEntry->customer($customerEntry);
		$checkoutEntry->shippingMethodID($_POST['shippingMethodID']);
		$checkoutEntry->shippingServiceID($_POST['shippingServiceID'][$_POST['shippingMethodID']]);	
		$checkoutEntry->paymentMethodID($_POST['paymentMethodID']);
		$checkoutEntry->widgets = $Register->get('widgets');
		$checkoutEntry->customers_comment($_POST['order_comment']);

		$orderID = $checkoutEntry->emulate_ordOrderProcessing();
		$cartEntry = new ShoppingCart();
		$cartEntry->cleanCurrentCart();
		$_SESSION["newoid"] = $orderID;
		RedirectSQ('ukey=checkout&step=success&orderID='.$orderID);
	}

	//Изменить конфигурацию
	if (isset($_POST['ChangeConfigurationItem'])){
		$productData = array();
		$productData['updateElement'] = "";
		$productData['removeElement'] = "";
		$productData = OneStepOrder_SetOptions($_POST);	
		OneStepOrder_GetAllPricec($resCart, $resDiscount, $currencyEntry, $cart_discount_show, $coupon_discount_show, $isErrorMinimalAmount);
		OneStepOrder_GetShippingMethodsCost($_POST['countryID'], $_POST['zoneID'], $_POST['shippingAddressID'], $ShippingCosts);
		$ReturnData = array(
			'ElementPrice'=>show_price($_POST['costUC']*$_POST['count']),
			'TotalItemPrice'=>$currencyEntry->getView($resDiscount['total']['cu']),
			'TotalItemPriceWhthoutUnits'=>$resDiscount['total']['cu'],
			'CartDiscount'=>$cart_discount_show,
			'CartDiscountPersent'=>round($resDiscount['discount_percent'],2),
			'CouponDiscount'=>$coupon_discount_show,
			'ShippingCosts'=>$ShippingCosts,
			'isErrorMinimalAmount'=>$isErrorMinimalAmount,
			'ProductData' => $productData
		);
		echo json_encode($ReturnData);
		exit();
	}
		

		
	$smarty->assign('main_content_template', 'onesteporder/main.html');

?>