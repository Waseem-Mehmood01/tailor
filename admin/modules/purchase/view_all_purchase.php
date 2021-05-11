<?php

$where_clause = "";
$having_clause = "";
$default_record_msg = 'Invoices of '.date('M-Y');

$supplier = isset($_POST['supplier']) ? $_POST['supplier']: array();
if(empty($supplier)){ $supplier = isset($_GET['supplier']) ? $_GET['supplier']: array(); }


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
		
		
		
		

	

	

	

	
	if(!empty($supplier)){
                                
	    if(is_serialized($supplier)){
	        $supplier = unserialize($supplier);
                                }
		
           $supplier_array = implode("','", @$supplier);

		
			$where_clause .=" 
			AND si.`supplier_id` IN ('". $supplier_array."') ";
		
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
 

 




?>




<?php
//Draft Expense Voucher
$tbl = new HTML_Table('', 'table table-hover table-striped table-bordered');
$tbl->addTSection('thead');
$tbl->addRow();
$tbl->addCell('Invoice #', '', 'header');
$tbl->addCell('Supplier', '', 'header');
$tbl->addCell('No. of Item', '', 'header');
$tbl->addCell('Sub Total', '', 'header');
$tbl->addCell('Discount', '', 'header');
$tbl->addCell('Tax', '', 'header');
$tbl->addCell('Purchase Total', '', 'header');
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
$sql = "SELECT * FROM sale_invoice s WHERE s.`is_reverse`='0' ".$where_clause." ".$having_clause." ORDER BY s.`purchase_invoice_id` DESC";
*/
        $sql = "SELECT sd.`item`, sd.`description`,  si.*,  
		si.`created_on` AS last_sold_on,
		si.`total_amount` AS total_sale, 
		si.`dis_perc` AS total_dis_perc, si.`dis_amount` AS total_dis,
		si.`tax_perc` AS total_tax_perc, si.`tax_amount` AS total_tax
		 FROM purchase_invoice_detail sd 
				LEFT JOIN purchase_invoice si
				ON(si.`purchase_invoice_id`=sd.`purchase_invoice_id`  AND si.`is_reverse`='0') 
				WHERE 1=1 ".$where_clause." 
				 GROUP BY si.`purchase_invoice_id` ".$having_clause." ORDER BY purchase_invoice_id DESC";
        
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
$tbl->addCell($row['purchase_invoice_id']);
$tbl->addCell(strtoupper($row['supplier']));
$tbl->addCell($row['no_of_item']);
$tbl->addCell('$'.$row['sub_total']);
$tbl->addCell($row['dis_amount'].' ['.$row['dis_perc'].'%]');
$tbl->addCell('$'.$row['tax_amount']);
$tbl->addCell('<b>$'.$row['total_amount'].'</b>');
$tbl->addCell(date("d-m-Y", strtotime($row['date'])));
$tbl->addCell("
<a class='pull btn btn-default btn-xs' href ='?route=modules/purchase/purchase_invoice_view&invoice_id=".$row['purchase_invoice_id']."'>Detail&nbsp;<span class='glyphicon glyphicon-edit'></span></a>
");

}
			  

?>



 <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
          		Purchase
            <small>List of All Purchase Invoice Entry .</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?php echo SITE_ROOT; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">List of Purchase Invoice</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">

 <!-- Default box -->
          <div class="box">
            <div class="box-header with-border">
              <h4 class=""><?php echo $default_record_msg; ?></h4>
                        <a class="btn btn-primary d-inline" href="?route=modules/purchase/view_all_purchase">View All of This Month</a>
                        
                        
			<a class="btn btn-success d-inline" href="#"  data-toggle="modal" data-target="#modalFilter"><i class="glyphicon glyphicon-filter"></i>&nbsp;Filter</a>
                        <a class="btn btn-default d-inline pull-right" href="?route=modules/purchase/new_purchase"><i class="glyphicon glyphicon-plus"></i>&nbsp;Add New Invoice</a>
                          
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
			
            <div class="box-body">
				<?php  echo $tbl->display(); ?>
                                
                                   <?php 	
					$page_url="?route=modules/purchase/view_all_purchase";
                                        if(isset($_POST['btnFilter'])){
                                            foreach ($_POST as $param_name => $param_val) {
                                                if( $param_name=='supplier'){
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
		<form class="form-horizontal" name="frmFilter" id="frmFilter" action="?route=modules/purchase/view_all_purchase" method="POST">
			
			
			
			
			
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
			


			
			  
			  
			  
			 
			  
			  
			  
			
			  
			  
			  
              
              
               <div class="form-group">
				<label class="control-label col-sm-3">Supplier:</label>
				<div class="col-sm-6">
				  <select class="form-control multiselect" multiple="multiple" data-placeholder="Default All" name="supplier[]" class="form-control">
					<?php 
					$suppliers = DB::query("SELECT m.`manufacturers_id`, m.`name` FROM manufacturers m ORDER BY m.`name`");
					foreach($suppliers as $supp){
					    echo '<option';
					    if($supp['manufacturers_id'] == @$supplier) { echo 'SELECTED'; }
					    echo ' value="'.$supp['manufacturers_id'].'">'.$supp['name'].'</option>';
					}
					?>
					
					

				  </select>
				</div>
				<div class="col-sm-3">
				  
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
