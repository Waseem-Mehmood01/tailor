<?php

$message = "";

$get_webprice_level = DB::queryFirstField("select value from sys_config where title = 'web_price_level'");



/*
if (isset($_POST['submit'])) {
    
    
    
    $valid_types = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv');
    
    $flag = true;
    
    $file = $_FILES['importer']['tmp_name'];
    
    $file_name = $_FILES['importer']['name'];
    
    $type = $_FILES['importer']['type'];
    
    $_SESSION['last_import_file'] = $file_name;
    
    
    if(in_array($type, $valid_types)){
        
        $handle  = fopen($file, "r");
        
        if(empty($handle) === false) {
            
            $data = fgetcsv($handle, 10000, ",");
            
            $num_col= count($data);
            
            
            
            if($num_col==2){
                
                if(($data = fgetcsv($handle, 10000, ",")) !== FALSE){
                    
                    
                
       
                    
                    $is_delivered='1';
                    
                    $is_paid='1';
                    
                    $date = date("Y-m-d");
                    
                    $ref_no = 'S'.get_sale_no().'D'.date('dmy');
                    
                    $is_return = '0';
                    
                    $t_qty = 0;
                    
                    $n_items = 0;
                    
                    $sub_total = 0;
                    
                    
                    $insert = DB::Insert('sale_invoice',
                        array(
                            'ref_no'			=> $ref_no,
                            'sale_no'			=> get_sale_no(),
                            'date'				=> $date,
                            'customer'			=> 'Other',
                            'total_qty'			=> $t_qty,
                            'no_of_item'		=> $n_items,
                            'sub_total'			=> $sub_total,
                            'dis_perc'			=> '0',
                            'dis_amount'		=> '0',
                            'tax_perc'			=> '0',
                            'tax_amount'		=> '0',
                            'ship_amount'		=> '0',
                            'total_amount'		=> $sub_total,
                            'comment1'			=> 'Ref# '.$file_name,
                            'comment2'			=> '',
                            'created_by'		=> $_SESSION['username'],
                            'created_on'		=> $now,
                            'is_delivered'		=> $is_delivered,
                            'is_paid'			=> $is_paid,
                            'reciept_type'		=> 'sale',
                            'reciept_workstation'	=> $_SESSION['workstation'],
                            'tender'				=> 'cash'
                        ));
                    
                    
                    $invoice_id =DB::insertId();
                    
                    
                    $handle2  = fopen($file, "r");
                    
                    $flag = 0;
                    
                    
                    
                    while(($data = fgetcsv($handle2, 10000, ",")) !== FALSE){
                        
                        
                        $item = $data[0];
                        
                        $qty = $data[1];
                        
                        $sql_pro = "SELECT r.`item_no`, r.`dcs`, r.`products_name`,r.`unit_case`,r.`".$get_webprice_level."` FROM inventory r WHERE (r.`item_no`='".$item."')";
                        
                        $pro = DB::queryFirstRow($sql_pro);
                        
                        $price = trim($pro[$get_webprice_level]);
                        
                        $amount = (int)$qty * $price;
                        
                        $insert = DB::Insert('sale_invoice_detail',
                            array(
                                'sale_invoice_id'	=> $invoice_id,
                                'ref_no'			=> $ref_no,
                                'item'				=> $item,
                                'dcs'				=> $pro['dcs'],
                                'description'		=> $pro['products_name'],
                                'qty'				=> $qty,
                                'unit_case'			=> $pro['unit_case'],
                                'unit_price'		=> $price,
                                'total'				=> $amount,
                                'last_sold'			=> $now
                            ));
                        
                        update_inventory_qty($item, 'sale', $qty);
                        
                        $update = DB::update('inventory',array('last_sold' => $now),'item_code=%s',$item );
                        
                        $n_items = $n_items+1;
                        
                        $t_qty = $t_qty + $qty;
                        
                        $sub_total = $sub_total + $amount;
                        
                        
                        
                        
                    }
                    
                    
                    fclose($handle);
                    
                    fclose($handle2);
                    
                    $update = DB::update('sale_invoice',
                        array('total_qty'			=> $t_qty,
                            'no_of_item'		=> $n_items,
                            'sub_total'			=> $sub_total,
                            'total_amount'		=> $sub_total,),
                        'sale_invoice_id=%s', $invoice_id );
                        
                        
                        $message = "Import Success. New sale invoice created";
                        
                        echo '<script>alert("Import Success. Sale invoice created");
									location.href="?route=modules/sale/import_pro_qty";
									</script>';
                        
                } else {
                    
                    $message = "Empty data can not be uploaded";
                    
                }
                
            } else {
                
                $message = "You are trying to import invalid csv file.<br> Please contact with support";
                
            }
        }
        
        
    } else {
        
        $message = "Invalid file type.";
        
    }
    
}  *****/















/***


if(isset($_POST['orderPunch'])){
    
    $orders_id = trim($_POST['orders_id']);
    
    
    
    if($orders_id<>''){
        
        $products = DB::query("select * from orders_products where orders_id='".(int)$orders_id."'");
        
        $total_products = DB::count();
        
        
        if($total_products > 0){
            
            

            
            $is_delivered='1';
            
            $is_paid='1';
            
            $date = date("Y-m-d");
            
            $ref_no = 'S'.get_sale_no().'D'.date('dmy');
            
            $is_return = '0';
            
            $t_qty = 0;
            
            $n_items = 0;
            
            $total_amount = DB::queryFirstField("SELECT `text` FROM orders_total WHERE orders_id='".$orders_id."' where class='ot_total'");
            
            $total_amount = floatval($total_amount);
            
            
            $insert = DB::Insert('sale_invoice',
                array(
                    'ref_no'			=> $ref_no,
                    'sale_no'			=> get_sale_no(),
                    'date'				=> $date,
                    'customer'			=> 'Other',
                    'total_qty'			=> $t_qty,
                    'no_of_item'		=> $n_items,
                    'sub_total'			=> $total_amount,
                    'dis_perc'			=> '0',
                    'dis_amount'		=> '0',
                    'tax_perc'			=> '0',
                    'tax_amount'		=> '0',
                    'ship_amount'		=> '0',
                    'total_amount'		=> $total_amount,
                    'comment1'			=> 'Ref# '.$orders_id,
                    'comment2'			=> '',
                    'created_by'		=> $_SESSION['username'],
                    'created_on'		=> $now,
                    'is_delivered'		=> $is_delivered,
                    'is_paid'			=> $is_paid,
                    'reciept_type'		=> 'sale',
                    'reciept_workstation'	=> $_SESSION['workstation'],
                    'tender'				=> 'cash'
                ));
            
            
            $invoice_id =DB::insertId();
            
            foreach($products as $product ){
                
                $pro_row = DB::queryFirstRow("select `dcs`,`unit_case` from inventory where `item_no`='".$product['products_id']."'");
                
                $amount = (int)$product['products_quantity'] * $product['final_price'];
                
                $insert = DB::Insert('sale_invoice_detail',
                    array(
                        'sale_invoice_id'	=> $invoice_id,
                        'ref_no'			=> $ref_no,
                        'item'				=> $product['products_id'],
                        'dcs'				=> $pro_row['dcs'],
                        'description'		=> $product['products_name'],
                        'qty'				=> $product['products_quantity'],
                        'unit_case'			=> $pro_row['unit_case'],
                        'unit_price'		=> $product['final_price'],
                        'total'				=> $amount,
                        'last_sold'			=> $now
                    ));
                
                update_inventory_qty($product['products_id'], 'sale', $product['products_quantity']);
                
                $update = DB::update('inventory',array('last_sold' => $now),'item_no=%s',$product['products_id']);
                
                $t_qty += $product['products_quantity'];
                
                $n_items += 1;
                
            }
            $update = DB::update("sale_invoice",array(
                'total_qty'			=> $t_qty,
                'no_of_item'		=> $n_items
            ),'sale_invoice_id=%s', $invoice_id);
            
            if($insert){
                
                echo '<script>
						alert("Punch Success");
					 	location.href="?route=modules/sale/import_pro_qty";
						</script>';
                
            } else {
                echo '<script>
						alert("Whoops..! Something went wrong. Please contact with support");
					 	location.href="?route=modules/sale/import_pro_qty";
						</script>';
            }
            
            
        }
    }
    
} ****/







/***
 * Submit orders range
 */

if(isset($_POST['submitOrdersRange'])){
    
    if(isset($_POST['ordersRange'])){
    
    for($i=0;$i<count($_POST['ordersRange']);$i++){
        
        $orders_id = (int)trim($_POST['ordersRange'][$i]);
        
        if($orders_id<>''){
            
            $products = DB::query("select * from orders_products where orders_id='".(int)$orders_id."'");
            
            $total_products = DB::count();
            
            
            if($total_products > 0){
                
                
                /****** INSERT IVOICE ***/
                
                $is_delivered='1';
                
                $is_paid='1';
                
                $date = date("Y-m-d");
                
                $ref_no = 'S'.get_sale_no().'D'.date('dmy');
                
                $is_return = '0';
                
                $t_qty = 0;
                
                $n_items = 0;
                
                $total_amount = DB::queryFirstField("SELECT `text` FROM orders_total WHERE class='ot_total' AND orders_id='".$orders_id."'");
                
                $total_amount = preg_replace("/[^0-9\.]/", "",$total_amount);
                
                $tx_val = $total_amount - ($total_amount/1.05);
                
                $sub_total = $total_amount - $tx_val;
                
                $customers_name = DB::queryFirstField("select customers_name from orders where orders_id='".$orders_id."'");
                
                /*
                 echo ' Total:'.$total_amount;
                 
                 echo ' taxl:'.$tx_val;
                 echo ' Sub Total:'.$sub_total;
                 
                 die;
                 */
                
                $insert = DB::Insert('sale_invoice',
                    array(
                        'ref_no'			=> $ref_no,
                        'sale_no'			=> get_sale_no(),
                        'date'				=> $date,
                        'customer'			=> $customers_name,
                        'total_qty'			=> $t_qty,
                        'no_of_item'		=> $n_items,
                        'sub_total'			=> $sub_total,
                        'dis_perc'			=> '0',
                        'dis_amount'		=> '0',
                        'tax_perc'			=> '5',
                        'tax_amount'		=> $tx_val,
                        'ship_amount'		=> '0',
                        'total_amount'		=> $total_amount,
                        'comment1'			=> 'Ref# '.$orders_id,
                        'comment2'			=> '',
                        'created_by'		=> $_SESSION['username'],
                        'created_on'		=> $now,
                        'is_delivered'		=> $is_delivered,
                        'is_paid'			=> $is_paid,
                        'reciept_type'		=> 'sale',
                        'reciept_workstation'	=> $_SESSION['workstation'],
                        'tender'				=> 'cash',
                        'tender_amount'			=> $total_amount
                    ));
                
                
                $invoice_id =DB::insertId();
                
                foreach($products as $product ){
                    
                    $casing = 1;
                    
                    $proCode = 0;
                    
                    $pro_row = DB::queryFirstRow("select `dcs`,`unit_case`, `products_name` from inventory where `item_no`='".$product['products_id']."'");
                    
                    $proCode = DB::queryFirstField("select beer_case from casing where casing_id = '" . (int)$product['products_id']. "'");
                    
                    if($proCode == '1'){
                        
                        if(!is_promotion_item($product['products_id'])){
                        
                            $casing = DB::queryFirstField("select casing_packof from casing where casing_id = '" .(int)$product['products_id']. "' AND beer_case = 1");
                        
                            $amount = ( (int)$product['products_quantity'] / (int)$casing ) * $product['final_price'];
                        
                        } else {
                            
                            $amount = (int)$product['products_quantity'] * $product['final_price'];
                            
                        }
                      
                    } else {
                        
                        $amount = (int)$product['products_quantity'] * $product['final_price'];
                        
                    }
                    
                    
                    
                    $insert = DB::Insert('sale_invoice_detail',
                        array(
                            'sale_invoice_id'	=> $invoice_id,
                            'ref_no'			=> $ref_no,
                            'item'				=> $product['products_id'],
                            'dcs'				=> $pro_row['dcs'],
                            'description'		    => $pro_row['products_name'],
                            'qty'				=> $product['products_quantity'],
                            'unit_case'			=> $pro_row['unit_case'],
                            'unit_price'		=> $product['final_price'],
                            'total'				=> $amount,
                            'last_sold'			=> $now
                        ));
                    
                    
                    
                    /*check if item is promo then update inventory on base of sub item*/
                    
                    if(is_promotion_item($product['products_id'])){
                        $promo_items = DB::query("select sub_items, qty from promotion_items where item_no='".$product['products_id']."'");
                        foreach($promo_items as $promo_item){
                            update_inventory_qty($promo_item['sub_items'], 'sale', ( (int)$product['products_quantity'] * (int)$promo_item['qty'] ) );
                            $update = DB::update('inventory',array('last_sold' => $now),'item_no=%s',$promo_item['sub_items']);
                            
                        }
                    }
                    
                    
                    
                    update_inventory_qty($product['products_id'], 'sale', $product['products_quantity']);
                    
                    $update = DB::update('inventory',array('last_sold' => $now),'item_no=%s',$product['products_id']);
                    
                    $t_qty += $product['products_quantity'];
                    
                    $n_items += 1;
                    
                }
                
                $update = DB::update("sale_invoice",array(
                    'total_qty'			=> $t_qty,
                    'no_of_item'		=> $n_items
                ),'sale_invoice_id=%s', $invoice_id);
                
                $update = DB::update("orders",array(
                    'orders_status'			=> '11'
                ),'orders_id=%s', $orders_id);
                
                
                
            }
            
            
            
            
            $gift_products = DB::query("select * from orders_gifts_products where orders_id='".(int)$orders_id."'");
            
            $total_gproducts = DB::count();
            
            
            if($total_gproducts > 0){
                
                
                /****** INSERT IVOICE ***/
                
                $is_delivered='1';
                
                $is_paid='1';
                
                $date = date("Y-m-d");
                
                
                $is_return = '0';
                
                $t_qty = 0;
                
                $n_items = 0;
                
                $total_amount = 0;
                
                $tx_val = 0;
                
                $sub_total = $total_amount - $tx_val;
                
                
                
                $customers_name = DB::queryFirstField("select customers_name from orders where orders_id='".$orders_id."'");
                
                /*
                 echo ' Total:'.$total_amount;
                 
                 echo ' taxl:'.$tx_val;
                 echo ' Sub Total:'.$sub_total;
                 
                 die;
                 */
                
                $insert = DB::Insert('sale_invoice',
                    array(
                        'ref_no'			=> $ref_no,
                        'sale_no'			=> get_sale_no(),
                        'date'				=> $date,
                        'customer'			=> $customers_name,
                        'total_qty'			=> $t_qty,
                        'no_of_item'		=> $n_items,
                        'sub_total'			=> $sub_total,
                        'dis_perc'			=> '0',
                        'dis_amount'		=> '0',
                        'tax_perc'			=> '5',
                        'tax_amount'		=> $tx_val,
                        'ship_amount'		=> '0',
                        'total_amount'		=> $total_amount,
                        'comment1'			=> 'Ref# '.$orders_id,
                        'comment2'			=> '',
                        'created_by'		=> $_SESSION['username'],
                        'created_on'		=> $now,
                        'is_delivered'		=> $is_delivered,
                        'is_paid'			=> $is_paid,
                        'reciept_type'		=> 'sale',
                        'reciept_workstation'	=> $_SESSION['workstation'],
                        'tender'				=> 'gift'
                    ));
                
                
                $invoice_id =DB::insertId();
                
                foreach($gift_products as $product ){
                    
                    $casing = 1;
                    
                    $proCode = 0;
                    
                    $pro_row = DB::queryFirstRow("select `dcs`,`unit_case`, `products_name` from inventory where `item_no`='".$product['products_id']."'");
                    
                    $proCode = DB::queryFirstField("select beer_case from casing where casing_id = '" . (int)$product['products_id']. "'");
                    
                    if($proCode == '1'){
                        
                        $casing = DB::queryFirstField("select casing_packof from casing where casing_id = '" .(int)$product['products_id']. "' AND beer_case = 1");
                        
                        $amount = ( (int)$product['products_quantity'] / (int)$casing ) * $product['final_price'];
                        
                    } else {
                        
                        $amount = (int)$product['products_quantity'] * $product['final_price'];
                        
                    }
                    
                    
                    
                    $insert = DB::Insert('sale_invoice_detail',
                        array(
                            'sale_invoice_id'	=> $invoice_id,
                            'ref_no'			=> $ref_no,
                            'item'				=> $product['products_id'],
                            'dcs'				=> $pro_row['dcs'],
                            'description'		=> $pro_row['products_name'],
                            'qty'				=> $product['products_quantity'],
                            'unit_case'			=> $pro_row['unit_case'],
                            'unit_price'		=> $product['final_price'],
                            'total'				=> $amount,
                            'last_sold'			=> $now
                        ));
                    
                    
                    
                    /*check if item is promo then update inventory on base of sub item*/
                    
                    if(is_promotion_item($product['products_id'])){
                        $promo_items = DB::query("select sub_items, qty from promotion_items where item_no='".$product['products_id']."'");
                        foreach($promo_items as $promo_item){
                            update_inventory_qty($promo_item['sub_items'], 'sale', ( (int)$product['products_quantity'] * (int)$promo_item['qty'] ) );
                            $update = DB::update('inventory',array('last_sold' => $now),'item_no=%s',$promo_item['sub_items']);
                            
                        }
                    }
                    
                    update_inventory_qty($product['products_id'], 'sale', $product['products_quantity']);
                    
                    $update = DB::update('inventory',array('last_sold' => $now),'item_no=%s',$product['products_id']);
                    
                    $t_qty += $product['products_quantity'];
                    
                    $total_amount += $amount;
                    
                    $tx_val = $total_amount - ($total_amount/1.05);
                    
                    $sub_total = $total_amount - $tx_val;
                    
                    $n_items += 1;
                    
                }
                
                $update = DB::update("sale_invoice",array(
                    'total_qty'			=> $t_qty,
                    'no_of_item'		=> $n_items,
                    'sub_total'			=> $sub_total,
                    'tax_amount'		=> $tx_val,
                    'total_amount'		=> $total_amount,
                ),'sale_invoice_id=%s', $invoice_id);
                
                
                
                
                
                
                
                
            }
        }
    }
    
    if($insert){
        
        echo '<script>
						alert("Punch Success");
					 	location.href="?route=modules/sale/import_pro_qty";
						</script>';
        
    } else {
        echo '<script>
						alert("Whoops..! Something went wrong. Please contact with support");
					 	location.href="?route=modules/sale/import_pro_qty";
						</script>';
    }
    
    } else {
        
        echo '<script>
						alert("Whoops..! Please select orders");
					 	location.href="?route=modules/sale/import_pro_qty";
						</script>';
        
    }
    
}


?>

 <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
          		Web Reciepts Entry
            <small> (As Sale).</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?php echo SITE_ROOT; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Import Product Qty</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">

 <!-- Default box 
          <div class="box">
            <div class="box-header with-border">
              <h3 class="">Create Sale Invoice as Import</h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
              </div>
            </div>

            <div class="box-body">
				<div class="row">
					<div class="col-md-4 col-md-offset-4">
						<div class="row">
							<form class="form-vertical" method="POST" action="" enctype="multipart/form-data">
								<div class="col-md-8">
								<input type="file" name="importer" title="Select .csv file" required="required" accept=".csv,.txt, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" id="importer" class="form-control" />
								</div><div class="col-md-8">
								<p>Select .txt file of order</div>
								<br><br>
								<div class="col-md-8">
								<button type="submit" name="submit" class="btn btn-lg btn-success"><i class="glyphicon glyphicon-import"></i>&nbsp;Import Now</button>
								</div>
							</form>
						</div>
					</div>
				</div>
            </div>
            <div class="box-footer">
            <?php if(isset($_SESSION['last_import_file'])){
            	echo 'Last Import File was: '.$_SESSION['last_import_file'];
            } ?>
            <br>
             <h4><?php echo $message; ?></h4>
            </div>
          </div>
		</section>







        <section class="content">


          <div class="box">
            <div class="box-header with-border">
              <h3 class="">View Web Sale Invoice</h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
              </div>
            </div>

            <div class="box-body">
				<div class="row">
					<div class="col-md-4 col-md-offset-4">
						<div class="row">
							<form class="form-vertical" method="POST" action="">
								<div class="col-md-8">
								<input type="text" name="order_id" placeholder="Order ID" title="Order ID" required="required" id="order_id" class="form-control" value="<?php if(isset($_POST['order_id'])){ echo $_POST['order_id']; } ?>" />
								</div><div class="col-md-8">
								<p></p></div>
								<br><br>
								<div class="col-md-8">
								<button type="submit" name="submitOrder" class="btn btn-lg btn-primary">NEXT</button>
								</div>
								<div class="col-md-8">
								<p></p></div>
								<br><br>
							</form>
						</div>
					</div>
				</div>

				<?php if(isset($_POST['submitOrder'])){

						$order_id = (int)trim($_POST['order_id']);

						if($order_id<>''){

							$products = DB::query("select * from orders_products where orders_id='".$order_id."'");



					?>
				<div class="row">
					<div class="col-md-8 col-md-offset-2">
						<table style="width: 100%;">
							<thead style="text-align: center;">
							<tr style="border:1px solid black;">
								<th style="text-align: center;">&nbsp;Item</th>
								<th style="text-align:left; padding-left:4%;">&nbsp;Description</th>
								<th style="text-align: center;">&nbsp;Qty</th>
								<th style="text-align: center;">&nbsp;Unit Price</th>
								<th style="text-align: center;">&nbsp;Total</th>
							</tr>
							</thead>
							<tbody style="border-bottom:1px solid black; text-align: center;">
								<?php foreach($products as $product){ ?>
								<tr>
									<td><?php echo $product['products_id']; ?></td>
									<td style="text-align:left; padding-left:5%;"><?php echo $product['products_name']; ?></td>
									<td><?php echo $product['products_quantity']; ?></td>
									<td style="text-align:right; padding-right:5%;"><?php echo $product['final_price']; ?></td>
									<?php $sub_total =  (int)$product['products_quantity'] * $product['final_price']; ?>
									<td style="text-align:right; padding-right:5%;"><?php echo $sub_total; ?></td>
								</tr>
								<?php } ?>
							</tbody>
							<tr>
							<?php $total_amount = DB::queryFirstField("SELECT `text` FROM orders_total WHERE orders_id='".$order_id."'"); ?>
							<td colspan="5" style="text-align:right; padding-right:4%;"><h4>Total: <strong><?php echo parseFloat($total_amount); ?></strong></h4></td>
							</tr>

							<tr>

							<td colspan="5" style="text-align:right; padding-right:4%;"><strong>&nbsp;</strong></td>
							</tr>

							<tr>
								<td colspan="5" style="text-align:right; padding-right:4%;">
									<form method="POST" action="">
									<input type="hidden" name="orders_id" id="orders_id" value="<?php echo $order_id; ?>">
									<input type="submit" name="orderPunch" id="orderPunch" value="PUNCH AS INVOICE" class="btn btn-lg btn-success" />
									</form>

								</td>
							</tr>

							<tr>

							<td colspan="5" style="text-align:right; padding-right:4%;"><strong>&nbsp;</strong></td>
							</tr>

						</table>


					</div>
				</div>

				<?php }

						}
				?>

            </div>



            <div class="box-footer">

            </div>
          </div>
		</section> -->















      <!--  <section class="content">-->

 <!-- Default box -->
          <div class="box">
            <div class="box-header with-border">
              <h3 class="">Punch Orders</h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
              </div>
            </div>

            <div class="box-body">
				<div class="row">
					<div class="col-md-6 col-md-offset-4">
						<div class="row">
							<form class="form-horizontal" method="POST" action="">
								<div class="form-inline">
								<label>From:&nbsp;</label><input type="text" name="order_id_from" placeholder="Order ID From" title="Order ID" required="required" id="order_id_from" class="form-control" value="<?php if(isset($_POST['order_id_from'])){ echo $_POST['order_id_from']; } ?>" />
								
								<label>&nbsp;To:&nbsp;</label><input type="text" name="order_id_to" placeholder="Order ID To" title="Order ID" required="required" id="order_id_to" class="form-control" value="<?php if(isset($_POST['order_id_to'])){ echo $_POST['order_id_to']; } ?>" />
								
								&nbsp;<button type="submit" name="submitOrderRange" class="btn btn-primary">NEXT</button>
								</div>

							</form>
						</div>
					</div>
				</div>

				<?php 
				$where_clause='';
				
				$order_id_from = isset($_POST['order_id_from'])?(int)trim($_POST['order_id_from']):'';

				$order_id_to = isset($_POST['order_id_to'])?(int)trim($_POST['order_id_to']):'';

						if( ( $order_id_from <> '' ) AND ( $order_id_to <> '' ) ) {

							if($order_id_to > $order_id_from){
								$o = $order_id_to;
								$order_id_to = $order_id_from;
								$order_id_from = $o;
							}
							
							$where_clause = " AND o.`orders_id` >= '".$order_id_to."' AND  o.`orders_id` <= '".$order_id_from."' ";

						} else {
						    $where_clause = " AND o.`orders_status`='10' ";
						}
                    
						$sql_produ = "SELECT ot.`orders_id`, ot.`text` FROM orders o, orders_total ot WHERE o.`orders_id`= ot.`orders_id` AND ot.`class` = 'ot_total' ".$where_clause." ORDER BY o.`orders_id` DESC";
				
                        $orders_row = DB::query($sql_produ);
						

							
				
				?>
				<div class="row">
					<div class="col-md-8 col-md-offset-2">
						<table class="table table-bordered table-hover table-striped">
							<thead>
							<tr>
								<th style="width: 50px;"><input type="checkbox" id="selectAllOrders"></th>
								<th>&nbsp;Order</th>
								<th>&nbsp;Total</th>
								<th>&nbsp;Status</th>
								<th>&nbsp;</th>
							</tr>
							</thead>
							<tbody>
								<form method="POST" action="">
								<?php foreach($orders_row as $orders){ ?>
								<tr>
									<td><input type="checkbox" name="ordersRange[]" value="<?php echo $orders['orders_id']; ?>" class="ordersRange"></td>
									<td><?php echo $orders['orders_id']; ?></td>
									<td><?php echo $orders['text']; ?></td>
									<td><?php echo get_order_status($orders['orders_id']); ?></td>
									<td><a class="btn btn-default btn-sm btnOID" data-oID="<?php echo $orders['orders_id']; ?>" data-toggle="modal" data-target="#receiptModal" href="#">DETAIL</a></td>

								</tr>
								<?php
									
								}
								 ?>

								<tr>
									<td>&nbsp;</td>
									<td colspan="3">
										<?php if(!empty($orders_row)){ ?>
									<input type="submit" name="submitOrdersRange" id="submitOrdersRange" class="btn btn-lg btn-success pull-right" value="PUNCH ORDERS">
								<?php } ?>
									</td>
								</tr>
								</form>
							</tbody>


						</table>


					</div>
				</div>

				

            </div><!-- /.box-body -->



            <div class="box-footer">

            </div>
          </div><!-- /.box -->
		</section>



<script type="text/javascript">

$(document).ready(function(){


	$(".btnOID").on('click', function(){

		oID = $(this).data('oid');
		$("#txtOID").html(oID);
		$.ajax({

			method: 'POST',
			url: 'ajax_get_order_detail.php',
			data: {oID: oID},
			success: function(e){
				$("div#invoiceDetail").html(e);
				}
			

			});
		
	});

	

	$("#selectAllOrders").click(function(){
		if($(this).is(':checked')){
			$(".ordersRange").each(function(){
				$(this).prop("checked",true);
			});
		}else{
			$(".ordersRange").each(function(){
				$(this).prop("checked",false);
			});
		}
	});

})

</script>



<!-- Modal -->
<div id="receiptModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Invoice Detail [ <strong id="txtOID"></strong> ]</h4>
      </div>
      <div class="modal-body" id="invoiceDetail">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
