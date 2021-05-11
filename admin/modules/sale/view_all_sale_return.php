<?php

$text='"Are you sure to want delete this invoice as permanent?"';
$text2='"Are you sure to want push this invoice as sale?"';
if(isset($_GET['delete'])){
	if(isset($_GET['sale_invoice_id'])){
		$sale_invoice_id = $_GET['sale_invoice_id'];
		
		DB::query("delete from sale_invoice where sale_invoice_id='".$sale_invoice_id."'");
		DB::query("delete from sale_invoice_detail where sale_invoice_id='".$sale_invoice_id."'");
		 echo '<script>
			$(document).ready(function() {
				 $( "#msgDiv" ).fadeIn("slow").delay(1000).fadeOut("slow",function(){
					window.location.href="?route=modules/sale/view_all_sale_return"; 
				 });
			});
			 </script>';

	}
}

if(isset($_GET['push_sale'])){
	if(isset($_GET['sale_invoice_id'])){
		$sale_invoice_id = $_GET['sale_invoice_id'];
		
		$invoice_details = DB::query("SELECT qty, item FROM sale_invoice_detail sd WHERE sd.`sale_invoice_id`='".$sale_invoice_id."'");
		foreach($invoice_details as $invoice_detail){
				//echo $invoice_detail['item'].'<br>';
				
					/*check if item is promo then update inventory on base of sub item*/
					if(is_promotion_item($invoice_detail['item'])){
						$promo_items = DB::query("select sub_items, qty from promotion_items where item_no='".$invoice_detail['item']."'");
						foreach($promo_items as $promo_item){
							$current_qty_pro = $promo_item['qty'];
							$get_qty_pro = DB::queryFirstField("SELECT i.`qty` FROM items_qty i WHERE i.`items_id`='".$promo_item['sub_items']."' AND stores_id='".$store."'");
							$get_qty_pro = (int)$get_qty_pro - (int)$current_qty_pro;
							$update = DB::query("update items_qty set qty='".$get_qty_pro."' where items_id='".$promo_item['sub_items']."' and stores_id='".$store."'");
						}
					}
					
				$current_qty = $invoice_detail['qty'];
				$get_qty = DB::queryFirstField("SELECT i.`qty` FROM items_qty i WHERE i.`items_id`='".$invoice_detail['item']."' AND stores_id='".$store."'");
				$get_qty = (int)$get_qty - (int)$current_qty;
				$update = DB::query("update items_qty set qty='".$get_qty."' where items_id='".$invoice_detail['item']."' and stores_id='".$store."'");
				
		}
		
		
		
		DB::Update('sale_invoice',array('is_reverse'=>'0','reciept_type'=>'sale'),'sale_invoice_id=%s',$sale_invoice_id);
		 echo '<script>
			$(document).ready(function() {
				 $( "#msgDivPush" ).fadeIn("slow").delay(1000).fadeOut("slow",function(){
					window.location.href="?route=modules/sale/view_all_sale_return"; 
				 });
			});
			 </script>';

	}
}

?>


<?php
//Draft Expense Voucher
$tbl = new HTML_Table('', 'table table-hover table-striped table-bordered');
$tbl->addTSection('thead');
$tbl->addRow();
$tbl->addCell('Invoice #', '', 'header');
$tbl->addCell('Reciept Type.', '', 'header');
$tbl->addCell('Customer', '', 'header');
$tbl->addCell('No. of Item', '', 'header');
$tbl->addCell('Total Qty', '', 'header');
$tbl->addCell('Sub Total', '', 'header');
$tbl->addCell('Discount', '', 'header');
$tbl->addCell('Tax', '', 'header');
$tbl->addCell('Total Amount', '', 'header');
$tbl->addCell('Date', '', 'header');
$tbl->addCell('Is Reverse', '', 'header');
$tbl->addCell('Actions', '', 'header');
$tbl->addTSection('tbody');
?>

<?php
if(isset($_GET["page"])) {
		$page = (int)$_GET["page"];
	} else{
	$page = 1;	
	}
	
	$setLimit = 20;
	$pageLimit = ($page * $setLimit) - $setLimit;
$sql = "SELECT * FROM sale_invoice s WHERE ( s.`is_reverse`='1' OR s.`reciept_type`='return' ) ORDER BY s.`sale_invoice_id` DESC";
$sql2 = $sql;
DB::query($sql2);
$total_records = DB::count();
$sql .= ' LIMIT '.$pageLimit.', '.$setLimit;
$res = DB::query($sql);
foreach($res as $row) {
    if($row['is_reverse'] == 1){
        $r_type = 'REVERSE';
    }else{
        $r_type = strtoupper($row['reciept_type']);
    }
$tbl->addRow();
$tbl->addCell($row['sale_invoice_id']);
$tbl->addCell($r_type);
$tbl->addCell($row['customer']);
$tbl->addCell($row['no_of_item']);
$tbl->addCell($row['total_qty']);
$tbl->addCell($row['sub_total']);
$tbl->addCell($row['dis_amount'].' ['.$row['dis_perc'].'%]');
$tbl->addCell($row['tax_amount']);
$tbl->addCell('<b>'.$row['total_amount'].'</b>');
$tbl->addCell(date("d-m-Y", strtotime($row['date'])));
if($row['is_reverse']=='1'){
    $tbl->addCell('<span class="text-danger">YES</span>');
}else{
    $tbl->addCell('<span class="text-success">NO</span>');
}

$tbl->addCell("
<a class='pull btn btn-default btn-xs' href ='?route=modules/sale/sale_invoice_view&invoice_id=".$row['sale_invoice_id']."'>Detail&nbsp;<span class='glyphicon glyphicon-edit'></span></a>

");
}
			  

?>



 <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
          		Return/Reverse Sale
            <small>List of All Return Sale Invoices.</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?php echo SITE_ROOT; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">List of Return Sale Invoice</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">

 <!-- Default box -->
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">List of All Return/Reverse Sale Invoices</h3>
			  <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
			<div id="msgDiv" style="display:none;" class="alert alert-warning alert-dismissible col-md-6 col-md-offset-3" >
						 <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<h4><i class="icon fa fa-check"></i> Deleted!</h4>
						Invoice has been deleted as permanent.
					</div>
					<div id="msgDivPush" style="display:none;" class="alert alert-success alert-dismissible col-md-6 col-md-offset-3" >
						 <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<h4><i class="icon fa fa-check"></i> Return!</h4>
						Invoice return back to sale successfully.
					</div>
            <div class="box-body">
				<?php  echo $tbl->display(); ?>
                <?php 	
					$page_url="?route=modules/sale/view_all_sale_return";
                                        if(isset($_POST['btnFilter'])){
                                            foreach ($_POST as $param_name => $param_val) {
                                                $page_url .= "&".$param_name."=".$param_val;
                                            }
                                        } else if(isset($_GET['btnFilter'])){
                                            foreach ($_GET as $param_name => $param_val) {
                                                $page_url .= "&".$param_name."=".$param_val;
                                            }
                                        } else {
                                            
                                        }
                                        
                                        $page_url .="&";
                                        
					echo displayPaginationBelow($setLimit,$page,$sql2,$page_url); 
				
                                        ?>
            </div><!-- /.box-body -->
            <div class="box-footer">
             
            </div>
          </div><!-- /.box -->
		</section> 

