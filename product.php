<?php
require_once dirname(__FILE__)."/DataBase.php";
require_once "index.php";

?>


<html>
<head></head>
<body>
	<?php
		$product = new Product($_GET["code"]);
	?>
	
	<div class="product">
		ProductPage
		<div class="name"><strong><?= $product->name ?></strong></div>
	</div>

</body>
</html>