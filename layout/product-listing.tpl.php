<div class="col-md-3 col-sm-6 wow fadeInUp">
	<div class="product-grid6">
		<div class="product-image6">
			<a href="#"> <img class="pic-1"
				src="<?php echo SITE_URL; ?>getimage/350x350/products/<?php echo $product['img_path']; ?>" alt="<?php  echo $product['name']; ?>">
			</a>
		</div>
		<div class="product-content">
			<h3 class="title">
				<a href="#"><?php  echo $product['name']; ?></a>
			</h3>
			<div class="price">
				<?php echo DEFAULT_PRICE.get_product_price($product['products_id']); ?> <!-- <span>$0.00</span> -->
			</div>
		</div>
		<ul class="social">
			<li><a href="<?php echo SITE_URL; ?>p/<?php echo str_replace(" ", "-", $product['name']).'/'.$product['products_id']; ?>" data-tip="Quick View"><i class="fa fa-search"></i></a></li>
			<li><a href="" data-tip="Add to Wishlist"><i
					class="fa fa-shopping-bag"></i></a></li>
			<li><a href="<?php echo SITE_URL; ?>p/<?php echo str_replace(" ", "-", $product['name']).'/'.$product['products_id']; ?>" data-tip="Add to Cart"><i class="fa fa-shopping-cart"></i></a></li>
		</ul>
	</div>
</div>