<?php

$where_clause = "";
$having_clause = "";
$default_record_msg = 'Items Sold '.date('M-Y');

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
    $default_record_msg = 'Items Sold '.@$from_date_time.' to Now';
}

if(@$to_date<>''){
    @$to_date_time = @$to_date.' '.@$to_time;
    $where_clause .= "
		AND si.`created_on` <= '".getDateTime(@$to_date_time, "mySQL")."'";
    $default_record_msg = 'Items Sold'.@$from_date_time.' to '.@$to_date_time;
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










if(@$from_date=='' AND @$to_date==''){
    $where_clause .="
 AND MONTH(si.`created_on`) = MONTH('".getDateTime('0',"mySQL")."')
AND YEAR(si.`created_on`) = YEAR('".getDateTime('0',"mySQL")."') ";
}



?>





<?php

$tbl = new HTML_Table('', 'table table-hover table-striped table-bordered');
$tbl->addTSection('thead');
$tbl->addRow();
$tbl->addCell('Item #', '', 'header');
$tbl->addCell('Category', '', 'header');
$tbl->addCell('Name', '', 'header');
$tbl->addCell('No. of Sold', '', 'header');
$tbl->addCell('Last Sold', '', 'header');

$tbl->addTSection('tbody');
?>

<?php

if($having_clause<>''){ $having_clause = " HAVING 1=1 ".$having_clause; }

if(isset($_GET["page"])) {
		$page = (int)$_GET["page"];
	} else{
	$page = 1;	
	}
	
	$setLimit = 20;
	$pageLimit = ($page * $setLimit) - $setLimit;
        
$sql = "SELECT sd.`item` ,sd.`description`,si.`sale_invoice_id`,  
		MAX(si.`created_on`) AS last_sold_on,
		SUM(sd.`qty`) AS total_qty
		 FROM sale_invoice_detail sd 
				LEFT JOIN sale_invoice si
				ON(si.`sale_invoice_id`=sd.`sale_invoice_id` AND si.`reciept_type`='sale' 
                  AND si.`is_reverse`='0') 
				WHERE 1=1 ".$where_clause." 
				 GROUP BY sd.`item` ".$having_clause." ORDER BY last_sold_on DESC";

        $total_earning = 0.00;
        $ded_gift = 0.00;
        $total_deductions = 0.00;
        $sub_total = 0.00; 
        $gift_cash = 0;
        $gift_gift = 0;
        $sql2 = $sql;
	$res1 = DB::query($sql2);
	$total_records = DB::count();
	$sql .= ' LIMIT '.$pageLimit.', '.$setLimit;

/*
echo '<pre>';
echo $sql;
echo '</pre>';
*/


$res = DB::query($sql);

foreach($res as $row) {

$tbl->addRow();
$tbl->addCell($row['item']);
$tbl->addCell(get_product_category($row['item']));
$tbl->addCell(stripslashes($row['description']));
$tbl->addCell($row['total_qty']);
$tbl->addCell(getDateTime($row['last_sold_on'], "dtShort"));

 
}








?>





<?php

                                    $page_url="?route=modules/reports/items_sold";
                                   
                                    
                                        if(isset($_POST['btnFilter'])){
                                            foreach ($_POST as $param_name => $param_val) {
                                                if($param_name=='reciept_workstation' || $param_name=='user_id' || $param_name=='tender'|| $param_name=='customer'){
                                                    $param_val = serialize($param_val);
                                                }
                                                $page_url .= "&".$param_name."=".$param_val;
                                                $export_url .= "&".$param_name."=".$param_val;
                                                $export_rpro .= "&".$param_name."=".$param_val;
                                                $export_rpro_shop .= "&".$param_name."=".$param_val;
                                            }
                                        } else if(isset($_GET['btnFilter'])){
                                            foreach ($_GET as $param_name => $param_val) {
                                                $page_url .= "&".$param_name."=".$param_val;
                                               
                                            }
                                        } else {
                                            
                                        }
                                        
										
							
										
                                        $page_url .="&";



?>



 <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
          		Report
            <small>Items Sold.</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?php echo SITE_ROOT; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Items Sold Report</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">

 <!-- Default box -->
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Items Sold Report</h3>
                                <h3><?php echo $default_record_msg; ?></h3>
                                <a class="btn btn-primary d-inline" href="?route=modules/reports/items_sold">View All of This Month</a>
                                <a class="btn btn-success d-inline" href="#"  data-toggle="modal" data-target="#modalFilter"><i class="glyphicon glyphicon-filter"></i>&nbsp;Filter</a>                                

			</div>

            <div class="box-body">

					<div class="">
						<?php echo $tbl->display(); ?>
					</div>
                
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
			<form class="form-horizontal" name="frmFilter" id="frmFilter" action="?route=modules/reports/items_sold" method="POST">
			
			
			
			
			
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
					<select id="cID" name="cID" class="form-control">
								<option value="">-ALL-</option>
								<?php
								$cate = DB::query("SELECT c.`categories_id`,c.`parent_id`, c.`name` FROM categories c");
								foreach($cate as $cat){
									echo '<option value="'.$cat['categories_id'].'"';
									if(@$cID==$cat['categories_id']){ echo 'SELECTED'; }
									echo '>';
									if($cat['parent_id'] <> '0') {
										echo '-';
									}
									echo $cat['name'].'</option>';
								} ?>
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
				<label class="control-label col-sm-3">Tender:</label>
				<div class="col-sm-6">
				  <select class="form-control multiselect" name="tender[]" multiple="multiple" data-placeholder="Default All">
					
					<option <?php if(in_array('cash', @$tender)) { echo 'SELECTED'; } ?> value="cash">Cash</option>
					<option <?php if(in_array('card', @$tender)){ echo 'SELECTED'; } ?> value="card">Card</option>	
					<option <?php if(in_array('slpit', @$tender)) { echo 'SELECTED'; } ?> value="split">Split</option>	
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
              
              
             
			  
			  
			
			  
			  
			  
			  
			  <div class="form-group">
				<label class="control-label col-sm-3">Customer:</label>
				<div class="col-sm-6">
				  <select name="customer[]" class="form-control multiselect" multiple="multiple" data-placeholder="Default All">
					<option value="Walk-in">Walk-in</option>
					<option value="Instagram">Instagram</option>
				
					
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

