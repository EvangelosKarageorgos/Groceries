<tr class="item">
	<td>
		<img src="<?= $model['imageUrl'] ?>" width="100" height="100" alt="<?= $model['name'] ?>" />
		<div class="name"><strong><?= $model['name'] ?></strong></div>
		<div class="group"><?= $model['groupName'] ?></div>
	</td>
	<td>
		<div class="price"><?= $model['price'] ?>$</div>
	</td>
	<td>
		<div class="qty"><?= $model['qty'] ?></div>
	</td>
	<td>
		<div class="total-price"><?= $model['qty']*$model['price'] ?>$</div>
	</td>
</tr>
