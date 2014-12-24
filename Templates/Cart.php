<div class="cart">
	<table>
		<tr>
			<th>
				Product
			</th>
			<th>
				Price
			</th>
			<th>
				Quantity
			</th>
			<th>
				Total
			</th>		
		</tr>
		<?php foreach($model["items"] as &$item){ ?>
			<?php echo renderTemplate($model["itemTemplate"], $item); ?>
		<?php } ?>
	</table>
	<div class="summary">
		<div class="total-quantity">
			<span class="label">Total items:&nbsp;</span>
			<span class="qty"><?= $model["totalQuantity"] ?></span>
		</div>
		<div class="total-price">
			<span class="label">Total cost:&nbsp;</span>
			<span class="price"><?= $model["totalPrice"] ?>$</span>
		</div>
	</div>
	<div class="checkout-btn-container">
		<a href="<?= Application::getRequest()->getBasePath()."/checkout" ?>">
			<input class="groceriesBtn checkout-button" type="button" value="Checkout" />
		</a>
	</div>
	<script>
		$(".cart .item").each(function(jj, wrapper){
			$(wrapper).find('.add-qty').click(function(){
				addToCart(wrapper, 1)
			});
			$(wrapper).find('.rem-qty').click(function(){
				addToCart(wrapper, -1)
			});
		});
	</script>
</div>