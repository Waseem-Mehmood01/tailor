<?php

$where_clause = "";

$default_record_msg = 'Sale of '.date('M-Y');


if(isset($_POST['btnFilter'])){
	
	echo '<pre>';
	print_r($_POST);
	echo '</pre>';
	$where_clause = '';
	
	/*
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
	
	
	
	if(@$reciept_workstation <> ''){
		
				$workstations = implode("','", @$reciept_workstation);

		
		if(@$reciept_workstation_log == '='){
			$where_clause .=" AND si.`reciept_workstation` IN ('".$workstations."') ";
		} else {
			$where_clause .=" AND si.`reciept_workstation` NOT IN (".$workstations.") ";
		}
	}
	
	
	if(@$user_id <> ''){
		
				$users_array = implode("','", @$user_id);

		
		if(@$user_id_log == '='){
			$where_clause .=" AND si.`created_by` IN ('".$users_array."') ";
		} else {
			$where_clause .=" AND si.`created_by` NOT IN ('".$users_array."') ";
		}
	}
	
	
	if(@$tender <> ''){
		
				$tender_array = implode("','", @$tender);

		
		if(@$tender_log == '='){
			$where_clause .=" AND si.`tender` IN ('".$tender_array."') ";
		} else {
			$where_clause .=" AND si.`tender` NOT IN ('".$tender_array."') ";
		}
	}
	
	
	if(@$customer <> ''){
		
				$customer_array = implode("','", @$customer);

		
		if(@$customer_log == '='){
			$where_clause .=" AND si.`customer` IN ('".$customer_array."') ";
		} else {
			$where_clause .=" AND si.`customer` NOT IN ('".$customer_array."') ";
		}
	}
	
	
	

	

	
	
	
	

	

	
	
}

if(@$from_date=='' AND @$to_date==''){
 $where_clause .=" AND MONTH(si.`created_on`) >= MONTH('".getDateTime('0',"mySQL")."') 
AND YEAR(si.`created_on`) = YEAR('".getDateTime('0',"mySQL")."') ";	
}



?>





<?php

$tbl = new HTML_Table('', 'table table-hover table-striped table-bordered data-table');
$tbl->addTSection('thead');
$tbl->addRow();
$tbl->addCell('Invoice #', '', 'header');
$tbl->addCell('Ref No.', '', 'header');
$tbl->addCell('Customer', '', 'header');
$tbl->addCell('No. of Item', '', 'header');
$tbl->addCell('Total Qty', '', 'header');
$tbl->addCell('Sub Total', '', 'header');
$tbl->addCell('Discount %', '', 'header');
$tbl->addCell('Tax %', '', 'header');
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
		LEFT JOIN sale_invoice_detail sd
		ON(si.`sale_invoice_id`=sd.`sale_invoice_id`) 
		WHERE 1=1 ".$where_clause." 
		GROUP BY si.`sale_invoice_id` ORDER BY si.`sale_invoice_id` DESC ";
echo '<pre>';
echo $sql;
echo '</pre>';
$res = DB::query($sql);
foreach($res as $row) {	
$tbl->addRow();
$tbl->addCell($row['sale_no']);
$tbl->addCell($row['ref_no']);
$tbl->addCell($row['customer']);
$tbl->addCell($row['no_of_item']);
$tbl->addCell($row['total_qty']);
$tbl->addCell($row['sub_total']);
$tbl->addCell($row['dis_perc']);
$tbl->addCell($row['tax_perc']);
$tbl->addCell($row['ship_amount']);
$tbl->addCell('<b>'.$row['total_amount'].'</b>');
$tbl->addCell(getDateTime($row['created_on'], "dtShort"));
$tbl->addCell(strtoupper($row['reciept_type']));
$tbl->addCell(strtoupper($row['tender']));
$tbl->addCell(get_store_workstation_name($row['reciept_workstation']));
$tbl->addCell($row['created_by']);
$tbl->addCell("
<a class='pull btn btn-default btn-xs' href ='?route=modules/sale/sale_invoice_view&invoice_id=".$row['sale_invoice_id']."'>Detail&nbsp;<span class='glyphicon glyphicon-edit'></span></a>&nbsp;
");
}
			  

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
			   <a class="btn btn-success btn-lg pull-right" href="#"  data-toggle="modal" data-target="#modalFilter"><i class="glyphicon glyphicon-filter"></i>&nbsp;Filter</a>
				<a class="" href="?route=modules/reports/sale_report">View All of This Month</a>
           
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
			<form class="form-horizontal" name="frmFilter" id="frmFilter" action="" method="POST">
			
			
			
			
			
			<div class="form-group">
				<label class="control-label col-sm-3">From:</label>
				<div class="col-sm-3">
				  <input type="text" class="form-control date-picker" value="<?php echo @$from_date; ?>" name="from_date" id="from_date" placeholder="dd-mm-yyyy">
				</div>
				<div class="col-sm-3">
				  <input type="text" class="form-control timepicker" value="<?php echo @$from_time; ?>" name="from_time" id="from_time" placeholder="hh:mm">
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
				  <input type="text" class="form-control timepicker" value="<?php echo @$to_time; ?>" name="to_time" id="to_time" placeholder="hh:mm">
				</div>
				<div class="col-sm-3">
				  
				</div>
			  </div>
			




			
			<div class="form-group">
				<label class="control-label col-sm-3">Item #:</label>
				<div class="col-sm-3">
				  <input type="text" class="form-control" name="item_no_from" id="item_no_from" maxlength="4" placeholder="From">
				</div>
				
				<div class="col-sm-3">
				  <input type="text" class="form-control" name="item_no_to" id="item_no_to" maxlength="4" placeholder="To">
				</div>

				<div class="col-sm-3">
				  <select class="form-control" name="item_no_log">
					<option value="=">Include</option>
					<option value="!=">Exclude</option>
				  </select>
				</div>
			  </div>
			  
			  
			  <div class="form-group">
				<label class="control-label col-sm-3">DCS:</label>
				<div class="col-sm-6">
				  <input type="text" class="form-control" name="dcs" id="dcs" maxlength="4"  placeholder="">
				</div>

				<div class="col-sm-3">
				  <select class="form-control" name="dcs_log">
					<option value="=">Include</option>
					<option value="!=">Exclude</option>
				  </select>
				</div>
			  </div>
			  
			  
			  <div class="form-group">
				<label class="control-label col-sm-3">Desc2:</label>
				<div class="col-sm-6">
				  <input type="text" class="form-control" name="desc" id="desc" maxlength="4" placeholder="">
				</div>

				<div class="col-sm-3">
				  <select class="form-control" name="desc_log">
					<option value="=">Include</option>
					<option value="!=">Exclude</option>
				  </select>
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
												<option value="<?php echo $row['workstations_id']; ?>"><?php echo $row['store_title'].' - '.$row['title'].' - '.$row['description']; ?></option> 
													
								<?php } ?>
					</select>
				</div>

				<div class="col-sm-3">
				  <select class="form-control" name="reciept_workstation_log">
					<option value="=">Include</option>
					<option value="!=">Exclude</option>
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
					<option value="<?php echo trim($user['username']); ?>"><?php echo trim($user['username']); ?></option>	
					<?php } ?>
				  </select>
				</div>
				<div class="col-sm-3">
				  <select class="form-control" name="user_id_log">
					<option value="=">Include</option>
					<option value="!=">Exclude</option>
				  </select>
				</div>
			  </div>
			  
			  
			  
			  
			  <div class="form-group">
				<label class="control-label col-sm-3">Tender:</label>
				<div class="col-sm-6">
				  <select class="form-control multiselect" name="tender[]" multiple="multiple" data-placeholder="Default All">
					
					<option value="cash">Cash</option>
					<option value="card">Card</option>	
					<option value="gift">Gift</option>	

				  </select>
				</div>
				<div class="col-sm-3">
				  <select class="form-control" name="tender_log">
					<option value="=">Include</option>
					<option value="!=">Exclude</option>
				  </select>
				</div>
			  </div>
			  
			  
			  <div class="form-group">
				<label class="control-label col-sm-3">Qty:</label>
				<div class="col-sm-3">
				  <input type="text" class="form-control" name="qty_from" id="qty_from" placeholder="From">
				</div>
				
				<div class="col-sm-3">
				  <input type="text" class="form-control" name="qty_to" id="qty_to" placeholder="To">
				</div>

				<div class="col-sm-3">
				  <select class="form-control" name="qty_log">
					<option value="=">Include</option>
					<option value="!=">Exclude</option>
				  </select>
				</div>
			  </div>
			  
			  
			  
			  <div class="form-group">
				<label class="control-label col-sm-3">Discount (%):</label>
				<div class="col-sm-3">
				  <input type="text" class="form-control" name="dis_perc_from" id="dis_perc_from" maxlength="3" placeholder="From">
				</div>
				
				<div class="col-sm-3">
				  <input type="text" class="form-control" name="dis_perc_to" id="dis_perc_to"  maxlength="3" placeholder="To">
				</div>

				<div class="col-sm-3">
				  <select class="form-control" name="dis_perc_log">
					<option value="=">Include</option>
					<option value="!=">Exclude</option>
				  </select>
				</div>
			  </div>
			  
			  
			  
			  
			  <div class="form-group">
				<label class="control-label col-sm-3">Discount (USD):</label>
				<div class="col-sm-3">
				  <input type="text" class="form-control" name="dis_amount_from" id="dis_amount_from" maxlength="10" placeholder="From">
				</div>
				
				<div class="col-sm-3">
				  <input type="text" class="form-control" name="dis_amount_to" id="dis_amount_to"  maxlength="10" placeholder="To">
				</div>

				<div class="col-sm-3">
				  <select class="form-control" name="dis_amount_log">
					<option value="=">Include</option>
					<option value="!=">Exclude</option>
				  </select>
				</div>
			  </div>
			  
			  
			  
			  
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
			  
			  
	  
			

        </div>
        <div class="modal-footer">
			<button type="submit" class="btn btn-success" name="btnFilter" id="btnFilter">Apply</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        </div>
      </div>
      </form>
    </div>
  </div>


