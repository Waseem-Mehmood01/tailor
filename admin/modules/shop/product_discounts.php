<?php


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
			Discount Offers<a href="#" id="addNew" data-toggle="modal"
				data-target="#editModal" class="pull-right btn btn-primary"> <span
				class="glyphicon glyphicon-plus"></span> &nbsp;Add Offer
			</a>
		</h3>
	</div>
	<div class="panel-body" style="font-size: 18px;">
		<table class="table data-table">
			<thead>
				<tr>
					<th>Product</th>
					<th>Old Price</th>
					<th>New Price</th>
					<th>Time From</th>
					<th>Time To</th>
					<th>Expired On</th>
					<th>Created On</th>
					<th>Status</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>	
    <?php
    $categ = DB::query("SELECT * FROM product_discounts ORDER BY product_discounts_id DESC");
    foreach ($categ as $row) {
        echo ' 
    <tr>
       <td>' . $row['products_id'] . '</td>';
        echo '<td>' . $row['old_price'] . '</td>';
        echo '<td>' . $row['new_price'] . '</td>';
        echo '<td>' . $row['time_from'] . '</td>';
        echo '<td>' . $row['time_to'] . '</td>';
        echo '<td>' . $row['expired_on'] . '</td>';
        echo '<td>' . $row['created_on'] . '</td>';
        if ($row['active'] == 1) {
            echo '<td><span class="text-success">Active</span></td>';
        } else {
            echo '<td><span class="text-danger">In-Active</span></td>';
        }

        echo '<td><a alt="Edit" data-expired-on="' . $row['expired_on'] . '" data-active="' . $row['active'] . '" data-products-id="' . $row['products_id'] . '" data-time-from="' . $row['time_from'] . '" data-time-to="' . $row['time_to'] . '" data-old-price="' . $row['old_price'] . '" data-new-price="' . $row['new_price'] . '" data-id="' . $row['product_discounts_id'] . '" title="Edit" data-toggle="modal" data-target="#editModal" class="btn btn-info btn-sm edit" href="#"><i class="fa fa-pencil"></i> Edit
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
				<h4 class="modal-title">Add/Edit Discount</h4>
			</div>
			<form action="" method="POST" class="form-horizontal">
				<div class="modal-body">
					<input type="hidden" class="form-control" name="driver_id"
						id="driver_id" value="">
					<div class="form-group">
						<label class="control-label col-sm-4">Product</label>
						<div class="col-sm-8">
							<select name="products_id" id="products_id"
								class="form-contro select2" style="width: 100%">
								<?php 
								$prod = DB::query("SELECT `products_id`, `name` FROM products ORDER BY `name`");
								foreach ($prod as $pro){
								    echo '<option value="'.$pro['products_id'].'">'.$pro['name'].'</option>';
								}
								?>
								</select>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-4">Old Price</label>
						<div class="col-sm-8">
							<input type="tel" name="old_price" id="old_price"
								class="form-control" required>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-4">New Price</label>
						<div class="col-sm-8">
							<input type="tel" name="new_price" id="new_price"
								class="form-control" required>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-4">Happy Hours</label>
						<div class="col-sm-4">
							<input type="text" name="time_from" id="time_from"
								class="form-control" placeholder="From">
						</div>
						<div class="col-sm-4">
							<input type="text" name="time_to" id="time_to"
								class="form-control" placeholder="To">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-4">Expired On</label>
						<div class="col-sm-8">
							<input type="text" name="expired_on" id="expired_on"
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
