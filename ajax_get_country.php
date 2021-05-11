<?php 

include "functions.php"; 

if(isset($_GET['country_id'])){
    $states = DB::query("SELECT st.`name`, st.`id` FROM states st, countries c WHERE c.`id`=st.`country_id` AND c.`name` LIKE '".cleanVar($_GET['country_id'])."'");
    foreach($states as $st){
        echo '<option value="'.$st['name'].'">'.$st['name'].'</option>';
    }
} else {
    echo 'Access denied';
}


?>