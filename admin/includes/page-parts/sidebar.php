<?php
if ($path != "") {
    $path = explode('/', $path);
    $path = $path[1];
}

?>
<aside class="main-sidebar">
	<!-- sidebar: style can be found in sidebar.less -->
	<section class="sidebar" style="height: auto;">
		<!-- sidebar menu: : style can be found in sidebar.less -->
		<ul class="sidebar-menu tree" data-widget="tree">
			<li class="header">MAIN NAVIGATION</li>
			<li class="<?php if($path==""){ echo '  active'; } ?>"><a
				href="index.php"> <i class="fa fa-dashboard"></i> <span>Dashboard</span>
			</a></li>
			<li class="treeview <?php if($path=="orders"){ echo '  active'; } ?>"><a
				href="#"> <i class="fa fa-pie-chart"></i> <span>Orders</span> <span
					class="pull-right-container"> <i
						class="fa fa-angle-left pull-right"></i>
				</span>
			</a>
				<ul class="treeview-menu">
					<li><a
						href="<?php echo SITE_ROOT; ?>?route=modules/orders/view_orders&phoneid=pos">POS</a></li>
					<li><a
						href="<?php echo SITE_ROOT; ?>?route=modules/orders/view_orders&phoneid=web">Web</a></li>
				</ul></li>
			<li class="treeview <?php if($path=="shop"){ echo '  active'; } ?>"><a
				href="#"> <i class="fa fa-shopping-cart"></i> <span>Shop</span><span
					class="pull-right-container"> <i
						class="fa fa-angle-left pull-right"></i>
				</span>
			</a>
				<ul class="treeview-menu">
					<li><a
						href="<?php echo SITE_ROOT; ?>?route=modules/shop/manage_categories">Products
							Categories</a></li>
					<li><a
						href="<?php echo SITE_ROOT; ?>?route=modules/shop/manage_products">Products</a></li>
					<li class="divider"></li>
					
					<li><a href="<?php echo SITE_ROOT; ?>?route=modules/shop/inventory">Stock
							in Hand</a></li>
							<li class="divider"></li>
							<li><a href="<?php echo SITE_ROOT; ?>?route=modules/shop/manage_barcodes">Manage Barcodes</a></li>
							<li class="divider"></li>
					<li><a href="<?php echo SITE_ROOT; ?>?route=modules/shop/promotions">Promotion Items</a></li>
					<li><a
						href="<?php echo SITE_ROOT; ?>?route=modules/shop/discounts">Disounts</a></li>
					<li class="divider"></li>
							<li><a href="<?php echo SITE_ROOT; ?>?route=modules/shop/stock_forecast">Stock
							Forecast</a></li>
				</ul></li>
			<li
				class="treeview <?php if($path=="customers"){ echo '  active'; } ?>"><a
				href="#"> <i class="fa fa-users"></i> <span>Customers</span><span
					class="pull-right-container"> <i
						class="fa fa-angle-left pull-right"></i>
				</span>
			</a>
				<ul class="treeview-menu">
					<li><a
						href="<?php echo SITE_ROOT; ?>?route=modules/customers/pos_customers">Our
							Customers</a></li>
					<li class="divider"></li>
					<li><a
						href="<?php echo SITE_ROOT; ?>?route=modules/customers/users_visit">Web Visitors</a></li>
					<li class="divider"></li>
				</ul></li>
			<li
				class="treeview <?php if($path=="purchase"){ echo '  active'; } ?>"><a
				href="#"> <i class="fa fa-cubes"></i> <span>Purchase</span><span
					class="pull-right-container"> <i
						class="fa fa-angle-left pull-right"></i>
				</span>
			</a>
				<ul class="treeview-menu">
					<li><a
						href="<?php echo SITE_ROOT; ?>?route=modules/purchase/new_purchase">New
							Purchase</a></li>
					<li><a
						href="<?php echo SITE_ROOT; ?>?route=modules/purchase/view_all_purchase">View
							All Purchases</a></li>
					<li class="divider"></li>
					<li><a
						href="<?php echo SITE_ROOT; ?>?route=modules/purchase/manage_manufacturer">Suppliers</a></li>
				</ul></li>
			<li class="treeview <?php if($path=="offers"){ echo '  active'; } ?>"><a
				href="#"> <i class="fa fa-trophy"></i> <span>Offers</span><span
					class="pull-right-container"> <i
						class="fa fa-angle-left pull-right"></i>
				</span>
			</a>
				<ul class="treeview-menu">
					<li><a href="<?php echo SITE_ROOT; ?>?route=modules/offers/coupons">Coupns</a></li>
				</ul></li>
			<li
				class="treeview <?php if($path=="reports"){ echo '  active'; } ?>"><a
				href="#"> <i class="fa fa-bar-chart"></i> <span>Analysis</span><span
					class="pull-right-container"> <i
						class="fa fa-angle-left pull-right"></i>
				</span>
			</a>
				<ul class="treeview-menu">
					<li><a
						href="<?php echo SITE_ROOT; ?>?route=modules/reports/sale_report">Sale
							Report</a></li>
					<li><a
						href="<?php echo SITE_ROOT; ?>?route=modules/reports/items_sold">Products
							Sale</a></li>
				</ul></li>
				
				
				
		</ul>
	</section>
	<!-- /.sidebar -->
</aside>
<div class="content-wrapper" style="min-height: 260px;">