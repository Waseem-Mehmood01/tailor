<?php
if(isset($_GET['del'])){
	
	if(isset($_GET['id'])){
		
		$id=$_GET['id'];
		$sql = "DELETE FROM extra_attributes WHERE extra_attributes_id=".(int)$id;
		
		$acc =  DB::query($sql);
		
		echo '<script type="text/javascript">
		<!--
		window.location = "?route=modules/shop/extra_attributes"
		//-->
		</script>';

	}

}

if(isset($_POST['btnEdit'])){
    $extra_attributes_id = isset($_POST['extra_attributes_id'])?(int)$_POST['extra_attributes_id']:'';
    @extract($_POST);
    DB::insertUpdate("extra_attributes", array(
      'extra_attributes_id'   => $extra_attributes_id,
      'attribute_categories_id' => $cid,
      'name'    => $name,
      'description' => $description,
      'cost'  => $cost
    ));
    
    
    if($extra_attributes_id==''){
        $extra_attributes_id = DB::insertId();
    }
    
    $handle = new Upload($_FILES['img']);
    if ($handle->uploaded) {
        $img_name = 'extra'.$extra_attributes_id;
        $type = substr($_FILES['img']['type'],6,10);
        $handle->image_resize = true;
        $handle->image_ratio = true;
        $handle->image_x = 350;
        if($type=='jpeg'){ $type='jpg'; }
        DB::update('extra_attributes',array('img'=> $img_name.'.'.$type), 'extra_attributes_id=%s', $extra_attributes_id);
        $handle->file_new_name_body   = $img_name;
        $handle->process('../images/extras/');
        if ($handle->processed) {
            // echo 'image resized';
            $handle->clean();
        } else {
            echo 'error : ' . $handle->error;
        }
    }
    
    echo '<script type="text/javascript">
		<!--
alert("Success");
		window.location = "?route=modules/shop/extra_attributes"
		//-->
		</script>';
}

?>
<div class="panel panel-info">
  <!-- Default panel contents -->
  <div class="panel-heading"><h3>Manage Extras<a href="#" data-toggle="modal" data-target="#editModal" class="pull-right btn btn-sm btn-primary"> <span class="glyphicon glyphicon-plus"></span> &nbsp;Add New Extra</a> </h3> </div>
  <div class="panel-body" style="font-size: 18px;">
  <table class="table table-striped table-bordered">
    <thead>
      <tr>
      <th>Attribute Category</th>
      <th>Extra Name</th>
      <th>Description</th>
      <th>Cost</th>
      <th>Img</th>
      <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $extras  = DB::query("select * from extra_attributes");
      foreach($extras as $row){
      ?>
      <tr>
        <td><?php
          $cate = DB::queryFirstField("select name from attribute_categories where attribute_categories_id='".$row['attribute_categories_id']."'");
         echo $cate; ?></td>
        <td><?php echo $row['name']; ?></td>
        <td><?php echo $row['description']; ?></td>
        <td><?php echo $row['cost']; ?></td>
        <td><img src="../images/extras/<?php echo $row['img']; ?>" style="max-width: 150px" /></td>
        <td><a href="#" data-cid="<?php echo $row['attribute_categories_id']; ?>" data-cost="<?php echo $row['cost']; ?>" data-description="<?php echo $row['description']; ?>" data-name="<?php echo $row['name']; ?>" data-id="<?php echo $row['extra_attributes_id']; ?>" title="Edit" data-toggle="modal" data-target="#editModal" class="btn btn-sm btn-info edit">Edit</a>&nbsp;<a onclick="return confirm('Are you sure to want delete this?');" href="?route=modules/shop/extra_attributes&del=yes&id=<?php echo $row['extra_attributes_id']; ?>" class="btn btn-sm btn-danger">Delete</a></td>
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
			var cid = $(this).data('cid');
			var name = $(this).data('name');
		var cost = $(this).data('cost');
			$("#cid").val(cid);
      $("#extra_attributes_id").val(id);
			$("#name").val(name);
			$("#description").val($(this).data('description'));
			$("#cost").val(cost);
			 $("select#cid option").each(function(){
			        if($(this).val()==cid){ // EDITED THIS LINE
			            $(this).attr("selected","selected");    
			        }
			    });

			});
	});
</script>
<div id="editModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit Extra</h4>
      </div>
      
      <form action="" method="POST" class="form-horizontal" enctype="multipart/form-data">
          
      <div class="modal-body">
        <input type="hidden" id="extra_attributes_id" name="extra_attributes_id" value="">
        <div class="form-group">
            <label class="control-label col-sm-4" for="cID">Attribute Category</label>
            <div class="col-sm-8">
              <select id="cid" name="cid" class="form-control" required="required">
              
              <?php 
                    $categ = DB::query("select * from attribute_categories");
                    foreach($categ as $cat){
                      echo '<option value="'.$cat['attribute_categories_id'].'">'.$cat['name'].'</option>';
                    }
        	   ?>
        		</select>
            </div>
          </div>
         
           <div class="form-group">
            <label class="control-label col-sm-4" for="active">Name</label>
            <div class="col-sm-8">
              <input class="form-control" type="text" name="name" id="name" value="">
          </div>
        
        
      </div>
      
      <div class="form-group">
            <label class="control-label col-sm-4">Description</label>
            <div class="col-sm-8">
              <input class="form-control" type="text" name="description" id="description" value="">
          </div>
        
        
      </div>
      
      <div class="form-group">
            <label class="control-label col-sm-4">Cost</label>
            <div class="col-sm-8">
              <input class="form-control" type="text" name="cost" id="cost" value="">
          </div>
      </div>
      
      <div class="form-group">
            <label class="control-label col-sm-4">Image</label>
            <div class="col-sm-8">
              <input class="form-control" type="file" name="img" id="img" value="">
              <p class="text-muted">*Leave blank for no image change</p>
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
