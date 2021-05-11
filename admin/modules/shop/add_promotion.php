<?php
if (isset($_POST['btnSave'])) {

    @extract($_POST);

    $insert = DB::insert('products', array(
        'categories_id' => $categories_id,
        'name' => $product_name,
        'description1' => 'Promotion Item',
        'highlights' => '',
        'height' => '',
        'joint' => '',
        'active' => 1,
        'is_promotion' => 1
    ));

    $products_id = DB::insertId();

    DB::Insert('products_price', array(
        'products_id' => $products_id,
        'size' => '',
        'cost_price' => 0,
        'sale_price' => $sale_price,
        'min_bid' => 0,
        'stock' => $stock,
        'stock_check' => 1,
        'barcode' => $barcode,
        'active' => $active
    ));
    for ($i = 0, $iMaxSize = count($_POST['item']); $i < $iMaxSize; $i ++) {
        $item = (int) trim($_POST['item'][$i]);
        if ($item != '') {
            DB::insert('promotion_items', array(
                'item_no' => $products_id,
                'sub_items' => $item,
                'products_name' => $_POST['description'][$i],
                'qty' => $_POST['qty'][$i],
                'sub_item_size' => $_POST['size'][$i],
                'products_price_id' => get_product_price_id($item, $_POST['size'][$i])
            ));
        }
    }

    echo '<script>alert("New Product Saved Successfully! ");
window.location.replace("?route=modules/shop/promotions");
</script>';
}

?>

<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		Products <small>Create Promotion</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="<?php echo SITE_ROOT; ?>"><i class="fa fa-dashboard"></i>
				Home</a></li>
		<li class="active">Promo Items</li>
	</ol>
</section>
<!-- Main content -->
<section class="content">
	<!-- Default box -->
	<div class="box">

		<div class="box-body">

			<form method="POST" action="" enctype="multipart/form-data">
				<div class="form-group col-md-8">
					<div class="col-md-3">
						<label>Category <span class="text-danger">*</span></label>
					</div>
					<div class="col-md-5">
						<select name="categories_id" class="form-control"
							required="required">
							<option value="209">Offer</option>
						</select>
					</div>
				</div>

				<div class="form-group col-md-8">
					<div class="col-md-3">
						<label>Product Title</label><span class="text-danger">*</span>
					</div>
					<div class="col-md-5">
						<textarea name="product_name" placeholder="Name"
							class="form-control" required="required"></textarea>
					</div>
				</div>
				<div class="form-group col-md-8">
					<div class="col-md-3">
						<label>Sale Price</label><span class="text-danger">*</span>
					</div>
					<div class="col-md-5">
						<input type="text" name="sale_price" placeholder="$"
							class="form-control" required="required">
					</div>
				</div>
				<div class="form-group col-md-8">
					<div class="col-md-3">
						<label>Barcode</label><span class="text-danger">*</span>
					</div>
					<div class="col-md-5">
						<input type="text" name="barcode" placeholder="__"
							class="form-control" required="required">
					</div>
				</div>
				<div class="form-group col-md-8">
					<div class="col-md-3">
						<label>Stock</label>
					</div>
					<div class="col-md-5">
						<input type="text" name="stock" placeholder="0"
							class="form-control">
					</div>
				</div>
				<div class="form-group col-md-8">
					<div class="col-md-3">
						<label>Status</label>
					</div>
					<div class="col-md-5">
						<label class="radio-inline"><input type="radio" name="active"
							value="1" checked>Active</label> <label class="radio-inline"><input
							type="radio" name="active" value="0">In-Active</label>
					</div>
				</div>
				<div class='row'>

					<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>
						<h4>Sub Items</h4>
						<table id="priceTable" class="table table-bordered table-hover">
							<thead>
								<tr>
									<th width="2%"><input id="check_all" class="formcontrol"
										type="checkbox" /></th>
									<th width="10%"><label>Item</label></th>
									<th><label>Description</label></th>
									<th width="10%"><label>Size</label></th>
									<th width="10%"><label>Qty</label></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><input class="" tabindex="-1" type="checkbox" /></td>
									<td><input type="tel" autofocus class="form-control item_code"
										name="item[]" id="item_1"
										onkeypress="return IsNumeric(event);" required="required"
										autocomplete="false" onfocus="this.select();"></td>
									<input type="hidden" name="rows[]" />
									<input type="hidden" name="cost[]" id="cost_1" value="0" />
									<td><input class="form-control description" data-toggle="modal"
										data-target="#productModal" readonly="true" tabindex="-1"
										name="description[]" id="description_1" type="text"
										placeholder="Description" required /></td>
									<td><input class="form-control size" readonly="true"
										tabindex="-1" name="size[]" id="size_1" type="text"
										placeholder="Size" /></td>
									<td><input type="tel" class="form-control changesNo qty"
										name="qty[]" id="qty_1" onkeypress="return IsNumeric(event);"
										required onfocus="this.select();" required="required"
										autocomplete="false"></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>

				<div class='row'>
					<div class='col-md-3 pull-right'>
						<button class="btn btn-success btn-lg" name="btnSave"
							type="submit">
							<i class="fa fa-floppy-o" aria-hidden="true"></i> SAVE
						</button>

					</div>
				</div>
			</form>





		</div>
		<!-- /.box-body -->
		<div class="box-footer"></div>
	</div>
	<!-- /.box -->
</section>



<!-- Modal -->
<div id="productModal" class="modal fade" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Find Product</h4>
			</div>
			<form class="form-horizontal" action="">
				<div class="modal-body">

					<div class="form-group">
						<label class="control-label col-sm-2" for="products_name">Name:</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" onfocus="this.select()"
								id="products_name" autocomplete="off" placeholder="Search.."> <input
								type="hidden" value="1" id="proIndex"> <input type="hidden"
								value="" id="proID">
							<div id="ListingDiv">
								<ul id="productListing">

								</ul>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="products_size">Size:</label>
						<div class="col-sm-10">
							<div id="products_size"></div>
						</div>
					</div>



				</div>
				<div class="modal-footer">
					<button type="button" id="btnProductOK" class="btn btn-success">OK</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				</div>
			</form>
		</div>

	</div>
</div>




<script type="text/javascript" src="sale_invoice.js?v=1.2.2"></script>

<script>

$(function(){

	$(document).on('click', '.description', function(){
		$("#products_name").val('');
		$("#products_size").html('');
		i_ar = $(this).attr('id');
		i = i_ar.split("_");
		$("#proIndex").val(i[1]);
		//$("#products_name").attr('data-ind',i[1]);
	});
	
	$("#products_name").on("keypress keyup keydown", function(){
		if($(this).val() !=''){
		$.ajax({
			method: 'GET',
			url:  'ajax_products_listing.php',
			data: 'p_name='+$(this).val(),
			success: function(e){
					$("#productListing").html(e);
				}
		});	
		} else {
			$("#productListing").html('');
		}
	});

	$(document).on('click','.pListing',function(e){
		$("#products_name").val($(this).data('pdescr'));
		/*$("#products_name").attr('data-pid',$(this).data('pid'));*/
		$("#proID").val($(this).data('pid'));
		$("#productListing").html('');
		$.ajax({
			method: 'GET',
			url:  'ajax_product_sizes.php',
			data: 'p_id='+$(this).data('pid'),
			success: function(f){
					$("#products_size").html(f);
				}
		});	
	});
});
</script>
<script>
$(function(){

	$("#btnProductOK").click(function(){
		i = $("#proIndex").val();
		
		$("#item_"+i).val($("#proID").val());
		
		$("#description_"+i).val($("#products_name").val());
		$("#size_"+i).val($('input[name="products_size"]:checked').val());
		$("#qty_"+i).val('1');
		$("#rate_"+i).val($('input[name="products_size"]:checked').data('sprice'));
		$("#cost_"+i).val($('input[name="products_size"]:checked').data('cost'));
		$('#productModal').modal('hide');
		j=parseInt(i)+parseInt(1);
		$("#qty_"+i).trigger( "change" );
		$("#item_"+j).focus();
		
		calculateSubTotal();
		count_qty();
        calculateTax();
        calculateDisc();
		count_items();
		count_qty();
		calculateTotal();
		addInvoiceRow();
		
		

	});
	
});

</script>






