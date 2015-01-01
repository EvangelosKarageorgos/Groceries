<?php
require_once dirname(__FILE__)."/Code/init.php";
?>

<?php
	$showError = false;
	$errorMsg = "";
	if(Application::getRequest()->getPostParam("action", "") == "register"){
	
			$userName 	= Application::getRequest()->getPostParam("uname", "");
			$passWord 	= Application::getRequest()->getPostParam("pass", "");
			$fullName 	= Application::getRequest()->getPostParam("fullname", "");
			$eMail 		= Application::getRequest()->getPostParam("email", "");
			$street 	= Application::getRequest()->getPostParam("street", "");
			$town 		= Application::getRequest()->getPostParam("town", "");
			$postcode 	= Application::getRequest()->getPostParam("postcode", "");
			
			$errorCode = Application::getAuth()->register($userName, $passWord, $fullName, $eMail, $street, $town, $postcode);
			
			if($errorCode==0){
				$dst = Application::getRequest()->getGetParam("dst", "");
				if(strlen($dst)>0){
					WebTools::redirect(WebTools::urlDecode($dst));
				} else
					WebTools::redirect(Application::getRequest()->getBasePath()."/index.php");
			} 
			else if($errorCode==1){
				$showError = true;
				$errorMsg = "Missing fields";
			}
			
			else if($errorCode==2){
				$showError = true;
				$errorMsg = "Username already exists";
			}
			
	}
?>
<form method="POST">
	<input class="btn" type="hidden" name="action" value="register" />
	
	<div class="registerArea">
	
		<div class="personalInfo">
			<div class="registerRow">
				<div>Username :</div>
				<div><input name="uname" type="text"/></div>
			</div>
			
			<div class="registerRow">
				<div>Password :</div>
				<div><input name="pass" type="password"/></div>
			</div>
			
			
			<div class="registerRow">
				<div>Full Name :</div>
				<div><input name="fullname" type="text"/></div>
			</div>
			
			<div class="registerRow">
				<div>eMail :</div>
				<div><input name="email" type="text"/></div>
			</div>
		</div>
		
		<div class="addressInfo">
			<div class="registerRow">
				<div>Street :</div>
				<div><input name="street" type="text"/></div>
			</div>
			
			<div class="registerRow">
				<div>Town :</div>
				<div><input name="town" type="text"/></div>
			</div>
			
			<div class="registerRow">
				<div>Post Code :</div>
				<div><input name="postcode" type="text"/></div>
			</div>
		</div>
	
	</div>
	
	
	
	
	
	<?php if($showError){ ?>
		<div><?= $errorMsg ?></div>
	<?php } ?>
	
	<div class="submitButtonArea">
		<input class="groceriesBtn" type="submit" value="Register"/>
	</div>
	
</form>