<?php
if(isset($_GET['del'])){
	
	if(isset($_GET['categories_id'])){
		
		$id=$_GET['categories_id'];
		$sql = "DELETE FROM attribute_categories WHERE attribute_categories_id=".(int)$id;
		
		$acc =  DB::query($sql);
		
		echo '<script type="text/javascript">
		<!--
		window.location = "?route=modules/shop/attribute_categories"
		//-->
		</script>';

	}

}

if(isset($_POST['btnEdit'])){
	@extract($_POST);
    $cID = isset($_POST['cID'])?(int)$_POST['cID']:'';
     $name = isset($_POST['name'])?$_POST['name']:'';
    DB::insertUpdate("attribute_categories", array(
     'attribute_categories_id' => $cID,
     'categories_id' => $categories_id,
     'name' => $name
     )
  );
    echo '<script type="text/javascript">
		<!--
alert("Success");
		window.location = "?route=modules/shop/attribute_categories"
		//-->
		</script>';
}

?>
<div class="panel panel-info">
  <!-- Default panel contents -->
  <div class="panel-heading"><h3>Products Extra Attribute Categories<a href="#" data-toggle="modal" data-target="#editModal" class="pull-right btn btn-sm btn-primary"> <span class="glyphicon glyphicon-plus"></span> &nbsp;Add New Category</a> </h3> </div>
  <div class="panel-body" style="font-size: 18px;">
  <table class="table">
  	<tr>
  		<th>Product Category</th>
		<th>Attribute Category</th>
		<th>Action</th>
  	</tr>
    <?php 
      $categ = DB::query("select * from attribute_categories");
      foreach($categ as $row){
    echo ' 
    <tr>
       <td>'.get_category_name($row['categories_id']).'</td>';
       echo '<td>'.$row['name'].'<td>';
       echo '<td><a alt="Edit" data-name="'.$row['name'].'" data-pcid="'.$row['categories_id'].'" data-id="'.$row['attribute_categories_id'].'" title="Edit" data-toggle="modal" data-target="#editModal" class="btn btn-info btn-sm edit" href="#"><i class="fa fa-pencil"></i> Edit
      </a><td>
    </tr>';
   } ?>
  </table>
 </div>
</div>

  
  
  <script>
	$(function(){
	
		
		$(".edit").on('click',function(e){
			var id = $(this).data('id');
			var name = $(this).data('name');
			var pcid = $(this).data('pcid');
			$("#cID").val(id);
			$("#name").val(name);
			$("#categories_id").val(pcid);
			});
	});
</script>
<div id="editModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit Category</h4>
      </div>
      
      <form action="" method="POST" class="form-horizontal">
          
      <div class="modal-body">
      
       
          <input type="hidden" name="cID" id="cID" value="">
          <div class="form-group">
            <label class="control-label col-sm-4" for="active">Product Category</label>
            <div class="col-sm-8">
              <select id="categories_id" name="categories_id" class="form-control" required="required">
              <?php 
                    $output = '';
        			CategoryTreeSelect($output, 0, '', '');
        			echo $output;
        	   ?>
        		</select>
          </div>
      </div>
           <div class="form-group">
            <label class="control-label col-sm-4" for="active">Attribute Category Name</label>
            <div class="col-sm-8">
              <input class="form-control" type="text" name="name" id="name" value="">
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
