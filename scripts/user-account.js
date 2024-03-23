const userText = document.getElementById("user-username");
const emailText = document.getElementById("user-email");

// display user and email

//edit and cancel buttons
const editUsernameBtn = document.getElementById("edit-username-btn");
const cancelUsernameBtn = document.getElementById("close-username-btn");

const editEmailBtn = document.getElementById("edit-email-btn");
const cancelEmailBtn = document.getElementById("close-email-btn");

const editPassBtn = document.getElementById("edit-psword-btn");
const cancelPassBtn = document.getElementById("close-psword-btn");

//submit buttons
const submitUsername = document.getElementById("updateUsername");
const submitEmail = document.getElementById("updateEmail");
const submitPass = document.getElementById("updatePsword");

//Edit divs
const editUsernameDiv = document.getElementById("edit-username-div");
const editEmailDiv = document.getElementById("edit-email-div");
const editPassDiv = document.getElementById("edit-psword-div");

//input statuses
const usernameStatus = document.querySelector(".username-status");

//input boxes 
const inputUser = document.getElementById("username");
const inputEmail = document.getElementById("email");
const inputOldPass = document.getElementById("old-pword");
const inputNewPass = document.getElementById("new-pword");
const inputNewPass2 = document.getElementById("new-pword2");


//navbarusername
const navbarusername = document.getElementById("nav-name");
//input boxes event listeners

inputOldPass.addEventListener("keyup", () => {
    newPass = inputNewPass.value;
    newPass2 = inputNewPass2.value;
    oldPass = inputOldPass.value;
    validatePassword(newPass, newPass2, oldPass);
});

inputNewPass.addEventListener("keyup", () => {
    newPass = inputNewPass.value;
    newPass2 = inputNewPass2.value;
    oldPass = inputOldPass.value;
    validatePassword(newPass, newPass2, oldPass);
});

inputNewPass2.addEventListener("keyup", () => {
    newPass = inputNewPass.value;
    newPass2 = inputNewPass2.value;
    oldPass = inputOldPass.value;
    validatePassword(newPass, newPass2, oldPass);
});


function validatePassword(password, password2, oldPass){

    submitPass.disabled = !(password.length >= 8 && password == password2 && password != "" && oldPass.length >= 8);
    
    // add css hover if submitPass is enabled
    btnhover(submitPass);
}

inputUser.addEventListener("keyup", () => {
    const userPattern = /^[\w\-]+$/;
    let newUsername = inputUser.value;

    submitUsername.disabled = !(newUsername.length > 0 && userPattern.test(newUsername));

    // add css hover if submitUsername is enabled
    btnhover(submitUsername);
});

inputEmail.addEventListener("keyup", () => {
    const emailPattern = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    let newEmail = inputEmail.value;

    submitEmail.disabled = !emailPattern.test(newEmail);

    // add css hover if submitEmail is enabled
    btnhover(submitEmail);
});

//Display and Hide edit div event listeners
editUsernameBtn.addEventListener("click", () => showEditWindow(editUsernameDiv, editUsernameBtn));
cancelUsernameBtn.addEventListener("click", () => hideEditWindow(editUsernameDiv, editUsernameBtn));

editEmailBtn.addEventListener("click", () => showEditWindow(editEmailDiv, editEmailBtn));
cancelEmailBtn.addEventListener("click", () => hideEditWindow(editEmailDiv, editEmailBtn));

editPassBtn.addEventListener("click", () => showEditWindow(editPassDiv, editPassBtn));
cancelPassBtn.addEventListener("click", () => hideEditWindow(editPassDiv, editPassBtn));



function showEditWindow(editDiv, editBtn){
    editDiv.style.display = "block";
    editBtn.style.display = "none";
}

function hideEditWindow(editDiv, editBtn){
    editDiv.style.display = "none";
    editBtn.style.display = "block";
}


function btnhover(submitButton) {
    // add css hover if submitButton is enabled
    if (!submitButton.disabled) {
        submitButton.classList.add("edit-submit-enabled");
    } else {
        submitButton.classList.remove("edit-submit-enabled");
    }
}

