<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require("mysqli_connect");

function log_register($email) {

    $query_get = "SELECT user_id FROM users WHERE email = '" . $email . "'";

    $result = mysqli_query($dbcon, $query_get);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        
        $query_insert = mysqli_prepare($dbcon, "INSERT INTO user_activity_log (user_id) VALUES (?)");
        mysqli_stmt_bind_param($query_insert, 'i', $row['user_id']);
        mysqli_stmt_execute($query_insert);
    }

    // Free the result set
    mysqli_free_result($result);
}

function log_login($user) {
    return 0;
}

function log_logout($user) {
    return 0;
}

function log_text_translate($user) {
    return 0;
}

mysqli_close($dbcon);
?>