

<div class="searchbox">
			
	<div class="box-heading">
		<span>Admin Area</span>
	</div>
				
	<div class="box-content"> 

		<div class="account-menu">
				<a href="<?= Application::getRequest()->getBasePath() ?>/account">
			<div class="menu-item<?= strcmp($model['selected'], 'userInfo')==0?" selected":"" ?>">
					<span class="menu-item-text">UserInfo</span>
			</div>
				</a>
				<a href="orders.php">
			<div class="menu-item<?= strcmp($model['selected'], 'orders')==0?" selected":"" ?>">
					<span class="menu-item-text">Orders</span>
			</div>
				</a>
				<a href="changeDetails.php">
			<div class="menu-item<?= strcmp($model['selected'], 'changeDetails')==0?" selected":"" ?>">
					<span class="menu-item-text">Change details</span>
			</div>
				</a>
			<?php /*
			<div class="menu-item<?= strcmp($model['selected'], 'changeCredentials')==0?" selected":"" ?>">
				<a href="changeCredentials.php">
					<span class="menu-item-text">Change credentials</span>
				</a>
			</div>
			*/ ?>
		</div>
	</div>
	<div class="clrfloat"> </div>
</div>