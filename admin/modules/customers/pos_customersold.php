
<div class="panel panel-info">
  <!-- Default panel contents -->
  <div class="panel-heading row"><h3>POS Customers</h3></div>
  <div class="panel-body">

<table class="table table-striped table-bordered">
<thead>
	<tr>
	<th>Customer</th>
	<th>Customer Type</th>
	<th>Email</th>
	<th>Phone</th>
	<th>Timestamp</th>
	</tr>
</thead>
<tbody>
<?php
$sql = "SELECT si.`created_on`, si.`customer`, si.`customer_type`, si.`customer_email`, si.`customer_phone` FROM sale_invoice si WHERE si.`customer_phone` != '' ORDER by si.`created_on` DESC LIMIT 0, 500";
$get = DB::query($sql);
foreach($get as $cat) { 
echo "<tr>";
echo "<td>".$cat['customer']."</td>";
echo "<td>".$cat['customer_type']."</td>";
echo "<td>".$cat['customer_email']."</td>";
echo "<td>".$cat['customer_phone']."</td>";
echo "<td>".$cat['created_on']."</td>";
echo "</tr>";		   
}
			  // echo $tbl->display();
?>
</tbody>
</table>
 </div>
</div>
