<?php
	require_once dirname(__FILE__)."/../Code/init.php";
	Application::getAuth()->enterProtectedPage();

 includeHeadResource(new StyleResource(Application::getRequest()->getBasePath()."/styles/db.css"));

	
	$noname = false;
	$noemail = false;
	$noaddress = false;
	$notown = false;
	$nopostcode = false;
	$submitted = intval(Application::getRequest()->getPostParam("submitted", "0")) !=0;
	if($submitted){
		$name = trim(Application::getRequest()->getPostParam("name", ""));
		$email = trim(Application::getRequest()->getPostParam("email", ""));
		$address = trim(Application::getRequest()->getPostParam("address", ""));
		$town = trim(Application::getRequest()->getPostParam("town", ""));
		$postcode = trim(Application::getRequest()->getPostParam("postcode", ""));
		$noname = strlen($name)==0;
		$noemail = strlen($email)==0;
		$noaddress = strlen($address)==0;
		$notown = strlen($town)==0;
		$nopostcode = strlen($postcode)==0;
		$submitted = !($noname || $noemail || $noaddress || $notown || $nopostcode);
		$info = array("Name" => $name, "Email" => $email, "Address" => $address, "Town" => $town, "Postcode" => $postcode);
	} else
	{
		$info = Application::getAuth()->getCustomerInfo();
	}
	
	


?>
<?php if($submitted){
	Application::getDB()->ExecuteInsertUpdate("update users set cust_name='?', email='?', street='?', town='?', post_code='?' where cust_no=?"
	, $name, $email, $address, $town, $postcode, Application::getAuth()->getCustomerNum());
	WebTools::redirect(Application::getRequest()->getBasePath()."/account");
} else {
	echo renderTemplate(dirname(__FILE__)."/../Templates/Account/accountMenu.php", array('selected'=>'changeDetails'));
?>
<form id="change-details-form" method="POST">
	<input type="hidden" name="submitted" value="1" />
	<div class="change-details">
		<div class="field name">
			<span class="label">Name :</span>
			<input class="value" type="text" name="name" value="<?= $info['Name'] ?>" />
			<?php if($noname){ ?> <div class="error">Empty name</div> <?php } ?>
		</div>
		<div class="field email">
			<span class="label">Email :</span>
			<input class="value" type="text" name="email" value="<?= $info['Email'] ?>" />
			<?php if($noemail){ ?> <div class="error">Empty email</div> <?php } ?>
		</div>
		<div class="field address">
			<span class="label">Address :</span>
			<input class="value" type="text" name="address" value="<?= $info['Address'] ?>" />
			<?php if($noaddress){ ?> <div class="error">Empty address</div> <?php } ?>
		</div>
		<div class="field town">
			<span class="label">Town :</span>
			<input class="value" type="text" name="town" value="<?= $info['Town'] ?>" />
			<?php if($notown){ ?> <div class="error">Empty town</div> <?php } ?>
		</div>
		<div class="field postcode">
			<span class="label">Post code :</span>
			<input class="value" type="text" name="postcode" value="<?= $info['Postcode'] ?>" />
			<?php if($nopostcode){ ?> <div class="error">Empty post code</div> <?php } ?>
		</div>
		<input type="submit" class="groceriesBtn" value="Submit" />
	</div>
</form>
<?php } ?>
