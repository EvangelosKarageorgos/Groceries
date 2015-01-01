<div class="order-box full-content">
	<div class="summary">
		<div class="field date">
			<span class="label">Date :&nbsp;</span>
			<span class="qty"><?= $model["date"] ?></span>
		</div>
		<div class="field total-quantity">
			<span class="label">Total items:&nbsp;</span>
			<span class="qty"><?= $model["totalQuantity"] ?></span>
		</div>
		<div class="field total-price">
			<span class="label">Total cost:&nbsp;</span>
			<span class="price"><?= $model["totalPrice"] ?>&nbsp;$</span>
		</div>
		<div class="field status">
			<span class="label">Status:&nbsp;</span>
			<span class="status"><?= $model["status"] ?></span>
		</div>
		<div class="clrfloat"></div>
	</div>
	<input type="button" class="expander" value="details" onClick="toggleExpander(this)"/>
	<div class="details expandable-content hidden">
		<table class="items-table">
			<tr>
				<th class="product">
					Product
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
	</div>
</div>
