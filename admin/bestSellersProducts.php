<?php
require_once dirname(__FILE__)."/../Code/init.php";
Application::getAuth()->enterAdminPage();

	$queryControls = GroceriesTools::getAdminBasicQueryControls();
	
	
	$queryString = " SELECT prod.prod_code AS Code, prod.name AS Product, product_groups.group_name AS GroupName, SUM(order_details.order_sum) AS 'Total Amount'	" 
				." FROM orders " 
				." INNER JOIN order_details ON (orders.order_no=order_details.order_no) "
				." INNER JOIN products prod ON (prod.prod_code=order_details.prod_code) "
				." INNER JOIN product_groups ON (prod.prod_group=product_groups.group_code) "
				." WHERE order_date between '?' and '?' " 
				." 		AND prod.prod_code =  "
				."   		(  "
								." SELECT prd.prod_code " 
								." FROM orders " 
								." INNER JOIN order_details ON (orders.order_no=order_details.order_no) "
								." INNER JOIN products prd ON (prd.prod_code=order_details.prod_code) "
								." INNER JOIN product_groups prg ON (prd.prod_group=prg.group_code) "
								." WHERE order_date between '?' and '?' " 
								."         AND prg.group_code = prod.prod_group "
								." GROUP BY prg.group_code, prd.prod_code " 
								." ORDER BY  SUM(order_details.order_sum) desc " 
								." LIMIT 1 "				
				."			) 	"
				." GROUP BY product_groups.group_code, prod.prod_code " 
				." ORDER BY  SUM(order_details.order_sum) desc " ;				
		

	
	$results = Application::getDB()->ExecuteDataTable($queryString, $queryControls['model']['dateFrom'], $queryControls['model']['dateTo'], $queryControls['model']['dateFrom'], $queryControls['model']['dateTo']);

?>

<?= renderTemplate(dirname(__FILE__)."/adminSideArea.php", array()) ?>
<div class="itemArea">
	<?= $queryControls['markup'] ?>
	<?= $results->toHtml() ?>
</div>
