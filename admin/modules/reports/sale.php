 <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
          		Reports
            <small>Sale Report</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?php echo SITE_ROOT; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Sale Report</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
			
 <!-- Default box -->

          <div class="box">
            <div class="box-header with-border">
              
            </div>
	
                        <div class="col-md-12">
                            <div class="col-md-4 pull-right">   
                                           
                            </div>
                            
                        </div>
            <div class="box-body"> 
					<div class="pull-left">
									
					</div>

			<div class="box-body">
			<div class="row">
    			<div class="col-md-6">
              <!-- LINE CHART -->
              <div class="box box-info">
                  <div class="chart">
                    <canvas id="lineChart" style="height: 250px; width: 510px;" width="510" height="250"></canvas>
                  </div>
                </div>
                </div>
                </div>
            </div>
            </div><!-- /.box-body -->
            <div class="box-footer">
             
            </div>
          </div><!-- /.box -->
		</section> 
		
		
		
		<script>
  $(function () {
    /* ChartJS
     * -------
     * Here we will create a few charts using ChartJS
     */
     var areaChartData = {
    	      labels  : ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
    	      datasets: [
    	        {
    	          label               : 'Electronics',
    	          fillColor           : 'rgba(210, 214, 222, 1)',
    	          strokeColor         : 'rgba(210, 214, 222, 1)',
    	          pointColor          : 'rgba(210, 214, 222, 1)',
    	          pointStrokeColor    : '#c1c7d1',
    	          pointHighlightFill  : '#fff',
    	          pointHighlightStroke: 'rgba(220,220,220,1)',
    	          data                : [65, 59, 80, 81, 56, 55, 40]
    	        }
    	      ]
    	    }

    	    var areaChartOptions = {
    	      //Boolean - If we should show the scale at all
    	      showScale               : true,
    	      //Boolean - Whether grid lines are shown across the chart
    	      scaleShowGridLines      : false,
    	      //String - Colour of the grid lines
    	      scaleGridLineColor      : 'rgba(0, 0, 255, 1)',
    	      //Number - Width of the grid lines
    	      scaleGridLineWidth      : 4,
    	      //Boolean - Whether to show horizontal lines (except X axis)
    	      scaleShowHorizontalLines: true,
    	      //Boolean - Whether to show vertical lines (except Y axis)
    	      scaleShowVerticalLines  : true,
    	      //Boolean - Whether the line is curved between points
    	      bezierCurve             : true,
    	      //Number - Tension of the bezier curve between points
    	      bezierCurveTension      : 0.3,
    	      //Boolean - Whether to show a dot for each point
    	      pointDot                : false,
    	      //Number - Radius of each point dot in pixels
    	      pointDotRadius          : 4,
    	      //Number - Pixel width of point dot stroke
    	      pointDotStrokeWidth     : 6,
    	      //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
    	      pointHitDetectionRadius : 20,
    	      //Boolean - Whether to show a stroke for datasets
    	      datasetStroke           : true,
    	      //Number - Pixel width of dataset stroke
    	      datasetStrokeWidth      : 6,
    	      //Boolean - Whether to fill the dataset with a color
    	      datasetFill             : true,
    	      //String - A legend template
    	      legendTemplate          : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].lineColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>',
    	      //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
    	      maintainAspectRatio     : true,
    	      //Boolean - whether to make the chart responsive to window resizing
    	      responsive              : true
    	    }


    //-------------
    //- LINE CHART -
    //--------------
    var lineChartCanvas          = $('#lineChart').get(0).getContext('2d')
    var lineChart                = new Chart(lineChartCanvas)
    var lineChartOptions         = areaChartOptions
    lineChartOptions.datasetFill = false
    lineChart.Line(areaChartData, lineChartOptions)


  });
</script>