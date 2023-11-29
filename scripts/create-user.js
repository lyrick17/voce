const closeBtn = document.querySelector('.closecreate-btn');
const createWindow = document.querySelector(".create-window");
const createBtn = document.querySelector(".create-btn");
const form = document.getElementById("form");
const submitBtn = document.getElementById("create-user");
const userType = document.getElementById('userType');


//Account information conditions 
const uniqueUserTxt = document.querySelector(".unique-user");
const validUserTxt = document.querySelector(".valid-user");
const validEmailTxt = document.querySelector(".valid-email");
const confirmPassTxt = document.querySelector(".confirm-pass");

//Account information inputs

const username = document.getElementById('username');
const email = document.getElementById('email');
const pword = document.getElementById('pword');
const pword2 = document.getElementById('pword2');

let usernames = [];
let emails = []
fetchUsers();

userType.addEventListener("change", () =>{
    readyToSubmit();
});

form.addEventListener("submit", function(e){
    let fd = new FormData(form);
    fetch('user-table.php', {method: 'POST', body: fd})
    .then((response) => {console.log(response)});
});
username.addEventListener("keyup", () => {
    validateUser(username.value);
});

email.addEventListener("keyup", () => {
    validateEmail(email.value);
});

pword.addEventListener("keyup", () => {
    validatePassword(pword.value, pword2.value);
});

pword2.addEventListener("keyup", () => {
    validatePassword(pword.value, pword2.value);
});

closeBtn.addEventListener("click", () => {
    hideCreateWindow(createWindow);
});

createBtn.addEventListener("click", () => {
    displayCreateWindow(createWindow);
});

function displayCreateWindow(window){
    window.style.visibility = "visible";
}

function readyToSubmit(){
    if(uniqueUserTxt.style.color == "white" &&
    validUserTxt.style.color == "white" &&
    validEmailTxt.style.color == "white" &&
    confirmPassTxt.style.color == "white" &&
    (userType.options[userType.selectedIndex].text == "Admin" ||
    userType.options[userType.selectedIndex].text == "User")){
        submitBtn.disabled = false;
    }
    else{
        submitBtn.disabled = true;
    }
}

//hides confirmation window
function hideCreateWindow(window){
    window.style.visibility = "hidden";
}

// not yet finished, need to check if username is unique
function validateUser(username){
    if(username.length >= 6 && username.length <= 30 && !usernames.includes(username.toLowerCase()))
        uniqueUserTxt.style.color = "white";
    else{
        uniqueUserTxt.style.color = "red";
    }

    const userPattern = /^[\w\-]+$/;

    if(userPattern.test(username)){
        validUserTxt.style.color = "white";
    }
    else{
        validUserTxt.style.color = "red";
    }
    readyToSubmit();
}

// not yet finished, need to check if email is unique
function validateEmail(email){
    const emailPattern = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    if(emailPattern.test(email) && !emails.includes(email)){
        validEmailTxt.style.color = "white";
    }
    else{
        validEmailTxt.style.color = "red";
    }
    readyToSubmit();
}

function validatePassword(password, password2){
    if(password.length >= 8 && password === password2 && password != "")
        confirmPassTxt.style.color = "white";
    else{
        confirmPassTxt.style.color = "red";
    }
    readyToSubmit();

}

function fetchUsers(){
    fetch('user-table.php', {
    headers: {
      'credentials': 'same-origin',
      'X-Requested-With': 'XMLHttpRequest',
      'Content-Type': 'application/json'
       // or 'Content-Type': 'application/x-www-form-urlencoded'
    }})
  .then((res) => res.json())
  .then((response) => {
    console.log(response);

    for(let i = 0; i < response.length; i++){
        usernames.push(response[i]['username'].toLowerCase());
        emails.push(response[i]['email'].toLowerCase());
    }
  })
}

