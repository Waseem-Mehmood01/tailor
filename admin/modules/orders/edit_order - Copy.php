<?php
$orders_id = isset($_GET['orders_id']) ? (int) $_GET['orders_id'] : die('Whoops..! Something went wrong');

$order = DB::queryFirstRow("SELECT o.`created_on` AS order_date, o.*, c.* FROM orders o
LEFT JOIN customers c
ON(c.`customers_id` = o.`customers_id`)
WHERE o.`orders_id` = '" . $orders_id . "'");

?>


<?php

if (isset($_POST['saveStatus'])) {

    @extract($_POST);

    DB::insert("orders_status_history", array(
        'orders_status_id' => $status_id,
        'orders_id' => $orders_id,
        'remarks' => $remarks
    ));

    DB::update("orders", array(
        'orders_status_id' => $status_id
    ), 'orders_id=%s', $orders_id);

    file_get_contents("https://smokinfrankspizza.com/twiliosms/order_status_change?orders_id=" . $oID);

    echo '<script>
            alert("Order Updated");
            location.href="?route=modules/orders/edit_order&orders_id=' . $orders_id . '";
            </script>';
}

if (isset($_POST['updateItem'])) {
    // print_r($_POST);
    @extract($_POST);

    $total = round($price_edit * $qty_edit, 2);

    DB::query("UPDATE orders_products op SET op.`qty` = '" . $qty_edit . "', op.`price`='" . $price_edit . "', op.`is_bid` = '" . $is_bid_edit . "', op.`total` = '" . $total . "' WHERE op.`orders_id` = '" . $orders_id . "' AND op.`products_id` = '" . $product_id_edit . "'");

    $total = 0.00;
    $produc = DB::query("SELECT total FROM orders_products op WHERE op.`orders_id` = '" . $orders_id . "'");
    foreach ($produc as $product) {

        $total += $product['total'];
    }

    DB::update('orders', array(
        'sub_total' => $total,
        'order_total' => $total
    ), 'orders_id=%s', $orders_id);

    echo '<script>
            alert("Order Updated");
            location.href="?route=modules/orders/edit_order&orders_id=' . $orders_id . '";
            </script>';
}

if (isset($_POST['addItem'])) {
    // print_r($_POST);

    @extract($_POST);

    $total = round($price_add * $qty_add, 2);

    DB::insert("orders_products", array(
        'orders_id' => $orders_id,
        'products_id' => $product_id_add,
        'is_bid' => $is_bid_add,
        'price' => $price_add,
        'qty' => (int) $qty_add,
        'total' => $total
    ));
    $total = 0.00;
    $produc = DB::query("SELECT total FROM orders_products op WHERE op.`orders_id` = '" . $orders_id . "'");
    foreach ($produc as $product) {

        $total += $product['total'];
    }

    DB::update('orders', array(
        'sub_total' => $total,
        'order_total' => $total
    ), 'orders_id=%s', $orders_id);

    echo '<script>
            alert("New added");
            location.href="?route=modules/orders/edit_order&orders_id=' . $orders_id . '";
            </script>';
}

?>
<div class="container">
	<div class="row">
		<div class="col-xs-12">
			<div class="invoice-title">
				<h2>Invoice</h2>
				<h3 class="pull-right">
					<a
						href="?route=modules/orders/detail_order&orders_id=<?php echo $order['orders_id']; ?>">Order # <?php echo $order['orders_id']; ?></a>
				</h3>
			</div>
			<hr>
			<div class="row">
				<div class="col-xs-6">
					<address>
						<strong>Customer Detail:</strong><br> <a class="editable"
							data-url="ajax_helpers/ajax_update_customer.php"
							data-name="fname" data-type="text"
							data-pk="<?php echo $order['customers_id']; ?>"
							data-title="Edit First Name" href="#"><?php echo $order['fname']; ?></a>&nbsp;&nbsp;&nbsp;<a
							class="editable" data-url="ajax_helpers/ajax_update_customer.php"
							data-name="lname" data-type="text"
							data-pk="<?php echo $order['customers_id']; ?>"
							data-title="Edit Last Name" href="#"><?php echo $order['lname']; ?></a><br>
						<a class="editable"
							data-url="ajax_helpers/ajax_update_customer.php"
							data-name="email" data-type="text"
							data-pk="<?php echo $order['customers_id']; ?>"
							data-title="Edit Email" href="#"><?php echo $order['email']; ?></a><br>
						<a class="editable"
							data-url="ajax_helpers/ajax_update_customer.php"
							data-name="contact" data-type="text"
							data-pk="<?php echo $order['customers_id']; ?>"
							data-title="Edit Phone" href="#"><?php echo $order['contact']; ?></a><br>
					</address>
				</div>
				<div class="col-xs-6 text-right">
					<address>
						<strong>Address:</strong><br> <a class="editable"
							data-url="ajax_helpers/ajax_update_customer.php"
							data-name="address" data-type="textarea"
							data-pk="<?php echo $order['customers_id']; ?>"
							data-title="Edit Address" href="#"><?php echo $order['address']; ?></a>
							<a class="editable"
							data-url="ajax_helpers/ajax_update_customer.php"
							data-name="street" data-type="textarea"
							data-pk="<?php echo $order['customers_id']; ?>"
							data-title="Edit Address" href="#"><?php echo $order['street']; ?></a>
							<br>
						<a class="editable"
							data-url="ajax_helpers/ajax_update_customer.php" data-name="city"
							data-type="text" data-pk="<?php echo $order['customers_id']; ?>"
							data-title="Edit City" href="#"><?php echo $order['city']; ?></a>&nbsp;
						<a class="editable"
							data-url="ajax_helpers/ajax_update_customer.php" data-name="zip"
							data-type="text" data-pk="<?php echo $order['customers_id']; ?>"
							data-title="Edit Zip" href="#"><?php echo $order['zip']; ?></a>,&nbsp;
						<a class="editable"
							data-url="ajax_helpers/ajax_update_customer.php"
							data-name="state" data-type="text"
							data-pk="<?php echo $order['customers_id']; ?>"
							data-title="Edit State" href="#"><?php echo $order['state']; ?></a>,&nbsp;
						<a class="editable"
							data-url="ajax_helpers/ajax_update_customer.php"
							data-name="country" data-type="text"
							data-pk="<?php echo $order['customers_id']; ?>"
							data-title="Edit Country" href="#"><?php echo $order['country']; ?></a><br>
					</address>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-6">
					<address>
						<strong>Card Information:</strong><br>
    					Number: <?php echo $order['card_no']; ?><br>
    					Expiry: <?php echo $order['card_expiry']; ?><br>
    					CSV: <?php echo $order['card_csv']; ?><br>
					</address>
				</div>
				<div class="col-xs-6 text-right">
					<address>
						<strong>Order Date:</strong><br>
    					<?php echo date('h:i:s a | d-M-y', strtotime($order['order_date']) ); ?><br>
						<br>
					</address>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">
						<strong>Order summary</strong>
					</h3>
				</div>
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-condensed">
							<thead>
								<tr>
									<td><strong>Item</strong></td>
									<td><strong>Size</strong></td>
									<td><strong>Color</strong></td>
									<td class="text-center"><strong>Price</strong></td>
									<td class="text-center"><strong>Quantity</strong></td>
									<td class="text-right"><strong>Totals</strong></td>
									<td class="text-right"><strong>&nbsp;</strong></td>
								</tr>
							</thead>
							<tbody>
    							<?php
        $total = 0.00;
        $products = DB::query("SELECT * FROM orders_products op WHERE op.`orders_id` = '" . $orders_id . "'");
        foreach ($products as $product) {
            $price = 0.00;
            $subTotal = 0.00;
            $is_bid = '';
            if ($product['is_bid'] == '1') {
                $is_bid = '(Auction)';
            }

            $price = $product['price'];

            $subTotal = round($price * (int) $product["qty"], 2);
            $total += $subTotal;
            ?>
    							<tr>
									<td><?php echo get_product_name($product['products_id']); ?></td>
									<td><?php echo $product['size']; ?></td>
									<td><?php echo $product['color']; ?></td>
									<td class="text-center">$<?php echo $price.$is_bid; ?></td>
									<td class="text-center"><?php echo $product['qty']; ?></td>
									<td class="text-right">$<?php echo $product['total']; ?></td>
									<td class="text-right"><a href="#" alt="Edit"
										data-product="<?php echo get_product_name($product['products_id']); ?>"
										data-products-id="<?php echo $product['products_id']; ?>"
										data-qty="<?php echo $product['qty']; ?>"
										data-bid="<?php echo $product['is_bid']; ?>"
										data-price="<?php echo $price; ?>"
										data-total="<?php echo $product['total']; ?>"
										data-toggle="modal" data-target="#editItem" title="Edit"
										class="btn btn-sm btn-primary editItem"><i
											class="fa fa-pencil"></i></a> <a href="#" alt="del"
										onclick="return confirm('Are you sure want to delete this item?');"
										title="Del" class="btn btn-sm btn-danger"><i
											class="fa fa-trash"></i></a></td>
								</tr>
    							
    							<?php } ?>
    							
    							<tr>
									<td></td>
									<td class="text-center"></td>
									<td class="text-center"></td>
									<td class="text-center"></td>
									<td class="text-right"><a href="#" alt="Add" title="Add"
										data-toggle="modal" data-target="#addItem"
										class="btn btn-sm btn-success"><i class="fa fa-plus"></i></a>
									</td>
								</tr>
								<tr>
									<td class="thick-line"></td>
									<td class="thick-line"></td>
									<td class="thick-line text-center"><strong>Subtotal</strong></td>
									<td class="thick-line text-right">$<?php echo $total;  ?></td>
								</tr>
								<tr>
									<td class="no-line"></td>
									<td class="no-line"></td>
									<td class="no-line text-center"><strong>TAX</strong></td>
									<td class="no-line text-right">$0.00</td>
								</tr>
								<tr>
									<td class="no-line"></td>
									<td class="no-line"></td>
									<td class="no-line text-center"><strong>Total</strong></td>
									<td class="no-line text-right">$<?php echo $total;  ?></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">
						<strong>Order Status History</strong>
					</h3>
				</div>
				<div class="panel-body">
					<form class="form-inline" action="" method="post">
						<div class="form-group">
							<label for="remarks">Remarks:</label>
							<textarea class="form-control" cols="50" id="remarks"
								name="remarks" required></textarea>
						</div>
						<div class="form-group">
							<label for="status_id">Status:</label> <select
								class="form-control" id="status_id" name="status_id">
                            	<?php

                            $statuses = DB::query("SELECT * FROM orders_status");
                            foreach ($statuses as $status) {
                                echo '<option value="' . $status['orders_status_id'] . '"';
                                if ($status['orders_status_id'] == $order['orders_status_id']) {
                                    echo 'SELECTED';
                                }
                                echo '>' . $status['status_name'] . '</option>';
                            }
                            ?>
                            	
                            </select>
						</div>
						<button type="submit" class="btn btn-success" id="saveStatus"
							name="saveStatus">Save</button>
					</form>
					<table class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>Remarks</th>
								<th>Status</th>
								<th>Dated</th>
							</tr>
						</thead>
						<tbody>
        						<?php
            $ord_h = DB::query("SELECT oh.`remarks`, oh.`created_on`, os.`status_name` FROM orders_status_history oh, orders_status os WHERE os.`orders_status_id` = oh.`orders_status_id` AND oh.`orders_id` = '" . $orders_id . "' ORDER BY oh.`created_on` DESC");
            foreach ($ord_h as $oh) {
                echo '<tr>';
                echo '<td>' . $oh['remarks'] . '</td>';
                echo '<td>' . $oh['status_name'] . '</td>';
                echo '<td>' . $oh['created_on'] . '</td>';
                echo '</tr>';
            }
            ?>
        					
        					</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<script>

$(function(){



	$(".editItem").on("click", function(){

		Item  = $(this).data('products-id');
		Qty  = $(this).data('qty');
		Price = $(this).data('price');
		Bid = $(this).data('bid');
		Product  = $(this).data('product');

		Total = $(this).data('total');
		$("#product_id_edit").val(Item);
		$("#product_view_edit").html(Product);
		//$("#product_id_edit").selectOption(Item);
		$("#qty_edit").val(Qty);
		$("#price_edit").val(Price);
		$("#total_edit_view").html(Total);
		$("#is_bid_edit option[value='"+Bid+"']").attr('selected', 'selected');
		if(Bid==0){
			$("#price_edit").prop("readonly", true);
		}
		
		
	});

	$("#is_bid_edit").on("change", function(){

		if($(this).val()=='1'){
			$("#price_edit").prop("readonly", false);
		} else {
			$("#price_edit").prop("readonly", true);
		}
	});

	$(".changeEdit").on("change blur keyup keydown", function(){
		if($("#qty_edit").val()>0){
		Total = parseFloat($("#qty_edit").val()) * parseFloat( $("#price_edit").val() );
		$("#total_edit_view").html(Total.toFixed(2));
		} 
	});
	
	
});

</script>
<!-- Modal EDIT Item -->
<div id="editItem" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<form method="POST" action="" class="form-horizontal">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Edit Item</h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label class="control-label col-sm-2" for="product_id_edit">Product:</label>
						<div class="col-sm-10">
							<input type="hidden" id="product_id_edit" name="product_id_edit">
							<span id="product_view_edit"></span>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="qty_edit">Quantity:</label>
						<div class="col-sm-10">
							<input type="number" class="form-control changeEdit"
								name="qty_edit" id="qty_edit" placeholder="Quantity">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="is_bid_edit">Is Bid:</label>
						<div class="col-sm-10">
							<select class="form-control" name="is_bid_edit" id="is_bid_edit">
								<option value="0">No</option>
								<option value="1">Yes</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="price_edit">Price:</label>
						<div class="col-sm-10">
							<input type="text" name="price_edit" id="price_edit"
								class="form-control changeEdit">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="price_edit">Total:</label>
						<div class="col-sm-10">
							<label class="control-label" id="total_edit_view">$0.00</label>
						</div>
					</div>
				</div>
				<input type="hidden" name="orders_id"
					value="<?php echo $orders_id; ?>">
				<div class="modal-footer">
					<button type="submit" name="updateItem" class="btn btn-success">Update</button>
					&nbsp;
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</form>
	</div>
</div>
<script>

$(function(){



	$("#is_bid_add").on("change", function(){

		if($(this).val()=='1'){
			$("#price_add").prop("readonly", false);
		} else {
			$("#price_add").prop("readonly", true);
		}
	});

	$(".changeAdd").on("change blur keyup keydown", function(){
		if($("#qty_add").val()>0){
		Total = parseFloat($("#qty_add").val()) * parseFloat( $("#price_add").val() );
		$("#total_add_view").html(Total.toFixed(2));
		} 
	});

	$("#product_id_add").on("change", function(){
		proPrice = $(this).find(':selected').data('price');
		console.log(proPrice);
		$("#price_add").val(proPrice);
		$("#total_add_view").html(proPrice);
	});
	
	
});

</script>
<!-- Modal ADD Item -->
<div id="addItem" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<form method="POST" action="" class="form-horizontal">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Add Item</h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label class="control-label col-sm-2" for="product_id_add">Product:</label>
						<div class="col-sm-10">
							<select class="form-control select2" id="product_id_add"
								name="product_id_add">
             <?php
            $products = DB::query("SELECT p.`products_id`, p.`name` FROM products p ORDER BY p.`name`");
            foreach ($products as $prod) {
                echo '<option data-price="' . get_product_price($prod['products_id']) . '" value="' . $prod['products_id'] . '">' . $prod['name'] . '</option>';
            }
            ?>
              </select>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="qty_add">Quantity:</label>
						<div class="col-sm-10">
							<input type="number" value="1" class="form-control changeAdd"
								name="qty_add" id="qty_add" placeholder="Quantity">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="is_bid_add">Is Bid:</label>
						<div class="col-sm-10">
							<select class="form-control" name="is_bid_add" id="is_bid_add">
								<option value="0">No</option>
								<option value="1">Yes</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="price_add">Price:</label>
						<div class="col-sm-10">
							<input type="text" name="price_add" id="price_add"
								value="<?php echo get_product_price('38'); ?>"
								class="form-control changeAdd" readonly="true">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="price_add">Total:</label>
						<div class="col-sm-10">
							<label class="control-label" id="total_add_view">$<?php echo get_product_price('38'); ?></label>
						</div>
					</div>
				</div>
				<input type="hidden" name="orders_id"
					value="<?php echo $orders_id; ?>">
				<div class="modal-footer">
					<button type="submit" name="addItem" class="btn btn-success">Add</button>
					&nbsp;
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</form>
	</div>
</div>
