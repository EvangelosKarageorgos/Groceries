					<div class="sort-field">
						<select name="sort-field">
							<option value="price" <?= strcmp($model['sortField'], 'price')==0?'selected' : '' ?> >Price</option>
							<option value="productName" <?= strcmp($model['sortField'], 'productName')==0?'selected' : '' ?> >Product</option>
							<option value="groupName" <?= strcmp($model['sortField'], 'groupName')==0?'selected' : '' ?> >Group</option>
						</select>
					</div>
					<div class="order-field">
						<select name="sort-order">
							<option value="asc" <?= strcmp($model['sortOrder'], 'asc')==0?'selected' : '' ?> >Asc</option>
							<option value="desc" <?= strcmp($model['sortOrder'], 'desc')==0?'selected' : '' ?> >Desc</option>
						</select>
					</div>