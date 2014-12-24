<?php
	require_once dirname(__FILE__)."/../Code/Init.php";
	includeFootResource(new ScriptResource(Application::getRequest()->getBasePath()."/scripts/checkout.js"));
	Application::getAuth()->enterProtectedPage();

	$cart = Application::getCartManager()->getCart();
	$paymentMethod=0;
	$cardno = '';
	
	if(strcmp(Application::getRequest()->getPostParam("submitorder", ""), "true")==0){
		$cartid = intval(Application::getRequest()->getPostParam("cartid", "-1"));
		$customerno = intval(Application::getRequest()->getPostParam("customerno", "-1"));
		$cardno = Application::getRequest()->getPostParam("cardno", "");
		$agree = Application::getRequest()->getPostParam("agree", "");
		if(		$cartid == $cart->cartid
			&&	$customerno == Application::getAuth()->getCustomerNum()
			&&	strlen($cardno)>0
			&&	strcmp($agree, "yes")==0
			)
		{
			var_dump("Submitting order...");
			$order_no = Application::getDB()->ExecuteScalar("select convert_cart_to_order(?)", $cartid);
			var_dump($order_no);
			if($order_no>=0){
				Application::getDB()->ExecuteNonQuery("call create_transaction(?, '?')", $order_no, $cardno);
				WebTools::redirect(Application::getRequest()->getBasePath()."/checkout/invoice.php?orderno=".$order_no);
			}
		}
	}
	
	$cartModel = $cart->toModel();
	$cartModel['itemTemplate'] = dirname(__FILE__)."/../Templates/OrderCartItem.php";
	
	$invoice = renderTemplate(dirname(__FILE__)."/../Templates/OrderCart.php", $cartModel);

?>
<div class="checkout-form">
	<form id="proceed-form" method="POST">
		<input type="hidden" name="submitorder" value="true"/>
		<input type="hidden" name="customerno" value="<?= Application::getAuth()->getCustomerNum() ?>" />
		<input type="hidden" name="cartid" value="<?= $cart->cartid ?>" />
		<span>Please type your credit card number :</span>
		<input type="text" name="cardno" value="<?= $cardno ?>" placeholder="cretid card no." />
		<div class="error cardno hidden"></div>
		<input type="checkbox" name="agree" value="yes" >I aggree with the site's terms and conditions</input>
		<input type="submit" class="groceriesBtn proceed-button" name="submit" value="Proceed" />
		<?php /*<input type="submit" value="submit"/>*/ ?>
	</form>
	<?= $invoice ?>
</div>
