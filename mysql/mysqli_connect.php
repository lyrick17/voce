<?php 

DEFINE ('DB_USER', 'root'); // change this depending on the db_user 
DEFINE ('DB_PASSWORD', ''); // change this depending on the db_password
DEFINE ('DB_HOST', 'localhost');
//DEFINE ('DB_NAME', 'audiototext');
DEFINE ('DB_NAME', 'voce');


try{
    $dbcon = @mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
    OR die ('Could not connect to MySQLi: '.mysqli_connect_error());
    mysqli_set_charset($dbcon, 'utf8');
}
catch(mysqli_sql_exception $e){
    require "mysqli_error_display.php";
    exit();
}