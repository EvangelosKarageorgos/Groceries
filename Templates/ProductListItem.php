<div class="item">
	<a href="<?= $model['url'] ?>">
		<img src="<?= $model['imageUrl'] ?>" width="100" height="100" alt="<?= $model['name'] ?>" />
	</a>
	<a href="<?= $model['url'] ?>">
		<div class="name"><strong><?= $model['name'] ?></strong></div>
	</a>
	<div class="group"><?= $model['groupName'] ?></div>
	<div class="description"><?= $model['description'] ?></div>
	<div class="price">Price: <?= $model['price'] ?>$</div>
</div>