<?php
require_once dirname(__FILE__)."/Code/init.php";
//Application::getAuth()->enterProtectedPage();
?>


<?php
	$m = array();
	echo renderTemplate(dirname(__FILE__)."/Templates/Search/searchArea.php", $m);
	echo renderTemplate(dirname(__FILE__)."/Templates/Search/itemArea.php", $m);
?>
