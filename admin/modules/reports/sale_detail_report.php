<?php
$where_clause = "";
$having_clause = "";
$default_record_msg = 'Sale Detail of '.date('M-Y');

$reciept_workstation = isset($_POST['reciept_workstation']) ? $_POST['reciept_workstation']: array();

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
		$default_record_msg = 'Sale Detail of '.@$from_date_time.' to Now';
	}
	
	if(@$to_date<>''){
		@$to_date_time = @$to_date.' '.@$to_time;
		$where_clause .= "
		AND si.`created_on` <= '".getDateTime(@$to_date_time, "mySQL")."'";
		$default_record_msg = 'Sale Detail of '.@$from_date_time.' to '.@$to_date_time;
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
	
	
	
	
	if(@$dcs <> ''){
		
		if(@$dcs_log == '='){
			$where_clause .="
				AND sd.`dcs` LIKE '".@$dcs."' ";
		} else {
			$where_clause .="
				AND sd.`dcs` NOT LIKE '".@$dcs."' ";
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
	
	
	if(!empty($customer)){
                                
                                if(is_serialized($customer)){
                                    $customer = unserialize($customer);
                                }
		
				$customer_array = implode("','", @$customer);

		
		if(@$customer_log == '='){
			$where_clause .=" 
			AND si.`customer` IN ('".$customer_array."') ";
		} else {
			$where_clause .=" 
			AND si.`customer` NOT IN ('".$customer_array."') ";
		}
	}
	
	
	
	
	if(@$qty_from<>'' AND @$qty_to<>''){
		
		if(@$qty_log <> '='){
			$having_clause .="
			                      AND total_qty NOT IN ('".@$qty_from."' , '".@$qty_to."') ";
		} else {
			
			$having_clause .="
			                     AND total_qty >= '".@$qty_from."' AND total_qty <= '".@$qty_to."' ";
		}
	} else {
		if(@$qty_from<>''){
			$having_clause .="
			                      AND total_qty >= '".@$qty_from."' ";
			
		}
		
		if(@$qty_to<>''){
			$having_clause .="
			                      AND total_qty <= '".@$qty_to."'";
			
		}
	}
	
	
	if(@$dis_perc_from<>'' AND @$dis_perc_to<>''){
		
		if(@$dis_perc_log <> '='){
			$having_clause .="
			                     AND dis_perc NOT IN ('".@$dis_perc_from."' , '".@$dis_perc_to."') ";
		} else {
			
			
			if(@$dis_perc_from<>''){
				$having_clause .= "
						AND dis_perc >= '".@$dis_perc_from."' ";
			}
			
			if(@$dis_perc_to<>''){
				$having_clause.= "
						AND  dis_perc <= '".@$dis_perc_to."' ";
			}
		}
	} else {
		if(@$dis_perc_from<>''){
			if(@$dis_perc_log=='='){
				$having_clause .= "
                        AND  dis_perc >= '".@$dis_perc_from."' ";
			}
		}
		
		if(@$dis_perc_to<>''){
			if(@$dis_perc_log=='='){
				$having_clause.= "
						AND dis_perc <= '".@$dis_perc_to."' ";
			}
			
		}
	}
	
	
	
	
	if(@$dis_amount_from<>'' AND @$dis_amount_to<>''){
		
		if(@$dis_amount_log <> '='){
			$having_clause .="
			                     AND dis_amount NOT IN ('".@$dis_amount_from."' , '".@$dis_amount_to."') ";
		} else {
			
			
			if(@$dis_amount_from<>''){
				$having_clause .= "
						AND dis_amount >= '".@$dis_amount_from."' ";
			}
			
			if(@$dis_amount_to<>''){
				$having_clause .= "
						AND dis_amount <= '".@$dis_amount_to."' ";
			}
		}
	} else {
		if(@$dis_amount_from<>''){
			if(@$dis_amount_log=='='){
				$having_clause .= "
						AND dis_amount >= '".@$dis_amount_from."' ";
			}
		}
		
		if(@$dis_amount_to<>''){
			if(@$dis_amount_log=='='){
				$having_clause .= "
						AND dis_amount <= '".@$dis_amount_to."' ";
			}
			
		}
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	



if(@$from_date=='' AND @$to_date==''){
	$where_clause .="
 AND MONTH(si.`created_on`) = MONTH('".getDateTime('0',"mySQL")."')
AND YEAR(si.`created_on`) = YEAR('".getDateTime('0',"mySQL")."') ";
}




if($having_clause<>''){ $having_clause = " HAVING 1=1 ".$having_clause; }

if(isset($_GET["page"])) {
		$page = (int)$_GET["page"];
	} else{
	$page = 1;	
	}
	
	$setLimit = 10;
	$pageLimit = ($page * $setLimit) - $setLimit;
        
$sql = "SELECT sd.`item`, sd.`dcs` ,sd.`description`, si.*
		 FROM sale_invoice_detail sd
				LEFT JOIN sale_invoice si
				ON(si.`sale_invoice_id`=sd.`sale_invoice_id` AND si.`reciept_type`='sale'  AND si.`is_reverse`='0')
				WHERE 1=1 ".$where_clause."
				 GROUP BY  si.`sale_invoice_id` ".$having_clause." ORDER BY si.`sale_invoice_id` DESC";


  $total_earning = 0.00;
        $ded_gift = 0.00;
        $total_deductions = 0.00;
        $sub_total = 0.00;                         
        $sql2 = $sql;
	$res1 = DB::query($sql2);
	$total_records = DB::count();
	
	$sql .= ' LIMIT '.$pageLimit.', '.$setLimit;
        
//$sql = "SELECT * FROM sale_invoice s WHERE s.`is_reverse`='0' ORDER BY s.`sale_invoice_id` DESC";

/*
echo '<pre>';
echo $sql;
echo '</pre>';
*/


?>


<?php
//Draft Expense Voucher
$tbl = new HTML_Table('', 'table table-hover table-striped table-bordered');
$tbl->addTSection('thead');
$tbl->addRow();
$tbl->addCell('Invoice #', '', 'header');
$tbl->addCell('Ref No.', '', 'header');
$tbl->addCell('Reciept Type.', '', 'header');
$tbl->addCell('Tender', '', 'header');
$tbl->addCell('Cashier', '', 'header');
$tbl->addCell('No. of Item', '', 'header');
$tbl->addCell('Total Qty', '', 'header');
$tbl->addCell('Sub Total', '', 'header');
$tbl->addCell('Discount %', '', 'header');
$tbl->addCell('Tax', '', 'header');
$tbl->addCell('Shipping Cost', '', 'header');
$tbl->addCell('Total Amount', '', 'header');
$tbl->addCell('Date', '', 'header');
$tbl->addCell('Comment1', '', 'header');
$tbl->addCell('Comment2', '', 'header');
$tbl->addTSection('tbody');
?>

<?php


$res = DB::query($sql);
foreach($res as $row) {	
    
    $tend = $row['tender'];
    
    $is_gift_s = DB::queryFirstField("select count(*) as total_split from sale_split_gift where sales_invoice_id='".$row['sale_invoice_id']."'");
    if($is_gift_s > 0) {
        $tend .= ' [Split]';
    }
    
    $g_cash = DB::queryFirstField("select amount from sale_split_gift where sales_invoice_id='".$row['sale_invoice_id']."' AND payment_type = 'cash'");
    
    if($g_cash <> ''){
        $total_amount += $g_cash;
        $g_cash = '[ cash: '.$g_cash.' ]';
        
    }
    $total_price = $row['total_amount'];
	$tbl->addRow('',array('class'=>'invoice-head'));
		$tbl->addCell($row['sale_invoice_id']);
		$tbl->addCell($row['ref_no']);
		$tbl->addCell(strtoupper($row['reciept_type']));
		$tbl->addCell(strtoupper($tend));
		$tbl->addCell($row['created_by']);
		$tbl->addCell($row['no_of_item']);
		$tbl->addCell($row['total_qty']);
		$tbl->addCell($row['sub_total']);
		$tbl->addCell($row['dis_perc']);
		$tbl->addCell($row['tax_amount']);
		$tbl->addCell($row['ship_amount']);
		$tbl->addCell('<b>'.$total_price.'</b>'.$g_cash);
		$tbl->addCell(date("d-m-Y", strtotime($row['date'])));
		$tbl->addCell($row['comment1']);
		$tbl->addCell($row['comment2']);



$products = DB::query("select * from sale_invoice_detail where sale_invoice_id = '".$row['sale_invoice_id']."'");
	$total_pro = DB::count();
	if($total_pro>0){
		$tbl->addRow();
		$tbl->addCell('', '', 'header');
		$tbl->addCell('Item#', '', 'header');
		$tbl->addCell('DESC', '', 'header',array('colspan'=>6));
		$tbl->addCell('Qty', '', 'header');
		$tbl->addCell('Unit Price', '', 'header');
		$tbl->addCell('Sub Total', '', 'header');
		$tbl->addCell('', '', 'header');
		$tbl->addCell('', '', 'header');
		$tbl->addCell('', '', 'header');
		$tbl->addCell('', '', 'header');
		$tbl->addCell('', '', 'header',array('style'=>'display:none;'));
		$tbl->addCell('', '', 'header',array('style'=>'display:none;'));
		$tbl->addCell('', '', 'header',array('style'=>'display:none;'));
		$tbl->addCell('', '', 'header',array('style'=>'display:none;'));
		$tbl->addCell('', '', 'header',array('style'=>'display:none;'));
		
		
		
		foreach($products as $product){
			$tbl->addRow();
			$tbl->addCell("&nbsp;");
			$tbl->addCell($product['item']);
			$tbl->addCell($product['description'],'','data',array('colspan'=>6));
			$tbl->addCell($product['qty']);
			$tbl->addCell($product['unit_price']);
			$tbl->addCell($product['total']);
			$tbl->addCell("&nbsp;");
			$tbl->addCell("&nbsp;");
			$tbl->addCell("&nbsp;");
			$tbl->addCell("&nbsp;");
			$tbl->addCell('&nbsp;','','data',array('style'=>'display:none;'));
			$tbl->addCell('&nbsp;','','data',array('style'=>'display:none;'));
			$tbl->addCell('&nbsp;','','data',array('style'=>'display:none;'));
			$tbl->addCell('&nbsp;','','data',array('style'=>'display:none;'));
			$tbl->addCell('&nbsp;','','data',array('style'=>'display:none;'));
			
	
			
			
		}
		
		$tbl->addRow();
		$tbl->addCell('');
		$tbl->addCell('');
		$tbl->addCell('');
		$tbl->addCell('');
		$tbl->addCell('');
		$tbl->addCell('');
		$tbl->addCell('');
		$tbl->addCell('');
		$tbl->addCell('');
		$tbl->addCell('');
		$tbl->addCell('');
		$tbl->addCell('');
		$tbl->addCell('');
		$tbl->addCell('');
		
	}
	/*
	if($row['tender'] == 'gift') { $ded_gift +=$row['total_amount'];  }
	
	
	$sub_total += $row['total_amount']; */
}












			  

?>




<?php

                                       $page_url="?route=modules/reports/sale_detail_report";
                                       $export_url = "export_sale_detail_report.php?key=33667";
                                        if(isset($_POST['btnFilter'])){
                                            foreach ($_POST as $param_name => $param_val) {
                                                if($param_name=='reciept_workstation' || $param_name=='user_id' || $param_name=='tender'|| $param_name=='customer'){
                                                    $param_val = serialize($param_val);
                                                }
                                                $page_url .= "&".$param_name."=".$param_val;
                                                $export_url .= "&".$param_name."=".$param_val;
                                            }
                                        } else if(isset($_GET['btnFilter'])){
                                            foreach ($_GET as $param_name => $param_val) {
                                                $page_url .= "&".$param_name."=".$param_val;
                                                $export_url .= "&".$param_name."=".$param_val;
                                            }
                                        } else {
                                            
                                        }
                                        
                                        $page_url .="&";


?>

<style>
.invoice-head {
    background-color: #dcefff!important;
    font-weight: bold;
}
</style>

 <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
          		Sale Detail
            <small>List of All Sale Invoices .</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?php echo SITE_ROOT; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">List of Sale Invoice</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">

 <!-- Default box -->
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Sale Detail Report</h3>
			  <h3><?php echo $default_record_msg; ?></h3>
                                <a class="btn btn-primary d-inline" href="?route=modules/reports/sale_detail_report">View All of This Month</a>
                                <a class="btn btn-success d-inline" href="#"  data-toggle="modal" data-target="#modalFilter"><i class="glyphicon glyphicon-filter"></i>&nbsp;Filter</a>
                                <a href='<?php echo $export_url; ?>' target="_blank" class="btn btn-default d-inline pull-right"><i class="glyphicon glyphicon-export"></i>&nbsp;Export</a>
			</div>

            <div class="box-body">
				<?php  echo $tbl->display(); ?>
                
                                    <?php 	
					
                                        
					echo displayPaginationBelow($setLimit,$page,$sql2,$page_url); 
				
                                        ?>
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
			<form class="form-horizontal" name="frmFilter" id="frmFilter" action="?route=modules/reports/sale_detail_report" method="POST">
			
			
			
			
			
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
			  </div>
			  
			  
			  
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
					<option <?php if(in_array('slpit', @$tender)) { echo 'SELECTED'; } ?> value="split">Split</option>		

				  </select>
				</div>
				<div class="col-sm-3">
				  <select class="form-control" name="tender_log">
					<option <?php if(@$tender_log=='=') { echo 'SELECTED'; } ?>  value="=">Include</option>
					<option <?php if(@$tender_log=='!=') { echo 'SELECTED'; } ?>  value="!=">Exclude</option>
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

		

