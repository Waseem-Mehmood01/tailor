<?php
require '../twiliosms/vendor/autoload.php';

include_once '../twiliosms/variables.php';

$startsending = isset($_GET['startsending']) ? true : false;

$has_media = false;

if (isset($_POST['frmToken'])) {

    $txtfile = fopen("sms_campaing_logs.txt", "a");

    $handle = new Upload($_FILES['media']);

    if ($handle->uploaded) {
        $img_name = 'smscamp-' . date('d-m') . '-' . rand(2, 99);
        $type = substr($_FILES['media']['type'], 6, 10);
       /* $handle->image_resize = true;
        $handle->image_ratio = true;
        $handle->image_x = 650; */
        if ($type == 'jpeg') {
            $type = 'jpg';
        }
        $handle->file_new_name_body = $img_name;
        $handle->process('../public/');
        if ($handle->processed) {
            $handle->clean();
            $has_media = true;
        } else {
            echo 'error : ' . $handle->error;
            $has_media = false;
        }
    }

    $startsending = true;
    $sr = 1;
    $client = new Twilio\Rest\Client(ACCTOUNT_ID, ACCTOUNT_TOKEN);

    $txt = "************// SMS CAMPAING " . date('h:i:s a | d-M-Y') . " **************//";
    fwrite($txtfile, "\n" . $txt);

    $sms = $_POST['message'];
    fwrite($txtfile, "\nSMS: " . $sms);



    for ($i = 0; $i < count($_POST['phones']); $i ++) {

        $to = $_POST['phones'][$i];
        $status = '';
        try {

            if ($has_media) {

                $client->messages->create('+1' . $to, array(
                    'from' => ACCTOUNT_NUM,
                   
                    'body' => $sms
                ));
            } else {
                $client->messages->create('+1' . $to, array(
                    'from' => ACCTOUNT_NUM,
                    'body' => $sms
                ));
            }
            $status = '--SENT';
        } catch (Exception $e) {
            $status = "sent to $to Fail! " . $e->getCode() . ' : ' . $e->getMessage() . "<br>";
        }

        fwrite($txtfile, "\n" . $to . $status);
        $sr ++;
    }
    fclose($txtfile);
    echo '<script>location.href="?route=modules/marketing/sms_campaign&startsending=true";</script>';
}

?>
<div class="panel panel-info">
	<!-- Default panel contents -->
	<div class="panel-heading row">
		<h3>SMS Campaign</h3>
	</div>
	<div class="panel-body">
	<?php if($startsending==true):?>
		<div class="alert alert-success">
			<strong>Success!</strong> SMS putted in sending queque. Log book has
			been created
		</div>
	<?php endif; ?>
	
	<?php if($startsending==false):?>
		<form action="" id="frm" name="frm" method="POST"
			enctype="multipart/form-data">
			<div class="col-md-6 col-md-offset-3">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<div class="form-group">
							<select class="form-control select2" name="phones[]"
								multiple="multiple" required>
							<?php
    $contact = DB::query("SELECT DISTINCT(contact), fname, lname, customers_id FROM customers WHERE contact !='' AND newsletter = '1'");
    foreach ($contact as $row) {
        echo '<option value="' . $row['contact'] . '">' . $row['contact'] . '--' . $row['fname'] . ' ' . $row['lname'] .'---prev.orders: '. get_prev_orders($row['customers_id']) .'</option>';
    }
    ?>
							</select>
							<p class="text-muted">**Subscribed customers</p>
						</div>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<div class="form-group">
							<textarea class="form-control input-sm borderRadius "
								name="message" id="message" placeholder="Message"
								maxlength="140" rows="7" required></textarea>
						</div>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<div class="form-group">
							<input type="file" name="media" id="media">
						</div>
					</div>
					<input type="hidden" name="frmToken">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
						<span class="help-block"> &nbsp; </span>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6" align="right">
						<button class="btn btn-flat btn-block btn-lg btn-success"
							id="btnSubmit" name="btnSubmit" type="button">Start Sending SMS</button>
					</div>
				</div>
			</div>
		</form>
		<?php endif; ?>
	</div>
</div>
<script type="text/javascript">
$(function(){
	$("#btnSubmit").on("click", function(){
		var surr = confirm('Are you sure to fire this action? Action may not undo');
		if(surr==true){
			$("#frm").submit();
			$("#btnSubmit").prop('disabled', true);
		} else{
			event.preventDefault();
		}
	});
	
});
</script>
