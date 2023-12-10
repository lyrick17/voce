<?php 


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require "phpmailer/src/Exception.php";
require "phpmailer/src/PHPMailer.php";
require "phpmailer/src/SMTP.php";

require "smtp.php";

$contact_message = '';
$message = array("Thank you for your Feedback!", 
                "Please fill out the fields. Thank you.", 
                "Message could not be sent. Please Try Again");
$contact_color = '';

// user sends out a feedback
if($_SERVER['REQUEST_METHOD'] == "POST") {

    $error = 0;

    $_POST['contact_name'] = sanitize_input($_POST['contact_name']);
    $_POST['contact_subject'] = sanitize_input($_POST['contact_subject']);
    $_POST['contact_message'] = sanitize_input($_POST['contact_message']);

    // server-side validation first before sending the email
    $c_name = (!empty($_POST['contact_name'])) ? $_POST['contact_name'] : $error++;
    $c_subject = (!empty($_POST['contact_subject'])) ? $_POST['contact_subject'] : $error++;
    $c_message = (!empty($_POST['contact_message'])) ? $_POST['contact_message'] : $error++;

    if ($error > 0) {
        $contact_message = $message[1];
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

            // show to user that email has been sent
            $contact_message = $message[0];
            $contact_color = "green";

        } catch (Exception $e) {
            $contact_message = $message[2];
            $contact_color = "red";
        }
        
    }


}

function sanitize_input($post) {
    global $dbcon;
    $post = trim($post);
    $post = htmlspecialchars($post);
    $post = mysqli_real_escape_string($dbcon, $post);
    return $post;
}



?>
