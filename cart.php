<?php
include_once 'functions.php';

include_once 'layout/header.php';

?>


<main>
	<div class="slider-area position-relative">
		<div class="slider-active">
			<div class="container">
				<h1 class="text-center">Your Shopping Cart</h1>
			</div>
		</div>
	</div>
	<!--?  Contact Area start  -->
	<section class="contact-section">
		<div class="container">
			<div class="row">

				<div class="container">
 
    <?php
    if (! empty($_SESSION['cart_item'])) {

        /* print_r($_SESSION); */

        ?>
					<div class="row">

						<div class="col-md-8">
							<div class="cart-container ">
								<div class="columns">
									<form action="checkout.php" method="POST" id="cartForm"
										class="form form-cart" novalidate="novalidate">
										<div class="cart table-wrapper">
											<table id="shopping-cart-table" class="cart items data table">
												<caption role="heading" aria-level="2" class="table-caption">Shopping
													Cart Items</caption>
												<thead>
													<tr>
														<th class="col item" scope="col"><span>ITEM</span></th>
														<th class="col price" scope="col"><span>PRICE</span></th>
														<th class="col qty" scope="col"><span>QTY</span></th>
														<th class="col subtotal" scope="col"><span>SUBTOTAL</span></th>
													</tr>
												</thead>
												<tbody class="cart item">
            
            <?php

        $total = 0.00;

        foreach ($_SESSION["cart_item"] as $item) {
            // $item_price = $item["quantity"]*$item["price"];
            $price = 0.00;
            $subTotal = 0.00;
            $is_bid = '';

            if ($item['products_price_id'] == '') {
                $attr = DB::queryFirstRow("SELECT pp.`products_price_id`, pp.`size`, pp.`sale_price` FROM products_price pp WHERE pp.`products_id` = '" . $item['product_id'] . "' ORDER BY pp.`products_price_id`");
            } else {
                $attr = DB::queryFirstRow("SELECT pp.`products_price_id`, pp.`size`, pp.`sale_price` FROM products_price pp WHERE pp.`products_id` = '" . $item['product_id'] . "' AND pp.`products_price_id` = '" . $item['products_price_id'] . "' ");
            }
            $price = $attr['sale_price'];

            $subTotal = round($price * (int) $item["quantity"], 2);

            $total += $subTotal;

            ?>
                <tr class="item-info">
														<td data-th="ITEM" class="col item">
															<div class="col-xs-12">
																<div class="row">
																	<div class="col-xs-6">
																		<a
																			href="<?php echo SITE_URL.'p/'.str_replace(' ', '-', get_product_name($item['product_id'])); ?>/<?php echo $item['product_id']; ?>"
																			title="<?php echo get_product_name($item['product_id']); ?>"
																			tabindex="-1" class="product-item-photo"> <img
																			class="product-image-photo"
																			src="<?php echo SITE_URL . 'getimage/100x100/products/'.get_product_img($item['product_id']); ?>"
																			alt="<?php echo get_product_name($item['product_id']); ?>">

																		</a>
																	</div>
																	<div class="col-xs-6" style="margin: 7px">
																		<div class="col-xs-12">
																			<div class="product-item-details">
																				<strong><?php echo get_product_name($item['product_id']); ?>   </strong>
																			</div>
																			<div class="col-xs-6">
																		
                                <?php if($item['size']<>''){ ?>Size:
																		
                                   <small> <?php echo $item['size']; ?></small> <?php } ?>
                                    <?php if($item['color']<>''){ ?>Color:
																		
                                   <small> <?php echo $item['color']; ?></small> <?php } ?></div>

																		</div>
																		<div class="col-xs-12">
																		<?php if($item['height']<>''){ ?>Height:
																		
                                   <small> <?php echo $item['height']; ?></small> <?php } ?>
																		
                                    <?php if($item['width']<>''){ ?>Width:
																		
                                   <small> <?php echo $item['width']; ?></small> <?php } ?></div>
                                   
                                   <div class="col-xs-12">
																		<?php if($item['prod_note']<>''){ ?>Note:
																		
                                   <small> <?php echo $item['prod_note']; ?></small> <?php } ?>
																		
                                  </div>


																	</div>
																</div>
															</div>


														</td>

														<td class="col price" data-th="PRICE"><span
															class="cart-price"> <span class="price"><?php
            echo DEFAULT_PRICE . floatval($price);
            ?></span>

														</span></td>

														<td class="col qty" data-th="QTY">
															<div class="field qty">

																<input style="width: 80px;"
																	id="cart-<?php echo $item['product_id']; ?>-qty"
																	name="cart[<?php echo $item['product_id']; ?>][qty]"
																	data-item-id="<?php echo (int)$item['product_id']; ?>"
																	value="<?php echo $item['quantity']; ?>" type="number"
																	size="4" title="QTY" class="input-text qty"
																	data-validate="{required:true,'validate-greater-than-zero':true}"
																	data-role="cart-item-qty">

															</div>
														</td>

														<td class="col subtotal" data-th="SUBTOTAL"><span
															class="price-excluding-tax" data-label="Excl. Tax"> <span
																class="cart-price"> <span class="price">$<?php echo $subTotal; ?></span>
															</span>

														</span></td>
													</tr>
													<tr class="item-actions">
														<td colspan="100">
															<div class="actions-toolbar">
																<div id="gift-options-cart-item-2303289"
																	data-bind="scope:'giftOptionsCartItem-2303289'"
																	class="gift-options-cart-item"></div>
																<!--  <a class="action-edit" href="#" title="Edit item parameters">
                                <i class="fa fa-pencil"></i><span>
            Edit        </span>
                            </a>  
                             <a href="#" title="Remove item" data-id="<?php echo  $item['product_id']; ?>" class="action remove" style="float: right; font-size: 1.5em;padding: 8px;">
                               <i class="fa fa-trash-o" aria-hidden="true"></i> 
                            </a> 
                             <a href="#" title="Edit item" data-id="<?php echo  $item['product_id']; ?>" class="action edit" style="float: right; font-size: 1.5em;">
                               <i class="fa fa-pencil" aria-hidden="true"></i> 
                            </a>-->
																<a style="color: #BE9278;" href="#" title="Remove item"
																	data-item-id="<?php echo  $item['product_id']; ?>"
																	class="action remove"> Remove </a>&nbsp;|&nbsp;<a
																	href="#" title="Update Qty" style="color: #BE9278;">
																	Update Qty </a>
															</div>
														</td>
													</tr>
                
                <?php } ?>
            </tbody>
											</table>
										</div>
										<div class="cart main actions">
											<a class="action genric-btn link-border continue"
												href="/home" title="Keep Shopping"> <span>Keep Shopping</span>
											</a>
											<!-- <button type="submit" name="update_cart_action"
												data-cart-empty="" value="empty_cart" title="Empty Cart"
												class="action genric-btn danger-border clear" id="empty_cart_button">
												<span>Empty Cart</span>
											</button> -->
											<!-- <button type="submit" name="update_cart_action" data-cart-item-update="" value="update_qty" title="UPDATE" class="action update">
            <span>UPDATE</span>
        </button>  -->
											<input type="hidden" value=""
												id="update_cart_action_container" data-cart-item-update="">
										</div>
									</form>


								</div>

							</div>


						</div>

						<div class="col-md-4">

							<div class="cart-summary" style="top: 0px;">
								<strong class="summary title">Summary</strong>

								<div id="cart-totals" class="cart-totals"
									data-bind="scope:'block-totals'">
									<!-- ko template: getTemplate() -->
									<div class="table-wrapper" data-bind="blockLoader: isLoading">
										<table class="data table totals">
											<!-- <caption class="table-caption" data-bind="text: $t('Total')">Total</caption> -->
											<tbody>
												<tr class="totals sub">
													<th data-bind="i18n: title" class="mark" scope="row">SUBTOTAL</th>
													<td class="amount"><span class="price"
														data-bind="text: getValue(), attr: {'data-th': title}"
														data-th="SUBTOTAL"><?php echo DEFAULT_PRICE . floatval($total); ?></span></td>
												</tr>

												<tr class="totals-tax">
													<th data-bind="text: title" class="mark" colspan="1"
														scope="row">Tax</th>
													<td data-bind="attr: {'data-th': title}" class="amount"
														data-th="Tax"><span class="price"
														data-bind="text: getValue()"><?php echo DEFAULT_PRICE; ?>0.00</span></td>
												</tr>

												<tr class="grand totals">
													<th class="mark" scope="row"><strong
														data-bind="i18n: title">Order Total</strong></th>
													<td data-bind="attr: {'data-th': title}" class="amount"
														data-th="Order Total"><strong><span class="price"
															data-bind="text: getValue()"><?php echo DEFAULT_PRICE . floatval($total); ?></span></strong>
													</td>
												</tr>
												<!-- /ko -->
											</tbody>
										</table>
									</div>
									<!-- /ko -->

								</div>

								<ul class="checkout methods items checkout-methods-items">
									<li class="item">
										<button type="button" id="checkout"
											data-role="proceed-to-checkout" title="CHECKOUT"
											class="action genric-btn primary checkout">
											<span><i class="fa fa-shopping-cart"></i> CHECKOUT</span>
										</button>
									</li>
									<li class="item"></li>

								</ul>
							</div>
						</div>

					</div>
				</div>
				<?php } else { ?>
<div class="cart-empty">
					<p>You have no items in your shopping cart.</p>
					<p>
						Click <a href="/index">here</a> to continue shopping.
					</p>
				</div>
<?php } ?>
			</div>
		</div>
	</section>


</main>

<script>
$(function(){
$("#checkout").click(function(){
	//$("#cartForm").submit();
	location.href="checkout";
	
});

var ID ='';
$(".qty").on("blur", function(){
	ID = $(this).data("item-id");
	Qty = $(this).val();
	if(Qty<1){
		$(this).val('1');
	} else {
		$.ajax({

			method: 'GET',
			url: '<?php echo SITE_URL; ?>cart_api?token=b61c3340d363bdcb3ec0b49462299d7c0f1cb01&action=editQty&quantity='+Qty+'&product_id='+ID,
			success: function(e){
					window.location.reload();
				}
		});

	}
});


$(".remove").on("click", function(){
	ID = $(this).data("item-id");

	var suRe = confirm('Are you sure? want to remove this product');
	if(suRe){
		$.ajax({
			method: 'GET',
			url: '<?php echo SITE_URL; ?>cart_api?token=b61c3340d363bdcb3ec0b49462299d7c0f1cb01&action=remove&product_id='+ID,
			success: function(e){
					window.location.reload();
				}
		});

	}
});

	
});
</script>
<?php
include_once 'layout/footer.php';
?>