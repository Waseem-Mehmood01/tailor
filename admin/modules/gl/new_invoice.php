<?php

$quote_card_info_id = isset($_GET['leads_id'])?(int)trim($_GET['leads_id']):'';
$customer = isset($_GET['customer'])?trim($_GET['customer']):'';
$email = isset($_GET['email'])?trim($_GET['email']):'';
$address = isset($_GET['address'])?trim($_GET['address']):'';
$contact = isset($_GET['phone'])?trim($_GET['phone']):'';

if( checkInvoiceWithLead($quote_card_info_id) > 0){
  echo '<div class="alert alert-danger">
  <strong>Invoice already created of this lead! </strong> <a href="?route=modules/gl/sale_invoice_view&invoice_id='.checkInvoiceWithLead($quote_card_info_id).'">View Invoice</a>.
</div>';
  die();
}

$invoice_id = '';

if(isset($_POST['btnCompleteInvoice'])){
    
   $invoice_id = saveInvoice($_POST, 'Paid');

           if(isset($_FILES['final_design']) AND is_uploaded_file($_FILES['final_design']['tmp_name'])){
        
    $handle = new Upload($_FILES['final_design']);
    $handle->file_max_size = '4000000';
    $handle->file_overwrite = true;
    $customer_id = DB::queryFirstField("SELECT qc.`quote_customers_id` FROM quote_customers qc WHERE qc.`quote_card_info_id` = '".$_POST['quote_card_info_id']."'");
    if($customer_id <> ''){
    $dir = '../uploads/customer_'.$customer_id;
        if (!file_exists($dir)) {
          mkdir($dir, 0777, true);
    }
    $handle->process($dir.'/');
     if ($handle->processed) {
                $handle->clean();
               // $_SESSION['errors'] = '';
                $msg = 'Upload Success';

                DB::update('sale_invoice', array('final_design'=> $_FILES['final_design']['name']), 'sale_invoice_id=%s', $invoice_id);

            } else {
                 $msg = $handle->error;

            }
        
      } else {
        $msg = 'Design can not upload';
      }   
    }
    $message = '<div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-check"></i>Saved!</h4>
                Sale Invoice saved successfully as Paid. '.@$msg.'
              </div>';

} elseif(isset($_POST['btnSaveDraft'])){
    
   $invoice_id =  saveInvoice($_POST, 'Draft');
           if(isset($_FILES['final_design']) AND is_uploaded_file($_FILES['final_design']['tmp_name'])){
        
    $handle = new Upload($_FILES['final_design']);
    $handle->file_max_size = '4000000';
    $handle->file_overwrite = true;
    $customer_id = DB::queryFirstField("SELECT qc.`quote_customers_id` FROM quote_customers qc WHERE qc.`quote_card_info_id` = '".$_POST['quote_card_info_id']."'");
    if($customer_id <> ''){
    $dir = '../uploads/customer_'.$customer_id;
        if (!file_exists($dir)) {
          mkdir($dir, 0777, true);
    }
    $handle->process($dir.'/');
     if ($handle->processed) {
                $handle->clean();
               // $_SESSION['errors'] = '';
                $msg = 'Upload Success';

                DB::update('sale_invoice', array('final_design'=> $_FILES['final_design']['name']), 'sale_invoice_id=%s', $invoice_id);

            } else {
                 $msg = $handle->error;

            }
        
      } else {
        $msg = 'Design can not upload';
      }   
    }
    $message = '<div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-check"></i>Saved!</h4>
                Sale Invoice saved successfully as Draft. '.@$msg.'
              </div>';

} elseif(isset($_POST['btnSaveDraftEmail'])){
    
   $invoice_id =  saveInvoice($_POST, 'Pending');
    
    if($_POST['customer_email'] <> ''){
      sendBillingInvoice($invoice_id, $_POST['customer_email'], $_POST['subTotal']);
    }
    

       if(isset($_FILES['final_design']) AND is_uploaded_file($_FILES['final_design']['tmp_name'])){
        
    $handle = new Upload($_FILES['final_design']);
    $handle->file_max_size = '4000000';
    $handle->file_overwrite = true;
    $customer_id = DB::queryFirstField("SELECT qc.`quote_customers_id` FROM quote_customers qc WHERE qc.`quote_card_info_id` = '".$_POST['quote_card_info_id']."'");
    if($customer_id <> ''){
    $dir = '../uploads/customer_'.$customer_id;
        if (!file_exists($dir)) {
          mkdir($dir, 0777, true);
    }
    $handle->process($dir.'/');
     if ($handle->processed) {
                $handle->clean();
               // $_SESSION['errors'] = '';
                $msg = 'Upload Success';

                DB::update('sale_invoice', array('final_design'=> $_FILES['final_design']['name']), 'sale_invoice_id=%s', $invoice_id);

            } else {
                 $msg = $handle->error;

            }
        
      } else {
        $msg = 'Design can not upload';
      }   
    }

    $message = '<div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-check"></i>Saved!</h4>
                Sale Invoice saved successfully. '.@$msg.'
              </div>';

} else {

}





function saveInvoice($data, $reciept_type){

  $_POST = $data;


  $date = date("Y-m-d", strtotime($_POST['date']));

  $now = date('Y-m-d G:i:s');


if( checkInvoiceWithLead($_POST['quote_card_info_id']) > 0){
  echo '<div class="alert alert-danger">
  <strong>Invoice already created of this lead! </strong> <a href="?route=modules/gl/sale_invoice_view&invoice_id='.checkInvoiceWithLead($_POST['quote_card_info_id']).'">View Invoice</a>.
</div>';
  die();
}


  $insert = DB::Insert('sale_invoice',
                array(
                  'date'        => $date,
                  'customer'      => $_POST['customer'],
                  'quote_card_info_id'   => (int)$_POST['quote_card_info_id'],
                  'total_qty'     => $_POST['total_qty'],
                  'no_of_item'    => $_POST['count_item'],
                  'sub_total'     => $_POST['sub_total'],
                  'dis_perc'      => $_POST['disc'],
                  'dis_amount'    => $_POST['s_total2'],
                  'tax_perc'      => $_POST['tax'],
                  'tax_amount'    => $_POST['s_total3'],
                  'total_amount'    => $_POST['subTotal'],
                  'comment1'      => $_POST['comment1'],
                  'comment2'      => $_POST['comment2'],
                  'created_by'    => $_SESSION['user_name'],
                  'created_on'    => $now,
                  'reciept_type'    => $reciept_type,
                  'tender'        => @$_POST['tender'],
                  'tender_amount'   => $_POST['subTotal'],
                  'due_amount'    => $_POST['due_amount'],
                  'customer_phone'    => $_POST['customer_phone'],
                  'customer_email'    => $_POST['customer_email'],
                  'customer_type'   => 'Web',
                  'address'    => $_POST['address'],
                  'city'    => $_POST['city'],
                  'state'    => $_POST['state']
                  
                  
                  ));
  $invoice_id =DB::insertId();

  
  for($i=0, $iMaxSize=count($_POST['rows']); $i<$iMaxSize; $i++){
        if(trim($_POST['item'][$i])<>''){
           
            /*
             * if already exist update quantity
             */

            /*let size as zero for this case */


            $_POST['size'][$i] = 0;

            $is_exist = DB::queryFirstRow("SELECT sd.`qty`,  sd.`sale_invoice_detail_id` FROM sale_invoice_detail sd WHERE 
                                                    sd.`sale_invoice_id` = '".$invoice_id."' 
                                                    AND sd.`item` = '".$_POST['item'][$i]."' 
                                                    AND sd.`size` = '".$_POST['size'][$i]."'");
            if(DB::count() > 0){
                
                $qty = $is_exist['qty'];
                $qty =  (int)$_POST['qty'][$i]+(int)$qty;
                $total_cost =  $_POST['cost'][$i]*$qty;
                $total = $_POST['total'][$i];
                $sd_id = $is_exist['sale_invoice_detail_id'];
                DB::update("sale_invoice_detail", array(
                    'qty'           => $qty,
                    'total_cost'    => $total_cost,
                    'total'         => $total
                ), 'sale_invoice_detail_id=%s', $sd_id);
                
            } else {
              
            $total_cost =  $_POST['cost'][$i]*(int)$_POST['qty'][$i];
          $insert = DB::Insert('sale_invoice_detail',
                array(
                    'sale_invoice_id' => $invoice_id,
                    'item'        => $_POST['item'][$i],
                    'description'   => $_POST['description'][$i],
                        'size'            => $_POST['size'][$i],
                    'qty'       => $_POST['qty'][$i],
                    'unit_price'    => $_POST['rate'][$i],
                        'unit_cost'       => $_POST['cost'][$i],
                        'total_cost'    => $total_cost,
                    'total'       => $_POST['total'][$i],
                    'last_sold'     => $now
                  ));
          }
          
          
    
          
          
        }
        


  }

  

 
  
  
return $invoice_id;






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
            <li><a href="#">Sales</a></li>
            <li class="active">Sale Invoice</li>
          </ol>
        </section>
        <!-- Main content -->
        <section >
          <!-- title row -->
          <div class="box">
             <div class="box-header with-border">
              <h3 class="box-title">New Sale Invoice</h3><small></small>
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
                <li class="" id="li-step2"><a href="#step-2">
                    <h4 class="list-group-item-heading">Payment</h4>

                </a></li>
              
            </ul>
        </div>
  </div>

  <form method="POST" action="" enctype="multipart/form-data" role="form" name="frmInvoice" id="frmInvoice" onkeypress="return event.keyCode != 13;" >

    <!--  step-1 -->
    <div class="setup-content" id="step-1">
    <!-- info row -->
          <div class="row ">
            <div class="col-sm-4">
       <b>Luxury Metal Cards <br/>
17195 San Carlos Blvd <br/>
Fort Myers Beach Fl 33931<br/>
  </b><br/>
               
        
        <div class="form-inline invoicBootLegger"><strong>Name </strong>
        <input type="text" name="customer" id="customer" value="<?php echo $customer; ?>" class="form-control" required="required" placeholder="Name">
       <br/><strong>Contact </strong> <input class="form-control" name="customer_phone" id="customer_phone" type="text" placeholder="Customer Phone" value="<?php echo $contact; ?>" autocomplete="no" maxlength="10">

         <!-- <a class="" href="#" data-toggle="modal" data-target="#customerModalAdd">Add New</a> --> </div>
            </div><!-- /.col -->
      <div class="col-sm-4" style="text-align:center;">
        
            </div><!-- /.col -->
      <div class="col-sm-4">
        <strong>Invoice#: </strong> <?php
        echo get_sale_no();
        ?>
        <input type="hidden" name="sale_no" value="<?php echo get_sale_no(); ?>">
        <br/>
        
      
        <strong>Date: </strong><input type="tel" name="date" class="" readonly="true" value="<?php echo date('d-m-Y'); ?>" ><br/>
        <div class="form-inline invoicBootLegger"><strong>Lead ID#: </strong><input type="tel" name="quote_card_info_id" class="form-control" value="<?php echo @$quote_card_info_id; ?>" required>
<br/><label>Email: </label><input class="form-control" value="<?php echo $email; ?>" name="customer_email" id="customer_email" type="email" placeholder="Customer Email" autocomplete="no">
        </div>
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



<h1 style="font-family: cursive;text-align:center;">LuxuryMetalCards Invoice</h1> 




        <div class='row'>
          <div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>
            <table class="table table-bordered table-hover table-striped">
          <thead>
            <tr>
              <th width="2%"><input id="check_all" class="formcontrol" type="checkbox"/></th>
              <th width="20%"><label>Item</label></th>
              <th><label>Description</label></th>
               <th width="10%"><label>Qty</label></th>
              <th width="10%"><label>Unit Price</label></th>
              <th width="10%"><label>Line Total</label></th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><input class="" tabindex="-1" type="checkbox"/></td>
              <td><select class="form-control item_code" name="item[]" id="item_1" required="required">
                <?php
                $r = DB::query("SELECT name FROM categories ORDER BY name");
        foreach($r as $c){
            echo '<option value="' . $c['name'] . '">'. $c['name'] . '</option>';
          }
                ?>
              </select>
              </td>
              <input type="hidden" name="rows[]"/>
              <input type="hidden" name="cost[]" id="cost_1" value="0"/>
              <td><input class="form-control description"   tabindex="-1" name="description[]" id="description_1" type="text" placeholder="Description" required /></td>
             
              <td><input type="tel" class="form-control changesNo qty" name="qty[]" id="qty_1" onkeypress="return IsNumeric(event);" required onfocus="this.select();" required="required" autocomplete="false"></td>
              <td><input class="form-control changesNo" name="rate[]" tabindex="-1" id="rate_1" type="tel" placeholder="Rate" autocomplete="off" onkeypress="return IsNumeric(event);" onfocus="this.select();" required/></td>
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
      <a href="#" id="activate-step-2" class="btn btn-primary btn-lg pull-right btn-flat"> NEXT <i class="fa fa-chevron-right" aria-hidden="true"></i></a>
      </div>

      </div>
    </div>

  </div> <!-- //end step-1 -->
     <div class="row">
        <div class="col-xs-12">
            <div class="col-md-12 well setup-content" id="step-2">
                <h1 class="text-center">Payment</h1>
        <div class="col-md-6 col-md-offset-3">
          <div class="form-group input-group-lg">
              <label>Total USD: &nbsp;</label>
                <input type="tel" class="form-control"  id="subTotal2" placeholder="0.00" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" readonly="true" tabindex="-1">
          </div>

          <div class="">

            
            <div class="radio">
              <label><input type="radio" class="tender" value="card" name="tender" id="card"/>
              Card</label>
            </div>
           
            <div class="radio">
              <label><input type="radio" class="tender" value="paypal" name="tender" id="paypal"/>
              Paypal</label>
            </div>
            <div class="radio">
              <label><input type="radio" class="tender" value="stripe" name="tender" id="stripe"/>
              Stripe</label>
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

<br/><label>Address: </label>
<textarea class="form-control" name="address" id="address" placeholder="Customer Address" autocomplete="no"><?php echo $address; ?></textarea>
<br/><label>City: </label><input class="form-control" value="" name="city" id="city" type="text" placeholder="Customer City">
<br/><label>State: </label><select class="form-control" name="state" id="state">
  '<option value="">-SELECT-</option>'
  <?php 
    $cities = DB::query("SELECT s.`name` FROM states s WHERE s.`country_id` = '231'");
    foreach ($cities as $city) {
      echo '<option value="'.$city['name'].'">'.$city['name'].'</option>';
    }
  ?>
</select>

<br/><label>Upload Final Design: </label><input class="form-control" value="" name="final_design" id="final_design" type="file" placeholder="Design">
<br/>
<br/>
        
 <div class="col-md-4"> <button type="submit" class="btn btn-danger btn-lg btn-flat pull-right" name="btnSaveDraft" id="btnSaveDraft" value="Save">Save Draft&nbsp; <i class="glyphicon glyphicon-floppy-save"></i></button>
         </div>
          <div class="col-md-4"> <button type="submit" class="btn btn-warning btn-lg btn-flat pull-right" name="btnSaveDraftEmail" id="btnSaveDraftEmail" value="Save">Draft & Send Email &nbsp; <i class="glyphicon glyphicon-send"></i></button>
         </div>
          <div class="col-md-4"> <button type="submit" class="btn btn-success btn-lg btn-flat pull-right" name="btnCompleteInvoice" id="btnCompleteInvoice" value="Save">Complete Sale &nbsp; <i class="glyphicon glyphicon-floppy-disk"></i></button>
         </div>
         
          <input type="hidden" name="due_amount" id="due_amount" value="0.00" />
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








<script>

$(function(){
    $('.alert').delay(1000).hide('slow',function(){
     
      location.href='?route=modules/gl/sale_invoice_view&invoice_id=<?php echo $invoice_id; ?>';
      
     
    });
});
</script>





<script type="text/javascript" src="sale_invoice.js?v=1.2.2"></script>











<script>
/*
 no more need for this
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
*/

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




});

</script>






