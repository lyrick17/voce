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
        case "register":
            $activity = "user registered";
            break;
        case "login":
            $activity = "user logged in";
            break;
        case "logout":
            $activity = "user logged out";
            break;
        case "text-to-text":
            $activity = "translation: text-to-text";
            break;
        case "audio-to-text":
            $activity = "translation: audio-to-text";
            break;
        case "error-tt":
            $activity = "error text-text translation";
            break;
        case "error-at":
            $activity = "error audio-text translation";
            break;
        default:
            $activity = "unknown activity";
            break;
        // more logs to be added
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
