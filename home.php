<?php
require_once dirname(__FILE__)."/Code/init.php";
//Application::getAuth()->enterProtectedPage();
?>

<?php includeHeadResource(new StyleResource("/groceries/base.css")); ?>
<?php includeHeadResource(new StyleResource("/groceries/style/style.css")); ?>
<?php includeFootResource(new ScriptResource("/groceries/base.js")); ?>




<?php
	$m = array();
	echo renderTemplate(dirname(__FILE__)."/Search/searchArea.php", $m);
	echo renderTemplate(dirname(__FILE__)."/Search/itemArea.php", $m);
?>
