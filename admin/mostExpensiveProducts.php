<?php
require_once dirname(__FILE__)."/../Code/init.php";
Application::getAuth()->enterProtectedPage();

	$queryControls = GroceriesTools::getAdminBasicQueryControls();
	
	$queryString = " SELECT prods.prod_code AS Code, prods.name AS Product, group_name AS GroupName, list_price AS Price " 
				." FROM products AS prods" 
				." INNER JOIN product_groups ON (prods.prod_group=product_groups.group_code) " 
				." WHERE prods.list_price = ( SELECT MAX(pr.list_price) FROM products pr WHERE pr.prod_group = prods.prod_group ) "
				." ORDER BY list_price DESC ";

	
	$results = Application::getDB()->ExecuteDataTable($queryString);

?>

<?= renderTemplate(dirname(__FILE__)."/adminSideArea.php", array()) ?>
<div class="itemArea">
	<?= $queryControls['markup'] ?>
	<?= $results->toHtml() ?>
</div>
