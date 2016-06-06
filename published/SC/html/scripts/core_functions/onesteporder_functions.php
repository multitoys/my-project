<?php

/*Модуль AJAX Оформления заказа в 1 шаг (версия 2)
Разработано: © JOrange.ru*/


/*=================================
			НАСТРОЙКИ
===================================*/

	//Настройки для полей полного заказа
	function settingCONF_ONESTEPORDER_FIELDS_STANDART(){
		if ( isset($_POST["save"]) && isset($_POST["settingCONF_ONESTEPORDER_FIELDS_STANDART"]) ){	
			$m_checked = array_keys(scanArrayKeysForID($_POST, 'settingCONF_ONESTEPORDER_FIELDS_STANDART'));
			$value = array();
			foreach($m_checked as $checked){
				$value[$checked] = 1;
			}
			$save_data = json_encode($value);
			_setSettingOptionValue( 'CONF_ONESTEPORDER_FIELDS_STANDART', $save_data );
		}
		$fields = json_decode(_getSettingOptionValue( 'CONF_ONESTEPORDER_FIELDS_STANDART' ), true);
		$checked = array();
		foreach($fields as $key => $field){	
			if($field == '1') $checked[$key] = 'checked="checked"';
		}

		$output='
		<input type="hidden" value="yep" name="settingCONF_ONESTEPORDER_FIELDS_STANDART" >	
		<ul style="list-style: none;">
			<li><label><input type="checkbox" value="1" name="settingCONF_ONESTEPORDER_FIELDS_STANDART_first_name" '.$checked['first_name'].' >*'.translate('usr_custinfo_first_name').'</label></li>
			<li><label><input type="checkbox" value="1" name="settingCONF_ONESTEPORDER_FIELDS_STANDART_last_name" '.$checked['last_name'].' >*'.translate('usr_custinfo_last_name').'</label></li>
			<li><label><input type="checkbox" value="1" name="settingCONF_ONESTEPORDER_FIELDS_STANDART_Email" '.$checked['Email'].' >*'.translate('usr_custinfo_email').'</label></li>
			<li><label><input type="checkbox" value="1" name="settingCONF_ONESTEPORDER_FIELDS_STANDART_subscribe" '.$checked['subscribe'].' >'.translate('usrreg_subscribe_for_blognews').'</label></li>
			<li><label><input type="checkbox" value="1" name="settingCONF_ONESTEPORDER_FIELDS_STANDART_billing_as_shipping" '.$checked['billing_as_shipping'].' >'.translate('onesteporder_billing_as_shipping').'</label></li>
			<li><label><input type="checkbox" value="1" name="settingCONF_ONESTEPORDER_FIELDS_STANDART_address" '.$checked['address'].' >'.((CONF_ADDRESSFORM_ADDRESS==0)?'*':'').translate('str_address').'</label></li>
			<li><label><input type="checkbox" value="1" name="settingCONF_ONESTEPORDER_FIELDS_STANDART_city" '.$checked['city'].' >'.((CONF_ADDRESSFORM_CITY==0)?'*':'').translate('usr_custinfo_city').'</label></li>
			<li><label><input type="checkbox" value="1" name="settingCONF_ONESTEPORDER_FIELDS_STANDART_state" '.$checked['state'].' >'.((CONF_ADDRESSFORM_STATE==0)?'*':'').translate('usr_custinfo_state').'</label></li>
			<li><label><input type="checkbox" value="1" name="settingCONF_ONESTEPORDER_FIELDS_STANDART_zip" '.$checked['zip'].' >'.((CONF_ADDRESSFORM_ZIP==0)?'*':'').translate('usr_custinfo_zip').'</label></li>
			<li><label><input type="checkbox" value="1" name="settingCONF_ONESTEPORDER_FIELDS_STANDART_country" '.$checked['country'].' >*'.translate('usr_custinfo_country').'</label></li>		
			<li><label><input type="checkbox" value="1" name="settingCONF_ONESTEPORDER_FIELDS_STANDART_useradresses" '.$checked['useradresses'].' >'.translate('conf_onesteporder_user_adresses').'</label></li>		
		';
		$additional_fields = GetRegFields();
		foreach($additional_fields as $key => $field){	
			$output.='
				<li><label><input type="checkbox" value="1" name="settingCONF_ONESTEPORDER_FIELDS_STANDART_additional_field_'.$field['reg_field_ID'].'" '.$checked['additional_field_'.$field['reg_field_ID']].' >'.(($field['reg_field_required'])?'*':'').$field['reg_field_name'].'</label></li>
			';
		}
		$output.='</ul>';
		return $output;
	}

	//Настройки для полей быстрого заказа
	function settingCONF_ONESTEPORDER_FIELDS_FAST(){
		if ( isset($_POST["save"]) && isset($_POST["settingCONF_ONESTEPORDER_FIELDS_FAST"]) ){	
			$m_checked = array_keys(scanArrayKeysForID($_POST, 'settingCONF_ONESTEPORDER_FIELDS_FAST'));
			$value = array();
			foreach($m_checked as $checked){
				$value[$checked] = 1;
			}
			$save_data = json_encode($value);
			_setSettingOptionValue( 'CONF_ONESTEPORDER_FIELDS_FAST', $save_data );
		}
		$fields = json_decode(_getSettingOptionValue( 'CONF_ONESTEPORDER_FIELDS_FAST' ), true);
		$checked = array();
		foreach($fields as $key => $field){	
			if($field == '1') $checked[$key] = 'checked="checked"';
		}

		$output='
		<input type="hidden" value="yep" name="settingCONF_ONESTEPORDER_FIELDS_FAST" >	
		<ul style="list-style: none;">
			<li><label><input type="checkbox" value="1" name="settingCONF_ONESTEPORDER_FIELDS_FAST_first_name" '.$checked['first_name'].' >*'.translate('usr_custinfo_first_name').'</label></li>
			<li><label><input type="checkbox" value="1" name="settingCONF_ONESTEPORDER_FIELDS_FAST_last_name" '.$checked['last_name'].' >*'.translate('usr_custinfo_last_name').'</label></li>
			<li><label><input type="checkbox" value="1" name="settingCONF_ONESTEPORDER_FIELDS_FAST_Email" '.$checked['Email'].' >*'.translate('usr_custinfo_email').'</label></li>
			<li><label><input type="checkbox" value="1" name="settingCONF_ONESTEPORDER_FIELDS_FAST_subscribe" '.$checked['subscribe'].' >'.translate('usrreg_subscribe_for_blognews').'</label></li>
			<li><label><input type="checkbox" value="1" name="settingCONF_ONESTEPORDER_FIELDS_FAST_billing_as_shipping" '.$checked['billing_as_shipping'].' >'.translate('onesteporder_billing_as_shipping').'</label></li>
			<li><label><input type="checkbox" value="1" name="settingCONF_ONESTEPORDER_FIELDS_FAST_address" '.$checked['address'].' >'.((CONF_ADDRESSFORM_ADDRESS==0)?'*':'').translate('str_address').'</label></li>
			<li><label><input type="checkbox" value="1" name="settingCONF_ONESTEPORDER_FIELDS_FAST_city" '.$checked['city'].' >'.((CONF_ADDRESSFORM_CITY==0)?'*':'').translate('usr_custinfo_city').'</label></li>
			<li><label><input type="checkbox" value="1" name="settingCONF_ONESTEPORDER_FIELDS_FAST_state" '.$checked['state'].' >'.((CONF_ADDRESSFORM_STATE==0)?'*':'').translate('usr_custinfo_state').'</label></li>
			<li><label><input type="checkbox" value="1" name="settingCONF_ONESTEPORDER_FIELDS_FAST_zip" '.$checked['zip'].' >'.((CONF_ADDRESSFORM_ZIP==0)?'*':'').translate('usr_custinfo_zip').'</label></li>
			<li><label><input type="checkbox" value="1" name="settingCONF_ONESTEPORDER_FIELDS_FAST_country" '.$checked['country'].' >*'.translate('usr_custinfo_country').'</label></li>
			<li><label><input type="checkbox" value="1" name="settingCONF_ONESTEPORDER_FIELDS_FAST_useradresses" '.$checked['useradresses'].' >'.translate('conf_onesteporder_user_adresses').'</label></li>				
		';
		$additional_fields = GetRegFields();
		foreach($additional_fields as $key => $field){	
			$output.='
				<li><label><input type="checkbox" value="1" name="settingCONF_ONESTEPORDER_FIELDS_FAST_additional_field_'.$field['reg_field_ID'].'" '.$checked['additional_field_'.$field['reg_field_ID']].' >'.(($field['reg_field_required'])?'*':'').$field['reg_field_name'].'</label></li>
			';
		}
		$output.='</ul>';
		return $output;
	}
		
	//Настройки для полей быстрого заказа
	function settingCONF_ONESTEPORDER_YANDEX_ADRESS_FIELDS(){
		if ( isset($_POST["save"]) && isset($_POST["settingCONF_ONESTEPORDER_YANDEX_ADRESS_FIELDS"]) ){	
			$m_selected = array_keys(scanArrayKeysForID($_POST, 'settingCONF_ONESTEPORDER_YANDEX_ADRESS_FIELDS'));
			$value = array();
			foreach($m_selected as $selected){
				$value[$selected] = $_POST['settingCONF_ONESTEPORDER_YANDEX_ADRESS_FIELDS_'.$selected];
			}
			 $save_data = json_encode($value);
			_setSettingOptionValue( 'CONF_ONESTEPORDER_YANDEX_ADRESS_FIELDS', $save_data );
		}
		$selected = json_decode(_getSettingOptionValue( 'CONF_ONESTEPORDER_YANDEX_ADRESS_FIELDS' ), true);
		$output='
		<input type="hidden" value="yep" name="settingCONF_ONESTEPORDER_YANDEX_ADRESS_FIELDS" >	
		<table>';
		//
		$additional_fields = GetRegFields();
		foreach($additional_fields as $key => $field){	
			$output.='
				<tr>
				<td>
					'.$field['reg_field_name'].'
				</td>
				<td>
					<select name="settingCONF_ONESTEPORDER_YANDEX_ADRESS_FIELDS_'.$field['reg_field_ID'].'">
						<option value="">-</option>
						<option value="street"  '.(($selected[$field['reg_field_ID']]=="street")?"selected":"").' >'.translate('onesteporder_street').'</option>
						<option value="building" '.(($selected[$field['reg_field_ID']]=="building")?"selected":"").' >'.translate('onesteporder_building').'</option>
						<option value="suite" '.(($selected[$field['reg_field_ID']]=="suite")?"selected":"").' >'.translate('onesteporder_suite').'</option>
						<option value="flat" '.(($selected[$field['reg_field_ID']]=="flat")?"selected":"").' >'.translate('onesteporder_flat').'</option>
						<option value="entrance" '.(($selected[$field['reg_field_ID']]=="entrance")?"selected":"").' >'.translate('onesteporder_entrance').'</option>
						<option value="floor" '.(($selected[$field['reg_field_ID']]=="floor")?"selected":"").' >'.translate('onesteporder_floor').'</option>
						<option value="intercom" '.(($selected[$field['reg_field_ID']]=="intercom")?"selected":"").' >'.translate('onesteporder_intercom').'</option>
						<option value="zip" '.(($selected[$field['reg_field_ID']]=="zip")?"selected":"").' >'.translate('onesteporder_zip').'</option>
						<option value="metro" '.(($selected[$field['reg_field_ID']]=="metro")?"selected":"").' >'.translate('onesteporder_metro').'</option>
						<option value="cargolift" '.(($selected[$field['reg_field_ID']]=="cargolift")?"selected":"").' >'.translate('onesteporder_cargolift').'</option>
						<option value="fathersname" '.(($selected[$field['reg_field_ID']]=="fathersname")?"selected":"").' >'.translate('onesteporder_fathersname').'</option>
						<option value="phone" '.(($selected[$field['reg_field_ID']]=="phone")?"selected":"").' >'.translate('onesteporder_phone').'</option>
						<option value="phone-extra" '.(($selected[$field['reg_field_ID']]=="phone-extra")?"selected":"").' >'.translate('onesteporder_phone-extra').'</option>
					</select>
				</td>
				</tr>
			';
		}
		$output.='</table>';
		return $output;
	}
	
	//Настройки поля телефон для Киви
	function settingCONF_ONESTEPORDER_QIWI_PHONE_FIELD(){
		if ( isset($_POST["save"]) && isset($_POST["settingCONF_ONESTEPORDER_QIWI_PHONE_FIELD"]) ){	
			$value = $_POST['settingCONF_ONESTEPORDER_QIWI_PHONE_FIELD'];
			_setSettingOptionValue( 'CONF_ONESTEPORDER_QIWI_PHONE_FIELD', $value );
		}
		$current = json_decode(_getSettingOptionValue( 'CONF_ONESTEPORDER_QIWI_PHONE_FIELD' ), true);
		$output.='<table><tr><td><select name="settingCONF_ONESTEPORDER_QIWI_PHONE_FIELD"><option value="">-</option>';
		$additional_fields = GetRegFields();
		foreach($additional_fields as $key => $field){	
			$output.='<option value="'.$field['reg_field_ID'].'"  '.(($field['reg_field_ID']==$current)?"selected":"").' >'.$field['reg_field_name'].'</option>';
		}
		$output.='</select></td></tr></table>';
		return $output;
	}
	
	//Настройки поля название компании для способа оплаты
	function settingCONF_ONESTEPORDER_COMPANY_NAME_FIELD(){
		if ( isset($_POST["save"]) && isset($_POST["settingCONF_ONESTEPORDER_COMPANY_NAME_FIELD"]) ){	
			$value = $_POST['settingCONF_ONESTEPORDER_COMPANY_NAME_FIELD'];
			_setSettingOptionValue( 'CONF_ONESTEPORDER_COMPANY_NAME_FIELD', $value );
		}
		$current = json_decode(_getSettingOptionValue( 'CONF_ONESTEPORDER_COMPANY_NAME_FIELD' ), true);
		$output.='<table><tr><td><select name="settingCONF_ONESTEPORDER_COMPANY_NAME_FIELD"><option value="">-</option>';
		$additional_fields = GetRegFields();
		foreach($additional_fields as $key => $field){	
			$output.='<option value="'.$field['reg_field_ID'].'"  '.(($field['reg_field_ID']==$current)?"selected":"").' >'.$field['reg_field_name'].'</option>';
		}
		$output.='</select></td></tr></table>';
		return $output;
	}
	
	//Настройки поля ИНН для способа оплаты
	function settingCONF_ONESTEPORDER_INN_NAME_FIELD(){
		if ( isset($_POST["save"]) && isset($_POST["settingCONF_ONESTEPORDER_INN_NAME_FIELD"]) ){	
			$value = $_POST['settingCONF_ONESTEPORDER_INN_NAME_FIELD'];
			_setSettingOptionValue( 'CONF_ONESTEPORDER_INN_NAME_FIELD', $value );
		}
		$current = json_decode(_getSettingOptionValue( 'CONF_ONESTEPORDER_INN_NAME_FIELD' ), true);
		$output.='<table><tr><td><select name="settingCONF_ONESTEPORDER_INN_NAME_FIELD"><option value="">-</option>';
		$additional_fields = GetRegFields();
		foreach($additional_fields as $key => $field){	
			$output.='<option value="'.$field['reg_field_ID'].'"  '.(($field['reg_field_ID']==$current)?"selected":"").' >'.$field['reg_field_name'].'</option>';
		}
		$output.='</select></td></tr></table>';
		return $output;
	}
	
/*=================================
			Функции
===================================*/

	//Получение всех цен корзины
	function OneStepOrder_GetAllPricec(&$resCart, &$resDiscount, &$currencyEntry, &$cart_discount_show, &$coupon_discount_show, &$isErrorMinimalAmount){
		$resCart = cartGetCartContent();
		$resDiscount = dscGetCartDiscounts( $resCart["total_price"], (isset($_SESSION["log"]) ? $_SESSION["log"] : "") );
		$currencyEntry = Currency::getSelectedCurrencyInstance();					
		$cart_discount_show = $resDiscount['other_discounts']['cu'] > 0 ? $currencyEntry->getView($resDiscount['other_discounts']['cu']) : '';
		$coupon_discount_show = $resDiscount['coupon_discount']['cu'] > 0 ? $currencyEntry->getView($resDiscount['coupon_discount']['cu']) : '';
		$d = oaGetDiscountValue( $resCart, "" );
		$order["order_amount"] = $resCart["total_price"] - $d;
		$isErrorMinimalAmount = ($order["order_amount"]<CONF_MINIMAL_ORDER_AMOUNT)?true:false;
	}
	
	//Загрузка способов оплаты
	function OneStepOrder_GetBillingMethods($SID, $count_shipping_methods, &$payment_methods){
		$_payment_methods = payGetAllPaymentMethods(true);
		if($count_shipping_methods >0){
			$payment_methods = array();
			foreach( $_payment_methods as $payment_method ){
				$shippingMethodsToAllow = false;
				foreach( $payment_method['ShippingMethodsToAllow'] as $ShippingMethod ){
					if ( ((int)$SID == (int)$ShippingMethod['SID']) && $ShippingMethod['allow'] ){				
						$shippingMethodsToAllow = true;
						break;
					}
				}
				if ( $shippingMethodsToAllow )$payment_methods[] = $payment_method;
			}
		}else{
			$payment_methods = $_payment_methods;
		}
	}
	
	//Загрузка способов доставки
	function OneStepOrder_GetShippingMethods($countryID, $zoneID, $shippingAddressID, &$shipping_costs, &$shipping_methods, &$SID, &$count_all_shipping_methods){
		$cartEntry = new ShoppingCart();
		$cartEntry->loadCurrentCart();
		$shipping_methods	= shGetAllShippingMethods( true );
		$count_all_shipping_methods	= count($shipping_methods);
		$shipping_costs		= array();
		$shipping_costs_bySID		= array();
		$checkoutEntry = &Checkout::getInstance(_CHECKOUT_INSTANCE_NAME);
		$order = $checkoutEntry->emulate_getOrder();
		$cart = $cartEntry->emulate_cartGetCartContent();
		
		$countryID = ($_POST['countryID']!='')?$_POST['countryID']:CONF_DEFAULT_COUNTRY;
		$zones = znGetZonesById($countryID);
		$zoneID = ($_POST['zoneID']!='')?$_POST['zoneID']:$zones[0]['zoneID'];
		$shippingAddressID = ($_POST['shippingAddressID']!='')?$_POST['shippingAddressID']:false;
		$shipping_address = (!$shippingAddressID)?array('countryID'=>$countryID, 'zoneID'=>$zoneID):regGetAddress( $shippingAddressID );
		$addresses = array($shipping_address, $shipping_address);
		
		$j = 0;
		foreach( $shipping_methods as $key => $shipping_method ){
			$_ShippingModule = ShippingRateCalculator::getInstance($shipping_method["module_id"]);	
			if($_ShippingModule){
				if( $_ShippingModule->allow_shipping_to_address($shipping_address)){
					$shipping_costs[$j] = oaGetShippingCostTakingIntoTax( $cart, $shipping_method["SID"], $addresses, $order );
					$shipping_costs_bySID[$shipping_method["SID"]] = oaGetShippingCostTakingIntoTax( $cart, $shipping_method["SID"], $addresses, $order );
				}else{
					$shipping_costs[$j] = array(array('rate'=>-1));
					$shipping_costs_bySID[$shipping_method["SID"]]  = array(array('rate'=>-1));
				}
			}else{
				$shipping_costs[$j] = oaGetShippingCostTakingIntoTax( $cart, $shipping_method["SID"], $addresses, $order );
			}
			$j++;
		}
		$_i = count($shipping_costs)-1;
		for ( ; $_i>=0; $_i-- ){
			$_t = count($shipping_costs[$_i])-1;
			for ( ; $_t>=0; $_t-- ){
				if($shipping_costs[$_i][$_t]['rate']>0){
					$shipping_costs[$_i][$_t]['rateWithUnit'] = show_price($shipping_costs[$_i][$_t]['rate']);
					$shipping_costs[$_i][$_t]['rateWithOutUnit'] = show_priceWithOutUnit($shipping_costs[$_i][$_t]['rate']);
				}else {
					if(count($shipping_costs[$_i]) == 1 && $shipping_costs[$_i][$_t]['rate']<0){
						$shipping_costs[$_i] = 'n/a';
					}else{
						$shipping_costs[$_i][$_t]['rate'] = '';
					}
				}
			}
		}
		$SID = '';
		foreach($shipping_methods as $key=>$shipping_method){
			if($shipping_costs[$key] != 'n/a'){
				$SID = $shipping_method['SID'];
				break;
			}
		}
	}

	//Загрузка цен для способов доставки
	function OneStepOrder_GetShippingMethodsCost($countryID, $zoneID, $shippingAddressID, &$ShippingCosts ){
		$cartEntry = new ShoppingCart();
		$cartEntry->loadCurrentCart();
		$shipping_methods	= shGetAllShippingMethods( true );
		$ShippingCosts		= array();
		$checkoutEntry 		= &Checkout::getInstance(_CHECKOUT_INSTANCE_NAME);
		$order 				= $checkoutEntry->emulate_getOrder();
		$cart				= $cartEntry->emulate_cartGetCartContent();
		$countryID 			= ($_POST['countryID']!='')?$_POST['countryID']:CONF_DEFAULT_COUNTRY;
		$zones 				= znGetZonesById($countryID);
		$zoneID 			= ($_POST['zoneID']!='')?$_POST['zoneID']:$zones[0]['zoneID'];
		$shippingAddressID 	= ($_POST['shippingAddressID']!='')?$_POST['shippingAddressID']:false;
		$shipping_address 	= (!$shippingAddressID)?array('countryID'=>$countryID, 'zoneID'=>$zoneID):regGetAddress( $shippingAddressID );
		$addresses 			= array($shipping_address, $shipping_address);
		
		$j = 0;
		foreach( $shipping_methods as $key => $shipping_method ){
			$ShippingCosts[$shipping_method["SID"]] = oaGetShippingCostTakingIntoTax( $cart, $shipping_method["SID"], $addresses, $order );
			$j++;
		}
		foreach($ShippingCosts as $key=>$ShippingCostsMethod){
			foreach($ShippingCostsMethod as $key2=>$ShippingCost){
				$ShippingCosts[$key][$key2]['rateWithUnit'] = show_price($ShippingCost['rate']);
				$ShippingCosts[$key][$key2]['rateWithOutUnit'] = show_priceWithOutUnit($ShippingCost['rate']);
			}
		}
	}
	

	//Проверка формы	
	function OneStepOrder_CheckFormData($ordering=false){
		$res = cartGetCartContent();
		$d = oaGetDiscountValue( $res, "" );
		$order["order_amount"] = $res["total_price"] - $d;
		if($order["order_amount"]<CONF_MINIMAL_ORDER_AMOUNT) return PEAR::raiseError(translate('cart_min_order_amount_not_reached').show_price(CONF_MINIMAL_ORDER_AMOUNT));
		$checkoutEntry = &Checkout::getInstance(_CHECKOUT_INSTANCE_NAME);
		$forms_data = $checkoutEntry->formsData();
	
		if($forms_data["customer_phone"]){
			if(!preg_match('@^\d{11}$@',$forms_data["customer_phone"])){
				$forms_data["customer_phone"] =  preg_replace('/[\. \( \) \- \+ \s]/','',$forms_data["customer_phone"]);
				if(!preg_match('@^\d{11}$@',$forms_data["customer_phone"])) {
					return PEAR::raiseError(translate('onesteporder_empty_email'));
				}
			}
		}
		
		$customerEntry = new Customer();
		$customerEntry->loadFromArray($forms_data['customer_info'], true);
	
		if(!$forms_data['permanent_registering']){
			$customerEntry->Login = '';
			$customerEntry->cust_password = '';
		} 
		$res = $customerEntry->checkInfo($forms_data['permanent_registering']?'required_loginpass':null);
		if(PEAR::isError($res))return $res;	
		if($forms_data['permanent_registering'] && $customerEntry->cust_password != $forms_data['customer_info']['cust_password1'])
			return PEAR::raiseError(translate('err_password_confirm_failed'));
			
			
		$paymentMethod = payGetPaymentMethodById( $forms_data['paymentMethodID'] );
		$currentPaymentModule = isset($paymentMethod['module_id']) && $paymentMethod['module_id']?PaymentModule::getInstance( $paymentMethod["module_id"]):null;
		if(!is_null($currentPaymentModule)){	
			$process_payment_result = $currentPaymentModule->payment_process( $order_payment_details );
			if($process_payment_result !== 1){
				return PEAR::raiseError($process_payment_result);
			}
		}
		
		$shippingAddress = new Address();
		$shippingAddress->loadFromArray($forms_data['shipping_address'], true);
		$res = $shippingAddress->checkInfo();
		if(PEAR::isError($res))return $res;
		if(CONF_ORDERING_REQUEST_BILLING_ADDRESS){
			$billingAddress = new Address();
			$billingAddress->loadFromArray($forms_data['billing_as_shipping']?$forms_data['shipping_address']:$forms_data['billing_address'], true);
			$res = $billingAddress->checkInfo();
			if(PEAR::isError($res))return $res;
		}
		
		
		if(CONF_ENABLE_CONFIRMATION_CODE) {
			if(!$ordering){
				if(!$forms_data['confirmation_code']) return PEAR::raiseError(translate("err_wrong_ccode"));	
				if(strtolower($_SESSION['SS_IVAL']) != strtolower($forms_data['confirmation_code'])) return PEAR::raiseError(translate("err_wrong_ccode"));	
			}else{			
				$i = new IValidator();
				if(!$i->checkCode($forms_data['confirmation_code'])) return PEAR::raiseError(translate("err_wrong_ccode"));
			}
		}
	}
		
	//Изменение полей формы в соответсвии со способом оформления	
	function OneStepOrder_SetPostData($input, &$output){	
		$output['billing_as_shipping'] = ($input['billing_as_shipping'])?1:0;
		$output['permanent_registering'] = ($input['permanent_registering'])?$input['permanent_registering']:false;
		$FieldsStandart = json_decode(CONF_ONESTEPORDER_FIELDS_STANDART, true);
		$FieldsFast = json_decode(CONF_ONESTEPORDER_FIELDS_FAST, true);
		$additional_fields = GetRegFields();
		

		if($input['ordering'] == 'standart'){
			$output['customer_info']['first_name'] 		= ($FieldsStandart['first_name'] == 0)?" ":$input['customer_info']['first_name'];
			$output['customer_info']['last_name'] 		= ($FieldsStandart['last_name'] == 0)?" ":$input['customer_info']['last_name'];
			$output['customer_info']['Email'] 			= ($FieldsStandart['Email'] == 0)?"empty@mail.wa":$input['customer_info']['Email'];
			
			$output['shipping_address']['first_name'] 	= ($FieldsStandart['first_name'] == 0)?" ":$input['customer_info']['first_name'];
			$output['shipping_address']['last_name'] 	= ($FieldsStandart['last_name'] == 0)?" ":$input['customer_info']['last_name'];
			$output['shipping_address']['address'] 		= ($FieldsStandart['address'] == 0)?" ":$input['shipping_address']['address'];
			$output['shipping_address']['city'] 		= ($FieldsStandart['city'] == 0)?" ":$input['shipping_address']['city'];
			
		
			$output['shipping_address']['countryID'] = ($FieldsStandart['country'] == 0)?CONF_DEFAULT_COUNTRY:$input['shipping_address']['countryID'];
			$zonesS = znGetZonesById($output['shipping_address']['countryID']);
			$output['shipping_address']['zoneID'] = ($FieldsStandart['state'] == 0)?$zonesS[0]['zoneID']:$input['shipping_address']['zoneID'];
			$output['shipping_address']['state']  = ($FieldsStandart['state'] == 0)?" ":$input['shipping_address']['state'];
			
			$output['billing_as_shipping']  = ($FieldsStandart['billing_as_shipping'] == 0)?1:$input['billing_as_shipping'];
			if(CONF_ORDERING_REQUEST_BILLING_ADDRESS && !$output['billing_as_shipping']){
				$output['billing_address']['first_name'] 	= ($FieldsStandart['first_name'] == 0)?" ":$input['billing_address']['first_name'];
				$output['billing_address']['last_name'] 	= ($FieldsStandart['last_name'] == 0)?" ":$input['billing_address']['last_name'];
				$output['billing_address']['address'] 		= ($FieldsStandart['address'] == 0)?" ":$input['billing_address']['address'];
				$output['billing_address']['city'] 			= ($FieldsStandart['city'] == 0)?" ":$input['billing_address']['city'];
				$output['billing_address']['zip'] 			= ($FieldsStandart['zip'] == 0)?" ":$input['billing_address']['zip'];
				
				$output['billing_address']['countryID'] = ($FieldsStandart['country'] == 0)?CONF_DEFAULT_COUNTRY:$input['billing_address']['countryID'];
				$zonesB = znGetZonesById($output['billing_address']['countryID']);
				$output['billing_address']['zoneID'] = ($FieldsStandart['state'] == 0)?$zonesB[0]['zoneID']:$input['billing_address']['zoneID'];
				$output['billing_address']['state']	 = ($FieldsStandart['state'] == 0)?" ":$input['billing_address']['state'];
			}
			
			foreach($additional_fields as $key=>$Field){
				$output['customer_info']['_custom_fields'][$Field['reg_field_ID']] 	= ($FieldsStandart['additional_field_'.$Field['reg_field_ID']] == 0)?" ":$input['customer_info']['_custom_fields'][$Field['reg_field_ID']];	
				if(CONF_ONESTEPORDER_QIWI_PHONE_FIELD && CONF_ONESTEPORDER_QIWI_PHONE_FIELD==$Field['reg_field_ID']){
					$output["customer_phone"] = $input['customer_info']['_custom_fields'][$Field['reg_field_ID']];
				}
				if(CONF_ONESTEPORDER_COMPANY_NAME_FIELD && CONF_ONESTEPORDER_COMPANY_NAME_FIELD==$Field['reg_field_ID']){
					$output["minvoicejur_company_name"] = $input['customer_info']['_custom_fields'][$Field['reg_field_ID']];
				}
				if(CONF_ONESTEPORDER_INN_NAME_FIELD && CONF_ONESTEPORDER_INN_NAME_FIELD==$Field['reg_field_ID']){
					$output["minvoicejur_inn"] = $input['customer_info']['_custom_fields'][$Field['reg_field_ID']];
				}
			}
		}
		
		if($input['ordering'] == 'fast'){
			$output['customer_info']['first_name'] 		= ($FieldsFast['first_name'] == 0)?" ":$input['customer_info']['first_name'];
			$output['customer_info']['last_name'] 		= ($FieldsFast['last_name'] == 0)?" ":$input['customer_info']['last_name'];
			$output['customer_info']['Email'] 			= ($FieldsFast['Email'] == 0)?"empty@mail.wa":$input['customer_info']['Email'];
			
			$output['shipping_address']['first_name'] 	= ($FieldsFast['first_name'] == 0)?" ":$input['customer_info']['first_name'];
			$output['shipping_address']['last_name'] 	= ($FieldsFast['last_name'] == 0)?" ":$input['customer_info']['last_name'];
			$output['shipping_address']['address'] 		= ($FieldsFast['address'] == 0)?" ":$input['shipping_address']['address'];
			$output['shipping_address']['city'] 		= ($FieldsFast['city'] == 0)?" ":$input['shipping_address']['city'];
			$output['shipping_address']['zip'] 			= ($FieldsFast['zip'] == 0)?" ":$input['shipping_address']['zip'];
			
			$output['shipping_address']['countryID'] = ($FieldsFast['country'] == 0)?CONF_DEFAULT_COUNTRY:$input['shipping_address']['countryID'];
			$zonesS = znGetZonesById($output['shipping_address']['countryID']);
			$output['shipping_address']['zoneID'] = ($FieldsFast['state'] == 0)?$zonesS[0]['zoneID']:$input['shipping_address']['zoneID'];
			$output['shipping_address']['state']  = ($FieldsFast['state'] == 0)?" ":$input['shipping_address']['state'];
			
			$output['billing_as_shipping']  = ($FieldsFast['billing_as_shipping'] == 0)?1:$input['billing_as_shipping'];
			if(CONF_ORDERING_REQUEST_BILLING_ADDRESS && !$output['billing_as_shipping']){
				$output['billing_address']['first_name'] 	= ($FieldsFast['first_name'] == 0)?" ":$input['billing_address']['first_name'];
				$output['billing_address']['last_name'] 	= ($FieldsFast['last_name'] == 0)?" ":$input['billing_address']['last_name'];
				$output['billing_address']['address'] 		= ($FieldsFast['address'] == 0)?" ":$input['billing_address']['address'];
				$output['billing_address']['city'] 			= ($FieldsFast['city'] == 0)?" ":$input['billing_address']['city'];
				$output['billing_address']['zip'] 			= ($FieldsFast['zip'] == 0)?" ":$input['billing_address']['zip'];
				
				$output['billing_address']['countryID'] = ($FieldsFast['country'] == 0)?CONF_DEFAULT_COUNTRY:$input['billing_address']['countryID'];
				$zonesB = znGetZonesById($output['billing_address']['countryID']);
				$output['billing_address']['zoneID'] = ($FieldsFast['state'] == 0)?$zonesB[0]['zoneID']:$input['billing_address']['zoneID'];
				$output['billing_address']['state']	 = ($FieldsFast['state'] == 0)?" ":$input['billing_address']['state'];
			}
			
			$_POST['shippingMethodID'] = '';
			$_POST['shippingServiceID'] = '';
			$_POST['paymentMethodID'] = '';
			foreach($additional_fields as $key=>$Field){
				$output['customer_info']['_custom_fields'][$Field['reg_field_ID']] 	= ($FieldsFast['additional_field_'.$Field['reg_field_ID']] == 0)?" ":$input['customer_info']['_custom_fields'][$Field['reg_field_ID']];
			}
		}
	}
		
	//Яндекс быстрый заказ
	function OneStepOrder_YandexAdress($input, &$output){
		$address = json_decode(urldecode($input['address']), true);
		if (empty($address)) return false;
		$FieldsStandart = json_decode(CONF_ONESTEPORDER_FIELDS_STANDART, true);
		$output = array();
		
		$output['first_name'] 	= ($FieldsStandart['first_name'] != 0)?$address['firstname']:false;
		$output['last_name'] 	= ($FieldsStandart['last_name'] != 0)?$address['lastname']:false;
		$output['Email'] 		= ($FieldsStandart['Email'] != 0)?$address['email']:false;
		$output['city'] 		= ($FieldsStandart['city'] != 0)?$address['city']:false;
		$output['zip'] 			= ($FieldsStandart['zip'] != 0)?$address['zip']:false;
		$output['countryID'] 	= ($FieldsStandart['country'] != 0)?$address['country']:false;
		
		$selected = json_decode(_getSettingOptionValue( 'CONF_ONESTEPORDER_YANDEX_ADRESS_FIELDS' ), true);
	
		$adress	= '';
		$adress.= ($FieldsStandart['zip'] == 0 && $address['zip'] && !in_array('zip',$selected))?$address['zip'].' ':'';
		$adress.= ($FieldsStandart['country'] == 0 && $address['country']  && !in_array('country',$selected))?$address['country'].' ':'';
		$adress.= ($FieldsStandart['city'] == 0 && $address['city']  && !in_array('city',$selected))?" г.".$address['city'].' ':'';
		$adress.= ($address['street']  && !in_array('street',$selected))?"ул. ".$address['street']:'';
		$adress.= ($address['building']  && !in_array('building',$selected))?", дом ".$address['building']:'';
		$adress.= ($address['suite']  && !in_array('suite',$selected))?", строение ".$address['suite']:'';
		$adress.= ($address['flat']  && !in_array('flat',$selected))?", кв. ".$address['flat']:'';
		$adress.= ($address['entrance']  && !in_array('entrance',$selected))?", подъезд ".$address['entrance']:'';
		$adress.= ($address['floor']  && !in_array('floor',$selected))?", этаж ".$address['floor']:'';
		$adress.= ($address['intercom']  && !in_array('intercom',$selected))?", домофон ".$address['intercom']:'';
		
		$output['address'] 		= ($FieldsStandart['address'] != 0)?$adress:false;
		$output['comment'] 		= ($address['comment'])?$address['comment']:false;
		
		$output['additional_fields'] = array();
		$additional_fields = GetRegFields();
		foreach($additional_fields as $key => $field){	
			$output['additional_fields'][$field['reg_field_ID']]=$address[$selected[$field['reg_field_ID']]];
		}
		return true;
		
	}
	
	
	//Выбранная конфигурация
	function OneStepOrder_GetOptionsIDs($variants){
		$return = array();
		$variants = array_map('intval',$variants);
		if(!empty($variants)){
		$sql = 'SELECT optionID, variantID FROM ?#PRODUCTS_OPTIONS_VALUES_VARIANTS_TABLE WHERE variantID IN(?@)';
		$variants_from_bd = db_phquery_fetch(DBRFETCH_ASSOC_ALL, $sql,$variants);
		foreach($variants_from_bd as $key => $val){
			$return[$val['optionID']]=$val['variantID'];
		
		}
		}
		return $return;
	}
	
	//Массив товара в корзине
	function OneStepOrder_GetProductInCart($productID, $variants, $qty ){
		$customerEntry = Customer::getAuthedInstance();
		if(!is_null($customerEntry)){
			$q = db_phquery('
			SELECT t3.*, t1.itemID, t1.Quantity, t4.thumbnail FROM ?#SHOPPING_CARTS_TABLE t1
				LEFT JOIN ?#SHOPPING_CART_ITEMS_TABLE t2 ON t1.itemID=t2.itemID
				LEFT JOIN ?#PRODUCTS_TABLE t3 ON t2.productID=t3.productID
				LEFT JOIN ?#PRODUCT_PICTURES t4 ON t3.default_picture=t4.photoID
			WHERE customerID=?', $customerEntry->customerID);
			while ($cart_item = db_fetch_assoc($q)){
				// get variants
				$variants=GetConfigurationByItemId( $cart_item["itemID"] );
				LanguagesManager::ml_fillFields(PRODUCTS_TABLE, $cart_item);
				$costUC = GetPriceProductWithOption( $variants, $cart_item["productID"]);
				$tmp = array(
				"productID" => $cart_item["productID"],
				"slug" => $cart_item["slug"],
				"id" =>	$cart_item["itemID"],
				"name" =>	$cart_item["name"],
				'thumbnail_url' => $cart_item['thumbnail']&&file_exists(DIR_PRODUCTS_PICTURES.'/'.$cart_item['thumbnail'])?URL_PRODUCTS_PICTURES.'/'.$cart_item['thumbnail']:'',
				"brief_description"	=>	$cart_item["brief_description"],
				"quantity"	=>	$cart_item["Quantity"],
				"free_shipping"	=>	$cart_item["free_shipping"],
				"costUC"	=>	$costUC,
				"cost" => show_price($cart_item["Quantity"]*$costUC),
				"product_code" => $cart_item["product_code"],
				"in_stock" => (CONF_CHECKSTOCK)?$cart_item["in_stock"]:100000,
				"extra" =>  GetExtraParametrs($cart_item["productID"]),
				"configurations" =>  OneStepOrder_GetOptionsIDs(GetConfigurationByItemId($cart_item["itemID"])),
				);
				if($tmp['thumbnail_url']){
					list($thumb_width, $thumb_height) = getimagesize(DIR_PRODUCTS_PICTURES.'/'.$cart_item['thumbnail']);
					list($tmp['thumbnail_width'], $tmp['thumbnail_height']) = shrink_size($thumb_width, $thumb_height, round(CONF_PRDPICT_THUMBNAIL_SIZE/2), round(CONF_PRDPICT_THUMBNAIL_SIZE/2));
				}
				$freight_cost += $cart_item["Quantity"]*$cart_item["shipping_freight"];
				$strOptions=GetStrOptions(GetConfigurationByItemId( $tmp["id"] ));
				if(trim($strOptions) != "")
				$tmp["name"].="  (".$strOptions.")";
				if ( $cart_item["min_order_amount"] > $cart_item["Quantity"] )
				$tmp["min_order_amount"] = $cart_item["min_order_amount"];
				$cart_content = $tmp;
			}
		}else{
			$q = db_phquery("SELECT t1.*, p1.thumbnail FROM ?#PRODUCTS_TABLE t1 LEFT JOIN ?#PRODUCT_PICTURES p1 ON t1.default_picture=p1.photoID WHERE t1.productID=?", $productID);
			if ($r = db_fetch_row($q)){
				LanguagesManager::ml_fillFields(PRODUCTS_TABLE, $r);
				$costUC = GetPriceProductWithOption($variants, $productID);
				$id = $productID;
				if (count($variants) > 0){
					for ($tmp1=0;$tmp1<count($variants);$tmp1++) $id .= "_".$variants[$tmp1];
				}
				$tmp = array(
					"productID"	=>  $productID,
					"slug"	=>  $r['slug'],
					"id"		=>	$id,
					"name"		=>	$r['name'],
					'thumbnail_url' => $r['thumbnail']&&file_exists(DIR_PRODUCTS_PICTURES.'/'.$r['thumbnail'])?URL_PRODUCTS_PICTURES.'/'.$r['thumbnail']:'',
					"brief_description"	=> $r["brief_description"],
					"quantity"	=>	$qty,
					"free_shipping"	=>	$r["free_shipping"],
					"costUC"	=>	$costUC,
					"cost"		=>	show_price($costUC * $qty),
					"in_stock" => (CONF_CHECKSTOCK)?$r["in_stock"]:100000,
					"product_code" => $r["product_code"],
					"extra" =>  GetExtraParametrs($productID),
					"configurations" =>  OneStepOrder_GetOptionsIDs($variants)
				);
				if($tmp['thumbnail_url']){
					list($thumb_width, $thumb_height) = getimagesize(DIR_PRODUCTS_PICTURES.'/'.$r['thumbnail']);
					list($tmp['thumbnail_width'], $tmp['thumbnail_height']) = shrink_size($thumb_width, $thumb_height, round(CONF_PRDPICT_THUMBNAIL_SIZE/2), round(CONF_PRDPICT_THUMBNAIL_SIZE/2));
				}
				$strOptions=GetStrOptions( $variants);
				if ( trim($strOptions) != "" ) $tmp["name"].="  (".$strOptions.")";
					$q_product = db_query( "select min_order_amount, shipping_freight from ".PRODUCTS_TABLE." where productID=".$productID );
					$product = db_fetch_row( $q_product );
				if ( $product["min_order_amount"] > $_qty ) $tmp["min_order_amount"] = $product["min_order_amount"];
				$cart_content = $tmp;
			}
		}
		return $cart_content;
	}
	
	//Установить конфигурацию
	function OneStepOrder_SetOptions($POST){
		$qty = $POST['count_'.$POST['itemID']];
		$qty = max(0,intval($qty));
		$productID = intval($POST['productID']);
		$product_data = GetProduct($productID);
		if(!$product_data['ordering_available'])return false;
		if(!$product_data['enabled'])return false;
		$is = intval($product_data['in_stock']);
		$min_order_amount = $product_data['min_order_amount'];
		
		$variants=array();
		foreach($POST as $key => $val ) {
			if(!strstr($key, 'option_'))continue;
			if(!$val)continue;
			$variants[] = $val;
		}
		if (!isset($_SESSION["log"])){
			if (!isset($_SESSION["gids"])){
				$_SESSION["gids"] = array();
				$_SESSION["counts"] = array();
				$_SESSION["configurations"] = array();
			}
			$item_index=SearchConfigurationInSessionVariable( $variants, $productID );
			if ( $item_index!=-1 ){	
				$qty = min($qty,$is - $_SESSION["counts"][$item_index]);
				$qty = max($qty,0);
				$itemID = $_SESSION["gids"][$item_index];
				if (count($_SESSION["configurations"][$item_index]) > 0){
					for ($tmp1=0;$tmp1<count($_SESSION["configurations"][$item_index]);$tmp1++) $itemID .= "_".$_SESSION["configurations"][$item_index][$tmp1];
				}
				if($itemID != $POST['itemID']){
					$_SESSION["counts"][$item_index] += $qty;
					$cartEntry = new ShoppingCart();
					$cartEntry->loadCurrentCart();
					$cartEntry->setItemQuantity($POST['itemID'], 0);
					$cartEntry->saveCurrentCart();
					$cart_content = OneStepOrder_GetProductInCart($_SESSION["gids"][$item_index], $_SESSION["configurations"][$item_index], $_SESSION["counts"][$item_index] );	
					$cart_content['removeElement'] = $POST['itemID'];
					
					$id = $_SESSION["gids"][$item_index];
					if (count($_SESSION["configurations"][$item_index]) > 0){
						for ($tmp1=0;$tmp1<count($_SESSION["configurations"][$item_index]);$tmp1++) $id .= "_".$_SESSION["configurations"][$item_index][$tmp1];
					}
					$cart_content['updateElement'] = $id;
				}
			}else{
				$qty = max($qty,$min_order_amount,0);
				if(CONF_CHECKSTOCK!=0){
					$qty = min($qty,$is);
				}
				$qty = max($qty,0);
				$_SESSION["gids"][] = $productID;
				$_SESSION["counts"][] = $qty;
				$_SESSION["configurations"][]=$variants;
				cartUpdateAddCounter($productID);
				$cartEntry = new ShoppingCart();
				$cartEntry->loadCurrentCart();
				$cartEntry->setItemQuantity($POST['itemID'], 0);
				$cartEntry->saveCurrentCart();
						
				$cart_content = OneStepOrder_GetProductInCart($productID, $variants, $qty );
				$cart_content['updateElement'] = $POST['itemID'];
				$cart_content['removeElement'] = "";
			}
		}else{ 
			$itemID = SearchConfigurationInDataBase($variants, $productID );
			$customerEntry = Customer::getAuthedInstance();
			if(is_null($customerEntry))return false;
			if ( $itemID !=-1 ){
				$quantity = db_phquery_fetch(DBRFETCH_FIRST, "SELECT Quantity FROM ?#SHOPPING_CARTS_TABLE WHERE customerID=? AND itemID=?", $customerEntry->customerID, $itemID);
				$qty = min($qty,$is-$quantity);
				$qty = max($qty,0);
				
				if($itemID != $POST['itemID']){
					db_phquery("UPDATE ?#SHOPPING_CARTS_TABLE SET Quantity=? WHERE customerID=? AND itemID=?", $quantity+$qty, $customerEntry->customerID, $itemID);
					
					$cartEntry = new ShoppingCart();
					$cartEntry->loadCurrentCart();
					$cartEntry->setItemQuantity($POST['itemID'], 0);
					$cartEntry->saveCurrentCart();
					$cart_content = OneStepOrder_GetProductInCart($itemID, $variants, $quantity+$qty );	
					$cart_content['removeElement'] = $POST['itemID'];
					$cart_content['updateElement'] = $itemID;
				}
			}else{
				$qty = max($qty,$min_order_amount);
				$qty = min($qty,$is);
				$itemID=InsertNewItem($variants, $productID );
				InsertItemIntoCart($itemID);
				db_phquery("UPDATE ?#SHOPPING_CARTS_TABLE SET Quantity=? WHERE customerID=? AND itemID=?",
				$qty, $customerEntry->customerID, $itemID);
				cartUpdateAddCounter($productID);
				
				$cartEntry = new ShoppingCart();
				$cartEntry->loadCurrentCart();
				$cartEntry->setItemQuantity($POST['itemID'], 0);
				$cartEntry->saveCurrentCart();
						
				$cart_content = OneStepOrder_GetProductInCart($productID, $variants, $qty );
				$cart_content['updateElement'] = $POST['itemID'];
				$cart_content['removeElement'] = "";
			}
		}
		return $cart_content;	
	}


?>