<?php
$meta = getMetaTags();
$curPageName = substr($_SERVER["SCRIPT_NAME"], strrpos($_SERVER["SCRIPT_NAME"], "/") + 1);
$next = getNextStep($curPageName);

if(!isset($_SESSION['company'])){
    header("Location: home");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<title><?php echo $meta['site_title']; ?> | Launch Design</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description"
	content="<?php echo $meta['site_description']; ?>">
<meta name="keywords" content="<?php echo $meta['site_tags']; ?>">
<meta name="robots" content="<?php echo $meta['meta_robots']; ?>">
<link rel="stylesheet" href="assets/bootstrap/css/bootstrap.css">
<link rel="stylesheet" href="assets/font-awesome/css/font-awesome.css">
<link href="https://fonts.googleapis.com/css?family=Lato"
	rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Montserrat"
	rel="stylesheet" type="text/css">

<link rel="stylesheet" href="assets/css/style.css?v=1.3.4">
<link rel="stylesheet" href="assets/css/options.css?v=1.7.5">
<script async
	src="https://www.googletagmanager.com/gtag/js?id=AW-986154232"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    
    gtag('config', 'AW-986154232');
    </script>
</head>
<body class="bg-white">
	<header>
		<!-- <div class="festive-strip">
			<span class="festive-textCss avail_offer  whiteBtnNectar"
				data-coupon="SPECIAL100"><span class="front--div">Grab the Larget
					Sale in History, <b style="color: #FFFFFF;">Buy 250 Cards get 250
						Cards Free!</b>&nbsp;All Starts with Free Design
			</span> </span>
		</div> -->
		<div class="col-md-6 col-sm-6">
			<a href="<?php echo SITE_URL; ?>"><img alt="" style="max-width: 170px;" src="images/logow.png"></a>
		</div>
		<div class="col-md-6 col-sm-6">
			<ul id="simple-menu" class=" pull-right">
				<li><a href="tel:+19495737226"><img style="width: 27px; margin-top: -3px;" src="images/usa.jpg">&nbsp;949-573-7226</a></li>
				<li><a href="wood-cards">Engraved Wood Cards</a></li>
				<li><a href="wood-cards">Printed Wood Cards</a></li>
			</ul>
		</div>
	</header>
	<div class="launch_progress_bar  common-css">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12">
					<ul class="nav nav-pills nav-justified steps">
						<li class="" data-active="0" data-selected="1"><span
							class="main-stip strip-fill"> <a class=" autoSaveSteps  strips "
								href="home" title="Logo Design"><span
									class="show-design-categories">Company Name</span> <span>1</span>
							</a>
						</span></li>
						<li class="" data-active="1" data-selected="1"><span
							class="main-stip <?php if(isset($_SESSION['step2'])) echo 'strip-fill'; ?>">
								<a class=" autoSaveSteps  strips " href="<?php if(isset($_SESSION['step2'])){ echo 'launch-design-options'; }else { echo 'javascript: void(0);'; } ?>"
								title="Pick Your Style"><span class="show-design-categories">Choose
										Options</span> <span>2</span> </a>
						</span></li>
						<li class="" data-active="0" data-selected="0"><span
							class="main-stip <?php if(isset($_SESSION['step3'])) echo 'strip-fill'; ?>"> <a class=" strips "
								href="<?php if(isset($_SESSION['step3'])){ echo 'launch-design-upload'; }else { echo 'javascript: void(0);'; } ?>" title="Pick Colors"><span
									class="show-design-categories">Upload Logo</span> <span>3</span>
							</a>
						</span></li>
						<li class="" data-active="0" data-selected="0"><span
							class="main-stip <?php if(isset($_SESSION['step4'])) echo 'strip-fill'; ?>"> <a class=" strips "
								href="<?php if(isset($_SESSION['step4'])){ echo 'launch-design-print'; }else { echo 'javascript: void(0);'; } ?>" title="Project Brief and Package"><span
									class="show-design-categories">Wood Stock</span> <span>4</span>
							</a>
						</span></li>
						<li class="" data-active="0" data-selected="0"><span
							class="main-stip <?php if(isset($_SESSION['step5'])) echo 'strip-fill'; ?>"> <a class=" strips "
								href="<?php if(isset($_SESSION['step5'])){ echo 'launch-design-customer'; }else { echo 'javascript: void(0);'; } ?>" title="Pay &amp; Proceed"><span
									class="show-design-categories">Get Free Design</span> <span>5</span>
							</a>
						</span></li>
					</ul>
				</div>
			</div>
		</div>
	</div>