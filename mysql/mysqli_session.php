<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require("mysqli_connect.php");
require("mysqli_logs.php");


// file if user/admin is logged in  

?>