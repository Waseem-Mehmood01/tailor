<?php

$alertMsg = "";

if(isset($_GET['delete'])){
    $quote_card_info_id = isset($_GET['quote_card_info_id'])?(int)trim($_GET['quote_card_info_id']):'';
    if($quote_card_info_id<>''){
        $sql = "UPDATE `quote_card_info` SET `move_trash`='0' WHERE `quote_card_info_id`='".$quote_card_info_id."'";
		
		DB::query($sql);
		
	/*	$sql = "DELETE FROM `quote_customers` WHERE `quote_card_info_id`='".(int)$quote_card_info_id."'"; */
		
		DB::query($sql);
		
		echo '<script type="text/javascript">
		<!--
		alert("Moved to Inbox");
		window.location = "?route=modules/quote/view_quotes_trash"
		//-->
		</script>';
    }
}
if(isset($_GET["page"])) {
    $page = (int)$_GET["page"];
} else{
    $page = 1;
}

$setLimit = 15;
$pageLimit = ($page * $setLimit) - $setLimit;







?>




<?php
$sql = "SELECT * FROM quote_card_info q 
JOIN quote_customers qc
ON(q.`quote_card_info_id` = qc.`quote_card_info_id`) WHERE q.`move_trash`='0' ";

if(isset($_POST['btnFilter'])){
    $sql .=" AND `".$_POST['s_type']."` LIKE '%".$_POST['keyword']."%' ";
}

 $sql .=" ORDER BY q.`created_on` DESC";
//echo 'SOrt '.$sorting_by;
	$sql2 = $sql;

	DB::query($sql2);
	$total_records = DB::count();
	//echo $total_records;
	$sql .= ' LIMIT '.$pageLimit.', '.$setLimit;
	//echo $sql;
?>

<?php
//Draft Expense Voucher
$tbl = new HTML_Table('', 'table table-hover table-striped table-bordered');
$tbl->addTSection('thead');
$tbl->addRow();
$tbl->addCell('ID#', '', 'header');
$tbl->addCell('Customer', '', 'header');
$tbl->addCell('Email', '', 'header');
$tbl->addCell('Status', '', 'header');

$tbl->addCell('Order Date', '', 'header');
$tbl->addCell('Income', '', 'header');
$tbl->addCell('Cost', '', 'header');
$tbl->addCell('Other Expense', '', 'header');
$tbl->addCell('Net Profit', '', 'header');
$tbl->addCell('Order Closed', '', 'header');
$tbl->addCell('Action', '', 'header');
$tbl->addTSection('tbody');

	if(isset($_POST['statusQuote'])){
	    if($_POST['statusQuote'] == 'New Lead'){
	        $sqlStatus = 'SELECT * FROM `quote_card_info` q
JOIN `quote_customers` c ON q.`quote_card_info_id`=c.`quote_card_info_id`
WHERE NOT EXISTS (SELECT * FROM `quote_card_info_timline` t WHERE q.`quote_card_info_id`=t.quote_card_info_id)
';
	    }else{
    $sqlStatus = 'SELECT q.quote_card_info_id,c.`fname`, c.`lname`, 
    c.`email`,c.`contact`, i.`qty`, i.`metal_stock`, 
    i.`color_front`, i.`color_back`, q.status, i.`has_sample`, i.`created_on` FROM `quote_card_info_timline` q
JOIN `quote_card_info` i ON q.quote_card_info_id = i.`quote_card_info_id`
JOIN `quote_customers` c ON i.`quote_card_info_id`=c.`quote_card_info_id`
WHERE q.status ="'.$_POST['statusQuote'].'" ';
}
    $res = DB::query($sqlStatus);
}else{
$res = DB::query($sql);
}
foreach($res as $row) {
$tbl->addRow();
$tbl->addCell($row['quote_card_info_id']);
$tbl->addCell($row['fname'].' '.$row['lname']);
$tbl->addCell($row['email']);
$tbl->addCell(get_quote_status($row['quote_card_info_id']));
$tbl->addCell(date('d-M-y H:i', strtotime($row['created_on']) ));
$tbl->addCell('0.00');
$tbl->addCell('0.00');
$tbl->addCell('0.00');
$tbl->addCell('0.00');
$tbl->addCell('');
$del_btn = '';

if($_SESSION['role_id']==1){
    $del_btn = '<a href="#" class="btn btn-sm btn-default">Close Order</a>';
}

$tbl->addCell($del_btn.'&nbsp;<a href="?route=modules/quote/detail_quote&quote_card_info_id='.$row['quote_card_info_id'].'" class="btn btn-sm btn-default">Detail</a>
');



}
			  

?>

 <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
          		Accounts
            <small>Manage Payments</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?php echo SITE_ROOT; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Manage Payments</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
			
 <!-- Default box -->

          <div class="box">
            <div class="box-header with-border">
              <a href="?route=modules/quote/view_quotes_trash"><h3 class="box-title">All Quote</h3></a>
              <div class="col-md-4 pull-right">
              	<form action="" method="POST" class="form-inline">
              	<input type="text" value="<?php echo @$_POST['keyword']; ?>" name="keyword" class="form-control" placeholder="Search">
              	<select class="form-control" name="s_type">
              		<option value="fname">Customer</option>
              		<option value="email">Email</option>
              		<option value="contact">Contact</option>
              	</select>
              	<input type="submit" class="btn btn-primary btn-sm" name="btnFilter" value="Find">
              	</form>
              </div>
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

							
				<?php  echo $tbl->display(); ?>
			
				<?php 	
					$page_url="?route=modules/quote/view_quotes_trash&";
					echo displayPaginationBelow($setLimit,$page,$sql2,$page_url); 
				?>


            </div><!-- /.box-body -->
            <div class="box-footer">
             
            </div>
          </div><!-- /.box -->
		</section> 
