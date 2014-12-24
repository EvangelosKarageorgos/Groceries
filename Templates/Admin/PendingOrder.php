<div class="order-box full-content">
	<div class="summary">
		<div class="field orderno">
			<span class="label">Order number :&nbsp;</span>
			<span class="orderno"><?= $model["orderNo"] ?></span>
		</div>
		<div class="field date">
			<span class="label">Date :&nbsp;</span>
			<span class="date"><?= $model["date"] ?></span>
		</div>
		<div class="field total-quantity">
			<span class="label">Total items:&nbsp;</span>
			<span class="qty"><?= $model["totalQuantity"] ?></span>
		</div>
		<div class="field creditcard-no">
			<span class="label">Creditcard no:&nbsp;</span>
			<span class="creditcard-no"><?= $model["creditcardNo"] ?></span>
		</div>
		<div class="field total-price">
			<span class="label">Total cost:&nbsp;</span>
			<span class="price"><?= $model["totalPrice"] ?>&nbsp;$</span>
		</div>
		<div class="field actions">
			<input type="button" class="cancel-action groceriesBtn" value="Cancel" />
			<input type="button" class="complete-action groceriesBtn" value="Complete" />
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
