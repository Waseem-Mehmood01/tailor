<?php
include_once 'functions.php';

if (isset($_GET['cname'])) {
    $_SESSION['company'] = $_GET['cname'];
    $_SESSION['step2'] = 'ok';
} else if (isset($_SESSION['company'])) {} else {
    header('Location: home');
    exit();
}
include_once 'layout/header-design.php';
?>
<form method="POST" action="<?php echo $next; ?>">
	<div class="launch-contest-mid common-css">
		<div class="container">
			<h1>Choose Your Options</h1>
			<h4>Help us understand how to trailor your designs to what you
				looking for!</h4>
			<div class="col-md-12">
				<div class="row">
			<?php
$options = DB::query("SELECT * FROM card_options");
foreach ($options as $opt) {
    ?>
				<div class="col-md-3 col-sm-3 col-lg-3 col-xs-6 text-center">
						<label class="image-checkbox">
							<div class=" card_options" style="background-image: url('<?php echo SITE_URL; ?>getimage/250x250/cards/<?php echo $opt['featured_img']; ?>');background-size: cover;"
							>&nbsp;</div> <input type="checkbox" name="card_options[]"
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