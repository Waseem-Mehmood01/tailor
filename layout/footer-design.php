<div class="fixed-footer common-css">
	<div class="container">
		<div class="row">
			<div
				class="col-md-2 col-sm-3 col-xs-12 lcnt-vbf-btn-action whiteBtnNectar text-left">
				<div class="pay_launch_btn process-css whiteBtnNectar"
					style="display: block;">
					<?php if(isset($_SESSION['is_success_wood'])){ ?>
					<a href="index" class="btn btn-default btn-vbf-continue">Continue</a>
					<?php } else { ?>
					<button type="submit" class="btn btn-default btn-vbf-continue">
					<?php

        if (isset($_SESSION['step5'])) {
            echo 'Get Free Design';
        } else {
            echo 'Next Step';
        }
        ?>
					</button>
					<?php } ?>
				</div>
			</div>
			<div class="col-sm-4 col-xs-12 pg--text">
				<div class="visual-moneyBack">
					<p class="visual-guarenteeCss">
						<i class="fa fa-check-circle fa-1x" aria-hidden="true"></i> Free
						Design before You Buy
					</p>
				</div>
			</div>
		</div>
	</div>
</div>
</form>
