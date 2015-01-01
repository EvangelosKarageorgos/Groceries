<ul>
	<?php foreach($model["items"] as &$item){ ?>
		<li>
			<?php echo renderTemplate($model["itemTemplate"], $item); ?>
		</li>
	<?php } ?>
</ul>
