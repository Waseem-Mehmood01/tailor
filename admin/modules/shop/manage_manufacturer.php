<?php
if(isset($_GET['active'])){
	
	if(isset($_GET['manufacturers_id'])){

	
		DB::update("manufacturers", array('active' => (int)trim($_GET['active'])),
		    
		    "manufacturers_id=%s", (int)trim($_GET['manufacturers_id']));
		
	
		echo '<script type="text/javascript">
		<!--
		window.location = "?route=modules/shop/manage_manufacturer"
		//-->
		</script>';

	}

}


?>
<div class="panel panel-info">
  <!-- Default panel contents -->
  <div class="panel-heading"><h3>Products Supplier<a href="?route=modules/shop/add_manufacturer" class=" pull-right btn btn-sm btn-primary"> <span class="glyphicon glyphicon-plus"></span> &nbsp;Add New Supplier</a> </h3> </div>
  <div class="panel-body">

<table class="table table-striped table-bordered data-table">
<thead>
	<tr>
	<th>Supplier Name</th>
	<th>UserName</th>
	<th>Description</th>
	<th>Contact</th>
	<th>Email</th>
	<th>Address</th>
	<th>Active</th>
	<th>Actions</th>
	</tr>
</thead>
<tbody>
<?php
$sql = "SELECT * FROM manufacturers";
$get = DB::query($sql);
foreach($get as $cat) { 
echo "<tr>";
echo "<td>".$cat['name']."</td>";
echo "<td>".$cat['user_name']."</td>";
echo "<td>".$cat['description']."</td>";
echo "<td>".$cat['contact']."</td>";
echo "<td>".$cat['email']."</td>";
echo "<td>".$cat['address']."</td>";
if($cat['active']==1){
    echo "<td>Yes</td>";
} else {
    echo "<td>No</td>";
}

echo "<td>";
if($cat['active']==1){
    echo "<a class='btn btn-warning btn-sm' onclick='return confirm(\" Are you sure to de-active this? \");' href ='?route=modules/shop/manage_manufacturer&manufacturers_id=".$cat['manufacturers_id']."&active=0'>De-Active&nbsp;<span class='glyphicon glyphicon-cross'></span></a>";
} else {
    echo "<a class='btn btn-success btn-sm' onclick='return confirm(\" Are you sure to activate this? \");' href ='?route=modules/shop/manage_manufacturer&manufacturers_id=".$cat['manufacturers_id']."&active=1'>Activate&nbsp;<span class='glyphicon glyphicon-tick'></span></a>";
}
echo "&nbsp;<a class='btn btn-primary btn-sm' href ='?route=modules/shop/edit_manufacturer&manufacturers_id=".$cat['manufacturers_id']."'>Edit&nbsp;<span class='glyphicon glyphicon-new-window'></span></a>";

echo "</td>";
echo "</tr>";		   
}
			  // echo $tbl->display();
?>
</tbody>
</table>
 </div>
</div>
