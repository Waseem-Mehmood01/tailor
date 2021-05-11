<?php
$filter = isset($_GET['filter']) ? (int) $_GET['filter'] : 7;

$days = array();

$items = array();
for ($i = 0; $i < $filter; $i ++) {
    $orders = DB::queryFirstColumn("SELECT orders_id FROM orders o WHERE DATE(created_on)= DATE('2020-09-23')
");

    $hot_items = DB::query("SELECT
  p.`products_id`, p.`name`, SUM(p.`qty`) AS qty
FROM
  orders_products p
  LEFT JOIN orders o
  ON(o.`orders_id`=p.`orders_id`)
  WHERE DATE(o.`created_on`)= DATE('" . date('Y-m-d', strtotime('-' . $i . ' days')) . "')
GROUP BY
  p.`products_id`
ORDER BY
  SUM(p.`qty`) DESC");

    foreach ($hot_items as $itm) {

        $da = array(
            'Product_id' => $itm['products_id'],
            'Name' => $itm['name'],
            'Qty' => $itm['qty']
        );
        array_push($items, $da);
    }
}

$sold_qty = array();
foreach ($items as $key => $row) {
    $sold_qty[$key] = $row['Qty'];
}
array_multisort($sold_qty, SORT_DESC, $items);

$result = array();
foreach ($items as $k => $v) {
    $Product_id = $v['Product_id'];
    $result[$Product_id][] = $v['Qty'];
}

$hot_items = array();

foreach ($result as $key => $value) {
    $hot_items[] = array(
        'Product_id' => $key,
        'Qty' => array_sum($value)
    );
}

?>


<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		<a href="?route=modules/shop/stock_forecast"><i class="fa fa-refresh"
			aria-hidden="true"></i></a>&nbsp;Inventory <small>Demand Stock
			Forecasting</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="<?php echo SITE_ROOT; ?>"><i class="fa fa-dashboard"></i>
				Home</a></li>
		<li class="active">Stock Forecasting</li>
	</ol>
</section>
<!-- Main content -->
<section class="content">
	<!-- Default box -->
	<div class="box">
		<div class="box-header with-border"></div>
		<div class="box-body">
			<table class="table table-hover table-striped table-bordered">
				<thead>
					<tr>
						<th>ID</th>
						<th>Product</th>
						<th>Selling this Week</th>
						<th>Current Stock</th>
					</tr>
				</thead>
				<tbody>
				<?php  for ($i = 0; $i < count($hot_items); $i ++) { ?>
				<tr>
						<td><?php echo $hot_items[$i]['Product_id']; ?></td>
						<td><?php echo get_product_name($hot_items[$i]['Product_id']); ?></td>
						<td><?php echo $hot_items[$i]['Qty']; ?></td>
						<td><?php echo $hot_items[$i]['Product_id']; ?></td>
					</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
		<!-- /.box-body -->
		<div class="box-footer"></div>
	</div>
	<!-- /.box -->
</section>