<?php
include_once 'functions.php';

include_once 'layout/header.php';

$error = '';
$success = '';
if (isset($_POST['btnLogin'])) {
    $email = isset($_POST['user']) ? cleanVar($_POST['user']) : '';
    $passw = isset($_POST['password']) ? cleanVar($_POST['password']) : '';

    if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error .= 'Email invalid,';
    }
    if (strlen($passw) < 3) {
        $error .= ' Password invalid';
    }

    if ($error == '') {
        $customer = DB::queryFirstRow("SELECT * FROM customers c WHERE (c.`email` LIKE '" . $email . "' AND c.`password` = '" . $passw . "') AND 1=1 ");

        if (DB::count() > 0) {
            $success = 'Login success';
            $_SESSION['customers_is_login'] = 1;
            $_SESSION['customers_id'] = $customer['customers_id'];
            $_SESSION['customers_email'] = $customer['email'];
            $_SESSION['customers_fname'] = $customer['fname'];
            $_SESSION['customers_lname'] = $customer['lname'];
            echo '<script>  window.setTimeout(function(){ location.href="'.SITE_URL.'account"; }, 1000);</script>';
        } else {
            $error = 'Invalid email or Password';
        }
    }
}



if(isset($_SESSION['customers_id'])){
    
    echo '<script> location.href="'.SITE_URL.'account";</script>';
    
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
								<h2 data-animation="fadeInLeft" data-delay=".4s">Login</h2>
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

 Services Area Start -->
	<section class="categories-area section-padding40">
		<div class="container">
			<!-- section Tittle -->
			<div class="col-lg-8">
        <small class="pull-right">Don't have account? <a href="<?php echo SITE_URL; ?>account" class="genric-btn link-border circle">Create account</a></small>
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
        
                <form class="form-contact" action="" method="post"
					id="loginfrm">
					<div class="row">

						<div class="col-12">
							<div class="form-group">
								<label>E-mail:</label> <input class="form-control" name="user"
									id="user" type="text" onfocus="this.placeholder = ''"
									onblur="this.placeholder = 'Enter Email'"
									placeholder="Your Email Address" required="required" value="<?php echo @$email; ?>">
							</div>
						</div>

						<div class="col-12">
							<div class="form-group">
								<label>Password:</label> <input class="form-control"
									name="password" id="password" type="password"
									onfocus="this.placeholder = ''"
									onblur="this.placeholder = 'Enter Password'"
									placeholder="Password" required="required">
							</div>
						</div>
					</div>
					<div class="form-group mt-3">
						<button type="submit" name="btnLogin"
							class="button button-contactForm boxed-btn">Login</button>
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