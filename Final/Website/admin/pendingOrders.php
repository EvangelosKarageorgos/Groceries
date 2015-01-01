<?php
require_once dirname(__FILE__)."/../Code/init.php";
Application::getAuth()->enterAdminPage();

	$queryControls = GroceriesTools::getAdminBasicQueryControls();
	
	$orders = array();
	Application::getDB()->WhileReader("select o.order_no, t.creditcard_no from orders o
			 inner join transactions t on t.order_no=o.order_no
			 where o.is_valid=1 and o.is_completed=0 and order_date between '?' and '?' order by order_date asc limit ?", function(&$r) use (&$orders){
		$order = new Order();
		$order->load(intval($r['order_no']));
		$orderModel = $order->toModel();
		$orderModel['orderNo'] = $order->orderno;
		$orderModel['creditcardNo'] = $r['creditcard_no'];
		$orderModel['itemTemplate'] = dirname(__FILE__)."/../Templates/Admin/OrderItem.php";
		
		array_push($orders, $orderModel);
	}, $queryControls['model']['dateFrom'], $queryControls['model']['dateTo'], $queryControls['model']['resultsCount']);
	

	

?>

<?= renderTemplate(dirname(__FILE__)."/adminSideArea.php", array()) ?>
<div class="itemArea">
	<?= $queryControls['markup'] ?>
	<?php
	foreach($orders as &$order){
		echo renderTemplate(dirname(__FILE__)."/../Templates/Admin/PendingOrder.php", $order);
	}
	?>
</div>
	<script>
		$(".order-box").each(function(jj, wrapper){
			$(wrapper).find('.complete-action').click(function(){
				completeOrder(wrapper);
			});
			$(wrapper).find('.cancel-action').click(function(){
				cancelOrder(wrapper);
			});
		});
	</script>
