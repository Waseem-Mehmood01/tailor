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


?>
<div class="panel panel-info">
  <!-- Default panel contents -->
  <div class="panel-heading"><h3>Products Categories<a href="?route=modules/shop/add_category" class=" pull-right btn btn-sm btn-primary"> <span class="glyphicon glyphicon-plus"></span> &nbsp;Add New Category</a> </h3> </div>
  <div class="panel-body">

<table class="table table-striped table-bordered data-table">
<thead>
	<tr>
	<th>Category Name</th>
	<th>Parent Category</th>
	<th>Description</th>
	<th>Actions</th>
	</tr>
</thead>
<tbody>
<?php
$sql = "SELECT * FROM categories";
$get = DB::query($sql);
foreach($get as $cat) { 
echo "<tr>";
echo "<td>".$cat['name']."</td>";
echo "<td>".get_category_name($cat['parent_id'])."</td>";
echo "<td>".$cat['description']."</td>";
echo "<td><!--<a class='btn btn-primary btn-sm' href ='?route=modules/shop/edit_category&categories_id=".$cat['categories_id']."'>Edit&nbsp;<span class='glyphicon glyphicon-new-window'></span></a>-->";

echo "<a class='btn btn-danger btn-sm' onclick='return confirm(\" Are you sure to delete this? \");' href ='?route=modules/shop/manage_categories&categories_id=".$cat['categories_id']."&del=yes'>Delete&nbsp;<span class='glyphicon glyphicon-trash'></span></a>
			   </td>";
echo "</tr>";		   
}
			  // echo $tbl->display();
?>
</tbody>
</table>
 </div>
</div>
