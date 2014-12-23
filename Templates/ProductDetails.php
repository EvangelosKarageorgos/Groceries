<?php
	
	$queryString = "SELECT min(order_qty) AS min_order, max(order_qty) AS max_order FROM `order_details` WHERE prod_code = '".$model['code']."' GROUP BY prod_code ";

		$product_orders = array();
	Application::getDB()->WhileReader($queryString, function(&$row) use(&$product_orders){
		$product_orders['min'] = $row['min_order']>0 ? $row['min_order'] : 0;
		$product_orders['max'] = $row['max_order']>0 ? $row['max_order'] : 0;
	});

?>

<div class="details" code="<?= $model['code'] ?>">

	<h2><?= $model['name'] ?></h2>
	
	
	<div class="productInfo">
		<div>
			<img src="<?= $model['imageUrl'] ?>" width="400" height="400" alt="<?= $model['name'] ?>" />
		</div>
		<div class="group">Group: <?= $model['groupName'] ?></div>
		<div class="description">
			<span>Description</span>
			<p><?= $model['description'] ?></p>
		</div>
		
		<div class="orderStatistics">
			<div>
				<span> Minimum Ordered Quantity: </span>
				<span> <?= $product_orders['min'] ?> </span>
			</div>
			
			<div>
				<span> Maximum Ordered Quantity: </span>
				<span> <?= $product_orders['max'] ?> </span>
			</div>
			
		</div>
	</div>
	
	<div class="productOrderArea">
		<div class="price"><?= $model['price'] ?>$</div>
		<div class="quantity">Available Quantity: <?= $model['availableQuantity'] ?></div>
		
		<div class="addToCartArea">
			<input class="orderedQuantity" type="text" name="orderedQty" placeholder="Qty" value="1"/>
			<input class="groceriesBtn add-to-cart-button" type="button" value="Add To Cart" />
		</div>
	</div>
	
	
	

</div>


<script>

		$(".details").each(function(jj, wrapper){
			var orderedQty = parseInt( $(wrapper).find("input[name='orderedQty']").val().trim() );
			console.log(orderedQty);
			$(wrapper).find('.add-to-cart-button').click(function(){
				addToCart(wrapper, orderedQty);
			});
		});
</script>