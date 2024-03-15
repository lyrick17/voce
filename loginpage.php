<?php include ("mysql/mysqli_registration.php");
if (isset ($_SESSION['username'])) {
    header("location: dashboard1.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="styles/loginpage.css">
    <title>VoCE - Login</title>
    <link rel="icon" type="image/x-icon" href="images/icon.ico">
</head>

<body>

    <div class="container" id="container">
        <!-- LOGIN -->
        <div class="form-container sign-in">
            <form action="loginpage.php" method="post">
                <h1>Sign In</h1>
                <div class="social-icons">
                    <span id="loginerror" style="color: red; font-style: italic;">
                        <?= $display_errors['login'] ?? ""; ?>
                    </span>
                </div>

                <!-- This input type determines whether form is register or LOGIN -->
                <input type="hidden" id="formType2" name="formType2" value="login">

                <input type="text" placeholder="Username/Email" id="user" name="user">
                <input type="password" placeholder="Password" id="pass" name="pass">
                <a href="#">Forget Your Password?</a>
                <input type="submit" id="submit-login" name="submit-register" value="Sign In">
            </form>
        </div>
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-right">
                    <h1>Welcome Back, Voce Admin.</h1>
                    <p>Enter your personal details to use all of site features.</p>
                </div>
            </div>
        </div>
    </div>
    <script src="scripts/script.js"></script>
    <script src="scripts/form-validation.js"></script>

</body>

</html>