<?php
$convesation_id = isset($_GET['convid']) ? (int) $_GET['convid'] : DB::queryFirstField("SELECT conversation_id FROM conversation ORDER BY updated_on DESC");

$default_conversation = DB::queryFirstRow("select * from conversation where conversation_id='" . $convesation_id . "'");

?>
<style>
img {
	max-width: 100%;
	display: none;
}

.loader {
	margin: 0 auto;
	display: block !important;
	margin-top: 10%;
}

.inbox_people {
	background: #f8f8f8 none repeat scroll 0 0;
	float: left;
	overflow: hidden;
	width: 40%;
	border-right: 1px solid #c4c4c4;
}

.inbox_msg {
	border: 1px solid #c4c4c4;
	clear: both;
	overflow: hidden;
}

.top_spac {
	margin: 20px 0 0;
}

.recent_heading {
	float: left;
	width: 40%;
}

.srch_bar {
	display: inline-block;
	text-align: right;
	width: 60%;
	padding:
}

.headind_srch {
	padding: 10px 29px 10px 20px;
	overflow: hidden;
	border-bottom: 1px solid #c4c4c4;
}

.recent_heading h4 {
	color: #05728f;
	font-size: 21px;
	margin: auto;
}

.srch_bar input {
	border: 1px solid #cdcdcd;
	border-width: 0 0 1px 0;
	width: 80%;
	padding: 2px 0 4px 6px;
	background: none;
}

.srch_bar .input-group-addon button {
	background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
	border: medium none;
	padding: 0;
	color: #707070;
	font-size: 18px;
}

.srch_bar .input-group-addon {
	margin: 0 0 0 -27px;
}

.chat_ib h5 {
	font-size: 15px;
	color: #464646;
	margin: 0 0 8px 0;
}

.chat_ib h5 span {
	font-size: 13px;
	float: right;
}

.chat_ib p {
	font-size: 14px;
	color: #989898;
	margin: auto
}

.chat_img {
	float: left;
	width: 11%;
}

.chat_ib {
	float: left;
	padding: 0 0 0 15px;
	width: 88%;
}

.chat_people {
	overflow: hidden;
	clear: both;
}

.chat_list {
	border-bottom: 1px solid #c4c4c4;
	margin: 0;
	padding: 18px 16px 10px;
}

.inbox_chat {
	height: 550px;
	overflow-y: scroll;
}

.active_chat {
	background: #ebebeb;
}

.incoming_msg_img {
	display: inline-block;
	width: 6%;
}

.received_msg {
	display: inline-block;
	padding: 0 0 0 10px;
	vertical-align: top;
	width: 92%;
}

.received_withd_msg p {
	background: #ebebeb none repeat scroll 0 0;
	border-radius: 3px;
	color: #646464;
	font-size: 14px;
	margin: 0;
	padding: 5px 10px 5px 12px;
	width: 100%;
}

.time_date {
	color: #747474;
	display: block;
	font-size: 12px;
	margin: 8px 0 0;
}

.received_withd_msg {
	width: 57%;
	margin-top: 20px;
}

.mesgs {
	float: left;
	padding: 30px 15px 0 25px;
	width: 60%;
}

.sent_msg p {
	background: #05728f none repeat scroll 0 0;
	border-radius: 3px;
	font-size: 14px;
	margin: 0;
	color: #fff;
	padding: 5px 10px 5px 12px;
	width: 100%;
}

.write-msg {
	background-color: #a1cce147;
	padding: 12px;
	margin: -20px;
}

.outgoing_msg {
	overflow: hidden;
	margin: 26px 0 26px;
}

.sent_msg {
	float: right;
	width: 46%;
}

.input_msg_write input {
	background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
	border: medium none;
	color: #4c4c4c;
	font-size: 15px;
	min-height: 48px;
	width: 100%;
}

.type_msg {
	border-bottom: 1px solid #c4c4c4;
	position: relative;
	margin-bottom: 5px;
}

.msg_send_btn {
	background: #05728f none repeat scroll 0 0;
	border: medium none;
	border-radius: 50%;
	color: #fff;
	cursor: pointer;
	font-size: 17px;
	height: 33px;
	position: absolute;
	right: 0;
	top: 11px;
	width: 33px;
}

.messaging {
	padding: 0 0 50px 0;
}

.msg_history {
	height: 516px;
	overflow-y: auto;
}
</style>
<div class="panel panel-info">
	<!-- Default panel contents -->
	<div class="panel-heading row">
		<div class="col-md-9">
			<h3>Messaging</h3>
		</div>
		<div class="col-md-3">
			<button id="newSMS" title="New SMS"
				style="border-radius: 50%; box-shadow: 5px 6px 6px #888888;"
				class="btn btn-success btn-lg">
				<i class="fa fa-envelope" aria-hidden="true"></i>
			</button>
		</div>
	</div>
	<div class="panel-body">
		<div class="messaging">
			<div class="inbox_msg">
				<div class="inbox_people">
					<div class="headind_srch">
						<div class="recent_heading">
							<h4>Recent</h4>
						</div>
						<!-- <div class="srch_bar">
							<div class="stylish-input-group">
								<input type="text" class="search-bar" placeholder="Search"> <span
									class="input-group-addon">
									<button type="button">
										<i class="fa fa-search" aria-hidden="true"></i>
									</button>
								</span>
							</div>
						</div> -->
					</div>
					<div class="inbox_chat">
					<?php
    $convers = DB::query("SELECT * FROM conversation ORDER BY updated_on DESC");
    foreach ($convers as $conv) {
        ?>
						<div onclick="loadSMS(<?php echo $conv['conversation_id']; ?>);"
							id="chat_list<?php echo $conv['conversation_id']; ?>"
							class="chat_list <?php if($convesation_id==$conv['conversation_id']) echo 'active_chat';  ?> ">
							<div class="chat_people">
								<div class="chat_img">
									<img src=" " alt="waseem">
								</div>
								<div class="chat_ib">
									<h5>
										<?php
        $chat_with = DB::queryFirstRow("SELECT cs.`to`,cs.`from` FROM conversation_sms cs WHERE cs.`conversation_id` = '" . $conv['conversation_id'] . "'");
        $ch_from = $chat_with['from'];
        $ch_to = $chat_with['to'];
        if ($ch_from == DEFAULT_ACCTOUNT_NUM) {
            $chat_with = $ch_to;
        } else {
            $chat_with = $ch_from;
        }
        echo get_customer_name_by_phone(substr($chat_with, 2, 16)) . ' <small>[' . $chat_with . ']</small>';
        ?> <span class="chat_date"><?php echo formate_date_days($conv['updated_on']); ?></span>
									</h5>
									<p><?php echo get_conversation_text($conv['conversation_sms_id']); ?></p>
								</div>
							</div>
						</div>
						<?php } ?>
						
						
						
					</div>
				</div>
				<div class="mesgs">
					<form id="frmMsgSend" method="POST" action="">
						<div class="write-msg">
							<div class="form-group" id="divSendNew"
								style="display: none; width: 100%;">
								<select style="width: 100%;" class="form-control select2"
									name="phone" placeholder="Recipient">
							<?php
    $contact = DB::query("SELECT DISTINCT(contact), fname, lname, customers_id FROM customers WHERE contact !='' AND newsletter = '1'");
    foreach ($contact as $row) {
        echo '<option value="' . $row['contact'] . '">' . $row['contact'] . '--' . $row['fname'] . ' ' . $row['lname'] . '---prev.orders: ' . get_prev_orders($row['customers_id']) . '</option>';
    }
    ?>
							</select>
							</div>
							<div class="form-group">
								<textarea name="text" class="write_msg form-control"
									placeholder="Type message" required></textarea>
							</div>
							<input type="hidden" name="req" id="req" value="sendsingle">
							<button id="msg_send_btn" class="btn btn-success" type="submit">
								Send <i class="fa fa-paper-plane-o" aria-hidden="true"></i>
							</button>
							<input type="hidden" id="conversation_id" name="conversation_id"
								value="<?php echo $convesation_id; ?>">
						</div>
					</form>
					<div class="msg_history">
					<?php
    $msgs = DB::query("SELECT * FROM conversation_sms WHERE conversation_id = '" . $convesation_id . "'  ORDER BY created_on DESC");
    foreach ($msgs as $msg) {
        $who = '';
        ?>
						<div
							class="<?php if($msg['from']==DEFAULT_ACCTOUNT_NUM){ echo ' outgoing_msg ';  }else{ echo ' incoming_msg ';  } ?>">
							<div class="">
								<img src=" " alt="waseem">
							</div>
							<div
								class="<?php if($msg['from']==DEFAULT_ACCTOUNT_NUM){  $who='You'; echo ' sent_msg '; }else{ $who = get_customer_name_by_phone(substr($msg['from'], 2, 16)); echo ' received_withd_msg ';} ?>">
								<span class="time_date"> <?php echo $who; ?><small> [ <?php echo $msg['from']; ?> ]</small></span>
								<p><?php echo $msg['text']; ?></p>
								<span class="time_date"> <?php echo formate_date_days($msg['created_on']); ?></span>
							</div>
						</div>
						
						<?php } ?>
						
					
					
					
				</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$(function(){
	var coID = <?php if($convesation_id==''){ echo 0; }else{ echo $convesation_id; } ?>;
	$("#frmMsgSend").on("submit", function(){
		event.preventDefault();
		$.ajax({
		    method : 'POST',
		    url : 'ajax_helpers/ajax_sms.php',
		    data : $(this).serialize(),
		    success: function(e){
		    	$(".write_msg").val('');
		    	loadConvers();
		    	loadSMS(e);
		    	coID = e;
			    }
		});
	});


	$("#newSMS").click(function(){
		$("#divSendNew").fadeIn();
		$("#req").val('sendnewsms');
		$(".msg_history").html('');
		$("#conversation_id").val('');
	});




	
});







function loadSMS(conID){
	$.ajax({
	    method : 'GET',
	    url : 'ajax_helpers/ajax_sms.php',
	    data : {req: 'loadsms', convesation_id:conID },
	    beforeSend: function(){
			$(".msg_history").html('<img class="loader" src="images/loader.gif">');
		   },
	    success: function(e){
		    $(".msg_history").html(e);
		    $("#divSendNew").hide();
		    $("#req").val('sendsingle');
		    $(".chat_list").removeClass("active_chat");
		    $("#chat_list"+conID).addClass("active_chat");
		    }
	});

	$("#conversation_id").val(conID);


}


function loadConvers(){
	$.ajax({
	    method : 'GET',
	    url : 'ajax_helpers/ajax_sms.php',
	    data : {req: 'loadconversion'},
	    beforeSend: function(){
			$(".inbox_chat").html('<img class="loader" src="images/loader.gif">');
		   },
	    success: function(e){
		    $(".inbox_chat").html(e);
		    
		    }
	});
}
</script>
