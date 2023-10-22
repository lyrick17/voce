// !!! Dynamic Form Validation for REGISTER !!! 

// TO BE ADDED:
//  - email regex


// checks all input textbox to see if they are empty or invalid by adding Event Listeners 
//  this will not work at the start, but rather would work when the user started using the textboxes
document.getElementById('username').addEventListener("input", checkRegisterUser);
document.getElementById('email').addEventListener("input", checkRegisterEmail);
document.getElementById('pword').addEventListener("input", checkRegisterPassword);
document.getElementById('pword2').addEventListener("input", checkRegisterPassword);

// VARIABLES as Logic Gates. Once these are all true, the user can Register
let regUsernameFilled = (document.getElementById('username').value != "");
let regEmailFilled = (document.getElementById('email').value != "");
let regPasswordFilledMatched = false;

// functions for checking each input text box
function checkRegisterUser() {
    // validates username input textbox by checking
    //  if username textbox is EMPTY or NOT
    let username = document.getElementById('username').value;
    //let errorElement = document.getElementById('username-error');

    regUsernameFilled = (username) ? true : false;
    //errorElement.innerHTML = (username) ? "" : " *please enter your username"; // display the error message if username empty

    allowRegister();
}

function checkRegisterEmail() {
    // validates email input textbox by consecutively checking
    //  if email textbox is EMPTY or NOT
    //                   is a VALID EMAIL ADDRESS

    let email = document.getElementById('email').value;
    //let errorElement = document.getElementById('email-error');

    regEmailFilled = (email) ? true : false;
    //errorElement.innerHTML = (email) ? "" : " *please enter your email";
    // EMAIL REGEX TO BE ADDED
    allowRegister();
}

function checkRegisterPassword() {
    // validates password input textbox by consecutively checking
    //  if pass textbox is EMPTY or NOT
    //                  is less than 8 characters or NOT
    //                  matches the confirm pass textbox

    let password = document.getElementById('pword').value;
    let cPassword = document.getElementById('pword2').value;
    //let errorElement = document.getElementById('pword-error');

    // check first if password textbox is empty and atleast 8 chars, 
    //  then once user started writing confirm password, automatically compare password and cPassword
    if (!password) {
        //errorElement.innerHTML = " *please enter your password";
        regPasswordFilledMatched = false;
    //} else if (password.length < 8) {
        //errorElement.innerHTML = " *must be atleast 8 characters";
        //regPasswordFilledMatched = false;
    } else if (cPassword && (password != cPassword)) {
        //errorElement.innerHTML = " *passwords do not match";
        regPasswordFilledMatched = false;
    } else if (!cPassword) {
        //errorElement.innerHTML = "";
        regPasswordFilledMatched = false;
    } else {
        //errorElement.innerHTML = "";
        regPasswordFilledMatched = true;
    }
    allowRegister();
}

// function checks if all fields are ok to be passed to server and would allow the Register button to be clickable
function allowRegister() {
    if (regUsernameFilled && regEmailFilled && regPasswordFilledMatched) {
        document.getElementById('submit-register').disabled = false;
        return;
    }
    document.getElementById('submit-register').disabled = true;
}


// for server-side errors, immediately show modal after webpage refresh
/*
window.addEventListener('load', function() {
    let params = new URLSearchParams(window.location.search);
    let myModal = new bootstrap.Modal(document.getElementById('enroll'));

    $error = false;
    if (params.get('usernametaken') == '1') {
        console.log("eyy");
        $error = true;
        this.document.getElementById('username-error').innerHTML = " *username already taken";
    }
    if (params.get('emailtaken') == '1') {
        $error = true;
        this.document.getElementById('email-error').innerHTML = " *email already exists";
    }
    if (params.get('emailvalid') == '0') {
        $error = true;
        this.document.getElementById('email-error').innerHTML = " *email invalid";
    
    }
    if ($error) 
        myModal.show();

});*/

// next thing to do is login validation and addition of logout.php

// !!! Dynamic Form Validation for LOGIN !!! 

// checks all input textbox to see if they are empty or invalid by adding Event Listeners 
//  this will not work at the start, but rather would work when the user started using the textboxes
/*document.getElementById('loginUser').addEventListener("input", checkLoginUser);
document.getElementById('loginPassword').addEventListener("input", checkLoginPassword);

// VARIABLES as Logic Gates. Once these are all true, the user can Login
let logUserFilled = false;
let logPasswordFilled = false;

// functions for checking user and pass textbox
function checkLoginUser() {
    // validates user input textbox by checking
    //  if user textbox is EMPTY or NOT
    let user = document.getElementById('loginUser').value;
    let errorElement = document.getElementById('loginUserError');

    if (!user) {
        errorElement.innerHTML = " *username/email required"; // automatically display the error message
    } else {
        errorElement.innerHTML = ""; // clear our the error message when textbox is not empty
    }

}

function checkLoginPassword() {
    // validates password input textbox by consecutively checking
    //  if pass textbox is EMPTY or NOT
    //                  is less than 8 characters or NOT
    //                  matches the confirm pass textbox

    let password = document.getElementById('loginPassword').value;
    let errorElement = document.getElementById('loginPasswordError');

    // check first if password textbox is empty and atleast 8 chars, 
    //  then once user started writing confirm password, automatically compare password and cPassword
    if (!password) {
        errorElement.innerHTML = " *password required";
    } else {
        errorElement.innerHTML = "";
    }
}
*/
