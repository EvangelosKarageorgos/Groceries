<?php
require_once dirname(__FILE__)."/../Code/Application.php";
require_once dirname(__FILE__)."/../Code/Template.php";
require_once dirname(__FILE__)."/../Code/WebTools.php";

require_once dirname(__FILE__)."/../Code/Product.php";
require_once dirname(__FILE__)."/../Code/Group.php";

Application::getRequest()->setBasePath("/groceries");
require_once dirname(__FILE__)."/../Code/AjaxService.php";


AjaxService::addService("add", function($data){
	$cart = Application::getCartManager()->getCart();
	$added = $cart->add($data['code'], $data['qty']);
    return $added;
});

AjaxService::serve();