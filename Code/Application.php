<?php


class Application{
	private static $_database = null;
	private static $_authentication = null;
	private static $_request = null;
	private static $_cartManader = null;

	public static function initialize(){

	}
	public static function getDB(){
		if(self::$_database==null){
			require_once dirname(__FILE__)."/DataBase.php";
			self::$_database = new MysqlDatabaseEngine();
			self::$_database->setConnectionString("host=localhost,database=groceries,user=groceries_user,password=groceries_password");
			self::$_database->initialize();
		}
		return self::$_database;
	}

	public static function getAuth(){
		if(self::$_authentication==null){
			require_once dirname(__FILE__)."/Authentication.php";
			self::$_authentication = new Authentication();
			self::$_authentication->initialize();
		}
		return self::$_authentication;
	}

	public static function getRequest(){
		if(self::$_request==null){
			require_once dirname(__FILE__)."/Request.php";
			self::$_request = new Request();
			self::$_request->initialize();
		}
		return self::$_request;
	}
	
	public static function getCartManager(){
		if(self::$_cartManader==null){
			require_once dirname(__FILE__)."/CartManager.php";
			self::$_cartManader = new CartManager();
			self::$_cartManader->initialize();
		}
		return self::$_cartManader;
	}
}


