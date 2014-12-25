<div class="product">
	<span><?= $model['prodCode'] ?></span>
	<span><?= $model['name'] ?></span>
	<span><?= $model['qty'] ?></span>
	<form method="POST">
		<input type="hidden" name="submittype" value="supply" />
		<input type="hidden" name="prodCode" value="<?=$model['prodCode'] ?>" />
		<select name="supplierId" >
		<?php foreach($model['suppliers'] as &$supplier){ ?>
			<option value="<?= $supplier['supplierId'] ?>"><?= $supplier['name'] ?></option>
		<?php } ?>
		</select>
		<input type="submit" class="groceriesBtn" value="supply" />
	</form>
	<div class="new-supplier-container">
		<input type="button" class="expander" value="new supplier" onClick="toggleExpander(this)"/>
		<div class="details expandable-content<?= (count($model['suppliers'])>0 && $model['newexpanded']==false)?" hidden":"" ?>">		
			<form method="POST">
				<input type="hidden" name="submittype" value="add-supplier" />
				<input type="hidden" name="prodCode" value="<?=$model['prodCode'] ?>" />
				<input type="text" name="suppliername" value="<?=$model['supName'] ?>" placeholder="name" />
				<?php if($model['noname']){ ?><div class="error">Name must be filled</div><?php } 
				else if($model['nameexists']){ ?><div class="error">Supplier name already exists</div><?php } else { ?><div>&nbsp;</div><?php } ?>
				<input type="text" name="supplieremail" value="<?=$model['supEmail'] ?>" placeholder="email" />
				<?php if($model['noemail']){ ?><div class="error">Email must be filled</div><?php } else { ?><div>&nbsp;</div><?php } ?>
				<input type="submit" class="groceriesBtn" value="Add supplier" />
			</form>
		</div>
	</div>

</div>