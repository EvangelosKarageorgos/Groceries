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
	
	
	public function register($userName, $passWord, $fullName, $eMail, $street, $town, $postcode){
		session_name("Auth");
		session_start();
		
		$result = 0;
		
		$isAuth = false;
		$custNo = -1;
		
		if(!strlen($userName)>0 || !strlen($passWord)>0 || !strlen($fullName)>0 || !strlen($eMail.'')>0 
			|| !strlen($street)>0 || !strlen($town)>0 || !strlen($postcode)>0 )
		{
			$result = 1;
		}
		
		else {
			
			Application::getDB()->WhileReader("call user_register('?', '?', '?', '?', '?', '?', '?')", function(&$r) use(&$isAuth, &$custNo){
				$userNotExists = intval($r["existingLogin"])==0;
				if($userNotExists){
					$result = 0;
					$isAuth = true;
					$custNo = intval($r["custNum"]);
				}
				else {
					$result = 2;
				}
			}, $userName, $passWord, $fullName, $eMail, $street, $town, $postcode);
			
			
		
		}
		
		
				


		
		$_SESSION["cust_no"] = $custNo;
		$_SESSION["IsAuthenticated"] = $isAuth;
		session_write_close();
		$this->_isAuthenticated = $isAuth;
		$this->_custNo = $custNo;
		$this->_isAuthEvaluated = true;
		
		return $result;
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

	public function enterAdminPage(){
		return $this->isAuthenticated();
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