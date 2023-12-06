<?php 

$contact_message = '';
$message = array("Thank you for your Feedback!", "Error. Please try again. Thank you.");

// user sends out a feedback
if($_SERVER['REQUEST_METHOD'] == "POST") {

    $error = 0;
    // server-side validation first before sending the email
    $c_name = (!empty($_POST['contact_name'])) ? $_POST['contact_name'] : $error++;
    $c_email = (!empty($_POST['contact_email'])) ? $_POST['contact_email'] : $error++; // the sender email
    if (!filter_var($_POST['contact_email'], FILTER_VALIDATE_EMAIL)) { $error++; }
    $c_subject = (!empty($_POST['contact_subject'])) ? $_POST['contact_subject'] : $error++;
    $c_message = (!empty($_POST['contact_message'])) ? $_POST['contact_message'] : $error++;

    if ($error > 0) {
        $contact_message = $message[0];
    } else {
        // provide the content to be sent out onto our email
        $to_email = 'example@example.com'; // our email, the recipient
        $email_subject = $c_subject;
        $headers = ['From' => $email, 'Reply-To' => $email, 'Content-type' => 'text/html; charset=utf-8'];
        $body_content = ["Name: {$c_name}", "Email: {$c_email}", "Message:", $c_message];
        $body = join(PHP_EOL, $bodyContent);

        if (mail($to_email, $email_subject, $body, $headers) {
            $contact_message = $message[1];
        } else {
            $contact_message = $message[0];
        }
    }

}

?>
