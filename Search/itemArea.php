

<div class="itemArea">

	<?php
		$products = array();
		Application::getDB()->WhileReader("select * from products p inner join product_groups pg on pg.group_code = p.prod_group order by name", function(&$row) use(&$products){
			$p = new Product();
			$p->loadFromRow($row);
			array_push($products, $p->toModel());
		});
		$list = array("items" => $products, "itemTemplate" => dirname(__FILE__)."/../Templates/ProductListItem.php");	
		
		echo renderTemplate(dirname(__FILE__)."/../Templates/List.php", $list);
	?>
	
</div>






	
