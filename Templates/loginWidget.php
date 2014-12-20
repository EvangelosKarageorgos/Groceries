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
		<input type="button" Value="MyAccount" onClick="window.location='<?= $model["MyAccountUrl"] ?>'" />
		<span><?= $model["CustomerName"] ?></span>
		<form method="POST">
			<input type="hidden" name="action" value="logout" />
			<input class="btn" type="submit" value="Logout" />
		</form>
	<?php } else { ?>
		<input class="btn" type="button" Value="Login" onClick="window.location='<?= $model["LoginUrl"] ?>'" />
	<?php } ?>
</div>
