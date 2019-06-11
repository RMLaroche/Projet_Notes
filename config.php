<?php

define('DB_SERVER', '91.121.159.198:5420');
define('DB_USERNAME', 'POE');
define('DB_PASSWORD', '3h3zmNrmzmQrZmz9');
define('DB_NAME', 'POE');
 
/* Attempt to connect to MySQL database */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>