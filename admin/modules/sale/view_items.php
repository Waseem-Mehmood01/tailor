<?php

$price_level='';
$alertMsg = "";
$text="'Are you sure to want delete?'";
	if(isset($_GET["page"])) {
		$page = (int)$_GET["page"];
	} else{
	$page = 1;	
	}
	
	$setLimit = 30;
	$pageLimit = ($page * $setLimit) - $setLimit;





if(isset($_GET['item_no'])){
	$item_no = $_GET['item_no'];
} else if(isset($_POST['item_no'])){
 	$item_no = $_POST['item_no'];
} else {
	$item_no = '';
}
if(isset($_POST['price_level'])){
	$price_level=$_POST['price_level'];
} else if(isset($_GET['price_level'])){
	$price_level=$_GET['price_level'];
} else {
	$price_level = '';
}
if(isset($_POST['order_by'])){
	$order_by=$_POST['order_by'];
} else if(isset($_GET['order_by'])){
	$order_by=$_GET['order_by'];
} else {
	$order_by = '';
}
if(isset($_POST['sorting_by'])){
	$sorting_by=$_POST['sorting_by'];
} else if(isset($_GET['sorting_by'])){
	$sorting_by=$_GET['sorting_by'];
} else {
	$sorting_by = '';
}
if(!isset($_GET['page'])){
	if($sorting_by == 'ASC'){
		$sorting_by = 'DESC';
	} else {
		$sorting_by = 'ASC';
	}
}
?>




<?php
$sql = "SELECT * FROM inventory  WHERE products_name!=''";


if($price_level<>''){
	$sql .="AND (".$price_level."<>'' OR  ".$price_level."<>'0.00') ";
}


if($item_no<>''){
	
	if(is_numeric($item_no)){
		$sql .=" AND item_no='".$item_no."' ";
	} else {
		$sql .=" AND products_name like '%".$item_no."%' ";
	}
	
	
}

if($order_by<>''){
	$sql .=" ORDER BY ".$order_by." ".$sorting_by." ";		
}
 
//echo 'SOrt '.$sorting_by;
	$sql2 = $sql;
	DB::query($sql2);
	$total_records = DB::count();
	//echo $total_records;
	$sql .= ' LIMIT '.$pageLimit.', '.$setLimit;
	//echo $sql;
?>
<style type="text/css">
<?php if($price_level<>''){ $i = preg_replace("/[^0-9]/","",$price_level); $i=$i+6; ?>
	table > thead > tr > th:nth-child(<?php echo $i; ?>){
		background-color: rgba(128, 106, 0, 0.32);
	}
	table > tbody > tr > td:nth-child(<?php echo $i; ?>){
		background-color: rgba(128, 106, 0, 0.32);
	}
	
<?php } ?>
</style>
<?php
//Draft Expense Voucher
$tbl = new HTML_Table('', 'table table-hover table-striped table-bordered');
$tbl->addTSection('thead');
$tbl->addRow();
$tbl->addCell('<a href="?route=modules/inventory/inventory&item_no='.$item_no.'&price_level='.$price_level.'&order_by=item_no&sorting_by='.$sorting_by.'">Item Code</a>', '', 'header');
$tbl->addCell('<a href="?route=modules/inventory/inventory&item_no='.$item_no.'&price_level='.$price_level.'&order_by=dcs&sorting_by='.$sorting_by.'">DCS</a>', '', 'header');
$tbl->addCell('<a href="?route=modules/inventory/inventory&item_no='.$item_no.'&price_level='.$price_level.'&order_by=products_name&sorting_by='.$sorting_by.'">Description</a>', '', 'header');
$tbl->addCell('<a href="?route=modules/inventory/inventory&item_no='.$item_no.'&price_level='.$price_level.'&order_by=unit_case&sorting_by='.$sorting_by.'">Unit Case</a>', '', 'header');
$tbl->addCell('<a href="?route=modules/inventory/inventory&item_no='.$item_no.'&price_level='.$price_level.'&order_by=packing&sorting_by='.$sorting_by.'">Packing</a>', '', 'header');
$tbl->addCell('<a href="?route=modules/inventory/inventory&item_no='.$item_no.'&price_level='.$price_level.'&order_by=retail_price&sorting_by='.$sorting_by.'">Retail Price</a>', '', 'header');

$tbl->addTSection('tbody');

/* 
//get customers name according to price levels
$cus1=DB::queryFirstField("SELECT name FROM customers where price_level like 'pl1'");
$cus2=DB::queryFirstField("SELECT name FROM customers where price_level like 'pl2'");
$cus3=DB::queryFirstField("SELECT name FROM customers where price_level like 'pl3'");
$cus4=DB::queryFirstField("SELECT name FROM customers where price_level like 'pl4'");
$cus5=DB::queryFirstField("SELECT name FROM customers where price_level like 'pl5'");
$cus6=DB::queryFirstField("SELECT name FROM customers where price_level like 'pl6'");
$cus7=DB::queryFirstField("SELECT name FROM customers where price_level like 'pl7'");
$cus8=DB::queryFirstField("SELECT name FROM customers where price_level like 'pl8'");
$cus9=DB::queryFirstField("SELECT name FROM customers where price_level like 'pl9'"); 
$cus10=DB::queryFirstField("SELECT name FROM customers where price_level like 'pl10'"); 
 * 
 */
$res = DB::query($sql);

foreach($res as $row) {
$combine_qty = '';	
$tbl->addRow();
$tbl->addCell($row['item_no']);
$tbl->addCell(stripslashes($row['dcs']));
$tbl->addCell(stripslashes($row['products_name']));
$tbl->addCell($row['unit_case']);
$tbl->addCell($row['packing']);
$tbl->addCell($row['retail_price']);

}
			  

?>



 <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
          		Inventory
            <small>Detail of inventory.</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?php echo SITE_ROOT; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Detail Inventory</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
			
 <!-- Default box -->

          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">All inventory items</h3>
              
            </div>
	
                        <div class="col-md-12">
                            <div class="col-md-4 pull-right">   
                                            <div id="msgDiv" style="display:none;" class="alert alert-success alert-dismissible col-md-6 col-md-offset-3" >
                                                     <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                                    <h4><?php echo $alertMsg; ?></h4>

                                            </div>
                            </div>
                        </div>
            <div class="box-body"> 
					<div class="pull-left">
						Total Records: <?php echo $total_records; ?> [Page: <?php echo $page; ?>]				
					</div>
					<div class="col-md-4 pull-right">
						
											<form method="POST" class="form-inline" action="?route=modules/sale/view_items">
												<div class="input-group">
													<input type="text" name="item_no" id="item_no" value="<?php echo $item_no; ?>" placeholder="Seach Item.." class="form-control" />
														<span class="input-group-btn">
															<button type="submit" id="searchItem" name="searchItem" class="btn btn-default" type="button"><i class="fa fa-search"></i></button>
														</span>
												</div>
												
											</form>
                                                
					
				</div>
				</form>
				<?php  echo $tbl->display(); ?>
			
				<?php 	
					$page_url="?route=modules/inventory/inventory&order_by=".$order_by."&sorting_by=".$sorting_by."&item_no=".$item_no."&price_level=".$price_level."&";
					echo displayPaginationBelow($setLimit,$page,$sql2,$page_url); 
				?>


            </div><!-- /.box-body -->
            <div class="box-footer">
             
            </div>
          </div><!-- /.box -->
		</section> 
<script>
	$(function(){
	
		
		$("#item_no").on('keydown keyup',function(e){
			console.log(e.keyCode);
			if(e.keyCode=='13'){
				$("#searchItem").trigger('click');		
			}		
			});
	});
</script>
