<?php
class SuccessController extends ActionsController {
	
	function main(){
		
		$Register = &Register::getInstance();
		/*@var $Register Register*/
		$smarty = &$Register->get(VAR_SMARTY);
		/*@var $smarty Smarty*/
		$checkoutEntry = &Checkout::getInstance(_CHECKOUT_INSTANCE_NAME);
		
		if(!(isset($_GET["orderID"]) && isset($_SESSION["newoid"]) && (int)$_SESSION["newoid"] == (int)$_GET["orderID"]))
			RedirectSQ('?');
		
		$cartEntry = new ShoppingCart();
		$cartEntry->cleanCurrentCart();
		$paymentMethod = payGetPaymentMethodById($checkoutEntry->paymentMethodID());
		$currentPaymentModule = PaymentModule::getInstance($paymentMethod['module_id']);
	
		if ( $currentPaymentModule != null ){
			$after_processing_html = $currentPaymentModule->after_processing_html($_GET['orderID']);
		}else{
			$after_processing_html = '';
		}
		$smarty->assign( 'after_processing_html', $after_processing_html );

		$smarty->assign( 'order_success', 1 );
	
		$smarty->assign('main_content_template', 'checkout.success.html');
	}
}
?>