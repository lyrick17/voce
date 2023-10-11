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
        $activity = "registered";
        
        $query_insert = mysqli_prepare($dbcon, "INSERT INTO user_activity_log (user_id, activity_description, activity_date) VALUES (?, ?, NOW())");
        mysqli_stmt_bind_param($query_insert, 'is', $row['user_id'], $activity);
        mysqli_stmt_execute($query_insert);


        // $_SERVER['REMOTE_ADDR'] - for IP address
    }

    // Free the result set
    mysqli_free_result($result);
}

function log_login($user) {

    $query_get = "SELECT user_id FROM users WHERE username = '" . $user . "'";

    $result = mysqli_query($dbcon, $query_get);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $activity = "logged in";
        
        $query_insert = mysqli_prepare($dbcon, "INSERT INTO user_activity_log (user_id, activity_description, activity_date) VALUES (?, ?, NOW())");
        mysqli_stmt_bind_param($query_insert, 'is', $row['user_id'], $activity);
        mysqli_stmt_execute($query_insert);
    }

    // Free the result set
    mysqli_free_result($result);
}

function log_logout($user) {
    
    $query_get = "SELECT user_id FROM users WHERE username = '" . $user . "'";

    $result = mysqli_query($dbcon, $query_get);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $activity = "logged out";
        
        $query_insert = mysqli_prepare($dbcon, "INSERT INTO user_activity_log (user_id, activity_description, activity_date) VALUES (?, ?, NOW())");
        mysqli_stmt_bind_param($query_insert, 'is', $row['user_id'], $activity);
        mysqli_stmt_execute($query_insert);
    }

    // Free the result set
    mysqli_free_result($result);
}

function log_text_translate($user) {
    
    $query_get = "SELECT user_id FROM users WHERE username = '" . $user . "'";

    $result = mysqli_query($dbcon, $query_get);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $activity = "translation: text-to-text";
        
        $query_insert = mysqli_prepare($dbcon, "INSERT INTO user_activity_log (user_id, activity_description, activity_date) VALUES (?, ?, NOW())");
        mysqli_stmt_bind_param($query_insert, 'is', $row['user_id'], $activity);
        mysqli_stmt_execute($query_insert);
    }

    // Free the result set
    mysqli_free_result($result);
}


// register - needs username
// login - needs username or email
// logout - needs username
// every other action - needs the SESSION username variable 
    // t-t translate, audio upload, audio download, output download, audio transcript, audio translate
/*
function logs($log_act, $user) {
    $result = mysqli_query($dbcon, $query_get);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
    
        switch ($log_act) {
            case "register":
                break;
            case "login":
                break;
            case "logout":
                break;
            case "text-to-text":
                $activity = "translation: text-to-text";
                break;
            default:
                break;
        }
        
        $query_insert = mysqli_prepare($dbcon, "INSERT INTO user_activity_log (user_id, activity_description, activity_date) VALUES (?, ?, NOW())");
        mysqli_stmt_bind_param($query_insert, 'is', $row['user_id'], $activity);
        mysqli_stmt_execute($query_insert);
    }

    // Free the result set
    mysqli_free_result($result);
}*/

mysqli_close($dbcon);
?>
