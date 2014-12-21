<?php
class CartItem
{
	public $code;
	public $qty;
	public function __construct($code, $qty){
		$this->code = $code;
		$this->qty = $qty;
	}
	public function toModel(){
		$p = new Product();
		$p->loadFromDb($this->code);
		$p->qty = $this->qty;
		return $p->toModel();
	}
}
class Cart
{
	public $cartid=-1;
	public $cust_no=-1;
	public $items = array();
	public function load($cartid=-1, $cust_no=-1){
		$items = array();
		Application::getDB()->WhileReader("call get_cart(?, ?)", function(&$r) use(&$cartid, &$cust_no, &$items){
			$cartid = intval($r['cart_id']);
			$cust_no = strlen($r['cust_no'].'')==0?-1 : intval($r['cust_no']);
			if($r['prod_code']!=null){
				array_push($items, new CartItem($r['prod_code'].'', intval($r['qty'])));
			}
		}, $cartid, $cust_no);
		$this->cartid = $cartid;
		$this->cust_no = $cust_no;
		$this->items = $items;
	}
	
	public function add($prod_code, $qty){
		$added = intval(Application::getDB()->ExecuteScalar("select add_to_cart(?, '?', ?)", $this->cartid, $prod_code, $qty));
		$found = false;
		foreach($this->items as &$item){
			if(strcmp($item->code, $prod_code)==0){
				$item->qty += $added;
				$found = true;
			}
		}
		if(!$found){
			array_push($this->items, new CartItem($prod_code, $added));
		}
		return $added;
	}
	
	public function toModel(){
		$items_count = 0;
		$total_price = 0.0;
		$total = Application::getDB()->WhileReader("select sum(p.list_price * cd.qty) as price, sum(cd.qty) as items from cart_details cd
			inner join products p on p.prod_code=cd.prod_code where cd.cartid=?", function(&$r) use (&$items_count, &$total_price){
				$items_count = intval($r["items"]);
				$total_price = floatval($r["price"]);
			}, $this->cartid);
		$items = array();
		Application::getDB()->WhileReader("select p.*, pg.*, cd.qty from
			cart_details cd inner join products p on p.prod_code=cd.prod_code
			inner join product_groups pg on pg.group_code=p.prod_group
			where cd.cartid=?", function(&$r) use (&$items){
				$p = new Product();
				$p->loadFromRow($r);
				array_push($items, $p->toModel());
			}, $this->cartid);
			
		return array("totalQuantity" => $items_count, "totalPrice" => $total_price, "items" => $items);
		return $result;
	}
}

class CartManager
{
	private $_cartid = -1;
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