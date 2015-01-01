<?php
class Authentication
{
	private $_custNo = 0;
	private $_isAuthenticated = false;
	private $_isAdmin = false;
	private $_isAuthEvaluated = false;
	
	public function initialize(){
	}

	private function readSession(){
		session_name("Auth");
		session_start();	
		$this->_isAuthenticated = false;
		$this->_custNo = -1;
		if(isset($_SESSION)){
			if(isset($_SESSION["IsAuthenticated"]) && $_SESSION["IsAuthenticated"]){
				if(isset($_SESSION["cust_no"])){
					$this->_isAuthenticated = true;
					$this->_custNo = $_SESSION["cust_no"];
					if(isset($_SESSION["IsAdmin"]))
						$this->_isAdmin = $_SESSION["IsAdmin"];
				}
			}
		}
		session_write_close();
		$this->_isAuthEvaluated = true;
	}
	
	
	public function register($userName, $passWord, $fullName, $eMail, $street, $town, $postcode){
		session_name("Auth");
		session_start();
		
		$result = 0;
		
		$isAuth = false;
		$isAdmin = false;
		$custNo = -1;
		
		if(!strlen($userName)>0 || !strlen($passWord)>0 || !strlen($fullName)>0 || !strlen($eMail.'')>0 
			|| !strlen($street)>0 || !strlen($town)>0 || !strlen($postcode)>0 )
		{
			$result = 1;
		}
		
		else {
			
			Application::getDB()->WhileReader("select user_register('?', '?', '?', '?', '?', '?', '?') as custNum", function(&$r) use(&$isAuth, &$custNo){
				$userNotExists = intval($r["existingLogin"])==0;
				if($userNotExists){
					$result = 0;
					$isAuth = true;
					$isAdmin = false;
					$custNo = intval($r["custNum"]);
				}
				else {
					$result = 2;
				}
			}, $userName, $passWord, $fullName, $eMail, $street, $town, $postcode);
			
			
		
		}

		$_SESSION["cust_no"] = $custNo;
		$_SESSION["IsAuthenticated"] = $isAuth;
		$_SESSION["IsAdmin"] = $isAdmin;
		session_write_close();
		$this->_isAuthenticated = $isAuth;
		$this->_isAdmin = $isAdmin;
		$this->_custNo = $custNo;
		$this->_isAuthEvaluated = true;
		return $result;
	}
	
	
	public function login($username, $password){
		session_name("Auth");
		session_start();
		
		$isAuth = false;
		$isAdmin = false;
		$custNo = -1;
		
		Application::getDB()->WhileReader("call user_login('?', '?')", function(&$r) use(&$isAuth, &$isAdmin, &$custNo){
			$isAuth = intval($r["IsAuth"])>0;
			if($isAuth){
				$isAdmin = intval($r["IsAdmin"])>0;
				$custNo = intval($r["custNum"]);
			}
		}, $username, $password);
		$_SESSION["cust_no"] = $custNo;
		$_SESSION["IsAuthenticated"] = $isAuth;
		$_SESSION["IsAdmin"] = $isAdmin;
		session_write_close();
		$this->_isAuthenticated = $isAuth;
		$this->_isAdmin = $isAdmin;
		$this->_custNo = $custNo;
		$this->_isAuthEvaluated = true;
	}
	
	public function logout(){
		session_name("Auth");
		session_start();
		$_SESSION["cust_no"] = -1;
		$_SESSION["IsAuthenticated"] = false;
		$_SESSION["IsAdmin"] = false;
		$this->_custNo = -1;
		$this->_isAuthenticated = false;
		$this->_isAdmin = false;
		$this->_isAuthEvaluated = false;
	}
	
	public function isAuthenticated(){
		if(!$this->_isAuthEvaluated){
			$this->readSession();
		}
		return $this->_isAuthenticated;
	}
	
	public function isAdmin(){
		return $this->isAuthenticated() && $this->_isAdmin;
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

	public function enterAdminPage(){
		$this->enterProtectedPage();
		if(!$this->isAdmin()){
			WebTools::redirect(Application::getRequest()->getBasePath());
		}
	}	
	public function getCustomerInfo(){
		$result = array();
		Application::getDB()->WhileReader("select * from users where cust_no=?", function(&$r) use (&$result){
			$result['Name'] = $r['cust_name'];
			$result['Email'] = $r['email'];
			$result['Address'] = $r['street'];
			$result['Town'] = $r['town'];
			$result['Postcode'] = $r['post_code'];
			$result['CreditBalance'] = $r['curr_bal'];
		}, $this->getCustomerNum());
		return $result;
	}
	
}