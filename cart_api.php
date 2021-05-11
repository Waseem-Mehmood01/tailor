<?php 
include_once 'functions.php';


$action = isset($_GET['action'])?cleanVar($_GET['action']):'';
if($action == ''){
    $action = isset($_POST['action'])?cleanVar($_POST['action']):'';
}
if($action == ''){
    die('action404');
}

$token = isset($_GET['token'])?cleanVar($_GET['token']):'';
if($token == ''){
    $token = isset($_POST['token'])?cleanVar($_POST['token']):'';
} 

if($token == ''){
    die('token404');
}


switch($action){
    case "addCart":
        if(!empty($_POST["quantity"])) {
            $itemArray = array((int)cleanVar($_POST["product_id"])=>array( 'product_id'=>(int)cleanVar($_POST["product_id"]),'prod_note'=>cleanVar($_POST["prod_note"]),'width'=>cleanVar($_POST["width"]),'height'=>cleanVar($_POST["height"]), 'quantity'=>(int)cleanVar($_POST["quantity"]), 'size'=>cleanVar($_POST["size"]), 'color'=>cleanVar($_POST["color"]), 'products_price_id'=>(int)cleanVar($_POST["products_price_id"]) ));           
            
            if(!empty($_SESSION["cart_item"])) {
                if(in_array((int)cleanVar($_POST["product_id"]),array_keys($_SESSION["cart_item"]))) {
                    foreach($_SESSION["cart_item"] as $k => $v) {
                        echo $_SESSION["cart_item"][$k]["size"];
                        echo '--'.cleanVar($_POST["size"]);
                        if((int)cleanVar($_POST["product_id"]) == $k) {
                            if(!strcmp($_SESSION["cart_item"][$k]["size"],cleanVar($_POST["size"])) ){
                            if(empty($_SESSION["cart_item"][$k]["quantity"])) {
                                $_SESSION["cart_item"][$k]["quantity"] = 1;
                            }
                            $_SESSION["cart_item"][$k]["quantity"] += (int)cleanVar($_POST["quantity"]);
                            } else {
                                $_SESSION["cart_item"] = array_merge($_SESSION["cart_item"],$itemArray);
                            }
                        }
                    }
                } else {
                    $_SESSION["cart_item"] = array_merge($_SESSION["cart_item"],$itemArray);
                }
            } else {
                $_SESSION["cart_item"] = $itemArray;
            }
        }
    break;
    
    case "remove":
        if(!empty($_SESSION["cart_item"])) {
            foreach($_SESSION["cart_item"] as $k => $v) {
                if((int)cleanVar($_GET["product_id"]) == $_SESSION["cart_item"][$k]["product_id"]){
                    unset($_SESSION["cart_item"][$k]);
                }
                if(empty($_SESSION["cart_item"])){
                        unset($_SESSION["cart_item"]);
                }
            }
        }
        break;
        
    case "editQty":
        if(!empty($_SESSION["cart_item"])) {
            foreach($_SESSION["cart_item"] as $k => $v) {
                if((int)cleanVar($_GET["product_id"]) == $_SESSION["cart_item"][$k]["product_id"]) {
                    $_SESSION["cart_item"][$k]["quantity"] = (int)cleanVar($_GET["quantity"]);
                }
            }
        }
        break;
    case "empty":
        unset($_SESSION["cart_item"]);
        break;
}


?>