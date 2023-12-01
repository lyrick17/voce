// !!! Dynamic Form Validation for REGISTER !!! 

// checks all input textbox to see if they are empty or invalid by adding Event Listeners 
//  this will not work at the start, but rather would work when the user started using the textboxes
document.getElementById('username').addEventListener("input", checkRegisterUser);
document.getElementById('email').addEventListener("input", checkRegisterEmail);
document.getElementById('pword').addEventListener("input", checkRegisterPassword);
document.getElementById('pword2').addEventListener("input", checkRegisterPassword);

// VARIABLES as Logic Gates. Once these are all true, the user can Register
let regUsernameFilled = false;
let regEmailFilled = false;
let regPasswordFilledMatched = false;

let errorElement = "";

// function checks if all fields are ok to be passed to server and would allow the Register button to be clickable
function allowRegister() {
    let submitButton = document.getElementById('submit-register');
    // user passed validation
    if (regUsernameFilled && regEmailFilled && regPasswordFilledMatched) {
        submitButton.disabled = false;
        submitButton.classList.add("submit-register-allow");
        submitButton.style.cursor = "pointer";
        return;
    }
    
    
    // user failed validation
    submitButton.disabled = true;
    
    submitButton.classList.remove("submit-register-allow");
    submitButton.style.cursor = "";
}

function checkRegisterUser() {
    // check if empty and alphanumeric

    let username = document.getElementById('username').value;
    let errorElement = document.getElementById('usererror');

    if (!username) {
        regUsernameFilled = false;
        errorElement.innerHTML = "*please enter your username";
    } else if (!/^[\w\-]+$/.test(username)) {
        regUsernameFilled = false;
        errorElement.innerHTML = "*username must only be letters, digits, - and _";
    } else {
        regUsernameFilled = true;
        errorElement.innerHTML = " ";
    }
    
    allowRegister();
}

function checkRegisterEmail() {
    // check if email is empty

    let email = document.getElementById('email').value;
    let errorElement = document.getElementById('emailerror');

    regEmailFilled = (email) ? true : false;
    errorElement.innerHTML = (regEmailFilled) ? "" : "*please enter your email";

    checkRegisterUser();
}

function checkRegisterPassword() {
    // check if password is empty, less than 8 char and psw matched

    let password = document.getElementById('pword').value;
    let cPassword = document.getElementById('pword2').value;
    let errorElement = document.getElementById('passerror');

    if (!password) {
        regPasswordFilledMatched = false;
        errorElement.innerHTML = "*please enter your password";
    } else if (password.length < 8) {
        regPasswordFilledMatched = false;
        errorElement.innerHTML = "*password must be atleast 8 characters";
    } else if (cPassword && (password != cPassword)) {
        regPasswordFilledMatched = false;
        errorElement.innerHTML = "*passwords do not match";
    } else if (!cPassword) {
        regPasswordFilledMatched = false;
        errorElement.innerHTML = "";
    } else {
        regPasswordFilledMatched = true;
        errorElement.innerHTML = "";
    }

    checkRegisterEmail();
}


// checks if there is error in register form when user has submitted it in server side
// automatically animates to showing the create account form.
window.addEventListener('load', function() {
    let userError = document.getElementById('usererror').innerHTML;
    let emailError = document.getElementById('emailerror').innerHTML;
    let passError = document.getElementById('passerror').innerHTML;
    if (userError || emailError || passError) {
        this.document.getElementById('container').classList.add("active");
    }
});