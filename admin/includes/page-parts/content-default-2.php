<?php
// print_r($_SESSION);
?>
<div class="content-wrapper" style="min-height: 260px;">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
           <?php echo $_SESSION['company_name']; ?>
            <small> </small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Dashboard</li>
		</ol>
	</section>
	<!-- Main content -->
	<section class="content">
		<!-- Default box -->
		<div class="box">
			<div class="box-body">
				<div class="pull-left"></div>
				<div class="box-body">
					<div class="row">
						<div class="col-md-12">
							<div class="col-md-6">
								<!-- LINE CHART -->
								<div class="box box-info">
									<h3 class="box-title">Sale Report</h3>
									<div class="chart">
										<canvas id="saleChart" style="height: 200px; width: 510px;"
											width="510" height="200"></canvas>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<!-- LINE CHART -->
								<div class="box box-info">
									<h3 class="box-title">Pizza Sold</h3>
									<div class="chart">
										<canvas id="pizzaChart" style="height: 200px; width: 510px;"
											width="510" height="200"></canvas>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-12">
						 <?php

    $pos_orders = DB::queryFirstField("SELECT COUNT(*) FROM orders o WHERE o.`phoneid`='pos' AND o.`orders_status_id` = '1'");
    $web_orders = DB::queryFirstField("SELECT COUNT(*) FROM orders o WHERE o.`phoneid`='web' AND o.`orders_status_id` = '1'");
    $app_orders = DB::queryFirstField("SELECT COUNT(*) FROM orders o WHERE o.`phoneid` NOT IN('pos', 'web') AND o.`orders_status_id` = '1'");
    ?>
							<div class="col-lg-3 col-xs-6">
								<!-- small box -->
								<div
									class="small-box <?php if($pos_orders>0){ echo 'bg-green'; }else{ echo 'bg-aqua'; } ?> ">
									<div class="inner">
										<h3><?php echo $pos_orders; ?></h3>
										<p>POS Pending Orders</p>
									</div>
									<div class="icon">
										<i class="ion ion-bag"></i>
									</div>
									<a href="?route=modules/orders/view_orders&phoneid=pos"
										class="small-box-footer">View All <i
										class="fa fa-arrow-circle-right"></i></a>
								</div>
							</div>
							<div class="col-lg-3 col-xs-6">
								<!-- small box -->
								<div
									class="small-box <?php if($web_orders>0){ echo 'bg-green'; }else{ echo 'bg-aqua'; } ?>">
									<div class="inner">
										<h3><?php echo $web_orders; ?></h3>
										<p>Web Pending Orders</p>
									</div>
									<div class="icon">
										<i class="ion ion-bag"></i>
									</div>
									<a href="?route=modules/orders/view_orders&phoneid=web"
										class="small-box-footer">View All <i
										class="fa fa-arrow-circle-right"></i></a>
								</div>
							</div>
							<div class="col-lg-3 col-xs-6">
								<!-- small box -->
								<div
									class="small-box <?php if($app_orders>0){ echo 'bg-green'; }else{ echo 'bg-aqua'; } ?>">
									<div class="inner">
										<h3><?php echo $app_orders; ?></h3>
										<p>App Pending Orders</p>
									</div>
									<div class="icon">
										<i class="ion ion-bag"></i>
									</div>
									<a href="?route=modules/orders/view_orders&phoneid=app"
										class="small-box-footer">View All <i
										class="fa fa-arrow-circle-right"></i></a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- /.box-body -->
			<div class="box-footer"></div>
			<!-- /.box-footer-->
		</div>
		<!-- /.box -->
	</section>
	<!-- /.content -->
</div>
<?php
$sales_complete = array();
$pizza16 = array();
$pizza12 = array();
$days = array();
for ($i = 0; $i < 7; $i ++) {
    $days[] = date('D d-M', strtotime('-' . $i . ' days'));

    $d = DB::queryFirstField("SELECT SUM(o.`order_total`) AS total FROM orders o WHERE DATE(o.`created_on`) = DATE('" . date('Y-m-d', strtotime('-' . $i . ' days')) . "') ");
    $p16 = DB::queryFirstField("SELECT COUNT(*) AS total FROM orders o 
LEFT JOIN orders_products op
ON(o.`orders_id`=op.`orders_id`)
 WHERE ExtractNumber(op.`size`) = '16'
 AND
 DATE(o.`created_on`) = DATE('" . date('Y-m-d', strtotime('-' . $i . ' days')) . "')");
    $p12 = DB::queryFirstField("SELECT COUNT(*) AS total FROM orders o
LEFT JOIN orders_products op
ON(o.`orders_id`=op.`orders_id`)
 WHERE ExtractNumber(op.`size`) = '12'
 AND
 DATE(o.`created_on`) = DATE('" . date('Y-m-d', strtotime('-' . $i . ' days')) . "')");
    if ($p16 == '')
        $p16 = 0;
    if ($p12 == '')
        $p12 = 0;
    if ($d == '')
        $d = 0.00;
    $sales_complete[] = $d;
    $pizza16[] = $p16;
    $pizza12[] = $p12;
}
$days = array_reverse($days);
$sales_complete = array_reverse($sales_complete);
$sales_complete = implode(',', $sales_complete);
$pizza16 = array_reverse($pizza16);
$pizza16 = implode(',', $pizza16);
$pizza12 = array_reverse($pizza12);
$pizza12 = implode(',', $pizza12);
?>
<script
	src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0/dist/Chart.min.js"></script>
<script>
var ctx = document.getElementById('saleChart').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: [<?php echo "'" . implode("','", $days) . "'" ?>],
        datasets: [{
            label: 'Total Sale',
            data: [<?php echo $sales_complete; ?>],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1,
            scaleSteps : 1,
            options: {
                scales: {
                    yAxes: [{
                        stacked: true
                    }]
                }
            }
        }]
    }
});
   
</script>
<script>
var ctx = document.getElementById('pizzaChart').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: [<?php echo "'" . implode("','", $days) . "'" ?>],
        datasets: [{
            label: '16" Pizza Sold',
            data: [<?php echo $pizza16; ?>],
            backgroundColor: 'rgba(191, 159, 43, 0.1)',
            borderColor:'rgba(191, 159, 43, 1)',
            borderWidth: 1,
            options: {
                scales: {
                    yAxes: [{
                        stacked: true
                    }]
                }
            }
        },

{
            label: '12" Pizza Sold',
            data: [<?php echo $pizza12; ?>],
            backgroundColor: 'rgba(68, 191, 43, 0.1)',
            borderColor: 'rgba(68, 191, 43, 1)',
            borderWidth: 1,
            options: {
                scales: {
                    yAxes: [{
                        stacked: true
                    }]
                }
            }
        }

        ]
    }
});
   
</script>