<?php
include_once 'functions.php';

include_once 'layout/header.php';

$error = '';
$success = '';
if (isset($_POST['btnUpdate'])) {
    if (isset($_SESSION['customers_id'])) {
        // update

        // check email duplication

        @extract($_POST);

        $is_email_exist = DB::queryFirstField("SELECT email FROM customers WHERE email='" . $email . "' AND customers_id !='" . $_SESSION['customers_id'] . "'");

        if (DB::count() > 0) {
            $error .= 'Email already in use, please try different one';
        }

        if ($error == '') {

            DB::update('customers', array(
                'fname' => $fname,
                'lname' => $lname,
                'email' => $email,
                'contact' => $contact,
                'address' => $address,
                'city' => $city,
                'state' => $state,
                'country' => $country,
                'zip' => $zip
            ), 'customers_id=%s', $_SESSION['customers_id']);
            $success = ' Information updated.';
            
            echo '<script>  window.setTimeout(function(){ location.href="'.SITE_URL.'index"; }, 1000);</script>';
            
        }
    } else {

        // insert
        
        
        @extract($_POST);
        
        $is_email_exist = DB::queryFirstField("SELECT email FROM customers WHERE email='" . $email . "'");
        
        if (DB::count() > 0) {
            $error .= 'Email already in use, please try different one';
        }
        
        if($password<>$confirm_password){
            $error .= ' Password did not match. please confirm both password same';
        }
        
        if ($error == '') {
            
            DB::insert('customers', array(
                'fname' => $fname,
                'lname' => $lname,
                'email' => $email,
                'contact' => $contact,
                'address' => $address,
                'password' => $password,
                'city' => $city,
                'state' => $state,
                'country' => $country,
                'created_on'    => $now,
                'newsletter'    => 1,
                'zip' => $zip
            ));
            $success = ' Account created. Please login now. ';
            
            echo '<script>  window.setTimeout(function(){ location.href="'.SITE_URL.'login"; }, 1000);</script>';
        }
        
        
        
    }
}

?>

<main>
	<!--? slider Area Start
	<div class="slider-area position-relative">
		<div class="slider-active">
		
			<div
				class="single-slider position-relative hero-overly slider-height2  d-flex align-items-center"
				data-background="images/hero/h1_hero.png">
				<div class="container">
					<div class="row">
						<div class="col-xl-6 col-lg-6">
							<div class="hero-caption hero-caption2">
								<img src="images/hero/hero-icon.png" alt=""
									data-animation="zoomIn" data-delay="1s">
								<h2 data-animation="fadeInLeft" data-delay=".4s">Account</h2>
							</div>
						</div>
					</div>
				</div>
				
				<div class="hero-img hero-img2">
					<img src="images/hero/h2_hero2.png" alt=""
						data-animation="fadeInRight" data-transition-duration="5s">
				</div>
			</div>
		</div>
	</div>

	<!--? Services Area Start -->
	<section class="categories-area section-padding40">
		<div class="container">
			<!-- section Tittle -->
			<div class="col-md-8 offset-md-3">
			
			        <?php if($error<>''): ?>
        
        <div class="alert alert-danger">
					<strong>Sorry! </strong> <?php echo $error; ?>.
</div>
        
        <?php endif; ?>
        
                <?php if($success<>''): ?>
        
        <div class="alert alert-success">
					<strong>Success! </strong> <?php echo $success; ?>.
</div>
        
        <?php endif; ?>
        
        
<?php

if (isset($_SESSION['customers_id'])) {

    $customer = DB::queryFirstRow("SELECT * FROM customers c WHERE c.`customers_id` = '" . $_SESSION['customers_id'] . "'");

    @extract($customer);
}
?>
        
                <form class="form-contact" action="" method="post">
					<div class="row">
						<div class="col-sm-8 col-sm-offset-4">
						
						<?php
    if (! isset($_SESSION['customers_id'])) {
        ?>
						<h3>Create Account</h3>
							<small class="pull-right">Already have an account <a
								href="<?php echo SITE_URL; ?>login"
								class="genric-btn link-border circle">Login</a></small> 
								
								<?php } else { ?>
								<h3>My Account</h3>
								<?php } ?>
								<input name="form_key" type="hidden"
								value="b61c3340d363bdcb3ec0b49462299d7c0f1cb01e">
							<fieldset class="fieldset create info">
								
						<?php

    include 'layout/account_fields.php';

    if (! isset($_SESSION['customers_id'])) {

        ?>
					
		<div class="field required">
									<label for="password" class="label"><span>Password</span></label>
									<div class="control">
										<input type="password" name="password" id="password" value=""
											title="Password" class="single-input"
											data-validate="{required:true}" aria-required="true">
									</div>
								</div>

								<div class="field required">
									<label for="password" class="label"><span>Confirm Password</span></label>
									<div class="control">
										<input type="password" name="confirm_password"
											id="confirm_password" value="" title="Re enter Password"
											class="single-input" data-validate="{required:true}"
											aria-required="true">
									</div>
								</div>				
		<?php } ?>
						<div class="form-group mt-3">
									<button type="submit" name="btnUpdate"
										class="button button-contactForm boxed-btn">Continue</button>
								</div>
							</fieldset>

						</div>
					</div>

				</form>
			</div>
		</div>

	</section>
	<!-- instagram-social End -->
</main>
<?php

include_once 'layout/footer.php';
?>