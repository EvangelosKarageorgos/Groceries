<tr class="item" code="<?= $model['code'] ?>" qty="<?= $model['qty'] ?>">
	<td>
		<a href="<?= $model['url'] ?>">
			<img src="<?= $model['imageUrl'] ?>" width="100" height="100" alt="<?= $model['name'] ?>" />
		</a>
		<div>
			<a href="<?= $model['url'] ?>">
				<div class="name"><strong><?= $model['name'] ?></strong></div>
			</a>
		</div>
		<div class="group"><?= $model['groupName'] ?></div>
	</td>
	<td>
		<div class="price"><?= $model['price'] ?>$</div>
	</td>
	<td>
		<div class="qty"><?= $model['qty'] ?></div>
		<div class="add-qty">+</div>
		<div class="rem-qty">-</div>
	</td>
	<td>
		<div class="total-price"><?= $model['qty']*$model['price'] ?>$</div>
	</td>
</tr>
