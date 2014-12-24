<tr class="item" code="<?= $model['code'] ?>" qty="<?= $model['qty'] ?>" price="<?= $model['price'] ?>">
	<td>
		<a href="<?= $model['url'] ?>">
			<img src="<?= $model['imageUrl'] ?>" width="100" height="100" alt="<?= $model['name'] ?>" />
		</a>
		<div class="name">
			<a href="<?= $model['url'] ?>">
				<span><strong><?= $model['name'] ?></strong></span>
			</a>
		</div>
		<div class="group"><?= $model['groupName'] ?></div>
		<div class="clrfloat"></div>
	</td>
	<td>
		<div class="price"><?= $model['price'] ?>$</div>
	</td>
	<td>
		<div class="qty"><?= $model['qty'] ?></div>
		<div class="qty-modify">
			<div class="add-qty">+</div>
			<div class="rem-qty">-</div>
		<div>
		<div class="clrfloat"></div>
	</td>
	<td>
		<div class="total-price"><?= $model['qty']*$model['price'] ?>$</div>
	</td>
</tr>
