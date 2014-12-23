<div class="account-menu">
	<a href="<?= Application::getRequest()->getBasePath() ?>/account">
		<span class="menu-item<?= strcmp($model['selected'], 'userInfo')==0?" selected":"" ?>">UserInfo</span>
	</a>
	<a href="orders.php">
		<span class="menu-item<?= strcmp($model['selected'], 'orders')==0?" selected":"" ?>">Orders</span>
	</a>
	<a href="changeDetails.php">
		<span class="menu-item<?= strcmp($model['selected'], 'changeDetails')==0?" selected":"" ?>">Change details</span>
	</a>
	<?php /*<a href="changeCredentials.php">
		<span class="menu-item"<?= strcmp($model['selected'], 'changeCredentials')==0?" selected":"" ?>>Change credentials</span>
	</a> */ ?>
</div>