<?php
if (isset($_GET['newsletter'])) {
    $ne = (int) $_GET['newsletter'];
    $id = (int) $_GET['customers_id'];
    DB::update("customers", array(
        'newsletter' => $ne
    ), 'customers_id=%s', $id);
    echo '<script>location.href="?route=modules/customers/pos_customers";</script>';
}

if (isset($_POST['btnRatingSubmit'])) {
    $data = array_keys($_POST);
    $btncustomer = $data[1];
    $customer = explode('_', $btncustomer);
    $customers_id = $customer[1];
    $rati = $_POST['rating_' . $customers_id];
    DB::update('customers', array(
        'rating' => $rati
    ), 'customers_id=%s', $customers_id);
    echo '<script>location.href="?route=modules/customers/pos_customers";</script>';
}

if (isset($_GET["page"])) {
    $page = (int) $_GET["page"];
} else {
    $page = 1;
}

if (isset($_POST['keyword'])) {
    $page = 1;
}

$setLimit = 15;
$pageLimit = ($page * $setLimit) - $setLimit;

$sql = "SELECT * FROM customers WHERE 1=1";

if (isset($_POST['keyword'])) {
    $sql .= " AND fname like '%" . $_POST['keyword'] . "%' OR lname like '%" . $_POST['keyword'] . "%' OR  contact = '" . $_POST['keyword'] . "'";
}
$sql .= " ORDER BY created_on DESC";

$sql2 = $sql;
DB::query($sql2);
$total_records = DB::count();
// echo $total_records;
$sql .= ' LIMIT ' . $pageLimit . ', ' . $setLimit;

$get = DB::query($sql);

?>
<style>
.checked {
	color: orange;
}

.rating {
	direction: rtl;
	float: left;
}

.rating>span:hover:before, .rating>span:hover ~ span:before {
	color: orange;
}
</style>
<div class="panel panel-info">
	<!-- Default panel contents -->
	<div class="panel-heading row">
		<a href="?route=modules/customers/pos_customers"><h3>Our Valuable
				Customers</h3></a>
		<h5>Total Records: <?php echo $total_records; ?> [Page: <?php echo $page; ?>]</h5>
	</div>
	<div class="row">
		<div class="col-md-2">
			<a href="export_customers.php?export_my_customers_4432=dlj90alj=$@="
				target="_blank" class="btn btn-sm btn-default">Export</a>
		</div>
		<div class="col-md-4 pull-right">
			<form method="POST" action="" id="frmFilter">
				<div class="input-group">
					<input type="text" name="keyword"
						value="<?php echo @$_POST['keyword']; ?>" class="form-control"
						placeholder="Search">
					<div class="input-group-btn">
						<button class="btn btn-default" type="submit">
							<i class="glyphicon glyphicon-search"></i>
						</button>
					</div>
				</div>
				<input type="hidden" name="route"
					value="modules/customers/pos_customers">
			</form>
		</div>
	</div>
	<div class="panel-body">
		<table class="table table-striped table-bordered">
			<thead>
				<tr>
					<th>Customer</th>
					<th>Email</th>
					<th>Phone</th>
					<th>Address</th>
					<th>Rating</th>
					<th>Newsletter</th>
					<th>Prv. Orders</th>
				</tr>
			</thead>
			<tbody>
<?php

foreach ($get as $cat) {
    echo "<tr>";
    echo '<td><a href="#" class="editable" data-url="ajax_helpers/ajax_edit_customer.php" data-name="fname" data-type="text" data-pk="' . $cat['customers_id'] . '" data-title="Edit First Name">' . $cat['fname'] . '</a> <a href="#" class="editable" data-url="ajax_helpers/ajax_edit_customer.php" data-name="lname" data-type="text" data-pk="' . $cat['customers_id'] . '" data-title="Edit Last Name">' . $cat['lname'] . "</a></td>";
    echo '<td><a href="#" class="editable" data-url="ajax_helpers/ajax_edit_customer.php" data-name="email" data-type="text" data-pk="' . $cat['customers_id'] . '" data-title="Edit Email">' . $cat['email'] . " </a></td>";
    echo '<td><a href="#" class="editable" data-url="ajax_helpers/ajax_edit_customer.php" data-name="contact" data-type="tel" data-pk="' . $cat['customers_id'] . '" data-title="Edit Contact">' . $cat['contact'] . " </a></td>";
    echo '<td><a href="#" class="editable" data-url="ajax_helpers/ajax_edit_customer.php" data-name="address" data-type="textarea" data-pk="' . $cat['customers_id'] . '" data-title="Edit Address">' . $cat['address'] . ' </a>, ';
    echo '<a href="#" class="editable" data-url="ajax_helpers/ajax_edit_customer.php" data-name="city" data-type="text" data-pk="' . $cat['customers_id'] . '" data-title="Edit City">' . $cat['city'] . ' </a> ';
    echo '<a href="#" class="editable" data-url="ajax_helpers/ajax_edit_customer.php" data-name="zip" data-type="text" data-pk="' . $cat['customers_id'] . '" data-title="Edit Zip">' . $cat['zip'] . ' </a>, ';
    echo '<a href="#" class="editable" data-url="ajax_helpers/ajax_edit_customer.php" data-name="state" data-type="text" data-pk="' . $cat['customers_id'] . '" data-title="Edit State">' . $cat['state'] . " </a></td>";

    $rating = '';

    for ($i = 5; $i >= 1; $i --) {
        $rating .= '<span data-val="' . $i . '" data-id="' . $cat['customers_id'] . '" class="fa fa-star setrating';
        if ($cat['rating'] >= $i) {
            $rating .= ' checked';
        }
        $rating .= '"></span>';
    }

    echo "<td>
<form method='POST' action='' id='frmRating" . $cat['customers_id'] . "'><input type='hidden' name='btnRatingSubmit'><input type='hidden' class='changerating' id='rating_" . $cat['customers_id'] . "' name='rating_" . $cat['customers_id'] . "' value='" . $cat['rating'] . "'></form>
<div class='rating'>" . $rating . "</div></td>";
    if ($cat['newsletter'] == 1) {
        echo "<td><a href='?route=modules/customers/pos_customers&newsletter=0&customers_id=" . $cat['customers_id'] . "' onclick=\"return confirm('Are you sure want to change newsletter?');\"><span class='text-success'>Yes</span></a></td>";
    } else {
        echo "<td><a href='?route=modules/customers/pos_customers&newsletter=1&customers_id=" . $cat['customers_id'] . "' onclick=\"return confirm('Are you sure want to change newsletter?');\"><span class='text-danger'>No</span></a></td>";
    }

    echo "<td><a target='_BLANK' href='?route=modules/orders/view_orders&customer_id=" . $cat['customers_id'] . "'><h4 class='text-success'>" . get_prev_orders($cat['customers_id']) . "</a></h4></td>";
    echo "</tr>";
}

?>
</tbody>
		</table>
		<?php
$page_url = "?route=modules/customers/pos_customers&";
echo displayPaginationBelow($setLimit, $page, $sql2, $page_url);
?>
	</div>
</div>
<script>
$(function(){

	$(".setrating").on('click',function(){
		var VaL = $(this).data('val');
		var ID = $(this).data('id'); 
		$("#rating_"+ID).val(VaL);
		if (confirm("Are you sure to change rating")) $("#frmRating"+ID).submit();
		
		});
});
</script>