<?php
require_once "Init.php";
require_once "Template.php";

class Product
{
	public $code = "";
	public $name = "";
	public $description = "";
	public $price = 0.0;
	public $availableQuantity = 0;
	public function __construct($code)
	{
		$data = array();
		$dt = Application::db()->ExecuteDatatable("select * from PRODUCTS where prod_code = '?'", $code);
		if(count($dt->rows)>0){
			$this->code = $dt->rows[0]['prod_code'];
			$this->name = $dt->rows[0]['name'];
			$this->description = $dt->rows[0]['description'];
			$this->price = floatval($dt->rows[0]['list_price']);
			$this->availableQuantity = intval($dt->rows[0]['qty_on_hand']);
		}
	}
	
	public function __toString(){
		$data = array(
			"name" => $this->name,
			"description" => $this->description,
			"price" => $this->price,
			"url" => "/product.php?code=".$this->code
		);
		return renderTemplate(dirname(__FILE__)."/templates/ProductListItem.php", $data);
	}
}

?>
<html>
<head></head>
<body>
	<?php
		echo new Product('p00');
		$dt = Application::db()->ExecuteDatatable("select * from PRODUCTS");
		echo $dt->toHtml();
	?>
</body>
</html>

