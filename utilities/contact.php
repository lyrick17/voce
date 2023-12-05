<?php 


// user sends out a feedback
if($_SERVER['REQUEST_METHOD'] == "POST") {

    // bukas ko na ituloy
   
    // 1
    $username = $_POST['contact_name'];
    $email = $_POST['contact_email'];
    $password = $_POST['contact_subject'];
    $password = $_POST['contact_message'];


    // 2
    $usernameCheck = "SELECT * FROM `users` WHERE username = '" . $username . "'";
    $usernameResult = mysqli_query($dbcon, $usernameCheck);
    if ($usernameResult) {

    }

    // 3
    if (empty($_POST['username'])) {
        $display_errors['user'] = error_message("user-1"); $error++;
    } elseif (strlen($_POST['username']) < 6 || strlen($_POST['username']) > 30) {
        $display_errors['user'] = error_message("user-2"); $error++;
    } elseif (mysqli_num_rows($usernameResult) >= 1) {
        $display_errors['user'] = error_message("user-3"); $error++;
    }

    if (empty($_POST['email'])) {
        $display_errors['email'] = error_message("email-1"); $error++;
    } elseif (strlen($_POST['email']) > 100) {
        $display_errors['email'] = error_message("email-2"); $error++;
    } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $display_errors['email'] = error_message("email-3"); $error++;
    } elseif (mysqli_num_rows($emailResult) >= 1) {
        $display_errors['email'] = error_message("email-4"); $error++;
    }
}

?>