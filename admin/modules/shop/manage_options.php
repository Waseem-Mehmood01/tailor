<?php
if (isset($_GET['del'])) {

    if (isset($_GET['card_options_id'])) {

        $id = $_GET['card_options_id'];
        $sql = "DELETE FROM card_options WHERE card_options_id=" . (int) $id;

        $acc = DB::query($sql);

        echo '<script type="text/javascript">
    <!--
    window.location = "?route=modules/shop/manage_options"
    //-->
    </script>';
    }
}

if (isset($_POST['btnEdit'])) {

    $cID = isset($_POST['cID']) ? (int) $_POST['cID'] : '';
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $unit = isset($_POST['unit']) ? $_POST['unit'] : '';
    $cost = isset($_POST['cost']) ? $_POST['cost'] : 0.00;
    DB::insertUpdate("products_options", array(
        'products_options_id' => $cID,
        'unit' => $unit,
        'cost' => $cost,
        'name' => addslashes($name)
    ));

    if ($cID == '') {
        $cID = DB::insertId();
    }

    $handle = new Upload($_FILES['img']);
    if ($handle->uploaded) {
        $img_name = 'option' . $cID;
        $type = substr($_FILES['img']['type'], 6, 10);
        $handle->image_resize = true;
        $handle->image_ratio = true;
        $handle->image_x = 350;
        if ($type == 'jpeg') {
            $type = 'jpg';
        }
        DB::update('products_options', array(
            'img' => $img_name . '.' . $type
        ), 'products_options_id=%s', $cID);
        $handle->file_new_name_body = $img_name;
        $handle->process('../images/options/');
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
    window.location = "?route=modules/shop/manage_options"
    //-->
    </script>';
}

?>
<div class="panel panel-info">
	<!-- Default panel contents -->
	<div class="panel-heading">
		<h3>
			Product Ingredients<a href="#" data-toggle="modal"
				data-target="#editModal" id="add"
				class="add pull-right btn btn-sm btn-primary"> <span
				class="glyphicon glyphicon-plus"></span> &nbsp;Add New Option
			</a>
		</h3>
	</div>
	<div class="panel-body" style="font-size: 18px;">
<?php

$options = DB::query("SELECT * FROM products_options");
?>
<table class="table">
			<thead>
				<tr>
					<td>#</td>
					<td>Name</td>
					<td>Measurement</td>
					<td>Cost</td>
					<td>img</td>
					<td>Action</td>
				</tr>
			</thead>
			<tbody>
<?php
foreach ($options as $opt) {
    echo '<tr>';
    echo '<td>' . $opt['products_options_id'] . '</td>';
    echo '<td>' . $opt['name'] . '</td>';
    echo '<td>' . $opt['unit'] . '</td>';
    if($opt['cost']<>''){
        echo '<td>$' . $opt['cost'] . '</td>';
    } else {
        echo '<td></td>';
    }
    
    echo '<td><img style="max-width:150px;" src="../images/options/' . $opt['img'] . '"></td>';
    echo '<td><a alt="Edit" data-cost="' . $opt['cost'] . '"  data-unit="' . $opt['unit'] . '"  data-name="' . $opt['name'] . '" data-id="' . $opt['products_options_id'] . '" title="Edit" data-toggle="modal" data-target="#editModal" class="text-success edit" href="#"><i class="fa fa-pencil"></i>Edit</a></td>';
    echo '</tr>';
}

?>
</tbody>
		</table>
	</div>
</div>
<script>
  $(function(){      
    $(".edit").on('click',function(e){
      var id = $(this).data('id');     
      var name = $(this).data('name');  
      var UNit = $(this).data('unit');  
      var cost = $(this).data('cost');  
      $("#cID").val(id);   
      $("#name").val(name);
      $("#cost").val(cost);
      $("#unit").val(UNit);
      });
    $("#add").on('click',function(e){
      $(".form-control").val('');   
     
      });
  });
</script>
<div id="editModal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Add/Edit Option</h4>
			</div>
			<form action="" method="POST" class="form-horizontal"
				enctype="multipart/form-data">
				<div class="modal-body">
					<input type="hidden" name="cID" id="cID" value="">
					<div class="form-group">
						<label class="control-label col-sm-4">Name/Title</label>
						<div class="col-sm-8">
							<input class="form-control" type="text" name="name" id="name"
								value="">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-4">Measurement</label>
						<div class="col-sm-8">
							<input class="form-control" type="text" name="unit" id="unit"
								placeholder="Unit" value="">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-4">Cost</label>
						<div class="col-sm-8">
							<input class="form-control" type="text" name="cost" id="cost"
								placeholder="Cost per measurement" value="">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-4">Featured Image</label>
						<div class="col-sm-8">
							<input class="form-control" type="file" name="img" id="img">
							<p>*Leave blank for no image update</p>
						</div>
					</div>
					<div class="modal-footer">
						<button type="submit" name="btnEdit" id="btnEdit"
							class="btn btn-success">Save</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
			
			</form>
		</div>
	</div>
</div>
