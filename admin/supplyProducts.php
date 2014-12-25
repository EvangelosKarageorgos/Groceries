<?php
require_once dirname(__FILE__)."/../Code/init.php";
Application::getAuth()->enterAdminPage();

	$productsModel = array();
	Application::getDB()->WhileReader("select prod_code, name, procur_qty from products where qty_on_hand < procur_level", function(&$r) use(&$productsModel, &$suppliersModel){
		$productsModel[] = array('prodCode' => $r['prod_code'], 'name' => $r['name'], 'qty' => $r['procur_qty'], 'supName' => "", 'supEmail' => '', 'noname' => false, 'noemail' => false, 'nameexists' => false, 'newexpanded' => false);
	});

	switch(Application::getRequest()->getPostParam("submittype", ""))
	{
		case "add-supplier":
			$prodCode = Application::getRequest()->getPostParam("prodCode", "");
			if(strlen($prodCode)>0){
				$suppliername = Application::getRequest()->getPostParam("suppliername", "");
				$supplieremail = Application::getRequest()->getPostParam("supplieremail", "");
				var_dump($suppliername);
				$check = true;
				$noname = false;
				$nameexists = false;
				$noemail = false;
				if(strlen($suppliername)==0){
					$check = false;
					$noname = true;
				}
				if(strlen($supplieremail)==0){
					$check = false;
					$noemail = true;
				}
				if($check){
					if(Application::getDB()->ExecuteScalar("select supply_product_new_supplier('?', '?', '?') as s", $prodCode, $suppliername, $supplieremail)<0){
						$nameexists = true;
						$check = false;
					}
				}
				if($check){
					foreach($productsModel as $k => &$p)
						if(strcmp($prodCode, $p['prodCode'])==0)
							unset($productsModel[$k]);
					
				} else {
					foreach($productsModel as &$p)
						if(strcmp($prodCode, $p['prodCode'])==0){
							var_dump($p['prodCode']."|".$prodCode);
							$p['noname'] = $noname;
							$p['nameexists'] = $nameexists;
							$p['noemail'] = $noemail;
							$p['supName'] = $suppliername;
							$p['supEmail'] = $supplieremail;
							$p['newexpanded'] = true;
						}
				}
			}
		break;
		case "supply":
			$prodCode = Application::getRequest()->getPostParam("prodCode", "");
			$supplierId = intval(Application::getRequest()->getPostParam("supplierId", "-1"));
			if(strlen($prodCode)>0 && $supplierId>=0){
				Application::getDB()->ExecuteNonQuery("call supply_product(?, '?')", $supplierId, $prodCode);
				foreach($productsModel as $k => &$p)
					if(strcmp($prodCode, $p['prodCode'])==0)
						unset($productsModel[$k]);				
			}
		break;
		default:
		break;
	}
	
	$suppliersModel = array();
	Application::getDB()->WhileReader("select distinct supplier_id, supplier_name from suppliers order by supplier_name", function(&$r) use(&$suppliersModel){
		$suppliersModel[] = array('supplierId' => $r['supplier_id'], 'name' => $r['supplier_name']);
	});
	foreach($productsModel as &$p)
		$p['suppliers'] = $suppliersModel;
?>

<?= renderTemplate(dirname(__FILE__)."/adminSideArea.php", array()) ?>
<div class="itemArea admin-supply-products">
	<?php
		foreach($productsModel as &$p){
		//var_dump($p);
			echo renderTemplate(dirname(__FILE__)."/../Templates/Admin/ProductToSupply.php", $p);
		}
	?>
	
</div>
