<div class="searchbox">
			
	<div class="box-heading">
		<span>Admin Area</span>
	</div>
				
	<div class="box-content"> 

			<div class="form-field">
				<span class="title">Statistics</span>
					<div class="query-menu-category">
						<span class="title">Most Popular</span>
						<div title="Most Popular Products(week)" class="query-menu-item"> 
							<a href="<?= Application::getRequest()->getBasePath()."/admin/topProducts.php" ?>">Products</a> 
						</div>
						<div title="Most Popular Suppliers(week)" class="query-menu-item"> 
							<a href="<?= Application::getRequest()->getBasePath()."/admin/topSuppliers.php" ?>">Suppliers</a> 
						</div>
						<div title="Most Popular Postal Codes(week)" class="query-menu-item"> 
							<a href="<?= Application::getRequest()->getBasePath()."/admin/topPostCodes.php" ?>">Post Codes</a> 
						</div>		
					</div>

					<div class="query-menu-category">
						<span class="title">Various</span>
						<div title="Never been ordered Products " class="query-menu-item"> 
							<a href="<?= Application::getRequest()->getBasePath()."/admin/notOrderedProducts.php" ?>"> Not Ordered Products </a> 
						</div>
						<div title="Most Expensive Product per Group" class="query-menu-item"> 
							<a href="<?= Application::getRequest()->getBasePath()."/admin/mostExpensiveProducts.php" ?>">Expensive Products</a> 
						</div>
						
						<div title="Best Sellers Products per Group" class="query-menu-item"> 
							<a href="<?= Application::getRequest()->getBasePath()."/admin/bestSellersProducts.php" ?>">Best Sellers</a> 
						</div>
						
					</div>
			</div>
		
		
			<div class="form-field">
				<span class="title">User Awards</span>
					<div class="query-menu-item"> 
						<a href="<?= Application::getRequest()->getBasePath()."/admin/bestClients.php" ?>"> Best Clients </a> 
					</div>				
				<div class="clrfloat"> </div>
								
			</div>
			
			
			<div class="form-field">
				<span class="title">Logistics</span>
					<div class="query-menu-item"> 
						<a href="<?= Application::getRequest()->getBasePath()."/admin/underMinStockProducts.php" ?>"> Running Out Products </a> 
					</div>				
					<div class="query-menu-item"> 
						<a href="<?= Application::getRequest()->getBasePath()."/admin/pendingOrders.php" ?>"> Pending Orders </a> 
					</div>				
					<div class="query-menu-item"> 
						<a href="<?= Application::getRequest()->getBasePath()."/admin/supplyProducts.php" ?>"> Supply Products </a> 
					</div>				
				<div class="clrfloat"> </div>
								
			</div>
			
			
			<div class="clrfloat"> </div>
			
	</div>
	
	
	
	
	
			
</div>