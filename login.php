<?php
include_once 'functions.php';

include_once 'layout/header.php';


?>

<main>
    <!--? slider Area Start-->
    <div class="slider-area position-relative">
        <div class="slider-active">
            <!-- Single Slider -->
            <div class="single-slider position-relative hero-overly slider-height2  d-flex align-items-center" data-background="images/hero/h1_hero.png">
                <div class="container">
                 <div class="row">
                     <div class="col-xl-6 col-lg-6">
                        <div class="hero-caption hero-caption2">
                            <img src="images/hero/hero-icon.png" alt="" data-animation="zoomIn" data-delay="1s">
                            <h2 data-animation="fadeInLeft" data-delay=".4s">Login</h2>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Left img -->
            <div class="hero-img hero-img2">
                <img src="images/hero/h2_hero2.png" alt=""  data-animation="fadeInRight" data-transition-duration="5s">
            </div>
        </div>
    </div>
</div>

<!--? Services Area Start -->
<section class="categories-area section-padding40">
    <div class="container">
        <!-- section Tittle -->
        <div class="col-lg-8">
                <form class="form-contact" action="" method="post" id="loginfrm" >
                    <div class="row">
                       
                        <div class="col-12">
                            <div class="form-group">
                                <input class="form-control" name="user" id="user" type="text" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter UserName'" placeholder="User Name/Email">
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-group">
                                <input class="form-control" name="password" id="password" type="password" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter Password'" placeholder="Password">
                            </div>
                        </div>
                    </div>
                    <div class="form-group mt-3">
                        <button type="submit" class="button button-contactForm boxed-btn">Login</button>
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