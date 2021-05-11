<?php
include_once 'functions.php';

include_once 'layout/header.php';

/*
 * PLACE ORDER START HERE
 */

if (isset($_POST['form_key']) and isset($_POST['fname']) and isset($_POST['email'])) {

    $product_html = '';

    if ($_POST['form_key'] == sha1("waseem")) {
        $subsc = 0;
        if (isset($_POST['newsletter'])) {
            $subsc = 1;
        }
        $_POST = array_map('cleanVar', $_POST);
        @extract($_POST);

        // $country = DB::queryFirstField("SELECT c.`name` FROM countries c WHERE c.`id` = '" . $country . "'");

        if (isset($_SESSION['customers_id'])) {
            DB::update("customers", array(
                'fname' => $fname,
                'lname' => $lname,
                'email' => $email,
                'contact' => $contact,
                'card_no' => $card_no,
                'card_expiry' => $card_exp,
                'card_csv' => $card_csc,
                'address' => $address,
                'city' => $city,
                'state' => $state,
                'zip' => $zip,
                'country' => $country,
                'newsletter' => $subsc
            ), 'customers_id=%s', $_SESSION['customers_id']);

            $customers_id = $_SESSION['customers_id'];
        } else {

            DB::insert("customers", array(
                'fname' => $fname,
                'lname' => $lname,
                'email' => $email,
                'contact' => $contact,
                'card_no' => $card_no,
                'card_expiry' => $card_exp,
                'card_csv' => $card_csc,
                'address' => $address,
                'city' => $city,
                'state' => $state,
                'zip' => $zip,
                'country' => $country,
                'newsletter' => $subsc
            ));

            $customers_id = DB::insertId();
        }

        DB::insert("orders", array(
            'customers_id' => $customers_id,
            'orders_status_id' => '1',
            'sub_total' => $total,
            'tax_amount' => '0.00',
            'order_total' => $total,
            'phoneid'   => 'web'
        ));

        $orders_id = DB::insertId();

        foreach ($_SESSION["cart_item"] as $item) {
            $price = 0.00;
            $subTotal = 0.00;

            if ($item['products_price_id'] == '') {
                $attr = DB::queryFirstRow("SELECT pp.`products_price_id`, pp.`size`, pp.`sale_price` FROM products_price pp WHERE pp.`products_id` = '" . $item['product_id'] . "' ORDER BY pp.`products_price_id`");
            } else {
                $attr = DB::queryFirstRow("SELECT pp.`products_price_id`, pp.`size`, pp.`sale_price` FROM products_price pp WHERE pp.`products_id` = '" . $item['product_id'] . "' AND pp.`products_price_id` = '" . $item['products_price_id'] . "' ");
            }
            $price = $attr['sale_price'];

            $subTotal = round($price * (int) $item["quantity"], 2);

            DB::insert("orders_products", array(
                'orders_id' => $orders_id,
                'products_id' => $item['product_id'],
                'name' => get_product_name($item['product_id']),
                'products_price_id' => $item['products_price_id'],
                'size' => $item['size'],
                'color' => $item['color'],
                'height' => $item['height'],
                'width' => $item['width'],
                'prod_note' => $item['prod_note'],
                'price' => $price,
                'qty' => (int) $item["quantity"],
                'total' => $subTotal
            ));

            $product_html .= ' <tr>
      <td class="product">
        <div class="product-img"><img style="max-width: 150px;" src="' . SITE_URL . '/images/products/' . get_product_img($item['product_id']) . '"></div>
        <div class="product-name">' . get_product_name($item['product_id']) . '
      </td>
      <td class="price">' . $price . '</td>
      <td class="quantity">' . (int) $item["quantity"] . '</td>
      <td class="price">' . $subTotal . '</td>
    </tr>';
        }

        echo '<script>
              
            
                  window.location.href = "order_success?order_confirm=yes";
            ;</script>';
    }
}

if (isset($_SESSION['customers_id'])) {

    $customer = DB::queryFirstRow("SELECT * FROM customers c WHERE c.`customers_id` = '" . $_SESSION['customers_id'] . "'");

    @extract($customer);
}

?>


<main>
	<div class="slider-area position-relative">
		<div class="slider-active">
			<div class="container">
				<h1 class="text-center">Checkout</h1>
			</div>
		</div>
	</div>
			
			<?php

if (! empty($_SESSION['cart_item'])) {

    $total = 0.00;

    foreach ($_SESSION["cart_item"] as $item) {
        // $item_price = $item["quantity"]*$item["price"];
        $price = 0.00;
        $subTotal = 0.00;

        if ($item['products_price_id'] == '') {
            $attr = DB::queryFirstRow("SELECT pp.`products_price_id`, pp.`size`, pp.`sale_price` FROM products_price pp WHERE pp.`products_id` = '" . $item['product_id'] . "' ORDER BY pp.`products_price_id`");
        } else {
            $attr = DB::queryFirstRow("SELECT pp.`products_price_id`, pp.`size`, pp.`sale_price` FROM products_price pp WHERE pp.`products_id` = '" . $item['product_id'] . "' AND pp.`products_price_id` = '" . $item['products_price_id'] . "' ");
        }
        $price = $attr['sale_price'];

        $subTotal = round($price * (int) $item["quantity"], 2);

        $total += $subTotal;
    }

    ?>
			<!--?  Contact Area start  -->
	<section class="contact-section">
		<div class="container">
			<div class="row">

				<div class="container">
					<div class=" form-error-container alert-container"
						style="display: none;">
						<div class="error-body animated fadeIn">
							<div class="message" id="errorLog"></div>
							<i class="fa fa-exclamation-triangle"></i>
						</div>
						<ul></ul>
					</div>
					<form class="" action="" method="POST" id="frmPlaceOrder"
						name="frmPlaceOrder">

						<div class="col-sm-8" style="float: left;">
						<?php   if (!isset($_SESSION['customers_id'])) { ?>
						<small class="pull-right">Already have an account <a
								href="<?php SITE_URL; ?>login"
								class="genric-btn link-border circle">Login</a></small>
							<?php } ?>
							<input name="form_key" type="hidden"
								value="b61c3340d363bdcb3ec0b49462299d7c0f1cb01e">
							<fieldset class="fieldset create info">
								<legend class="legend"> Quick Checkout </legend>

								<br> <?php

    include 'layout/account_fields.php';

    ?>
								<!--  <div class="field required">
            <label for="card_no" class="label"><span>Credit Card No.</span></label>
            <div class="control">
                <input type="tel" name="card_no" id="" autocomplete="off" value="" title="Card Number" class="single-input ccFormatMonitor" aria-required="true" maxlength="19">
            </div>
        </div> -->
								<div class="field choice newsletter"
									style="display: inline-flex;">
									<input type="checkbox" name="newsletter"
										title="Sign Up for Newsletter" value="1" id="newsletter"
										class="checkbox" style="margin-top: 4px;"> <label
										for="newsletter" class="label"><span>&nbsp;Sign Up for
											Newsletter</span></label>
								</div>
							</fieldset>
						</div>

						<div class="col-sm-4" style="float: right;">
							<div class="cart-summary" style="top: 0px;">
								<strong class="summary title">Your Order</strong>
								<div id="cart-totals" class="cart-totals"
									data-bind="scope:'block-totals'">
									<!-- ko template: getTemplate() -->
									<div class="table-wrapper" data-bind="blockLoader: isLoading">
										<table class="data table totals">
											<tbody>
												<tr style="border-bottom: 1px solid #e5e5e5;">
													<td colspan="2" style="padding: 0px;">
														<table width="100%">
            			
            				<?php

    foreach ($_SESSION["cart_item"] as $item) {
        $price = 0.00;
        $subTotal = 0.00;

        if ($item['products_price_id'] == '') {
            $attr = DB::queryFirstRow("SELECT pp.`products_price_id`, pp.`size`, pp.`sale_price` FROM products_price pp WHERE pp.`products_id` = '" . $item['product_id'] . "' ORDER BY pp.`products_price_id`");
        } else {
            $attr = DB::queryFirstRow("SELECT pp.`products_price_id`, pp.`size`, pp.`sale_price` FROM products_price pp WHERE pp.`products_id` = '" . $item['product_id'] . "' AND pp.`products_price_id` = '" . $item['products_price_id'] . "' ");
        }
        $price = $attr['sale_price'];

        echo '<tr>';
        echo '<td style="padding-top: 5px; ">' . get_product_name($item['product_id']);
        echo '<small>';
        if ($item['size'] != '') {
            echo '<br><i>Size: </i>' . $item['size'];
        }
        if ($item['color'] != '') {
            echo '<br><i>Color: </i>' . $item['color'];
        }
        if ($item['width'] != '') {
            echo '<br><i>Width: </i>' . $item['width'];
        }
        if ($item['height'] != '') {
            echo '<br><i>Height: </i>' . $item['height'];
        }
        if ($item['prod_note'] != '') {
            echo '<br><i>Note: </i>' . $item['prod_note'];
        }
        echo '</small>';
        echo '</td>';
        echo '<td style="padding-top: 5px;">' . $item['quantity'] . '</td>';
        echo '<td style="padding-top: 5px; text-align: right; color: #48b98c; font-size: 18px; font-weight:600;">' . DEFAULT_PRICE . floatval($price) . '</td>';
        echo '</tr>';
    }

    ?>
            			
            			</table>
													</td>
												</tr>
												<tr class="totals sub">
													<th data-bind="i18n: title" class="mark" scope="row"><strong>SUBTOTAL</strong></th>
													<td class="amount"><span class="price" data-th="SUBTOTAL"><strong><?php echo DEFAULT_PRICE.floatval($total); ?></strong></span>
													</td>
												</tr>
												<tr class="totals-tax">
													<th data-bind="text: title" class="mark" colspan="1"
														scope="row">Tax</th>
													<td data-bind="attr: {'data-th': title}" class="amount"
														data-th="Tax"><span class="price">$0.00</span></td>
												</tr>
												<tr class="grand totals">
													<th class="mark" scope="row"><strong
														data-bind="i18n: title">Order Total</strong></th>
													<td data-bind="attr: {'data-th': title}" class="amount"
														data-th="Order Total"><strong><span class="price"
															data-bind="text: getValue()"><?php echo DEFAULT_PRICE.floatval($total); ?></span></strong>
														<input type="hidden" name="total"
														value="<?php echo $total; ?>"></td>
												</tr>
												<tr style="border-top: #FFF;">
													<td colspan="2" style="border-top: #FFF;"><h4>
															<input type="radio" CHECKED> Credit Card
														</h4> <img alt="" src="images/cards.png"></td>
												</tr>
												<tr style="border-top: #FFF;">
													<td colspan="2" style="border-top: #FFF;">Pay securely
														using your credit card.</td>
												</tr>

												<tr style="border-top: #FFF;">
													<td colspan="2" style="border-top: #FFF;">
														<div class="field required">
															<label for="card_no" class="label"><span>Card Number</span></label>
															<div class="control">
																<input type="tel" name="card_no" id="card_no"
																	autocomplete="off" value="" title="Card Number"
																	class="single-input ccFormatMonitor"
																	aria-required="true" maxlength="19"
																	placeholder="&bull;&bull;&bull;&bull; &bull;&bull;&bull;&bull; &bull;&bull;&bull;&bull; &bull;&bull;&bull;&bull;">
																<p></p>
															</div>
														</div>
													</td>
												</tr>
												<tr style="border-top: #FFF;">
													<td colspan="2" style="border-top: #FFF;">
														<div class="field required">
															<label for="card_exp" class="label"><span>Expiration</span></label>
															<div class="control">
																<input type="tel" name="card_exp" id=""
																	autocomplete="off" value="" title="Card Expiry Date"
																	class="single-input" aria-required="true" maxlength="5"
																	onkeyup="formatExpiry(event);" placeholder="MM/YY">
																<p></p>
															</div>
														</div>
													</td>
												</tr>
												<tr style="border-top: #FFF;">
													<td colspan="2" style="border-top: #FFF;">
														<div class="field required">
															<label for="card_csc" class="label"><span>Card Security
																	Code</span></label>
															<div class="control">
																<input type="tel" name="card_csc" id=""
																	autocomplete="off" value="" title="Card Security Code"
																	class="single-input" aria-required="true" maxlength="3"
																	placeholder="CSC">
																<p></p>
															</div>
														</div>
													</td>
												</tr>

												<!-- /ko -->
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>


						<button type="button" id="confirmOrder"
							data-role="proceed-to-checkout" title="CHECKOUT"
							class="action genric-btn primary checkout">
							<span><i class="fa fa-paper-plane"></i> Place Order</span>
						</button>

					</form>

				</div>
			</div>
		</div>
	</section>
			
			<?php
} else {

    echo '<script>
               
                 location.href = "/index";
    </script>';
}
?>
		</main>

<script>

 $(function(){
		$("#confirmOrder").click(function(){
			$("#frmPlaceOrder").submit();
		});

$("#country").on("change", function(){
			var contrID = $(this).val();
			
			
			$.ajax({
					method: 'GET',
					url: '<?php echo  SITE_URL; ?>ajax_get_country?country_id='+contrID,
					success: function(data){
				
						$("#state").html(data);
						}
				});
		});
		
	});

	
    
    </script>
<?php
include_once 'layout/footer.php';
?>