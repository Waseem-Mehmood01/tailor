<?php

$username = "root";
$password = "";
$hostname = "localhost";
$dbName = "tailor";

define('REDHARE_ID', '1');
define('ANCHANTO_ID', '2');

DB::$user = $username;
DB::$password = $password;
DB::$dbName = $dbName;
DB::$host = $hostname; // defaults to localhost if omitted

?>
