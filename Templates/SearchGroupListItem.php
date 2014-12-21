<div class="groupItem">

	<?php

		$isChecked = (false) ? "checked" : ""; 
		
		$generatedInputField = "<input type=\"checkbox\" name=\"group\" value=\"".$model['code']."\" ".$isChecked.">"
									.$model['name']
								."</input>";
		
		echo $generatedInputField;
	?>



</div>

