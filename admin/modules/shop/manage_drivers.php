<?php
if (isset($_GET['del'])) {

    if (isset($_GET['categories_id'])) {

        $id = $_GET['categories_id'];
        $sql = "DELETE FROM attribute_categories WHERE attribute_categories_id=" . (int) $id;

        $acc = DB::query($sql);

        echo '<script type="text/javascript">
		<!--
		window.location = "?route=modules/shop/manage_drivers"
		//-->
		</script>';
    }
}

if (isset($_POST['btnEdit'])) {
    $driver_id = isset($_POST['driver_id']) ? (int) $_POST['driver_id'] : '';
    @extract($_POST);
    DB::insertUpdate("drivers", array(
        'driver_id' => $driver_id,
        'd_fullname' => $d_fullname,
        'd_username' => $d_username,
        'd_password' => $d_password,
        'd_email' => $d_email,
        'd_contact' => $d_contact,
        'd_active' => $d_active
    ));
    echo '<script type="text/javascript">
		<!--
alert("Success");
		window.location = "?route=modules/shop/manage_drivers"
		//-->
		</script>';
}

?>
<div class="panel panel-info">
	<!-- Default panel contents -->
	<div class="panel-heading">
		<h3>
			Orders Delivery Drivers<a href="#" id="addNew" data-toggle="modal"
				data-target="#editModal" class="pull-right btn btn-primary"> <span
				class="glyphicon glyphicon-plus"></span> &nbsp;Add New Driver
			</a>
		</h3>
	</div>
	<div class="panel-body" style="font-size: 18px;">
		<table class="table data-table">
			<thead>
				<tr>
					<th>Name</th>
					<th>UserName</th>
					<th>Password</th>
					<th>Email</th>
					<th>Contact</th>
					<th>Status</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>	
    <?php
    $categ = DB::query("select * from drivers");
    foreach ($categ as $row) {
        echo ' 
    <tr>
       <td>' . $row['d_fullname'] . '</td>';
        echo '<td>' . $row['d_username'] . '</td>';
        echo '<td>' . $row['d_password'] . '</td>';
        echo '<td>' . $row['d_email'] . '</td>';
        echo '<td>' . $row['d_contact'] . '</td>';
        if ($row['d_active'] == 1) {
            echo '<td><span class="text-success">Active</span></td>';
        } else {
            echo '<td><span class="text-danger">In-Active</span></td>';
        }

        echo '<td><a alt="Edit" data-active="' . $row['d_active'] . '" data-contact="' . $row['d_contact'] . '" data-email="' . $row['d_email'] . '" data-password="' . $row['d_password'] . '" data-username="' . $row['d_username'] . '" data-fullname="' . $row['d_fullname'] . '" data-id="' . $row['driver_id'] . '" title="Edit" data-toggle="modal" data-target="#editModal" class="btn btn-info btn-sm edit" href="#"><i class="fa fa-pencil"></i> Edit
      </a><td>
    </tr>';
    }
    ?>
    </tbody>
		</table>
	</div>
</div>
<script>
	$(function(){
	$("#addNew").click(function(){
		$(".form-control").val('');
	});
		
		$(".edit").on('click',function(e){
			$("#driver_id").val($(this).data('id'));
			$("#d_fullname").val($(this).data('fullname'));
			$("#d_username").val($(this).data('username'));
			$("#d_password").val($(this).data('password'));
			$("#d_email").val($(this).data('email'));
			$("#d_contact").val($(this).data('contact'));
			$("#d_active"+$(this).data('active')).prop("checked", true);
			});
	});
</script>
<div id="editModal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Add/Edit Driver</h4>
			</div>
			<form action="" method="POST" class="form-horizontal">
				<div class="modal-body">
					<input type="hidden" class="form-control" name="driver_id"
						id="driver_id" value="">
					<div class="form-group">
						<label class="control-label col-sm-4">Name</label>
						<div class="col-sm-8">
							<input type="text" name="d_fullname" id="d_fullname"
								class="form-control" required>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-4">User Name</label>
						<div class="col-sm-8">
							<input type="text" name="d_username" id="d_username"
								class="form-control" required>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-4">Password</label>
						<div class="col-sm-8">
							<input type="text" name="d_password" id="d_password"
								class="form-control" required>
							<p class="text-muted">**driver can login into driver panel to
								view assigned orders</p>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-4">Contact</label>
						<div class="col-sm-8">
							<input type="text" name="d_contact" id="d_contact"
								class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-4">Email</label>
						<div class="col-sm-8">
							<input type="text" name="d_email" id="d_email"
								class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-4">Status</label>
						<div class="col-sm-8">
							<div class="radio">
								<label><input type="radio" id="d_active1" name="d_active"
									value="1" checked>Active</label>
							</div>
							<div class="radio">
								<label><input type="radio" id="d_active0" name="d_active"
									value="0">In-Active</label>
							</div>
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
