<?php
include_once 'functions.php';

if (isset($_FILES['file'])) {

    $handle = new Upload($_FILES['file']);
    if ($handle->uploaded) {
        
        $img_name = cleanName($_SESSION['company']);
 
        $type = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
        $img_types = array(
            'gif',
            'png',
            'jpeg',
            'bmp',
            'webp'
        );
        
        
        if (in_array($type, $img_types)) {
            $type = 'jpg';
            $handle->image_convert = 'jpg';
        }
        
      $handle->image_resize = true;
       $handle->image_x = 1080;
      $handle->image_ratio = true;
       $handle->image_ratio_y = true;
        $handle->file_new_name_body = $img_name;
        $handle->process(__DIR__.'/uploads/temp/');
        if ($handle->processed) {
            $_SESSION['file'] = $img_name . '.' . $type;
            $_SESSION['file_name'] = $img_name;
            $_SESSION['file_type'] = $type;
            $handle->clean();
        } else {
            echo 'error : ' . $handle->error;
        }
    }

    $_SESSION['step4'] = 'ok';
}

include_once 'layout/header-design.php';
?>
<form method="POST" action="<?php echo $next; ?>">
	<div class="launch-contest-mid common-css">
		<div class="container">
			<h1>Wood Stock</h1>
			<h4>Choose a wood, or go with a random assortment!</h4>
			<div class="col-md-12">
				<div class="row">
			<?php
$options = DB::query("SELECT p.`products_id`, p.`name` FROM products p WHERE p.`categories_id` = 11 GROUP BY p.`name` ORDER BY p.`name` ");
foreach ($options as $opt) {
    ?>
				<!-- <div class="col-md-3 col-sm-3 col-lg-3 col-xs-6 text-center">
						<label class="image-radio">
							<div class="card_options" style="background-image: url('getimage/350x350/products/<?php //echo get_product_img($opt['products_id']); ?>');background-size: cover;"
							>&nbsp;</div> <input type="radio" name="metal_stock"
							value="<?php //echo $opt['name']; ?>" /> <i
							class="fa fa-check hidden fa-radio"></i> <?php //echo $opt['name']; ?>
					</label>
					</div> -->
					<div class="col-md-3 col-sm-3 col-lg-3 col-xs-6 text-center">
						<label class="image-checkbox">
							<div class="card_options" style="background-image: url('<?php echo SITE_URL; ?>getimage/250x250/products/<?php echo get_product_img($opt['products_id']); ?>');background-size: cover;"
							>&nbsp;</div> <input type="checkbox" name="metal_stock[]"
							value="<?php echo $opt['name']; ?>" /> <i
							class="fa fa-check hidden fa-checkbox"></i> <?php echo $opt['name']; ?>
							
					</label>
					</div>
				<?php } ?>
			
			</div>
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