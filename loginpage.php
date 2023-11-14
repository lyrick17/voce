<?php include("mysql/mysqli_registration.php"); 
    if (isset($_SESSION['username'])) {
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
    <link rel="stylesheet" href="styles/style.css">
    <title>Modern Login Page | AsmrProg</title>
</head>

<body>

    <div class="container" id="container">
        <!-- REGISTER -->
        <div class="form-container sign-up">
            <form action="loginpage.php" method="post">
                <h1>Create Account</h1>
                <div class="social-icons">
                    <span id="usererror" style="color: red; font-style: italic;"><?= $display_errors['user'] ?? ""; ?></span><br />
                    <span id="emailerror" style="color: red; font-style: italic;"><?= $display_errors['email'] ?? ""; ?></span><br />
                    <span id="passerror" style="color: red; font-style: italic;"><?= $display_errors['pass'] ?? ""; ?></span>
                </div>
                
                <!-- This input type determines whether form is REGISTER or login -->
                <input type="hidden" id="formType" name="formType" value="register">
                <input type="text" placeholder="Name" id="username" name="username" required>
                <input type="email" placeholder="Email" id="email" name="email" required>
                <input type="password" placeholder="Password" id="pword" name="pword" required>
                <input type="password" placeholder="Confirm Password" id="pword2" name="pword2" required>
                <input type="submit" id="submit-register" name="submit-register" value="Submit" disabled>
                
            </form>
        </div>
        <!-- LOGIN -->
        <div class="form-container sign-in">
            <form action="loginpage.php" method="post">
                <h1>Sign In</h1>
                <div class="social-icons">
                    <span id="loginerror" style="color: red; font-style: italic;"><?= $display_errors['login'] ?? ""; ?></span>
                </div>
                <!-- This input type determines whether form is register or LOGIN -->
                <input type="hidden" id="formType" name="formType2" value="login">
                
                <input type="text" placeholder="Username/Email" id="user" name="user">
                <input type="password" placeholder="Password" id="pass" name="pass">
                <a href="#">Forget Your Password?</a>
                <input type="submit" id="submit-register" name="submit-register" value="Sign In">
            </form>
        </div>
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>Welcome Back!</h1>
                    <p>Enter your personal details to use all of site features</p>
                    <button class="hidden" id="login">Sign In</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>Hello, Friend!</h1>
                    <p>Register with your personal details to use all of site features</p>
                    <button class="hidden" id="register">Sign Up</button>
                </div>
            </div>
        </div>
    </div>
    <script src="scripts/script.js"></script>
    <script src="scripts/form-validation.js"></script>
    
</body>

</html>
