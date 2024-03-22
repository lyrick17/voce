<?php
require ("../mysql/mysqli_session.php");
$current_page = basename($_SERVER['PHP_SELF']);

require "Translator_Functions.php";


$sess_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$email = $_SESSION['email'];
$sess_hashedPass = $_SESSION['pword'];
$usernameerror = "";
$emailerror = "";
$passerror = "";

if (isset ($_GET['e'])) {
    switch ($_GET['e']) {
        // username errors
        case 1:
            $usernameerror = "<style>#edit-username-btn{display:none}#edit-username-div{display:block;}</style><p style = 'color:red'>You are already using this username.</p>";
            break;
        case 2:
            $usernameerror = "<style>#edit-username-btn{display:none}#edit-username-div{display:block;}</style><p style = 'color:red'>This username already exists.</p>";
            break;

        // email errors
        case 3:
            $emailerror = "<style>#edit-email-btn{display:none;}#edit-email-div{display:block;}</style><p style = 'color:red'>You are already using this email.</p>";
            break;
        case 4:
            $emailerror = "<style>#edit-email-btn{display:none;}#edit-email-div{display:block;}</style><p style = 'color:red'>This email is already taken.</p>";
            break;


        // password errors
        case 5:
            $passerror = "<style>#edit-psword-btn{display:none;}#edit-psword-div{display:block;}</style><p style = 'color:red'>Your password must be different from your old password</p>";
            break;
        case 6:
            $passerror = "<style>#edit-psword-btn{display:none;}#edit-psword-div{display:block;}</style><p style = 'color:red'>Your old password is not correct.</p>";
            break;
    }
}

// for username
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset ($_POST['username'])) {

    $newUsername = mysqli_real_escape_string($dbcon, trim($_POST['username']));

    // check if username already exists
    $query = mysqli_prepare($dbcon, "SELECT user_id FROM admin_users where username = ?");
    mysqli_stmt_bind_param($query, "s", $newUsername);
    mysqli_stmt_execute($query);
    mysqli_stmt_bind_result($query, $result);
    mysqli_stmt_fetch($query);

    if ($username == $newUsername) {
        // username error, user didnt change username
        //header("Location: account.php?e=1");
        $exit = ['error' => 1];
        exit(json_encode($exit));
    } elseif ($result > 0) {
        // username error, username already exists
        //header("Location: account.php?e=2");
        $exit = ['error' => 2];
        exit(json_encode($exit));
    } else {
        // no error
        $query = mysqli_prepare($dbcon, "UPDATE admin_users SET username = ?
        WHERE user_id = ?");
        mysqli_stmt_bind_param($query, "ss", $newUsername, $sess_id);
        $result = mysqli_stmt_execute($query);

        $_SESSION['username'] = $newUsername;
        unset($_POST);

        //header("Location: account.php");
        $exit = ['error' => 0, 'username' => $newUsername];
        exit(json_encode($exit));
    }
}

// for email
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset ($_POST['email'])) {
    $newEmail = mysqli_real_escape_string($dbcon, trim($_POST['email']));

    // check if email already exists
    $query = mysqli_prepare($dbcon, "SELECT user_id FROM admin_users where email = ?");
    mysqli_stmt_bind_param($query, "s", $newEmail);
    mysqli_stmt_execute($query);
    mysqli_stmt_bind_result($query, $result);
    mysqli_stmt_fetch($query);


    if ($email == $newEmail) {
        // email error, user type his same email
        //header("Location: account.php?e=3");
        $exit = ['error' => 3];
        exit(json_encode($exit));
    } elseif ($result > 0) {
        // email error, email already exists
        //header("Location: account.php?e=4");
        $exit = ['error' => 4];
        exit(json_encode($exit));
    } else {
        // no error
        $query = mysqli_prepare($dbcon, "UPDATE admin_users SET email = ?
        WHERE user_id = ?");
        mysqli_stmt_bind_param($query, "ss", $newEmail, $sess_id);
        $result = mysqli_stmt_execute($query);

        $_SESSION['email'] = $newEmail;
        unset($_POST);

        //header("Location: account.php");
        $exit = ['error' => 0, 'email' => $newEmail];
        exit(json_encode($exit));
    }

}

// for changing password
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset ($_POST['new-pword'])) {
    $oldPass = mysqli_real_escape_string($dbcon, trim($_POST['old-pword']));

    $newPass = mysqli_real_escape_string($dbcon, trim($_POST['new-pword']));
    $hashedPass = password_hash($newPass, PASSWORD_BCRYPT);

    if ($oldPass == $newPass) {
        // pass error, user didnt change password
        //header("Location: account.php?e=5");
        $exit = ['error' => 5];
        exit(json_encode($exit));
    } elseif (password_verify($oldPass, $sess_hashedPass)) {
        // no error
        $query = mysqli_prepare($dbcon, "UPDATE admin_users SET pword = ?
        WHERE user_id = ?");
        mysqli_stmt_bind_param($query, "ss", $hashedPass, $sess_id);
        $result = mysqli_stmt_execute($query);

        $_SESSION['pword'] = $hashedPass;
        unset($_POST);

        //header("Location: account.php");
        $exit = ['error' => 0];
        exit(json_encode($exit));
    } else {
        // pass error, old password is wrong
        //header("Location: account.php?e=6");
        $exit = ['error' => 6];
        exit(json_encode($exit));
    }
}
?>