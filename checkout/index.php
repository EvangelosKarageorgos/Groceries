<?php
	require_once dirname(__FILE__)."/../Code/Init.php";
	includeFootResource(new ScriptResource(Application::getRequest()->getBasePath()."/scripts/checkout.js"));
	Application::getAuth()->enterProtectedPage();

	$cart = Application::getCartManager()->getCart();
	
	if(strcmp(Application::getRequest()->getPostParam("submitorder", ""), "true")==0){
		$cartid = intval(Application::getRequest()->getPostParam("cartid", "-1"));
		$custumerno = intval(Application::getRequest()->getPostParam("customerno", "-1"));
		$cardno = Application::getRequest()->getPostParam("cardno", "");
		$agree = Application::getRequest()->getPostParam("agree", "");
		if(		$cartid == $cart->cartid
			&&	$custumerno == Application::getAuth()->getCustomerNum()
			&&	strlen($cardno)>0
			&&	strcmp($agree, "yes")==0
			)
		{
			var_dump("Submitting order...");
		}
	}
	
	$cartModel = $cart->toModel();
	$cartModel['itemTemplate'] = dirname(__FILE__)."/../Templates/OrderItem.php";
	
	$cardno = "grgerger";
	$invoice = renderTemplate(dirname(__FILE__)."/../Templates/Invoice.php", $cartModel);

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
		<input type="button" class="groceriesBtn proceed-button" name="submit" value="Proceed" />
		<input type="submit" value="submit"/>
	</form>
	<?= $invoice ?>
</div>
