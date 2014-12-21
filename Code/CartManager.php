<?php
class CartItem
{
	
}
class Cart
{
	public $cartid=-1;
	public $cust_no=-1;
	public function load($cartid=-1, $cust_no=-1){
		Application::getDB()->WhileReader("call get_cart(?, ?)", function(&$r) use(&$cartid, &$cust_no){
			$cartid = intval($r['cartid']);
			$cust_no = strlen($r['cust_no'].'')==0?-1 : intval($r['cust_no']);
		}, $cartid, $cust_no);
		$this->cartid = $cartid;
		$this->cust_no = $cust_no;
	}
	
	public function add(prod_code, qty){
		
	}
}

class CartManager
{
	private $_cartid;
	private $_isCartLoaded = false;
	private $_cart;
	
	public function initialize(){
	}
	
	public function getCart(){
		if(!$this->_isCartLoaded){
			session_name("Cart");
			session_start();
				if(isset($_SESSION["cartid"]))
					$this->_cartid = $_SESSION["cartid"];
				else
					$this->_cartid = -1;
			session_write_close();
			
			$cust_no = Application::getAuth()->getCustomerNum();
			
			$this->_cart = new Cart();
			$this->_cart->load($this->_cartid, $cust_no);
			if($this->_cartid != $this->_cart->cartid){
				$this->_cartid = $this->_cart->cartid;
				session_start();
				$_SESSION["cartid"] = $this->_cartid;
				session_write_close();
			}
			$this->_isCartLoaded = true;
		}
		return $this->_cart;
	}
}