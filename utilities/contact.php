<?php 

$contact_error = '';
// user sends out a feedback
if($_SERVER['REQUEST_METHOD'] == "POST") {

    // 
    // 1

    $error = 0;

    $c_name = (!empty($_POST['contact_name'])) ? $_POST['contact_name'] : $error++;
    $c_email = (!empty($_POST['contact_email'])) ? $_POST['contact_email'] : $error++;
    $c_subject = (!empty($_POST['contact_email'])) ? $_POST['contact_subject'] : $error++;
    $c_message = (!empty($_POST['contact_email'])) ? $_POST['contact_message'] : $error++;

    if (!filter_var($_POST['contact_email'], FILTER_VALIDATE_EMAIL)) { $error++; }

    if ($error > 0) {
        $contact_error = "Please fill out all the fields. Thank you";
    }
}

?>