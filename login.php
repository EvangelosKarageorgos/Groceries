<?php
require_once dirname(__FILE__)."/Code/init.php";
?>

<?php
	$showError = false;
	$errorMsg = "";
	if(Application::getRequest()->getPostParam("action", "") == "login"){
			Application::getAuth()->login(Application::getRequest()->getPostParam("uname", ""), Application::getRequest()->getPostParam("pass", ""));
			if(Application::getAuth()->isAuthenticated()){
				$dst = Application::getRequest()->getGetParam("dst", "");
				if(strlen($dst)>0){
					WebTools::redirect(WebTools::urlDecode($dst));
				} else
					WebTools::redirect(Application::getRequest()->getBasePath()."index.php");
			} else {
				$showError = true;
				$errorMsg = "Wrong user name or password";
			}
	}
?>
<form method="POST">
	<input class="btn" type="hidden" name="action" value="login" />
	<div>Username :</div><input name="uname" type="text" width="200" />
	<div>Password :</div><input name="pass" type="password" width="200" />
	<?php if($showError){ ?>
		<div><?= $errorMsg ?></div>
	<?php } ?>
	<input class="groceriesBtn" type="submit" value="Login"/>
</form>