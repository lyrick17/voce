// !!! Dynamic Form Validation for REGISTER !!! 

// TO BE ADDED:
//  - email regex


// checks all input textbox to see if they are empty or invalid by adding Event Listeners 
//  this will not work at the start, but rather would work when the user started using the textboxes
document.getElementById('username').addEventListener("input", allowRegister);
document.getElementById('email').addEventListener("input", allowRegister);
document.getElementById('pword').addEventListener("input", allowRegister);
document.getElementById('pword2').addEventListener("input", allowRegister);

// VARIABLES as Logic Gates. Once these are all true, the user can Register
let regUsernameFilled = (document.getElementById('username').value != "");
let regEmailFilled = (document.getElementById('email').value != "");
let regPasswordFilledMatched = false;

// function checks if all fields are ok to be passed to server and would allow the Register button to be clickable
function allowRegister() {
    // just combine all the checking of each textbox instead of using different functions
    let username = document.getElementById('username').value;
    let email = document.getElementById('email').value;
    let password = document.getElementById('pword').value;
    let cPassword = document.getElementById('pword2').value;

    regUsernameFilled = (username) ? true : false;
    regEmailFilled = (email) ? true : false; // EMAIL REGEX TO BE ADDED

    // check first if password textbox is empty and atleast 8 chars, 
    //  then once user started writing confirm password, automatically compare password and cPassword
    if (!password) {
        regPasswordFilledMatched = false;
    } else if (cPassword && (password != cPassword)) {
        regPasswordFilledMatched = false;
    } else if (!cPassword) {
        regPasswordFilledMatched = false;
    } else {
        regPasswordFilledMatched = true;
    }


    if (regUsernameFilled && regEmailFilled && regPasswordFilledMatched) {
        document.getElementById('submit-register').disabled = false;
        return;
    }
    document.getElementById('submit-register').disabled = true;
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














//------------------------------------------------------------------------------------------------
/* --- This Huge block of code was used for dynamic error handling
   ---  but the current version does not need it
// functions for checking each input text box
function checkRegisterUser() {
    // validates username input textbox by checking
    //  if username textbox is EMPTY or NOT
    let username = document.getElementById('username').value;
    //let errorElement = document.getElementById('username-error');

    regUsernameFilled = (username) ? true : false;
    
    allowRegister();
}

function checkRegisterEmail() {
    // validates email input textbox by consecutively checking
    //  if email textbox is EMPTY or NOT
    //                   is a VALID EMAIL ADDRESS

    let email = document.getElementById('email').value;
    //let errorElement = document.getElementById('email-error');

    regEmailFilled = (email) ? true : false;
    
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
*/
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
//------------------------------------------------------------------------------------------------