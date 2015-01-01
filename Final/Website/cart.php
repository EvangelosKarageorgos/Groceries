<?php
	require_once dirname(__FILE__)."/Code/Init.php";


	$cart = Application::getCartManager()->getCart();
	$cartModel = $cart->toModel();
	$cartModel['itemTemplate'] = dirname(__FILE__)."/Templates/CartItem.php";
	echo renderTemplate(dirname(__FILE__)."/Templates/Cart.php", $cartModel);

?>


