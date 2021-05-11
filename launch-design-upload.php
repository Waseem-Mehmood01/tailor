<?php
include_once 'functions.php';
if(isset($_POST['card_options'])){
    $_SESSION['card_options'] = $_POST['card_options'];
    $_SESSION['step3'] = 'ok';
}
include_once 'layout/header-design.php';
?>
<form method="POST" action="<?php echo $next; ?>" enctype="multipart/form-data">
<div class="launch-contest-mid common-css">
		<div class="container">
			<div class="col-md-12">
				<div class="row">
					<div class="col-md-6 col-md-offset-3">
						<div class="file-upload">
							<button class="file-upload-btn Uploaded Image image-title"
								type="button"
								onclick="$('.file-upload-input').trigger( 'click' )">
								<i class="fa fa-cloud-upload" aria-hidden="true"></i>&nbsp;Upload
								Logo
							</button>
							<div class="image-upload-wrap">
								<input class="file-upload-input" type='file'
									onchange="readURL(this);" name="file" id="file" accept="image/*" />
								<div class="drag-text">
									<h3>Drag and drop a file or upload logo</h3>
								</div>
							</div>
							<div class="file-upload-content">
								<img class="file-upload-image" src="#" alt="your image" />
								<div class="image-title-wrap">
									<button type="button" onclick="removeUpload()"
										class="remove-image btn btn-danger">
										<i class="fa fa-trash-o fa-2x" aria-hidden="true"></i></span>
									</button>
								</div>
							</div>
							<h4>Prefered file formats</h4>
							<img alt="" src="<?php echo SITE_URL; ?>images/filtype.jpg">
						</div>
					</div>
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