<?php
require_once dirname(__FILE__)."/../Code/init.php";
Application::getAuth()->enterAdminPage();

		
	
	
	$suppliersModel = array();
	Application::getDB()->WhileReader("select distinct supplier_id, supplier_name from suppliers", function(&$r) use(&$suppliersModel){
		$suppliersModel[] = array('supplierId' => $r['supplier_id'], 'name' => $r['supplier_name']);
	});
	
	$productsModel = array();
	Application::getDB()->WhileReader("select prod_code, name, procur_qty from products where qty_on_hand < procur_level", function(&$r) use(&$productsModel, &$suppliersModel){
		$productsModel[] = array('prodCode' => $r['prod_code'], 'name' => $r['name'], 'qty' => $r['procur_qty'], 'suppliers' => $suppliersModel);
	});
?>

<?= renderTemplate(dirname(__FILE__)."/adminSideArea.php", array()) ?>
<?php if(strlen(Application::getRequest()->getPostParam("submittype", ""))==0) { ?>
<div class="itemArea">
	<?php
		foreach($productsModel as &$p){
			echo renderTemplate(dirname(__FILE__)."/../Templates/Admin/ProductToSupply.php", $p);
		}
	?>
	<form method="POST">
		<input type="hidden" name="submittype" value="add-supplier" />
		<input type="text" name="suppliername" value="" placeholder="name" />
		<input type="submit" class="croceriesBtn" value="Add supplier" />
	</form>
	
</div>
<?php } ?>