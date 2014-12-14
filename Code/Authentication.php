<?php
class Authentication
{
	private $_custNo = 0;
	private $_isAuthenticated = false;
	private $_isAuthEvaluated = false;
	
	public function initialize(){
	}

	private function readSession(){
		session_name("Auth");
		session_start();	
		$this->IsAuthenticated = false;
		$this->_custNo = -1;
		if(isset($_SESSION)){
			if(isset($_SESSION["IsAuthenticated"]) && $_SESSION["IsAuthenticated"]){
				if(isset($_SESSION["cust_no"])){
					$this->_isAuthenticated = true;
					$this->_custNo = $_SESSION["cust_no"];
				}
			}
		}
		session_write_close();
		$this->_isAuthEvaluated = true;
	}
	
	public function login($username, $password){
		session_name("Auth");
		session_start();
		
		$isAuth = false;
		$custNo = -1;
		
		Application::getDB()->WhileReader("call user_login('?', '?')", function(&$r) use(&$isAuth, &$custNo){
			$isAuth = intval($r["IsAuth"])>0;
			if($isAuth)
				$custNo = intval($r["custNum"]);
		}, $username, $password);
		
		$_SESSION["cust_no"] = $custNo;
		$_SESSION["IsAuthenticated"] = $isAuth;
		session_write_close();
		$this->_isAuthenticated = $isAuth;
		$this->_custNo = $custNo;
		$this->_isAuthEvaluated = true;
	}
	
	public function logout(){
		session_name("Auth");
		session_start();
		$_SESSION["cust_no"] = -1;
		$_SESSION["IsAuthenticated"] = false;
		$this->_custNo = -1;
		$this->_isAuthenticated = false;
		$this->_isAuthEvaluated = false;
	}
	
	public function isAuthenticated(){
		if(!$this->_isAuthEvaluated){
			$this->readSession();
		}
		return $this->_isAuthenticated;
	}
	
	public function getCustomerNum(){
		if(!$this->_isAuthEvaluated){
			$this->readSession();
		}
		return $this->_isAuthenticated ? $this->_custNo : -1;
	}
	
	public function enterProtectedPage(){
		if(!$this->isAuthenticated())
		{
			$dst = WebTools::urlEncode(WebTools::getPageUrl());
			WebTools::redirect(Application::getRequest()->getBasePath()."/login.php?dst=".$dst);
		}
	}
	
}