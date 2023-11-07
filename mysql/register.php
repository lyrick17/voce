<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=no">
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="bootstrap.css">
	<title>Terra</title>
</head>
<body>
    <!-- Header -->
    <nav class="navbar navbar-expand-lg py-0" id="navbar">
        <div class="container-fluid py-sm-2 px-3 px-sm-5">
            <div class="navbar-nav">
                <a class="navbar-brand" href="#">
                    <p class="h1">VOICE</p>
                </a>
            </div>
        </div>
    </nav>

    <!-- Body -->
    <div>
        <!-- Main -->  
        
        <!-- Login/SignUp -->
        <div class="container-fluid signup-section">
            <div class="container p-3 p-md-4">
                <div class="row">

                    <!-- Left Side, Introduction -->
                    <div class="col-md-6" style="padding: 10vh 0;">
                            <p class="front-page-font-sm">I need the </p>
                            <p class="front-page-font-sm"><b>Design</b> and <b>Database</b></p>
                            <p class="front-page-font-sm">para ma-integrate ko na yung forms pati yung connection sa DB</p>
                    </div>
            
                    <!-- Right Side, Login/Register -->
                    <div class="col-md-6">





                        <!-- Login -->
                        <div class="card p-3 p-sm-4 p-lg-5 registerForm collapse" id="login">
                            <form action="register.php" method="post">

                                <!-- This input type determines whether form is register -->
                                <input type="hidden" name="formType" value="login">

                                <p class="text-center h3">Log In</p>


                                <!-- Username / Email -->
                                <div class="form-outline  mb-2">
                                    <label class="form-label" for="loginUser">Username / Email:</label>
                                    
                                    <!-- span element for handling USER ERRORS -->
                                    <span id="loginUserError" class="text-danger" style="font-size: 12px; font-style: italic;"></span>

                                    <input type="text" 
                                            id="loginUser" 
                                            name="loginUser"
                                            placeholder="Email"
                                            class="form-control"> 
                                </div>
                                <!-- Password -->
                                <div class="form-outline  mb-2">
                                    <label class="form-label" for="loginPassword">Password:</label>
                                    
                                    <!-- span element for handling PASSWORD ERRORS -->
                                    <span id="loginPasswordError" class="text-danger" style="font-size: 12px; font-style: italic;"></span>
                                    
                                    <input type="password" 
                                            id="loginPassword" 
                                            name="loginPassword"
                                            placeholder="Password"
                                            class="form-control">
                                </div>
                                <!-- Checkbox -->
                                <div class="mb-2">
                                    <a href="#">Forgot Password?</a>
                                </div>

                                <!-- Submit button -->
                                <!-- Submit button -->
                                <input type="submit" 
                                        id="submitLogin" 
                                        name="submitLogin" 
                                        class="btn btn-primary btn-block mb-3 px-5" 
                                        value="Login"
                                        disabled>
                                <div>
                                    New to Terra? 
                                    
                                    <a href="#" role="button" data-bs-toggle="collapse" data-bs-target="#signup" aria-expanded="false" aria-controls="signup" class="toggle-link">Sign Up</a>
                                </div>
                            </form>
                        </div>




                        <!-- Register -->
                        <!-- Registration Form Request -->
                        <?php include ("mysqli_registration.php"); ?>

                        <div class="card p-3 p-sm-4 p-lg-5 registerForm collapse show" id="signup">

                            <!-- FORM FOR REGISTRATION -->
                            <form action="register.php" method="post">

                                <!-- This input type determines whether form is REGISTER or login -->
                                <input type="hidden" id="formType" name="formType" value="register">
        
                                <p class="text-center h3">Register</p>
                                <!-- Username -->
                                <div class="form-outline  mb-2">
                                    <label class="form-label" for="registerUsername">Username:</label>

                                        <!-- span element for handling USERNAME ERRORS -->
                                        <span id="registerUsernameError" class="text-danger" style="font-size: 12px; font-style: italic;"></span>
                                    
                                    <input type="text" 
                                            id="registerUsername"
                                            name="registerUsername" 
                                            placeholder="Username"
                                            class="form-control" 
                                            value="<?php if (isset($_POST['registerUsername'])) echo $_POST['registerUsername'] ?>"
                                            required>
                                </div>

                                <!-- Email -->
                                <div class="form-outline  mb-2">
                                    <label class="form-label" for="registerEmail">Email:</label>

                                        <!-- span element for handling EMAIL ERRORS -->
                                        <span id="registerEmailError" class="text-danger" style="font-size: 12px; font-style: italic;"></span>

                                    <input type="email" 
                                            id="registerEmail" 
                                            name="registerEmail"
                                            placeholder="Email"
                                            class="form-control" 
                                            value="<?php if (isset($_POST['registerEmail'])) echo $_POST['registerEmail'] ?>">
                                </div>

                                <!-- Password -->
                                <div class="form-outline  mb-2">
                                    <label class="form-label" for="registerPassword">Password:</label>

                                        <!-- span element for handling PASSWORD ERRORS -->
                                        <span id="registerPasswordError" class="text-danger" style="font-size: 12px; font-style: italic;"></span>

                                    <input type="password" 
                                            id="registerPassword" 
                                            name="registerPassword"
                                            placeholder="Password"
                                            class="form-control">
                                </div>
                                <!-- Confirm Password -->
                                <div class="form-outline mb-2">
                                    <label class="form-label" for="registerConfirmPassword">Confirm Password:</label>
                                    <input type="password" 
                                            id="registerConfirmPassword" 
                                            name="registerConfirmPassword"
                                            placeholder="Confirm Password"
                                            class="form-control" >
                                </div>


                                <!-- Checkbox -->
                                <div class="form-check">
                                    <label class="form-check-label" for="registerCheck">
                                        <input class="form-check-input mb-3" 
                                                type="checkbox" 
                                                id="registerCheck"
                                                name="registerCheck"
                                                aria-describedby="registerCheckHelpText" 
                                                required />
                                        I have read and agree to the terms
                                    </label>

                                    <!-- span element for handling CHECKBOX -->
                                    <span id="registerCheckError" class="text-danger" style="font-size: 12px; font-style: italic;"></span>
                                </div>
        
                                <!-- Submit button -->
                                <input type="submit" 
                                        id="submitRegister" 
                                        name="submit" 
                                        class="btn btn-primary btn-block mb-3 px-5" 
                                        value="Register"
                                        disabled>

                                <div>
                                    Already have an account? 
                                    <a href="#login" role="button" data-bs-toggle="collapse" data-bs-target="#login" aria-expanded="false" aria-controls="login" class="toggle-link">Log In</a>
                                </div>
                            </form>
                        </div>






                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- FOOTER -->
    <div class="footer">
        <div class="container pt-4 text-center py-3">
        © 2023 Voice - Group ni Lean, CS301
        </div>
    </div>

    <script src="bootstrap.bundle.js"></script>
    <script src="scripts/form-validation.js"></script>
    <!-- <script src="design.js"></script> -->
    <script>

        // Add event listener to toggle links
        document.querySelectorAll('.toggle-link').forEach(function(link) {
            link.addEventListener('click', function(event) {
                event.preventDefault(); // Prevent the default link behavior
                var target = this.getAttribute('data-bs-target');
                document.querySelectorAll('.registerForm').forEach(function(form) {
                    if (('#' + form.id) === target) {
                        form.classList.add('show'); // Show the target form
                    } else {
                        form.classList.remove('show'); // Hide other forms
                    }
                });
            });
        });
    </script>
    
</body>
</html>