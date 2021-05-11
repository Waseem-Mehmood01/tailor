<?php
if (isset($_GET["page"])) {
    $page = (int) $_GET["page"];
} else {
    $page = 1;
}

$setLimit = 15;

$pageLimit = ($page * $setLimit) - $setLimit;


$user_id = isset($_POST['user_id'])?(int)$_POST['user_id']:'';


$sql = "SELECT * FROM login_attempts WHERE 1=1 ";


if($user_id<>''){
    $sql .= " AND user_id = '".$user_id."' ";
}

$sql .= ' ORDER BY login_on DESC ';

$sql2 = $sql;
$sql .= ' LIMIT ' . $pageLimit . ', ' . $setLimit;

?>

<?php
// Draft Expense Voucher
$tbl = new HTML_Table('', 'table table-hover table-striped table-bordered');
$tbl->addTSection('thead');
$tbl->addRow();
$tbl->addCell('User', '', 'header');
$tbl->addCell('Login On', '', 'header');
$tbl->addCell('Logout On', '', 'header');
$tbl->addCell('Duration', '', 'header');

$tbl->addTSection('tbody');

$res = DB::query($sql);

foreach ($res as $row) {

    $tbl->addRow();
    $tbl->addCell($row['user_name']);
    $tbl->addCell($row['login_on']);
    $tbl->addCell($row['logout_on']);
    if($row['logout_on']<>''){
        $duration = calculateDuration($row['logout_on'], $row['login_on']);
    } else {
        $duration = '';
    }
    $tbl->addCell($duration);
}

?>
<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		Users <small>Login Book</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="<?php echo SITE_ROOT; ?>"><i class="fa fa-dashboard"></i>
				Home</a></li>
		<li class="active">Detail Logins</li>
	</ol>
</section>
<!-- Main content -->
<section class="content">
	<!-- Default box -->
	<div class="box">
		<div class="box-header with-border">
			<a href="?route=modules/system/login_log"><h3 class="box-title">Login
					Log</h3></a>
		</div>
		<div class="box-body">
			<div class="col-md-4 pull-right">
				<form method="POST" class="form-inline" action="">
					<div class="input-group">
						<span class="input-group-addon"> <select name="user_id">
							<option value="" <?php if($user_id=='') echo 'SELECTED'; ?>>-All-</option>
								<?php
								$users = DB::query("select user_id,user_name from sa_test_users");
								foreach ($users as $user){
								    echo '<option value="'.$user['user_id'].'"';
								    if($user_id==$user['user_id']) echo ' SELECTED';
								    echo '>'.$user['user_name'].'</option>';
								}
								?>
						</select></span> <span class="input-group-btn">
							<button type="submit" id="searchItem" name="searchItem"
								class="btn btn-default" type="button">
								<i class="fa fa-search"></i>
							</button>
						</span>
					</div>
				</form>
			</div>
							
				<?php  echo $tbl->display(); ?>
			
				<?php
    $page_url = "?route=modules/system/login_log&";
    echo displayPaginationBelow($setLimit, $page, $sql2, $page_url);
    ?>


            </div>
		<!-- /.box-body -->
		<div class="box-footer"></div>
	</div>
	<!-- /.box -->
</section>

