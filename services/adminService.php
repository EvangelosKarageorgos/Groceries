<?php
require_once dirname(__FILE__)."/../Code/Application.php";
require_once dirname(__FILE__)."/../Code/Template.php";
require_once dirname(__FILE__)."/../Code/WebTools.php";

require_once dirname(__FILE__)."/../Code/Product.php";
require_once dirname(__FILE__)."/../Code/Group.php";

Application::getRequest()->setBasePath("/groceries");
require_once dirname(__FILE__)."/../Code/AjaxService.php";


AjaxService::addService("complete-order", function($data){
	return Application::getDB()->ExecuteScalar("select complete_order(?)", $data);
});

AjaxService::addService("cancel-order", function($data){
	Application::getDB()->ExecuteNonQuery("call cancel_order(?)", $data);
    return '';
});
AjaxService::serve();