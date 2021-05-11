<?php

$manufacturers_id=isset($_GET['manufacturers_id'])?(int)trim($_GET['manufacturers_id']):'';
if($manufacturers_id==''){
    $manufacturers_id=isset($_POST['manufacturers_id'])?(int)trim($_POST['manufacturers_id']):die("Whoops..!Something went wrong");   
}


$message= "";
if(isset($_POST['add'])){
    
    @extract($_POST);
    
    
    DB::update('manufacturers',array(
        'name' => $name,
        'description' => $description,
        'email' => $email,
        'contact' => $contact,
        'user_name' => $user_name,
        'password' => $password,
        'address' => $address,
        'active' => $active
    ), 'manufacturers_id=%s', $manufacturers_id
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
    $message = "Successfully Added New Manufacturer";
    
    echo '<script type="text/javascript">
<!--
window.location = "?route=modules/purchase/manage_manufacturer";
//-->
</script>';
    
}

$manufac = DB::queryFirstRow("select * from manufacturers where `manufacturers_id` = '".$manufacturers_id."'");

if(DB::count()<1){
    die("Data no found. Go back");
}



?>

  <div class="panel panel-info">
  <!-- Default panel contents -->
  <div class="panel-heading"><h3>Products Manufacturer<a href="?route=modules/purchase/manage_manufacturer" class=" pull-right btn btn-sm btn-primary"> <span class="glyphicon glyphicon-list"></span> &nbsp;View All</a> </h3> </div>
  <div class="panel-body">
<div class="container">

      <div class="row">
    
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xs-offset-0 col-sm-offset-0 col-md-offset-3 col-lg-offset-3 toppad" >
   
   
          <div class="panel panel-info">
		  <form method="post" action="<?php $_SERVER['PHP_SELF']; ?>">
            <div class="panel-heading">
              <h3 class="panel-title">Update Manufacturer</h3>
            </div>
            <div class="panel-body">
              <div class="row">
                
                <div class="col-lg-10"> 
                  <table class="table table-user-information">
                    <tbody>
 	  
					
                      <tr>
                        <td>Manufacturer Name:</td>
                        <td><input type="text" required name="name" value="<?php echo $manufac['name']; ?>"></td>
                      </tr>
                   
                      <tr>
                        <td>User Name:</td>
                        <td><input type="text" required name="user_name" value="<?php echo $manufac['user_name']; ?>" placeholder="Login Detail"></td>
                      </tr>
                      <tr>
                        <td>Password:</td>
                        <td><input type="text" required name="password" value="<?php echo $manufac['password']; ?>"  placeholder="Login Detail"></td>
                      </tr>
                     
                      <tr>
                        <td>Contact:</td>
                        <td><input type="text" name="contact" value="<?php echo $manufac['contact']; ?>"></td>
                      </tr>
                      <tr>
                        <td>Email:</td>
                        <td><input type="email" required name="email" value="<?php echo $manufac['email']; ?>"></td>
                      </tr>
                      <tr>
                        <td>Address:</td>
                        <td><textarea name="address"><?php echo $manufac['address']; ?></textarea></td>
                      </tr>
					  <tr>
                        <td>Description:</td>
                        <td><textarea name="description"><?php echo $manufac['description']; ?></textarea></td>
                      </tr> 
                      <tr>
                        <td>Active:</td>
                        <td><label class="radio-inline"><input type="radio" name="active" value="1"  <?php if($manufac['active']==1){ echo 'CHECKED'; } ?>>Yes</label>
							<label class="radio-inline"><input type="radio" name="active" value="0" <?php if($manufac['active']==0){ echo 'CHECKED'; } ?>>No</label></td>
                      </tr> 
                      
                     
					  <tr>
					  <td></td>
					  <td><input type="submit" class='btn btn-success btn-sm' name="add" value=" Update ">
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

