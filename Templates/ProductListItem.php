<div class="item">
	<a href="<?= $model['url'] ?>">
		<img src="<?= $model['imageUrl'] ?>" width="200" height="200" alt="<?= $model['name'] ?>" />
	</a>
	<a href="<?= $model['url'] ?>">
		<div class="name"><strong><?= $model['name'] ?></strong></div>
	</a>
	<div class="group"><?= $model['groupName'] ?></div>
	<div class="description"><?= $model['description'] ?></div>
	<div class="price"><?= $model['price'] ?></div>
</div>