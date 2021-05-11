<?php
require('functions.php');

$qu = DB::query("select * from products where name like '%".$_GET['p_name']."%' limit 0,9");

foreach($qu as $res){
    echo '<li><a class="pListing" href="#" data-pID="'.$res['products_id'].'" data-pdescr="'.$res['name'].'">['.$res['products_id'].']-'.$res['name'].'</a></li>';
}

?>