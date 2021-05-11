<?php

if(isset($_GET["page"])) {
    $page = (int)$_GET["page"];
} else{
    $page = 1;
}




if(isset($_GET['keyword'])){
    $keyword = $_GET['keyword'];
} else if(isset($_POST['keyword'])){
    $keyword = $_POST['keyword'];
} else {
    $keyword = '';
}

if(isset($_GET['stype'])){
    $stype = $_GET['stype'];
} else if(isset($_POST['stype'])){
    $stype = $_POST['stype'];
} else {
    $stype = '';
}

if(isset($_GET['orderby'])){
    $orderby = $_GET['orderby'];
} else if(isset($_POST['orderby'])){
    $orderby = $_POST['orderby'];
} else {
    $orderby = 'p.`created_on`';
}

if(isset($_GET['ordertype'])){
    $ordertype = $_GET['ordertype'];
} else if(isset($_POST['ordertype'])){
    $ordertype = $_POST['ordertype'];
} else {
    $ordertype = 'ASC';
}

$setLimit = 15;

$pageLimit = ($page * $setLimit) - $setLimit;








?>




<?php
$sql = "SELECT * FROM meta_tags ";



if($keyword<>''){
    
    switch($stype){
        case 'product':
        $sql .=" WHERE `meta_type` = 'product' AND `id`='".(int)trim($keyword)."'";
        break;
        case 'category':
            $sql .="  WHERE `meta_type` = 'category' AND `id`='".(int)trim($keyword)."'";
        break;
       
        default:
        break;
    }
}


if($ordertype=='DESC'){ $oType = 'ASC'; } else { $oType = 'DESC'; }

  
    $sql2 = $sql;
	$sql .= ' LIMIT '.$pageLimit.', '.$setLimit;

?>

<?php
//Draft Expense Voucher
$tbl = new HTML_Table('', 'table table-hover table-striped table-bordered');
$tbl->addTSection('thead');
$tbl->addRow();

$tbl->addCell('Type', '', 'header');
$tbl->addCell('Name', '', 'header');
$tbl->addCell('Title', '', 'header');
$tbl->addCell('Description', '', 'header');
$tbl->addCell('Keyword', '', 'header');
$tbl->addCell('Author', '', 'header');
$tbl->addTSection('tbody');


$res = DB::query($sql);

foreach($res as $row) {

$tbl->addRow();
$tbl->addCell($row['meta_type']);
$tbl->addCell($row['page_name']);
$tbl->addCell($row['title']);
$tbl->addCell($row['description']);
$tbl->addCell($row['keywords']);
$tbl->addCell($row['author']);


}
			  

?>



 <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
          		Meta Tags
            <small>SEO</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?php echo SITE_ROOT; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">SEO</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
			
 <!-- Default box -->

          <div class="box">
            <div class="box-header with-border">
              <a href="#"><h3 class="box-title btn btn-success">Add Meta</h3></a>
              
            </div>
	
            <div class="box-body"> 
					
					<div class="col-md-4 pull-right">
						
											<form method="POST" class="form-inline" action="">
												<div class="input-group">
													<input type="text" name="keyword" value="<?php echo $keyword; ?>" placeholder="Seach.." class="form-control" />
													<span class="input-group-addon">
													<select name="stype">
														<option value="barcode" <?php if($stype=='barcode'){ echo 'SELECTED'; } ?>>Barcode</option>
														<option value="name" <?php if($stype=='name'){ echo 'SELECTED'; } ?>>Name</option>
														<option value="products_id" <?php if($stype=='products_id'){ echo 'SELECTED'; } ?>>Item ID</option>
													</select></span>
										<span class="input-group-btn">
										    
															<button type="submit" id="searchItem" name="searchItem" class="btn btn-default" type="button"><i class="fa fa-search"></i></button>
														</span>
												</div>
								
											</form>
                                                
					
				</div>
							
				<?php  echo $tbl->display(); ?>
			
				<?php 	
					$page_url="?route=modules/shop/inventory&stype=".$stype."&keyword=".$keyword."&orderby=".$orderby."&ordertype=".$ordertype."&";
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
