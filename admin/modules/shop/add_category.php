<?php




	$message= "";
if(isset($_POST['add'])){

@extract($_POST);


DB::Insert('categories',array(
			'parent_id' => $parent_id,
			'name' => $name,
			'description' => $description)  
			);

/*	
	for($i=0, $iMaxSize=count($_POST['module_id']); $i<$iMaxSize; $i++){
		$insert = DB::Insert(DB_PREFIX.$_SESSION['co_prefix'].'user_module_access', 
								array(			
										'user_id'		=> $user_id,
										'module_id'		=> $_POST['module_id'][$i] 
									));
	}
	*/
$message = "Successfully Added New Category";
 
echo '<script type="text/javascript">
<!--
window.location = "?route=modules/shop/manage_categories";
//-->
</script>';

} 

?>

  <div class="panel panel-info">
  <!-- Default panel contents -->
  <div class="panel-heading"><h3>Products Categories<a href="?route=modules/shop/manage_categories" class=" pull-right btn btn-sm btn-primary"> <span class="glyphicon glyphicon-list"></span> &nbsp;View All</a> </h3> </div>
  <div class="panel-body">
<div class="container">

      <div class="row">
    
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xs-offset-0 col-sm-offset-0 col-md-offset-3 col-lg-offset-3 toppad" >
   
   
          <div class="panel panel-info">
		  <form method="post" action="<?php $_SERVER['PHP_SELF']; ?>">
            <div class="panel-heading">
              <h3 class="panel-title">Add New Category</h3>
            </div>
            <div class="panel-body">
              <div class="row">
                
                <div class="col-lg-10"> 
                  <table class="table table-user-information">
                    <tbody>
 	  
					<tr>
                        <td>Parent Category:</td>
                        <td><select name="parent_id">
								<option value="0">-NONE-</option>
								<?php
								$cate = DB::query("SELECT c.`categories_id`,c.`parent_id`, c.`name` FROM categories c");
								foreach($cate as $cat){
									echo '<option value="'.$cat['categories_id'].'">';
									if($cat['parent_id'] <> '0') {
										echo '-';
									}
									echo $cat['name'].'</option>';
								} ?>
							</select>
						</td>
                      </tr>
                      <tr>
                        <td>Category Name:</td>
                        <td><input type="text" required name="name"></td>
                      </tr>
					  <tr>
                        <td>Description:</td>
                        <td><textarea name="description"></textarea></td>
                      </tr> 
                     
					  <tr>
					  <td></td>
					  <td><input type="submit" class='btn btn-primary btn-sm' name="add" value="Add">
					  <font color="red"><?php echo $message;?> </font>
					  </td>
					  </tr>
                     
                    </tbody>
                  </table>
                              
                </div>
              </div>
            </div>
                <!--  <div class="panel-footer">
                        <a data-original-title="Broadcast Message" data-toggle="tooltip" type="button" class="btn btn-sm btn-primary"><i class="glyphicon glyphicon-envelope"></i></a>
                        <span class="pull-right">
                            <a href="edit.html" data-original-title="Edit this user" data-toggle="tooltip" type="button" class="btn btn-sm btn-warning"><i class="glyphicon glyphicon-edit"></i></a>
                            <a data-original-title="Remove this user" data-toggle="tooltip" type="button" class="btn btn-sm btn-danger"><i class="glyphicon glyphicon-remove"></i></a>
                        </span>
                    </div> -->
            </form>
          </div>
        </div>
      </div>
    </div>
</div>
</div>

