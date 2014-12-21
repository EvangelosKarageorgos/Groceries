<div class="groupItem">

	<?php
		$isChecked = (in_array($model['code'], $model['searchGroups'])) ? "checked" : ""; 
		
		$generatedInputField = "<input type=\"checkbox\" name=\"group\" value=\"".$model['code']."\" ".$isChecked.">"
									.$model['name']
								."</input>";
		
		echo $generatedInputField;
	?>



</div>

