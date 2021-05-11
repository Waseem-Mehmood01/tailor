<?php

$text='"Are you sure to want reverse this invoice?"';

if(isset($_GET['delete'])){
	if(isset($_GET['sale_invoice_id'])){
		$rec_type = $_GET['reciept_type'];
		if($rec_type=='sale'){
		$sale_invoice_id = (int)trim($_GET['sale_invoice_id']);
		DB::update('sale_invoice', array('is_reverse'=>'1'), 'sale_invoice_id=%s', $sale_invoice_id);
		
		
		$s_detail = DB::query("SELECT sd.`item`, sd.`size`, sd.`qty` FROM sale_invoice_detail sd WHERE sd.`sale_invoice_id` = '".$sale_invoice_id."'");
		
		
		foreach($s_detail as $si){
		
		/*
		 * UPDATE STOCK ROLL BACK
		 */
		
		$u_sql = "UPDATE products_price pp SET
                    pp.`stock` = (pp.`stock` + ".(int)$si['qty'].")
                    WHERE pp.`products_id` = '".(int)$si['item']."'
                    AND pp.`size` = '".$si['size']."'";
		
		DB::query($u_sql);
		
		}
		
		 echo '<script>
			$(document).ready(function() {
				 $( "#msgDiv" ).fadeIn("slow").delay(1000).fadeOut("slow",function(){
					window.location.href="?route=modules/sale/view_my_sale"; 
				 });
			});
			 </script>';
		} else {
			echo '<script>
			alert("Return Invoice can not be reverse");
			window.location.href="?route=modules/sale/view_my_sale"; 
			 </script>';
		}

	}
}
?>

<?php

$where_clause = "";
$having_clause = "";
$default_record_msg = 'Invoices of '.date('M-Y');

$user_id = isset($_POST['user_id']) ? $_POST['user_id']: array();
if(empty($user_id)){ $user_id = isset($_GET['user_id']) ? $_GET['user_id']: array(); }
$tender = isset($_POST['tender']) ? $_POST['tender']: array();
if(empty($tender)){ $tender = isset($_GET['tender']) ? unserialize($_GET['tender']): array(); }


if(isset($_POST['btnFilter'])){
	@extract($_POST);	
} else {
        @extract($_GET);
}


	if(@$from_date<>''){
		@$from_date_time = @$from_date.' '.@$from_time;
		$where_clause .= " 
		AND si.`created_on` >= '".getDateTime(@$from_date_time, "mySQL")."'";
		$default_record_msg = 'Invoices of '.@$from_date_time.' to Now';
	}
	
	if(@$to_date<>''){
		@$to_date_time = @$to_date.' '.@$to_time;
		$where_clause .= " 
		AND si.`created_on` <= '".getDateTime(@$to_date_time, "mySQL")."'";
		$default_record_msg = 'Invoices of '.@$from_date_time.' to '.@$to_date_time;
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
			                     AND total_dis_perc NOT IN ('".@$dis_perc_from."' , '".@$dis_perc_to."') ";
			} else {
				
				
				if(@$dis_perc_from<>''){
					$having_clause .= " 
						AND total_dis_perc >= '".@$dis_perc_from."' ";
				}
				
				if(@$dis_perc_to<>''){
						$having_clause.= " 
						AND  total_dis_perc <= '".@$dis_perc_to."' ";
				} 
			}
		} else {
				if(@$dis_perc_from<>''){
					if(@$dis_perc_log=='='){
						$having_clause .= " 
                        AND  total_dis_perc >= '".@$dis_perc_from."' ";
					} 
				}
				
				if(@$dis_perc_to<>''){
					if(@$dis_perc_log=='='){
						$having_clause.= " 
						AND total_dis_perc <= '".@$dis_perc_to."' ";
					}
			
				}
		}
		
		
		
		
		if(@$dis_amount_from<>'' AND @$dis_amount_to<>''){

			if(@$dis_amount_log <> '='){
				$having_clause .=" 
			                     AND total_dis NOT IN ('".@$dis_amount_from."' , '".@$dis_amount_to."') ";
			} else {
				
				
				if(@$dis_amount_from<>''){
						$having_clause .= " 
						AND total_dis >= '".@$dis_amount_from."' ";
				}
				
				if(@$dis_amount_to<>''){
						$having_clause .= " 
						AND total_dis <= '".@$dis_amount_to."' ";
				} 
			}
		} else {
				if(@$dis_amount_from<>''){
					if(@$dis_amount_log=='='){
						$having_clause .= " 
						AND total_dis >= '".@$dis_amount_from."' ";
					} 
				}
				
				if(@$dis_amount_to<>''){
					if(@$dis_amount_log=='='){
						$having_clause .= " 
						AND total_dis <= '".@$dis_amount_to."' ";
					}
			
				}
		}
	
	

	
	



if(@$from_date=='' AND @$to_date==''){
 $where_clause .=" 
 AND MONTH(si.`created_on`) = MONTH('".getDateTime('0',"mySQL")."') 
AND YEAR(si.`created_on`) = YEAR('".getDateTime('0',"mySQL")."') ";	
}




/*
 *
 *  WALK-IN SALE ONLY
 *
 */
 
 if(isset($_POST['searchPhone'])){
  $where_clause = '';
  $having_clause = '';
  $where_clause .= " AND si.`customer_phone`='".$_POST['customer_phone']."' ";
  
 }


//$where_clause .= " AND si.`created_by`='".$_SESSION['user_name']."' ";




?>




<?php
//Draft Expense Voucher
$tbl = new HTML_Table('', 'table table-hover table-striped table-bordered');
$tbl->addTSection('thead');
$tbl->addRow();
$tbl->addCell('Invoice #', '', 'header');
$tbl->addCell('Visited', '', 'header');
$tbl->addCell('Cashier', '', 'header');
$tbl->addCell('Tender', '', 'header');
$tbl->addCell('Phone', '', 'header');
$tbl->addCell('No. of Item', '', 'header');
$tbl->addCell('Sub Total', '', 'header');
$tbl->addCell('Disc', '', 'header');
$tbl->addCell('Tax', '', 'header');
$tbl->addCell('Total Amount', '', 'header');
$tbl->addCell('Date', '', 'header');
$tbl->addCell('Actions', '', 'header');
$tbl->addTSection('tbody');
?>

<?php
        
        if($having_clause<>''){ 
            $having_clause = " HAVING 1=1 ".$having_clause;
        }
        
        if(isset($_GET["page"])) {
		$page = (int)$_GET["page"];
	} else{
	$page = 1;	
	}
	
	$setLimit = 20;
	$pageLimit = ($page * $setLimit) - $setLimit;
  /*      
$sql = "SELECT * FROM sale_invoice s WHERE s.`is_reverse`='0' ".$where_clause." ".$having_clause." ORDER BY s.`sale_invoice_id` DESC";
*/
        $sql = "SELECT sd.`item`, sd.`description`,  si.*,  
		si.`created_on` AS last_sold_on,
		si.`total_amount` AS total_sale,
		si.`dis_perc` AS total_dis_perc, si.`dis_amount` AS total_dis,
		si.`tax_perc` AS total_tax_perc, si.`tax_amount` AS total_tax
		 FROM sale_invoice_detail sd 
				LEFT JOIN sale_invoice si
				ON(si.`sale_invoice_id`=sd.`sale_invoice_id` AND  si.`reciept_type`='sale'  AND si.`is_reverse`='0') 
				WHERE 1=1 ".$where_clause." 
				 GROUP BY si.`sale_invoice_id` ".$having_clause." ORDER BY sale_invoice_id DESC";
        
        $sql2 = $sql;
	$total_q = DB::query($sql2);
	$total_records = DB::count();
        $sql .= ' LIMIT '.$pageLimit.', '.$setLimit;
        
        $cost_total = 0.00;

/*        
  echo '<pre>';
echo $sql;
echo '</pre>'; */


 $res = DB::query($sql);       
foreach($res as $row) {	
$tbl->addRow();
$tbl->addCell($row['sale_invoice_id']);

$counter = DB::queryFirstRow("select `counter`, `browser` from  invoice_viewed where sale_invoice_id='".$row['sale_invoice_id']."'");

if($counter['counter']>0){
    $is_visited = '<span title="'.$counter['counter'].' Time | Browser: '.$counter['browser'].'" class="text-success">Yes</span>';
} else {
    $is_visited = '<span class="text-danger">No</span>';
}
$tbl->addCell($is_visited);
$tbl->addCell(strtoupper($row['created_by']));
$tbl->addCell(strtoupper($row['tender']));
$tbl->addCell($row['customer_phone']);
$tbl->addCell($row['no_of_item']);
$tbl->addCell('$'.$row['sub_total']);
$tbl->addCell($row['dis_amount'].' ['.$row['dis_perc'].'%]');
$tbl->addCell('$'.$row['tax_amount']);
$tbl->addCell('<b>$'.$row['total_amount'].'</b>');
$tbl->addCell(date("d-m-Y", strtotime($row['date'])));
$tbl->addCell("<div class='btn-group' role='group'>

<a class='pull btn btn-default btn-xs' href ='?route=modules/sale/sale_invoice_view&invoice_id=".$row['sale_invoice_id']."'>Detail&nbsp;<span class='glyphicon glyphicon-edit'></span></a>
<button type='button' class='btn btn-primary btn-xs dropdown-toggle' data-toggle='dropdown'>
    More.. <span class='caret'></span></button>
    <ul class='dropdown-menu' role='menu'>
	<!--<li><a class='' href ='?route=modules/sale/sale_invoice_edit&invoice_id=".$row['sale_invoice_id']."'><span class='glyphicon glyphicon-pencil'></span>Edit&nbsp;</a></li>-->
	<li><a class='' href ='?route=modules/sale/view_my_sale&sale_invoice_id=".$row['sale_invoice_id']."&delete=yes&reciept_type=".$row['reciept_type']."' onclick='return confirm(".$text.");'><span class='glyphicon glyphicon-share-alt'></span>Reverse Sale&nbsp;</a></li> 
	</ul> 
	</div>
");

}
			  

?>



 <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
          		Walk-in Customer Sale
            <small>List of All Sale Invoice Entry .</small>
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
              <h4 class=""><?php echo $default_record_msg; ?></h4>
                        <a class="btn btn-primary d-inline" href="?route=modules/sale/view_my_sale">View All of This Month</a>
                        <div class="col-md-2 pull-right">
						
											<form method="POST" class="form-inline" action="">
												<div class="input-group">
													<input type="text" name="customer_phone" id="customer_phone" value="<?php echo @$_POST['customer_phone']; ?>" placeholder="Seach by phone" class="form-control" maxlength="10"  onkeypress="return IsNumeric(event);" required/>
										<span class="input-group-btn">
										    
															<button type="submit" id="searchPhone" name="searchPhone" class="btn btn-default" type="button"><i class="fa fa-search"></i></button>
														</span>
												</div>
												</form>
                 </div>
               
                        
                <div class="row">
           
            </div>          
			<a class="btn btn-success d-inline" href="#"  data-toggle="modal" data-target="#modalFilter"><i class="glyphicon glyphicon-filter"></i>&nbsp;Filter</a>
                        <a class="btn btn-default d-inline pull-right" href="?route=modules/sale/sale_invoice"><i class="glyphicon glyphicon-plus"></i>&nbsp;Add New Invoice</a>
                          
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
			<div id="msgDiv" style="display:none;" class="alert alert-success alert-dismissible col-md-6 col-md-offset-3" >
						 <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<h4><i class="icon fa fa-check"></i> Return!</h4>
						Invoice return back successfully.
					</div>
            <div class="box-body">
				<?php  echo $tbl->display(); ?>
                                
                                   <?php 	
					$page_url="?route=modules/sale/view_my_sale";
                                        if(isset($_POST['btnFilter'])){
                                            foreach ($_POST as $param_name => $param_val) {
                                                if($param_name=='reciept_workstation' || $param_name=='user_id' || $param_name=='tender'|| $param_name=='customer'){
                                                    $param_val = serialize($param_val);
                                                }
                                                $page_url .= "&".$param_name."=".$param_val;
                                            }
                                        } else if(isset($_GET['btnFilter'])){
                                            foreach ($_GET as $param_name => $param_val) {
                                                $page_url .= "&".$param_name."=".$param_val;
                                            }
                                        } else {
                                            
                                        }
                                        
                                        $page_url .= "&";
                                        
                                        
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
		<form class="form-horizontal" name="frmFilter" id="frmFilter" action="?route=modules/sale/view_my_sale" method="POST">
			
			
			
			
			
			<div class="form-group">
				<label class="control-label col-sm-3">From:</label>
				<div class="col-sm-3">
				  <input type="text" class="form-control date-picker" value="<?php echo @$from_date; ?>" name="from_date" id="from_date" placeholder="dd-mm-yyyy" autocomplete="off">
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
				  <input type="text" class="form-control date-picker" value="<?php echo @$to_date; ?>" name="to_date" id="to_date" placeholder="dd-mm-yyyy" autocomplete="off">
				</div>
				<div class="col-sm-3">
				  <input type="text" class="form-control timepicker" value="<?php if(isset($to_time)){ echo @$to_time; } else { echo '11:59 PM'; } ?>" name="to_time" id="to_time" placeholder="hh:mm">
				</div>
				<div class="col-sm-3">
				  
				</div>
			  </div>
			<!-- 
			<div class="form-group">
				<label class="control-label col-sm-3">Customer:</label>
				<div class="col-sm-6">
				  <select name="customer[]" class="form-control multiselect" multiple="multiple" data-placeholder="Default All">
					<option  value="Walk-in">Walk-in</option>
					<option  value="Instagram">Instagram</option>
					
				</select>
				</div>
				<div class="col-sm-3">
				  <select class="form-control" name="customer_log">
					<option value="=">Include</option>
					<option value="!=">Exclude</option>
				  </select>
				</div>
			  </div> -->
			  


			
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
				<label class="control-label col-sm-3">Item Name:</label>
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
				<label class="control-label col-sm-3">Tender:</label>
				<div class="col-sm-6">
				  <select class="form-control multiselect" name="tender[]" multiple="multiple" data-placeholder="Default All">
					
					<option <?php if(in_array('cash', @$tender)) { echo 'SELECTED'; } ?> value="cash">Cash</option>
					<option <?php if(in_array('card', @$tender)){ echo 'SELECTED'; } ?> value="card">Card</option>	
					<option <?php if(in_array('venmo', @$tender)) { echo 'SELECTED'; } ?> value="venmo">Venmo</option>
					<option <?php if(in_array('paypal', @$tender)) { echo 'SELECTED'; } ?> value="paypal">Paypal</option>
					<option <?php if(in_array('cashapp', @$tender)) { echo 'SELECTED'; } ?> value="cashapp">Cashapp</option>

				  </select>
				</div>
				<div class="col-sm-3">
				  <select class="form-control" name="tender_log">
					<option <?php if(@$tender_log=='=') { echo 'SELECTED'; } ?>  value="=">Include</option>
					<option <?php if(@$tender_log=='!=') { echo 'SELECTED'; } ?>  value="!=">Exclude</option>
				  </select>
				</div>
			  </div>
              
              
             
			  
			  
			  
	  
			

        </div>
        <div class="modal-footer">
        <button type="submit" class="btn btn-success" name="btnFilter" id="btnFilter">Apply</button>
        	<button type="button" class="btn btn-primary" name="btnReset" id="btnReset">Clear Filter</button>
          <button type="button" class="btn btn-default" data-dismiss="modal
Success!">Cancel</button>
        </div>
      </div>
      </form>
    </div>
  </div>
  

<script type="text/javascript">

$(function(){ $("#btnReset").on("click",function(){ $(".form-control").val("");});});

</script>