<?php
require_once dirname(__FILE__)."/../Code/init.php";
Application::getAuth()->enterAdminPage();

	$queryControls = GroceriesTools::getAdminBasicQueryControls();
	
	$queryString = " SELECT suppliers.supplier_name AS Supplier, SUM(suppliers.quant_sofar) AS 'Total Amount'	" 
				." FROM suppliers "
				." GROUP BY suppliers.supplier_id " 
				." ORDER BY  SUM(suppliers.quant_sofar) desc " 
				." LIMIT ? ";
	
	$results = Application::getDB()->ExecuteDataTable($queryString , $queryControls['model']['resultsCount']);
		//$queryControls['model']['dateFrom'], $queryControls['model']['dateTo'], $queryControls['model']['resultsCount']);

?>

<?= renderTemplate(dirname(__FILE__)."/adminSideArea.php", array()) ?>
<div class="itemArea">
	<?= $queryControls['markup'] ?>
	<?= $results->toHtml() ?>
</div>
