<?php
include_once 'functions.php';

include_once 'layout/header.php';


function get_client_ip()
{
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if (getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if (getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if (getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if (getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
    else if (getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

$error_msg = '';
$success_msg = '';
$frmsubmit = FALSE;
if (isset($_POST['btnsubmit'])) {
   

        $name = isset($_POST['name']) ? cleanVar($_POST['name']) : '';
        $phone = isset($_POST['phone']) ? cleanVar($_POST['phone']) : '';
        $email = isset($_POST['email']) ? cleanVar($_POST['email']) : '';
        $message = isset($_POST['message']) ? cleanVar($_POST['message']) : '';

        if (strlen($name) < 3) {
            $error_msg .= 'Name Blank, ';
        }

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error_msg .= 'Email invalid, ';
        }
        if (strlen($message) < 5) {
            $error_msg .= 'Message Blank ';
        }
        if ($error_msg == '') {
            $insert = DB::insert('contact_us', array(
                'name' => $name,
                'phone' => $phone,
                'email' => $email,
                'message' => $message,
                'client_ip' => get_client_ip(),
                'created_on' => $now
            ));
            try {
                $sent = send_email_contact_us($name, $email);
                // print_r($sent);
            } catch (Exception $e) {}
            if ($insert) {
                $success_msg = '<i class="fa fa-check-circle" aria-hidden="true"></i><strong> Thank
						You</strong> We will contact you soon';
            } else {
                $error_msg = 'Whoops! Something went wrong. Please contact us directly by email/phone';
            }
            $frmsubmit = TRUE;
        } else {
            $frmsubmit = FALSE;
        }
   
}

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
                            <h2 data-animation="fadeInLeft" data-delay=".4s">contact</h2>
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
<!-- slider Area End-->
<!--?  Contact Area start  -->
<section class="contact-section">
    <div class="container">
 
 
         <?php if($error_msg<>''): ?>
        
        <div class="alert alert-danger">
					<strong>Sorry! </strong> <?php echo $error_msg; ?>.
</div>
        
        <?php endif; ?>
        
                <?php if($success_msg<>''): ?>
        
        <div class="alert alert-success">
					<strong>Success! </strong> <?php echo $success_msg; ?>.
</div>
        
        <?php endif; ?>
        
        
        <div class="row">
            <div class="col-12">
                <h2 class="contact-title">Get in Touch</h2>
            </div>
            <div class="col-lg-8">
                <form class="" action="" method="post" id="" novalidate="novalidate">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <textarea class="form-control w-100" name="message" id="message" cols="30" rows="9" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter Message'" placeholder=" Enter Message"></textarea>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <input class="form-control valid" name="name" id="name" type="text" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter your name'" placeholder="Enter your name">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <input class="form-control valid" name="email" id="email" type="email" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter email address'" placeholder="Email">
                            </div>
                        </div>
                       
                    </div>
                    <div class="form-group mt-3">
                        <button type="submit" name="btnsubmit" class="button button-contactForm boxed-btn">Send</button>
                    </div>
                </form>
            </div>
            <div class="col-lg-3 offset-lg-1">
                <div class="media contact-info">
                    <span class="contact-info__icon"><i class="ti-home"></i></span>
                    <div class="media-body">
                        <h3>Buttonwood, California.</h3>
                        <p>Rosemead, CA 91770</p>
                    </div>
                </div>
                <div class="media contact-info">
                    <span class="contact-info__icon"><i class="ti-tablet"></i></span>
                    <div class="media-body">
                        <h3>+1 253 565 2365</h3>
                        <p>Mon to Fri 9am to 6pm</p>
                    </div>
                </div>
                <div class="media contact-info">
                    <span class="contact-info__icon"><i class="ti-email"></i></span>
                    <div class="media-body">
                        <h3>support@colorlib.com</h3>
                        <p>Send us your query anytime!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Contact Area End -->
</main>
<?php

include_once 'layout/footer.php';
?>