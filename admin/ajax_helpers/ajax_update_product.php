<?php

require('../functions.php');

$name = $_POST['name']; 

$pk= $_POST['pk']; 

$value= $_POST['value'];
if(!empty($value)) {
    DB::Update("products",array(
        $name => $value
    ),
        "products_id =%s", $pk);
} else {
    echo "This field is required!";
}

?>