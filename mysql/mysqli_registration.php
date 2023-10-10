<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require("mysqli_connect.php");


// Registration Form Request; isset function makes sure the form submitted is for registration

// variables used on server-side error handling 
$param_error = array("usernameTaken" => false,
                            "emailTaken" => false,
                            "emailValid" => true);
$display_errors = array("username" => "",
                        "email" => "",
                        "password" => "");

$loginError = "";


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['formType']) == 'register') {
    $error = 0;

    // 1 get all the values input first and protection from sql injection, then
    // 2 get username and email from database, to avoid same data
    // 3 before validating it if its empty or user/email already taken or password matched

    // 1
    $username = mysqli_real_escape_string($dbcon, trim($_POST['username']));
    $email = mysqli_real_escape_string($dbcon, trim($_POST['email']));
    $password = mysqli_real_escape_string($dbcon, trim($_POST['pword']));
    $usertype = "user";
    $hashedPass = md5($password);

    // 2
    $usernameCheck = "SELECT * FROM `users` WHERE username = '" . $username . "' ";
    $usernameResult = mysqli_query($dbcon, $usernameCheck);
    $emailCheck = "SELECT * FROM `users` WHERE email = '" . $email . "' ";
    $emailResult = mysqli_query($dbcon, $emailCheck);

    // 3
    if (empty($_POST['username'])) {
        $display_errors["username"] = " *please enter your username"; $error++;
    } else if (mysqli_num_rows($usernameResult) >= 1) {
        $display_errors['username'] = " *username already taken"; $error++;
        $param_error['usernameTaken'] = true;
    }

    if (empty($_POST['email'])) {
        $display_errors["email"] = " *please enter your email"; $error++;
    } else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $display_errors["email"] = " *email not valid"; $error++;
        $param_error['emailValid'] = false;
    } else if (mysqli_num_rows($emailResult) >= 1) {
        $display_errors['email'] = " *email already taken"; $error++;
        $param_error['emailTaken'] = true;
    }

    if (empty($_POST['pword'])) {
        $display_errors["password"] = " *please enter your password"; $error++;
    } else if ($_POST['pword'] != $_POST['pword2']) {
        $display_errors["password"] = " *passwords do not match"; $error++;
    }

    if ($error == 0) {
        $query = mysqli_prepare($dbcon, "INSERT INTO users(username, email, pword, type, registration_date) 
                                        VALUES (?, ?, ?, ?, NOW())");
        mysqli_stmt_bind_param($query, "ssss", $username, $email, $hashedPass, $usertype);
        $result = mysqli_stmt_execute($query);

        if ($result) {
            // register is successful

            // set the session variables
            //$result = mysqli_stmt_get_result($query);
            //$_SESSION = mysqli_fetch_array($result, MYSQLI_ASSOC);
            
            // set the session variables - manually
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            $_SESSION['usertype'] = $usertype;

            header("location: dashboard.php");
            mysqli_free_result($result);
            mysqli_close($dbcon);
            exit();

        } else {
            echo " q<script>alert('System Error. \\nPlease try again or contact the System Administrator. We apologize for the inconvenience');</script>";
            echo " q<script>alert('Error: ". mysqli_error($dbcon) ."');</script>";
        }
        mysqli_close($dbcon);
        

    } else {
        //determines what is the server-side error
        header("location: index.php?usernametaken=". (int) $param_error['usernameTaken'] ."&emailtaken=". (int) $param_error['emailTaken'] ."&emailvalid=". (int) $param_error['emailValid']);
        exit();
    }

    mysqli_close($dbcon);
}



// Login Form Request; isset function makes sure the form submitted is for login

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['formType2']) == 'login') {

    // variable that will handle the errors and hold error messages
    $errors = array();


    // validate USERNAME / EMAIL
    if (!empty($_POST['//'])) {
        $username = trim($_POST['//']);
        
        // protection from SQL Injections
        $sanitized_username = mysqli_real_escape_string($dbcon, $username);
    } else {
        $errors[] = "Username";
        $loginError = " *invalid username or password";
    }

    // validate PASSWORD
    if (!empty($_POST['//'])) {
        $email = trim($_POST['//']);
        
        // protection from SQL Injections
        $sanitized_email = mysqli_real_escape_string($dbcon, $email);


    } else {
        $errors[] = "Email Address";
        $loginError = " *invalid username or password";
    }

    // validate PASSWORD and CONFIRM PASSWORD
    if (!empty($_POST['pword'])) {
        if ($_POST['pword'] != $_POST['pword2']) {
            $errors[] = "Your passwords did not match";
        } else {
            $pass = trim($_POST['pword']);

            // protection from SQL Injections
            $sanitized_pass = mysqli_real_escape_string($dbcon, $pass);

        }
    } else {
        $errors[] = "Password";
    }

    if (empty($errors)) {
        

        $query = "INSERT INTO users(username, email, pword, type, registration_date) 
        VALUES ('$sanitized_username',  
                '$sanitized_email', 
                md5($sanitized_pass), 
                'user',
                NOW())";


        $query = mysqli_prepare($dbcon, "INSERT INTO users(username, email, pword, type, registration_date) 
                                        VALUES (?, ?, ?, ?, NOW())");

        mysqli_stmt_bind_param($query, "ssss", $sanitized_username, $sanitized_email, md5($sanitized_pass), 'user');

        
        $result = mysqli_stmt_execute($query);
        #$result = @mysqli_query($dbcon, $query);

        if ($result) {
            // register is successful

            // set the session variables
            $_SESSION['username'] = $sanitized_username;
            $_SESSION['email'] = $sanitized_email;


            
            echo "<script> alert('Goods') </script>";
            header("location: dashboard.php");
            exit();
        } else {
            echo " q<script>alert('System Error. \\nPlease try again or contact the System Administrator. We apologize for the inconvenience');</script>";
        }
        mysqli_close($dbcon);
        

    } else {
        echo "<script>alert('You forgot to enter your: \\n";
        foreach ($errors as $msg) {
            echo " - $msg\\n";
        }
        echo "Please try again'); </script>";
    }
}


?>