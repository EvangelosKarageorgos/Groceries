<?php
require_once dirname(__FILE__)."/../Code/init.php";
Application::getAuth()->enterProtectedPage();
echo renderTemplate(dirname(__FILE__)."/../Templates/Account/accountMenu.php", array());
?>


<?php
	$orders = array();
	Application::getDB()->WhileReader("select order_no from orders where cust_no=?", function(&$r) use (&$orders){
		$order = new Order();
		$order->load(intval($r['order_no']));
		array_push($orders, $order->toModel());
	}, Application::getAuth()->getCustomerNum());
	var_dump($orders);
?>
