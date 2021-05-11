<div class="panel panel-info">
	<!-- Default panel contents -->
	<div class="panel-heading row">
		<h3>
			Drivers Live GPS <small> **Data vary on terms & conditions</small>
		</h3>
	</div>
	<div class="panel-body">
		<div id="map" style="width: 100%; height: 400px;"></div>
	</div>
</div>
<?php
$data = DB::query("SELECT * FROM drivers");

?>
<script
	src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBRDbIfXDmKhEfZ5CB1-FRe8RBAgrsjR6Y"></script>
<script type="text/javascript">

locations = [];

<?php
$i=1;
foreach ($data as $row) {
    ?>

    locations.push(['Driver: <?php echo $row['d_fullname']; ?> ', <?php echo $row['live_lat'] ?>, <?php echo $row['live_long'] ?>, <?php echo $i; ?>]);
    
<?php   $i++; } ?>

var map = new google.maps.Map(document.getElementById('map'), {
  zoom: 12,
  center: new google.maps.LatLng(26.490771, -81.94285),
  mapTypeId: google.maps.MapTypeId.ROADMAP,
  travelMode: google.maps.TravelMode.DRIVING
});

var infowindow = new google.maps.InfoWindow();

var marker, i;

for (i = 0; i < locations.length; i++) {  
  marker = new google.maps.Marker({
    position: new google.maps.LatLng(locations[i][1], locations[i][2]),
    map: map
  });

  google.maps.event.addListener(marker, 'click', (function(marker, i) {
    return function() {
      infowindow.setContent(locations[i][0]);
      infowindow.open(map, marker);
    }
  })(marker, i));
}
</script>