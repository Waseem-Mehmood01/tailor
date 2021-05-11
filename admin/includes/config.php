<?php
//ini_set('display_errors', 0);
//ini_set('memory_limit', '-1');
date_default_timezone_set('America/New_York');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Define System CONSTANTS
define('SYSTEM_ENCODING', 'utf8');
define('BR', '</br>');

$now = date("Y-m-d H:i:s");

define('FOLDER_NAME', 'tailor/admin');

define('ROOT_PATH', realpath(dirname(__FILE__) . "/../") . '/');
if (isset($_SERVER['HTTPS'])) {
    define('SITE_ROOT', 'https://' . $_SERVER['HTTP_HOST'] . '/' . FOLDER_NAME . '/');
} else {
    define('SITE_ROOT', 'http://' . $_SERVER['HTTP_HOST'] . '/' . FOLDER_NAME . '/');
}
define('DB_PREFIX', 'sa_');
define('DEFAULT_PRICE', '$');
define('COMPANY_NAME', 'Tailor Shop');
define('SITE_URL', 'https://localhost/tailor/');


?>
