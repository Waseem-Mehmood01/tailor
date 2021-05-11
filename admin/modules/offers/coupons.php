<?php
if(isset($_GET['active'])){
	
	if(isset($_GET['id'])){
		
		$id=$_GET['id'];
		$active = $_GET['active'];
		
		DB::update("coupons", array('active'=>$active), 'coupons_id=%s', $id);
		
		echo '<script type="text/javascript">
		<!--
		window.location = "?route=modules/offers/coupons"
		//-->
		</script>';

	}

}

if(isset($_POST['btnEdit'])){
    $coupons_id = isset($_POST['coupons_id'])?(int)$_POST['coupons_id']:'';
    @extract($_POST);
    DB::insertUpdate("coupons", array(
        'coupons_id'    => $coupons_id,
        'code'          => $code,
        'min_shoping'   => $min_shoping,
        'discount'      => preg_replace('/\D/', '', $discount),
        'expired_on'    => date("Y-m-d H:i:s", strtotime($expired_on)),
        'active'        => $active,
        
    ));
    
    
   
    
    echo '<script type="text/javascript">
		<!--
alert("Success");
		window.location = "?route=modules/offers/coupons"
		//-->
		</script>';
}

?>
<div class="panel panel-info">
  <!-- Default panel contents -->
  <div class="panel-heading"><h3>Manage Coupons<a href="#" data-toggle="modal" data-target="#editModal" class="pull-right btn btn-sm btn-primary"> <span class="glyphicon glyphicon-plus"></span> &nbsp;Add New Coupon</a> </h3> </div>
  <div class="panel-body" style="font-size: 18px;">
  <table class="table table-striped table-bordered">
    <thead>
      <tr>
      <th>Code</th>
      <th>Discount</th>
      <th>Minimum Shopping</th>
      <th>Created On</th>
      <th>Expiry</th>
      <th>&nbsp;</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $extras  = DB::query("select * from coupons order by coupons_id desc");
      foreach($extras as $row){
      ?>
      <tr>
        
        <td><?php echo $row['code']; ?></td>
        <td><?php echo $row['discount']; ?>%</td>
        <td><?php echo $row['min_shoping']; ?>$</td>
        <td><?php echo date("h:i A, d-m-Y", strtotime($row['created_on'])); ?></td>
        <td><?php echo date("h:i A, d-m-Y", strtotime($row['expired_on'])); ?></td>
        <?php 
        if($row['active']==1){
            $status = '<a onclick="return confirm(\'Are you sure want to in-active this coupon?\');" href="?route=modules/offers/coupons&active=0&id='.$row['coupons_id'].'" title="Put In-Active" class="btn btn-sm btn-success btn-flat">Active</a>';
        }else{
            $status = '<a onclick="return confirm(\'Are you sure want to active this coupon?\');" href="?route=modules/offers/coupons&active=1&id='.$row['coupons_id'].'" title="Put Active" class="btn btn-sm btn-danger btn-flat">In-Active</a>';
        }
        
        ?>
        <td><?php echo $status; ?>&nbsp;&nbsp;<a data-id="<?php echo $row['coupons_id']; ?>" data-shopping="<?php echo $row['min_shoping']; ?>" data-code="<?php echo $row['code']; ?>" data-discount="<?php echo $row['discount']; ?>" data-created="<?php echo $row['created_on']; ?>" data-expired="<?php echo $row['expired_on']; ?>" title="Edit" data-toggle="modal" data-target="#editModal" class="btn btn-sm btn-info edit">Edit</a>&nbsp;</td>
      </tr>
    <?php } ?>
    </tbody>
  </table>
 
 </div>
</div>

  
  
  <script>
	$(function(){
	
		
		$(".edit").on('click',function(e){
			var id = $(this).data('id');
			
      		$("#coupons_id").val(id);
			$("#code").val($(this).data('code'));
			$("#discount").val($(this).data('discount'));
			$("#min_shoping").val($(this).data('shopping'));
			$("#expired_on").val($(this).data('expired'));

			});
	});
</script>
<div id="editModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add/Edit Coupon</h4>
      </div>
      
      <form action="" method="POST" class="form-horizontal">
          
      <div class="modal-body">
        <input type="hidden" id="coupons_id" name="coupons_id" value="">
        
         
           <div class="form-group">
            <label class="control-label col-sm-4">Code</label>
            <div class="col-sm-8">
              <input class="form-control" type="text" name="code" id="code" value="">
          	</div>
         
      	</div>
      
      <div class="form-group">
            <label class="control-label col-sm-4">Discount</label>
            <div class="col-sm-4">
            	<div class="input-group">
                    <input class="form-control" type="text" name="discount" id="discount" value="">
                    
                       <span class="input-group-addon">%</span>
                    
                  </div>
              
          </div>
        
        
      </div>
      
      <div class="form-group">
            <label class="control-label col-sm-4">Min-Shopping</label>
            <div class="col-sm-4">
            <div class="input-group">
                    <input class="form-control" type="text" name="min_shoping" id="min_shoping" value="">
                    
                       <span class="input-group-addon">$</span>
                    
                  </div>
              
          </div>
      </div>
      <div class="form-group">
            <label class="control-label col-sm-4">Expiry</label>
            <div class="col-sm-8">
              <input class="form-control datetimepicker" type="text" name="expired_on" id="expired_on" value="">
          </div>
      </div>
     <div class="form-group">
            <label class="control-label col-sm-4">Status</label>
            <div class="col-sm-8">
              	<label class="radio-inline"><input type="radio" name="active" value="1" checked>Active</label>
				<label class="radio-inline"><input type="radio" name="active" value="0">In-Active</label>
          </div>
      </div>
      
     
      </div>
      <div class="modal-footer">
      <button type="submit" name="btnEdit" id="btnEdit" class="btn btn-success">Save</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      </form>
    </div>

  </div>
</div>
