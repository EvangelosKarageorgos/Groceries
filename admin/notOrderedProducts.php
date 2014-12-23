<?php
require_once dirname(__FILE__)."/../Code/init.php";
Application::getAuth()->enterProtectedPage();

	$queryControls = GroceriesTools::getAdminBasicQueryControls();
	
	$queryString = " SELECT products.prod_code AS Code, products.name AS Product, product_groups.group_name AS GroupName " 
				." FROM products "
				." INNER JOIN product_groups ON (products.prod_group=product_groups.group_code) "
				." WHERE products.prod_code NOT IN ( "
				."    SELECT distinct order_details.prod_code "
				."    FROM orders " 
				."    INNER JOIN order_details ON (orders.order_no=order_details.order_no) "
				."    WHERE order_date between '?' AND '?' " 
				." ) "
				." ORDER BY products.name ASC " 
				." LIMIT ? ";
	
	$results = Application::getDB()->ExecuteDataTable($queryString,
		$queryControls['model']['dateFrom'], $queryControls['model']['dateTo'], $queryControls['model']['resultsCount']);

?>

<?= renderTemplate(dirname(__FILE__)."/adminSideArea.php", array()) ?>
<div class="itemArea">
	<?= $queryControls['markup'] ?>
	<?= $results->toHtml() ?>
</div>
