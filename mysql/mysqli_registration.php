<?php 


// This file is possible to  be removed, as login is not needed anymore


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require("mysqli_connect.php");
require("mysqli_logs.php");

// file if user/admin is not logged in


// Registration Form Request; isset function makes sure the form submitted is for registration



$display_errors = array();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['formType'])) {
    $error = 0;

    // 1 get all the values input first and protection from sql injection, then
    // 2 get username and email from database, to avoid same data
    // 3 before validating it if its empty or user/email already taken or password matched

    // 1
    $username = mysqli_real_escape_string($dbcon, trim($_POST['username']));
    $email = mysqli_real_escape_string($dbcon, trim($_POST['email']));
    $password = mysqli_real_escape_string($dbcon, trim($_POST['pword']));
    $hashedPass = password_hash($password, PASSWORD_BCRYPT);


    // 2
    $usernameCheck = "SELECT * FROM `users` WHERE username = '" . $username . "' ";
    $usernameResult = mysqli_query($dbcon, $usernameCheck);
    $emailCheck = "SELECT * FROM `users` WHERE email = '" . $email . "' ";
    $emailResult = mysqli_query($dbcon, $emailCheck);

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

    if (empty($_POST['pword'])) {
        $display_errors['pass'] = error_message("pass-1"); $error++;
    } elseif ($_POST['pword'] != $_POST['pword2']) {
        $display_errors['pass'] = error_message("pass-2"); $error++;
    }

    if ($error == 0) {
        $query = mysqli_prepare($dbcon, "INSERT INTO admin_users(username, email, pword, registration_date) 
                                        VALUES (?, ?, ?, NOW())");
        mysqli_stmt_bind_param($query, "ssss", $username, $email, $hashedPass);
        $result = mysqli_stmt_execute($query);

        if ($result) {
            // register is successful
            
            // get the new user info from db, with the user_id
            $get_user_query = "SELECT * FROM admin_users WHERE (`username` = '". $username ."' AND `pword` = '". $hashedPass . "')";

            $userresult = @mysqli_query($dbcon, $get_user_query);

            $_SESSION = mysqli_fetch_array($userresult, MYSQLI_ASSOC);

            //$user_id = mysqli_query($dbcon, "SELECT user_id FROM users WHERE username = '$username'
            //    and email = '$email'");

            //$_SESSION['user_id'] = mysqli_fetch_assoc($user_id);
            //$_SESSION['username'] = $username;
            //$_SESSION['email'] = $email;
            //$_SESSION['usertype'] = $usertype;

            // record/log it
            logs("register", $username, $dbcon);

            header("location: dashboard1.php");
            mysqli_free_result($result);
            mysqli_close($dbcon);
            exit();

        } else {
            echo " q<script>alert('System Error. \\nPlease try again or contact the System Administrator. We apologize for the inconvenience');</script>";
            echo " q<script>alert('Error: ". mysqli_error($dbcon) ."');</script>";
        }
        mysqli_close($dbcon);
        

    }

}


// Login Form Request; isset function makes sure the form submitted is for login

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['formType2'])) {

    $username = (!empty($_POST['user'])) ? mysqli_real_escape_string($dbcon, trim($_POST['user'])) : FALSE;
    $password = (!empty($_POST['pass'])) ? mysqli_real_escape_string($dbcon, trim($_POST['pass'])) : FALSE;
    
    if ($username && $password) {

        $q1 = "SELECT * FROM users WHERE (`username` = '". $username ."' OR `email` = '". $username ."')";
        //$q1 = "SELECT * FROM admin_users WHERE (`username` = '". $username ."' OR `email` = '". $username ."')";
        $result1 = @mysqli_query($dbcon, $q1);
        
        
        $user_row = (mysqli_num_rows($result1) == 1) ? mysqli_fetch_array($result1, MYSQLI_ASSOC) : false;
        
        if ($user_row) {
            $hashed_pass = $user_row['pword'];
            
            if (password_verify($password, $hashed_pass)) {
                $_SESSION = $user_row;
    
                logs("login", $_SESSION['username'], $dbcon);  
                
                mysqli_free_result($result1);
                mysqli_close($dbcon);
                
                header("location: dashboard1.php");
                exit();
            } else {
                $display_errors['login']  = error_message("invalid-login");
            }

        } else {
            $display_errors['login'] = error_message("invalid-login");
        }
    } else {
        $display_errors['login']  = error_message("invalid-login");
    }
}





// error messages on server-side error handling 
function error_message($error) {
    switch ($error) {
        case "user-1":
            return " *please enter your username";
            break;
        case "user-2":
            return " *username must be 6-30 characters only";
            break;
        case "user-3":
            return " *username already taken";
            break;
        case "email-1":
            return " *please enter your email";
            break;
        case "email-2":
            return " *email maximum only 100 characters";
            break;
        case "email-3":
            return " *email not valid";
            break;
        case "email-4":
            return " *email already taken";
            break;
        case "pass-1":
            return " *please enter your password";
            break;
        case "pass-2":
            return " *passwords do not match";
            break;
        case "invalid-login":
            return "invalid username/email or password";
            break;
    }
}
?>
