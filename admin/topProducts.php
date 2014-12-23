<?php
require_once dirname(__FILE__)."/../Code/init.php";
Application::getAuth()->enterProtectedPage();







?>


<?php
	$m = array();
	echo renderTemplate(dirname(__FILE__)."/adminSideArea.php", $m);
	<div class="itemArea">
		<?php echo renderTemplate(dirname(__FILE__)."/../Templates/List.php", $list); ?>
	</div>
?>
