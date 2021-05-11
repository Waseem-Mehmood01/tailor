<?php

$where_clause = "";

if(isset($_POST['btnFilter'])){
    @extract($_POST);
} else {
    @extract($_GET);
}


if(@$from_date<>''){;
    $where_clause .= "
		AND DATE(created_on) >= DATE('".date('Y-m-d', strtotime(@$from_date))."') ";
}

if(@$to_date<>''){
    $where_clause .= "
		AND DATE(created_on) <= DATE('".date('Y-m-d', strtotime(@$to_date))."') ";
}

if(@$adjustment_type<>''){
    $where_clause .= "
		AND adjustment_type = '".@$adjustment_type."' ";
}

if(@$item_no_from<>''){
    $where_clause .= "
		AND item_no >= '".@$item_no_from."' ";
}

if(@$item_no_to<>''){
    $where_clause .= "
		AND item_no <= '".@$item_no_to."' ";
}





$tbl = new HTML_Table('', 'table table-hover table-striped table-bordered');
$tbl->addTSection('thead');
$tbl->addRow();
$tbl->addCell('Adjust Type', '', 'header');
$tbl->addCell('Item#', '', 'header');
$tbl->addCell('Old Price [PL]', '', 'header');
$tbl->addCell('New Price [PL]', '', 'header');
$tbl->addCell('Old Cost [Vendor]', '', 'header');
$tbl->addCell('New Cost [Vendor]', '', 'header');
$tbl->addCell('Old Qty [Store]', '', 'header');
$tbl->addCell('New Qty [Store]', '', 'header');
$tbl->addCell('Adjust By', '', 'header');
$tbl->addCell('Adjust on', '', 'header');
$tbl->addCell('Comment1', '', 'header');
$tbl->addCell('Comment2', '', 'header');

$tbl->addTSection('tbody');


if(isset($_GET["page"])) {
		$page = (int)$_GET["page"];
	} else{
	$page = 1;	
	}
	
	$setLimit = 20;
	$pageLimit = ($page * $setLimit) - $setLimit;
        
    $sql = "SELECT * FROM adjustment_memos WHERE 1=1 ".$where_clause." ORDER BY adjustment_memos_id DESC";

	$total_records = DB::count();
	$sql2 = $sql;
	$sql .= ' LIMIT '.$pageLimit.', '.$setLimit;

/*
echo '<pre>';
echo $sql;
echo '</pre>';
*/


$res = DB::query($sql);

foreach($res as $row) {

$tbl->addRow();
$tbl->addCell(strtoupper($row['adjustment_type']));
$tbl->addCell($row['item_no']);
if($row['old_price']<>''){
$tbl->addCell($row['old_price'].' ['.$row['pl'].']');
} else { $tbl->addCell(' '); }
if($row['new_price']<>''){
$tbl->addCell($row['new_price'].' ['.$row['pl'].']');
} else { $tbl->addCell(' '); }
if($row['old_cost']<>''){
$tbl->addCell($row['old_cost'].' ['.$row['vendors_name'].']');
} else { $tbl->addCell(' '); }
if($row['new_cost']<>''){
$tbl->addCell($row['new_cost'].' ['.$row['vendors_name'].']');
} else { $tbl->addCell(' '); }
if($row['old_qty']<>''){
$tbl->addCell($row['old_qty'].' ['.$row['stores_name'].']');
} else { $tbl->addCell(' '); }
if($row['new_qty']<>''){
$tbl->addCell($row['new_qty'].' ['.$row['stores_name'].']');
} else { $tbl->addCell(' '); }
$tbl->addCell($row['created_by']);
$tbl->addCell(getDateTime($row['created_on'], "dtShort"));
$tbl->addCell($row['comment1']);
$tbl->addCell($row['comment2']);

 
}








                                    $page_url="?route=modules/reports/view_adjust_memos";
                                    
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
                                        
										
							
										
                                        $page_url .="&";



?>



 <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
          		Report
            <small>Adjustments.</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?php echo SITE_ROOT; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Adjustments Report</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">

 <!-- Default box -->
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title"><a href="?route=modules/reports/view_adjust_memos">View Adjustments</a></h3>
                                <h3></h3>
                               <a class="btn btn-success pull-right" href="#"  data-toggle="modal" data-target="#modalFilter"><i class="glyphicon glyphicon-filter"></i>&nbsp;Filter</a>                                
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
			<form class="form-horizontal" name="frmFilter" id="frmFilter" action="?route=modules/reports/view_adjust_memos" method="POST">
			
			
			
			
			
			<div class="form-group">
				<label class="control-label col-sm-3">From:</label>
				<div class="col-sm-6">
				  <input type="text" class="form-control date-picker" value="<?php echo @$from_date; ?>" name="from_date" id="from_date" autocomplete="off" placeholder="dd-mm-yyyy">
				</div>
				
			  </div>
			  
			  <div class="form-group">
				<label class="control-label col-sm-3">To:</label>
				<div class="col-sm-6">
				  <input type="text" class="form-control date-picker" value="<?php echo @$to_date; ?>" name="to_date" id="to_date" autocomplete="off" placeholder="dd-mm-yyyy">
				</div>
			  </div>
			  
			   <div class="form-group">
				<label class="control-label col-sm-3">Type:</label>
				<div class="col-sm-6">
				  <select class="form-control" name="adjustment_type" id="adjustment_type">
				  	<option <?php if(@$adjustment_type==''){ echo 'SELECTED'; } ?> value="">-All-</option>
				  	<option <?php if(@$adjustment_type=='qty'){ echo 'SELECTED'; } ?> value="qty">Qty</option>
				  	<option <?php if(@$adjustment_type=='cost'){ echo 'SELECTED'; } ?> value="cost">Cost</option>
				  	<option <?php if(@$adjustment_type=='price'){ echo 'SELECTED'; } ?> value="price">Price</option>
				  </select>
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
  



