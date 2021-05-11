<?php 

include "functions.php"; 

if(isset($_GET['country_id'])){
    $states = DB::query("SELECT * FROM states s WHERE s.`country_id` = '".(int)cleanVar($_GET['country_id'])."'");
    foreach($states as $st){
        echo '<option value="'.$st['name'].'">'.$st['name'].'</option>';
    }
} else {
    echo 'Access denied';
}


?>