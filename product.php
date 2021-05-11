<?php
include_once 'functions.php';

include_once 'layout/header.php';

$date = date('Y-m-d');

$products_id = isset($_REQUEST['product_id']) ? (int) $_REQUEST['product_id'] : '';

$sql1 = "SELECT p.`name`,p.`categories_id`,p.`description1`,p.`meta_title`,p.`meta_description`,p.`meta_keywords`
FROM products p WHERE p.`products_id` = '" . $products_id . "' AND p.`active` = '1'";
$products = DB::queryFirstRow($sql1);

if (DB::count() > 0) {
    $prodTitle = $products['name'];
} else {
    header('location:/home');
}

$qu = "SELECT p.`products_id`, p.`name`,p.`description1`, p.`description2` FROM products p
WHERE p.`active` = '1'
AND p.`products_id` = '" . $products_id . "'";

$product = DB::queryFirstRow($qu);

$products_price_id = isset($_POST['products_price_id']) ? (int) cleanGet($_POST['products_price_id']) : '';

$attr = DB::query("SELECT pp.`products_price_id`, pp.`size`, pp.`sale_price`,pp.stock FROM products_price pp WHERE pp.`products_id` = '" . $products_id . "' ORDER BY pp.`products_price_id`");

$default_size = DB::queryFirstField("SELECT  pp.`size` FROM products_price pp WHERE pp.`products_id` = '" . $products_id . "' ORDER BY pp.`products_price_id`");

$default_color = DB::queryFirstField("SELECT color FROM products_img WHERE products_id = '" . $products_id . "' ORDER BY products_img_id DESC");

$default_price = DB::queryFirstField("SELECT  pp.`sale_price` FROM products_price pp WHERE pp.`products_id` = '" . $products_id . "' ORDER BY pp.`products_price_id`");

$default_price_id = DB::queryFirstField("SELECT  pp.`products_price_id` FROM products_price pp WHERE pp.`products_id` = '" . $products_id . "' ORDER BY pp.`products_price_id`");

?>


<main>
	<div class="container">
		<section class="contact-section">
			<form method="POST" action="" id="attrFrm">
				<div class="row">

					<div class="container">

						<div class="row">
							<div class="">
								<div class="container-fliud">
									<div class="wrapper row">
										<div class="preview col-md-6">
											<div class="preview-pic tab-content">
				<?php
    $i = 0;
    $images = DB::query("SELECT * FROM products_img WHERE products_id = '" . $products_id . "'");
    foreach ($images as $img) {
        echo '<div class="tab-pane ';
        if ($i == 0)
            echo ' active ';

        echo '" id="pic-' . $img['products_img_id'] . '">

			
				<img title="' . $prodTitle . '" alt="' . $prodTitle . '" src="' . SITE_URL . '/getimage/550x550/products/' . $img['img_path'] . '" /> 
	    
	    
					</div>';
        $i ++;
    }
    ?>
					
					
				</div>

										</div>
										<div class="details col-md-6">
											<h1 class="product-title"><?php echo $prodTitle; ?></h1>
											<h1><?php echo DEFAULT_PRICE.floatval($default_price); ?></h1>
											<br>

											<div class="col-xs-12">
												<span>Size: </span>
												<div class="input-group-icon mt-10">
													<div class="icon">
														<i class="fa fa-child" aria-hidden="true"></i>
													</div>
													<div class="form-select" id="sizes">
														<select name="size" class="nice-select" id="size">
																<?php
                foreach ($attr as $sizes) {
                    echo '<option value="' . $sizes['size'] . '">' . $sizes['size'] . '</option>';
                }
                ?>
																</select>
													</div>
												</div>

											</div>

											<br>

											<div class="col-xs-12">
												<span>Color: </span><small id="setcolor"><?php echo $default_color; ?></small>
												<ul class="preview-thumbnail nav nav-tabs">
				<?php
    $i = 0;
    $images = DB::query("SELECT * FROM products_img WHERE products_id = '" . $products_id . "'");
    foreach ($images as $img) {
        echo '<li style="width: 55px; margin: 0px;" class="';
        if ($i == 0)
            echo ' active ';
        echo '"><a class="color-picker" data-color="' . $img['color'] . '" title="' . $img['color'] . '" data-target="#pic-' . $img['products_img_id'] . '" data-toggle="tab"><img
							src="' . SITE_URL . 'getimage/50x50/products/' . $img['img_path'] . '" alt="' . $prodTitle . '-thumb' . '" /></a></li>';
        $i ++;
    }
    ?>
					
				</ul>
											</div>
											<br>
											<div class="col-xs-12">



												<input type="hidden" name="name"
													value="<?php echo str_replace(' ', '-', $product['name']); ?>">
												<input type="hidden" name="product"
													value="<?php echo $products_id; ?>">
												<div class="col-xs-6">
													<span>Qty: </span>
													<div class="input-group" style="max-width: 200px;">
														<span class="input-group-btn">
															<button type="button"
																class="quantity-left-minus genric-btn primary-border small btn-number"
																data-type="minus" data-field="">
																<i class="fa fa-minus" aria-hidden="true"></i>
															</button>
														</span> <input type="text" name="quantity" id="qty"
															style="font-size: 20px;"
															class="single-input form-control input-number" value="1"
															min="1" max="100"> <span class="input-group-btn">
															<button type="button"
																class="quantity-right-plus genric-btn primary-border small btn-number"
																data-type="plus" data-field="">
																<i class="fa fa-plus" aria-hidden="true"></i>
															</button>
														</span>

													</div>
												</div>

												<div class="col-xs-6">&nbsp;</div>

											</div>

											<div class="clear-fix"></div>
											<div class="col-xs-12">
												<p>Measurement</p>
												<div class="form-inline">
													<span><small>Height:</small> <input type="text" value=""
														name="height" style="max-width: 80px;" placeholder="inch"></span>&nbsp;&nbsp;
													<span><small>Width:</small> <input type="text" value=""
														name="width" style="max-width: 80px;" placeholder="inch"></span>
												</div>
											</div>
											<br>
											<div class="clear-fix"></div>
											<div class="col-xs-12">
												<p>Special Note:</p>
												<div class="form-inline">
													<span><textarea class="single-textarea" name="prod_note"
															placeholder="___"></textarea></span>
												</div>
											</div>
											<br>
											<div class="clear-fix"></div>
											<div class="col-xs-12">
												<input type="hidden" name="color"
													value='<?php echo $default_color ?>' id="color" /> <input
													type="hidden" name="token" id="token"
													value="b61c3340d363bdcb3ec0b49462299d7c0f1cb01"> <input
													type="hidden" name="action" id="action" value="addCart"> <input
													type="hidden" name="products_price_id"
													id="products_price_id"
													value="<?php echo $default_price_id; ?>"> <input
													type="hidden" name="product_id" id="product_id"
													value="<?php echo $products_id; ?>" />
												<!-- <input type="hidden" name="quantity" id="quantity" value="1" /> -->


												<button id="addBag" onclick="event.preventDefault()"
													data-role="proceed-to-checkout"
													class="genric-btn primary circle checkout">
													Add to cart <i class="fa fa-arrow-right"></i>
												</button>

											</div>


										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

				</div>
				<p class="product-description"><?php echo html_entity_decode(strip_tags($products['description1'])); ?></p>
			</form>
		</section>
	</div>
</main>



<div id="addBagModal" class="modal" style="z-index: 9999;">
	<!-- Modal content -->
	<div class="modal-content">
		<!-- <div class="modal-header">
              <span class="close">&times;</span>
              <h2></h2>
            </div> -->
		<div class="modal-body">
			<div class="pull-right">

				<div class="funnel-model-content">
					<div class="fmc-title">
						You added <span><?php echo $product['name']; ?></span> to your
						cart.
					</div>
					<!-- <div class="fmc-shipping">You are only <span>$8.00</span> away from Free Shipping</div> -->
					<div class="button-group-area">


						<a href="<?php echo SITE_URL; ?>checkout"
							class="genric-btn success">Checkout</a> <a
							class="genric-btn primary" href="<?php echo SITE_URL; ?>cart">View
							Your Cart </a> <a href="<?php echo SITE_URL; ?>index"
							class="genric-btn info">Continue Shopping </a>


					</div>
				</div>
			</div>
		</div>
		<!-- <div class="modal-footer">
              <h3>Modal Footer</h3>
            </div> -->
	</div>
</div>

<script>


$(document).ready(function(){

var quantitiy=0;
   $('.quantity-right-plus').click(function(e){
        
        // Stop acting like a button
        e.preventDefault();
        // Get the field name
        var quantity = parseInt($('#qty').val());
        
        // If is not undefined
            
            $('#qty').val(quantity + 1);

          
            // Increment
        
    });

     $('.quantity-left-minus').click(function(e){
        // Stop acting like a button
        e.preventDefault();
        // Get the field name
        var quantity = parseInt($('#qty').val());
        
        // If is not undefined
      
            // Increment
            if(quantity>0){
            $('#qty').val(quantity - 1);
            }
    });
    
});


$(function(){
	

var modalAdd = document.getElementById('addBagModal');

// Get the button that opens the modal
var btnAdd = document.getElementById("addBag");

var spanClose = document.getElementsByClassName("close")[1];

btnAdd.onclick = function() {
	

		   var formdata = $("#attrFrm").serialize();
		   $.ajax({

				method: 'POST',
				url: '<?php echo SITE_URL; ?>cart_api',
				data:  formdata,
				beforeSend: function() {
				    $("#addBag").prop("disabled", true);
				    $("#addBag").html("Adding...");
				  },
				success: function(e){
							console.log(e);
							modalAdd.style.display = "block";
							 $("#addBag").html("ADDED TO BAG");
					}


			   });
	
}

$(".color-picker").on("click change", function(){

	var Color = $(this).data("color");
	console.log(Color);
	$("#color").val(Color);
	$("#setcolor").html(Color);

});

/*
spanClose.onclick = function() {
	modalAdd.style.display = "none";
}

window.onclick = function(event) {
  if (event.target == modalAdd) {
    modal.style.display = "none";
  }
}*/

	});

</script>

<?php
include_once 'layout/footer.php';
?>