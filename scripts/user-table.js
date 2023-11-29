let closeBtn = document.querySelector('.closecreate-btn');
let createWindow = document.querySelector(".create-window");
let createBtn = document.querySelector(".create-btn");
let form = document.getElementById("form");
let submitBtn = document.getElementById("create-user");
let userType = document.getElementById('userType');


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


//stores list of all delete 
let mysession = document.querySelector('.mysession');
let sessionId = mysession.getAttribute('id');
let deleteWindow = document.querySelector(".delete-window");
let deleteBtn = document.querySelectorAll(".delete-user");
let noBtn = document.querySelector(".confirm-no");
let yesBtn = document.querySelector(".confirm-yes");
let usersTable = document.querySelector(".users-table");
let confirmText = document.querySelector(".confirm-text");
//adds event listener to all delete buttons

//initialize form data content
let userId = 0;


for(let i = 0; i <deleteBtn.length;i++){
    deleteBtn[i].addEventListener("click", (e) => {
    confirmText.innerHTML = "Are you sure you want to delete this user?";

    //Sets the user to be deleted.
    userId = e.target.parentNode.parentNode.id;
    displayConfirmWindow(deleteWindow);
    });
}

userType.addEventListener("change", () =>{
    readyToSubmit();
});

form.addEventListener("submit", function(e){
    e.preventDefault();
    let fd = new FormData(form);
    fetch('user-table.php', {method: 'POST', body: fd})
    .then((res) => res.json()) // Converts response to JSON
    .then(response => {
        console.log(response);
        let updatedUsers ='<tr><td class = "create-cell"><button class = "table-btn create-btn">Create User</button></td></tr><tr><th class = "data">User ID</th><th>Username</th><th>Email</th><th>Registration Date</th><th>Type</th><th colspan = 3>Actions</th></tr>';

        //add rows to new users table
        for(let i = 0; i < response.length; i++){
            let obj = response[i];
            updatedUsers += setNewRow(obj);
        }



        //updates history content.
        usersTable.innerHTML = updatedUsers;

        //Truncates text after history is updated
        //re-initializes delete variables after updating the history.
        deleteWindow = document.querySelector(".delete-window");
        closeBtn = document.querySelector('.closecreate-btn');
        createWindow = document.querySelector(".create-window");
        createBtn = document.querySelector(".create-btn");
        form = document.getElementById("form");
        submitBtn = document.getElementById("create-user");
        userType = document.getElementById('userType');
        let deleteBtn = document.querySelectorAll(".delete-user");
        userId = 0;
        username.value = "";
        email.value = "";
        pword.value = "";
        pword2.value = "";
        uniqueUserTxt.style.color = "red";
        validUserTxt.style.color = "red";
        validEmailTxt.style.color = "red";
        confirmPassTxt.style.color = "red";
        resetUserType();
        submitBtn.disabled = true;

        //re-adds eventlisteners to new delete buttons.
        for(let i = 0; i <deleteBtn.length;i++){
            deleteBtn[i].addEventListener("click", (e) => {
            confirmText.innerHTML = "Are you sure you want to delete this user?";
            userId = e.target.parentNode.parentNode.id;
            displayConfirmWindow(deleteWindow);
        });

        closeBtn.addEventListener("click", () => {
            hideCreateWindow(createWindow);
        });
        
        createBtn.addEventListener("click", () => {
            displayCreateWindow(createWindow);
        });
    }
    hideCreateWindow(createWindow);    
    });

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


//Hides delete confirmation window if user clicks no
noBtn.addEventListener("click", () => {
    hideConfirmWindow(deleteWindow);
});


//Deletes row and updates user history
yesBtn.addEventListener("click", () => {

    //Deletes row if user id is not the current user's id
    if(sessionId != userId){
        let fd = new FormData();
        fd.append('userId', userId);
        fetch('delete_user.php',{
            method : 'post', 
            body: fd})
            .then((res) => res.json()) // Converts response to JSON
            .then(response => {
                console.log(response);
                let updatedUsers ='<tr><td class = "create-cell"><button class = "table-btn create-btn">Create User</button></td></tr><tr><th class = "data">User ID</th><th>Username</th><th>Email</th><th>Registration Date</th><th>Type</th><th colspan = 3>Actions</th></tr>';

                //add rows to new users table
                for(let i = 0; i < response.length; i++){
                    let obj = response[i];
                    updatedUsers += setNewRow(obj);
                }



                //updates history content.
                usersTable.innerHTML = updatedUsers;

                //Truncates text after history is updated
                //re-initializes delete variables after updating the history.
                deleteWindow = document.querySelector(".delete-window");
                closeBtn = document.querySelector('.closecreate-btn');
                createWindow = document.querySelector(".create-window");
                createBtn = document.querySelector(".create-btn");
                form = document.getElementById("form");
                submitBtn = document.getElementById("create-user");
                userType = document.getElementById('userType');
                let deleteBtn = document.querySelectorAll(".delete-user");
                userId = 0;
                username.value = "";
                email.value = "";
                pword.value = "";
                pword2.value = "";
                uniqueUserTxt.style.color = "red";
                validUserTxt.style.color = "red";
                validEmailTxt.style.color = "red";
                confirmPassTxt.style.color = "red";
                resetUserType();
                submitBtn.disabled = true;

                //re-adds eventlisteners to new delete buttons.
                for(let i = 0; i <deleteBtn.length;i++){
                    deleteBtn[i].addEventListener("click", (e) => {
                    userId = e.target.parentNode.parentNode.id;
                    displayConfirmWindow(deleteWindow);
                });

                closeBtn.addEventListener("click", () => {
                    hideCreateWindow(createWindow);
                });
                
                createBtn.addEventListener("click", () => {
                    displayCreateWindow(createWindow);
                });
            }
                hideConfirmWindow(deleteWindow);
            });
        }
        else{
            alert("STOP U IDIOT");
        }
    });



//shows confirmation window
function displayConfirmWindow(window){
    window.style.visibility = "visible";
}

//hides confirmation window
function hideConfirmWindow(window){
    window.style.visibility = "hidden";
}

function setNewRow(objData){
    return "<tr id = '"+ objData['user_id'] + "'>" +            
    "<td>" + objData['user_id']+ "</td>" +
    "<td>" + objData['username']+ "</td>" +
    "<td>" + objData['email']+ "</td>" +
    "<td>" + objData['registration_date'] + "</td>" +
    "<td>" + objData['type'] + "</td>" +
    "<td><button type = 'button' class = 'table-btn update-user'>Update</button></td>" +
    "<td><button type = 'button' class = 'table-btn delete-user'>Delete</button></td>" +
    "<td><button type = 'button' class = 'table-btn view-user'>View</button></td>" +
    "</tr>";

}

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
    }, method: 'POST'})
  .then((res) => res.json())
  .then((response) => {
    console.log(response);

    for(let i = 0; i < response.length; i++){
        usernames.push(response[i]['username'].toLowerCase());
        emails.push(response[i]['email'].toLowerCase());
    }
  })
}

function resetUserType(){
    userType.value = "default";
}