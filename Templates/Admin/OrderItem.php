<tr class="order-item">
	<td class="product">
		<div class="info-column">
			<img src="<?= $model['imageUrl'] ?>" width="50" height="50" alt="<?= $model['name'] ?>" />
		</div>
		<div class="info-column">
			<div class="name"><strong><?= $model['name'] ?></strong></div>
			<div class="group"><?= $model['groupName'] ?></div>
		</div>
	</td>
	<td>
		<div class="qty"><?= $model['qty'] ?></div>
	</td>
	<td>
		<div class="total-price"><?= $model['totalPrice'] ?></div>$
	</td>
</tr>
