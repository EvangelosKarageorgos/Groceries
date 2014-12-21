<?php

class Product
{
	public $code = "";
	public $name = "";
	public $description = "";
	public $group = "";
	public $groupName = "";
	public $price = 0.0;
	public $availableQuantity = 0;
	public $imageUrl = "";
	public $qty = 1;

	public function loadFromDb($code){
		$dt = Application::getDB()->ExecuteDataTable("select * from products p inner join product_groups pg on pg.group_code = p.prod_group where p.prod_code='?'", $code);
		if(count($dt->rows)>0)
			$this->loadFromRow($dt->rows[0]);
	}
	
	public function loadFromRow(&$row){
		$this->code = $row['prod_code'];
		$this->name = $row['name'];
		$this->group = $row['prod_group'];
		$this->groupName = $row['group_name'];
		$this->description = $row['description'];
		$this->price = floatval($row['list_price']);
		$this->availableQuantity = intval($row['qty_on_hand']);
		if(strlen($row['imageUrl'])>0){
			$this->imageUrl = $row['imageUrl'];
			if(strpos(strtolower($this->imageUrl), "http://")===false || strpos(strtolower($this->imageUrl), "https://")===false){
				$this->imageUrl = Application::getRequest()->getBasePath().$this->imageUrl;
			}
		}
		if(isset($row['qty'])){
			$this->qty = intval($row['qty']);
		}
	}
	
	public function toModel(){
		return array(
			"code" => $this->code,
			"name" => $this->name,
			"description" => $this->description,
			"group" => $this->group,
			"groupName" => $this->groupName,
			"availableQuantity" => $this->availableQuantity,
			"imageUrl" => $this->imageUrl,
			"price" => $this->price,
			"url" => Application::getRequest()->getBasePath()."/product.php?code=".$this->code,
			"qty" => $this->qty
		);
	}
}
