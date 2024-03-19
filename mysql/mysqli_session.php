<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require("mysqli_connect.php");
require("mysqli_logs.php");


// can be modified in php.ini but this is alternative
ini_set("max_execution_time", 600);     // 10 minute processing time max
ini_set('upload_max_filesize', '80M');  // 50MB max file size
ini_set('post_max_size', '80M');        // post_max_size
?>