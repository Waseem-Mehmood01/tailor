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
	$item = 1;

	// die(1);
	// if(isset($_POST['product_id_edit'])){

	// }
	// for ($i = 0; $i < $item; $i++) {
	// 	echo "sa";
        // DB::insert("orders_products", array(
        //     'orders_id' => $orders_id,
        //     'products_id' => $_POST['item'][$i],
        //     'name' => get_product_name($_POST['item'][$i]),
        //     'description' => '',
        //     'products_price_id' => '0',
        //     'is_bid' => '0',
        //     'size' => $_POST['size'][$i],
        //     'price' => $_POST['item_price'][$i],
        //     'qty' => $_POST['qty'][$i],
        //     'total' => $_POST['total_price'][$i],
        //     'options' => $_POST['options'][$i],
        //     'product_type' => '1'
        // ));
// print_r($_POST);die(1);
        if (isset($_POST['extproid_' . $_POST['product_id_edit']])) {
			$sql = "DELETE FROM orders_products_extras WHERE orders_id =".$orders_id." AND products_id=".$_POST['product_id_edit'];
					DB::query($sql);
            for ($k = 0; $k < count($_POST['extproid_' . $_POST['product_id_edit']]); $k ++) {
				// if(!empty($_POST['sidesid_' . $_POST['product_id_edit']][$k])){
					DB::insert("orders_products_extras", array(
                    'orders_id' => $orders_id,
                    'products_id' => $_POST['extproid_' . $_POST['product_id_edit']][$k],
                    'extra_attributes_id' => $_POST['extid_' . $_POST['product_id_edit']][$k],
                    'name' => $_POST['extname_' . $_POST['product_id_edit']][$k],
                    'cost' => $_POST['extcost_' . $_POST['product_id_edit']][$k],
                    'count' => '1'
                ),'orders_id=%s', $orders_id);
				// }else{

				// }
            }
		}
		if (isset($_POST['sidesproid_' . $_POST['product_id_edit']])) {	
				$sql = 'DELETE FROM `attributes_sides` WHERE orders_id ='.$orders_id.' AND toppings = "Extras" AND products_id = '.$_POST['product_id_edit'];
				// echo $sql;
				DB::query($sql);
				for ($k = 0; $k < count($_POST['sidesproid_' . $_POST['product_id_edit']]); $k ++) {
					// if(!empty($_POST['sidesid_' . $_POST['product_id_edit']][$k])){
						// $sideSql = "UPDATE attributes_sides s SET
						// s.sides_name='".$_POST['extsides_' . $_POST['product_id_edit']][$k]."',
						// s.attr_name='".$_POST['sidesextname_' . $_POST['product_id_edit']][$k]."'
						// WHERE s.sides_id = '".$_POST['sidesid_' . $_POST['product_id_edit']][$k]."'
						// ";
						// DB::query($sideSql);
						DB::insert("attributes_sides", array(
							'orders_id' => $orders_id,
							'products_id' => $_POST['sidesproid_' . $_POST['product_id_edit']][$k],
							'extra_attributes_id' => $_POST['sidesextid_' . $_POST['product_id_edit']][$k],
							'sides_name' => $_POST['extsides_' . $_POST['product_id_edit']][$k],
							'attr_name' => $_POST['sidesextname_' . $_POST['product_id_edit']][$k],
							'toppings' => $_POST['sidesToppingName_' . $_POST['product_id_edit']][$k],
							'count' => '1'
						));
					// }
					// else{
					// 	echo " product ID : ".$_POST['sidesproid_' . $_POST['product_id_edit']][$k];
					// 	echo " Extras ID : ".$_POST['sidesextid_' . $_POST['product_id_edit']][$k];
					// 	echo " Sides Name : ".$_POST['extsides_' . $_POST['product_id_edit']][$k];
					// 	echo " Extras name : ".$_POST['sidesextname_' . $_POST['product_id_edit']][$k];
					// 	echo "<br/>";
					// 	DB::insert("attributes_sides", array(
					// 		'orders_id' => $orders_id,
					// 		'products_id' => $_POST['sidesproid_' . $_POST['product_id_edit']][$k],
					// 		'extra_attributes_id' => $_POST['sidesextid_' . $_POST['product_id_edit']][$k],
					// 		'sides_name' => $_POST['extsides_' . $_POST['product_id_edit']][$k],
					// 		'attr_name' => $_POST['sidesextname_' . $_POST['product_id_edit']][$k],
					// 		'toppings' => $_POST['sidesToppingName_' . $_POST['product_id_edit']][$k],
					// 		'count' => '1'
					// 	));
					// }
				}
        }
        
        
        
        
        
        if (isset($_POST['INCsidesproid_' . $_POST['product_id_edit']])) {
			$sql = 'DELETE FROM `attributes_sides` WHERE orders_id ='.$orders_id.' AND toppings = "Included" AND products_id = '.$_POST['product_id_edit'];
			DB::query($sql);
			if(!empty($_POST['INCsidesid_' . $_POST['product_id_edit']])){
				for ($k = 0; $k < count($_POST['INCsidesproid_' . $_POST['product_id_edit']]); $k ++) {
					// $sideSql = "UPDATE attributes_sides s SET
					// s.sides_name='".$_POST['INCextsides_' . $_POST['product_id_edit']][$k]."',
					// s.attr_name='".$_POST['INCsidesextname_' . $_POST['product_id_edit']][$k]."'
					// WHERE s.sides_id = '".$_POST['INCsidesid_' . $_POST['product_id_edit']][$k]."'
					// ";
					// DB::query($sideSql);
					DB::insert("attributes_sides", array(
						'orders_id' => $orders_id,
						'products_id' => $_POST['INCsidesproid_' . $_POST['product_id_edit']][$k],
						'extra_attributes_id' => @$_POST['INCsidesextid_' . $_POST['product_id_edit']][$k],
						'sides_name' => $_POST['INCextsides_' . $_POST['product_id_edit']][$k],
						'attr_name' => $_POST['INCsidesextname_' . $_POST['product_id_edit']][$k],
						'toppings' => $_POST['INCsidesToppingName_' . $_POST['product_id_edit']][$k],
						'count' => '1'
					));
				}
			}
			// else{
			// 	for ($k = 0; $k < count($_POST['INCsidesproid_' . $_POST['product_id_edit']]); $k ++) {
			// 		DB::insert("attributes_sides", array(
			// 			'orders_id' => $orders_id,
			// 			'products_id' => $_POST['INCsidesproid_' . $_POST['product_id_edit']][$k],
			// 			'extra_attributes_id' => @$_POST['INCsidesextid_' . $_POST['product_id_edit']][$k],
			// 			'sides_name' => $_POST['INCextsides_' . $_POST['product_id_edit']][$k],
			// 			'attr_name' => $_POST['INCsidesextname_' . $_POST['product_id_edit']][$k],
			// 			'toppings' => $_POST['INCsidesToppingName_' . $_POST['product_id_edit']][$k],
			// 			'count' => '1'
			// 		));
			// 	}
			// }
        }
        
        
        
        
        
        
    // }
	echo "<pre>";
	
    @extract($_POST);

	$total = round($price_edit * $qty_edit, 2);
	
	// echo "UPDATE orders_products op SET op.`size` = '" . rtrim($sizes, '"\"').'"' . "', op.`qty` = '" . $qty_edit . "', op.`price`='" . $price_edit . "', op.`is_bid` = '" . $is_bid_edit . "', op.`total` = '" . $total . "' WHERE op.`orders_id` = '" . $orders_id . "' AND op.`products_id` = '" . $product_id_edit . "'";
	// print_r($_POST);
	// die(1);

    DB::query("UPDATE orders_products op SET op.`size` = '" . rtrim($sizes, '"\"').'"' . "', op.`qty` = '" . $qty_edit . "', op.`price`='" . $price_edit . "', op.`is_bid` = '" . $is_bid_edit . "', op.`total` = '" . $total . "' WHERE op.`orders_id` = '" . $orders_id . "' AND op.`products_id` = '" . $product_id_edit . "'");

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
    
	@extract($_POST);
	// echo "<pre>";
	// print_r($_POST);
	$total = round($price_add * $qty_add, 2);
	// echo $total;
	// die(1);
    // for ($i = 0; $i < count($_POST['item']); $i ++) {
    if(isset($_POST['product_id_add'])){
        DB::insert("orders_products", array(
            'orders_id' => $orders_id,
            'products_id' => $_POST['product_id_add'],
            'name' => get_product_name($_POST['product_id_add']),
            'description' => '',
            'products_price_id' => '0',
            'is_bid' => '0',
            'size' => $_POST[sizeText].'/',
            'price' => $price_add,
            'qty' => $qty_add,
            'total' => $total,
            'options' => $_POST['options'],
            'product_type' => '1'
        ));
        // die(1);
        if (isset($_POST['extproid_' . $_POST['product_id_add']])) {
            for ($k = 0; $k < count($_POST['extproid_' . $_POST['product_id_add']]); $k ++) {
                DB::insert("orders_products_extras", array(
                    'orders_id' => $orders_id,
                    'products_id' => $_POST['extproid_' . $_POST['product_id_add']][$k],
                    'extra_attributes_id' => $_POST['extid_' . $_POST['product_id_add']][$k],
                    'name' => $_POST['extname_' . $_POST['product_id_add']][$k],
                    'cost' => $_POST['extcost_' . $_POST['product_id_add']][$k],
                    'count' => '1'
                ));
            }
        }
        if (isset($_POST['sidesproid_' . $_POST['product_id_add']])) {
            for ($k = 0; $k < count($_POST['sidesproid_' . $_POST['product_id_add']]); $k ++) {
                DB::insert("attributes_sides", array(
                    'orders_id' => $orders_id,
                    'products_id' => $_POST['sidesproid_' . $_POST['product_id_add']][$k],
                    'extra_attributes_id' => $_POST['sidesextid_' . $_POST['product_id_add']][$k],
                    'sides_name' => $_POST['extsides_' . $_POST['product_id_add']][$k],
                    'attr_name' => $_POST['sidesextname_' . $_POST['product_id_add']][$k],
                    'toppings' => $_POST['sidesToppingName_' . $_POST['product_id_add']][$k],
                    'count' => '1'
                ));
            }
        }
        
        
        
        
        
        if (isset($_POST['INCsidesproid_' . $_POST['product_id_add']])) {
            for ($k = 0; $k < count($_POST['INCsidesproid_' . $_POST['product_id_add']]); $k ++) {
                // echo " product ID : ".$_POST['sidesproid_' . $_POST['product_id_add']][$k];
                // echo " Extras ID : ".$_POST['sidesextid_' . $_POST['product_id_add']][$k];
                // echo " Sides Name : ".$_POST['extsides_' . $_POST['product_id_add']][$k];
                // echo " Extras name : ".$_POST['sidesextname_' . $_POST['product_id_add']][$k];
                // echo "<br/>";
                DB::insert("attributes_sides", array(
                    'orders_id' => $orders_id,
                    'products_id' => $_POST['INCsidesproid_' . $_POST['product_id_add']][$k],
                    'extra_attributes_id' => @$_POST['INCsidesextid_' . $_POST['product_id_add']][$k],
                    'sides_name' => $_POST['INCextsides_' . $_POST['product_id_add']][$k],
                    'attr_name' => $_POST['INCsidesextname_' . $_POST['product_id_add']][$k],
                    'toppings' => $_POST['INCsidesToppingName_' . $_POST['product_id_add']][$k],
                    'count' => '1'
                ));
            }
        }
        
        
        
        
        
        
    }
    
    echo '<script>
            alert("New added");
            location.href="?route=modules/orders/edit_order&orders_id=' . $orders_id . '";
            </script>';
}

// if (isset($_POST['addItem'])) {
// 	echo "<pre>";
// 	print_r($_POST);
// 	die(1);

//     @extract($_POST);

//     $total = round($price_add * $qty_add, 2);

//     DB::insert("orders_products", array(
//         'orders_id' => $orders_id,
//         'products_id' => $product_id_add,
//         'is_bid' => $is_bid_add,
//         'price' => $price_add,
//         'qty' => (int) $qty_add,
//         'total' => $total
//     ));
//     $total = 0.00;
//     $produc = DB::query("SELECT total FROM orders_products op WHERE op.`orders_id` = '" . $orders_id . "'");
//     foreach ($produc as $product) {

//         $total += $product['total'];
//     }

//     DB::update('orders', array(
//         'sub_total' => $total,
//         'order_total' => $total
//     ), 'orders_id=%s', $orders_id);

//     echo '<script>
//             alert("New added");
//             location.href="?route=modules/orders/edit_order&orders_id=' . $orders_id . '";
//             </script>';
// }

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
				<div class="col-xs-6 text-right">
					<address>
						<strong>Order Type:</strong><br>
						<a class="editable" data-toggle="popover"
							data-url="ajax_helpers/ajax_get_product_options_details.php"
							data-orderid = "<?php echo $orders_id; ?>"
							data-name="order_type" data-type="text"
							data-pk="<?php echo $orders_id; ?>"
							data-opt="edit_product_type"
							data-title="Edit Order Type" href="#"><?php echo strtoupper($order['order_type']); ?></a><br>


							<div id="popover-content" style="display:none;">
								<select class="editable form-control">
									<?php 
									if($order['order_type'] == 'delivery'){
									?>
									<option value="pickup">Pickup</option>
									<option value="delivery" selected>Delivery</option>
									<option value="orderin">Order In</option>
									<?php }else if($order['order_type'] == 'pickup'){?>
										<option value="pickup" selected>Pickup</option>
										<option value="delivery">Delivery</option>
										<option value="orderin">Order In</option>
									<?php }else{?>
										<option value="pickup">Pickup</option>
										<option value="delivery">Delivery</option>
										<option value="orderin" selected>Order In</option>
									<?php }?>
								</select>
								<br/>
								<a href="#" class="btn btn-primary" id="submit_type">OK</a>
							</div>
						<script>
						$(function() {

							var inputs = $('[data-toggle="popover"]');
						
							// Popover init
							inputs.popover({
								'content'   : $('#popover-content').html(),
								'html'      : true,
								'placement' : 'auto',
							});
						
							inputs.on('shown.bs.popover', function() {
								// 'aria-describedby' is an attribute set by Bootstrap indicationg the #id of popover
								var popover = $('#' + $(this).attr('aria-describedby'));
						
								// jQuery.one(events [, selector ] [, data ], handler);
								// Passing references to <select> and <input> with `data` to `handler`
								popover.one('click', '.btn', {
										'$select'    : popover.find('select'),
										'$input'     : $(this),
									}, function(event) {
										// `event.data.$select` === `popover.find('select')`, the <select> in the popover
										jQuery(document).on('click',"#submit_type",function(){
											jQuery.ajax({
												method : "POST",
												url:"ajax_helpers/ajax_get_product_options_details.php",
												data:{req:'edit_product_type',orderName:event.data.$select.val(),order_id:<?php echo $orders_id; ?>},
												success:function(data){
													if(data == 1){
														var selected = event.data.$select.val();
														event.data.$input.text(selected.toUpperCase())// set the value of <input>
														.popover('hide');// hide the popover
														location.reload();
													}else{
														alert("Order Type Not Change:");
													}
												}
											});
										});
								});
							});
						});
						</script>
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
									<td><?php echo get_product_name($product['products_id']); ?>
									<?php

                                            if($product['options'] <> '') {
                                                $sides = DB::query("SELECT * FROM attributes_sides WHERE orders_id = '" . $orders_id . "' AND toppings = 'Included' AND products_id = '".$product['products_id']."'");
                                                //                 echo "SELECT * FROM attributes_sides WHERE orders_id = '" . $orders_id . "' AND toppings = 'Included'";
                                                //                 die(1);
                                                $css = "";
                                                echo '<BR/><strong>Includes:</strong><BR/>';
                                                if(!empty($sides)){
                                                    foreach($sides as $side){
                                                        if($side['sides_name'] == 'left'){
                                                            $css = "<div class='left'><div class='innerleft inn'></div></div>";
                                                        }else if($side['sides_name'] == 'complete'){
                                                            $css = "<div class='completeP'><div class='innercomplete inn'></div></div>";
                                                        }else if($side['sides_name'] == 'right'){
                                                            $css = "<div class='right'><div class='innerright inn'></div></div>";
                                                        }
                                                        echo '<div class="optionSides" style="display: flex;margin-bottom: 8px;">'.$css.'<span style="font-weight: 600;position: relative;top: 11px;">'.$side['attr_name'] . '</span></div>';
                                                    }
                                                }
                                            };

                                        ?>
									</td>
									<td><?php echo $product['size']; ?>
									 <?php
                                            $extras = DB::query("SELECT * FROM orders_products_extras WHERE orders_id = '".(int)$orders_id."' AND products_id='".(int)$product['products_id']."'");
                                            if(!empty($extras)){
                                                echo '<BR/><strong>Extras:</strong><BR/>';
                                                foreach ($extras as $ext) {
													$sides = DB::query("SELECT * FROM attributes_sides WHERE orders_id = '" . $ext['orders_id'] . "' AND extra_attributes_id = '".$ext['extra_attributes_id']."' AND toppings = 'Extras'");
													// echo "SELECT * FROM attributes_sides WHERE orders_id = '" . $ext['orders_id'] . "' AND extra_attributes_id = '".$ext['extra_attributes_id']."' AND toppings = 'Extras'";
													// die(1);
                                                    $css = "";
                                                    if(!empty($sides)){
                                                        foreach($sides as $side){
                                                            if($side['sides_name'] == 'left'){
                                                                $css = "<div class='left'><div class='innerleft inn'></div></div>";
                                                            }else if($side['sides_name'] == 'complete'){
                                                                $css = "<div class='completeP'><div class='innercomplete inn'></div></div>";
                                                            }else if($side['sides_name'] == 'right'){
                                                                $css = "<div class='right'><div class='innerright inn'></div></div>";
                                                            }
                                                        }
                                                    }
//                                                     echo $ext['name'].' ['.$ext['cost'].'] x '.$ext['count'].' = '.(int)$ext['count']*$ext['cost'].'<BR/>';
                                                    echo $css.'<span style="text-align:center;">'.$ext['name'] . '</span> <span style="display: block;">[' . $ext['cost'] . '] x ' . $ext['count'] . ' = ' . (int) $ext['count'] * $ext['cost'] . '</span><BR/>';
                                                }
                                            }
                                        ?>
									</td>
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
										title="Del" data-orderID="<?php echo $orders_id; ?>" data-proId="<?php echo $product['products_id']; ?>" class="btn btn-sm btn-danger deleteItem"><i
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

	jQuery(".deleteItem").on('click',function(){
		var productId = jQuery(this).attr("data-proid");
		var orderId = jQuery(this).attr("data-orderID");
		if(confirm('Are you sure want to delete this item?')){
			jQuery.ajax({
				method:"post",
				url:"ajax_helpers/ajax_get_product_options_details.php",
				data:{req:"deleteItem",orderID:orderId,productID:productId},
				success:function(data){
					location.reload();
				}
			});
		}else{
		}
	});

	$(".editItem").on("click", function(){

		Item  = $(this).data('products-id');
		Qty  = $(this).data('qty');
		Price = $(this).data('price');
		Bid = $(this).data('bid');
		Product  = $(this).data('product');

		Total = $(this).data('total');
		$("#product_id_edit").val(Item);
		$("#product_view_edit").html(Product);
		// $("#quantity").val(Qty);
		//$("#product_id_edit").selectOption(Item);
		$("#qty_edit").val(Qty);
		$("#price_edit").val(Price);
		$("#total_edit_view").html(Total);
		$("#is_bid_edit option[value='"+Bid+"']").attr('selected', 'selected');
		if(Bid==0){
			$("#price_edit").prop("readonly", true);
		}
		jQuery.ajax({
			method:'GET',
			url:'ajax_helpers/ajax_get_product_options_details.php?req=getEditSized&pro_id='+ Item+'&index=1&order_id='+<?php echo $orders_id; ?>,
			success:function(data){
				$("#sizesDiv").html(data);
			}
		});
		jQuery.ajax({
			method : 'GET',
			url : 'ajax_helpers/ajax_get_product_options_details.php?req=get_options&order_id='+<?php echo $orders_id;?>+'&pro_id='
					+ Item,
			success : function(data) {
				$("#loadOptions").html(data);

			}
		});
		
		
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
		<form method="POST" action="" class="form-horizontal" id="updateform">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Edit Item</h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label class="control-label col-sm-2" for="product_id_edit">Size:</label>
						<div class="col-sm-10">
							<input type="hidden" id="product_id_edit" name="product_id_edit">
							<!-- <span id="product_view_edit"></span> --> 
							<div id="sizesDiv">
								
							</div>
						</div>
						<label class="control-label col-sm-2" for="product_id_edit">Quantity:</label>
						<div class="col-sm-10">		
							<input type="number" id="qty_edit" name="qty_edit" value="" min="1"/>
							<input type="hidden" id="price_edit" name="price_edit" value=""/>
						</div>
				</div>

					
				   <div id="loadOptions" style='background: #f8f8f8; margin-top: 5px; padding: 8px; border: 1px solid #dfdfdf;'></div>
				</div>
				<input type="hidden" name="orders_id"
					value="<?php echo $orders_id; ?>">
				<div class="modal-footer">
					<button type="submit" name="updateItem" id="updateItem" class="btn btn-success">Update</button>
					&nbsp;
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</form>
	</div>
</div>
<script>

$(function(){
	
	$(".PizzaSizes").on('click',function(){
		// jQuery('#price_edit').val(parseFloat(jQuery('#price_edit').val())+parseFloat(jQuery(this).attr("data-size")));
	});

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
		<form method="POST" action="" id="AddItemForm" class="form-horizontal">
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
						<br/><br/>
						<label class="control-label col-sm-2" for="qty_add">Quantity:</label>
						<div class="col-sm-10">
							<input type="number" name="qty_add" class="form-control" id="qty_add" value="1" min="1" required />
						</div>
						<br/><br/>
						<div id="sizesDivOld" class="col-md-12">
						</div>
						<br/><br/><br/><br/>
						<div id="loadOptionsNew" style="background: #f8f8f8; margin-top: 5px; padding: 8px; border: 1px solid #dfdfdf;"><div class="col-md-6 col-lg-6 optCheckbox"></div>
					</div>
				<input type="hidden" name="orders_id"
					value="<?php echo $orders_id; ?>">
				<input type="hidden" name="price_add" id="price_add" value=""/>
				<div class="modal-footer">
					<button type="submit" name="addItem" id="AddNewItem" class="btn btn-success">Add</button>
					&nbsp;
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</form>
	</div>
</div>
<script>
jQuery("#product_id_add").on('click',function(){
	jQuery.ajax({
		method:'GET',
		url:'ajax_helpers/ajax_get_product_options_details.php?req=get_sizes&pro_id='+ $(this).val()+'&index=1',
		success:function(data){
			$("#sizesDivOld").html(data);
		}
	});
	jQuery.ajax({
		method:'GET',
		url:'ajax_helpers/ajax_get_product_options_details.php?req=add_new_get_options&pro_id='+ $(this).val(),
		success:function(data){
			$("#loadOptionsNew").html(data);
		}

	});
});
	jQuery(document).on('click','#AddNewItem',function(e){
		// e.preventDefault();
		jQuery('#AddItemForm').append('<input type="hidden" name="sizeText" value="'+jQuery('.sizes').attr('data-size')+' " />');
		var Extraas = 0;
	var ExtInpuT = '';
	$('input.selectextra').each(
		function() {
				if ($(this).is(':checked')) {
					Extraas = parseFloat(Extraas)
							+ parseFloat($(this).val());
					
					// start Sides
					ExtInpuT += '<input type="hidden" class="sidesAll'
					+ $(this).data('proid') + '" name="sidesproid_'
					+ $(this).data('proid') + '[]" value="'+$(this).data('proid')+'">';
					
					ExtInpuT += '<input type="hidden" data-name="'
					+$(this).attr('data-name')+'" class="sidesAll'
					+ $(this).data('proid') + '" data-ext="'
					+jQuery(this).attr('data-id')+'" data-proid="'
					+ $(this).data('proid') + '" name="extsides_'
					+ $(this).data('proid') + '[]" value="'+jQuery(this).attr('data-sides')+'">';
					
					ExtInpuT += '<input type="hidden" class="sidesAll'
					+ $(this).data('proid') + '" name="sidesextid_'
					+ $(this).data('proid') + '[]" value="'+$(this).data('id')+'">'; 

					ExtInpuT += '<input type="hidden" class="sidesAll'
					+ $(this).data('proid') + '" name="sidesextname_'
					+ $(this).data('proid') + '[]" value="'+$(this).data('name')+'">';
					
					ExtInpuT += '<input type="hidden" class="sidesAll'
					+ $(this).data('proid') + '" name="sidesToppingName_'
					+ $(this).data('proid') + '[]" value="Extras">';
					
					/*End sides */

					ExtInpuT += '<input type="hidden" class="extinput'
							+ $(this).data('proid')
							+ '" name="extproid_'
							+ $(this).data('proid') + '[]" value="'
							+ $(this).data('proid') + '">';
					ExtInpuT += '<input type="hidden"  class="extinput'
							+ $(this).data('proid') + '" name="extid_'
							+ $(this).data('proid') + '[]" value="'
							+ $(this).data('id') + '">';
					ExtInpuT += '<input type="hidden"  class="extinput'
							+ $(this).data('proid')
							+ '" name="extname_'
							+ $(this).data('proid') + '[]" value="'
							+ $(this).data('name') + '">';
					ExtInpuT += '<input type="hidden"  class="extinput'
							+ $(this).data('proid')
							+ '" name="extcost_'
							+ $(this).data('proid') + '[]" value="'
							+ $(this).data('cost') + '">';
				}
			});
			var sizeRate = parseFloat($('input[class=sizes]:checked', '#AddItemForm').val());
			// alert(parseFloat(sizeRate)+parseFloat(Extraas));
			// alert(sizeRate);
			// alert(Extraas);
			jQuery("#price_add").val(parseFloat(sizeRate)+parseFloat(Extraas));
			// alert(jQuery("#price_add").val());
	// 	});
		
		$("#AddItemForm").append(ExtInpuT);			
		var IncInput = '';
		var Included = [];
		$('input.selectoptions').each(function(){
			if ($(this).is(':checked')) {
				Included.push($(this).val());
			// start Sides
			IncInput += '<input type="hidden" class="sidesAll000'
			+ $(this).data('proid') + '" name="INCsidesproid_'
			+ $(this).data('proid') + '[]" value="'+$(this).data('proid')+'">';
			
			IncInput += '<input type="hidden" class="sidesAll000'
			+ $(this).data('proid') + '" name="INCextsides_'
			+ $(this).data('proid') + '[]" value="'+jQuery(this).attr('data-sides')+'">';

			IncInput += '<input type="hidden" class="sidesAll000'
			+ $(this).data('proid') + '" name="INCsidesextid_'
			+ $(this).data('proid') + '[]" value=" '+$(this).attr('data-opt')+' ">'; 

			IncInput += '<input type="hidden" class="sidesAll000'
			+ $(this).data('proid') + '" name="INCsidesextname_'
			+ $(this).data('proid') + '[]" value="'+$(this).val()+'">';

			IncInput += '<input type="hidden" class="sidesAll000'
			+ $(this).data('proid') + '" name="INCsidesToppingName_'
			+ $(this).data('proid') + '[]" value="Included">';
			
			/*End sides */
			}
		});
		$("#AddItemForm").append('<input type="hidden" name="options" id="options" value="'+Included+'" />');
			// alert(IncInput);
		$("#AddItemForm").append(IncInput);
	});
	jQuery(document).on('click','#updateItem',function(e){
	// e.preventDefault();
	var Extraas = 0;
	var ExtInpuT = '';
	$('input.selectextra').each(
		function() {
				if ($(this).is(':checked')) {
					Extraas = parseFloat(Extraas)
							+ parseFloat($(this).val());
					
					// start Sides
					ExtInpuT += '<input type="hidden" class="sidesAll'
					+ $(this).data('proid') + '" name="sidesproid_'
					+ $(this).data('proid') + '[]" value="'+$(this).data('proid')+'">';

					ExtInpuT += '<input type="hidden" class="sidesAll'
					+ $(this).data('proid') + '" name="sidesid_'
					+ $(this).data('proid') + '[]" value="'+jQuery(this).attr('data-sideid')+'">'; 
					
					ExtInpuT += '<input type="hidden" data-name="'
					+$(this).attr('data-name')+'" class="sidesAll'
					+ $(this).data('proid') + '" data-ext="'
					+jQuery(this).attr('data-id')+'" data-proid="'
					+ $(this).data('proid') + '" name="extsides_'
					+ $(this).data('proid') + '[]" value="'+jQuery(this).attr('data-sides')+'">';
					
					ExtInpuT += '<input type="hidden" class="sidesAll'
					+ $(this).data('proid') + '" name="sidesextid_'
					+ $(this).data('proid') + '[]" value="'+$(this).data('id')+'">'; 

					ExtInpuT += '<input type="hidden" class="sidesAll'
					+ $(this).data('proid') + '" name="sidesextname_'
					+ $(this).data('proid') + '[]" value="'+$(this).data('name')+'">';
					
					ExtInpuT += '<input type="hidden" class="sidesAll'
					+ $(this).data('proid') + '" name="sidesToppingName_'
					+ $(this).data('proid') + '[]" value="Extras">';
					
					/*End sides */

					ExtInpuT += '<input type="hidden" class="extinput'
							+ $(this).data('proid')
							+ '" name="extproid_'
							+ $(this).data('proid') + '[]" value="'
							+ $(this).data('proid') + '">';
					ExtInpuT += '<input type="hidden"  class="extinput'
							+ $(this).data('proid') + '" name="extid_'
							+ $(this).data('proid') + '[]" value="'
							+ $(this).data('id') + '">';
					ExtInpuT += '<input type="hidden"  class="extinput'
							+ $(this).data('proid')
							+ '" name="extname_'
							+ $(this).data('proid') + '[]" value="'
							+ $(this).data('name') + '">';
					ExtInpuT += '<input type="hidden"  class="extinput'
							+ $(this).data('proid')
							+ '" name="extcost_'
							+ $(this).data('proid') + '[]" value="'
							+ $(this).data('cost') + '">';
				}
			});
			var sizeRate = parseFloat($('input[class=PizzaSizes]:checked', '#updateform').attr('data-size'));
			// alert(parseFloat(sizeRate)+parseFloat(Extraas));
			// alert(sizeRate);
			// alert(Extraas);
			jQuery("#price_edit").val(parseFloat(sizeRate)+parseFloat(Extraas));
			// alert(jQuery("#price_edit").val());
	// 	});
		
		$("#updateform").append(ExtInpuT);			
		var IncInput = '';
		$('input.selectoptions').each(function(){
			if ($(this).is(':checked')) {
			// start Sides
			IncInput += '<input type="hidden" class="sidesAll000'
			+ $(this).data('proid') + '" name="INCsidesproid_'
			+ $(this).data('proid') + '[]" value="'+$(this).data('proid')+'">';

			IncInput += '<input type="hidden" class="sidesAll000'
			+ $(this).data('proid') + '" name="INCsidesid_'
			+ $(this).data('proid') + '[]" value="'+$(this).attr('data-sideid')+'">'; 
			
			IncInput += '<input type="hidden" class="sidesAll000'
			+ $(this).data('proid') + '" name="INCextsides_'
			+ $(this).data('proid') + '[]" value="'+jQuery(this).attr('data-sides')+'">';

			IncInput += '<input type="hidden" class="sidesAll000'
			+ $(this).data('proid') + '" name="INCsidesextid_'
			+ $(this).data('proid') + '[]" value=" '+$(this).attr('data-opt')+' ">'; 

			IncInput += '<input type="hidden" class="sidesAll000'
			+ $(this).data('proid') + '" name="INCsidesextname_'
			+ $(this).data('proid') + '[]" value="'+$(this).val()+'">';

			IncInput += '<input type="hidden" class="sidesAll000'
			+ $(this).data('proid') + '" name="INCsidesToppingName_'
			+ $(this).data('proid') + '[]" value="Included">';
			
			/*End sides */
			}
		});
			// alert(IncInput);
		$("#updateform").append(IncInput);
});










$(document).on('change', '.selectextra', function() {
    if ($(this).prop('checked')) { 
		jQuery(this).parent().next('.sides').show();
		// $(this).parent().nextAll('.sides').find('.allsides').each(function(){
		// 	if(jQuery(this).attr('data-value') == 1){
		// 		alert("s");
		// 	}else{
		// 		alert("saddas");
		// 	}
		// });
    }
    else {
        jQuery(this).parent().next('.sides').hide();
    }
});
jQuery(document).on('click','.leftHalff',function(){
	var main = jQuery(this);
    jQuery(this).find('.inner').css('background','linear-gradient(to right, rgb(216, 40, 45) 0%, rgb(214, 38, 44) 50%, rgb(255, 255, 255) 50%, rgb(255, 255, 255) 100%)');
    jQuery(this).next('.completee').find(".inner").css('background','linear-gradient( to left, #008766 0%, #008766 50%, #008766 50%, #008665 100% )');
    jQuery(this).nextAll('.rightHalff').find('.inner').css('background','linear-gradient( to left, #008766 0%, #008766 50%, #ffffff 50%, #FFFFFF 100% )');
//     jQuery(this).parent().prev('.optCheckbox').attr('class');
	jQuery(this).parent().prev('.optCheckbox').find('.selectoptions').attr('data-sides','left');
	jQuery(this).parent().prev('.optCheckbox').find('.selectoptions').attr('data-opt',jQuery(this).attr('data-id'));
});
jQuery(document).on('click','.completee',function(){
	jQuery(this).find('.inner').css('background','linear-gradient(to right, rgb(216, 40, 45) 0%, rgb(216, 40, 45) 50%, rgb(216, 40, 45) 50%, rgb(216, 40, 45) 100%)');
    jQuery(this).prev('.leftHalff').find('.inner').css('background','linear-gradient( to right, #008766 0%, #008766 50%, #ffffff 50%, #ffffff 100% )');
    jQuery(this).next('.rightHalff').find('.inner').css('background','linear-gradient( to left, #008766 0%, #008766 50%, #ffffff 50%, #FFFFFF 100% )');
    jQuery(this).parent().prev('.optCheckbox').find('.selectoptions').attr('data-sides','complete');
    jQuery(this).parent().prev('.optCheckbox').find('.selectoptions').attr('data-opt',jQuery(this).attr('data-id'));
});
jQuery(document).on('click','.rightHalff',function(){
	var right = jQuery(this);
    jQuery(this).find('.inner').css('background','linear-gradient(to right, rgb(255, 255, 255) 0%, rgb(255, 255, 255) 50%, rgb(216, 40, 45) 50%, rgb(216, 40, 45) 100%)');
    jQuery(this).prevAll('.leftHalff').find('.inner').css('background','linear-gradient( to right, #008766 0%, #008766 50%, #ffffff 50%, #ffffff 100% )');
    jQuery(this).prev('.completee').find(".inner").css('background','linear-gradient( to left, #008766 0%, #008766 50%, #008766 50%, #008665 100% )');
    jQuery(this).parent().prev('.optCheckbox').find('.selectoptions').attr('data-sides','right');
    jQuery(this).parent().prev('.optCheckbox').find('.selectoptions').attr('data-opt',jQuery(this).attr('data-id'));
});

jQuery(document).on('click','.leftHalf',function(){
    var main = jQuery(this);
    jQuery(this).find('.inner').css('background','linear-gradient(to right, rgb(216, 40, 45) 0%, rgb(214, 38, 44) 50%, rgb(255, 255, 255) 50%, rgb(255, 255, 255) 100%)');
    jQuery(this).next('.complete').find(".inner").css('background','linear-gradient( to left, #008766 0%, #008766 50%, #008766 50%, #008665 100% )');
    jQuery(this).nextAll('.rightHalf').find('.inner').css('background','linear-gradient( to left, #008766 0%, #008766 50%, #ffffff 50%, #FFFFFF 100% )');
    jQuery(this).find('.allsides').attr('data-value','1');
    jQuery(this).next('.complete').find('.allsides').attr('data-value','0');
    jQuery(this).nextAll('.rightHalf').find('.allsides').attr('data-value','0');
	jQuery(this).parent().prev().find('.selectextra').attr('data-sides','left');
	var extraID = jQuery(this).find('input').attr('data-id');
	var productID = jQuery(this).find('input').attr('data-proid');
	jQuery('.sidesAll'+productID).each(function(){
		if($(this).attr('data-ext') == extraID && $(this).attr('data-proid') ==  productID ){
			$(this).val('left');
		}
	});
});
jQuery(document).on('click','.complete',function(){
    jQuery(this).find('.inner').css('background','linear-gradient(to right, rgb(216, 40, 45) 0%, rgb(216, 40, 45) 50%, rgb(216, 40, 45) 50%, rgb(216, 40, 45) 100%)');
    jQuery(this).prev('.leftHalf').find('.inner').css('background','linear-gradient( to right, #008766 0%, #008766 50%, #ffffff 50%, #ffffff 100% )');
    jQuery(this).next('.rightHalf').find('.inner').css('background','linear-gradient( to left, #008766 0%, #008766 50%, #ffffff 50%, #FFFFFF 100% )');
    jQuery(this).find('.allsides').attr('data-value','1');
    jQuery(this).prev('.leftHalf').find('.allsides').attr('data-value','0');
    jQuery(this).next('.rightHalf').find('.allsides').attr('data-value','0');
	jQuery(this).parent().prevAll().find('.selectextra').attr('data-sides','complete');
	var extraID = jQuery(this).find('input').attr('data-id');
	var productID = jQuery(this).find('input').attr('data-proid');
	jQuery('.sidesAll'+productID).each(function(){
		if($(this).attr('data-ext') == extraID && $(this).attr('data-proid') ==  productID ){
			$(this).val('complete');
		}
	});
});
jQuery(document).on('click','.rightHalf',function(){
	var right = jQuery(this);
    jQuery(this).find('.inner').css('background','linear-gradient(to right, rgb(255, 255, 255) 0%, rgb(255, 255, 255) 50%, rgb(216, 40, 45) 50%, rgb(216, 40, 45) 100%)');
    jQuery(this).prevAll('.leftHalf').find('.inner').css('background','linear-gradient( to right, #008766 0%, #008766 50%, #ffffff 50%, #ffffff 100% )');
    jQuery(this).prev('.complete').find(".inner").css('background','linear-gradient( to left, #008766 0%, #008766 50%, #008766 50%, #008665 100% )');
    jQuery(this).find('.allsides').attr('data-value','1');
    jQuery(this).prev('.complete').find('.allsides').attr('data-value','0');
    jQuery(this).prevAll('.leftHalf').find('.allsides').attr('data-value','0');
	jQuery(this).parent().prevAll().find('.selectextra').attr('data-sides','right');
	var extraID = jQuery(this).find('input').attr('data-id');
	var productID = jQuery(this).find('input').attr('data-proid');
	jQuery('.sidesAll'+productID).each(function(){
		if($(this).attr('data-ext') == extraID && $(this).attr('data-proid') ==  productID ){
			$(this).val('right');
		}
	});
});
</script>