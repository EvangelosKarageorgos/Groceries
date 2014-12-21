<?php
	$groups = array();
	$searchGroups = explode(',', Application::getRequest()->getGetParam("groups", ""));
	Application::getDB()->WhileReader("select * from product_groups order by group_name", function(&$row) use(&$groups, &$searchGroups){
		$g = new Group();
		$g->loadFromRow($row);
		$gm = $g->toModel();
		$gm['searchGroups'] = $searchGroups;
		array_push($groups, $gm);
	});
	$list = array("items" => $groups, "itemTemplate" => dirname(__FILE__)."/SearchGroupListItem.php");
	
	
?>

<div class="searchbox">
			
	<div class="box-heading">
		<span>Advanced Search</span>
	</div>
				
	<div class="box-content"> 
		<form method="GET">

			<div class="search-text-selector form-field">
				<span class="title">Search for</span>
				<div class="field">
					<?php echo renderTemplate(dirname(__FILE__)."//searchKeys.php", array("text" => Application::getRequest()->getGetParam("text", ""))); ?> 					
				</div>
			</div>
		
		
			<div class="group-selector form-field">
				<span class="title">Groups</span>
								
				<div class="list">
					<?php echo renderTemplate(dirname(__FILE__)."/../List.php", $list); ?> 					
				</div>
				
				<div class="clrfloat"> </div>
								
			</div>
			

			
			<div class="price-range-selector form-field">
				<span class="title">Price range</span>
				<div class="price-range-area">
					<?php echo renderTemplate(dirname(__FILE__)."/searchPriceRange.php", array("priceFrom" => Application::getRequest()->getGetParam("price-from", ""), "priceTo" => Application::getRequest()->getGetParam("price-to", ""))); ?> 					
					<div class="clrfloat"> </div>
				</div>
				
			</div>
			
			<div class="sorting-selector form-field">
				<span class="title">Sort by</span>
				<div>
					<?php echo renderTemplate(dirname(__FILE__)."/searchSortBy.php", array("sortField" => Application::getRequest()->getGetParam("sort-field", ""), "sortOrder" => Application::getRequest()->getGetParam("sort-order", ""))); ?> 
					<div class="clrfloat"> </div>
				</div>
			</div>

			<div class="clrfloat"> </div>
			
			<input class="groceriesBtn search-button" type="button" value="Search"/>
		</form> 
	</div>
	
	
	
	
	
			
</div>