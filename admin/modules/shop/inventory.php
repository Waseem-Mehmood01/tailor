<?php
if (isset($_GET["page"])) {
    $page = (int) $_GET["page"];
} else {
    $page = 1;
}

if (isset($_GET['keyword'])) {
    $keyword = $_GET['keyword'];
} else if (isset($_POST['keyword'])) {
    $keyword = $_POST['keyword'];
} else {
    $keyword = '';
}

if (isset($_GET['stype'])) {
    $stype = $_GET['stype'];
} else if (isset($_POST['stype'])) {
    $stype = $_POST['stype'];
} else {
    $stype = '';
}

if (isset($_GET['orderby'])) {
    $orderby = $_GET['orderby'];
} else if (isset($_POST['orderby'])) {
    $orderby = $_POST['orderby'];
} else {
    $orderby = 'p.`created_on`';
}

if (isset($_GET['ordertype'])) {
    $ordertype = $_GET['ordertype'];
} else if (isset($_POST['ordertype'])) {
    $ordertype = $_POST['ordertype'];
} else {
    $ordertype = 'ASC';
}

$setLimit = 15;

$pageLimit = ($page * $setLimit) - $setLimit;

?>




<?php
$sql = "SELECT * FROM products p  JOIN products_price pp ON (pp.`products_id` = p.`products_id`) ";

if ($keyword != '') {

    switch ($stype) {
        case 'barcode':
            $sql .= " WHERE pp.`barcode` = '" . $keyword . "'";
            break;
        case 'name':
            $sql .= "  WHERE p.`name` LIKE '%" . $keyword . "%'";
            break;
        case 'products_id':
            $sql .= "  WHERE p.`products_id` = '" . (int) $keyword . "'";
            break;
        default:
            break;
    }
}

$sql .= " GROUP BY p.`products_id` ORDER BY " . $orderby . " " . $ordertype . " ";

if ($ordertype == 'DESC') {
    $oType = 'ASC';
} else {
    $oType = 'DESC';
}

$sql2 = $sql;
$sql .= ' LIMIT ' . $pageLimit . ', ' . $setLimit;

?>

<?php
// Draft Expense Voucher
$tbl = new HTML_Table('', 'table table-hover table-striped table-bordered');
$tbl->addTSection('thead');
$tbl->addRow();

$tbl->addCell('<a href="?route=modules/shop/inventory&stype=' . $stype . '&keyword=' . $keyword . '&orderby=p.`products_id`&ordertype=' . $oType . '">ID#</a>', '', 'header');
$tbl->addCell('Category', '', 'header');
$tbl->addCell('<a href="?route=modules/shop/inventory&stype=' . $stype . '&keyword=' . $keyword . '&orderby=p.`name`&ordertype=' . $oType . '">Name</a>', '', 'header');
// $tbl->addCell('Meta', '', 'header', array('style'=> 'width: 350px;'));
//$tbl->addCell('SKU', '', 'header');
$tbl->addCell('Size', '', 'header');
//$tbl->addCell('<a href="?route=modules/shop/inventory&stype=' . $stype . '&keyword=' . $keyword . '&orderby=pp.`barcode`&ordertype=' . $oType . '">Barcode</a>', '', 'header');

if ($_SESSION['role_id'] == 1) {
    $tbl->addCell('<a href="?route=modules/shop/inventory&stype=' . $stype . '&keyword=' . $keyword . '&orderby=pp.`cost_price`&ordertype=' . $oType . '">Cost Price</a>', '', 'header');
}
$tbl->addCell('<a href="?route=modules/shop/inventory&stype=' . $stype . '&keyword=' . $keyword . '&orderby=pp.`sale_price`&ordertype=' . $oType . '">Sale Price</a>', '', 'header');
$tbl->addCell('<a href="?route=modules/shop/inventory&stype=' . $stype . '&keyword=' . $keyword . '&orderby=pp.`stock`&ordertype=' . $oType . '">Stock</a>', '', 'header');
$tbl->addCell('Barcode', '', 'header');
$tbl->addCell('View', '', 'header');
$tbl->addTSection('tbody');

$res = DB::query($sql);

foreach ($res as $row) {

    $tbl->addRow();
    $tbl->addCell($row['products_id']);
    $tbl->addCell(get_category_name($row['categories_id']));
    $tbl->addCell('<a href="#" class="editable" data-url="ajax_helpers/ajax_update_product.php" data-name="name" data-type="textarea" data-pk="' . $row['products_id'] . '" data-title="Edit product">' . $row['name'] . '</a>');
   // $tbl->addCell('<a href="#" class="editable" data-url="ajax_helpers/ajax_update_product.php" data-name="sku" data-type="text" data-pk="' . $row['products_id'] . '" data-title="Edit SKU">' . $row['sku'] . '</a>');
    // $tbl->addCell('<a href="#" class="editable" data-url="ajax_helpers/ajax_update_product.php" data-name="meta_title" data-type="text" data-pk="'.$row['products_id'].'" data-title="Meta Title">'.$row['meta_title'].'</a>
    // <br/><a href="#" class="editable" data-url="ajax_helpers/ajax_update_product.php" data-name="meta_description" data-type="textarea" data-pk="'.$row['products_id'].'" data-title="Meta Description">'.$row['meta_description'].'</a>
    // <br/><a href="#" class="editable" data-url="ajax_helpers/ajax_update_product.php" data-name="meta_keywords" data-type="textarea" data-pk="'.$row['products_id'].'" data-title="Meta Keywords">'.$row['meta_keywords'].'</a>
    // ');
    $tbl->addCell('');
    $tbl->addCell('');
    $tbl->addCell('');
    $tbl->addCell('');
    $tbl->addCell('');
    $tbl->addCell('');
    $prices = DB::query("SELECT * FROM products_price pp WHERE pp.`products_id` = '" . $row['products_id'] . "'");
    foreach ($prices as $price) {
        $tbl->addRow();

        //$tbl->addCell('');
        $tbl->addCell('');
        $tbl->addCell('');
        $tbl->addCell('');
        $tbl->addCell($price['size']);
       // $tbl->addCell('<a href="#" class="editable" data-url="ajax_helpers/ajax_update_price_data.php" data-name="barcode" data-type="text" data-pk="' . $price['products_price_id'] . '" data-title="Edit barcode">' . $price['barcode'] . '</a>');
        
        if ($_SESSION['role_id'] == 1) {
            $tbl->addCell('<a href="#" class="editable" data-url="ajax_helpers/ajax_update_price_data.php" data-name="cost_price" data-type="text" data-pk="' . $price['products_price_id'] . '" data-title="Edit cost">' . $price['cost_price'] . '</a>');
        }
        $tbl->addCell('<a href="#" class="editable" data-url="ajax_helpers/ajax_update_price_data.php" data-name="sale_price" data-type="text" data-pk="' . $price['products_price_id'] . '" data-title="Edit sale price">' . $price['sale_price'] . '</a>');
        $tbl->addCell('<a href="#" class="editable" data-url="ajax_helpers/ajax_update_price_data.php" data-name="stock" data-type="number" data-pk="' . $price['products_price_id'] . '" data-title="Edit stock">' . $price['stock'] . '</a>');
    }
    $tbl->addCell('<a target="_blank" href="?route=modules/shop/manage_barcodes&item_no=' . $row['products_id'] . '" class="btn btn-sm btn-primary">View Barcode</a>');
    $tbl->addCell('<a target="_blank" href="?route=modules/shop/view_product&products_id=' . $row['products_id'] . '" class="btn btn-sm btn-success">Detail</a>');
}

?>
<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		Inventory <small>View all Products</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="<?php echo SITE_ROOT; ?>"><i class="fa fa-dashboard"></i>
				Home</a></li>
		<li class="active">Detail Products</li>
	</ol>
</section>
<!-- Main content -->
<section class="content">
	<!-- Default box -->
	<div class="box">
		<div class="box-header with-border">
			<a href="?route=modules/shop/inventory"><h3 class="box-title">All
					products</h3></a>
		</div>
		<div class="box-body">
			<div class="col-md-4 pull-right">
				<form method="POST" class="form-inline" action="">
					<div class="input-group">
						<input type="text" name="keyword" value="<?php echo $keyword; ?>"
							placeholder="Seach.." class="form-control" /> <span
							class="input-group-addon"> <select name="stype">
								<option value="barcode"
									<?php if($stype=='barcode'){ echo 'SELECTED'; } ?>>Barcode</option>
								<option value="name"
									<?php if($stype=='name'){ echo 'SELECTED'; } ?>>Name</option>
								<option value="products_id"
									<?php if($stype=='products_id'){ echo 'SELECTED'; } ?>>Item ID</option>
						</select></span> <span class="input-group-btn">
							<button type="submit" id="searchItem" name="searchItem"
								class="btn btn-default" type="button">
								<i class="fa fa-search"></i>
							</button>
						</span>
					</div>
				</form>
			</div>
							
				<?php  echo $tbl->display(); ?>
			
				<?php
    $page_url = "?route=modules/shop/inventory&stype=" . $stype . "&keyword=" . $keyword . "&orderby=" . $orderby . "&ordertype=" . $ordertype . "&";
    echo displayPaginationBelow($setLimit, $page, $sql2, $page_url);
    ?>


            </div>
		<!-- /.box-body -->
		<div class="box-footer"></div>
	</div>
	<!-- /.box -->
</section>
<script>
	$(function(){

		
		
		$("#item_no").on('keydown keyup',function(e){
			console.log(e.keyCode);
			if(e.keyCode=='13'){
				$("#searchItem").trigger('click');		
			}		
			});
	});
       
        $('[data-toggle=confirmation]').confirmation({
        rootSelector: '[data-toggle=confirmation]',
        // other options
        onConfirm: function(value) {
//            alert('You choosed ' + value);
        },
        onCancel: function() {
            $('[data-toggle=confirmation]').confirmation('hide');
        }
      });
</script>
