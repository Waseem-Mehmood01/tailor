<?php 

$orders_id = isset($_GET['orders_id'])?(int)$_GET['orders_id']:die('Whoops..! Something went wrong');

$order = DB::queryFirstRow("SELECT  o.`created_on` AS order_date, o.*, c.* FROM orders o
LEFT JOIN customers c
ON(c.`customers_id` = o.`customers_id`)
WHERE o.`orders_id` = '".$orders_id."'");

?>

<section>
<style media="print" type="text/css">.left>.innerleft{-webkit-print-color-adjust: exact;background: linear-gradient(to right, rgb(216, 40, 45) 0%, rgb(214, 38, 44) 50%, rgb(0, 0, 0) 50%, rgb(0, 0, 0) 100%) !important;height:21px;width:100%;border-radius:100%}.innercomplete{-webkit-print-color-adjust: exact;background: linear-gradient(to right, rgb(216, 40, 45) 0%, rgb(214, 38, 44) 50%, rgb(216, 40, 45) 50%, rgb(216, 40, 45) 100%) !important;height:21px;width:100%;border-radius:100%}.innerright{-webkit-print-color-adjust: exact;background: linear-gradient(to right, rgb(0, 0, 0) 0%, rgb(0, 0, 0) 50%, rgb(214, 38, 44) 50%, rgb(214, 38, 44) 100%) !important;height:21px;width:100%;border-radius:100%}.completeP,.left,.right{border:2px solid #248440;width:23%;height:37px;float:left;margin-right:10px;border-radius:100%;padding:6px;position:relative;}</style>
<div class="container">
<button id="btnPrint" onclick="window.print();" class="btn btn-success pull-right" ><i class="fa fa-print"></i> Print</button>
</div>
</section>


<div class="" id="printable">
<style>
@media print {
#btnPrint, footer, header{
display: none;
}

}
.invoice-title h2, .invoice-title h3 {
    display: inline-block;
}

.table > tbody > tr > .no-line {
    border-top: none;
}

.table > thead > tr > .no-line {
    border-bottom: none;
}

.table > tbody > tr > .thick-line {
    border-top: 2px solid;
}
</style>
    <div class="row">
        <div class="col-xs-12">
    		<div class="invoice-title">
    			<h2>Invoice</h2><h3 class="pull-right">Order # <?php echo $order['orders_id']; ?></h3>
    		</div>
    		<hr>
    		<div class="row">
    			<div class="col-xs-6">
    				<address>
    				<strong>Customer:</strong><br>
    					<?php echo $order['fname'].' '.$order['lname']; ?><br>
    					<?php echo $order['email']; ?><br>
    					<?php echo $order['customer_phone']; ?><br>
    				</address>
                     <address>
                    <strong>Order Type:</strong><br>
                        <?php echo $order['order_type']; ?><br>
                       <?php
                        if($order['order_type'] == 'pickup'){
                            echo 'Pickup Time: '.date("h:i A", strtotime($order['pickup_time'])); 
                        }
                       ?>
                    </address>
    			</div>
                
    			<div class="col-xs-6 text-right">
    				<address>
        			<strong>Address:</strong><br>
    					<?php echo $order['fname'].' '.$order['lname']; ?><br>
    					<?php echo $order['address'].$order['street']; ?><br>
    					<?php echo $order['city'].' '.$order['zip'].', '.$order['state'].', '.$order['country']; ?><br>
    				</address>
    			</div>
    		</div>
    		<div class="row">
    			<div class="col-xs-6">
    				<address>
    					<strong>Payment Method:</strong><br>
    					
    					 <?php  echo $order['tender'];
    					 ?><br>Order summary
    					<?php echo $order['email']; ?>
    				</address> <br><br>
    			</div>
    			
    		</div>
    		<div class="row">
    			<div class="col-xs-6">
    				<!--<address>
    					<strong>Payment Method:</strong><br>
    					Card ending **** **** **** <?php echo substr($order['card_no'],14,16); ?><br>
    					<?php echo $order['email']; ?>
    				</address> -->
                        <strong>Customer Note:</strong><br>
                        <?php echo $order['remarks']; ?><br><br>
    			</div>
    			<div class="col-xs-6 text-right">
    				<address>
    					<strong>Order Date:</strong><br>
    					<?php echo date('d-M-y H:i', strtotime($order['order_date']) ); ?><br><br>
    				</address>
    			</div>
    		</div>
    	</div>
    </div>
    
    <div class="row">
    	<div class="col-md-12">
    		<div class="panel panel-default">
    			<div class="panel-heading">
    				<h3 class="panel-title"><strong>Order summary</strong></h3>
    			</div>
    			<div class="panel-body">
    				<div class="table-responsive">
    					<table class="table table-condensed">
    						<thead>
                                <tr>
        							<td><strong>Item</strong></td>
        							<td><strong>Size</strong></td>
        							<td class="text-center"><strong>Price</strong></td>
        							<td class="text-center"><strong>Quantity</strong></td>
        							<td class="text-right"><strong>Totals</strong></td>
                                </tr>
    						</thead>
    						<tbody>
    							<?php 
    							$total = 0.00;
    							$products = DB::query("SELECT * FROM orders_products op WHERE op.`orders_id` = '".$orders_id."'");
    							foreach($products as $product){
    							    $price = 0.00;
    							    $subTotal = 0.00;
    							    
    							    if($product['product_type']=='2'){
    							        $categ = 'SMOKE';
    							    } else {
    							        $categ = get_product_category($product['products_id']);
    							        
    							    }
    							    
    							    $price = $product['price'];
    							   
    							    $subTotal = round( $price * (int)$product["qty"], 2);
    							    $total += $subTotal;
    							?>
    							<tr>
    								<td><?php echo '<small>['.$categ.']</small> '.$product['name']; ?>
    									
    								</td>
    								<td><?php echo stripslashes($product['size']); ?>
                                       
                                    </td>
    								<td class="text-center">$<?php echo $price; ?></td>
    								<td class="text-center"><?php echo $product['qty']; ?></td>
    								<td class="text-right">$<?php echo $product['total']; ?></td>
    							</tr>
    							
    							<?php } ?>
                              
    							<tr>
    								<td class="thick-line"></td>
    								<td class="thick-line"></td>
    								<td class="thick-line"></td>
    								<td class="thick-line text-center"><strong>Subtotal</strong></td>
    								<td class="thick-line text-right">$<?php echo $order['sub_total'];  ?></td>
    							</tr>
    							<tr>
    								<td class="no-line"></td>
    								<td class="no-line"></td>
    								<td class="no-line"></td>
    								<td class="no-line text-center"><strong>TAX</strong></td>
    								<td class="no-line text-right">$<?php echo $order['tax_amount'];  ?></td>
    							</tr>
                                <tr>
                                    <td class="no-line"></td>
                                    <td class="no-line"></td>
                                    <td class="no-line"></td>
                                    <td class="no-line text-center"><strong>TIP</strong></td>
                                    <td class="no-line text-right">$<?php echo $order['tip'];  ?></td>
                                </tr>
                                <tr>
                                    <td class="no-line"></td>
                                    <td class="no-line"></td>
                                    <td class="no-line"></td>
                                    <td class="no-line text-center"><strong>Delivery Charges</strong></td>
                                    <td class="no-line text-right">$<?php echo $order['delivery_charges'];  ?></td>
                                </tr>
    							<tr>
    								<td class="no-line"></td>
    								<td class="no-line"></td>
    								<td class="no-line"></td>
    								<td class="no-line text-center"><strong>Total</strong></td>
    								<td class="no-line text-right">$<?php echo $order['order_total'];  ?></td>
    							</tr>
    						</tbody>
    					</table>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
</div>





<!-- Modal -->
<div id="cardModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><i class="fa fa-credit-card"></i> Card Detail</h4>
      </div>
      <div class="modal-body">
      <?php 
      $card = DB::queryFirstRow("SELECT * FROM cards_info WHERE orders_id = '".$orders_id."'");
      ?>
        <table class="table">
        <tr>
        <td>Card #</td>
        <td><?php echo $card['card_no']; ?></td>
        </tr>
        <tr>
        <td>Expiry</td>
        <td><?php echo $card['expiry_date']; ?></td>
        </tr>
        <td>CVV</td>
        <td><?php echo $card['ccv']; ?></td>
        </tr>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>