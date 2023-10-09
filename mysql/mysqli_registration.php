<?php 

session_start();

require("mysqli_connect.php");


// Registration Form Request; isset function makes sure the form submitted is for registration

/* TO BE ADDED:
    - validate username if already exists 
    - email is already taken
    - passwords do not match (we validate it again after validating using javascript just to be sure)
    
    - cannot be done unless we already have the database

*/
$usernameError = "";
$emailError = "";
$passwordError = "";

$loginError = "";


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['formType']) == 'register') {

    // variable that will handle the errors and hold error messages
    $errors = array();

    // validate USERNAME, EMAIL ADDRESS and PASSWORD on server side
    //  && apply protection from SQL injections (using mysqli_real_escape_string)
    if (!empty($_POST['username'])) {
        $username = trim($_POST['username']);
        
        // protection from SQL Injections
        $sanitized_username = mysqli_real_escape_string($dbcon, $username);

        // check if username already exists
        $usernameCheck = "SELECT * FROM `users` WHERE username = '" . $sanitized_username . "' ";
        $usernameResult = mysqli_query($dbcon, $usernameCheck);
        if (mysqli_num_rows($usernameResult) >= 1) {
            $usernameError = " *username already taken";
            $errors[] = "Username";
        }

    } else {
        $errors[] = "Username";
        $usernameError = " *please enter your username";
    }

    // validate EMAIL ADDRESS
    if (!empty($_POST['email'])) {
        $email = trim($_POST['email']);
        
        // protection from SQL Injections
        $sanitized_email = mysqli_real_escape_string($dbcon, $email);

        // check if email already exists
        if (!filter_var($sanitized_email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Email Address";
            $emailError = " *email already taken";
        }

    } else {
        $errors[] = "Email Address";
        $emailError = " *please enter your password";
    }

    // validate PASSWORD and CONFIRM PASSWORD
    if (!empty($_POST['pword'])) {
        if ($_POST['pword'] != $_POST['pword2']) {
            $errors[] = "Password";
            $passwordError = " *passwords do not match";
        } else {
            $pass = trim($_POST['pword']);

            // protection from SQL Injections
            $sanitized_pass = mysqli_real_escape_string($dbcon, $pass);

        }
    } else {
        $errors[] = "Password";
        $passwordError = " *please enter your password";
    }

    if (empty($errors)) {
        $usertype = "user";

        $query = "INSERT INTO users(username, email, pword, type, registration_date) 
        VALUES ('$sanitized_username',  
                '$sanitized_email', 
                md5($sanitized_pass), 
                'user',
                NOW())";


        $query = mysqli_prepare($dbcon, "INSERT INTO users(username, email, pword, type, registration_date) 
                                        VALUES (?, ?, ?, ?, NOW())");

        mysqli_stmt_bind_param($query, "ssss", $sanitized_username, $sanitized_email, md5($sanitized_pass), $usertype);

        
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
        echo "<script>alert('You have error on: \\n";
        foreach ($errors as $msg) {
            echo " - $msg\\n";
        }
        echo "Please try again'); </script>";
    }
}



// Login Form Request; isset function makes sure the form submitted is for login


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['formType']) == 'login') {

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