<?php
include_once 'functions.php';
include_once 'layout/header-design.php';
if (isset($_SESSION['is_success_wood'])) {
    unset($_SESSION['step2']);
    unset($_SESSION['step3']);
    unset($_SESSION['step4']);
    unset($_SESSION['step5']);

} else {
    echo '<script>
    location.href="./index";
</script>';
   // header("Location: index");
}

?>
<div class="launch-contest-mid common-css text-center">
	<div class="container">
		<div class="col-md-12">
			<div class="row">
				<h1 class="text-success">
					<i class="fa fa-check-circle" aria-hidden="true"></i><BR> <strong>Thank
						You</strong> <BR> We will contact you soon
				</h1>
			</div>
		</div>
	</div>
	<div class="clearfix"></div>
</div>
<div class="clearfix"></div>
<br>
<br>
<br>
<br>
<?php
include_once 'layout/footer-design.php';
include_once 'layout/footer.php';
?>