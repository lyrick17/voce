<?php
 require("mysql/mysqli_connect.php"); 

//Delete records of a user from all tables in the database
    $q = "SET foreign_key_checks = 0;";
    mysqli_multi_query($dbcon, $q);

    $q = "DELETE FROM users WHERE user_id = ". $_POST['userId'] . ";";
    mysqli_multi_query($dbcon, $q);

    $q = "DELETE FROM audio_files WHERE user_id = ". $_POST['userId'] . ";";
    mysqli_multi_query($dbcon, $q);

    $q = "DELETE FROM text_translations WHERE user_id = ". $_POST['userId'] . ";";
    mysqli_multi_query($dbcon, $q);

    $q = "DELETE FROM user_activity_log WHERE user_id = ". $_POST['userId']  . ";";
    mysqli_multi_query($dbcon, $q);

    $q = "SET foreign_key_checks = 1;";
    mysqli_query($dbcon, $q);

    
$q = "SELECT user_id, username, email, registration_date, type FROM users ORDER BY user_id ASC";
$users = mysqli_query($dbcon, $q);
$result = mysqli_fetch_all($users, MYSQLI_ASSOC);
exit(json_encode($result));

