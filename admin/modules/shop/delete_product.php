 <?php 
 
// echo $alertMsg = 'sajjad';
 
 $products_id = isset($_GET['products_id'])?(int)$_GET['products_id']:die('Something went wrong go back..');

 $product = DB::queryFirstRow("DELETE FROM products WHERE `products_id` = '".$products_id."'");
 
// var_dump($product);
 if($product != Null){ 
     die('Product not found.. go back'); 
 }
 else{
     echo '<script>window.location.href = "?route=modules/shop/manage_products";</script>';
 }
 
// if(isset($_GET['active'])){
//     DB::update('products', array('active'=> (int)$_GET['active']), 'products_id=%s', $products_id);
//    echo '<script>alert("Product status changed");location.href="?route=modules/shop/view_product&products_id='.$products_id.'";</script>';
// }
 
 ?>