<div class="searchbox">
			
	<div class="box-heading">
		<span>Advanced Search</span>
	</div>
				
	<div class="box-content"> 
		<span>Search Items</span> 
	</div>
	<form method="GET">

		<div class="search-text-selector form-field">
			<span class="title">Text</span>
			<div class="field">
				<div class="label">Keyqords :</div>
				<input type="text" name="searchText" placeholder="keywords" />
			</div>

		</div>
	
	
		<div class="group-selector form-field">
			<span class="title">Groups</span>
			<div class="list">
				<ul class="groups-values">
					<li><input type="checkbox" name="group" value="M" checked>Vegetables</input></li>
					<li><input type="checkbox" name="group" value="S" checked>Seafood</input></li>
				</ul>
			</div>
		</div>
		

		
		<div class="price-range-selector form-field">
			<span class="title">Price range</span>
			<div class="field">
				<div class="label">From :</div>
				<input type="text" name="priceFrom" placeholder="from" />
			</div>
			<div class="field">
				<div class="label">To :</div>
				<input type="text" name="priceTo" placeholder="to" />
			</div>
		</div>
		
		<div class="sorting-selector form-field">
			<span class="title">Sorting</span>
			<div class="field">
				<div class="label">Sort field :</div>
				<select name="sort-field">
					<option value="price">Price</option>
					<option value="productName">Product name</option>
					<option value="ggroupName">Group name</option>
				</select>
			</div>
			<div class="field">
				<div class="label">Order :</div>
				<select name="sort-order">
					<option value="desc">Descending</option>
					<option value="asc">Ascending</option>
				</select>
			</div>
		</div>

		
		
		<input type="submit" value="Search"/>
	</form>
	
	
	
			
</div>