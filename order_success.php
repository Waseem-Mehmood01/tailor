<?php
include_once 'functions.php';

include_once 'layout/header.php';

?>


<main>
	<div class="slider-area position-relative">
		<div class="slider-active">
			<div class="container">
				<h1 class="text-center">Order Confirmation</h1>
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

        ?>
<center>
						<h1 class="text-success">Order Success</h1>
						<h2 style="color: green;">THANK YOU FOR SHOPPING WITH US.</h2>
						<br> <a class="action genric-btn link-border continue"
							href="/home" title="Keep Shopping"> <span>Keep Shopping</span>
						</a>

					</center>
				<?php

        unset($_SESSION["cart_item"]);
    } else {
        echo '<script>
location.href="home";
</script>';
    }
    ?>
				</div>
			</div>
		</div>
	</section>


</main>

<?php
include_once 'layout/footer.php';
?>