<?php 

$where_clause = '';

$from_date = isset($_POST['from_date']) ? $_POST['from_date'] : '';

$to_date = isset($_POST['to_date']) ? $_POST['to_date'] : '';

if ($from_date != '') {
    $where_clause .= " AND DATE(time_stamp) >= DATE('" . $from_date . "') ";
}

if ($to_date != '') {
    $where_clause .= " AND DATE(time_stamp) <= DATE('" . $to_date . "') ";
}

?>
<div class="panel panel-info">
	<!-- Default panel contents -->
  <?php
$recor = DB::queryFirstField("SELECT COUNT(DISTINCT `to`) FROM sms_verification_log WHERE 1=1 ".$where_clause);
?>
  <div class="panel-heading row">
		<h3>
			App OTP SMS Log <small>[<?php echo $recor; ?> Unique Records ]</small>
		</h3>
	</div>
	<a class="btn pull-right btn-success d-inline" href="#"
		data-toggle="modal" data-target="#modalFilter"><i
		class="glyphicon glyphicon-filter"></i>&nbsp;Filter</a>
	<div class="panel-body">
		<table class="table table-striped data-table table-bordered">
			<thead>
				<tr>
					<th>Phone</th>
					<th>Code</th>
					<th>Time Stamp</th>
				</tr>
			</thead>
			<tbody>
<?php


$sql = "SELECT * FROM sms_verification_log WHERE 1=1 " . $where_clause . " ORDER BY time_stamp DESC";

$get = DB::query($sql);
foreach ($get as $cat) {
    echo "<tr>";
    echo "<td>" . $cat['to'] . "</td>";
    echo "<td>" . $cat['code'] . "</td>";
    echo "<td>" . date('h:i:s a | d-M-y', strtotime($cat['time_stamp'])) . "</td>";
    echo "</tr>";
}
// echo $tbl->display();
?>
</tbody>
		</table>
	</div>
</div>
<!-- Modal -->
<div class="modal fade" id="modalFilter" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Filter By</h4>
			</div>
			<form class="form-horizontal" name="frmFilter" id="frmFilter"
				action="" method="POST">
				<div class="modal-body">
					<div class="form-group">
						<label class="control-label col-sm-4">From:</label>
						<div class="col-sm-6">
							<input type="text" class="form-control date-picker"
								value="<?php echo @$from_date; ?>" name="from_date"
								id="from_date" placeholder="dd-mm-yyyy" autocomplete="off">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-4">To:</label>
						<div class="col-sm-6">
							<input type="text" class="form-control date-picker"
								value="<?php echo @$to_date; ?>" name="to_date" id="to_date"
								placeholder="dd-mm-yyyy" autocomplete="off">
						</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-success" name="btnFilter"
							id="btnFilter">Apply</button>
						<button type="button" class="btn btn-primary" name="btnReset"
							id="btnReset">Clear Filter</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">

$(function(){ $("#btnReset").on("click",function(){ $(".form-control").val("");});});

</script>