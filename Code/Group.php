<?php

class Group
{
	public $code = "";
	public $name = "";

	public function loadFromDb($code){
		$dt = Application::getDB()->ExecuteDataTable("select * from product_groups where group_code='?'", $code);
		if(count($dt->rows)>0)
			$this->loadFromRow($dt->rows[0]);
	}
	
	public function loadFromRow(&$row){
		$this->code = $row['group_code'];
		$this->name = $row['group_name'];
	}
	
	public function toModel(){
		return array(
			"name" => $this->name,
			"code" => $this->code,
			"url" => Application::getRequest()->getBasePath()."/productList.php?group=".$this->code
		);
	}
}
