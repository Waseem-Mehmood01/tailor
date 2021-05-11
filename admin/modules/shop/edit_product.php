<?php
$products_id = isset($_GET['products_id']) ? (int) $_GET['products_id'] : '';

if ($products_id == '') {
    die('Whoops..! Something went wrong.');
}

if (isset($_POST['btnSavePro'])) {

    /*
     * //print_r($_POST);
     * //print_r($_FILES);
     */
    $erros = '';
    @extract($_POST);

    $insert = DB::update('products', array(
        'categories_id' => $categories_id,
        'manufacturers_id' => $manufacturers_id,
        'sku' => $sku,
        'name' => $product_name,
        'description1' => htmlspecialchars($description1),
        'description2' => htmlspecialchars($description2),
        'highlights' => htmlspecialchars($Highlights),
        'height' => addslashes($height),
        'joint' => $joint,
        'active' => $active
    ), 'products_id=%s', $products_id);

    /*
     * DELETE PREVIOUS
     * DB::query("DELETE FROM products_price WHERE products_id = '".$products_id."'");
     */

    for ($i = 0, $iMaxSize = count($_POST['rows']); $i < $iMaxSize; $i ++) {
      
        $price_id = isset($_POST['products_price_id'][$i])?(int)$_POST['products_price_id'][$i]:'';
        
       if($price_id == ''){
           DB::insert('products_price', array(
               'products_id' => $products_id,
               'size' => addslashes($_POST['size'][$i]),
               'cost_price' => $_POST['cost_price'][$i],
               'sale_price' => $_POST['sale_price'][$i],
               'min_bid' => $_POST['min_bid'][$i],
               'stock' => $_POST['stock'][$i],
               'stock_check' => $_POST['stock_check'][$i],
               'active' => $_POST['activePrice'][$i]
           ));
       } else {
           $is_exist = DB::queryFirstField("SELECT COUNT(*) AS total FROM products_price WHERE products_price_id = '".$_POST['products_price_id'][$i]."'");
           DB::update('products_price', array(
               'products_id' => $products_id,
               'size' => addslashes($_POST['size'][$i]),
               'cost_price' => $_POST['cost_price'][$i],
               'sale_price' => $_POST['sale_price'][$i],
               'min_bid' => $_POST['min_bid'][$i],
               'stock' => $_POST['stock'][$i],
               'stock_check' => $_POST['stock_check'][$i],
               'active' => $_POST['activePrice'][$i]
           ),'products_price_id =%s', $price_id);
       }
        
    }

    /*
     * DELETE PREVIOUS
     */

    DB::query("DELETE FROM products_img WHERE products_id = '" . $products_id . "'");

    for ($i = 0, $iMaxSize = count($_POST['rowsImg']); $i < $iMaxSize; $i ++) {
        $img = '';
        if (empty($_FILES['img']['name'][$i]) or $_FILES['img']['name'][$i] == '') {
            $img = $_POST['old_img'][$i];
        } else {
            $img = $_FILES['img']['name'][$i];
        }
        $file_size = $_FILES['img']['size'][$i];
        $file_tmp = $_FILES['img']['tmp_name'][$i];
        /* $imageFileType = pathinfo($img, PATHINFO_EXTENSION); */
        $insert3 = DB::Insert('products_img', array(
            'products_id' => $products_id,
            'color' => $_POST['color'][$i],
            'is_360' => $_POST['is360'][$i],
            'img_path' => $img,
            'active' => $_POST['activeImg'][$i],
            'order_img' => $_POST['order_image'][$i]
        ));
        if ($i == 0) {
            $file_name = DB::insertId();
        }
    }
    $files = array();
    foreach ($_FILES['img'] as $k => $l) {
        foreach ($l as $i => $v) {
            if (! array_key_exists($i, $files))
                $files[$i] = array();
            $files[$i][$k] = $v;
        }
    }
    $file = '';
    foreach ($files as $file) {
        $handle = new Upload($file);
        if ($handle->uploaded) {

            $img_name = substr(get_category_name($categories_id), 0, 4) . $file_name;
            $img_name = str_replace(' ', '-', $img_name);
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $img_name . '.' . $ext;
            // die(1);
            DB::update('products_img', array(
                'img_path' => $img_name . '.' . $ext
            ), 'products_img_id=%s', $file_name);
            $handle->file_new_name_body = $img_name;
            $handle->process('../images/products/');

            if ($handle->processed) {
                $handle->clean();
            } else {
                $erros = 'error : ' . $handle->error;
            }
        }

        $file_name ++;
    }

    /*
     * if(isset($_FILES['img'])){
     * if($imageFileType == "png" OR $imageFileType == "jpg" OR $imageFileType == "jpeg" ) {
     *
     * if(!move_uploaded_file(imagepng($file_tmp),"../images/products/". substr(get_category_name($categories_id),0,4).$file_name.'.png')){
     * $erros = 'Image Upload Fail';
     * }
     * } else {
     * $erros = 'Invalid Image Type';
     * }
     * }
     */

    echo '<script>alert("Product Updated Successfully ' . $erros . '");
window.location.replace("' . SITE_ROOT . '?route=modules/shop/manage_products");
</script>';
}
?>
<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		Products <small>Edit Product </small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="<?php echo SITE_ROOT; ?>"><i class="fa fa-dashboard"></i>
				Home</a></li>
		<li><a href="#">Products</a></li>
		<li class="active">Edit Product</li>
	</ol>
</section>
<!-- Main content -->
<div class="col-md-12">
	<section>
		<!-- title row -->
		<div class="box">
			<div class="box-header with-border">
				<h3 class="box-title">
					Edit Product <a
						href="?route=modules/shop/view_product&products_id=<?php echo $products_id; ?>">View
						This Product</a>
				</h3>
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
			<div class="box-body">

				<form method="POST" action="" enctype="multipart/form-data">

					<ul class="nav nav-tabs">
						<li class="active"><a data-toggle="tab" href="#detail">Product
								Detail</a></li>
						<li><a data-toggle="tab" href="#price">Price</a></li>
						<li><a data-toggle="tab" href="#img">Product Images</a></li>
						<li><a data-toggle="tab" href="#attr">Attributes & Save</a></li>
					</ul>

<?php

$product = DB::queryFirstRow("select * from products where products_id='" . $products_id . "'");

?>

<div class="tab-content">
						<div id="detail" class="tab-pane fade in active">
							<h3>Product Detail</h3>

							<div class="row col-md-offset-2">
								<div class="form-group col-md-8">
									<div class="col-md-3">
										<label>Category <span class="text-danger">*</span></label>
									</div>
									<div class="col-md-5">
										<select name="categories_id" class="form-control"
											required="required">
					<?php
    $cate = DB::query("SELECT c.`categories_id`,c.`parent_id`,c.`name` FROM categories c");
    foreach ($cate as $cat) {
        echo '<option value="' . $cat['categories_id'] . '"';
        if ($product['categories_id'] == $cat['categories_id']) {
            echo 'SELECTED';
        }
        echo '>';
        if ($cat['parent_id'] != 0)
            echo '-';
        echo $cat['name'] . '</option>';
    }
    ?>
				</select>
									</div>
								</div>
								<div class="form-group col-md-8">
									<div class="col-md-3">
										<label>Supplier</label>
									</div>
									<div class="col-md-5">
										<select name="manufacturers_id" class="form-control">
											<option value="">-NONE-</option>
					<?php
    $cate = DB::query("SELECT m.`manufacturers_id`, m.`name` FROM manufacturers m");
    foreach ($cate as $cat) {
        echo '<option value="' . $cat['manufacturers_id'] . '"';
        if ($product['manufacturers_id'] == $cat['manufacturers_id']) {
            echo 'SELECTED';
        }
        echo '>';

        echo $cat['name'] . '</option>';
    }
    ?>
				</select>
									</div>
								</div>


								<div class="form-group col-md-8">
									<div class="col-md-3">
										<label>Product Title</label><span class="text-danger">*</span>
									</div>
									<div class="col-md-5">
										<input type="text" value="<?php echo $product['name']; ?>"
											name="product_name" placeholder="Name" class="form-control"
											required="required" />
									</div>
								</div>
								<div class="form-group col-md-8">
									<div class="col-md-3">
										<label>Short Description</label>
									</div>
									<div class="col-md-5">
										<textarea name="description1" class="form-control summernote"><?php echo $product['description1']; ?></textarea>
									</div>
								</div>
								<div class="form-group col-md-8">
									<div class="col-md-3">
										<label>Long Description</label>
									</div>
									<div class="col-md-5">
										<textarea name="description2" class="summernote form-control"
											rows="6"><?php echo $product['description2']; ?></textarea>
									</div>
								</div>
							</div>

						</div>
						<div id="price" class="tab-pane fade">
							<h3>Price</h3>
							<div class='row'>
								<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>
									<table id="priceTable" class="table table-bordered table-hover">
										<thead>
											<tr>
												<th width="2%"><input id="check_all_price"
													class="formcontrol" type="checkbox" /></th>
												<th><label>Size</label></th>
												<!-- <th><label>Barcode</label></th> -->
												<th><label>Cost Price</label></th>
												<th><label>Sale Price<span class="text-danger">*</span></label></th>
												<th><label>Minimum Bid</label></th>
												<th><label>Stock in-hand</label></th>
												<th><label>Stock Check</label></th>
												<th><label>Active</label></th>
											</tr>
										</thead>
										<tbody>
						<?php
    $pro_pric = DB::query("select * from products_price where products_id = '" . $products_id . "'");
    $i = 1;
    foreach ($pro_pric as $price) {
        ?>
						<tr>
												<td><input class="case" type="checkbox" /></td>
												<td><input class="form-control size" name="size[]"
													id="size_<?php echo $i; ?>"
													value="<?php echo $price['size']; ?>" type="text"
													placeholder="Size" />
													<p class="help-text">*Leave blank for no size options</p></td>
												<!-- <td><input class="form-control barcode" onkeypress="return IsNumeric(event);" name="barcode[]" id="barcode_<?php //echo $i; ?>" value="<?php echo $price['barcode']; ?>" type="text" placeholder="Barcode"/></td>
							 -->
												<input type="hidden" name="products_price_id[]"
													id="priceid_<?php echo $i; ?>"
													value="<?php echo $price['products_price_id']; ?>">
												<input type="hidden" name="rows[]">
												<td><input class="form-control costPrice"
													onkeypress="return IsNumeric(event);" name="cost_price[]"
													id="costPrice_<?php echo $i; ?>"
													value="<?php echo $price['cost_price']; ?>" type="text"
													placeholder="Cost" /></td>
												<td><input class="form-control salePrice"
													onkeypress="return IsNumeric(event);" name="sale_price[]"
													id="salePrice_<?php echo $i; ?>"
													value="<?php echo $price['sale_price']; ?>" type="text"
													placeholder="Sale/Web Price" required="required" /></td>
												<td><input class="form-control minBid"
													onkeypress="return IsNumeric(event);" name="min_bid[]"
													id="minBid_<?php echo $i; ?>" type="text"
													value="<?php echo $price['min_bid']; ?>"
													placeholder="Bid Price" /></td>
												<td><input class="form-control stock"
													onkeypress="return IsNumeric(event);" name="stock[]"
													id="stock_<?php echo $i; ?>"
													value="<?php echo $price['stock']; ?>"
													placeholder="In Hand Stock" style="width: 100px;" /></td>
												<td><select class="form-control stock_check"
													name="stock_check[]" id="stockCheck_<?php echo $i; ?>"><option
															value="1"
															<?php if($price['stock_check']=='1'){ echo 'SELECTED'; } ?>>Yes</option>
														<option value="0"
															<?php if($price['stock_check']=='0'){ echo 'SELECTED'; } ?>>No</option></select></td>
												<td><select class="form-control activePrice"
													name="activePrice[]" id="activePrice_<?php echo $i; ?>"><option
															value="1"
															<?php if($price['active']=='1'){ echo 'SELECTED'; } ?>>Yes</option>
														<option value="0"
															<?php if($price['active']=='0'){ echo 'SELECTED'; } ?>>No</option></select></td>

											</tr>
						<?php $i++; } ?>
					</tbody>
									</table>
								</div>
							</div>

							<div class='row'>
								<div class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>
									<button class="btn btn-danger deletePrice" type="button">-
										Delete</button>
									<button class="btn btn-success addmorePrice" type="button">+
										Add More</button>
								</div>
							</div>
						</div>
						<div id="img" class="tab-pane fade">
							<h3>Images</h3>
							<div class='row'>
								<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>
									<table id="imgTable" class="table table-bordered table-hover">
										<thead>
											<tr>
												<th width="2%"><input id="check_all_img" class="formcontrol"
													type="checkbox" /></th>
												<th><label>Color</label></th>
												<th><label>Is 360</label></th>
												<th><label>Image</label></th>
												<th><label>Order Image</label></th>
												<th><label>Active</label></th>
											</tr>
										</thead>
										<tbody>
					<?php
    $pro_img = DB::query("select * from products_img where products_id = '" . $products_id . "'");
    $j = 1;
    foreach ($pro_img as $img) {
        ?>
						<tr>
												<td><input class="caseImg" type="checkbox" /></td>
												<td><input class="form-control color" name="color[]"
													id="color_<?php echo $j; ?>"
													value="<?php echo $img['color']; ?>" type="text"
													placeholder="Color" />
													<p class="help-text">*Leave blank for no color options</p>
												</td>
												<input type="hidden" name="rowsImg[]" />
												<td><select class="form-control is360" name="is360[]"
													id="is360_<?php echo $j; ?>"><option value="0"
															<?php if($img['is_360']=='0'){ echo 'SELECTED'; } ?>>No</option>
														<option value="1"
															<?php if($img['is_360']=='1'){ echo 'SELECTED'; } ?>>Yes</option></select></td>
												<td><input type="hidden" name="old_img[]"
													value="<?php echo $img['img_path']; ?>"><input class="img"
													name="img[]" id="img_<?php echo $j; ?>" type="file"
													value="<?php echo $img['img_path']; ?>" accept="image/*" /><img
													src="../images/products/<?php echo $img['img_path']; ?>"
													width="100px"></td>
												<td><select class="form-control orderImage" id="order_image"
													name="order_image[]">
														<option value="1"
															<?php if($img['order_img']=='1'){ echo 'SELECTED'; } ?>>1</option>
														<option value="2"
															<?php if($img['order_img']=='2'){ echo 'SELECTED'; } ?>>2</option>
														<option value="3"
															<?php if($img['order_img']=='3'){ echo 'SELECTED'; } ?>>3</option>
														<option value="4"
															<?php if($img['order_img']=='4'){ echo 'SELECTED'; } ?>>4</option>
														<option value="5"
															<?php if($img['order_img']=='5'){ echo 'SELECTED'; } ?>>5</option>
														<option value="6"
															<?php if($img['order_img']=='6'){ echo 'SELECTED'; } ?>>6</option>
														<option value="7"
															<?php if($img['order_img']=='7'){ echo 'SELECTED'; } ?>>7</option>
														<option value="8"
															<?php if($img['order_img']=='8'){ echo 'SELECTED'; } ?>>8</option>
														<option value="9"
															<?php if($img['order_img']=='9'){ echo 'SELECTED'; } ?>>9</option>
														<option value="10"
															<?php if($img['order_img']=='10'){ echo 'SELECTED'; } ?>>10</option>
														<option value="11"
															<?php if($img['order_img']=='11'){ echo 'SELECTED'; } ?>>11</option>
														<option value="12"
															<?php if($img['order_img']=='12'){ echo 'SELECTED'; } ?>>12</option>
														<option value="13"
															<?php if($img['order_img']=='13'){ echo 'SELECTED'; } ?>>13</option>
														<option value="14"
															<?php if($img['order_img']=='14'){ echo 'SELECTED'; } ?>>14</option>
														<option value="15"
															<?php if($img['order_img']=='15'){ echo 'SELECTED'; } ?>>15</option>
												</select></td>
												<td><select class="form-control activeImg"
													name="activeImg[]" id="activeImg_<?php echo $j; ?>"><option
															value="1"
															<?php if($img['active']=='1'){ echo 'SELECTED'; } ?>>Yes</option>
														<option value="0"
															<?php if($img['active']=='0'){ echo 'SELECTED'; } ?>>No</option></select></td>

											</tr>
						<?php $j++; } ?>
					</tbody>
									</table>
								</div>
							</div>

							<div class='row'>
								<div class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>
									<button class="btn btn-danger deleteImg" type="button">- Delete</button>
									<button class="btn btn-success addmoreImg" type="button">+ Add
										More</button>
								</div>
							</div>
						</div>
						<div id="attr" class="tab-pane fade">

							<h3>Attributes</h3>
							<div class="row col-md-offset-2">
								<div class="form-group col-md-8">
									<div class="col-md-3">
										<label>SKU</label>
									</div>
									<div class="col-md-5">
										<input value="<?php echo $product['sku']; ?>" type="text"
											name="sku" placeholder="SKU" class="form-control">
									</div>
								</div>
							</div>
							<div class="row col-md-offset-2">
								<div class="form-group col-md-8">
									<div class="col-md-3">
										<label>Joint</label>
									</div>
									<div class="col-md-5">
										<input value="<?php echo $product['joint']; ?>" type="text"
											name="joint" placeholder="Joint" class="form-control">
									</div>
								</div>
							</div>
							<div class="row col-md-offset-2">
								<div class="form-group col-md-8">
									<div class="col-md-3">
										<label>Height</label>
									</div>
									<div class="col-md-5">
										<input value="<?php echo stripslashes($product['height']); ?>"
											type="text" name="height" placeholder="Height"
											class="form-control">
									</div>
								</div>
							</div>
							<div class="row col-md-offset-2">
								<div class="form-group col-md-8">
									<div class="col-md-3">
										<label>Highlights</label>
									</div>
									<div class="col-md-5">
										<textarea name="Highlights" class="summernote form-control"
											rows="6"><?php echo $product['highlights']; ?></textarea>
									</div>
								</div>
							</div>
							<div class="row col-md-offset-2">
								<div class="form-group col-md-8">
									<div class="col-md-3">
										<label>Active</label>
									</div>
									<div class="col-md-5">
										<select class="form-control" name="active" id="active"><option
												value="1"
												<?php if($product['active']=='1'){ echo 'SELECTED'; } ?>>Yes</option>
											<option value="0"
												<?php if($product['active']=='0'){ echo 'SELECTED'; } ?>>No</option></select>
									</div>
								</div>
							</div>
							<div class="row col-md-offset-2">
								<div class="form-group col-md-8">
									<div class="col-md-3">
										<label>&nbsp;</label>
									</div>
									<div class="col-md-5">
										<input type="submit" class="btn btn-success btn-lg"
											name="btnSavePro" id="btnSavePro" value="Update Product">
									</div>
								</div>
							</div>

						</div>

					</div>
				</form>
			</div>
			<!-- /.box-body -->
			<div class="box-footer">
				<small></small>
			</div>
			<!-- /.box-footer-->
		</div>
		<!-- /.box -->

	</section>
	<!-- /.content -->

</div>



<script>
$(document).ready(function() {
var i=$('#priceTable tr').length;
$(".addmorePrice").on('click',function(){
	html = '<tr>';
	html += '<td><input class="case" type="checkbox"/></td>';
	html += '<td><input class="form-control size" name="size[]" id="color_'+i+'" type="text" placeholder="Size"/></td>';
	/*html += '<td><input class="form-control barcode" name="barcode[]" id="barcode_'+i+'" type="text" placeholder="Barcode"/></td>';
	*/
	html += '<input type="hidden" name="products_price_id[]" id="priceid_'+i+'" value="">';
	html += '<input type="hidden" name="rows[]"/>';
	html += '<td><input class="form-control costPrice" onkeypress="return IsNumeric(event);" name="cost_price[]" id="costPrice_'+i+'" type="text" placeholder="Cost"/></td>';
	html += '<td><input class="form-control salePrice" onkeypress="return IsNumeric(event);" name="sale_price[]" id="salePrice_'+i+'" type="text" placeholder="Sale/Web Price"/></td>';
	html += '<td><input class="form-control minBid" onkeypress="return IsNumeric(event);" name="min_bid[]" id="minBid_'+i+'" type="text" placeholder="Bid Price"/></td>';
	html += '<td><input class="form-control stock" onkeypress="return IsNumeric(event);" name="stock[]" id="stock_'+i+'" placeholder="In Hand Stock"  style="width: 100px;"/></td>';
	html += '<td><select class="form-control stock_check" name="stock_check[]" id="stockCheck_'+i+'"><option value="1">Yes</option><option value="0">No</option></select></td>';
	html += '<td><select class="form-control activePrice" name="activePrice[]" id="activePrice_'+i+'"><option value="1">Yes</option><option value="0">No</option></select></td>';
	html += '</tr>';
	$('#priceTable').append(html);
	i++;
});


//to check all checkboxes
$(document).on('change','#check_all_price',function(){
	$('input[class=case]:checkbox').prop("checked", $(this).is(':checked'));
});
//deletes the selected table rows
$(".deletePrice").on('click', function() {
	$('.case:checkbox:checked').parents("tr").remove();
	$('#check_all').prop("checked", false); 

});





var j=$('#imgTable tr').length;
$(".addmoreImg").on('click',function(){
	html = '<tr>';
	html += '<td><input class="caseImg" type="checkbox"/></td>';
	html += '<td><input class="form-control color" name="color[]" id="color_'+j+'" type="text" placeholder="Color"/></td>';
	html += '<input type="hidden" name="rowsImg[]"/>';
	html += '<td><select class="form-control is360" name="is360[]" id="is360_'+j+'"><option value="0">No</option><option value="1">Yes</option></select></td>';
	html += '<td><input class="img" name="img[]" id="img_'+j+'" type="file"/></td>';
	 html += '<td><select class="form-control orderImage" id="order_image" name="order_image[]"><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option></select></td>';
	html += '<td><select class="form-control activeImg" name="activeImg[]" id="activeImg_'+j+'"><option value="1">Yes</option><option value="0">No</option></select></td>';
							
	html += '</tr>';
	$('#imgTable').append(html);
	j++;
});


//to check all checkboxes
$(document).on('change','#check_all_img',function(){
	$('input[class=caseImg]:checkbox').prop("checked", $(this).is(':checked'));
});
//deletes the selected table rows
$(".deleteImg").on('click', function() {
	$('.caseImg:checkbox:checked').parents("tr").remove();
	$('#check_all_img').prop("checked", false); 

});


});	


//It restrict the non-numbers
var specialKeys = new Array();
specialKeys.push(8,46); //Backspace
function IsNumeric(e) {
    var keyCode = e.which ? e.which : e.keyCode;
    console.log( keyCode );
    var ret = ((keyCode >= 48 && keyCode <= 57) || specialKeys.indexOf(keyCode) != -1);
    return ret;
}
$(document).ready(function() {
  $('.summernote').summernote();
});
</script>