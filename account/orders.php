<?php
require_once dirname(__FILE__)."/../Code/init.php";
Application::getAuth()->enterProtectedPage();
echo renderTemplate(dirname(__FILE__)."/../Templates/Account/accountMenu.php", array('selected'=>'orders'));
?>


<?php
	$orders = array();
	Application::getDB()->WhileReader("select order_no from orders where cust_no=? order by order_date desc", function(&$r) use (&$orders){
		$order = new Order();
		$order->load(intval($r['order_no']));
		$orderModel = $order->toModel();
		$orderModel['itemTemplate'] = dirname(__FILE__)."/../Templates/Account/OrderItem.php";
		array_push($orders, $orderModel);
	}, Application::getAuth()->getCustomerNum());
	//$orders['itemTemplate'] = dirname(__FILE__)."/../Templates/Account/OrdersListItem.php";
	
	foreach($orders as &$order){
		echo renderTemplate(dirname(__FILE__)."/../Templates/Account/Order.php", $order);
		
	}
	var_dump($orders);
?>
