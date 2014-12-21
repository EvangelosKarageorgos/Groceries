<?php
	$groups = array();
	Application::getDB()->WhileReader("select * from product_groups order by group_name", function(&$row) use(&$groups){
		$g = new Group();
		$g->loadFromRow($row);
		array_push($groups, $g->toModel());
	});
	$list = array("items" => $groups, "itemTemplate" => dirname(__FILE__)."/../Templates/SearchGroupListItem.php");
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
					<?php echo renderTemplate(dirname(__FILE__)."/searchKeys.php", array()); ?> 					
				</div>
			</div>
		
		
			<div class="group-selector form-field">
				<span class="title">Groups</span>
								
				<div class="list">
					<?php echo renderTemplate(dirname(__FILE__)."/../Templates/List.php", $list); ?> 					
				</div>
				
				<div class="clrfloat"> </div>
								
			</div>
			

			
			<div class="price-range-selector form-field">
				<span class="title">Price range</span>
				<div class="price-range-area">
					<?php echo renderTemplate(dirname(__FILE__)."/searchPriceRange.php", array()); ?> 					
					<div class="clrfloat"> </div>
				</div>
				
			</div>
			
			<div class="sorting-selector form-field">
				<span class="title">Sort by</span>
				<div>
					<?php echo renderTemplate(dirname(__FILE__)."/searchSortBy.php", array()); ?> 
					<div class="clrfloat"> </div>
				</div>
			</div>

			<div class="clrfloat"> </div>
			
			<input class="groceriesBtn" type="submit" value="Search"/>
		</form> 
	</div>
	
	
	
	
	
			
</div>