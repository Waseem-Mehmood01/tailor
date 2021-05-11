<?php
$products_id = isset($_GET['products_id']) ? (int) $_GET['products_id'] : '';
$products_price_id = isset($_GET['size']) ? (int) $_GET['size'] : '';

if ($products_id != '' and $products_price_id != '') {

    $products_recipe_id = DB::queryFirstField("SELECT products_recipe_id FROM products_recipe WHERE products_id = '" . (int) $products_id . "' AND products_price_id = '" . (int) $products_price_id . "'");
} else {
    $products_recipe_id = '';
}

if (isset($_POST['btnSubmit'])) {
    print_r($_POST);

    DB::insertUpdate('products_recipe', array(
        'products_recipe_id' => $products_recipe_id,
        'products_id' => $products_id,
        'size' => $_POST['size'],
        'products_price_id' => $products_price_id,
        'total_cost' => $_POST['total_cost'],
        'created_on' => $now,
        'active' => 1
    ));

    if ($products_recipe_id == '') {
        $products_recipe_id = DB::insertId();
    } else {
        DB::delete('products_recipe_options', 'products_recipe_id=%s', $products_recipe_id);
    }
    for ($i = 0; $i < count($_POST['products_options_id']); $i ++) {
        DB::insert('products_recipe_options', array(
            'products_recipe_id' => $products_recipe_id,
            'products_options_id' => $_POST['products_options_id'][$i],
            'qty' => $_POST['qty'][$i],
            'unit' => $_POST['unit'][$i],
            'cost' => @$_POST['cost'][$i]
        ));
    }

    echo '<script>alert("SAVED Successfully ");
window.location.replace("' . SITE_ROOT . '?route=modules/inventory/add_edit_menu&products_id=' . $products_id . '&size=' . $products_price_id . '");
</script>';
}

?>
<style>
hr {
	border-top: 1px solid #d3d3d3;
}
</style>
<section class="content-header">
	<h1>
		Recipe <small>Add Product Menu</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="#">Inventory</a></li>
		<li class="active">Add Recipe</li>
	</ol>
</section>
<!-- Main content -->
<div class="col-md-12">
	<section>
		<!-- title row -->
		<div class="box">
			<div class="box-header with-border">
				<h3 class="box-title">Add Recipe</h3>
			</div>
			<div class="row">
				<div class="col-md-12">
				<?php if($products_id<>''): ?>
				<?php
        $prices = DB::queryFirstRow("SELECT sale_price,size FROM products_price WHERE products_price_id = '" . $products_price_id . "'");
        $total_cost = DB::queryFirstField("SELECT total_cost FROM products_recipe WHERE products_recipe_id='" . (int) $products_recipe_id . "'");
        ?>
        <form method="POST" action="">
						<div class="col-md-4">
							<img width="200px" class="img-thumbnail"
								src="../images/products/<?php echo get_product_img($products_id); ?>">
							<h2><?php echo get_product_name($products_id); ?></h2>
							<h4>
								Size: <strong><?php echo stripslashes($prices['size']); ?></strong>
							</h4>
							<input type="hidden" name="size"
								value='<?php echo $prices['size']; ?>'>
							<h4>
								Sale Price: <strong>$<?php echo $prices['sale_price']; ?></strong>
							</h4>
							<hr>
							<table class="table">
								<tbody>
									<tr>
										<td><label>Total Cost:</label></td>
										<td><input type="text" name="total_cost" placeholder="$$"
											value="<?php echo $total_cost; ?>" class="form-control"></td>
									</tr>
								</tbody>
							</table>
							<button type="submit" class="btn btn-success pull-right"
								name="btnSubmit">
								<i class="fa fa-floppy-o" aria-hidden="true"></i>&nbsp;SAVE
							</button>
							<table class="table">
								<tbody>
									<tr>
										<td><label>&nbsp;</label></td>
										<td>&nbsp;</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="col-md-8">
							<table class="table table-striped" id="tblMenu">
								<thead>
									<tr>
										<th style="width: 50px">&nbsp;</th>
										<th>Ingredient</th>
										<th style="width: 120px">Qty</th>
										<th>Unit</th>
									</tr>
								</thead>
								<tbody>
								<?php
        $i = 1;
        $rec_op = DB::query("SELECT * FROM products_recipe_options WHERE products_recipe_id='" . $products_recipe_id . "'");
        foreach ($rec_op as $recip) {

            echo '<tr class="item_row" id="item_row' . $i . '">';
            echo '<td><a href="javascript:void(0);" onclick="remove_item(' . $i . ')" class="btn btn-xs btn-danger my-product-remove">X</a></td>';
            echo '<td><select id="products_options_id' . $i . '" name="products_options_id[]" class="form-control products_options">';
            echo '<option data-cost="" data-unit="" value="">-SELECT-</option>';

            $options = DB::query("SELECT products_options_id, name, unit, cost FROM products_options ORDER BY name");
            foreach ($options as $opt) {

                echo '<option data-cost="' . $opt['cost'] . '" data-unit="' . $opt['unit'] . '" value="' . $opt['products_options_id'] . '"';
                if ($opt['products_options_id'] == $recip['products_options_id'])
                    echo ' SELECTED';
                echo '>' . $opt['name'] . '</option>';
            }
            echo '</select></td>';
            echo '<td><input type="text" value="' . $recip['qty'] . '" name="qty[]" class="form-control"></td>';
            echo '<td><input id="unit' . $i . '" name="unit[]"  value="' . $recip['unit'] . '" type="hidden"><span id="txtUnit' . $i . '">' . $recip['unit'] . '</span></td>';
            echo '</tr>';
            $i ++;
        }
        ?>
								</tbody>
							</table>
							<button id="addRow" onclick="add_row();" class="btn btn-default">Add
								Ingredient</button>
						</div>
					</form>
				<?php else: ?>
					<div class="col-md-6 col-md-offset-3">
						<div class="panel panel-info">
							<div class="panel-heading">
								<div class="panel-title">Step 1</div>
							</div>
							<form method="GET" action="" name="frmStep1" id="frmStep1">
								<input name="route" type="hidden"
									value="modules/inventory/add_edit_menu">
								<div style="padding-top: 30px" class="panel-body">
									<div class="form-group">
										<label>Select Product:</label> <select
											class="select2 form-control" name="products_id"
											id="products_id">
											<option value="">-SELECT-</option>
							<?php
        $products = DB::query("select products_id,name from products");
        foreach ($products as $pro) {
            echo '<option value="' . $pro['products_id'] . '">' . get_product_category($pro['products_id']) . '-' . $pro['name'] . '</option>';
        }
        ?>
							</select>
									</div>
									<div class="form-group">
										<label>Select Size:</label> <select class="form-control"
											name="size" id="size">
										</select>
									</div>
									<div class="form-group">
										<button type="submit"
											class="btn btn-success btn-block btn-flat">
											NEXT&nbsp;<i class="fa fa-chevron-right" aria-hidden="true"></i>
										</button>
									</div>
								</div>
							</form>
						</div>
					</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>
</div>
<script>

function remove_item(i) {
	$("tr#item_row" + i).remove();
}

function add_row() {
	event.preventDefault();
	if ($(".item_row")[0]) {
		var i = $(".item_row").last().attr("id");
		i = i.replace(/[^\d.]/g, '');
		i = parseInt(i) + 1;
	} else {
		var i = 1;
	}
	var hTML = '';
	hTML += '<tr class="item_row" id="item_row' + i + '">';
	hTML += '<td><a href="javascript:void(0);" onclick="remove_item(' + i
	+ ')" class="btn btn-xs btn-danger my-product-remove">X</a></td>';
	hTML += '<td><select id="products_options_id'+i+'" name="products_options_id[]" class="form-control products_options select2">';
	hTML += '<option data-cost="" data-unit="" value="">-SELECT-</option>';
        <?php
        $options = DB::query("SELECT products_options_id, name, unit, cost FROM products_options ORDER BY name");
        foreach ($options as $opt) {
            ?>
	hTML += '<option data-cost="<?php echo $opt['cost']; ?>" data-unit="<?php echo $opt['unit']; ?>" value="<?php echo $opt['products_options_id']; ?>"><?php echo $opt['name']; ?></option>';
	<?php } ?>
	hTML += '</select></td>';
	hTML += '<td><input type="text" name="qty[]" class="form-control"></td>';
	hTML += '<td><input id="unit'+i+'" name="unit[]" type="hidden"><span id="txtUnit'+i+'"></span></td>';
	hTML += '</tr>';

	$('#tblMenu').append(hTML);
	//$(".select2").select2();
}
$(function(){


	$(document).on('change','.products_options',function(){
		var i = $(this).attr("id");
		i = i.replace(/[^\d.]/g, '');
		var unIt = $(this).find(':selected').data('unit');
		var coSt = $(this).find(':selected').data('cost');
		$('#txtUnit'+i).html(unIt);
		$('#unit'+i).val(unIt);
	});
	

	
	$("#products_id").on("change", function(){
		if($(this).val() !=''){
			$.ajax({
				method: 'POST',
				url: 'ajax_helpers/ajax_product_size.php',
				data: {'produts_id': $(this).val()},
				success: function(data){
					$("#size").html(data);
				}
			});
		} else {
			$("#size").html('');
		}
	});
});
</script>