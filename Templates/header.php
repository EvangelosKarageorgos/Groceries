<h1>Header</h1>
<?php
	$showLoginWidget = true;
	if(strcmp(strtolower(Application::getRequest()->getDocumentName()), "login.php")==0)
		$showLoginWidget = false;
	else{
		$isAuthenticated = Application::getAuth()->isAuthenticated();
		$m = array();
		$m["IsAuthenticated"] = $isAuthenticated;
		$m["LoginUrl"] = WebTools::getPagePath()."login.php?dst=".WebTools::urlEncode(WebTools::getPageUrl());
		if($isAuthenticated){
			$name = Application::getDB()->ExecuteScalar("select cust_name from Users where cust_no=?", Application::getAuth()->getCustomerNum());
			$m["CustomerName"] = $name;
			$m["MyAccountUrl"] = WebTools::getPagePath()."account.php";
		}
		$loginWidget = renderTemplate(dirname(__FILE__)."/loginWidget.php", $m);
	}
	
	
	$groups = array();
	Application::getDB()->WhileReader("select * from product_groups order by group_name", function(&$row) use(&$groups){
		$g = new Group();
		$g->loadFromRow($row);
		array_push($groups, $g->toModel());
	});
	$list = array("items" => $groups, "itemTemplate" => dirname(__FILE__)."/GroupListItem.php");
	
	echo renderTemplate(dirname(__FILE__)."/List.php", $list);
	
	
?>
<?php if($showLoginWidget){ ?>
	<div><?= $loginWidget ?></div>
<?php } ?>