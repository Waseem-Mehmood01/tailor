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

if (isset($_GET['active'])) {
    @extract($_GET);
    DB::update("products", array(
        "active" => $active
    ), "products_id=%s", $products_id);
    echo '<script>alert("Updated..!");location.href="?route=modules/shop/manage_products&item_no=' . $item_no . '&page=' . $page . '";</script>';
}

if (isset($_GET['delete'])) {
    @extract($_GET);
    DB::delete("products", "products_id=%s", $products_id);
    echo '<script>alert("Deleted..!");location.href="?route=modules/shop/manage_products&item_no=' . $item_no . '&page=' . $page . '";</script>';
}

$sql = "SELECT * FROM products p WHERE 1=1 AND p.is_promotion=0 ";

if ($cID != '') {

    $sql = "SELECT * FROM products p LEFT JOIN categories c ON(c.`categories_id` = p.`categories_id`) WHERE ( c.`categories_id` = '" . $cID . "' OR c.`parent_id` = '" . $cID . "') AND p.is_promotion=0 ";
    if ($item_no != '') {

        $sql .= " AND p.`name` like '%" . $item_no . "%' ";
        goto skipItemSQL;
    }
}

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
$tbl = new HTML_Table('', 'table table-hover table-bordered');
$tbl->addTSection('thead');
$tbl->addRow();
$tbl->addCell('ID#', '', 'header');
$tbl->addCell('Category', '', 'header');
$tbl->addCell('Product Name', '', 'header');
$tbl->addCell('Avg SalePrice', '', 'header');
$tbl->addCell('Avg Cost', '', 'header');
$tbl->addCell('Active', '', 'header');
$tbl->addCell('Action', '', 'header');
$tbl->addTSection('tbody');

$res = DB::query($sql);

foreach ($res as $row) {
    $tbl->addRow();
    $tbl->addCell($row['products_id']);
    $tbl->addCell(get_category_name($row['categories_id']));
    $img = DB::queryFirstField("SELECT img_path	FROM products_img WHERE products_id = '" . (int) $row['products_id'] . "'");
    $tbl->addCell('<img src="../images/products/' . $img . '" width="90px"/><strong>' . $row['name'] . '</strong>');
    $tbl->addCell(get_product_avg_sale($row['products_id']));
    $tbl->addCell(get_product_avg_cost($row['products_id']));
    if ($row['active'] == '1') {
        $tbl->addCell('<span class="text-success">Yes</span>');
    } else {
        $tbl->addCell('<span class="text-danger">No</span>');
    }

    // $tbl->addCell('<a href="?route=modules/shop/view_product&products_id='.$row['products_id'].'" class="delete btn btn-sm btn-danger">Delete</a>');
    $tbl->addCell('<a href="?route=modules/shop/view_product&products_id=' . $row['products_id'] . '" class="btn btn-xs pull-left btn-default">Detail</a>&nbsp;<div class="dropdown pull-right">
  <button class="btn btn-xs btn-primary  dropdown-toggle" type="button" data-toggle="dropdown">Action
  <span class="caret"></span></button>
  <ul class="dropdown-menu">
    <li><a href="?route=modules/shop/manage_products&active=1&products_id=' . $row['products_id'] . '&item_no=' . $item_no . '&page=' . $page . '">Active</a></li>
    <li><a href="?route=modules/shop/manage_products&active=0&products_id=' . $row['products_id'] . '&item_no=' . $item_no . '&page=' . $page . '">In-Active</a></li>
<li><a href="?route=modules/shop/edit_product&edit=yes&products_id=' . $row['products_id'] . '">Edit</a></li>    
<li><a onclick="return confirm(\'Are sure to delete this item? Action can not be undo.\');" href="?route=modules/shop/manage_products&delete=1&products_id=' . $row['products_id'] . '&item_no=' . $item_no . '&page=' . $page . '">Delete</a></li>
  </ul>
</div>');
}

?>
<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		Products <small><a href="?route=modules/shop/manage_products">View all
				Products</a></small>
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

		<div class="col-md-12">
			<div class="col-md-4 pull-right">
				<div id="msgDiv" style="display: none;"
					class="alert alert-success alert-dismissible col-md-6 col-md-offset-3">
					<button type="button" class="close" data-dismiss="alert"
						aria-hidden="true"></button>
					<h4><?php echo $alertMsg; ?></h4>
				</div>
			</div>
		</div>
		<div class="box-body">
			<div class="pull-left form-inline">
				<form method="GET" action="" id="frmFilter">
					<strong>Filter:&nbsp;</strong><select id="cID" name="cID"
						class="form-control">
						<option value="">-ALL-</option>
								<?php
        $cate = DB::query("SELECT c.`categories_id`,c.`parent_id`, c.`name` FROM categories c");
        foreach ($cate as $cat) {
            echo '<option value="' . $cat['categories_id'] . '"';
            if ($cID == $cat['categories_id']) {
                echo 'SELECTED';
            }
            echo '>';
            if ($cat['parent_id'] != '0') {
                echo '-';
            }
            echo $cat['name'] . '</option>';
        }
        ?>
							</select> <input type="hidden" name="route"
						value="modules/shop/manage_products">
				</form>
				<script>
							    
							    $(function(){
							       $("#cID").on("change", function(){
							          $("#frmFilter").submit(); 
							       }); 
							    });
							    
							</script>
							Total Records: <?php echo $total_records; ?> [Page: <?php echo $page; ?>]
						
			</div>
			<div class="col-md-5 pull-right">
				<form method="GET" class="form-inline" action="">
					<div class="input-group">
						<input type="hidden" value="modules/shop/manage_products"
							name="route"> <input type="text" name="item_no" id="item_no"
							value="<?php echo $item_no; ?>" placeholder="Seach Item.."
							class="form-control" /> <span class="input-group-btn">
							<button type="submit" id="searchItem" name="searchItem"
								class="btn btn-default" type="button">
								<i class="fa fa-search"></i>
							</button>
						</span>
					</div>
					<a class="btn btn-success" href="?route=modules/shop/add_product"><i
						class="fa fa-plus"></i> Add New</a>
				</form>
				<h4>Total Avg CostPrice: $<?php echo get_total_avg_cost(); ?></h4>
				<h4>Total Avg SalePrice: $<?php echo get_total_avg_sale_price(); ?></h4>
			</div>
							
				<?php  echo $tbl->display(); ?>
			
				<?php
    $page_url = "?route=modules/shop/manage_products&";
    if ($cID != '') {
        $page_url .= 'cID=' . $cID . '&';
    }
    if ($item_no != '') {
        $page_url .= 'item_no=' . $item_no . '&';
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
