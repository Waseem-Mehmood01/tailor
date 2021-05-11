<div class="panel panel-info">
	<!-- Default panel contents -->
	<div class="panel-heading row">
		<h3>Call Log 239-888-8881</h3>
	</div>
	<div class="panel-body">
		<table class="table table-striped table-bordered data-table">
			<thead>
				<tr>
					<th>From</th>
					<th>Name</th>
					<th>Address</th>
					<th>City</th>
					<th>State</th>
					<th>Postal Code</th>
					<th>Time Stamp</th>
					<th>Prv. Orders</th>
				</tr>
			</thead>
			<tbody>
<?php
$sql = "SELECT * FROM calls_log ORDER BY `timestamp` DESC";
$get = DB::query($sql);
foreach ($get as $cat) {
    $prev_ord = DB::queryFirstField("SELECT COUNT(o.`orders_id`) FROM customers c, orders o  WHERE
o.`customers_id` = c.`customers_id`
AND c.`contact`='" . substr($cat['from'], 2, 12) . "'");
    if ($cat['name'] == '') {
        $customer = DB::queryFirstRow("SELECT * FROM customers c WHERE c.`contact`='" . substr($cat['from'], 2, 12) . "'");
        $name = $customer['fname'] . ' ' . $customer['lname'];
        $address = $customer['address'] . ' ' . $customer['street'];
        $state = $customer['state'];
        $zip = $customer['zip'];
        $city = $customer['city'];
    } else {
        $name = $cat['name'];
        $address = $cat['address'];
        $state = $cat['state'];
        $zip = $cat['postal_code'];
        $city = $cat['city'];
    }
    echo "<tr>";
    echo "<td>" . $cat['from'] . "</td>";
    echo "<td>" . $name . "</td>";
    echo "<td>" . $address . "</td>";
    echo "<td>" . $city . "</td>";
    echo "<td>" . $state . ", " . $cat['country'] . "</td>";
    echo "<td>" . $zip . "</td>";
    // echo "<td>" . $cat['lat'] . ", " . $cat['long'] . "</td>";
    // echo "<td>" . $cat['carrier'] . "</td>";
    echo "<td>" . date('h:i:s A', strtotime($cat['timestamp'])) . '<br/>' . date('d-M-Y', strtotime($cat['timestamp'])) . "</td>";
    if ($prev_ord > 0) {
        echo "<td><h4 class='text-success'>" . $prev_ord . "</h4></td>";
    } else {
        echo "<td><h4 class='text-danger'>" . $prev_ord . "</h4></td>";
    }
    echo "</tr>";
}
// echo $tbl->display();
?>
</tbody>
		</table>
	</div>
</div>

