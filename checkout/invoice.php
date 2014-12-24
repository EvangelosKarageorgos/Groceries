<?php
	require_once dirname(__FILE__)."/../Code/Init.php";
	includeFootResource(new ScriptResource(Application::getRequest()->getBasePath()."/scripts/checkout.js"));
	Application::getAuth()->enterProtectedPage();
	
	$orderno = intval(Application::getRequest()->getGetParam("orderno", "-1"));
	if($orderno>=0){
		$order = new Order();
		$order->load($orderno);
	}

	$orderModel = $order->toModel();
	$orderModel['itemTemplate'] = dirname(__FILE__)."/../Templates/OrderItem.php";
	
	$invoice = renderTemplate(dirname(__FILE__)."/../Templates/Invoice.php", $orderModel);

?>
<div class="checkout-form">
	<h4>Your order has been submitted</h4>
	<?= $invoice ?>
</div>
