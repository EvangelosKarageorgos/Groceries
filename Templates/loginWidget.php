<?php
	if($model["IsAuthenticated"])
	{
		$showError = false;
		$errorMsg = "";
		if(Application::getRequest()->getPostParam("action", "") == "logout"){
			Application::getAuth()->logout();
			WebTools::redirect(Application::getRequest()->getPageUrl());
		}
	}
?>
<div>
	<?php if($model["IsAuthenticated"]){ ?>
		<div class="accountArea">
			<span><?= $model["CustomerName"] ?></span>
			<input class="btn" type="button" Value="MyAccount" onClick="window.location='<?= $model["MyAccountUrl"] ?>'" />
		</div>
		<div class="logoutArea">
			<form method="POST">
				<input type="hidden" name="action" value="logout" />
				<input class="btn" type="submit" value="Logout" />
			</form>
		</div>
	<?php } else { ?>
		<input class="btn" type="button" Value="Register" onClick="window.location='<?= $model["RegisterUrl"] ?>'" />
		<input class="btn" type="button" Value="Login" onClick="window.location='<?= $model["LoginUrl"] ?>'" />
	<?php } ?>
</div>
