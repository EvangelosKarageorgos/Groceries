<?php
require_once dirname(__FILE__)."/Code/init.php";
//Application::getAuth()->enterProtectedPage();

?>

<?php includeHeadResource(new StyleResource("/groceries/base.css")); ?>
<?php includeHeadResource(new StyleResource("/groceries/style/style.css")); ?>
<?php includeFootResource(new ScriptResource("/groceries/base.js")); ?>



<div class="content">

<div class="centralContent">


<div>Content</div>
<?php

	$isAuth = Application::getAuth()->isAuthenticated();
	var_dump($isAuth);
	
	$cart = Application::getCartManager()->getCart();
	var_dump($cart);
	
	//Application::getAuth()->login("angello", "12344");
	
	//$isAuth = Application::getAuth()->isAuthenticated();
	//var_dump($isAuth);

?>







</div>

</div>