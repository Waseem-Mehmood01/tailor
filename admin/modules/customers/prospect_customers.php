
<div class="panel panel-info">
  <!-- Default panel contents -->
  <div class="panel-heading row"><h3>Prospect Customers</h3></div>
  <div class="panel-body">
 	<div id="calendar"></div>

 </div>
</div>
<script>
  $(function () {

    var date = new Date()
    var d    = date.getDate(),
        m    = date.getMonth(),
        y    = date.getFullYear()
    $('#calendar').fullCalendar({
     
       events    : [
        {
          title          : 'Customers',
          start          : new Date(y, m, 1),
          backgroundColor: '#f56954', //red
          borderColor    : '#f56954' //red
        }
      ]
   

        })

  })
</script>