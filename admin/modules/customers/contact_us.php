<?php
if (isset($_GET['del'])) {
    if (isset($_GET['id'])) {
        DB::delete('contact_us', 'contact_us_id=%s', (int) $_GET['id']);
        echo '<script type="text/javascript">
		<!--
alert("Deleted");
		window.location = "?route=modules/customers/contact_us"
		//-->
		</script>';
    }
}


?>

<div class="panel panel-info">
	<!-- Default panel contents -->
	<div class="panel-heading row">
		<h3>Contact us view</h3>
	</div>
	<div class="panel-body">
		<table class="table table-striped table-bordered">
			<thead>
				<tr>
					<th>Name</th>
					<th>Email</th>
					<th>Phone</th>
					<th>Message</th>
					<th>TimeStamp</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
<?php
$sql = "select * from contact_us order by created_on desc";
$get = DB::query($sql);
foreach ($get as $cat) {
    echo "<tr>";
    echo "<td>" . $cat['name'] . "</td>";
    echo "<td>" . $cat['email'] . "</td>";
    echo "<td>" . $cat['phone'] . "</td>";
    echo "<td>" . $cat['message'] .  "</td>";
    echo "<td>" . formate_date_days($cat['created_on']) . "</td>";
    echo '<td></a>&nbsp;<a onclick="return confirm(\'Are you sure to delete this?\');" alt="Del" href="?route=modules/customers/contact_us&del=yes&id=' . $cat['contact_us_id'] . '" title="Delete" class="btn btn-danger btn-sm edit" href="#"><i class="fa fa-trash"></i> Delete
      </a></td>';
    echo "</tr>";
}

?>
</tbody>
		</table>
	</div>
</div>
