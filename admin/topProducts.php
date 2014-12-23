<?php
require_once dirname(__FILE__)."/../Code/init.php";
Application::getAuth()->enterAdminPage();

	$queryControls = GroceriesTools::getAdminBasicQueryControls();
	
	$queryString = " SELECT products.prod_code AS Code, products.name AS Product, product_groups.group_name AS GroupName, SUM(order_details.order_sum) AS 'Total Amount'	" 
				." FROM orders " 
				." INNER JOIN order_details ON (orders.order_no=order_details.order_no) "
				." INNER JOIN products ON (products.prod_code=order_details.prod_code) "
				." INNER JOIN product_groups ON (products.prod_group=product_groups.group_code) "
				." WHERE order_date between '?' and '?' " 
				." GROUP BY products.prod_code " 
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
