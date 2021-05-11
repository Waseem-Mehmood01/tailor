<?php
$t_date = date('m/d/Y');
$sale_invoice_id = '';
//print_r($_SESSION['user_id']);


if(isset($_GET['invoice_id'])){
	$sale_invoice_id = $_GET['invoice_id'];
} else if(isset($_POST['invoice_id'])){
	$sale_invoice_id = $_POST['invoice_id'];
} else {
	$sale_invoice_id = '';
}





if($sale_invoice_id=='') { die('Whoops..! Something went wrong. Go back'); }

$invoice = DB::queryFirstRow("SELECT * FROM sale_invoice si WHERE si.`sale_invoice_id` = '".$sale_invoice_id."'");

@extract($invoice);



if(isset($_POST['save'])){
	

	
	$date = date("Y-m-d", strtotime($_POST['date']));
	
	
	$tender_amount = $_POST['subTotal'];
	
	if($_POST['tender'] == 'card'){
	    $tender_amount = 0;
	}
	
	$insert = DB::update('sale_invoice', 
								array(
									'date'				=> $date,
									'customer'			=> $_POST['customer'],
									'total_qty'			=> $_POST['total_qty'],
									'no_of_item'		=> $_POST['count_item'],
									'sub_total'			=> $_POST['sub_total'],
									'dis_perc'			=> $_POST['disc'],
									'dis_amount'		=> $_POST['s_total2'],
									'tax_perc'			=> $_POST['tax'],
									'tax_amount'		=> $_POST['s_total3'],
									'total_amount'		=> $_POST['subTotal'],
									'comment1'			=> $_POST['comment1'],
									'comment2'			=> $_POST['comment2'],
									'modified_by'		=> $_SESSION['user_name'],
									'modified_on'		=> $now,
									'reciept_type'		=> 'sale',
									'tender'				=> $_POST['tender'],
									'customer_phone'		=> $_POST['customer_phone'],
									'customer_email'		=> $_POST['customer_email'],
									'customer_type'		=> $_POST['customer_type']
									),'sale_invoice_id=%s',$sale_invoice_id);
	
	
	
	
	
	if( ($_POST['customer']=='Instagram') AND ($_POST['instagram_id'] <> '') ){
	    DB::Insert('instagram_pos',
	        array(
	            'sale_invoice_id'	=> $sale_invoice_id,
	            'instagram_id'			=> $_POST['instagram_id'] )
	        );
	}
	
	

	$invoice_details = DB::query("SELECT qty, item, size FROM sale_invoice_detail sd WHERE sd.`sale_invoice_id`='".$sale_invoice_id."'");
	foreach($invoice_details as $invoice_detail){
		
		/*
					 * Return back
					 */
					
					$u_sql = "UPDATE products_price pp SET 
                    pp.`stock` = (pp.`stock` + ".(int)$invoice_detail['qty'].") 
                    WHERE pp.`products_id` = '".(int)$invoice_detail['item']."' 
                    AND pp.`size` = '".$invoice_detail['size']."'";
					
					DB::query($u_sql);
			
	
		
	}


	
		
	
	/* delete previous record */

	DB::query("DELETE FROM sale_invoice_detail WHERE sale_invoice_id='".$sale_invoice_id."'");
	DB::query("DELETE FROM cards_info WHERE sales_invoice_id='".$sale_invoice_id."'");
	
	
	for($i=0, $iMaxSize=count($_POST['rows']); $i<$iMaxSize; $i++){
					if(trim($_POST['item'][$i])<>''){

				    /*
				     * if already exist update quantity
				     */

				    $is_exist = DB::queryFirstRow("SELECT sd.`qty`,  sd.`sale_invoice_detail_id` FROM sale_invoice_detail sd WHERE 
                                                    sd.`sale_invoice_id` = '".$sale_invoice_id."' 
                                                    AND sd.`item` = '".$_POST['item'][$i]."' 
                                                    AND sd.`size` = '".$_POST['size'][$i]."'");
				    if(DB::count() > 0){
				        
				        $qty = $is_exist['qty'];
				        $qty =  (int)$_POST['qty'][$i]+(int)$qty;
				        $total_cost =  $_POST['cost'][$i]*$qty;
				        $total = $_POST['total'][$i]*$qty;
				        $sd_id = $is_exist['sale_invoice_detail_id'];
				        DB::update("sale_invoice_detail", array(
				            'qty'           => $qty,
				            'total_cost'    => $total_cost,
				            'total'	        => $total
				        ), 'sale_invoice_detail_id=%s', $sd_id);
				        
				    } else {
				      
				    $total_cost =  $_POST['cost'][$i]*(int)$_POST['qty'][$i];
					$insert = DB::Insert('sale_invoice_detail',
								array(
										'sale_invoice_id'	=> $sale_invoice_id,
										'item'				=> $_POST['item'][$i],
										'description'		=> $_POST['description'][$i],
								        'size'		        => $_POST['size'][$i],
										'qty'				=> $_POST['qty'][$i],
										'unit_price'		=> $_POST['rate'][$i],
								        'unit_cost'		    => $_POST['cost'][$i],
								        'total_cost'		=> $total_cost,
										'total'				=> $_POST['total'][$i],
										'last_sold'			=> $now
									));
					}
					
					
					/*
					 * UPDATE STOCK
					 */
					
					$u_sql = "UPDATE products_price pp SET 
                    pp.`stock` = (pp.`stock` - ".(int)$_POST['qty'][$i].") 
                    WHERE pp.`products_id` = '".(int)$_POST['item'][$i]."' 
                    AND pp.`size` = '".$_POST['size'][$i]."'";
					
					DB::query($u_sql);
					
					
				}
				

	}
	
	$tender = strtolower(trim($_POST['tender']));
	if($tender=='card'){
		
		/*
		 $card_no = $_POST['card_no1'].'-'.$_POST['card_no2'].'-'.$_POST['card_no3'].'-'.$_POST['card_no4'];
		 $expiry_date = $_POST['expiry_date1'].'/'.$_POST['expiry_date2'];
		 */
		
		
		DB::delete('cards_info', "sales_invoice_id=%s", $sale_invoice_id);
		
		$card_type = trim($_POST['card_type']);
		
		$update= DB::insert('cards_info',
				array(
						'sales_invoice_id'	=> $sale_invoice_id,
						'card_type'			=> $card_type,
						'modified_on'		=> $now,
						'modified_by'		=> $_SESSION['user_name']
				));
		
		
		
	}
	
	
	
	
	
	
	
	if($insert){
		$message = '<div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-check"></i>Saved!</h4>
                Sale Invoice saved successfully.
              </div>';

	} 
}





//fetching data..





?>

<style>
.form-control[disabled], .form-control[readonly], fieldset[disabled] .form-control {
    background-color: rgba(76, 136, 247, 0.05);
	color: #000;
}

.table-striped > tbody > tr:nth-of-type(2n+1) {
       /* background-color: #f9fbbd; */
}
.table-striped > tbody > tr>td>.form-control:nth-of-type(2n+1) {
        /* background-color: #fdfee9; */
}
td, th {
    padding: 0px 0px!important;

}


.credit-card-div  span {
    padding-top:10px;
        }
.credit-card-div img {
    padding-top:30px;
}
.credit-card-div .small-font {
    font-size:9px;
}
.credit-card-div .pad-adjust {
    padding-top:10px;
}
span#subTotal3 {
    font-size: 22px;
    padding: 2px;
}
</style>
<!-- Content Header (Page header) -->
        <section class="content-header">
        
          <ol class="breadcrumb">
            <li><a href="<?php echo SITE_ROOT; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Sales</a></li>
            <li class="active">Edit Sale Invoice</li>
          </ol>
        </section>
        <!-- Main content -->
        <section >
          <!-- title row -->
          <div class="box">
             <div class="box-header with-border">
              <h3 class="box-title">Edit Sale Invoice</h3><small></small>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
			
	
	
	
	
<div class="box-body">
	<div class="col-lg-5 col-lg-offset-4">
				<?php echo @$message; ?>
	 </div>
	 
	 
	<div class="row form-group">
        <div class="col-xs-12">
            <ul class="nav nav-pills nav-justified thumbnail setup-panel">
                <li class="active" id="li-step1"><a href="#step-1">
                    <h4 class="list-group-item-heading">Invoice</h4>
                  
                </a></li>
                <li class="disabled" id="li-step2"><a href="#step-2">
                    <h4 class="list-group-item-heading">Payment</h4>
                   
                </a></li>
                <li class="disabled" id="li-step3"><a href="#step-3">
                    <h4 class="list-group-item-heading">Card Information</h4>
                  
                </a></li>
            </ul>
        </div>
	</div>	
		
  <form method="POST" action="" role="form" name="frmInvoice" id="frmInvoice" onkeypress="return event.keyCode != 13;">
		
		<!--  step-1 -->
		<div class="setup-content" id="step-1">
		          <div class="row ">
            <div class="col-sm-4">
			 <b>SmokinFranks<br/>
			 USA.</b><br/>
               
				
				<div class="form-inline invoicBootLegger"><strong>Customer</strong>
				<select name="customer" id="customer" class="form-control" required="required">
					<option value="Walk-in" <?php if($customer=='Walk-in') { echo 'SELECTED'; } ?>>Walk-in</option>
					<option value="Instagram" <?php if($customer=='Instagram') { echo 'SELECTED'; } ?>>Instagram</option>
				</select>
				 <!-- <a class="" href="#" data-toggle="modal" data-target="#customerModalAdd">Add New</a> --> </div>
            </div><!-- /.col -->
			<div class="col-sm-4" style="text-align:center;">
				
            </div><!-- /.col -->
			<div class="col-sm-4">
				<strong>Invoice#: </strong> <?php
				echo $sale_invoice_id;
				?>
				<input type="hidden" name="sale_no" value="<?php echo $sale_invoice_id; ?>">
				<br/>
				
			
				<strong>Date: </strong><input type="tel" name="date" class="date-picker" value="<?php echo $date; ?>" autocomplete="off" ><br/>
				<!-- <br/> <strong>Reciept Type: </strong>
				<select name="reciept_type" id="reciept_type" class="">
					<option value="sale">Sale</option>
					<!-- <option value="return">Return Sale</option>
					<option value="lost">Lost Sale</option> 
				</select>
				
				<br/>
                                <div class="invoicBootLegger">
				
				<input type="checkbox" value='1' name="is_delivered" CHECKED><strong> Delivered </strong><br/>
				<input type="checkbox" value='1' name="is_paid" CHECKED><strong> Paid </strong>
                                </div>  -->
            </div><!-- /.col -->

          </div><!-- /.row -->



<h1 style="font-family: cursive;text-align:center;">SMOKESHOP</h1> 
   


      	<div class='row'>
      		<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>
      			<table class="table table-bordered table-hover table-striped">
					<thead>
						<tr>
							<th width="2%"><input id="check_all" class="formcontrol" type="checkbox"/></th>
							<th width="10%"><label>Item</label></th>
							<th><label>Description</label></th>
							<th width="10%"><label>Size</label></th>
							<th width="10%"><label>Qty</label></th>
							<th width="10%"><label>Unit Price</label></th>
							<th width="10%"><label>Sub Total</label></th>
						</tr>
					</thead>
					<tbody>
						
<?php 
						
						//fetching invoice details
						
						$invoice_details = DB::query("SELECT * FROM sale_invoice_detail sd WHERE sd.`sale_invoice_id`='".$sale_invoice_id."'");
						$i = 1;
						foreach($invoice_details as $invoice_detail){
						
						?>


						<tr>
							<td><input class="" tabindex="-1" type="checkbox"/></td>
							<td><input type="tel" autofocus class="form-control item_code" name="item[]" <?php echo $invoice_detail['item']; ?>" id="item_<?php echo $i; ?>" onkeypress="return IsNumeric(event);" value="<?php echo $invoice_detail['item']; ?>" required="required" autocomplete="false" onfocus="this.select();">

								<input type="hidden" value="<?php echo $invoice_detail['item']; ?>"   name="pre_item[]" id="pre_item_<?php echo $i; ?>" >
							</td>
							<input type="hidden" name="rows[]"/>
							<input type="hidden" name="cost[]" id="cost_1" value="0"/>
							<td><input class="form-control description"  data-toggle="modal" data-target="#productModal" readonly="true" tabindex="-1" name="description[]" value="<?php echo $invoice_detail['description']; ?>" id="description_<?php echo $i; ?>" type="text" placeholder="Description" required /></td>
							<td><input class="form-control size" value="<?php echo $invoice_detail['size']; ?>" readonly="true" tabindex="-1" name="size[]" id="size_<?php echo $i; ?>" type="text" placeholder="Size"/></td>
							<td><input type="tel" class="form-control changesNo qty" name="qty[]" id="qty_<?php echo $i; ?>" value="<?php echo $invoice_detail['qty']; ?>" onkeypress="return IsNumeric(event);" required onfocus="this.select();" required="required" autocomplete="false"></td>
							<td><input class="form-control changesNo" value="<?php echo $invoice_detail['unit_price']; ?>" name="rate[]" tabindex="-1" id="rate_<?php echo $i; ?>" type="tel" placeholder="Rate" autocomplete="off" onkeypress="return IsNumeric(event);" onfocus="this.select();" required/></td>
							<td><input type="tel" value="<?php echo $invoice_detail['total']; ?>" name="total[]" id="total_<?php echo $i; ?>" class="form-control totalLinePrice" autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" readonly="true" tabindex="-1" required /></td>

						</tr>

					<?php

					 $i++;

					 } ?>

					</tbody>
				</table>
      		</div>
      	</div>

		<div class='col-xs-5 col-xs-offset-4 pull-right'>
			<div class="form-inline">
				<div class="col-xs-2 form-group"><b>Total Qty: </b><input type="text" value="<?php echo $total_qty; ?>" id="total_qty" name="total_qty" readonly="true" tabindex="-1" class="" size="10"></div>
				<div class="col-xs-4 pull-right form-group"> <b>Sub Total: </b><input type="hidden" id="s_total" name="s_total"><input id="sub_total" name="sub_total" value="<?php echo $sub_total; ?>" type="tel" readonly="true" tabindex="-1" class="" size="17" style="font-size: 16px;font-weight: bold;" ></div>
			</div>	
		</div>
		<div class='col-xs-5 col-xs-offset-4 pull-right'>
			<div class="form-inline">
				<div class="col-xs-2 form-group"><b>No. Items: </b><input type="text" id="count_item" name="count_item" value="<?php echo $no_of_item; ?>" readonly="true" tabindex="-1" class="" size="10"></div>
					<div class="col-xs-2 pull-right form-group"><b>&nbsp;</b><input id="s_total2" name="s_total2" value="<?php echo $dis_amount; ?>" type="text" readonly="true" tabindex="-1" class="" size="12"></div>
				<div class="col-xs-2 pull-right form-group"><b>Dis % </b><input type="text" value="<?php echo $dis_perc; ?>" id="disc" name="disc" class="changesNo" size="10" maxlength="3" maximum="100" onfocus="this.select();"></div>
			</div>	 
		</div>
		<div class='col-xs-5 col-xs-offset-4 pull-right'>
			<div class="form-inline">
				<div class="col-xs-2 pull-right form-group"><b>&nbsp;</b><input id="s_total3" name="s_total3" value="<?php echo $tax_amount; ?>" type="text" readonly="true" tabindex="-1" class="" size="12"></div>
				<div class="col-xs-2 pull-right form-group"><b>Tax % </b><input type="text" value="<?php echo $tax_perc; ?>" id="tax" name="tax" class="changesNo" size="10" maxlength="3" maximum="100" onfocus="this.select();"></div>
			</div>	 
		</div>
		<!-- 
		<div class='col-xs-5 col-xs-offset-4 pull-right'>
			<div class="form-inline">
				<div class="col-xs-2 pull-right form-group changesNo"><b>Shipping </b><input type="tel" name="shipping" id="shipping" value="0.00" class="" size="12" maxlength="5" onfocus="this.select();"></div>
			</div>
		</div> -->
      	<div class='row'>
      		<div class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>
      			<button class="btn btn-default delete" type="button" tabindex="-1">- Delete</button>
      			<button class="btn btn-default addmore" type="button" tabindex="-1">+ Add Row</button>
      	
						<div class="form-group">
									<label>commnent1</label>
									<input type="text" class="form-control" value="<?php echo $comment1; ?>" maxlength="30" name="comment1" id="comment1"/>
						</div>
						<div class="form-group">
									<label>commnent2</label>
									<input type="text" class="form-control" value="<?php echo $comment2; ?>" maxlength="30" name="comment2" id="comment2" />
						</div>
					</div>
		

			<div class='col-xs-3 pull-right'>

			<div class="form-group pull-right">
						<label>Total USD: &nbsp;</label>
						
						<div class="input-group">

							<input type="tel" class="form-control" name="subTotal" id="subTotal" value="<?php echo $total_amount; ?>" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;"readonly="true" tabindex="-1" style="background: black;color: red;font-size: 29px;text-align: right;">
						</div>
				
			</div>
			</div>
			<div class="row">
				<div class='col-xs-6 pull-right'>
				
					<div class='col-xs-2 pull-right'>
					<a href="#" id="activate-step-2" class="btn btn-primary btn-lg pull-right btn-flat"> NEXT <i class="fa fa-chevron-right" aria-hidden="true"></i></a>
					&nbsp;&nbsp;&nbsp;</div><div class='col-xs-2 pull-right'>
					<a class="btn btn-warning btn-lg pull-right btn-flat" href="?route=modules/sale/sale_invoice_view&invoice_id=<?php echo $sale_invoice_id; ?>"><i class="glyphicon glyphicon-chevron-left"></i>&nbsp;Cancel</a>
					</div>
				
				</div>
			</div>

			
		</div>

		<script>
$(function(){
	tender_selector(<?php echo $tender; ?>);
})l

</script>

	</div> <!-- //end step-1 -->
		 <div class="row">
        <div class="col-xs-12">
            <div class="col-md-12 well setup-content" id="step-2">
                <h1 class="text-center">TENDER</h1>
				<div class="col-md-6 col-md-offset-3">
					<div class="form-group input-group-lg">
							<label>Total USD: &nbsp;</label>
								<input type="tel" class="form-control"  id="subTotal2" placeholder="0.00" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" readonly="true" tabindex="-1">
					</div>

					<div class="">

						<div class="radio">
							<label><input type="radio" class="tender" value="cash" <?php if($tender=='cash') { echo 'CHECKED'; } ?> name="tender" id="cash" checked/>
							Cash</label>
						</div>
						<div class="radio">
							<label><input type="radio" class="tender" <?php if($tender=='card') { echo 'CHECKED'; } ?> value="card" name="tender" id="card"/>
							Card</label>
						</div>
						<div class="radio">
							<label><input type="radio" class="tender" <?php if($tender=='venmo') { echo 'CHECKED'; }  ?> value="venmo" name="tender" id="venmo"/>
							Venmo</label>
						</div>
						<div class="radio">
							<label><input type="radio" class="tender" <?php if($tender=='paypal') { echo 'CHECKED'; }  ?> value="paypal" name="tender" id="paypal"/>
							Paypal</label>
						</div>
						<div class="radio">
							<label><input type="radio" class="tender" <?php if($tender=='cashapp') { echo 'CHECKED'; }  ?> value="cashapp" name="tender" id="cashapp"/>
							Cashapp</label>
						</div>
						<!-- <div class="radio">
							<label><input type="radio" class="tender" value="gift" name="tender" id="gift" />
							Gift</label>
						</div> 
						
						<div class="radio">
							<label for="split"><input type="radio" class="tender" value="split" name="tender" id="split" />
							Split</label>
						</div>-->

					</div>
<input class="form-control" name="customer_phone" value="<?php echo $customer_phone; ?>" id="customer_phone" type="text" placeholder="Customer Phone" autocomplete="no" maxlength="10">
<small class="text-muted">*Customer phone number without +1</small>
<input class="form-control" name="customer_email" value="<?php echo $customer_email; ?>" id="customer_email" type="email" placeholder="Customer Email" autocomplete="no">
<br/>
<?php
$instagram_id = DB::queryFirstField("SELECT ip.`instagram_id` FROM instagram_pos ip WHERE ip.`sale_invoice_id` = '".$sale_invoice_id."'"); 
?>
<input class="form-control" name="instagram_id" id="instagram_id" value="<?php echo $instagram_id; ?>" type="text" placeholder="Instagram UserID" autocomplete="no">
	<label class="radio-inline"><input type="radio" value="local" name="customer_type" <?php if($customer_type=='local') { echo 'CHECKED'; }  ?>>Local</label>
<label class="radio-inline"><input type="radio" value="visitor" name="customer_type" <?php if($customer_type=='visitor') { echo 'CHECKED'; }  ?>>Visitor</label>
<br/>
					<a href="#" style="display:none;" id="activate-step-3" class="btn btn-primary btn-lg pull-right btn-flat"> NEXT <i class="fa fa-chevron-right" aria-hidden="true"></i> </a>

					<div class="input-group">
						
	                	<span class="input-group-btn">
							<button type="submit" class="btn btn-success btn-lg btn-flat pull-right" name="save" id="btnSaveInvoice" value="Save">Update Sale &nbsp; <i class="glyphicon glyphicon-floppy-disk"></i></button>
						</span>
					</div>
					
				</div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="col-md-12 well setup-content" id="step-3">

<?php

$card_type = DB::queryFirstField("SELECT ci.`card_type` FROM cards_info ci WHERE ci.`sales_invoice_id`='".(int)$sale_invoice_id."'");

?>


					 <div class="row">
						   <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="credit-card-div">
                <div class="panel panel-default">
                    <div class="panel-heading">
		
						<br/> 
					<div class="bootstrapradio">
						<div class="bootstrapradio-info">
							<input type="radio" class="" value="visa" name="card_type" id="visa" <?php if($card_type=='visa') { echo 'CHECKED'; } ?> >
							<label for="visa">VISA</label>
						</div>
						<div class="bootstrapradio-info">
							<input type="radio" class="" value="master" name="card_type" id="master"<?php if($card_type=='master') { echo 'CHECKED'; } ?> >
							<label for="master">MASTER</label>
						</div>
						<div class="bootstrapradio-primary">
							<input type="radio" class="" value="amex" name="card_type" id="amex" <?php if($card_type=='amex') { echo 'CHECKED'; } ?>>
							<label for="amex">AMEX</label>
						</div>
						
						
                        <div class="row ">
							<ul class="nav nav-pills nav-stacked">
								<li class="active"><a style="background-color:#3c8dbc;color:#FFF;" href="#"><span id="subTotal3" class="badge pull-right">USD: 0.00</span> Invoice Total</a>
								</li>
							</ul>
						</div>
						
						
						
					</div>
                        <div class="row ">
                            <div class="col-md-6 col-sm-6 col-xs-6 pad-adjust">

                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-6 pad-adjust">
                               <button type="submit" disabled="true" style="display:none;" class="btn btn-success pull-right btn-lg btn-flat" name="save" id="btnSaveInvoice2" value="Save">Complete Sale&nbsp; <i class="glyphicon glyphicon-floppy-disk"></i></button>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <!-- CREDIT CARD DIV END -->
        </div>
    </div>
					</div>



            </div>
        </div>
    </div>
    
    
    
    
    <!-- Split Payment -->

<div class="modal fade" id="splitPayment" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabelAdd">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Split Payment Method</h4>
      </div>
      <div class="modal-body">
      	<div class="row">
    		<h4 id="modalMsg"></h4>
    			<div class="form-group">
    			<label class="col-sm-4 control-label"><strong>Total Tender:</strong></label>
    			<div class="col-sm-8">
    			  <input type="tel" class="form-control" readonly="true" value="" placeholder="0.00" name="split_total" id="split_total" maxlength="50" onfocus="this.select();" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" autocomplete="false">
    			</div>
    		  </div>
    		  
    		
           	<div class="form-group">
    			<label class="col-sm-4 control-label"><strong>Cash:</strong></label>
    			<div class="col-sm-8">
    			  <input type="tel" class="form-control split_payment" placeholder="0.00" name="split_cash" id="split_cash" maxlength="50" onfocus="this.select();" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" autocomplete="false">
    			</div>
    		  </div>
    		  
    		  <div class="form-group">
    				<div class="col-sm-4">
    				<select class="col-sm-4 form-control" name="split_card" id="split_card">
    					<option value="visa">VISA</option>
    					<option value="master">MASTER</option>
    					<option value="amex">AMEX</option>
    				</select>
    				</div>
    			<div class="col-sm-8">
    			  <input type="tel" class="form-control split_payment" placeholder="0.00" name="split_card_amount" id="split_card_amount" maxlength="50" onfocus="this.select();" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" autocomplete="false">
    			</div>
    		  </div>
    		  
    		</div>
		</div>
      <div class="modal-footer">
		<button type="submit" class="btn btn-success pull-right btn-lg btn-flat" name="save" id="saveSplit">Complete Sale&nbsp; <i class="glyphicon glyphicon-floppy-disk"></i></button>
      </div>
    </div>

  </div>
</div> 





    <!-- Split Gift -->

<div class="modal fade" id="splitGift" tabindex="-1" role="dialog" aria-labelledby="giftModalLabelAdd">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Gift </h4>
      </div>
      <div class="modal-body">
      	<div class="row">
    		<h4 id="modalMsg"></h4>
    			<div class="form-group">
    			<label class="col-sm-4 control-label"><strong>Total Tender:</strong></label>
    			<div class="col-sm-8">
    			  <input type="tel" class="form-control" readonly="true" value="" placeholder="0.00" name="split_gift_total" id="split_gift_total" maxlength="50" onfocus="this.select();" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" autocomplete="false">
    			</div>
    		  </div>
    		  
    		 
    		<div class="form-group">
    			<label class="col-sm-4 control-label"><strong>Gift:</strong></label>
    			<div class="col-sm-8">
    			  <input type="tel" class="form-control split_gift" placeholder="0.00" name="split_gift" id="split_gift" maxlength="50" onfocus="this.select();" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" autocomplete="false">
    			</div>
    		  </div>
    		
           	<div class="form-group">
    			<label class="col-sm-4 control-label"><strong>Cash:</strong></label>
    			<div class="col-sm-8">
    			  <input type="tel" class="form-control split_gift" placeholder="0.00" name="split_gift_cash" id="split_gift_cash" maxlength="50" onfocus="this.select();" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" autocomplete="false">
    			</div>
    		  </div>
    		  
    		 
    		  
    		</div>
		</div>
      <div class="modal-footer">
		<button type="submit" class="btn btn-success pull-right btn-lg btn-flat" name="save" id="saveSplitGift">Complete Sale&nbsp; <i class="glyphicon glyphicon-floppy-disk"></i></button>
      </div>
    </div>

  </div>
</div> 



    
    


</form>

</div><!-- /.box-body -->
            <div class="box-footer">
             <small></small>
            </div><!-- /.box-footer-->
          </div><!-- /.box -->

     	 </section><!-- /.content -->





<!-- Modal -->
<div id="productModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Find Product</h4>
      </div>
      <form class="form-horizontal" action="">
      <div class="modal-body">
        
  <div class="form-group">
    <label class="control-label col-sm-2" for="products_name">Name:</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" onfocus="this.select()" id="products_name" placeholder="Search..">
      <input type="hidden" value="1" id="proIndex">
      <input type="hidden" value="" id="proID">
      <div id="ListingDiv">
      <ul id="productListing">
      
      </ul>
      </div>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="products_size">Size:</label>
    <div class="col-sm-10"> 
      <div id="products_size">
      	
      </div>
    </div>
  </div>
  
 

      </div>
      <div class="modal-footer">
      <button type="button" id="btnProductOK" class="btn btn-success">OK</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
      </div>
      </form>
    </div>

  </div>
</div>






<script>

$(function(){
		$('.alert').delay(1000).hide('slow',function(){
			location.href='?route=modules/sale/sale_invoice_view&invoice_id=<?php echo $sale_invoice_id; ?>';
		});
});
</script>




<script type="text/javascript" src="sale_invoice.js"></script>



<script>

$(function(){

	$(document).on('click', '.description', function(){
		$("#products_name").val('');
		$("#products_size").html('');
		i_ar = $(this).attr('id');
		i = i_ar.split("_");
		$("#proIndex").val(i[1]);
		//$("#products_name").attr('data-ind',i[1]);
	});
	
	$("#products_name").on("keypress keyup keydown", function(){
		if($(this).val() !=''){
		$.ajax({
			method: 'GET',
			url:  'ajax_products_listing.php',
			data: 'p_name='+$(this).val(),
			success: function(e){
					$("#productListing").html(e);
				}
		});	
		} else {
			$("#productListing").html('');
		}
	});

	$(document).on('click','.pListing',function(e){
		$("#products_name").val($(this).data('pdescr'));
		/*$("#products_name").attr('data-pid',$(this).data('pid'));*/
		$("#proID").val($(this).data('pid'));
		$("#productListing").html('');
		$.ajax({
			method: 'GET',
			url:  'ajax_product_sizes.php',
			data: 'p_id='+$(this).data('pid'),
			success: function(f){
					$("#products_size").html(f);
				}
		});	
	});
});
</script>
<script>
$(function(){

	$("#btnProductOK").click(function(){
		i = $("#proIndex").val();
		
		$("#item_"+i).val($("#proID").val());
		
		$("#description_"+i).val($("#products_name").val());
		$("#size_"+i).val($('input[name="products_size"]:checked').val());
		$("#qty_"+i).val('1');
		$("#rate_"+i).val($('input[name="products_size"]:checked').data('sprice'));
		$("#cost_"+i).val($('input[name="products_size"]:checked').data('cost'));
		$('#productModal').modal('hide');
		j=parseInt(i)+parseInt(1);
		$("#qty_"+i).trigger( "change" );
		$("#item_"+j).focus();
		
		calculateSubTotal();
		count_qty();
        calculateTax();
        calculateDisc();
		count_items();
		count_qty();
		calculateTotal();
		addInvoiceRow();
		
		

	});
	
});

</script>


<script>


$(document).ready(function() {
	
	

  
		
		var navListItems = $('ul.setup-panel li a'),
        allWells = $('.setup-content');

    allWells.hide();

    navListItems.click(function(e)
    {
        e.preventDefault();
        var $target = $($(this).attr('href')),
            $item = $(this).closest('li');
        
        if (!$item.hasClass('disabled')) {
            navListItems.closest('li').removeClass('active');
            $item.addClass('active');
            allWells.hide();
            $target.show();
			am = $("#subTotal").val();
			$("#subTotal2").val(am);
			$("#subTotal3").html('USD: '+am);
        }
    });
	

		$("#step-1").show();
		
		
	
    $('#activate-step-2').on('click', function(e) {
		$("#li-step1").removeClass("active");
		$("#li-step2").addClass("active");
		$("#li-step2").removeClass('disabled');
		$('.setup-content').hide();
		$("#step-2").fadeIn("slow");
		am = $("#subTotal").val();
		$("#subTotal2").val(am);
    });

	$('#activate-step-3').on('click', function(e) {
		$("#li-step2").removeClass("active");
		$("#li-step3").addClass("active");
		$("#li-step3").removeClass('disabled');
		$('.setup-content').hide();
		$("#step-3").fadeIn("slow");
		tender_selector('card');

    }); 
	var tender = $('.tender:checked').val();
	tender_selector(tender);
	$(".tender").on("change",function(){
		var tender = $(this).val();
		tender_selector(tender);
	});
	
	
});



function tender_selector(tender){
	$("#li-step2").addClass("active");
	$("#li-step2").removeClass('disabled');
	if(tender=='card'){
			$("#li-step3").addClass("active");
			$("#li-step3").removeClass('disabled');
			$("#btnSaveInvoice").prop("disabled",true);
			$("#btnSaveInvoice").hide();
			$("#activate-step-3").show();
			$("#btnSaveInvoice2").prop("disabled",false);
			$("#btnSaveInvoice2").show();
			am = $("#subTotal").val();
			$("#subTotal3").html('USD: '+am);
			$("#subTotal2").val(am);
			$(".card-validation").each(function(){
				$(this).prop("required",true);
			});
			
			
		} else if(tender=='gift'){
			$("#subTotal2").val('0.00');
			$("#btnSaveInvoice").prop("disabled",false);
			$("#btnSaveInvoice").show();
			$("#activate-step-3").hide();
			$("#li-step3").removeClass("active");
			$("#li-step3").addClass('disabled');
			$(".card-validation").each(function(){
				$(this).prop("required",false);
			});
			
		} else {
			$("#btnSaveInvoice").prop("disabled",false);
			$("#btnSaveInvoice").show();
			$("#activate-step-3").hide();
			$("#li-step3").removeClass("active");
			$("#li-step3").addClass('disabled');
			am = $("#subTotal").val();
			$("#subTotal2").val(am);
			$(".card-validation").each(function(){
				$(this).prop("required",false);
			});
		}
}

</script>


