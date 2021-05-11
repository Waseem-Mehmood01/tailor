 <?php
$alertMsg = '';

$products_id = isset($_GET['products_id']) ? (int) $_GET['products_id'] : die('Something went wrong go back..');

$product = DB::queryFirstRow("SELECT * FROM products p WHERE p.`products_id` = '" . $products_id . "'");

if (empty($product)) {
    die('Product not found.. go back');
}

if (isset($_GET['active'])) {
    DB::update('products', array(
        'active' => (int) $_GET['active']
    ), 'products_id=%s', $products_id);
    echo '<script>alert("Product status changed");location.href="?route=modules/shop/view_product&products_id=' . $products_id . '";</script>';
}

?>
<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		Products <small>View Product</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="<?php echo SITE_ROOT; ?>"><i class="fa fa-dashboard"></i>
				Home</a></li>
		<li class="active">Detail Product</li>
	</ol>
</section>

<!-- Main content -->
<section class="content">

	<!-- Default box -->

	<div class="box">
		<div class="box-header with-border">
			<a href="?route=modules/shop/manage_products"><h3 class="box-title">View
					All</h3></a>
			<div class="col-md-3 pull-right">
              <?php if($product['active']=='1'){ ?>
              <a
					onclick="return confirm('Are you sure to de-activate this product?');"
					href="?route=modules/shop/view_product&active=0&products_id=<?php echo $products_id; ?>"
					class="btn btn-danger pull-right"> De-Active</a>
              <?php } else {?>
              	<a
					onclick="return confirm('Are you sure to activate this product?');"
					href="?route=modules/shop/view_product&active=1&products_id=<?php echo $products_id; ?>"
					class="btn btn-success pull-right"> Activate</a>
              <?php } ?>
              
              <?php if($product['is_promotion']=='0'){ ?>
              &nbsp;<a
					href="?route=modules/shop/edit_product&edit=yes&products_id=<?php echo $products_id; ?>"
					class="btn btn-primary"><i class="fa fa-pencil"></i> Edit</a>
              <?php } ?>
              </div>
		</div>

		<div class="col-md-12">
			<div class="col-md-4 pull-right">
				<div id="msgDiv" style="display: none;"
					class="alert alert-success alert-dismissible col-md-6 col-md-offset-3">
					<button type="button" class="close" data-dismiss="alert"
						aria-hidden="true">×</button>
					<h4><?php echo $alertMsg; ?></h4>

				</div>
			</div>

		</div>
		<div class="box-body">
			<div class="row">
				<div class="col-md-12">
					<div class="col-md-6">

						<h3>Detail</h3>
						<table style="width: 100%">
							<tr>
								<td width="50%"><h4>Category</h4></td>
								<td><h5><?php echo get_category_name($product['categories_id']); ?></h5></td>
							</tr>

							<tr>
								<td width="50%"><h4>Supplier</h4></td>
								<td><h5><?php echo get_manufacturers_name($product['manufacturers_id']); ?></h5></td>
							</tr>

							<tr>
								<td width="50%"><h4>Product Title</h4></td>
								<td><h3><?php echo $product['name']; ?></h3></td>
							</tr>
							<tr>
								<td width="50%"><h4>Short Description</h4></td>
								<td><h5><?php echo $product['description1']; ?></h5></td>
							</tr>
							<tr>
								<td width="50%"><h4>Long Description</h4></td>
								<td><h5><?php echo $product['description2']; ?></h5></td>
							</tr>

						</table>

					</div>

					<div class="col-md-6" style="border-left: 2px solid beige;">
						<h3>Attributes</h3>
						<table style="width: 100%">
							<tr>
								<td width="50%"><h4>SKU</h4></td>
								<td><h5><?php echo $product['sku']; ?></h5></td>
							</tr>

							<tr>
								<td width="50%"><h4>Joints</h4></td>
								<td><h5><?php echo $product['joint']; ?></h5></td>
							</tr>

							<tr>
								<td width="50%"><h4>Highlights</h4></td>
								<td><h4><?php echo $product['highlights']; ?></h4></td>
							</tr>
							<tr>
								<td width="50%"><h4>Created On</h4></td>
								<td><h4><?php echo $product['created_on']; ?></h4></td>
							</tr>
							<tr>
								<td width="50%"><h4>Active</h4></td>
								<td><h5><?php

if ($product['active'] == '1') {
            echo 'Yes';
        } else {
            echo 'No';
        }
        ?></h5></td>
							</tr>


						</table>
					</div>

				</div>
			</div>

		</div>
		<!-- /.box-body -->

		<section class="content" style="border-top: 2px solid #d2d6de;">
			<div class="box-body">
				<div class="row">
					<div class="col-md-12">
						<h3>Price</h3>
						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<th><label>Size</label></th>
									<th><label>Barcode</label></th>
									<th><label>Cost Price</label></th>
									<th><label>Sale Price</label></th>
									<th><label>Minimum Bid</label></th>
									<th><label>Stock in-hand</label></th>
									<th><label>Stock Check</label></th>
									<th><label>Active</label></th>
								</tr>
							</thead>
							<tbody>
                						<?php
                    $prices = DB::query("SELECT * FROM products_price pp WHERE pp.`products_id` = '" . $products_id . "'");
                    foreach ($prices as $price) {
                        ?>
                						<tr>
									<td><?php echo $price['size']; ?></td>
									<td><?php echo $price['barcode']; ?></td>
									<td><?php echo $price['cost_price']; ?></td>
									<td><?php echo $price['sale_price']; ?></td>
									<td><?php echo $price['min_bid']; ?></td>
									<td><?php echo $price['stock']; ?></td>
									<td><?php if($price['stock_check'] == '1') { echo 'Yes'; } else { echo 'No'; } ?></td>
									<td><?php if($price['active'] == '1') { echo 'Yes'; } else { echo 'No'; } ?></td>
								</tr>
                						<?php } ?>
                					</tbody>
						</table>
					</div>
				</div>
			</div>

		</section>

		<section class="content" style="border-top: 2px solid #d2d6de;">
			<div class="box-body">
				<div class="row">
					<div class="col-md-12">
						<h3>Images</h3>
						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<th><label>Color</label></th>
									<th><label>Is 360</label></th>
									<th><label>Order Image</label></th>
									<th><label>Active</label></th>
									<th><label>Image</label></th>
								</tr>
							</thead>
							<tbody>
                						<?php
                    $prices = DB::query("SELECT * FROM products_img pp WHERE pp.`products_id` = '" . $products_id . "'");
                    foreach ($prices as $price) {
                        ?>
                						<tr>
									<td><?php echo $price['color']; ?></td>
									<td><?php if($price['is_360'] == '1') { echo 'Yes'; } else { echo 'No'; } ?></td>
									<td><?php echo $price['order_img']; ?></td>
									<td><?php if($price['active'] == '1') { echo 'Yes'; } else { echo 'No'; } ?></td>

									<td><img
										src="../images/products/<?php echo $price['img_path']; ?>"
										width="150px"></td>

								</tr>
                						<?php } ?>
                					</tbody>
						</table>
					</div>
				</div>
			</div>

		</section>

		<div class="box-footer"></div>
	</div>
	<!-- /.box -->
</section>
