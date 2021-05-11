<?php

$invoice_id = '';

if(isset($_POST['btnSave'])){
    
    
    
   $date = date("Y-m-d", strtotime($_POST['date']));
   
    
   $sss = explode(';', $_POST['supplier']);
   $supplier_id = $sss[0];
   $supplier = $sss[1];
    
    
    $insert = DB::Insert('purchase_invoice',
        array(
            'date'				=> $date,
            'supplier'			=> $supplier,
            'supplier_id'		=> $supplier_id,
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
            'created_by'		=> $_SESSION['user_name'],
            'created_on'		=> $now,
            'tender'				=> 'cash'
            
        ));
    $invoice_id =DB::insertId();
    
   
    
    
    for($i=0, $iMaxSize=count($_POST['rows']); $i<$iMaxSize; $i++){
        if(trim($_POST['item'][$i])<>''){
            $insert = DB::Insert('purchase_invoice_detail',
                array(
                    'purchase_invoice_id'	=> $invoice_id,
                    'item'				=> $_POST['item'][$i],
                    'description'		=> $_POST['description'][$i],
                    'size'		        => $_POST['size'][$i],
                    'qty'				=> $_POST['qty'][$i],
                    'unit_price'		=> $_POST['rate'][$i],
                    'total'				=> $_POST['total'][$i],
                    'last_sold'			=> $now
                ));
        }
        
        
        /*
         * ADD STOCK & UPDATE COST PRICE
        
        
        $u_sql = "UPDATE products_price pp SET
                    pp.`stock` = (pp.`stock` + ".(int)$_POST['qty'][$i]."),
                    PP.`cost_price` = '".$_POST['rate'][$i]."'
                    WHERE pp.`products_id` = '".(int)$_POST['item'][$i]."'
                    AND pp.`size` = '".$_POST['size'][$i]."'";
        
        DB::query($u_sql);
         */
        
        
    }
    
    
    
    if($insert){
        $message = '<div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-check"></i>Saved!</h4>
                Purchase Invoice created successfully. Stock is not updated yet
              </div>';
        
    }
}
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


 .radio label {
    font-weight: bold;
    font-size: 16px;
}


</style>
<!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
           <?php //echo get_store_heading(); ?>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?php echo SITE_ROOT; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Purchase</a></li>
            <li class="active">Purchase Invoice</li>
          </ol>
        </section>
        <!-- Main content -->
        <section >
          <!-- title row -->
          <div class="box">
             <div class="box-header with-border">
              <h3 class="box-title">New Purchase Invoice</h3><small></small>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
              </div>
            </div>





<div class="box-body">
	<div class="col-lg-5 col-lg-offset-4">
				<?php echo @$message; ?>
	 </div>



  <form method="POST" action="" role="form" name="frmInvoice" id="frmInvoice" onkeypress="return event.keyCode != 13;">

          <div class="row ">
            <div class="col-sm-4">
			 <b>SmokinFranks<br/>
			 USA.</b><br/>
               
				
				<div class="form-inline invoicBootLegger"><strong>Supplier</strong>
				<select name="supplier" id="supplier" class="form-control" required="required">
					<?php 
					$suppliers = DB::query("SELECT m.`manufacturers_id`, m.`name` FROM manufacturers m ORDER BY m.`name`");
					foreach($suppliers as $supp){
					    echo '<option value="'.$supp['manufacturers_id'].';'.$supp['name'].'">'.$supp['name'].'</option>';
					}
					?>
				</select>
				 <!-- <a class="" href="#" data-toggle="modal" data-target="#customerModalAdd">Add New</a> --> </div>
            </div><!-- /.col -->
			<div class="col-sm-4" style="text-align:center;">
				
            </div><!-- /.col -->
			<div class="col-sm-4">
				
				
			
				<strong>Date: </strong><input type="tel" name="date" class="" readonly="true" value="<?php echo date('d-m-Y'); ?>" ><br/>
				
				<!-- 
				<br/>
                                <div class="invoicBootLegger">
				
				<input type="checkbox" value='1' name="is_delivered" CHECKED><strong> Delivered </strong><br/>
				<input type="checkbox" value='1' name="is_paid" CHECKED><strong> Paid </strong>
                                </div>  -->
            </div><!-- /.col -->

          </div><!-- /.row -->



<h1 style="text-align:center;">SMOKESHOP PURCHASE</h1>




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
                            <th width="10%"><label>Cost Price</label></th>
							<th width="10%"><label>Sub Total</label></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><input class="" tabindex="-1" type="checkbox"/></td>
							<td><input type="tel" autofocus class="form-control item_code" name="item[]" id="item_1" onkeypress="return IsNumeric(event);" required="required" autocomplete="false" onfocus="this.select();">
							</td>
							<input type="hidden" name="rows[]"/>
							<td><input class="form-control description"  data-toggle="modal" data-target="#productModal" readonly="true" tabindex="-1" name="description[]" id="description_1" type="text" placeholder="Description" required /></td>
							<td><input class="form-control size" readonly="true" tabindex="-1" name="size[]" id="size_1" type="text" placeholder="Size"/></td>
							<td><input type="tel" class="form-control changesNo qty" name="qty[]" id="qty_1" onkeypress="return IsNumeric(event);" required onfocus="this.select();" required="required" autocomplete="false"></td>
                            <td><input class="form-control changesNo" name="rate[]" tabindex="-1" id="rate_1" type="tel" placeholder="Rate" autocomplete="off" onkeypress="return IsNumeric(event);" required/></td>
							<td><input type="tel" name="total[]" id="total_1" class="form-control totalLinePrice" autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" readonly="true" tabindex="-1" required /></td>

						</tr>
					</tbody>
				</table>
      		</div>
      	</div>

		<div class='col-xs-5 col-xs-offset-4 pull-right'>
			<div class="form-inline">
				<div class="col-xs-2 form-group"><b>Total Qty: </b><input type="tel" id="total_qty" name="total_qty" value="0" readonly="true" tabindex="-1" class="" size="6"></div>
				<div class="col-xs-4 pull-right form-group"> <b>Sub Total: </b><input type="hidden" id="s_total" name="s_total"><input id="sub_total" name="sub_total" value="0.00" type="tel" readonly="true" tabindex="-1" class="" size="17" style="font-size: 16px;font-weight: bold;" ></div>
			</div>
		</div>
		<div class='col-xs-5 col-xs-offset-4 pull-right'>
			<div class="form-inline">
				<div class="col-xs-2 form-group"><b>No. Items: </b><input type="tel" id="count_item" name="count_item" value="0" readonly="true" tabindex="-1" class="" size="6"></div>
					<div class="col-xs-2 pull-right form-group"><b>&nbsp;</b><input id="s_total2" name="s_total2" value="0.00" type="tel" readonly="true" tabindex="-1" class="" size="12"></div>
				<div class="col-xs-2 pull-right form-group"><b>Dis % </b><input type="tel" value="0" id="disc" name="disc" class="" size="5" maxlength="3" maximum="100" onfocus="this.select();"></div>
			</div>
		</div>
		<div class='col-xs-5 col-xs-offset-4 pull-right'>
			<div class="form-inline">
				<div class="col-xs-2 pull-right form-group"><b>&nbsp;</b><input id="s_total3" name="s_total3" value="0.00" type="tel" readonly="true" tabindex="-1" class="" size="12"></div>
				<div class="col-xs-2 pull-right form-group"><b>Tax % </b><input type="tel" value="0" id="tax" name="tax" class="" size="5" maxlength="3" maximum="100" onfocus="this.select();" ></div>
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
									<input type="text" class="form-control" maxlength="30" name="comment1" id="comment1"/>
						</div>
						<div class="form-group">
									<label>commnent2</label>
									<input type="text" class="form-control" maxlength="30" name="comment2" id="comment2" />
						</div>
					</div>


			<div class='col-xs-3 pull-right'>

			<div class="form-group pull-right">
						<label>Total USD: &nbsp;</label>
						<div class="input-group">

							<input type="tel" class="form-control" name="subTotal" id="subTotal" placeholder="0.00" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;"readonly="true" tabindex="-1" style="background: black;color: red;font-size: 29px;text-align: right;">
						</div>
						<br>
			<button type="submit" id="btnSave" name="btnSave" class="btn btn-success btn-lg pull-right btn-flat"> SAVE <i class="fa fa-flopy" aria-hidden="true"></i></button>
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
			
			location.href='?route=modules/purchase/purchase_invoice_view&invoice_id=<?php echo $invoice_id; ?>';
		
		});
});
</script>





<script type="text/javascript" src="purchase_invoice.js?v=1.2"></script>


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
                $("#qtyy_"+i).val('1');
		$("#rate_"+i).val($('input[name="products_size"]:checked').data('cost'));
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

$(document).ready(function () {
    $(window).on('beforeunload', function(){
		if($(".item_code").val()!=''){
		return "You have unsaved changes!";
		}

    });
    $(document).on("submit", "form", function(event){
        $(window).off('beforeunload');
    });
});


</script>






