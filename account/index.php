<?php
require_once dirname(__FILE__)."/../Code/init.php";
Application::getAuth()->enterProtectedPage();
echo renderTemplate(dirname(__FILE__)."/../Templates/Account/accountMenu.php", array());
?>


<?php
	$info = Application::getAuth()->getCustomerInfo();
?>
<div class="account-info">
	<div class="field name">
		<span class="label">Name :</span>
		<span class="value"><?= $info['Name'] ?></span>
	</div>
	<div class="field email">
		<span class="label">Email :</span>
		<span class="value"><?= $info['Email'] ?></span>
	</div>
	<div class="field address">
		<span class="label">Address :</span>
		<span class="value"><?= $info['Address'] ?></span>
	</div>
	<div class="field town">
		<span class="label">Town :</span>
		<span class="value"><?= $info['Address'] ?></span>
	</div>
	<div class="field postcode">
		<span class="label">Post code :</span>
		<span class="value"><?= $info['Postcode'] ?></span>
	</div>
	<div class="field credit-balance">
		<span class="label">Credit-balance :</span>
		<span class="value"><?= $info['CreditBalance'] ?></span>
	</div>

</div>