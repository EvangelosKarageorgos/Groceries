<?php
require_once dirname(__FILE__)."/DataBase.php";

$_database = new MysqlDatabaseEngine();
$_database->setConnectionString("host=localhost,database=groceries,user=groceries_user,password=groceries_password");
$_database->initialize();


class Application{
	public static function db(){
		global $_database;
		return $_database;
	}
}


