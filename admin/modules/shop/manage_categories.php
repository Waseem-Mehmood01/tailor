<?php
if(isset($_GET['del'])){
	
	if(isset($_GET['categories_id'])){
		
		$id=$_GET['categories_id'];
		$sql = "DELETE FROM categories WHERE categories_id=".(int)$id;
		
		$acc =  DB::query($sql);
		
		echo '<script type="text/javascript">
		<!--
		window.location = "?route=modules/shop/manage_categories"
		//-->
		</script>';

	}

}

if(isset($_POST['btnEdit'])){
    $cID = isset($_POST['cID'])?(int)$_POST['cID']:'';
    $parent_id = isset($_POST['parent_id'])?(int)$_POST['parent_id']:'';
    $name = isset($_POST['name'])?$_POST['name']:'';
    @extract($_POST);
    DB::insertUpdate("categories",array( 
      'categories_id' => $cID,
      'parent_id' => $parent_id,
      'name' => $name,
      'active' => $active,
      'meta_title' => $meta_title,
      'meta_description' => $meta_description, 
      'meta_keywords' => $meta_keywords) );
    if($cID==''){
      $cID = DB::insertId();
    }

      $handle = new Upload($_FILES['featured_img']);
      if ($handle->uploaded) {
        $img_name = 'cate'.$cID;
        $type = substr($_FILES['featured_img']['type'],6,10);
        $handle->image_resize = true;
        $handle->image_ratio = true;
        $handle->image_x = 350;
        if($type=='jpeg'){ $type='jpg'; }
        DB::update('categories',array('featured_img'=> $img_name.'.'.$type), 'categories_id=%s', $cID);
        $handle->file_new_name_body   = $img_name;
        $handle->process('../images/categories/');
        if ($handle->processed) {
         // echo 'image resized';
          $handle->clean();
        } else {
          echo 'error : ' . $handle->error;
        }
      }

    echo '<script type="text/javascript">
		<!--
alert("Updated");
		window.location = "?route=modules/shop/manage_categories"
		//-->
		</script>'; 
}

?>
<div class="panel panel-info">
  <!-- Default panel contents -->
  <div class="panel-heading"><h3>Products Categories<a href="#" data-toggle="modal" data-target="#editModal"  class=" pull-right btn btn-sm btn-primary"> <span class="glyphicon glyphicon-plus"></span> &nbsp;Add New Category</a> </h3> </div>
  <div class="panel-body" style="font-size: 18px;">
<?php 
//$rootRow = DB::queryFirstRow('SELECT categories_id, name FROM categories WHERE parent_id=0');

$rootRow = array('name'=>'','categories_id'=>0);
//echo '<ul>';
display_with_children($rootRow, 0);
//echo '</ul>';
?>
 
 </div>
</div>

  
  
  <script>
	$(function(){
	
		
		$(".edit").on('click',function(e){
			var id = $(this).data('id');
			var parent = $(this).data('parent');
			var name = $(this).data('name');
		
			$("#cID").val(id);
			$("#parent_id").val(parent);
			$("#meta_title").val($(this).data('metatitle'));
			$("#meta_description").val($(this).data('metadescription'));
			$("#meta_keywords").val($(this).data('metakeywords'));
			 $("select#parent_id option").each(function(){
			        if($(this).val()==parent){ // EDITED THIS LINE
			            $(this).attr("selected","selected");    
			        }
			    });
			
			$("#name").val(name);
			$("#active_"+$(this).data('active')).prop("checked", true);
			});
	});
</script>
<div id="editModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add/Edit Category</h4>
      </div>
      
      <form action="" method="POST" class="form-horizontal" enctype="multipart/form-data">
          
      <div class="modal-body">
      
        <div class="form-group">
            <label class="control-label col-sm-4" for="cID">Parent Category</label>
            <div class="col-sm-8">
              <select id="parent_id" name="parent_id" class="form-control" required="required">
              <option value="0">-ROOT-</option>
              <?php 
                    $output = '';
        			CategoryTreeSelect($output, 0, '', '');
        			echo $output;
        	   ?>
        		</select>
            </div>
          </div>
          <input type="hidden" name="cID" id="cID" value="">
           <div class="form-group">
            <label class="control-label col-sm-4" for="active">Name/Title</label>
            <div class="col-sm-8">
              <input class="form-control" type="text" name="name" id="name" value="">
          </div>
        
        
      </div>
      
      <div class="form-group">
            <label class="control-label col-sm-4">Featured Image</label>
            <div class="col-sm-8">
              <input class="form-control" type="file" name="featured_img" id="featured_img" >
              <p>*Leave blank for no image update</p>
          </div>
        
        
      </div>

      <div class="form-group">
            <label class="control-label col-sm-4">Meta Title</label>
            <div class="col-sm-8">
              <input class="form-control" type="text" name="meta_title" id="meta_title" value="">
          </div>
        
        
      </div>
      
      <div class="form-group">
            <label class="control-label col-sm-4">Meta Description</label>
            <div class="col-sm-8">
              <textarea class="form-control" name="meta_description" id="meta_description"></textarea>
          </div>
        
        
      </div>
      
      <div class="form-group">
            <label class="control-label col-sm-4">Meta Keywords</label>
            <div class="col-sm-8">
              <textarea class="form-control" name="meta_keywords" id="meta_keywords"></textarea>
          </div>
        
        
      </div>
      
      <div class="form-group">
            <label class="control-label col-sm-4">Active</label>
            <div class="col-sm-8">
              	<label class="radio-inline"><input type="radio" name="active" id="active_1" value="1" checked>Yes</label>
				<label class="radio-inline"><input type="radio" name="active" id="active_0" value="0">No</label>
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
