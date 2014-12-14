<?php
	require_once dirname(__FILE__)."/Code/Init.php";
	$product = new Product();
	$product->loadFromDb(Application::getRequest()->getGetParam("code", ""));
	
?>

<?php if(strlen($product->code)==0) { http_response_code(404); ?>
	<h3>Product not found</h3>
	<div>The code specified does not match any product.</div>
<?php } else { ?>
	<h3>ProductPage</h3>
	<?php echo renderTemplate(dirname(__FILE__)."/Templates/ProductDetails.php", $product->toModel());	?>
<?php } ?>
