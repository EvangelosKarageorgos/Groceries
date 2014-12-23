<?php
class OrderItem
{
	public $code;
	public $qty;
	public $sum;
	public function __construct($code, $qty, $sum){
		$this->code = $code;
		$this->qty = $qty;
		$this->sum = $sum;
	}
	public function toModel(){
		$p = new Product();
		$p->loadFromDb($this->code);
		$p->qty = $this->qty;
		return $p->toModel();
	}
}
class Order
{
	public $orderno=-1;
	public $cust_no=-1;
	public $total_cost = 0.0;
	public $date;
	public $items = array();
	public function load($orderno=-1){
		$items = array();
		Application::getDB()->WhileReader("select od.order_no, o.order_date, o.cust_no, o.total_cost, od.* from orders o left join order_details od on od.order_no=o.order_no where o.order_no = ?", function(&$r) use(&$orderno, &$date, &$cust_no, &$total_cost, &$items){
			$orderno = intval($r['order_no']);
			$date = $r['order_date'];
			$cust_no = intval($r['cust_no']);
			$total_cost = floatval($r['total_cost']);
			if($r['prod_code']!=null){
				array_push($items, new OrderItem($r['prod_code'].'', intval($r['order_qty']), intval($r['order_sum'])));
			}
		}, $orderno);
		$this->orderno = $orderno;
		$this->date = $date;
		$this->cust_no = $cust_no;
		$this->total_cost = $total_cost;
		$this->items = $items;
	}
	
	public function toModel(){
		$items_count = 0;
		$total_price = 0.0;
		foreach($this->items as &$item){
			$items_count += $item->qty;
		}
		$items = array();
		Application::getDB()->WhileReader("select p.*, pg.*, od.order_qty as qty, od.order_sum from
			order_details od inner join products p on p.prod_code=od.prod_code
			inner join product_groups pg on pg.group_code=p.prod_group
			where od.order_no=?", function(&$r) use (&$items){
				$p = new Product();
				$p->loadFromRow($r);
				$pm = $p->toModel();
				$pm['totalPrice'] = intval($r['order_sum']);
				array_push($items, $pm);
			}, $this->orderno);
			
		return array("date" => $this->date, "totalQuantity" => $items_count, "totalPrice" => $this->total_cost, "items" => $items);
		return $result;
	}
}
