<?php
include_once 'functions.php';
include_once 'layout/header-design.php';

if (isset($_POST['formid'])) {
    if ($_POST['formid'] == md5('waseem')) {

        if (isset($_SESSION['card_options'])) {
            $CardOptions = implode(", ", $_SESSION['card_options']);
        }

        DB::insert('quote_card_info', array(
            'metal_stock' => cleanVar(@$_SESSION['metal_stock']),
            'color_front' => cleanVar(@$_SESSION['color_front']),
            'color_back' => cleanVar(@$_SESSION['color_back']),
            'card_options' => $CardOptions,
            'has_sample' => 0,
            'status'    => 1
        ));

        $quote_info_id = DB::insertId();
        DB::insert('quote_customers', array(
            'quote_card_info_id' => $quote_info_id,
            'fname' => cleanVar($_POST['fname']),
            'lname' => cleanVar($_POST['lname']),
            'email' => cleanVar($_POST['email']),
            'contact' => cleanVar($_POST['phone']),
            'state' => cleanVar($_POST['state']),
            'country' => 'USA',
            'company' => cleanVar(@$_SESSION['company']),
            'password' => substr(cleanVar($_POST['phone']), - 4),
            'information' => cleanVar(@$_POST['information']),
            'referer' => @$_SESSION['REFERER']
        ));

        $customer_id = DB::insertId();

        if (isset($_SESSION['file'])) {
            $dir = __DIR__.'/uploads/customer_' . $customer_id;
            if (! file_exists($dir)) {
                mkdir($dir, 0777, true);
            }
            try {
                DB::insert('quote_card_desings', array(
                    'quote_card_info_id' => $quote_info_id,
                    'design' => $_SESSION['file'],
                    'created_by' => @$_POST['fname'] . ' ' . @$_POST['lname'],
                    'created_on' => date('Y-m-d H:i:s'),
                    'status' => 'Pending',
                    'remarks' => cleanVar(@$_POST['information']),
                    'is_final' => 0
                ));
                rename(__DIR__.'/uploads/temp/' . $_SESSION['file'], $dir . '/' . $_SESSION['file']);
            } catch (Exception $e) {}
        }

        if (isset($_POST['email']) and ($_POST['email'] != '')) {
            try {
                 send_email_to_customer($_POST['fname'] . ' ' . $_POST['lname'], $_POST['email']);
            } catch (Exception $e) {}
        }

        if (isset($_POST['phone'])) {
            $handle2 = curl_init();

            $url2 = SITE_URL . "twiliosms/send_invoice_4564?phone=" . $_POST['phone'];

            curl_setopt($handle2, CURLOPT_URL, $url2);

            curl_setopt($handle2, CURLOPT_RETURNTRANSFER, true);

            curl_exec($handle2);

            curl_close($handle2);
            $handle = curl_init();

            $url = SITE_URL . "twiliosms/ping_admin?orders_id=" . $quote_info_id;

            curl_setopt($handle, CURLOPT_URL, $url);

            curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);

            curl_exec($handle);

            curl_close($handle); 
        }

        $_SESSION['is_success_wood'] = 1;
        echo '<script>
    location.href="./free-design-success";
</script>';
        echo "Success. Thanks..!";
       // header("Location: ./free-design-success");
        exit();
    }
}

if (isset($_POST['metal_stock'])) {
    $metalStock = implode(", ", $_POST['metal_stock']);
    $_SESSION['metal_stock'] = $metalStock;
    $_SESSION['color_front'] = isset($_POST['color_front']) ? $_POST['color_front'] : '';
    $_SESSION['color_back'] = isset($_POST['color_back']) ? $_POST['color_back'] : '';
    $_SESSION['step5'] = 'ok';
}

?>
<form method="POST" action="" id="frmFinalStep">
	<div class="launch-contest-mid common-css">
		<input type="hidden" name="formid"
			value="7e4b9f14903758dd6dae26a7ff3d2624">
		<div class="no-padding">
		<div class="col-md-3 sidebar-left" style="padding-left: 0px;">
		<img alt="" src="<?php echo SITE_URL; ?>getimage/350x350/sidebar-left.png" style="width: 230px;">
		</div>
			<div class="col-md-6">
				<h2>Your Information For Your Wood Card Design</h2>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<div class="col-md-6">
								<label class="required">First Name</label> <input type="text"
									class="form-control" name="fname" id="fname" required=""
									placeholder="Doe">
							</div>
							<div class="col-md-6">
								<label class="required">Last Name</label> <input type="text"
									class="form-control" name="lname" id="lname" required=""
									placeholder="Jones">
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-12">
								<label class="required">Where to we email Your Free Designs?</label> <input type="email"
									class="form-control" name="email" id="email" required=""
									placeholder="abc@example.com">
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-6">
								<label class="required">State</label> <select
									class="form-control" name="state" id="state" required="">
									<option value="">-SELECT STATE-</option>	
                            	<?php
                            $states = DB::query("SELECT `name` FROM states WHERE `country_id` = '231'");

                            foreach ($states as $row) {
                                echo '<option value="' . $row['name'] . '">' . $row['name'] . '</option>';
                            }
                            ?>
								</select>
							</div>
							<div class="col-md-6">
								<label class="required">Contact</label>
								<div class="form-group">
									<div class="input-group">
										<span class="input-group-addon">+1</span> <input type="text"
											class="form-control" id="phone" name="phone" maxlength="10"
											value="" placeholder="Contact" required="required">
									</div>
								</div>
							</div>
						</div>
						 <div class="form-group">
							<div class="col-md-12">
								<label>Any additional information</label>
								<textarea name="information"
									placeholder="Any and all information needed for design! "
									class="form-control"></textarea>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-3 sidebar-right" style="padding-right: 0px;">
		<img alt="" src="<?php echo SITE_URL; ?>getimage/350x350/sidebar-right.png" style="max-width: 100%;">
		</div>
		</div>
		<div class="clearfix"></div>
	</div>
	<div class="clearfix"></div>
	<br> <br> <br> <br>
<?php
include_once 'layout/footer-design.php';
include_once 'layout/footer.php';
?>