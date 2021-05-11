<?php

$where_clause = "";

$default_record_msg = 'Sale of '.date('d-m-Y', strtotime( '-1 days' )).' & Today';

$reciept_workstation = isset($_POST['reciept_workstation']) ? $_POST['reciept_workstation']: array();

$user_id = isset($_POST['user_id']) ? $_POST['user_id']: array();

$tender = isset($_POST['tender']) ? $_POST['tender']: array();

$customer = isset($_POST['customer']) ? $_POST['customer']: array();
$from_sale_id = '';
$to_sale_id = '';
$from_date = '';
$to_date = '';
if(isset($_POST['btnFilter'])){
    /*
     echo '<pre>';
     print_r($_POST);
     echo '</pre>';
     $where_clause = '';
     
     
     $from_date = isset($_POST['from_date']) ? $_POST['from_date'] : '';
     
     $from_time = isset($_POST['from_time']) ? $_POST['from_time'] : '';
     
     $from_date_log = isset($_POST['from_date_log']) ? $_POST['from_date_log'] : '';
     
     $to_date = isset($_POST['from_date']) ? $_POST['from_date'] : '';
     
     $to_time = isset($_POST['to_time']) ? $_POST['to_time'] : '';
     
     $to_date_log = isset($_POST['to_date_log']) ? $_POST['to_date_log'] : '';
     
     $item_no_from = isset($_POST['item_no_from']) ? $_POST['item_no_from'] : '';
     
     $item_no_to = isset($_POST['item_no_to']) ? $_POST['item_no_to'] : '';
     
     $item_no_log= isset($_POST['item_no_log']) ? $_POST['to_date_log'] : '';
     */
    
    
    
    @extract($_POST);
    
    
    if(@$from_date<>''){
        @$from_date_time = @$from_date.' '.@$from_time;
        $where_clause .= " AND DATE(si.`created_on`) >= DATE('".getDateTime(@$from_date_time, "mySQL")."') ";
        $default_record_msg = 'Sale of '.@$from_date_time.' to Now';
    }
    
    if(@$to_date<>''){
        @$to_date_time = @$to_date.' '.@$to_time;
        $where_clause .= " AND DATE(si.`created_on`) <= DATE('".getDateTime(@$to_date_time, "mySQL")."') ";
        $default_record_msg = 'Sale of '.@$from_date_time.' to '.@$to_date_time;
    }
    
    /*
     if(@$item_no_from <> '' OR @$item_no_to <> ''){
     if(@$item_no_log=='='){
     $where_clause .=" AND sd.`item` IN ('".@$item_no_from."', '".@$item_no_to."') ";
     } else {
     $where_clause .=" AND sd.`item` NOT IN ('".@$item_no_from."', '".@$item_no_to."') ";
     }
     } */
    
    
    if(!empty($reciept_workstation)){
        if(is_serialized($reciept_workstation)){
            $reciept_workstation = unserialize($reciept_workstation);
        }
        $workstations = implode("','", @$reciept_workstation);
        
        
        if(@$reciept_workstation_log == '='){
            $where_clause .="
			AND si.`reciept_workstation` IN ('".$workstations."') ";
        } else {
            $where_clause .="
			AND si.`reciept_workstation` NOT IN (".$workstations.") ";
        }
    }
    
    
    if(!empty($user_id)){
        if(is_serialized($user_id)){
            $user_id = unserialize($user_id);
        }
        $users_array = implode("','", @$user_id);
        
        
        if(@$user_id_log == '='){
            $where_clause .="
			AND si.`created_by` IN ('".$users_array."') ";
        } else {
            $where_clause .="
			AND si.`created_by` NOT IN ('".$users_array."') ";
        }
    }
    
    
    if(!empty($tender)){
        if(is_serialized($tender)){
            $tender = unserialize($tender);
        }
        $tender_array = implode("','", @$tender);
        
        
        if(@$tender_log == '='){
            $where_clause .="
			AND si.`tender` IN ('".$tender_array."') ";
        } else {
            $where_clause .="
			AND si.`tender` NOT IN ('".$tender_array."') ";
        }
    }
    
    
    
    
    
    
    
    
    
    if(@$tax_amount_from<>''){
        $where_clause .= "
						AND si.`tax_amount` >= '".@$tax_amount_from."' ";
    }
    
    if(@$tax_amount_to<>''){
        $where_clause .= "
						AND si.`tax_amount` <= '".@$tax_amount_to."' ";
    }
    
    
    
    if(@$from_sale_id<>''){
        $where_clause .= "
						AND si.`sale_invoice_id` >= '".@$from_sale_id."' ";
    }
    
    if(@$to_sale_id<>''){
        $where_clause .= "
						AND si.`sale_invoice_id` <= '".@$to_sale_id."' ";
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}

if(@$from_date=='' AND @$to_date=='' AND @$from_sale_id=='' AND @$to_sale_id==''){
    
    $where_clause .=" AND MONTH(si.`created_on`) >= MONTH('".date('Y-m-d')."') AND  YEAR(si.`created_on`) >= YEAR('".date('Y-m-d')."')";
}



?>












 <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
          		Report
            <small>Z-Out Report.</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?php echo SITE_ROOT; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Z-Out Report</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">

 <!-- Default box -->
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Z-Out Report</h3>
			  <h3><?php echo $default_record_msg; ?></h3>
			   <a class="btn btn-success pull-right" href="#"  data-toggle="modal" data-target="#modalFilter"><i class="glyphicon glyphicon-filter"></i>&nbsp;Filter</a>
				&nbsp;&nbsp;<button id="printForm" class="btn btn-primary pull-right" onclick="javascript:printInvoice('printAble');" /><i class="glyphicon glyphicon-print"></i>&nbsp;PRINT</button>
			</div>

            <div class="box-body">
				<div class="table-responsive">
					<?php 

$printAble='';
$printAble .='		<div class="" style="padding:1px 1px; margin-top:1px;" id="printAble">
		<style>
@media print{
	h1,h2,h3,h4,strong,b{
		    font-family: serif!important;
	}
	
td,th{
text-align: right;
}

	.box-header, .box-footer, .box-footer, footer, header,.pace, .box-title, .box-tools, .content-header,  .btn, .with-border{
		display:none;
	}
    body{
        margin: 1px 1px!important;
    }
}


</style>
        <h4>Z-Out Report</h4>
		  <table width="60%">
		      <tr>
                <td>Reciept Date/Time: </td>
                <td>'.@$from_date_time.' to '.@$to_date_time.'</td>
              </tr>
              <tr>
                <td>Cashier: </td>';


   $printAble .='<td>'.@$users_array.'</td>
              </tr>
	       </table><BR/>';
 

   $sql_total = "SELECT COALESCE(SUM(si.`total_amount`), 0) AS total_amount  FROM sale_invoice si
		WHERE 1=1 ".$where_clause."  AND `is_reverse`='0' ";
   $total_amount = DB::queryFirstField($sql_total);
   $sql_returns = "SELECT COALESCE(SUM(si.`total_amount`), 0) AS total_amount  FROM sale_invoice si
		WHERE 1=1 ".$where_clause." AND `reciept_type`='return' ";
   $total_returns = DB::queryFirstField($sql_returns);
   
   $sql_reverse = "SELECT COALESCE(SUM(si.`total_amount`), 0) AS total_amount  FROM sale_invoice si
		WHERE 1=1 ".$where_clause."  AND `is_reverse`='1' ";
   $total_reverse = DB::queryFirstField($sql_reverse);
   
   $sql_gifts = "SELECT COALESCE(SUM(si.`total_amount`), 0) AS total_amount  FROM sale_invoice si
		WHERE 1=1 ".$where_clause." AND `tender`='gift' AND si.`is_reverse` = '0' ";
   $total_gifts = DB::queryFirstField($sql_gifts);
   
   $sql_gifts_s = "SELECT COALESCE(SUM(sg.`amount`),0) FROM sale_invoice si, sale_split_gift sg
WHERE 1=1 ".$where_clause." AND sg.`sales_invoice_id` = si.`sale_invoice_id`
AND si.`tender` = 'gift'  AND sg.`payment_type` = 'cash' AND si.`is_reverse` = '0' ";
   
   $gift_cash = DB::queryFirstField($sql_gifts_s);
   
   $total_gifts = $total_gifts - $gift_cash;
   
   $sql_sale = "SELECT count(*)  FROM sale_invoice si
		WHERE 1=1 ".$where_clause." ";
   $total_rec = DB::queryFirstField($sql_sale);
   
   $sql_ret = "SELECT count(*)  FROM sale_invoice si
		WHERE 1=1 ".$where_clause." AND `reciept_type`='return' ";
   $total_ret = DB::queryFirstField($sql_ret);
   
   $sql_rev = "SELECT count(*)  FROM sale_invoice si
		WHERE 1=1 ".$where_clause." AND `is_reverse`='1' ";
   $total_rev = DB::queryFirstField($sql_rev);
   
   
    
   if($total_returns=='') { $total_returns=0.00; }
   
   $net = $total_amount - ($total_returns+$total_reverse);
   
   $cash_flow = $net - $total_gifts;
   
$printAble .='<table width="100%">
   <tr>
    <th></th>
    <th>SALES</th>
    <th>RETURNS/REVERSE</th>
    <th>NET</th>
   </tr>
<tr>
    <td>Sales: </td>
    <td>'.$total_amount.'</td>
    <td>'.($total_returns+$total_reverse).'</td>
    <td>'.$net.'</td>
</tr>
<tr>
    <th>Total: </th>
    <th>'.$total_amount.'</th>
    <th>'.($total_returns+$total_reverse).'</th>
    <th>'.$net.'</th>
   </tr>
<tr>
<td colspan="4"><BR></td>
</tr>
<tr>
<td></td>
<td></td>
<td>Net Gift Cert : </td>
<td>'.$total_gifts.'</td>
</tr>
<tr>
<td></td>
<td></td>
<td>Net Returns: </td>
<td>'.$total_returns.'</td>
</tr>
<tr>
<td></td>
<td></td>
<td>Net Reverse: </td>
<td>'.$total_reverse.'</td>
</tr>
<tr>
<td colspan="4"><BR></td>
</tr>
<tr>
<th></th>
<th></th>
<th>Cash flow total : </th>
<th>'.$cash_flow.'</th>
</tr>

<tr>
<td></td>
<td></td>
<td><h5><strong>RECIEPTS COUNT<strong></h5></td>
<td></td>
</tr>
<tr>
<td></td>
<td></td>
<td>Sales : </td>
<td>'.$total_rec.'</td>
</tr>
<tr>
<td></td>
<td></td>
<td>Returns : </td>
<td>'.$total_ret.'</td>
</tr>
<tr>
<td></td>
<td></td>
<td>Cancels/Reversed: </td>
<td>'.$total_rev.'</td>
</tr>';


$sql_amount = "SELECT COALESCE(SUM(si.`tender_amount`), 0) AS total_amount  FROM sale_invoice si
		WHERE 1=1 ".$where_clause."  AND `is_reverse`='0' AND `tender`='cash'";
$paidin = DB::queryFirstField($sql_amount);

if($gift_cash<>''){
    $paidin = $paidin + $gift_cash;
}

$sql_amount = "SELECT COALESCE(SUM(si.`due_amount`), 0) AS total_amount  FROM sale_invoice si
		WHERE 1=1 ".$where_clause."  AND `is_reverse`='0' AND `tender`='cash' ";
$paidout = DB::queryFirstField($sql_amount);

$net_cash = $paidin - $paidout;

$printAble .='<tr>
<td></td>
<td></td>
<td><h5><strong>CASH<strong></h5></td>
<td></td>
</tr>
<tr>
<td></td>
<td></td>
<td>Paid In : </td>
<td>'.$paidin.'</td>
</tr>
<tr>
<td></td>
<td></td>
<td>Paid Out : </td>
<td>'.$paidout.'</td>
</tr>
<tr>
<td></td>
<td></td>
<td>Net : </td>
<td>'.$net_cash.'</td>
</tr>
';





$sql_splits = "SELECT COALESCE(SUM(si.`tender_amount`), 0) AS total_amount  FROM sale_invoice si
		WHERE 1=1 ".$where_clause."  AND `tender`='split' ";
$splits = DB::queryFirstField($sql_splits);


$printAble .='<tr>
<td></td>
<td></td>
<td><h5><strong>Split<strong></h5></td>
<td></td>
</tr>
<tr>
<td></td>
<td></td>
<td>Paid In : </td>
<td>'.$splits.'</td>
</tr>
';

$sql_splits = "SELECT COALESCE(SUM(sp.`amount`), 0)
FROM sale_invoice si, sale_split_payment sp
 WHERE 1=1 ".$where_clause."
 AND si.`sale_invoice_id` = sp.`sales_invoice_id`
 AND si.`tender`='split' AND sp.`payment_type` = 'cash'";


$splits_cash = DB::queryFirstField($sql_splits);


$printAble .='
<tr>
<td></td>
<td></td>
<td>Cash : </td>
<td>'.$splits_cash.'</td>
</tr>';

$sql_splits = "SELECT COALESCE(SUM(sp.`amount`), 0)
FROM sale_invoice si, sale_split_payment sp
 WHERE 1=1 ".$where_clause."
 AND si.`sale_invoice_id` = sp.`sales_invoice_id`
 AND si.`tender`='split' AND sp.`payment_type` != 'cash'";


$splits_card = DB::queryFirstField($sql_splits);


$printAble .='
<tr>
<td></td>
<td></td>
<td>Cards : </td>
<td>'.$splits_card.'</td>
</tr>';

$sql_cards = "SELECT COALESCE(SUM(si.`tender_amount`), 0) AS total_amount  FROM sale_invoice si
		WHERE 1=1 ".$where_clause."  AND `tender`='card' ";
$allcards = DB::queryFirstField($sql_cards);


$printAble .='<tr>
<td></td>
<td></td>
<td><h5><strong>All Cards<strong></h5></td>
<td></td>
</tr>
<tr>
<td></td>
<td></td>
<td>Paid In : </td>
<td>'.$allcards.'</td>
</tr>';

$sql_cards = "SELECT COALESCE(SUM(si.`total_amount`), 0)
FROM sale_invoice si, cards_info ci
 WHERE 1=1 ".$where_clause." 
 AND si.`sale_invoice_id` = ci.`sales_invoice_id`
 AND si.`tender`='card' AND ci.`card_type` = 'master'";


$card_master = DB::queryFirstField($sql_cards);


$printAble .='
<tr>
<td></td>
<td></td>
<td>Master : </td>
<td>'.$card_master.'</td>
</tr>';

$sql_cards = "SELECT COALESCE(SUM(si.`total_amount`), 0)
FROM sale_invoice si, cards_info ci
 WHERE 1=1 ".$where_clause."
 AND si.`sale_invoice_id` = ci.`sales_invoice_id`
 AND si.`tender`='card' AND ci.`card_type` = 'visa'";


$card_visa = DB::queryFirstField($sql_cards);


$printAble .='
<tr>
<td></td>
<td></td>
<td>Visa : </td>
<td>'.$card_visa.'</td>
</tr>';


$sql_cards = "SELECT COALESCE(SUM(si.`total_amount`), 0)
FROM sale_invoice si, cards_info ci
 WHERE 1=1 ".$where_clause."
 AND si.`sale_invoice_id` = ci.`sales_invoice_id`
 AND si.`tender`='card' AND ci.`card_type` = 'amex'";


$card_amex = DB::queryFirstField($sql_cards);


$printAble .='
<tr>
<td></td>
<td></td>
<td>AMEX : </td>
<td>'.$card_amex.'</td>
</tr>';








$sql_gifts_s = "SELECT COALESCE(SUM(sg.`amount`),0) FROM sale_invoice si, sale_split_gift sg
WHERE 1=1 ".$where_clause." AND sg.`sales_invoice_id` = si.`sale_invoice_id`
AND si.`tender` = 'gift'  AND sg.`payment_type` = 'gift' AND si.`is_reverse` = '0' ";
$gift_gift = DB::queryFirstField($sql_gifts_s);


$printAble .='<tr>
<td></td>
<td></td>
<td><h5><strong>Split-Gift<strong></h5></td>
<td></td>
</tr>
<tr>
<td></td>
<td></td>
<td>Gift : </td>
<td>'.$gift_gift.'</td>
</tr>';




$printAble .='
<tr>
<td></td>
<td></td>
<td>Cash : </td>
<td>'.$gift_cash.'</td>
</tr>';







$printAble .='</table>
</div><!-- /.box-body -->';

echo $printAble;

?>
					
				</div>
            </div><!-- /.box-body -->
            <div class="box-footer">
             
            </div>
            
          </div><!-- /.box -->
		</section> 
		
		
<script type="text/javascript">
		
        function printInvoice(divID) {
      
            var divElements = document.getElementById(divID).innerHTML;
       
            var oldPage = document.body.innerHTML;

            document.body.innerHTML = 
              "<html><head><title></title></head><body>" + 
              divElements + "</body>";
			
			
			window.focus();

            window.print();
         
            window.close();
            
            document.body.innerHTML = oldPage;


              $(function(){
            	  $('.date-picker').datepicker({
                      format: 'dd-mm-yyyy',
                      autoclose: true
                  }); 
                
            
                
            });
          
                        

          
        }

</script>		
	
		
		
		
		
		
		
		 <!-- Modal -->
  <div class="modal fade" id="modalFilter" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Filter By</h4>
        </div>
        <div class="modal-body">
			<form class="form-horizontal" name="frmFilter" id="frmFilter" action="?route=modules/reports/z_out" method="POST">
			
			
			
			
		<div class="form-group">
				<label class="control-label col-sm-3">From:</label>
				<div class="col-sm-3">
				  <input type="text" class="form-control date-picker" value="<?php echo @$from_date; ?>" name="from_date" id="from_date" placeholder="dd-mm-yyyy">
				</div>
				<div class="col-sm-3">
				  <input type="text" class="form-control timepicker" value="<?php if(isset($from_time)){ echo @$from_time; } else { echo '9:00 AM'; } ?>" name="from_time" id="from_time" placeholder="hh:mm">
				</div>
				<div class="col-sm-3">
				  
				</div>
			  </div>
			  
			  <div class="form-group">
				<label class="control-label col-sm-3">To:</label>
				<div class="col-sm-3">
				  <input type="text" class="form-control date-picker" value="<?php echo @$to_date; ?>" name="to_date" id="to_date" placeholder="dd-mm-yyyy">
				</div>
				<div class="col-sm-3">
				  <input type="text" class="form-control timepicker" value="<?php if(isset($to_time)){ echo @$to_time; } else { echo '11:59 PM'; } ?>" name="to_time" id="to_time" placeholder="hh:mm">
				</div>
				<div class="col-sm-3">
				  
				</div>
			  </div>
			

				<div class="form-group">
				<label class="control-label col-sm-3">Invoice ID:</label>
				<div class="col-sm-3">
				  <input type="text" class="form-control" value="<?php echo @$from_sale_id; ?>" name="from_sale_id" id="from_sale_id" placeholder="From">
				</div>
				<div class="col-sm-3">
				  <input type="text" class="form-control" value="<?php echo @$to_sale_id; ?>" name="to_sale_id" id="to_sale_id" placeholder="To">
				</div>
				<div class="col-sm-3">
				  
				</div>
			  </div>
			  
			  
			
			  
			  <div class="form-group">
				<label class="control-label col-sm-3">Store:</label>
				<div class="col-sm-6">
					<select class="form-control multiselect" name="reciept_workstation[]" multiple="multiple" data-placeholder="Default All">
								<?php $sql ="SELECT w.*, s.`title` AS store_title,s.`description` FROM
								workstations w
								LEFT JOIN stores s
								ON(w.`stores_id`=s.`stores_id`)
														ORDER BY s.`title`,w.`title`";
								$res = DB::query($sql);
								$opt_label = '';
								foreach($res as $row){
								?>
												<?php 
													if($opt_label == $row['store_title']) {	
												} else{
												?>
												<optgroup label="<?php echo $row['store_title']; $opt_label = $row['store_title']; ?>">
												<?php } ?>
												<option value="<?php echo $row['workstations_id']; ?>" <?php if(in_array($row['workstations_id'], @$reciept_workstation)) { echo 'SELECTED'; } ?> ><?php echo $row['store_title'].' - '.$row['title'].' - '.$row['description']; ?></option> 
													
								<?php } ?>
					</select>
				</div>

				<div class="col-sm-3">
				  <select class="form-control" name="reciept_workstation_log">
					<option <?php if(@$reciept_workstation_log=='=') { echo 'SELECTED'; } ?> value="=">Include</option>
					<option <?php if(@$reciept_workstation_log=='!=') { echo 'SELECTED'; } ?>  value="!=">Exclude</option>
				  </select>
				</div>
			  </div>
			  
			  
			  
			  <div class="form-group">
				<label class="control-label col-sm-3">Cashier:</label>
				<div class="col-sm-6">
				  <select class="form-control multiselect" name="user_id[]" multiple="multiple" data-placeholder="Default All">
					<?php
						$users = DB::query("SELECT u.`user_id`, u.`username` FROM users u");
						foreach($users as $user){
					?>
					<option value="<?php echo trim($user['username']); ?>"  <?php if(in_array($user['username'], @$user_id)) { echo 'SELECTED'; } ?> ><?php echo trim($user['username']); ?></option>	
					<?php } ?>
				  </select>
				</div>
				<div class="col-sm-3">
				  <select class="form-control" name="user_id_log">
					<option <?php if(@$user_id_log=='=') { echo 'SELECTED'; } ?>  value="=">Include</option>
					<option <?php if(@$user_id_log=='!=') { echo 'SELECTED'; } ?>  value="!=">Exclude</option>
				  </select>
				</div>
			  </div>
			  
			  
			  
			  
			 
             
			  
			  
			
		

        </div>
        <div class="modal-footer">
			<button type="submit" class="btn btn-success" name="btnFilter" id="btnFilter">Apply</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        </div>
      </div>
      </form>
    </div>
  </div>




