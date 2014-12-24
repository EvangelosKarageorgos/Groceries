<?php
require_once dirname(__FILE__)."/../Code/init.php";
Application::getAuth()->enterAdminPage();



	$supplier1 = -1;
	$supplier2 = -1;
	
	$supplier1 = intval(Application::getRequest()->getGetParam("supplier1", "-1"));
	$supplier2 = intval(Application::getRequest()->getGetParam("supplier2", "-1"));
	
	$suppliersModel = array();
	Application::getDB()->WhileReader("select distinct s.supplier_id, (select supplier_name from suppliers where supplier_id = s.supplier_id limit 1) as supplier_name from suppliers s", function(&$r) use(&$suppliersModel){
		$suppliersModel[] = array('supplierId' => $r['supplier_id'], 'name' => $r['supplier_name']);
	});
	
	$showSeperation = false;
	if($supplier1>=0 and $supplier2>=0){
		$showSeperation = true;
		$seperation = 0;
		
		$table = array();
		Application::getDB()->WhileReader("select distinct supplier_id, prod_code from suppliers", function(&$r) use (&$table){
			if(isset($table[intval($r['supplier_id'])]))
				$table[intval($r['supplier_id'])][] = $r['prod_code'];
			else
				$table[intval($r['supplier_id'])] = array($r['prod_code']);
			
			//$table[intval($r['supplier_id'])] = $r['prod_code'];
		});
		var_dump($table);
		$searchSet = array();
		foreach($table as $k => $v)
			if($k!=$supplier1)
				$searchSet[] = $k;
		$seperation = GroceriesTools::searchSeperation($table, $searchSet, $table[$supplier1], $table[$supplier2], 0);
		
		// --- seperation calculation
		
		
		
	}
	
?>

<?= renderTemplate(dirname(__FILE__)."/adminSideArea.php", array()) ?>
<?php if(strlen(Application::getRequest()->getPostParam("submittype", ""))==0) { ?>
<div class="itemArea">
	<form method="GET">
		<select name="supplier1" >
			<option value="-1" <?= $supplier1==-1?" selected":""?>></option>
			<?php foreach($suppliersModel as &$supplier){ ?>
				<option value="<?= $supplier['supplierId'] ?>"<?= $supplier1==$supplier['supplierId']?" selected":""?>><?= $supplier['name'] ?></option>
			<?php } ?>
		</select>
		<select name="supplier2" >
			<option value="-1" <?= $supplier2==-1?" selected":""?>></option>
			<?php foreach($suppliersModel as &$supplier){ ?>
				<option value="<?= $supplier['supplierId'] ?>"<?= $supplier2==$supplier['supplierId']?" selected":""?>><?= $supplier['name'] ?></option>
			<?php } ?>
		</select>
		<input type="submit" class="groceriesBtn" value="Calculate"/>
	</form>
	<?php if($showSeperation){ ?>
	<div>
		Supliers have a seperation degree of <?= $seperation ?>
	</div>
	<?php } ?>
	
</div>
<?php } ?>