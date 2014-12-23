<?php
require_once dirname(__FILE__)."/../Code/init.php";
Application::getAuth()->enterProtectedPage();

	$queryControls = GroceriesTools::getAdminBasicQueryControls();
	
	$results = Application::getDB()->ExecuteDataTable("select * from orders where order_date between '?' and '?'  order by order_date desc limit ?",
		$queryControls['model']['dateFrom'], $queryControls['model']['dateTo'], $queryControls['model']['resultsCount']);

?>

<?= renderTemplate(dirname(__FILE__)."/adminSideArea.php", array()) ?>
<div class="itemArea">
	<?= $queryControls['markup'] ?>
	<?= $results->toHtml() ?>
</div>
