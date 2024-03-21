<?php 
require ("../mysql/mysqli_session.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require "../phpmailer/src/Exception.php";
require "../phpmailer/src/PHPMailer.php";
require "../phpmailer/src/SMTP.php";

require "smtp.php";

$contact_message = '';
$message = array("Thank you for your Feedback!", 
                "Please fill out the fields. Thank you.", 
                "Message could not be sent. Please Try Again",
                "The character limit is 2,000.");
$contact_color = '';

// user sends out a feedback
if($_SERVER['REQUEST_METHOD'] == "POST") {

    $error = 0;
    $strlen_error = 0;
  
    $_POST['contact_name'] = sanitize_input($_POST['contact_name']);
    $_POST['contact_subject'] = sanitize_input($_POST['contact_subject']);
    $_POST['contact_message'] = sanitize_input($_POST['contact_message']);


    // server-side validation first before sending the email
    $c_name = (!empty($_POST['contact_name'])) ? $_POST['contact_name'] : $error++;
    $c_subject = (!empty($_POST['contact_subject'])) ? $_POST['contact_subject'] : $error++;
    $c_message = (!empty($_POST['contact_message'])) ? $_POST['contact_message'] : $error++;
    if (strlen($c_message) > 2000) { $strlen_error++; $error++; }
    
    
    if ($error > 0) {
        if ($strlen_error == 1) {
            $contact_message = $message[3];
        } else {
            $contact_message = $message[1];
        }
        $contact_color = "red";
    } else {

        try {
            $email = 'voceteam.contact@gmail.com'; // our email, the recipient
            $email_subject = $c_subject;
                $body_content = ["<h3>User Feedback sent from Voce Website</h3>",
                                "<b>Name:</b> {$c_name}", "<br />",
                                "<hr />",
                                "<h4>Message:</h4>", $c_message];
            $body = join(PHP_EOL, $body_content);
            
            // prepare PHPMailer and the content to be sent out onto our email
            $mail = new PHPMailer(true);
    
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = $smtpuser; // gmail account
            $mail->Password = $smtppass; // gmail app password
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;
    
            $mail->setFrom($email, $c_name);     // the sender of the email, in this case, our email  still
            $mail->addAddress($email, 'Voce');   // receiver of email, still our email
            
            $mail->isHTML(true);
    
            $mail->Subject = $email_subject;
            $mail->Body = $body;
    
            $mail->send();
            
            
            // save into db when guest user sends feedback (without userid)
            $query = mysqli_prepare($dbcon, "INSERT INTO contacts(username, subject, message) 
            VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($query, "sss", $c_name, $c_subject, $c_message);
            
            $result = mysqli_stmt_execute($query);


            // show to user that email has been sent
            $contact_message = $message[0];
            $contact_color = "green";

        } catch (Exception $e) {
            $contact_message = $message[2];
            $contact_color = "red";
        }


        
    }

    $json = ['message' => $contact_message, 'color' => $contact_color, 'error' => 0];
    exit(json_encode($json));
}

function sanitize_input($post) {
    global $dbcon;
    $post = trim($post);
    $post = htmlspecialchars($post);
    $post = mysqli_real_escape_string($dbcon, $post);

    return $post;
}



?>
