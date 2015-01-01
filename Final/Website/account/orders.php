<?php
require_once dirname(__FILE__)."/../Code/init.php";
Application::getAuth()->enterProtectedPage();
echo renderTemplate(dirname(__FILE__)."/../Templates/Account/accountMenu.php", array('selected'=>'orders'));
?>


<?php
	$orders = array();
	Application::getDB()->WhileReader("select order_no, case is_completed when 0 then 'Pending' else 'Completed' end as status from orders where cust_no=? and is_valid=1 order by order_date desc", function(&$r) use (&$orders){
		$order = new Order();
		$order->load(intval($r['order_no']));
		$orderModel = $order->toModel();
		$orderModel['status'] = $r['status'];
		$orderModel['itemTemplate'] = dirname(__FILE__)."/../Templates/Account/OrderItem.php";
		array_push($orders, $orderModel);
	}, Application::getAuth()->getCustomerNum());
	

?>
<div class="searchbox">
</div>
<div class="itemArea">
<div class="account-orders-list">
<?php
	foreach($orders as &$order){
		echo renderTemplate(dirname(__FILE__)."/../Templates/Account/Order.php", $order);
	}
?>
</div>
</div>