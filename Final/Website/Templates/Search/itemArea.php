<?php
	$whereClause = '';
	
	$orderClause = '';
	
	$whcf = true;

	$searchGroups = Application::getRequest()->getGetParam("groups", "");
	$searchGroups = strlen($searchGroups)>0?explode(',', $searchGroups) : array();
	$fg = true;
	$sge = false;
	$sgic = '';
	foreach($searchGroups as $sg){
		$sge = true;
		$sgic = $sgic.($fg?'':',')."'".$sg."'";
		$fg = false;
	}
	if($sge){
		$whereClause = $whereClause.($whcf?' where ': ' and ')."p.prod_group in (".$sgic.")";
		$whcf = false;
	}

	$text = Application::getRequest()->getGetParam("text", "");
	if(strlen($text)>0){
		
		$qn = "p.name like '%{0}%'";
		$qd = "p.description like '%{0}%'";
		$qg = "pg.group_name like '%{0}%'";
		$q = str_replace("{0}", $text, "(".$qn." or ".$qd." or ".$qg.")");
		$whereClause = $whereClause.($whcf?' where ': ' and ').$q;
		$whcf = false;
	}

	$priceFrom = Application::getRequest()->getGetParam("price-from", "");
	$priceTo = Application::getRequest()->getGetParam("price-to", "");
	
	if(strlen($priceFrom)>0 && strlen($priceTo)==0){
		$q = "p.list_price >= ".$priceFrom;
		$whereClause = $whereClause.($whcf?' where ': ' and ').$q;
		$whcf = false;
	}else
	if(strlen($priceFrom)==0 && strlen($priceTo)>0){
		$q = "p.list_price <= ".$priceTo;
		$whereClause = $whereClause.($whcf?' where ': ' and ').$q;
		$whcf = false;
	}else
	if(strlen($priceFrom)>0 && strlen($priceTo)>0){
		$q = "(p.list_price between ".$priceFrom." and ".$priceTo.")";
		$whereClause = $whereClause.($whcf?' where ': ' and ').$q;
		$whcf = false;
	}
	
	if(strlen($text)>0){
		
		$qn = "p.name like '%{0}%'";
		$qd = "p.description like '%{0}%'";
		$qg = "pg.group_name like '%{0}%'";
		$q = str_replace("{0}", $text, "(".$qn." or ".$qd." or ".$qg.")");
		$whereClause = $whereClause.($whcf?' where ': ' and ').$q;
		$whcf = false;
	}	

	$sortField = Application::getRequest()->getGetParam("sort-field", "price");
	$sortOrder = Application::getRequest()->getGetParam("sort-order", "asc");
	
	switch($sortField){
		case "price":
			$sortField = "list_price";
		break;
		case "productName":
			$sortField = "name";
		break;
		case "groupName":
			$sortField = "group_name";
		break;
		default:
			$sortField = "list_price";
		break;
	}

	switch($sortOrder){
		case "asc":
			$sortOrder = "asc";
		break;
		case "desc":
			$sortOrder = "desc";
		break;
		default:
			$sortOrder = "asc";
		break;
	}	
	$orderClause = " order by ".$sortField." ".$sortOrder;
	
	
	$query = "select * from products p inner join product_groups pg on pg.group_code = p.prod_group" . $whereClause . $orderClause;
	$products = array();
	Application::getDB()->WhileReader($query, function(&$row) use(&$products){
		$p = new Product();
		$p->loadFromRow($row);
		array_push($products, $p->toModel());
	});
	$list = array("items" => $products, "itemTemplate" => dirname(__FILE__)."/../ProductListItem.php");	
	
	
?>


<div class="itemArea">
	<?php echo renderTemplate(dirname(__FILE__)."/../List.php", $list); ?>
</div>
<script>
		$(".itemArea .item").each(function(jj, wrapper){
			$(wrapper).find('.add-to-cart-button').click(function(){
				addToCart(wrapper, 1);
			});
		});
</script>




	
