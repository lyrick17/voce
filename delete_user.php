<?php
 require("mysql/mysqli_connect.php"); 
 require("utilities/delete_files.php");

//Delete records of a user from all tables in the database
if($_POST['deleteSelectedUsers'] == 'true'){
    foreach(json_decode($_POST['usersToDelete']) as $userId){
        $q = "SET foreign_key_checks = 0;";
        mysqli_query($dbcon, $q);

        $q = mysqli_prepare($dbcon, "DELETE FROM users WHERE user_id = ?;");
        bindAndExec($q, "s", $userId);

        $q = mysqli_prepare($dbcon, "DELETE FROM audio_files WHERE user_id = ?;");
        bindAndExec($q, "s", $userId);

        $q =  mysqli_prepare($dbcon,"DELETE FROM text_translations WHERE user_id = ?;");
        bindAndExec($q, "s", $userId);

        $q =  mysqli_prepare($dbcon,"DELETE FROM user_activity_log WHERE user_id = ? ;");
        bindAndExec($q, "s", $userId);
        
        $q = "SET foreign_key_checks = 1;";
        mysqli_query($dbcon, $q);

        // all audio files that user uploaded will be deleted as well
        deleteAllAudioFiles($userId);
    }
}


else{
    $userId = $_POST['userId'];
    $q = "SET foreign_key_checks = 0;";
    mysqli_query($dbcon, $q);

    $q = mysqli_prepare($dbcon,"DELETE FROM users WHERE user_id = ? ;");
    bindAndExec($q, "s", $userId);

    $q =  mysqli_prepare($dbcon,"DELETE FROM audio_files WHERE user_id = ? ;");
    bindAndExec($q, "s", $userId);

    $q =  mysqli_prepare($dbcon,"DELETE FROM text_translations WHERE user_id = ? ;");
    bindAndExec($q, "s", $userId);

    $q = mysqli_prepare($dbcon,"DELETE FROM user_activity_log WHERE user_id = ? ;");
    bindAndExec($q, "s", $userId);

    $q = "SET foreign_key_checks = 1;";
    mysqli_query($dbcon, $q);

    // all audio files that user uploaded will be deleted as well
    deleteAllAudioFiles($userId);
}


$q = "SELECT user_id, username, email, registration_date, type FROM users ORDER BY user_id ASC";
$users = mysqli_query($dbcon, $q);
$result = mysqli_fetch_all($users, MYSQLI_ASSOC);
exit(json_encode($result));

function bindAndExec($stmt, $markers, $value){
    mysqli_stmt_bind_param($stmt, $markers, $value);
    mysqli_stmt_execute($stmt);
}
