<?php
require_once dirname(__FILE__)."/../Code/init.php";
Application::getAuth()->enterProtectedPage();

	$queryControls = GroceriesTools::getAdminBasicQueryControls();
	
	$queryString = " SELECT users.login AS 'Login', users.cust_name AS FullName,  SUM(order_details.order_sum) AS 'Total Amount'	" 
				." FROM orders " 
				." INNER JOIN order_details ON (orders.order_no=order_details.order_no) "
				." INNER JOIN users ON (users.cust_no=orders.cust_no) "
				." WHERE order_date between '?' and '?' " 
				." GROUP BY users.login " 
				." ORDER BY  SUM(order_details.order_sum) desc " 
				." LIMIT ? ";
	
	$results = Application::getDB()->ExecuteDataTable($queryString,
		$queryControls['model']['dateFrom'], $queryControls['model']['dateTo'], $queryControls['model']['resultsCount']);

?>

<?= renderTemplate(dirname(__FILE__)."/adminSideArea.php", array()) ?>
<div class="itemArea">
	<?= $queryControls['markup'] ?>
	<?= $results->toHtml() ?>
</div>
