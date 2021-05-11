<?php
if (isset($_GET['del'])) {

    if (isset($_GET['id'])) {

        $id = (int) $_GET['id'];

        DB::delete('discounts', 'discounts_id=%s', $id);

        echo '<script type="text/javascript">
		<!--
		window.location = "?route=modules/shop/discounts"
		//-->
		</script>';
    }
}

if (isset($_POST['btnEdit'])) {

    // $discounts_id = isset($_POST['discounts_id']) ? (int) $_POST['discounts_id'] : '';

    @extract($_POST);
    DB::insert("discounts", array(
        'products_id' => $products_id,
        'was_price' => $was_price,
        'new_price' => $new_price,
        'size' => $products_size,
        'min_order_qty' => $min_order_qty
    ));
    echo '<script type="text/javascript">
		<!--
alert("Success");
		window.location = "?route=modules/shop/discounts"
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
				class="glyphicon glyphicon-plus"></span> &nbsp;Add New Discount
			</a>
		</h3>
	</div>
	<div class="panel-body" style="font-size: 18px;">
		<table class="table data-table">
			<thead>
				<tr>
					<th>Product</th>
					<th>Size</th>
					<th>Actual Price</th>
					<th>Discount Price</th>
					<th>Minimum Order Qty</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>	
    <?php
    $categ = DB::query("SELECT * FROM discounts");
    foreach ($categ as $row) {
        echo ' 
    <tr>
       <td><a target="_BLANK" href="?route=modules/shop/view_product&products_id=' . $row['products_id'] . '">' . get_product_name($row['products_id']) . '</a></td>';
        echo '<td>' . $row['size'] . '</td>';
        echo '<td><strike>' . $row['was_price'] . '</strike></td>';
        echo '<td>' . $row['new_price'] . '</td>';
        echo '<td>' . $row['min_order_qty'] . '</td>';

        echo '<td><a alt="Del"  class="btn btn-danger btn-sm" onclick="confirm(\'Are you sure?\')" href="?route=modules/shop/discounts&del=yes&id=' . $row['discounts_id'] . '"><i class="fa fa-trash"></i> Delete</a><td>
    </tr>';
    }
    ?>
    </tbody>
		</table>
	</div>
</div>

<script>
$(function(){
$("#products_id").change(function(e){
	$.ajax({
		method: 'GET',
		url:  'ajax_product_sizes.php',
		data: 'p_id='+$(this).val(),
		success: function(f){
				$("#products_size").html(f);
			}
	});	
});

$(document).on('click change', '.productssize', function(){

	$("#was_price").val($(this).data('sprice'));
});

});
</script>

<div id="editModal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Add Discount</h4>
			</div>
			<form action="" method="POST" class="form-horizontal">
				<div class="modal-body">
					<div class="form-group">
						<label class="control-label col-sm-4">Product</label>
						<div class="col-sm-8">
							<select name="products_id" id="products_id"
								class="form-control select2" style="width: 100%">
								<option value="">-SELECT-</option>
							<?php
    $products = DB::query("select products_id, name from products order by name");
    foreach ($products as $pro) {
        echo '<option value="' . $pro['products_id'] . '">' . $pro['name'] . '</option>';
    }
    ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-4">Size</label>
						<div class="col-sm-8">
							<label class="radio-inline"> <input type="radio" data-sprice=""
								value="" name="products_size" class="productssize"> All sizes
							</label>
							<div id="products_size"></div>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-4">Actual Price</label>
						<div class="col-sm-8">
							<input type="text" name="was_price" id="was_price"
								class="form-control">

						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-4">Discount Price</label>
						<div class="col-sm-8">
							<input type="text" name="new_price" id="new_price"
								class="form-control" required>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-sm-4">Min Order Qty</label>
						<div class="col-sm-8">
							<input type="text" name="min_order_qty" id="min_order_qty"
								class="form-control" value="1" required>
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
