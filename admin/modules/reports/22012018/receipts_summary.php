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
		$where_clause .= " AND si.`created_on` >= '".getDateTime(@$from_date_time, "mySQL")."'";
		$default_record_msg = 'Sale of '.@$from_date_time.' to Now';
	}
	
	if(@$to_date<>''){
		@$to_date_time = @$to_date.' '.@$to_time;
		$where_clause .= " AND si.`created_on` <= '".getDateTime(@$to_date_time, "mySQL")."'";
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

    $where_clause .=" AND DATE(si.`created_on`) >= DATE('".date('Y-m-d', strtotime( '-1 days' ))."') ";	
}



?>





<?php

$tbl = new HTML_Table('', 'table table-hover table-striped table-bordered data-table');
$tbl->addTSection('thead');
$tbl->addRow();
$tbl->addCell('Invoice ID', '', 'header');
$tbl->addCell('Web Ref.', '', 'header');
$tbl->addCell('Customer', '', 'header');
$tbl->addCell('No. of Item', '', 'header');
$tbl->addCell('Total Qty', '', 'header');
$tbl->addCell('Sub Total', '', 'header');
$tbl->addCell('Discount %', '', 'header');
$tbl->addCell('Tax Amount', '', 'header');
$tbl->addCell('Shipping Cost', '', 'header');
$tbl->addCell('Total Amount', '', 'header');
$tbl->addCell('Time Stamp', '', 'header');
$tbl->addCell('Reciept Type', '', 'header');
$tbl->addCell('Tender', '', 'header');
$tbl->addCell('WorkStation', '', 'header');
$tbl->addCell('Cashier', '', 'header');

$tbl->addCell('Detail', '', 'header');
$tbl->addTSection('tbody');
?>

<?php

$sql = "SELECT * FROM sale_invoice si 
		WHERE 1=1 ".$where_clause." 
		GROUP BY si.`sale_invoice_id` ORDER BY si.`sale_invoice_id` DESC ";

/*
echo '<pre>';
echo $sql;
echo '</pre>';
*/

$tx_total = 0;
$sub_total = 0;
$total_amount = 0;

$res = DB::query($sql);

foreach($res as $row) {

if($row['reciept_type'] == 'return'){
    $total_price = $row['total_amount'] * -1;
}else{
    $total_price = $row['total_amount'];
}	

$tbl->addRow();
$tbl->addCell($row['sale_invoice_id']);
$tbl->addCell(str_replace('Ref# ','',$row['comment1']));
$tbl->addCell($row['customer']);
$tbl->addCell($row['no_of_item']);
$tbl->addCell($row['total_qty']);
$tbl->addCell($row['sub_total']);
$tbl->addCell($row['dis_perc']);
$tbl->addCell($row['tax_amount']);
$tbl->addCell($row['ship_amount']);
$tbl->addCell('<b>'.$total_price.'</b>');
$tbl->addCell(getDateTime($row['created_on'], "dtShort"));
$tbl->addCell(strtoupper($row['reciept_type']));
$tbl->addCell(strtoupper($row['tender']));
$tbl->addCell(get_store_workstation_name($row['reciept_workstation']));
$tbl->addCell($row['created_by']);

$tbl->addCell("
<a class='pull btn btn-default btn-xs' href ='?route=modules/sale/sale_invoice_view&invoice_id=".$row['sale_invoice_id']."'>Detail&nbsp;<span class='glyphicon glyphicon-edit'></span></a>&nbsp;
");

$tx_total += $row['tax_amount'];

$sub_total += $row['sub_total'];

$total_amount += $total_price;


}
			  

?>

<?php 

$tbl->addRow();
$tbl->addCell('');
$tbl->addCell('');
$tbl->addCell('');
$tbl->addCell('Total:');
$tbl->addCell($sub_total);
$tbl->addCell('');
$tbl->addCell($tx_total);
$tbl->addCell('');
$tbl->addCell($total_amount);
$tbl->addCell('');
$tbl->addCell('');
$tbl->addCell('');
$tbl->addCell('');
$tbl->addCell('');
$tbl->addCell('');
$tbl->addCell('');


?>








 <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
          		Report
            <small>Sales Receipts.</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?php echo SITE_ROOT; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Receipts Report</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">

 <!-- Default box -->
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Sale Receipts Report</h3>
			  <h3><?php echo $default_record_msg; ?></h3>
			   <a class="btn btn-success btn-lg pull-right" href="#"  data-toggle="modal" data-target="#modalFilter"><i class="glyphicon glyphicon-filter"></i>&nbsp;Filter</a>
				<a class="" href="?route=modules/reports/receipts_summary">View All of Today & Yesterday</a>
           
			</div>

            <div class="box-body">
				<div class="table-responsive">
					<?php  echo $tbl->display(); ?>
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
			<form class="form-horizontal" name="frmFilter" id="frmFilter" action="?route=modules/reports/receipts_summary" method="POST">
			
			
			
			
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
			  
			  
			


			<!-- 
			
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
				<label class="control-label col-sm-3">DCS:</label>
				<div class="col-sm-6">
					<select class="form-control" name="dcs" id="dcs">
						<option value="">-All-</option>
						<?php 
						  $dcss = DB::query("SELECT d.`dcs`, d.`department_name` FROM departments d");
						  foreach ($dcss as $dcss_r){
						?>
							<option value="<?php echo trim($dcss_r['dcs']); ?>" <?php if(@$dcs==trim($dcss_r['dcs'])){ echo 'SELECTED'; } ?>><?php echo trim($dcss_r['department_name']); ?></option>
						<?php } ?>
					</select>
				</div>

				<div class="col-sm-3">
				  <select class="form-control" name="dcs_log">
					<option <?php if(@$dcs_log=='=') { echo 'SELECTED'; } ?> value="=">Include</option>
					<option <?php if(@$dcs_log=='!=') { echo 'SELECTED'; } ?> value="!=">Exclude</option>
				  </select>
				</div>
			  </div>
			  
			  
			  <div class="form-group">
				<label class="control-label col-sm-3">Desc2:</label>
				<div class="col-sm-6">
				  <input type="text" value="<?php echo @$desc; ?>" class="form-control" name="desc" id="desc" placeholder="">
				</div>

				<div class="col-sm-3">
				  <select class="form-control" name="desc_log">
					<option <?php if(@$desc_log=='=') { echo 'SELECTED'; } ?> value="=">Include</option>
					<option <?php if(@$desc_log=='!=') { echo 'SELECTED'; } ?> value="!=">Exclude</option>
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
			  </div>  -->
			  
			  
			  
			  <div class="form-group">
				<label class="control-label col-sm-3">Discount (%):</label>
				<div class="col-sm-3">
				  <input type="text" class="form-control" value="<?php echo @$dis_perc_from; ?>" name="dis_perc_from" id="dis_perc_from" maxlength="5" placeholder="From">
				</div>
				
				<div class="col-sm-3">
				  <input type="text" class="form-control" value="<?php echo @$dis_perc_to; ?>" name="dis_perc_to" id="dis_perc_to"  maxlength="5" placeholder="To">
				</div>

				<div class="col-sm-3">
				  <select class="form-control" name="dis_perc_log">
					<option <?php if(@$dis_perc_log=='=') { echo 'SELECTED'; } ?>  value="=">Include</option>
					<option <?php if(@$dis_perc_log=='!=') { echo 'SELECTED'; } ?>  value="!=">Exclude</option>
				  </select>
				</div>
			  </div>
			  
			  
			  
			  
			  <div class="form-group">
				<label class="control-label col-sm-3">Discount (USD):</label>
				<div class="col-sm-3">
				  <input type="text" class="form-control" value="<?php echo @$dis_amount_from; ?>" name="dis_amount_from" id="dis_amount_from" maxlength="10" placeholder="From">
				</div>
				
				<div class="col-sm-3">
				  <input type="text" class="form-control" value="<?php echo @$dis_amount_to; ?>" name="dis_amount_to" id="dis_amount_to"  maxlength="10" placeholder="To">
				</div>

				<div class="col-sm-3">
				  <select class="form-control" name="dis_amount_log">
					<option <?php if(@$dis_amount_log=='=') { echo 'SELECTED'; } ?>  value="=">Include</option>
					<option <?php if(@$dis_amount_log=='!=') { echo 'SELECTED'; } ?> value="!=">Exclude</option>
				  </select>
				</div>
			  </div>
			  
			  <div class="form-group">
				<label class="control-label col-sm-3">TAX (USD):</label>
				<div class="col-sm-3">
				  <input type="text" class="form-control" value="<?php echo @$tax_amount_from; ?>" name="tax_amount_from" id="tax_amount_from" maxlength="10" placeholder="From">
				</div>
				
				<div class="col-sm-3">
				  <input type="text" class="form-control" value="<?php echo @$tax_amount_to; ?>" name="tax_amount_to" id="tax_amount_to"  maxlength="10" placeholder="To">
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
			  
			  
			  
			  
			  <div class="form-group">
				<label class="control-label col-sm-3">Tender:</label>
				<div class="col-sm-6">
				  <select class="form-control multiselect" name="tender[]" multiple="multiple" data-placeholder="Default All">
					
					<option <?php if(in_array('cash', @$tender)) { echo 'SELECTED'; } ?> value="cash">Cash</option>
					<option <?php if(in_array('card', @$tender)){ echo 'SELECTED'; } ?> value="card">Card</option>	
					<option <?php if(in_array('gift', @$tender)) { echo 'SELECTED'; } ?> value="gift">Gift</option>	

				  </select>
				</div>
				<div class="col-sm-3">
				  <select class="form-control" name="tender_log">
					<option <?php if(@$tender_log=='=') { echo 'SELECTED'; } ?>  value="=">Include</option>
					<option <?php if(@$tender_log=='!=') { echo 'SELECTED'; } ?>  value="!=">Exclude</option>
				  </select>
				</div>
			  </div>
              
              
             
			  
			  
			
			  
			  
			  <!-- 
			  
			  <div class="form-group">
				<label class="control-label col-sm-3">Customer:</label>
				<div class="col-sm-6">
				  <select name="customer[]" class="form-control multiselect" multiple="multiple" data-placeholder="Default All">
					<option  value="Other">Other</option>
					<?php $customers = DB::query("SELECT c.`name`,c.`city` FROM customers c"); 
						foreach($customers as $cus){
					?>
					<option value="<?php echo strtoupper($cus['name']).' ('.strtoupper($cus['city']).')'; ?>"><?php echo strtoupper($cus['name']).' ('.strtoupper($cus['city']).')'; ?></option>
						<?php } ?>
					
				</select>
				</div>
				<div class="col-sm-3">
				  <select class="form-control" name="customer_log">
					<option value="=">Include</option>
					<option value="!=">Exclude</option>
				  </select>
				</div>
			  </div>
			  
			 -->

        </div>
        <div class="modal-footer">
			<button type="submit" class="btn btn-success" name="btnFilter" id="btnFilter">Apply</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        </div>
      </div>
      </form>
    </div>
  </div>


