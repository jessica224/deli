<?php

error_reporting(E_ALL ^ E_NOTICE);
$path = $_SERVER['SERVER_NAME'];
if ($path == "localhost")
    $base_url = "http://localhost/delivery-web";
else
    $base_url = "http://" . $path;
define('BASE_URL', $base_url);


define('BASE_URL', $base_url);
define('API_KEY', 'deliveryApp4b4956495d5e9bfabb7005313af62fb3');
?>