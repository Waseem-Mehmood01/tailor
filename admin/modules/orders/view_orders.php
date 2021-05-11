<?php
$alertMsg = "";
$where_clause = "";
$default_record_msg = 'Sale of ' . date('M-Y');

if (isset($_GET['is_paid'])) {
    $orders_id = isset($_GET['orders_id']) ? (int) trim($_GET['orders_id']) : '';
    if ($orders_id != '') {

        DB::update("orders", array(
            'is_paid' => 1
        ), 'orders_id=%s', $orders_id);
        echo '<script type="text/javascript">
		<!--
		alert("Updated succesfuly");
		window.location = "?route=modules/orders/view_orders"
		//-->
		</script>';
    }
}

if (isset($_GET['delete'])) {
    $orders_id = isset($_GET['orders_id']) ? (int) trim($_GET['orders_id']) : '';
    if ($orders_id != '') {
        $sql = "DELETE FROM `orders` WHERE `orders_id`='" . (int) $orders_id . "'";

        DB::query($sql);

        $sql = "DELETE FROM `orders_products` WHERE `orders_id`='" . (int) $orders_id . "'";

        DB::query($sql);

        echo '<script type="text/javascript">
		<!--
		alert("Deleted succesfuly");
		window.location = "?route=modules/orders/view_orders"
		//-->
		</script>';
    }
}

if (isset($_GET["page"])) {
    $page = (int) $_GET["page"];
} else {
    $page = 1;
}

$setLimit = 15;
$pageLimit = ($page * $setLimit) - $setLimit;

?>




<?php

if (isset($_POST['btnFilter'])) {
    @extract($_POST);
} else {
    @extract($_GET);
}

if (@$from_date != '') {
    @$from_date_time = @$from_date . ' ' . @$from_time;
    $where_clause .= "
		AND o.`created_on` >= '" . getDateTime(@$from_date_time, "mySQL") . "'";
    $default_record_msg = 'Sale of ' . @$from_date_time . ' to Now';
}

if (@$to_date != '') {
    @$to_date_time = @$to_date . ' ' . @$to_time;
    $where_clause .= "
		AND o.`created_on` <= '" . getDateTime(@$to_date_time, "mySQL") . "'";
    $default_record_msg = 'Sale of ' . @$from_date_time . ' to ' . @$to_date_time;
}

if (! empty($tender)) {

    $where_clause .= " AND o.`tender` ='" . $tender . "' ";
}

if (@$from_date == '' and @$to_date == '') {
    $where_clause .= "
 AND MONTH(o.`created_on`) = MONTH('" . getDateTime('0', "mySQL") . "')
AND YEAR(o.`created_on`) = YEAR('" . getDateTime('0', "mySQL") . "') ";
}

$sql = "SELECT  o.`created_on` AS order_date, o.*, c.* FROM orders o 
LEFT JOIN customers c
ON(o.`customers_id` = c.`customers_id`) WHERE 1=1";

if (isset($_GET['phoneid'])) {

    $ordertype = isset($_GET['phoneid']) ? $_GET['phoneid'] : '';
    if ($ordertype == 'app') {
        $sql .= " AND o.`phoneid` NOT IN ('web', 'pos') ";
    } else {
        $sql .= " AND o.`phoneid`='" . $ordertype . "' ";
    }
}

if (isset($_GET['customer_id'])) {
    $customers_id = (int) $_GET['customer_id'];
    $where_clause = '';
    $ordertype = '';
    $sql .= " AND o.`customers_id`= '" . $customers_id . "' ";
    $default_record_msg = 'Orders of, '.strtoupper(get_customer_name($customers_id));
}
if (isset($_POST['btnFilterBasic'])) {
    $sql .= " AND `" . $_POST['s_type'] . "` LIKE '%" . $_POST['keyword'] . "%' ";
    $where_clause = '';
}

$sql .= $where_clause . " ORDER BY o.`orders_id` DESC";
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
$tbl->addCell('ID', '', 'header');
$tbl->addCell('Order From', '', 'header');
$tbl->addCell('Order Type', '', 'header');
$tbl->addCell('Customer', '', 'header');
$tbl->addCell('Email', '', 'header');
$tbl->addCell('Contact', '', 'header');
$tbl->addCell('Payment Type', '', 'header');
$tbl->addCell('Order Amount', '', 'header');
$tbl->addCell('Order Status', '', 'header');
$tbl->addCell('Paid', '', 'header');
$tbl->addCell('TimeStamp', '', 'header');
$tbl->addCell('Action', '', 'header');
$tbl->addTSection('tbody');

$res = DB::query($sql);

foreach ($res as $row) {
    $tbl->addRow();
    $tbl->addCell($row['orders_id']);
    if ($row['phoneid'] == 'pos' or $row['phoneid'] == 'web') {
        $oFrom = strtoupper($row['phoneid']);
    } else {
        $oFrom = 'App';
    }
    $tbl->addCell($oFrom);
    $tbl->addCell(strtoupper($row['order_type']));
    $tbl->addCell($row['fname'] . ' ' . $row['lname']);
    $tbl->addCell($row['email']);
    $tbl->addCell($row['customer_phone']);
    $tbl->addCell($row['tender']);
    $tbl->addCell($row['order_total']);
    $oStatus = DB::queryFirstField("SELECT os.`status_name` FROM orders_status os WHERE os.`orders_status_id` = '" . $row['orders_status_id'] . "'");
    $tbl->addCell($oStatus);
    if ($row['is_paid'] == 1) {
        $paid = '<b class="text-success">Yes</b>';
    } else {
        $paid = '<b class="text-danger">No</b>&nbsp;<a href="?route=modules/orders/view_orders&is_paid=1&orders_id=' . $row['orders_id'] . '" class="btn btn-xs btn-success">Mark Paid</a>';
    }
    $tbl->addCell($paid);
    $tbl->addCell(date('h:i:s a | d-M-y', strtotime($row['order_date'])));

    $del_btn = '';

    if ($_SESSION['role_id'] == 1) {
        $del_btn = '<a onclick="return confirm(\'Are you sure want to delete this order permanent? Action will not be undo\');" href="?route=modules/orders/view_orders&delete=yes&orders_id=' . $row['orders_id'] . '" class="btn btn-sm btn-danger">Delete</a>';
    }

    $tbl->addCell($del_btn . '&nbsp;<a href="?route=modules/orders/edit_order&orders_id=' . $row['orders_id'] . '" class="btn btn-sm btn-primary">Edit</a>&nbsp;<a href="?route=modules/orders/detail_order&orders_id=' . $row['orders_id'] . '" class="btn btn-sm btn-default">Invoice</a>
');
}

?>
<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		Orders [ <?php echo strtoupper(@$ordertype); ?> ]<small>View all Orders</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="<?php echo SITE_ROOT; ?>"><i class="fa fa-dashboard"></i>
				Home</a></li>
		<li class="active">Detail Orders</li>
	</ol>
</section>
<!-- Main content -->
<section class="content">
	<!-- Default box -->
	<div class="box">
		<div class="box-header with-border">
			<a
				href="?route=modules/orders/view_orders&phoneid=<?php echo $ordertype; ?>"><h3
					class="box-title">Refresh</h3></a>
			<h3><?php echo $default_record_msg; ?></h3>
			<a class="btn btn-success d-inline" href="#" data-toggle="modal"
				data-target="#modalFilter"><i class="glyphicon glyphicon-filter"></i>&nbsp;Filter</a>
			<div class="col-md-4 pull-right">
				<form action="" method="POST" class="form-inline">
					<input type="text" value="<?php echo @$_POST['keyword']; ?>"
						name="keyword" class="form-control" placeholder="Search"> <select
						class="form-control" name="s_type">
						<option value="orders_id">Order ID</option>
						<option value="fname">Customer</option>
						<option value="email">Email</option>
						<option value="contact">Contact</option>
					</select> <input type="submit" class="btn btn-primary btn-sm"
						name="btnFilterBasic" value="Find">
				</form>
			</div>
		</div>
		<div class="col-md-12">
			<div class="col-md-4 pull-right">
				<div id="msgDiv" style="display: none;"
					class="alert alert-success alert-dismissible col-md-6 col-md-offset-3">
					<button type="button" class="close" data-dismiss="alert"
						aria-hidden="true">�</button>
					<h4><?php echo $alertMsg; ?></h4>
				</div>
			</div>
		</div>
		<div class="box-body">
			<div class="pull-left">
						Total Records: <?php echo $total_records; ?> [Page: <?php echo $page; ?>]				
					</div>

							
				<?php  echo $tbl->display(); ?>
			
				<?php
    $page_url = "?route=modules/orders/view_orders&phoneid=" . $ordertype . "&";
    echo displayPaginationBelow($setLimit, $page, $sql2, $page_url);
    ?>


            </div>
		<!-- /.box-body -->
		<div class="box-footer"></div>
	</div>
	<!-- /.box -->
</section>
<!-- Modal -->
<div class="modal fade" id="modalFilter" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Filter By</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" name="frmFilter" id="frmFilter"
					action="?route=modules/orders/view_orders&phoneid=<?php echo $ordertype; ?>"
					method="POST">
					<div class="form-group">
						<label class="control-label col-sm-3">From:</label>
						<div class="col-sm-3">
							<input type="text" class="form-control date-picker"
								value="<?php echo @$from_date; ?>" name="from_date"
								id="from_date" placeholder="dd-mm-yyyy" autocomplete="off">
						</div>
						<div class="col-sm-3">
							<input type="text" class="form-control timepicker"
								value="<?php if(isset($from_time)){ echo @$from_time; } else { echo '6:00 AM'; } ?>"
								name="from_time" id="from_time" placeholder="hh:mm"
								autocomplete="off">
						</div>
						<div class="col-sm-3"></div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-3">To:</label>
						<div class="col-sm-3">
							<input type="text" class="form-control date-picker"
								value="<?php echo @$to_date; ?>" name="to_date" id="to_date"
								placeholder="dd-mm-yyyy" autocomplete="off">
						</div>
						<div class="col-sm-3">
							<input type="text" class="form-control timepicker"
								value="<?php if(isset($to_time)){ echo @$to_time; } else { echo '11:59 PM'; } ?>"
								name="to_time" id="to_time" placeholder="hh:mm"
								autocomplete="off">
						</div>
						<div class="col-sm-3"></div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-3">Tender:</label>
						<div class="col-sm-6">
							<select class="form-control" name="tender">
								<option <?php if('' == @$tender) { echo 'SELECTED'; } ?>
									value="">-All-</option>
								<option <?php if('cash' == @$tender) { echo 'SELECTED'; } ?>
									value="cash">Cash</option>
								<option <?php if('card'== @$tender){ echo 'SELECTED'; } ?>
									value="card">Card</option>
							</select>
						</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-success" name="btnFilter"
							id="btnFilter">Apply</button>
						<button type="button" class="btn btn-primary" name="btnReset"
							id="btnReset">Clear Filter</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
					</div>
			
			</div>
			</form>
		</div>
	</div>
	<script type="text/javascript">

$(function(){ $("#btnReset").on("click",function(){ $(".form-control").val("");});});

</script>