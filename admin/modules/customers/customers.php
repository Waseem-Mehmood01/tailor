<?php
if (isset($_GET['del'])) {
    if (isset($_GET['customers_id'])) {
        DB::update('quote_customers', array(
            'move_trash' => 1
        ), 'quote_customers_id=%s', (int) $_GET['customers_id']);
        echo '<script type="text/javascript">
		<!--
alert("Moved Trash");
		window.location = "?route=modules/customers/customers"
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

$keyword = isset($_POST['keyword']) ? trim($_POST['keyword']) : '';
$id = isset($_GET['id']) ? (int) trim($_GET['id']) : '';

if (isset($_POST['btnEdit'])) {
    $quote_customers_id = isset($_POST['quote_customers_id']) ? (int) $_POST['quote_customers_id'] : '';
    @extract($_POST);
    DB::insertUpdate("quote_customers", array(
        'quote_customers_id' => $quote_customers_id,
        'fname' => $fname,
        'lname' => $lname,
        'company' => $company,
        'address' => $address,
        'city' => $city,
        'country' => $country,
        'zip' => $zip,
        'email' => $email,
        'password' => $password,
        'contact' => $contact
    ));
    echo '<script type="text/javascript">
		<!--
alert("Success");
		window.location = "?route=modules/customers/customers"
		//-->
		</script>';
}

if ($id == '') {
    $sql = "SELECT DISTINCT(qc.`email`), qc.* FROM quote_customers qc WHERE qc.`move_trash`= '0' ";

    if ($keyword != '') {
        $sql .= "  AND (qc.fname LIKE '%" . $keyword . "%' OR  qc.lname LIKE '%" . $keyword . "%' OR  qc.email LIKE '" . $keyword . "' OR  qc.contact = '" . $keyword . "') ";
    }

    
    
} else {
    $sql = "SELECT * FROM quote_customers WHERE quote_customers_id = '" . $id . "'";
}



$sql2 = $sql;

DB::query($sql2);
$total_records = DB::count();
// echo $total_records;
$sql .= '   ORDER BY quote_customers_id DESC LIMIT ' . $pageLimit . ', ' . $setLimit;
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
		<a href="?route=modules/customers/customers"><h3>Our Valuable
				Customers</h3></a>

		<div class="col-md-3 pull-right">
			<div class="row">
				<form action="" method="POST" class="form-inline">
					<input type="text" value="<?php echo $keyword; ?>" name="keyword"
						class="form-control" placeholder="Search"> <input type="submit"
						class="btn btn-primary btn-sm" name="btnFilter" value="Find">
				</form>
			</div>
		</div>
	</div>
	<div class="panel-body">
		<div class="col-md-2">Total Records: <?php echo $total_records; ?></div>
		<table class="table table-striped table-bordered">
			<thead>
				<tr>
					<th>Customer</th>
					<th>Email</th>
					<th>Password</th>
					<th>Phone</th>
					<th>Company</th>
					<th>Address</th>
					<th>Registered On</th>
					<th>Last Login</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
<?php

foreach ($get as $cat) {
    echo "<tr>";
    echo "<td>" . $cat['fname'] . ' ' . $cat['lname'] . "</td>";
    echo "<td>" . $cat['email'] . "</td>";
    echo "<td>" . $cat['password'] . "</td>";
    echo "<td>" . $cat['contact'] . "</td>";
    echo "<td>" . $cat['company'] . "</td>";
    echo "<td>" . $cat['address'] . ', ' . $cat['city'] . ' ' . $cat['zip'] . ', ' . $cat['country'] . "</td>";
    echo "<td>" . formate_date_days(getLeadDateTime($cat['quote_card_info_id'])) . "</td>";
    if ($cat['last_login'] != '') {
        echo "<td>" . formate_date_days($cat['last_login']) . "</td>";
    } else {
        echo '<td>N/A</td>';
    }
    echo '<td><a alt="Edit" data-country="' . $cat['country'] . '" data-zip="' . $cat['zip'] . '" data-city="' . $cat['city'] . '" data-contact="' . $cat['contact'] . '" data-address="' . $cat['address'] . '" data-company="' . $cat['company'] . '" data-lname="' . $cat['lname'] . '" data-fname="' . $cat['fname'] . '" data-email="' . $cat['email'] . '" data-password="' . $cat['password'] . '"  data-id="' . $cat['quote_customers_id'] . '" title="Edit" data-toggle="modal" data-target="#editModal" class="btn btn-default btn-sm edit" href="#"><i class="fa fa-pencil"></i> Edit
      </a>
&nbsp;<a  href="?route=modules/quote/view_quotes&customers_id=' . $cat['quote_customers_id'] . '" class="btn btn-info btn-sm edit" ><i class="fa fa-shopping-cart"></i> Leads
      </a>
&nbsp;<a onclick="return confirm(\'Are you sure to delete this customer?\');" alt="Del" href="?route=modules/customers/customers&del=1&customers_id=' . $cat['quote_customers_id'] . '" title="Delete" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i>&nbsp;
      </a>

</td>';
    echo "</tr>";
}

?>
</tbody>
		</table>
		<?php
$page_url = "?route=modules/customers/customers&keyword=" . $keyword . "&";
echo displayPaginationBelow($setLimit, $page, $sql2, $page_url);
?>
	</div>
</div>
<script>
	$(function(){
	$("#addNew").click(function(){
		$(".form-control").val('');
	});
		
		$(".edit").on('click',function(e){
			$("#quote_customers_id").val($(this).data('id'));
			$("#fname").val($(this).data('fname'));
			$("#lname").val($(this).data('lname'));
			$("#password").val($(this).data('password'));
			$("#email").val($(this).data('email'));
			$("#contact").val($(this).data('contact'));
			$("#address").val($(this).data('address'));
			$("#company").val($(this).data('company'));
			$("#city").val($(this).data('city'));
			$("#country").val($(this).data('country'));
			$("#zip").val($(this).data('zip'));
			});
	});
</script>
<div id="editModal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Add/Edit Customer</h4>
			</div>
			<form action="" method="POST" class="form-horizontal">
				<div class="modal-body">
					<input type="hidden" class="form-control" name="quote_customers_id"
						id="quote_customers_id" value="">
					<div class="form-group">
						<label class="control-label col-sm-4">Name</label>
						<div class="col-sm-4">
							<input type="text" name="fname" placeholder="First" id="fname"
								class="form-control" required>
						</div>
						<div class="col-sm-4">
							<input type="text" placeholder="Last" name="lname" id="lname"
								class="form-control" required>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-4">Email</label>
						<div class="col-sm-8">
							<input type="text" name="email" id="email" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-4">Password</label>
						<div class="col-sm-8">
							<input type="text" name="password" id="password"
								class="form-control" required>
							<p class="text-muted">**customers can login into customer panel
								to view designs</p>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-4">Company</label>
						<div class="col-sm-8">
							<input type="text" name="company" id="company"
								class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-4">Contact</label>
						<div class="col-sm-8">
							<input type="text" name="contact" id="contact"
								class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-4">Address</label>
						<div class="col-sm-8">
							<textarea name="address" id="address" class="form-control"></textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-4">&nbsp;</label>
						<div class="col-sm-3">
							<input type="text" name="city" id="city" placeholder="City"
								class="form-control">
						</div>
						<div class="col-sm-3">
							<input type="text" name="country" id="country"
								placeholder="Country" class="form-control">
						</div>
						<div class="col-sm-2">
							<input type="text" name="zip" id="zip" placeholder="Zip"
								class="form-control">
						</div>
					</div>
					<div class="modal-footer">
						<button type="submit" name="btnEdit" id="btnEdit"
							class="btn btn-success">Save</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
