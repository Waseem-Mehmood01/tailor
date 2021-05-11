<?php 
$orders_id = isset($_GET['orders_id'])?(int)$_GET['orders_id']:die('Whoops..! Something went wrong');
$update_id = DB::query("UPDATE orders SET is_paid=1 WHERE orders_id = '".$orders_id."'");
if(isset($update_id)){
    echo '<script>window.location.href="?route=modules/orders/view_orders";</script>';
}
?>