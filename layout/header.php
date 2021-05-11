<?php
$prodID = isset($_REQUEST['product_id']) ? (int) $_REQUEST['product_id'] : '';
$meta = getMetaTags($prodID);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title><?php echo $meta['site_title']; ?></title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description"
	content="<?php echo $meta['site_description']; ?>">
<meta name="keywords" content="<?php echo $meta['site_tags']; ?>">
<meta name="robots" content="<?php echo $meta['meta_robots']; ?>">

    <meta http-equiv="x-ua-compatible" content="ie=edge">
    

    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.ico">

    <!-- CSS here -->
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/slicknav.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/flaticon.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/progressbar_barfiller.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/gijgo.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/animate.min.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/animated-headline.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/magnific-popup.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/fontawesome-all.min.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/themify-icons.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/slick.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/nice-select.css">
        <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/select2.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/style.css">
    
    <script src="<?php echo SITE_URL; ?>assets/js/jquery-3.3.1.min.js"></script>
<!-- Jquery, Popper, Bootstrap -->

<script src="<?php echo SITE_URL; ?>assets/js/bootstrap.min.js"></script>

</head>
<body>
    <!-- ? Preloader Start -->
    <div id="preloader-active">
        <div class="preloader d-flex align-items-center justify-content-center">
            <div class="preloader-inner position-relative">
                <div class="preloader-circle"></div>
                <div class="preloader-img pere-text">
                    <img src="<?php echo SITE_URL; ?>images/logo/loder.png" alt="">
                </div>
            </div>
        </div>
    </div> 
    <!-- Preloader Start-->
    <header>
        <!-- Header Start -->
        <div class="header-area header_area">
            <div class="main-header">
             <div class="header-bottom header-sticky">
                <!-- Logo -->
                <div class="logo">
                    <a href="<?php echo SITE_URL; ?>"><img src="<?php echo SITE_URL; ?>images/logo/logo.png" alt=""></a>
                </div>
                <div class="header-left  d-flex f-right align-items-center">
                    <!-- Main-menu -->
                    <div class="main-menu f-right d-none d-lg-block">
                        <nav> 
                            <ul id="navigation">                                                                                                                                     
                                <li><a href="<?php echo SITE_URL; ?>">Home</a></li>
                                <li><a href="<?php echo SITE_URL; ?>about-us">About</a></li>
                                <li><a href="<?php echo SITE_URL; ?>shop">shop</a></li>
                               <!--  <li><a href="services.html">Services</a></li>
                                <li><a href="blog.html">Blog</a>
                                    <ul class="submenu">
                                        <li><a href="blog.html">Blog</a></li>
                                        <li><a href="blog_details.html">Blog Details</a></li>
                                        <li><a href="elements.html">Elements</a></li>
                                    </ul>
                                </li> -->
                                <li><a href="<?php echo SITE_URL; ?>contact-us">Contact</a></li>
                            </ul>
                        </nav>
                    </div>
                    <!-- left Btn -->
                    <div class="header-right-btn f-right d-none d-lg-block  ml-30">
                        <div class="header-btn"><a title="<?php if(isset($_SESSION['customers_fname'])) echo ucwords($_SESSION['customers_fname'].' '.$_SESSION['customers_lname']) ?>" href="<?php echo SITE_URL; ?>login"><i class="fa fa-user" aria-hidden="true"></i></a>&nbsp;<a href="<?php echo SITE_URL; ?>cart"><i class="fa fa-shopping-cart" aria-hidden="true"></i> <span class='badge badge-warning' id='lblCartCount'> <?php if(!empty($_SESSION['cart_item'])){  echo '('.count($_SESSION['cart_item']).')'; } else { echo '0'; } ?> </span></a></div>
                    </div>
                </div>          
                <!-- Mobile Menu -->
                <div class="col-12">
                    <div class="mobile_menu d-block d-lg-none"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- Header End -->
</header>