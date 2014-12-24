<?php
require_once dirname(__FILE__)."/../Code/init.php";
Application::getAuth()->enterProtectedPage();

	$queryControls = GroceriesTools::getAdminBasicQueryControls();
	
	$queryString = " SELECT products.prod_code AS Code, products.name AS Product, product_groups.group_name AS GroupName, " 
					."qty_on_hand AS QoH, procur_level AS MinStockAllowed, procur_qty AS ProcurQty	 " 
				." FROM products " 
				." INNER JOIN product_groups ON (products.prod_group=product_groups.group_code) "
				." WHERE qty_on_hand<=procur_level " 
				." ORDER BY  qty_on_hand ASC ";
	
	$results = Application::getDB()->ExecuteDataTable($queryString);

?>

<?= renderTemplate(dirname(__FILE__)."/adminSideArea.php", array()) ?>
<div class="itemArea">
	<?= $queryControls['markup'] ?>
	<?= $results->toHtml() ?>
</div>
