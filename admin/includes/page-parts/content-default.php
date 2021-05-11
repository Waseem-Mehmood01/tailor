<?php
$filter = isset($_GET['filter']) ? (int) $_GET['filter'] : 7;

$sales_complete = array();
$avgSale = array();
$days = array();
$total_Sale = 0.00;
$total_orders = 0;
$items = array();
for ($i = 0; $i < $filter; $i ++) {
    $days[] = date('D d-M', strtotime('-' . $i . ' days'));

    $d = DB::queryFirstField("SELECT SUM(o.`order_total`) AS total FROM orders o WHERE DATE(o.`created_on`) = DATE('" . date('Y-m-d', strtotime('-' . $i . ' days')) . "') ");
    $num_trans = DB::queryFirstField("SELECT count(*) AS total FROM orders o WHERE DATE(o.`created_on`) = DATE('" . date('Y-m-d', strtotime('-' . $i . ' days')) . "') ");
    $total_orders += $num_trans;

    $hot_items = DB::query("SELECT
  p.`products_id`, p.`name`, SUM(p.`qty`) AS qty 
FROM
  orders_products p
  LEFT JOIN orders o
  ON(o.`orders_id`=p.`orders_id`)
  WHERE DATE(o.`created_on`)= DATE('" . date('Y-m-d', strtotime('-' . $i . ' days')) . "')
GROUP BY
  p.`products_id`
ORDER BY
  SUM(p.`qty`) DESC");

    foreach ($hot_items as $itm) {

        $da = array(
            'Product_id' => $itm['products_id'],
            'Name' => $itm['name'],
            'Qty' => $itm['qty']
        );
        array_push($items, $da);
    }

    if ($d == '')
        $d = 0.00;

    if ($d > 0) {
        $avg = round(($d / $num_trans), 2);
    } else {
        $avg = 0.00;
    }

    $total_Sale += $d;
    $avgSale[] = $avg;
    $sales_complete[] = $d;
}
$days = array_reverse($days);
$sales_complete = array_reverse($sales_complete);
$sales_complete = implode(',', $sales_complete);
$avgSale = array_reverse($avgSale);
$avgSale = implode(',', $avgSale);

$pos_orders = DB::queryFirstField("SELECT COUNT(*) FROM orders o WHERE o.`phoneid`='pos' AND o.`orders_status_id` = '1'");
$web_orders = DB::queryFirstField("SELECT COUNT(*) FROM orders o WHERE o.`phoneid`='web' AND o.`orders_status_id` = '1'");

$sold_qty = array();
foreach ($items as $key => $row) {
    $sold_qty[$key] = $row['Qty'];
}
array_multisort($sold_qty, SORT_DESC, $items);

$result = array();
foreach ($items as $k => $v) {
    $Product_id = $v['Product_id'];
    $result[$Product_id][] = $v['Qty'];
}

$hot_items = array();

foreach ($result as $key => $value) {
    $hot_items[] = array(
        'Product_id' => $key,
        'Qty' => array_sum($value)
    );
}

?>
<!-- Content Header (Page header) -->
<section class="content-header">
	<h1><?php echo $_SESSION['company_name']; ?> | Home
           <?php // echo $_SESSION['company_name']; ?>
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
						<div
							onclick="location.href='?route=modules/orders/view_orders&phoneid=pos';"
							class="col-md-4 col-sm-6 col-xs-12">
							<div class="info-box">
								<span
									class="info-box-icon <?php if($pos_orders>0){ echo 'bg-green'; }else{ echo 'bg-aqua'; } ?>"><i
									class="ion ion-ios-cart-outline"></i></span>
								<div class="info-box-content">
									<span class="info-box-text">POS Pending Orders</span> <span
										class="info-box-number"><?php echo $pos_orders; ?></span>
								</div>
							</div>
						</div>
						<div
							onclick="location.href='?route=modules/orders/view_orders&phoneid=web';"
							class="col-md-4 col-sm-6 col-xs-12">
							<div class="info-box">
								<span
									class="info-box-icon <?php if($web_orders>0){ echo 'bg-green'; }else{ echo 'bg-aqua'; } ?>"><i
									class="ion ion-ios-cart"></i></span>
								<div class="info-box-content">
									<span class="info-box-text">Web Pending Orders</span> <span
										class="info-box-number"><?php echo $web_orders; ?></span>
								</div>
							</div>
						</div>
						
						<div class="col-md-4 col-sm-6 col-xs-12">
							<div class="info-box">
								<span class="info-box-icon"><i class="ion ion-pricetags"></i></span>
								<div class="info-box-content">
									<span class="info-box-text">Total orders of week</span> <span
										class="info-box-number"><?php echo $total_orders; ?></span>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-12">
						<div class="col-md-9">
							<ul class="nav nav-tabs" role="tablist">
								<li class="nav-item active"><a class="nav-link active"
									data-toggle="tab" href="#sale_report" role="tab"
									aria-selected="true">Sale Report</a></li>
								<li class="nav-item"><a class="nav-link" data-toggle="tab"
									href="#avg_sale" role="tab" aria-selected="true">Avg. Sale</a></li>
								<li class="pull-right">
									<form method="GET" action="" id="frmFilter" name="frmFilter">
										<select name="filter" id="filter" class="form-control">
											<option value="7" <?php if($filter==7) echo 'SELECTED'; ?>>7
												Days Data</option>
											<option value="15" <?php if($filter==15) echo 'SELECTED'; ?>>15
												Days Data</option>
											<option value="30" <?php if($filter==30) echo 'SELECTED'; ?>>30
												Days Data</option>
										</select>
									</form>
								</li>
							</ul>
							<div class="tab-content">
								<div class="tab-pane active in" id="sale_report" role="tabpanel">
									<!-- LINE CHART -->
									<div class="box box-info">
										<h3 class="box-title">Sale Report</h3>
										<div class="chart">
											<canvas id="saleChart" style="height: 200px; width: 510px;"
												width="510" height="200"></canvas>
										</div>
									</div>
								</div>
								<div class="tab-pane" id="avg_sale" role="tabpanel">
									<!-- LINE CHART -->
									<div class="box box-info">
										<h3 class="box-title">Average sales by day</h3>
										<div class="chart">
											<canvas id="avgSaleChart"
												style="height: 200px; width: 510px;" width="510"
												height="200"></canvas>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="small-box"
								style="color: #FFF; background-color: #6d6a6a;">
								<div class="inner">
									<h3>$<?php echo $total_Sale; ?></h3>
									<p>Total sale of <?php echo $i; ?> days</p>
								</div>
								<div class="icon">
									<i class="ion ion-stats-bars"></i>
								</div>
							</div>
							<div class="small-box"
								style="color: #FFF; background-color: #6d6a6a;">
								<div class="inner">
									<h3>$<?php

        echo DB::queryFirstField("SELECT SUM(o.`order_total`) AS total FROM orders o WHERE MONTH(o.`created_on`) = '" . date('m') . "'");
        ?></h3>
									<p>Total sale of <?php echo date('M'); ?></p>
								</div>
								<div class="icon">
									<i class="ion ion-stats-bars"></i>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-12">
						<h2>Top selling items of this week</h2>
						<div class="col-md-6">
							<table class="table">
								<thead>
									<tr>
										<th></th>
										<th>Menu item</th>
										<th>Sale</th>
									</tr>
								</thead>
								<tbody>
									<?php
        $len = count($hot_items);
        $c = 1;
        $firsthalf = array_slice($hot_items, 0, $len / 2);
        $secondhalf = array_slice($hot_items, $len / 2);
        for ($i = 0; $i < count($firsthalf); $i ++) {

            ?>
									<tr>
										<td><?php echo $c; ?></td>
										<td><strong><?php echo get_product_name($firsthalf[$i]['Product_id']); ?></strong></td>
										<td><strong><?php echo $firsthalf[$i]['Qty']; ?></strong></td>
									</tr>
									<?php
            $c ++;
        }
        ?>
									</tbody>
							</table>
						</div>
						<div class="col-md-6">
							<table class="table">
								<thead>
									<tr>
										<th></th>
										<th>Menu item</th>
										<th>Sale</th>
									</tr>
								</thead>
								<tbody>
									<?php for ($i = 0; $i < count($firsthalf); $i ++) {  ?>
									<tr>
										<td><?php echo $c; ?></td>
										<td><strong><?php echo get_product_name($secondhalf[$i]['Product_id']); ?></strong></td>
										<td><strong><?php echo $secondhalf[$i]['Qty']; ?></strong></td>
									</tr>
									<?php
            $c ++;
        }
        ?>
									</tbody>
							</table>
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
<script>
$(function(){
	$("#filter").change(function(){ $("#frmFilter").submit(); });
});

</script>
<script
	src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0/dist/Chart.min.js"></script>
<script>
var ctx = document.getElementById('saleChart').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: [<?php echo "'" . implode("','", $days) . "'" ?>],
        lineTension: 0, 
        datasets: [{
            label: 'Total Sale',
            data: [<?php echo $sales_complete; ?>],
            fill: true,
            borderColor: "rgb(75, 192, 192)",
            lineTension: 0.1,
            borderWidth: 4,
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
var ctx2 = document.getElementById('avgSaleChart').getContext('2d');
var avgChart = new Chart(ctx2, {
    type: 'bar',
    data: {
        labels: [<?php echo "'" . implode("','", $days) . "'" ?>],
        datasets: [{
            label: 'Average Sale',
            data: [<?php echo $avgSale; ?>],
            backgroundColor: [
                'rgba(75, 93, 251, 0.8)'
            ],
            borderColor: [
                'rgba(75, 93, 251, 1)'
            ],
            borderWidth: 2,
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
        datasets: [
        	{
                label: '12" Pizza Sold',
                data: [<?php echo $pizza12; ?>],
                backgroundColor: 'rgba(56, 54, 201, 0.5)',
                borderColor:'rgba(56, 54, 201, 1)',
                borderWidth: 2,
                options: {
                    scales: {
                        yAxes: [{
                            stacked: true
                        }]
                    }
                }
            },
            {
                label: '14" Pizza Sold',
                data: [<?php echo $pizza14; ?>],
                backgroundColor: 'rgba(191, 159, 43, 0.5)',
                borderColor:'rgba(191, 159, 43, 1)',
                borderWidth: 2,
                options: {
                    scales: {
                        yAxes: [{
                            stacked: true
                        }]
                    }
                }
            },
            
            {
            label: '16" Pizza Sold',
            data: [<?php echo $pizza16; ?>],
            backgroundColor: 'rgba(68, 191, 43, 0.5)',
            borderColor: 'rgba(68, 191, 43, 1)',
            borderWidth: 2,
            options: {
                scales: {
                    yAxes: [{
                        stacked: true
                    }]
                }
            }
        },
        {
            label: '18" Pizza Sold',
            data: [<?php echo $pizza18; ?>],
            backgroundColor: 'rgba(201, 54, 174, 0.5)',
            borderColor:'rgba(201, 54, 174, 1)',
            borderWidth: 2,
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