<?php
$alertMsg = "";
$text = "'Are you sure to want delete?'";
if (isset($_GET["page"])) {
    $page = (int) $_GET["page"];
} else {
    $page = 1;
}

if (isset($_POST['searchItem'])) {
    $page = 1;
}

$setLimit = 15;
$pageLimit = ($page * $setLimit) - $setLimit;

if (isset($_GET['item_no'])) {
    $item_no = $_GET['item_no'];
} else if (isset($_POST['item_no'])) {
    $item_no = $_POST['item_no'];
} else {
    $item_no = '';
}

if (isset($_GET['cID'])) {
    $cID = $_GET['cID'];
} else if (isset($_POST['cID'])) {
    $cID = $_POST['cID'];
} else {
    $cID = '';
}

?>




<?php
$sql = "SELECT * FROM products_recipe WHERE 1=1";

if ($item_no != '') {

    $sql .= " AND name like '%" . $item_no . "%' ";
}

skipItemSQL:

$sql .= " ORDER BY created_on";
// echo 'SOrt '.$sorting_by;
$sql2 = $sql;
DB::query($sql2);
$total_records = DB::count();
// echo $total_records;
$sql .= ' LIMIT ' . $pageLimit . ', ' . $setLimit;
// echo $sql;
?>

<?php
// Draft Expense Voucher
$tbl = new HTML_Table('', 'table table-hover table-striped table-bordered');
$tbl->addTSection('thead');
$tbl->addRow();
$tbl->addCell('#', '', 'header');
$tbl->addCell('Category', '', 'header');
$tbl->addCell('Product', '', 'header');
$tbl->addCell('Recipe', '', 'header');
$tbl->addCell('Active', '', 'header');
$tbl->addCell('Img', '', 'header');
$tbl->addCell('Action', '', 'header');
$tbl->addTSection('tbody');

$res = DB::query($sql);

foreach ($res as $row) {
    $tbl->addRow();
    $tbl->addCell($row['products_id']);
    $tbl->addCell(get_product_category($row['products_id']));
    $tbl->addCell(get_product_name($row['products_id']));
    $recip_q = DB::query("SELECT po.`name`, ro.`qty`, ro.`cost`, ro.`unit` FROM products_recipe_options ro, products_options po WHERE
ro.`products_options_id` = po.`products_options_id` AND ro.`products_recipe_id` = '" . $row['products_recipe_id'] . "'");
    $recipe = '';
    foreach ($recip_q as $recp) {
        $recipe .= $recp['name'] . ' = ' . $recp['qty'] . $recp['unit'] . ',<BR/>';
    }
    $tbl->addCell($recipe);
    if ($row['active'] == '1') {
        $tbl->addCell('Yes');
    } else {
        $tbl->addCell('No');
    }

    $img = DB::queryFirstField("SELECT img_path	FROM products_img WHERE products_id = '" . (int) $row['products_id'] . "'");
    $tbl->addCell('<img src="../images/products/' . $img . '" width="90px"/>');
    $tbl->addCell('<a href="?route=modules/inventory/add_edit_menu&size='. $row['products_price_id'].'&products_id=' . $row['products_id'] . '" class="btn btn-sm btn-primary">Detail</a>');
    /*
     * // $tbl->addCell('<a href="?route=modules/shop/view_product&products_id='.$row['products_id'].'" class="delete btn btn-sm btn-danger">Delete</a>');
     * $tbl->addCell('<a class="btn btn-large btn-danger" data-toggle="confirmation" data-title="Are You Sure?" data-placement="left"
     * href="?route=modules/inventory/menu&products_id=' . $row['products_id'] . '">Move To Trash</a>');
     */
}

?>
<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		Products <small>Menu</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="<?php echo SITE_ROOT; ?>"><i class="fa fa-dashboard"></i>
				Home</a></li>
		<li class="active">Menu Items</li>
	</ol>
</section>
<!-- Main content -->
<section class="content">
	<!-- Default box -->
	<div class="box">
		<div class="box-header with-border">
			<a href="?route=modules/inventory/menu"><h3 class="box-title">All
					menu items</h3></a>
		</div>
		<div class="box-body">
			<div class="pull-right">
				<a class="btn btn-success"
					href="?route=modules/inventory/add_edit_menu"><i class="fa fa-plus"></i>
					Add New Menu Item</a>
			</div>
							
				<?php  echo $tbl->display(); ?>
			
				<?php
    $page_url = "?route=modules/inventory/menu&";
    if ($cID != '') {
        $page_url .= 'cID=' . $cID . '&';
    }
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
        jQuery(".delete").on("click",function(e){
            e.preventDefault();
//           alert($(this).attr("href")); 
           
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
