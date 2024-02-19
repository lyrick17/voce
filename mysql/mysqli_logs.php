<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function logs($log_act, $user, $dbcon) {
    // every action of user records a log in db
    // 1 determine the activity of the user before
    // 2 finding the user in db and
    // 3 creating a log
    $activity = "";

    // 1
    switch ($log_act) {

        // -----------------------------
        // Register, Login, Logout Logs
        case "register":
            $activity = "user registered";
            break;
        case "login":
            $activity = "user logged in";
            break;
        case "logout":
            $activity = "user logged out";
            break;
        // -----------------------------
        
        
        // -----------------------------
        // User Action Logs
            // contains upload file, translation and deletion of record
        case "upload-file":
            $activity = "user upload file";    
            break;
        case "text-to-text":
            $activity = "translation: text-to-text";
            break;
        case "audio-to-text":
            $activity = "translation: audio-to-text";
            break;
        case "delete-text-to-text":
            $activity = "user delete: text-to-text record id __";
            break;
        case "delete-audio-to-text":
            $activity = "user delete: audio-to-text record id __";
            break;
        // -----------------------------
        
        // -----------------------------
        // Error Logs
            // Errors for TEXT TO TEXT
        case "error-tt-1":
            $activity = "error text-text: language not selected";
            break;
        case "error-tt-2":
            $activity = "error text-text: no text input";
            break;
        case "error-tt-3":
            $activity = "error text-text: same language selected";
            break;
        case "error-tt-4":
            $activity = "error text-text: user chose unprovided language";
            break;
        case "error-tt-5":
            $activity = "error text-text: system error";
            break;

            // Errors for AUDIO TO TEXT
        case "error-at-1":
            $activity = "error audio-text: language / model not selected";
            break;
        case "error-at-2":
            $activity = "error audio-text: no file uploaded";
            break;
        case "error-at-3":
            $activity = "error audio-text: file format not supported";
            break;
        case "error-at-4":
            $activity = "error audio-text: same language selected";
            break;
        case "error-at-5":
            $activity = "translation no output: audio-to-text";
            break;
        case "error-at-6":
            $activity = "error audio-text: user chose unprovided language";
            break;
        case "error-at-7":
            $activity = "error audio-text: system error";
            break;
        // -----------------------------

        default:
            $activity = "unknown activity";
            break;
    }

    // 2
    $query_get = "SELECT user_id FROM users WHERE username = '" . $user . "'";
    $result = mysqli_query($dbcon, $query_get);
    
    if ($result) {
        $row = mysqli_fetch_assoc($result);

        // 3
        $query_insert = mysqli_prepare($dbcon, "INSERT INTO user_activity_log (user_id, activity_description, activity_date) VALUES (?, ?, NOW())");
        mysqli_stmt_bind_param($query_insert, 'is', $row['user_id'], $activity);
        mysqli_stmt_execute($query_insert);
        
    }
    // Free the result set
    mysqli_free_result($result);
}

//mysqli_close($dbcon);
?>


<?php 
/*
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function logs($log_act, $user, $dbcon) {
    // every action of user records a log in db
    // 1 determine the activity of the user before
    // 2 finding the user in db and
    // 3 creating a log
    $activity = "";
    $translation_id = 0;
    $admin_id = 0;

    // 1
    switch ($log_act) {

        // -----------------------------
        // Register, Login, Logout Logs
        /*
        case "register":
            $activity = "user registered";
            break;
        case "login":
            $activity = "user logged in";
            break;
        case "logout":
            $activity = "user logged out";
            break;
        */
        // -----------------------------
        
/*
        // -----------------------------
        // Action Logs
            // contains upload file, translation and deletion of record
        case "upload-file":
            $activity = "user upload file";    
            break;
        case "text-to-text":
            $activity = "translation: text-to-text";
            break;
        case "audio-to-text":
            $activity = "translation: audio-to-text";
            break;
        /*
        case "delete-text-to-text":
            $activity = "user delete: text-to-text record id __";
            break;
        case "delete-audio-to-text":
            $activity = "user delete: audio-to-text record id __";
            break;
        */
        // -----------------------------


/*
        // -----------------------------
        // Error Logs
            // Errors for TEXT TO TEXT
        case "error-tt-1":
            $activity = "error text-text: language not selected";
            break;
        case "error-tt-2":
            $activity = "error text-text: no text input";
            break;
        case "error-tt-3":
            $activity = "error text-text: same language selected";
            break;
        case "error-tt-4":
            $activity = "error text-text: user chose unprovided language";
            break;
        case "error-tt-5":
            $activity = "error text-text: system error";
            break;

            // Errors for AUDIO TO TEXT
        case "error-at-1":
            $activity = "error audio-text: language / model not selected";
            break;
        case "error-at-2":
            $activity = "error audio-text: no file uploaded";
            break;
        case "error-at-3":
            $activity = "error audio-text: file format not supported";
            break;
        case "error-at-4":
            $activity = "error audio-text: same language selected";
            break;
        case "error-at-5":
            $activity = "translation no output: audio-to-text";
            break;
        case "error-at-6":
            $activity = "error audio-text: user chose unprovided language";
            break;
        case "error-at-7":
            $activity = "error audio-text: system error";
            break;
        // -----------------------------

        default:
            $activity = "unknown activity";
            break;
    }

    // 2
    $query_get = "SELECT user_id FROM users WHERE username = '" . $user . "'";
    $result = mysqli_query($dbcon, $query_get);
    
    if ($result) {
        $row = mysqli_fetch_assoc($result);

        // 3
        $query_insert = mysqli_prepare($dbcon, "INSERT INTO activity_logs (activity_description, activity_date) VALUES (?, ?, NOW())");
        mysqli_stmt_bind_param($query_insert, 's', $activity);
        mysqli_stmt_execute($query_insert);
        
    }
    // Free the result set
    mysqli_free_result($result);
}

//mysqli_close($dbcon);
*/
?>

