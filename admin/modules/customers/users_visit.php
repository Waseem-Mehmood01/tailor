
<?php 

if(isset($_POST['selector'])){
    for($i=0; $i<count($_POST['selector']); $i++){
        $arr = explode(";", $_POST['selector'][$i]);
        $time = $arr[0];
        $email = $arr[1];
        DB::query("DELETE FROM age_verification_users WHERE email='".$email."' AND date_time = '".$time."'");
        echo '<script>alert("Deleted!"); location.href="?route=modules/customers/users_visit";</script>';
    }
}
?>


<div class="panel panel-info">
  <!-- Default panel contents -->
  <div class="panel-heading row"><h3>Customers Visits</h3><a onclick="retrun confirm('Are you sure want to clear all users visit log?');" href="#" id="btnAction" class=" pull-left btn btn-sm btn-warning"> <span class="glyphicon glyphicon-trash"></span> &nbsp;Delete Selected</a> </div>
  <div class="panel-body">

<table class="table table-striped table-bordered">
<thead>
	<tr>
	<th><input type="checkbox" id="selectall"></th>
	<th>Age Verification Type</th>
	<th>ID</th>
	<th>Time Stamp</th>
	</tr>
</thead>
<tbody>
<form method="POST" name="frmAction" id="frmAction" action="">
<?php
$sql = "SELECT * FROM age_verification_users ORDER by date_time DESC LIMIT 0, 100";
$get = DB::query($sql);
foreach($get as $cat) { 
echo "<tr>";
echo "<td><input type='checkbox' name='selector[]' class='selector' value='".$cat['date_time'].";".$cat['email']."'></td>";
echo "<td>".$cat['type']."</td>";
echo "<td>".$cat['email']."</td>";
echo "<td>".$cat['date_time']."</td>";
echo "</tr>";		   
}
			  // echo $tbl->display();
?>
</tbody>
</form>
</table>
 </div>
</div>

<script>
$(function(){
	$("#selectall").on("click", function(){
		if ($(this).is(':checked')) {
    		$('.selector').each(function(){
    			$(this).prop("checked", true);
    		});
		} else {
			$('.selector').each(function(){
    			$(this).prop("checked", false);
    		});
		}
	});


	$("#btnAction").on('click', function(){

		$("#frmAction").submit();
	});
	
});
</script>
