<?php

$where_clause = "";
$having_clause = "";
$default_record_msg = 'Sale of '.date('M-Y');


$user_id = isset($_POST['user_id']) ? $_POST['user_id']: array();

$tender = isset($_POST['tender']) ? $_POST['tender']: array();

$customer = isset($_POST['customer']) ? $_POST['customer']: array();



if(isset($_POST['btnFilter'])){
    @extract($_POST);
} else {
    @extract($_GET);
}


if(@$from_date<>''){
    @$from_date_time = @$from_date.' '.@$from_time;
    $where_clause .= "
		AND si.`created_on` >= '".getDateTime(@$from_date_time, "mySQL")."'";
    $default_record_msg = 'Sale of '.@$from_date_time.' to Now';
}

if(@$to_date<>''){
    @$to_date_time = @$to_date.' '.@$to_time;
    $where_clause .= "
		AND si.`created_on` <= '".getDateTime(@$to_date_time, "mySQL")."'";
    $default_record_msg = 'Sale of '.@$from_date_time.' to '.@$to_date_time;
}





if(@$item_no_from<>'' AND @$item_no_to<>''){
    
    if(@$item_no_log <> '='){
        $where_clause .="
				AND sd.`item` NOT BETWEEN ".@$item_no_from." AND ".@$item_no_to." ";
    } else {
        
        
        if(@$item_no_from<>''){
            $where_clause .= "
						AND sd.`item` >= '".@$item_no_from."'";
        }
        
        if(@$item_no_to<>''){
            $where_clause .= "
						AND sd.`item` <= '".@$item_no_to."'";
        }
    }
} else {
    if(@$item_no_from<>''){
        if(@$item_no_log=='='){
            $where_clause .= "
						AND sd.`item` >= '".@$item_no_from."'";
        }
    }
    
    if(@$item_no_to<>''){
        if(@$item_no_log=='='){
            $where_clause .= "
						AND sd.`item` <= '".@$item_no_to."'";
        }
        
    }
}





if(@$desc <> ''){
    
    if(@$desc_log == '='){
        $where_clause .="
				AND sd.`description` LIKE '".@$desc."' ";
    } else {
        $where_clause .="
				AND sd.`description` NOT LIKE '".@$desc."' ";
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
















if(@$from_date=='' AND @$to_date==''){
    $where_clause .="
 AND MONTH(si.`created_on`) = MONTH('".getDateTime('0',"mySQL")."')
AND YEAR(si.`created_on`) = YEAR('".getDateTime('0',"mySQL")."') ";
}



if($having_clause<>''){ $having_clause = " HAVING 1=1 ".$having_clause; }


        
$sql = "SELECT sd.`item`, sd.`description`,  si.*,  
		si.`created_on` AS last_sold_on,
		si.`total_amount` AS total_sale, SUM(sd.`qty`) AS total_qty, 
		si.`dis_perc` AS total_dis_perc, si.`dis_amount` AS total_dis,
		si.`tax_perc` AS total_tax_perc, si.`tax_amount` AS total_tax 
		 FROM sale_invoice_detail sd 
				LEFT JOIN sale_invoice si
				ON(si.`sale_invoice_id`=sd.`sale_invoice_id` AND  si.`reciept_type`='sale'  AND si.`is_reverse`='0') 
				WHERE 1=1 ".$where_clause." 
				 GROUP BY sd.`item` ".$having_clause." ORDER BY sale_invoice_id DESC";

   










$ded_reverse = 0.00;
$sql_ded = "SELECT sd.`item` ,sd.`description`, si.`reciept_type`,si.`tender`,si.`is_reverse`, si.`created_by`,  
		MAX(si.`created_on`) AS last_sold_on,
		SUM(sd.`total`) AS total_sale, SUM(sd.`qty`) AS total_qty, 
		SUM(si.`dis_perc`) AS total_dis_perc, SUM(si.`dis_amount`) AS total_dis,
		SUM(si.`tax_perc`) AS total_tax_perc, SUM(si.`tax_amount`) AS total_tax 
		 FROM sale_invoice_detail sd 
				LEFT JOIN sale_invoice si
				ON(si.`sale_invoice_id`=sd.`sale_invoice_id` AND si.`is_reverse`='1') 
				WHERE 1=1 ".$where_clause." 
				 GROUP BY sd.`item` ".$having_clause." ";
$deductions_row = DB::query($sql_ded);
foreach($deductions_row as $deductions_rows){
  $ded_reverse += $deductions_rows['total_sale'];  
}
                
 $total_q = DB::query($sql);
            $sub_total = 0.00;
            $tax_total = 0.00;
            $dis_total = 0.00;
            $total = 0.00;
            foreach($total_q as $tot){
                if( ($tot['reciept_type']=='sale') AND ($tot['is_reverse']=='0') ){
                    $sub_total += $tot['sub_total'];
                    $tax_total += $tot['tax_amount'];
                    $dis_total += $tot['total_dis'];
                    $total += $tot['total_amount'];
                }
            }


$total_pos = $total-$ded_reverse;








?>







 <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
          		Report
            <small>Sales.</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?php echo SITE_ROOT; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Sale Report</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">

 <!-- Default box -->
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Sale Report</h3>
                                <h3><?php echo $default_record_msg; ?></h3>
                                <a class="btn btn-primary d-inline" href="?route=modules/reports/sale_report">View All of This Month</a>
                                <a class="btn btn-success d-inline" href="#"  data-toggle="modal" data-target="#modalFilter"><i class="glyphicon glyphicon-filter"></i>&nbsp;Filter</a>                                

								

     

			</div>

            <div class="box-body">

				
         <div class="row">
            <div class="col-md-6">
                <div class="panel panel-primary">
                <div class="panel-heading">POS Sale [include instagram]</div>
                <div class="panel-body">
            	<table class="table">
            	<tr>
            		<td>Subtotal: </td>
            		<td><strong>$<?php echo $sub_total; ?></strong></td>
            	</tr>
            	<tr>
            		<td>TAX: </td>
            		<td><strong>$<?php echo $tax_total; ?></strong></td>
            	</tr>
            	<tr>
            		<td>Discount: </td>
            		<td><strong>$<?php echo $dis_total; ?></strong></td>
            	</tr>
            	<tr>
            		<td>Reverse: </td>
            		<td><strong>$<?php echo $ded_reverse; ?></strong></td>
            	</tr>
            	<tr>
            		<td><h3>Total: </h3></td>
            		<td><h3>$<?php echo $total_pos; ?> USD</h3></td>
            	</tr>
            	</table>
            	</div>
            	</div>
            </div>
            
            <?php
             $sub_total = 0.00;
            $tax_total = 0.00;
            $total_web = 0.00;
            $sql = "SELECT * FROM orders o WHERE 1=1 ";
            
        if(@$from_date<>''){
        @$from_date_time = @$from_date.' '.@$from_time;
        $sql .= " 
    		AND o.`created_on` >= '".getDateTime(@$from_date_time, "mySQL")."'";
            }
        
        if(@$to_date<>''){
            @$to_date_time = @$to_date.' '.@$to_time;
            $sql .= " 
        		AND o.`created_on` <= '".getDateTime(@$to_date_time, "mySQL")."'";
        }
                    
                 
            $ord = DB::query($sql);
            
            foreach($ord as $order){
                $sub_total += $order['sub_total'];
            $tax_total += $order['tax_amount'];
            $total_web += $order['order_total'];
            }
            
            ?>
            
            <div class="col-md-6">
                <div class="panel panel-primary">
                <div class="panel-heading">Web Sale</div>
                <div class="panel-body">
            	<table class="table">
            	<tr>
            		<td>Subtotal: </td>
            		<td><strong>$<?php echo $sub_total; ?></strong></td>
            	</tr>
            	<tr>
            		<td>TAX: </td>
            		<td><strong>$<?php echo $tax_total; ?></strong></td>
            	</tr>
            	
            	<tr>
            		<td><h3>Total: </h3></td>
            		<td><h3>$<?php echo $total_web; ?> USD</h3></td>
            	</tr>
            	</table>
            	</div>
            	</div>
            	
            	<h4>Total Earning: <h2>$<?php echo $total_pos+$total_web; ?> USD</h2></h4>
            	
            </div>
            
            </div> 
	
				
            </div><!-- /.box-body -->
            <div class="box-footer">
             
            </div>
          </div><!-- /.box -->
		</section> 
		
		
		
	
		
		
		
		
		
		
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
			<form class="form-horizontal" name="frmFilter" id="frmFilter" action="?route=modules/reports/sale_report" method="POST">
			
			
			
			
			
			<div class="form-group">
				<label class="control-label col-sm-3">From:</label>
				<div class="col-sm-3">
				  <input type="text" class="form-control date-picker" value="<?php echo @$from_date; ?>" name="from_date" id="from_date" placeholder="dd-mm-yyyy">
				</div>
				<div class="col-sm-3">
				  <input type="text" class="form-control timepicker" value="<?php if(isset($from_time)){ echo @$from_time; } else { echo '6:00 AM'; } ?>" name="from_time" id="from_time" placeholder="hh:mm">
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
				<label class="control-label col-sm-3">Item #:</label>
				<div class="col-sm-3">
				  <input type="text" class="form-control" name="item_no_from" id="item_no_from" value="<?php echo @$item_no_from; ?>" maxlength="4" placeholder="From">
				</div>
				
				<div class="col-sm-3">
				  <input type="text" class="form-control" name="item_no_to" value="<?php echo @$item_no_to; ?>" id="item_no_to" maxlength="4" placeholder="To">
				</div>

				<div class="col-sm-3">
				  <select class="form-control" name="item_no_log">
					<option <?php if(@$item_no_log=='=') { echo 'SELECTED'; } ?> value="=">Include</option>
					<option <?php if(@$item_no_log=='!=') { echo 'SELECTED'; } ?> value="!=">Exclude</option>
				  </select>
				</div>
			  </div>
			  
			  
			

			
			  
		
			  
			  
			    <div class="form-group">
				<label class="control-label col-sm-3">Qty:</label>
				<div class="col-sm-3">
				  <input type="text" value="<?php echo @$qty_from; ?>" class="form-control" name="qty_from" id="qty_from" placeholder="From">
				</div>
				
				<div class="col-sm-3">
				  <input type="text" value="<?php echo @$qty_to; ?>" class="form-control" name="qty_to" id="qty_to" placeholder="To">
				</div>

				<div class="col-sm-3">
				  <select class="form-control" name="qty_log">
					<option <?php if(@$qty_log=='=') { echo 'SELECTED'; } ?>  value="=">Include</option>
					<option <?php if(@$qty_log=='!=') { echo 'SELECTED'; } ?>  value="!=">Exclude</option>
				  </select>
				</div>
			  </div>
			  
			  
			  
			
			  
			  
			  
		
			  
			  
			  
			  
			  
			  <div class="form-group">
				<label class="control-label col-sm-3">Tender:</label>
				<div class="col-sm-6">
				  <select class="form-control multiselect" name="tender[]" multiple="multiple" data-placeholder="Default All">
					
					<option <?php if(in_array('cash', @$tender)) { echo 'SELECTED'; } ?> value="cash">Cash</option>
					<option <?php if(in_array('card', @$tender)){ echo 'SELECTED'; } ?> value="card">Card</option>	
					<option <?php if(in_array('slpit', @$tender)) { echo 'SELECTED'; } ?> value="split">Split</option>	
				  </select>
				</div>
			
              
              
             
			  
			  
			
			  
			  
			  
			  
			
			  
			  
	  
			

        </div>
        <div class="modal-footer">
        <button type="submit" class="btn btn-success" name="btnFilter" id="btnFilter">Apply</button>
        	<button type="button" class="btn btn-primary" name="btnReset" id="btnReset">Clear Filter</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        </div>
      </div>
      </form>
    </div>
  </div>
  

<script type="text/javascript">

$(function(){ $("#btnReset").on("click",function(){ $(".form-control").val("");});});

</script>

