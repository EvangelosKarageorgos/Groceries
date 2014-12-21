<?php
require_once dirname(__FILE__)."/Application.php";
require_once dirname(__FILE__)."/Template.php";
require_once dirname(__FILE__)."/WebTools.php";

require_once dirname(__FILE__)."/Product.php";
require_once dirname(__FILE__)."/Group.php";
require_once dirname(__FILE__)."/Order.php";

Application::getRequest()->setBasePath("/groceries");

renderMaster(dirname(__FILE__)."/../Templates/header.php", dirname(__FILE__)."/../Templates/footer.php");

includeHeadResource(new StyleResource("/groceries/base2.css"));
