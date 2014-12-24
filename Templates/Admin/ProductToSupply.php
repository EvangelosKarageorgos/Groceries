<div class="product">
<span><?= $model['prodCode'] ?></span>
<span><?= $model['name'] ?></span>
<span><?= $model['qty'] ?></span>
<?php foreach($model['suppliers'] as &$supplier){ ?>
	<div class="supplier" supplier-id="<?= $model['supplierId'] ?>">
		<div class="supplier-name">
			<?= $model['name'] ?>
		</div>
		<form method="POST">
			<input type="hidden" name="submittype" value="supply" />
			<input type="hidden" name="supplierId" value="<?= $model['supplierId'] ?>" />
			<input type="submit" class="croceriesBtn" value="supply" />
		</form>
	</div>
<?php } ?>


</div>