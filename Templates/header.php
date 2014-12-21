<?php includeHeadResource(new StyleResource(Application::getRequest()->getBasePath()."/styles/style.css")); ?>
<?php includeHeadResource(new ScriptResource(Application::getRequest()->getBasePath()."/scripts/jquery-1.11.1.min.js")); ?>
<?php includeHeadResource(new ScriptResource(Application::getRequest()->getBasePath()."/scripts/Core.js")); ?>
<?php includeHeadResource(new ScriptResource(Application::getRequest()->getBasePath()."/scripts/cart.js")); ?>
<?php includeFootResource(new ScriptResource(Application::getRequest()->getBasePath()."/scripts/search.js")); ?>
<script>BasePath = "<?= Application::getRequest()->getBasePath() ?>";</script>
<div class="header">

<div class="centralContent">

			<div>
				<div class="logoArea">
					<a href="<?= Application::getRequest()->getBasePath()."/home.php" ?>"/>
						<img src="<?= Application::getRequest()->getBasePath() ?>/images/general/logo.png">
					</a>
				</div>

<?php
	$showLoginWidget = true;
	if(strcmp(strtolower(Application::getRequest()->getDocumentName()), "login.php")==0 || 
			strcmp(strtolower(Application::getRequest()->getDocumentName()), "register.php")==0)
		$showLoginWidget = false;
	else{
		$isAuthenticated = Application::getAuth()->isAuthenticated();
		$m = array();
		$m["IsAuthenticated"] = $isAuthenticated;
		$m["LoginUrl"] = WebTools::getPagePath()."login.php?dst=".WebTools::urlEncode(WebTools::getPageUrl());
		$m["RegisterUrl"] = WebTools::getPagePath()."register.php?dst=".WebTools::urlEncode(WebTools::getPageUrl());
		if($isAuthenticated){
			$name = Application::getDB()->ExecuteScalar("select cust_name from Users where cust_no=?", Application::getAuth()->getCustomerNum());
			$m["CustomerName"] = $name;
			$m["MyAccountUrl"] = WebTools::getPagePath()."account.php";
		}
		$loginWidget = renderTemplate(dirname(__FILE__)."/loginWidget.php", $m);
		$cartWidget = renderTemplate(dirname(__FILE__)."/cartWidget.php", $m);
	}
	
	
	$groups = array();
	Application::getDB()->WhileReader("select * from product_groups order by group_name", function(&$row) use(&$groups){
		$g = new Group();
		$g->loadFromRow($row);
		array_push($groups, $g->toModel());
	});
	$list = array("items" => $groups, "itemTemplate" => dirname(__FILE__)."/GroupListItem.php");
	
	
	//echo renderTemplate(dirname(__FILE__)."/List.php", $list);
	
	
?>

<?php if($showLoginWidget){ ?>
			<div class="loginHeaderArea">
				<?= $cartWidget ?>
				<?= $loginWidget ?>
			</div>		
<?php } ?>
		<div class="clrfloat"> </div>

	</div>






	<div class="categoriesMenu">	
		<?php echo renderTemplate(dirname(__FILE__)."/List.php", $list); ?>
	</div>
	

	
</div>

</div>


