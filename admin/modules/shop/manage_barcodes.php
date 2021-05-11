<?php
$message = '';
$item_no = isset($_POST['item_no']) ? trim($_POST['item_no']) : '';
if ($item_no == '') {
    $item_no = isset($_GET['item_no']) ? trim($_GET['item_no']) : '';
}

if (isset($_POST['addBarcode'])) {
    @extract($_POST);
    if(@$products_price_id<>''){
        $price_data = explode('-', $products_price_id);
        $price_id = $price_data[0];
        $size = $price_data[1];
    }
    
    $insert = DB::insert('items_barcodes', array(
        'products_id' => $item_no,
        'products_price_id' => @$price_id,
        'size' => @$size,
        'vendors_id' => $vendors_id,
        'barcode' => $barcode,
        'active' => 1,
        'created_on' => $now
    ));
    if ($insert) {

        $message = "Success: New Barcode Added";
    } else {
        $message = "Whoops..! Something went wrong while adding barcode";
    }
    echo '<script>
				$(function(){
					$("#msgDiv").html("' . $message . '").fadeIn("slow").delay(2000).slideUp("slow", function(){
						location.href="?route=modules/shop/manage_barcodes&item_no=' . $item_no . '";
					});
				});
			</script>';
}

if (isset($_GET['is_delete'])) {
    if ($_GET['is_delete'] == '1') {

        @extract($_GET);
        if (($id != '') and ($item_no != '')) {
            $del = DB::query("DELETE FROM items_barcodes WHERE items_barcodes_id='" . $id . "' and products_id='" . (int) $item_no . "'");
            if ($del) {

                $message = "Deleted: Barcode has been deleted.";
            } else {

                $message = "Whoops.. Something went wrong while deleting barcode";
            }
        }
    }

    echo '<script>
				$(function(){
					$("#msgDiv").html("' . $message . '").fadeIn("slow").delay(2000).slideUp("slow", function(){
						location.href="?route=modules/shop/manage_barcodes&item_no=' . $item_no . '";
					});
				});
			</script>';
}

?>

<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>Product Barcodes</h1>
	<ol class="breadcrumb">
		<li><a href="<?php echo SITE_ROOT; ?>"><i class="fa fa-dashboard"></i>
				Home</a></li>
		<li class="active">Item's Barcodes</li>
	</ol>
</section>







<!-- Main content -->
<section class="content">

	<!-- Default box -->
	<div class="box">
		<div class="box-header with-border">
			<h3 class="box-title">Item's Barcodes</h3>
			<div class="box-tools pull-right">
				<button class="btn btn-box-tool" data-widget="collapse"
					data-toggle="tooltip" title="Collapse">
					<i class="fa fa-minus"></i>
				</button>
				<button class="btn btn-box-tool" data-widget="remove"
					data-toggle="tooltip" title="Remove">
					<i class="fa fa-times"></i>
				</button>
			</div>
		</div>



		<div class="col-md-3 pull-right">
			<div class="callout callout-success" id="msgDiv"
				style="display: none;">
				<p id="msgTxt"></p>
			</div>
		</div>



		<div class="box-body">
			<div class="row">
				<div class="col-md-4 col-md-offset-3">
					<div class="row">
						<div class="form-group">

							<label class="control-label col-md-3"><h4>Product:</h4></label>
							<form id="frmSearch" method="POST" action="">
								<div class="input-group">
									<input type="text" class="form-control" required="required"
										id="item_no" name="item_no" value="<?php echo $item_no; ?>"
										maxlength="50" placeholder="Enter Item No." /> <span
										class="input-group-btn"> <input type="submit"
										class="btn btn-primary btn-flat pull-right" name="submitItem"
										value="NEXT" />
									</span>

								</div>

							</form>

						</div>
					</div>


				</div>



				<!-- Item Discription DIV -->
								
					<?php

    if ($item_no != '') {
        $i = 1;

        $is_exist =  DB::queryFirstField("select products_id from products where products_id = '" . (int) $item_no . "'");
        if ($is_exist <> '') {
        	$barcodes = DB::query("select * from items_barcodes where products_id = '" . (int) $item_no . "'");
        
            ?>
										
					<br>



				<div class="row">
					<div class="col-md-6 col-md-offset-3">
						<div class="row">
							<div class="well jumbotron">
								<h4>
									Product ID: <strong><?php echo $item_no; ?></strong>
								</h4>

								<h4>
									Product Name: <strong><?php echo get_product_name($item_no); ?></strong>
								</h4>

								<a id="btnAdd" class="btn btn-success pull-right"
									data-toggle="modal" data-target="#barcodeModal"> Add Barcode </a>
								<br>
							</div>
						</div>
					</div>

					<div class="col-md-6 col-md-offset-3">

						<table id="vendorTable"
							class="table table-bordered table-hover table-striped">
							<thead>
								<tr>

									<th><label>Vendor</label></th>
									<th><label>Size</label></th>
									<th><label>Barcode</label></th>
									<th><label>Action</label></th>
								</tr>
							</thead>
							<tbody>
															<?php
            foreach ($barcodes as $barcode) {
                ?>
															<tr>

									<td style="background: #e2f0ff;"><strong>
																<?php

                echo get_manufacturers_name($barcode['vendors_id']);

                ?>
																	</strong></td>

									<td><?php echo get_price_id_size($barcode['products_price_id']); ?></td>
									<td><strong><?php echo $barcode['barcode']; ?></strong></td>





									<td>
																<?php
                echo '<a class="btn btn-danger btn-sm" alt="Delete" title="Delete" href="?route=modules/shop/manage_barcodes&id=' . $barcode['items_barcodes_id'] . '&item_no=' . $item_no . '&is_delete=1" onclick="return confirm(\' Are you sure to want delete this item barcode? Your action would not undo \');"><i class="fa fa-trash-o"></i>&nbsp;</a>';

                ?>
																 </td>


								</tr>
															<?php $i++; } ?>
															
														</tbody>
						</table>
						<!-- 
													<div class='col-md-4 pull-left'>
											      			<button class="btn btn-default deleteBarcode" type="button" tabindex="-1">- Delete</button>
											      			<button class="btn btn-default addBarcode" type="button" tabindex="-1">+ Add Row</button>
											      	</div>  -->




					</div>



				</div>
			</div>
		</div>
		<!-- //Item Discription DIV -->
					
					<?php
        } else {
            echo '<div class="col-md-4 col-md-offset-3">
									<h4 class="pull-left">Sorry: Item/barcode does not exist. Please add barcode or contact support.
									</div>';
        }
    }
    ?>
					
            </div>
	<!-- /.box-body -->
	<div class="box-footer">
		<h4><?php echo @$message; ?>
              
						</h4>
	</div>
	</div>
	<!-- /.box -->
</section>












<!-- Modal -->
<div id="barcodeModal" class="modal fade" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Add Barcode</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" method="POST" action=""
					id="frmAddBarcode" name="frmAddBarcode">
					<div class="form-group">
						<label class="col-md-3 control-label">Vendor</label>
						<div class="col-md-6">
							<input type="hidden" name="item_no"
								value="<?php echo $item_no; ?>" /> <select name="vendors_id"
								id="vendors_id" class="form-control" required>
								<!--	<option value="">-SELECT-</option> -->
			  	<?php
    $vendors_data = DB::query("SELECT manufacturers_id, name FROM manufacturers WHERE active = 1");
    foreach ($vendors_data as $vendor_data) {
        ?>
			  	<option value="<?php echo $vendor_data['manufacturers_id']; ?>"><?php echo $vendor_data['name']; ?></option>
			  	<?php } ?>
			  </select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Size</label>
						<div class="col-md-6">
							<select name="products_price_id" id="products_price_id"
								class="form-control" required="required">
								<?php
        $sizes = DB::query("SELECT products_price_id, size FROM products_price WHERE products_id = '" . $item_no . "'");
        foreach ($sizes as $size) {
            echo '<option value="' . $size['products_price_id'].'-'.$size['size'] . '">' . $size['size'] . '</option>';
        }
        ?>
							</select>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">Barcode</label>
						<div class="col-md-6">
							<input type="text" name="barcode" id="barcode"
								class="form-control" maxlength="50" required>
							<p class="help-text text-danger" style="display: none;"
								id="txtBarcode">Barcode already assigned to another item</p>
						</div>
					</div>
			
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-success" name="addBarcode"
					id="addBarcode">Add</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
		</form>
	</div>
</div>



<script type="text/javascript">
$(function(){
		 $("#item_no").on("keypress", function(event){
			   if (event.keyCode == 13 || event.keyCode == 10) {
					$("#frmSearch").submit();
					
				}	
		 });
        $("#barcode").on("change blur keyup keypress", function(){
        	var barCode = $(this).val();
        	if(barCode!=''){
        		$.ajax({
        			method : 'POST',
        			url:	'ajax_check_barcode.php',
        			data: {barcode: barCode},
        			success: function(e){
            			
        				if(e != ''){
        					if(e!=$("#item_no").val()){
        						$('#txtBarcode').fadeIn();
        						$("#addBarcode").prop("disabled", true);
        					}
        				} else {
        					$('#txtBarcode').fadeOut('');
        					$("#addBarcode").prop("disabled", false);
        				}
        			} 
        		});
        
        	} else {
        		$('#txtBarcode').fadeOut('');
        		$("#addBarcode").prop("disabled", false);
        	}
        	
        });

   $("#barcode_type").change(function(){
		var Type = $(this).val();
		if(Type=='unit_barcode'){
			$("#divCasingOf").fadeOut();
			$("#casing_of").prop('required', false);
			$("#is_casing").val("0");
		} else {
			$("#divCasingOf").fadeIn();
			$("#casing_of").prop('required', true);
			$("#is_casing").val("1");
		}
	});
});
</script>
