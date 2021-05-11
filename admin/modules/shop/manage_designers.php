<?php

if (isset($_POST['btnEdit'])) {
    $user_id = isset($_POST['user_id']) ? (int) $_POST['user_id'] : '';
    @extract($_POST);
    DB::insertUpdate("team_users", array(
        'user_id' => $user_id,
        'full_name' => $full_name,
        'user_name' => $user_name,
        'password' => $password,
        'user_email' => $email,
        'contact' => $contact,
        'active' => $active
    ));
    echo '<script type="text/javascript">
		<!--
alert("Success");
		window.location = "?route=modules/shop/manage_designers"
		//-->
		</script>';
}

?>
<div class="panel panel-info">
	<!-- Default panel contents -->
	<div class="panel-heading">
		<h3>
			Designers<a href="#" id="addNew" data-toggle="modal"
				data-target="#editModal" class="pull-right btn btn-primary"> <span
				class="glyphicon glyphicon-plus"></span> &nbsp;Add New Designer
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
    $categ = DB::query("SELECT * FROM team_users");
    foreach ($categ as $row) {
        echo ' 
    <tr>
       <td>' . $row['full_name'] . '</td>';
        echo '<td>' . $row['user_name'] . '</td>';
        echo '<td>' . $row['password'] . '</td>';
        echo '<td>' . $row['user_email'] . '</td>';
        echo '<td>' . $row['contact'] . '</td>';
        if ($row['active'] == 1) {
            echo '<td><span class="text-success">Active</span></td>';
        } else {
            echo '<td><span class="text-danger">In-Active</span></td>';
        }

        echo '<td><a alt="Edit" data-active="' . $row['active'] . '" data-email="' . $row['user_email'] . '" data-password="' . $row['password'] . '" data-username="' . $row['user_name'] . '" data-fullname="' . $row['full_name'] . '" data-id="' . $row['user_id'] . '" title="Edit" data-toggle="modal" data-target="#editModal" class="btn btn-info btn-sm edit" href="#"><i class="fa fa-pencil"></i> Edit
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
			$("#user_id").val($(this).data('id'));
			$("#full_name").val($(this).data('fullname'));
			$("#user_name").val($(this).data('username'));
			$("#password").val($(this).data('password'));
			$("#email").val($(this).data('email'));
			$("#contact").val($(this).data('contact'));
			$("#active"+$(this).data('active')).prop("checked", true);
			});
	});
</script>
<div id="editModal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Add/Edit Designer</h4>
			</div>
			<form action="" method="POST" class="form-horizontal">
				<div class="modal-body">
					<input type="hidden" class="form-control" name="user_id"
						id="user_id" value="">
					<div class="form-group">
						<label class="control-label col-sm-4">Full Name</label>
						<div class="col-sm-8">
							<input type="text" name="full_name" id="full_name"
								class="form-control" required>
						</div>
					</div>
					
					<div class="form-group">
						<label class="control-label col-sm-4">User Name</label>
						<div class="col-sm-8">
							<input type="text" name="user_name" id="user_name"
								class="form-control" required>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-4">Password</label>
						<div class="col-sm-8">
							<input type="text" name="password" id="password"
								class="form-control" required>
							<p class="text-muted">**designers can login into designer panel to
								view assigned designs</p>
						</div>
					</div>
					
					<div class="form-group">
						<label class="control-label col-sm-4">Email</label>
						<div class="col-sm-8">
							<input type="text" name="email" id="email"
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
						<label class="control-label col-sm-4">Status</label>
						<div class="col-sm-8">
							<div class="radio">
								<label><input type="radio" id="active1" name="active"
									value="1" checked>Active</label>
							</div>
							<div class="radio">
								<label><input type="radio" id="active0" name="active"
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
