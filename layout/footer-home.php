
<footer class="footer">
	<div class="container">
		<div class="row">

			<div class="col-md-4 col-sm-6 col-xs-12">
				<span class="logo"><img class="logo" style="max-width: 100%;"
					alt="Wood Business Cards Logo"
					src="<?php echo SITE_URL; ?>images/logo.png?v=1.2"></span>
				<p style="color: #FFF;">Created by our world class in house Design
					experts all the way down to the artists etching & custom cutting.
					You Provide the information needed we provide Craftmanship you
					expect.</p>
			</div>

			<div class="col-md-4 col-sm-6 col-xs-12">
				<ul class="menu">
					<span>Menu</span>
					<li><a href="<?php echo SITE_URL; ?>">Home</a></li>

					<li><a href="<?php echo SITE_URL; ?>#about">About</a></li>
					<li><a href="<?php echo SITE_URL; ?>wood-cards">Gallery </a></li>
					<!-- <li><a target="_BLANK" href="<?php echo SITE_URL; ?>blog">Blog</a></li> -->
<li><a href="<?php echo SITE_URL; ?>terms-of-service">Terms</a></li>
<li><a href="<?php echo SITE_URL; ?>privacy-policy">Privacy Policy</a></li>
					<li><a href="<?php echo SITE_URL; ?>contact-us">Contact us</a></li>

					<li><a href="#" onclick="event.preventDefault()"
						data-toggle="modal" data-target="#quoteModal">Get Free Design</a></li>
				</ul>
			</div>

			<div class="col-md-4 col-sm-6 col-xs-12">
				<ul class="address">
					<span>Contact</span>
					<li><i class="fa fa-phone" aria-hidden="true"> Phone: </i><a
						href="tel:+19495737226">(949)573-7226</a></li>
					<li><i class="fa fa-map-marker" aria-hidden="true"> Address: </i><a
						target="_BLANK" href="https://goo.gl/maps/DeQKazrNwus9SRbp6">17221
							San Carlos Blvd, Fort Myers Beach, FL 33931, USA</a></li>
					<li><i class="fa fa-envelope" aria-hidden="true"> Email: </i><a
						href="mailto:frank@metalcardguy.com">frank@metalcardguy.com</a></li>
				</ul>
			</div>


		</div>
	</div>

	<div class="footer-bottom">
	Copyright &copy; <?php echo date('Y').'. '.COMPANY_NAME; ?>
	<!--<a href="sitemap.xml" style="color: #8d8d8d; float: right;">SITEMAP</a>-->
	</div>

</footer>
<!-- Modal -->
<div id="quoteModal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center">Start Your Design</h4>
			</div>
			<div class="modal-body">

				<form action="<?php echo SITE_URL; ?>launch-design-options"
					method="GET">
					<div
						class="col-lg-10 col-md-12 col-sm-12 no-padding center-col search_top1">
						<h4>
							Upload your Logo, Choose your Options<BR> Receive your Free Wood
							Business Card Designs!
						</h4>
						<div
							class="col-lg-9 col-md-9 col-sm-12 no-margin no-padding top_seearch_div">
							<input required="" type="text" id="cname" name="cname"
								placeholder="Enter Your Business Name"
								class=" top_serch_input form-control"
								value="<?php echo @$_SESSION['company']; ?>">
						</div>
						<div class="col-lg-2 col-md-2 col-sm-12 no-margin no-padding ">
							<button type="submit"
								class="highlight-button-dark btn btn-large  button xs-margin-bottom-five get_desi">Get
								Designs</button>
						</div>
					</div>
					<div class="clearfix"></div>
				</form>
			</div>
			<div class="modal-footer"></div>
		</div>
	</div>
</div>


<div class="modal fade" id="imagemodal" tabindex="-1" role="dialog"
	aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
				</button>
				<img src="" class="imagepreview" style="width: 100%;">
			</div>
		</div>
	</div>
</div>

<?php if(!isset($_SESSION['offer_closed'])): ?>

<div class="modal fade" id="offermodal" tabindex="-1" role="dialog"
	aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content"
			style="background: url('<?php echo SITE_URL; ?>getimage/200x350/products/Woo698.jpg') no-repeat;background-size: cover;">
			<div class="modal-body">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
				</button>
				<h2 class="text-center">Wooden Business Cards</h2>
				<h3 class="text-center">cost you as low as</h3>
				<h1 class="text-center">
					<strong>$0.28</strong><span style="font-size: 14px;">only per card</span>
				</h1>
			
				<p class="text-center">We offer you free design before you buy</p>
				<div class="text-center">
					<a href="#" class="btn btn-success"
						onclick="event.preventDefault()" id="requestSample"
						data-toggle="modal" data-target="#quoteModal">Request Free Sample</a>
					<br>-or-<br> <a href="contact-us"><strong>Contact us</strong></a>
				</div>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>


<?php if(!is_localhost()): ?>
<!-- Start of LiveChat (www.livechatinc.com) code-->
<script type="text/javascript">
            window.addEventListener("DOMContentLoaded", function(){
                
            })
        </script>
<script type="text/javascript">
 window.__lc = window.__lc || {};
  window.__lc.license = 6991821;
  (function() {
    var lc = document.createElement('script'); lc.type = 'text/javascript'; lc.async = true;
    lc.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'cdn.livechatinc.com/tracking.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(lc, s);
  })();
</script>
<noscript>
	<a href="https://www.livechatinc.com/chat-with/6991821/" rel="nofollow">Chat
		with us</a>, powered by <a href="https://www.livechatinc.com/?welcome"
		rel="noopener nofollow" target="_blank">LiveChat</a>
</noscript>
<!-- End of LiveChat code -->

<?php endif; ?>