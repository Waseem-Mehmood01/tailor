<?php

require('../functions.php');

$name = $_POST['name']; 

$pk= $_POST['pk']; 

$value= $_POST['value'];
if(!empty($value)) {
    DB::Update("products_price",array(
        $name => $value
    ),
        "products_price_id =%s", $pk);
} else {
    echo "This field is required!";
}

?>