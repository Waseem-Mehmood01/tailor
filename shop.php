<?php
include_once 'functions.php';

include_once 'layout/header.php';

?>

<main>
	<!--? slider Area Start-->
	<div class="slider-area position-relative">
		<div class="slider-active">
			<!-- Single Slider -->
			<div
				class="single-slider position-relative hero-overly slider-height2  d-flex align-items-center"
				data-background="<?php echo SITE_URL; ?>images/hero/h1_hero.png">
				<div class="container">
					<div class="row">
						<div class="col-xl-6 col-lg-6">
							<div class="hero-caption hero-caption2">
								<img src="images/hero/hero-icon.png" alt=""
									data-animation="zoomIn" data-delay="1s">
								<h2 data-animation="fadeInLeft" data-delay=".4s">Shop</h2>
							</div>
						</div>
					</div>
				</div>
				<!-- Left img -->
				<div class="hero-img hero-img2">
					<img src="<?php echo SITE_URL; ?>images/hero/h2_hero2.png" alt=""
						data-animation="fadeInRight" data-transition-duration="5s">
				</div>
			</div>
		</div>
	</div>
	<!-- slider Area End-->
	<!--?  Contact Area start  -->
	<section class="contact-section">
		<div class="container">
			<div class="row">

				<div class="container">

					<div class="row">
					
								<?php

        $page_url = SITE_URL . "shop/";

        if (isset($_REQUEST["page"])) {
            $page = (int) $_REQUEST["page"];
        } else {
            $page = 1;
        }

        $setLimit = 15;

        $pageLimit = ($page * $setLimit) - $setLimit;

        $sql = "SELECT p.products_id,p.name,p.`description1`, i.`img_path` FROM products p, products_img i, categories c 
WHERE  p.`categories_id` = c.`categories_id` AND p.`products_id`=i.`products_id` GROUP BY p.`products_id` ORDER BY p.`products_id` DESC ";
        $sql2 = $sql;
        $sql .= ' LIMIT ' . $pageLimit . ', ' . $setLimit;

        $products = DB::query($sql);
        foreach ($products as $product) {
            include ('layout/product-listing.tpl.php');
        }
        ?>

					</div>
				</div>

			</div>
		</div>
		<div class="pull-right">
		<?php echo displayPaginationOverride($setLimit,$page,$sql2,$page_url);  ?>
		</div>

	</section>
	<!-- Contact Area End -->
</main>
<?php

include_once 'layout/footer.php';
?>