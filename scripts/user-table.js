//Fetch usernames and emails
let usernames = [];
let emails = []
fetchUsers();


//Create function DOMs
let closeBtn = document.querySelector('.closecreate-btn');
let createWindow = document.querySelector(".create-window");
let createBtn = document.querySelector(".create-btn");
let form = document.getElementById("form");
let submitBtn = document.getElementById("create-user");
let userType = document.getElementById('userType');
const uniqueUserTxt = document.querySelector(".unique-user");
const validUserTxt = document.querySelector(".valid-user");
const validEmailTxt = document.querySelector(".valid-email");
const confirmPassTxt = document.querySelector(".confirm-pass");
const username = document.getElementById('username');
const email = document.getElementById('email');
const pword = document.getElementById('pword');
const pword2 = document.getElementById('pword2');

//Update function DOMs
let updateBtns = document.querySelectorAll('.update-user');
let submitUpdate = document.getElementById('submitUpdate');
let updateWindow = document.querySelector(".update-window");
let closeupdateBtn = document.querySelector('.closeupdate-btn');
let updateDivHeader = document.querySelector('.update-div-header');
let updateForm = document.getElementById("update-form");
const updateUsername = document.getElementById('new-username');
const updateEmail = document.getElementById('new-email');
const updatePword = document.getElementById('new-pword');
const updatePword2 = document.getElementById('new-pword2');
const uniqueUserTxt2 = document.querySelector(".unique-user2");
const validUserTxt2 = document.querySelector(".valid-user2");
const validEmailTxt2 = document.querySelector(".valid-email2");
const confirmPassTxt2 = document.querySelector(".confirm-pass2");

//Delete function DOMs

let deleteWindow = document.querySelector(".delete-window");
let deleteBtn = document.querySelectorAll(".delete-user");
let noBtn = document.querySelector(".confirm-no");
let yesBtn = document.querySelector(".confirm-yes");
let usersTable = document.querySelector(".users-table");
let confirmText = document.querySelector(".confirm-text");

//Keeps track of users per row
let mysession = document.querySelector('.mysession');
let sessionId = mysession.getAttribute('id');

//initialize form data content
let userId = 0;





//Adds event listener for for every update and delete buttons per row in the table
for(let i = 0; i <deleteBtn.length;i++){
    updateBtns[i].addEventListener("click", (e) => {
        userId = e.target.parentNode.parentNode.id;
        updateDivHeader.innerHTML = "Update user " + userId + "?";
        alert(userId);
        displayWindow(updateWindow);
    });
    deleteBtn[i].addEventListener("click", (e) => {
        userId = e.target.parentNode.parentNode.id;
        alert(userId);
        confirmText.innerHTML = "Are you sure you want to delete this user?";
        //Sets the user to be deleted.
        displayWindow(deleteWindow);
    });
}

closeupdateBtn.addEventListener("click", (e) => {
    hideWindow(updateWindow);
});

userType.addEventListener("change", () =>{
    readyToSubmit("create");
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

        //re-initializes update variables after updating the history.
        updateBtns = document.querySelectorAll('.update-user');
        updateWindow = document.querySelector(".update-window");
        closeupdateBtn = document.querySelector('.closeupdate-btn');
        updateDivHeader = document.querySelector('.update-div-header');

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
            updateBtns[i].addEventListener("click", (e) => {
                userId = e.target.parentNode.parentNode.id;
                updateDivHeader.innerHTML = "Update user " + userId + "?";
                alert(userId);
                displayWindow(updateWindow);
            });
            deleteBtn[i].addEventListener("click", (e) => {
                userId = e.target.parentNode.parentNode.id;
                alert(userId);
                confirmText.innerHTML = "Are you sure you want to delete this user?";
                //Sets the user to be deleted.
                displayWindow(deleteWindow);
            });
            closeBtn.addEventListener("click", () => {
                hideWindow(createWindow);
            });
            
            createBtn.addEventListener("click", () => {
                displayWindow(createWindow);
            });
        }



    hideWindow(createWindow);  
    fetchUsers();  
    });

});

//Event listeners for validating create user inputs
username.addEventListener("keyup", () => {
    validateUser(username.value, "create");
});

email.addEventListener("keyup", () => {
    validateEmail(email.value, "create");
});

pword.addEventListener("keyup", () => {
    validatePassword(pword.value, pword2.value, "create");
});

pword2.addEventListener("keyup", () => {
    validatePassword(pword.value, pword2.value, "create");
});

//Event listeners for validating update user inputs
updateUsername.addEventListener("keyup", () => {
    validateUser(updateUsername.value, "update");
});

updateEmail.addEventListener("keyup", () => {
    validateEmail(updateEmail.value, "update");
});

updatePword.addEventListener("keyup", () => {
    validatePassword(updatePword.value, updatePword2.value, "update");
});

updatePword2.addEventListener("keyup", () => {
    validatePassword(updatePword.value, updatePword2.value, "update");
});

closeBtn.addEventListener("click", () => {
    hideWindow(createWindow);
});

createBtn.addEventListener("click", () => {
    displayWindow(createWindow);
});


//Hides delete confirmation window if user clicks no
noBtn.addEventListener("click", () => {
    hideWindow(deleteWindow);
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
                fetchUsers();
                let updatedUsers ='<tr><td class = "create-cell"><button class = "table-btn create-btn">Create User</button></td></tr><tr><th class = "data">User ID</th><th>Username</th><th>Email</th><th>Registration Date</th><th>Type</th><th colspan = 3>Actions</th></tr>';

                //add rows to new users table
                for(let i = 0; i < response.length; i++){
                    let obj = response[i];
                    updatedUsers += setNewRow(obj);
                }



                //updates history content.
                usersTable.innerHTML = updatedUsers;

                //re-initializes update variables after updating the history.
                updateBtns = document.querySelectorAll('.update-user');
                submitUpdate = document.getElementById('submitUpdate');
                updateWindow = document.querySelector(".update-window");
                closeupdateBtn = document.querySelector('.closeupdate-btn');
                updateDivHeader = document.querySelector('.update-div-header');
                updateForm = document.getElementById("update-form");


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
                    updateBtns[i].addEventListener("click", (e) => {
                        userId = e.target.parentNode.parentNode.id;
                        updateDivHeader.innerHTML = "Update user " + userId + "?";
                        alert(userId);
                        displayWindow(updateWindow);
                    });
                    deleteBtn[i].addEventListener("click", (e) => {
                        userId = e.target.parentNode.parentNode.id;
                        alert(userId);
                        confirmText.innerHTML = "Are you sure you want to delete this user?";
                        //Sets the user to be deleted.
                        displayWindow(deleteWindow);
                    });
                    closeBtn.addEventListener("click", () => {
                        hideWindow(createWindow);
                    });
                    
                    createBtn.addEventListener("click", () => {
                        displayWindow(createWindow);
                    });
                }
                hideWindow(deleteWindow);
            });
        }
        else{
            alert("You can't delete your own account.");
        }
        
        
    });


    updateForm.addEventListener("submit", (e) => {
    e.preventDefault();
    let fd = new FormData(updateForm);
    fd.append('userId', userId);
    fetch('user-table.php',{
        method : 'POST', 
        body: fd})
        .then((res) => res.json()) // Converts response to JSON
        .then(response => {
            console.log(response);
            fetchUsers();
            let updatedUsers ='<tr><td class = "create-cell"><button class = "table-btn create-btn">Create User</button></td></tr><tr><th class = "data">User ID</th><th>Username</th><th>Email</th><th>Registration Date</th><th>Type</th><th colspan = 3>Actions</th></tr>';

            //add rows to new users table
            for(let i = 0; i < response.length; i++){
                let obj = response[i];
                updatedUsers += setNewRow(obj);
            }

            //updates history content.
            usersTable.innerHTML = updatedUsers;

            //re-initializes update variables after updating the history.
            updateBtns = document.querySelectorAll('.update-user');
            submitUpdate = document.getElementById('submitUpdate');
            updateWindow = document.querySelector(".update-window");
            closeupdateBtn = document.querySelector('.closeupdate-btn');
            updateDivHeader = document.querySelector('.update-div-header');
            updateForm = document.getElementById("update-form");
            updateUsername.value = "";
            updateEmail.value = "";
            updatePword.value = "";
            updatePword2.value = "";
            uniqueUserTxt2.style.color = "red";
            validUserTxt2.style.color = "red";
            validEmailTxt2.style.color = "red";
            confirmPassTxt2.style.color = "red";
            submitUpdate.disabled = true;


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
                updateBtns[i].addEventListener("click", (e) => {
                    userId = e.target.parentNode.parentNode.id;
                    updateDivHeader.innerHTML = "Update user " + userId + "?";
                    alert(userId);
                    displayWindow(updateWindow);
                });
                deleteBtn[i].addEventListener("click", (e) => {
                    userId = e.target.parentNode.parentNode.id;
                    alert(userId);
                    confirmText.innerHTML = "Are you sure you want to delete this user?";
                    //Sets the user to be deleted.
                    displayWindow(deleteWindow);
                });
                closeBtn.addEventListener("click", () => {
                    hideWindow(createWindow);
                });
                
                createBtn.addEventListener("click", () => {
                    displayWindow(createWindow);
                });
            }
            hideWindow(updateWindow);
        });        
});



//shows confirmation window
function displayWindow(window){
    window.style.visibility = "visible";
}

//hides confirmation window
function hideWindow(window){
    window.style.visibility = "hidden";

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

    updateUsername.value = "";
    updateEmail.value = "";
    updatePword.value = "";
    updatePword2.value = "";
    uniqueUserTxt2.style.color = "red";
    validUserTxt2.style.color = "red";
    validEmailTxt2.style.color = "red";
    confirmPassTxt2.style.color = "red";

    for(let i = 0; i <updateBtns.length;i++){
        updateBtns.disabled = true;
    }
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

function displayWindow(window){
    window.style.visibility = "visible";
}

function readyToSubmit(func){
    if(func == "create"){
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
    else if (func == "update"){
        if(uniqueUserTxt2.style.color == "white" &&
        validUserTxt2.style.color == "white" &&
        validEmailTxt2.style.color == "white" &&
        confirmPassTxt2.style.color == "white"){
            submitUpdate.disabled = false;
        }
        else{
            submitUpdate.disabled = true;
        }
    }
}

// not yet finished, need to check if username is unique
function validateUser(username, func){
    if(func == "create"){
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
    }
    else if(func == "update"){
        if(username.length >= 6 && username.length <= 30 && !usernames.includes(username.toLowerCase()))
            uniqueUserTxt2.style.color = "white";
        else{
            uniqueUserTxt2.style.color = "red";
        }

        const userPattern = /^[\w\-]+$/;

        if(userPattern.test(username)){
            validUserTxt2.style.color = "white";
        }
        else{
            validUserTxt2.style.color = "red";
        }
    }
    readyToSubmit(func);
}

// not yet finished, need to check if email is unique
function validateEmail(email, func){
    const emailPattern = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    if(emailPattern.test(email) && !emails.includes(email)){
        if(func == "create")
            validEmailTxt.style.color = "white";    
        else if(func == "update")
        validEmailTxt2.style.color = "white";    
    }
    else{
        if(func == "create")
            validEmailTxt.style.color = "red";    
        else if(func == "update")
        validEmailTxt2.style.color = "red";    
    }
    readyToSubmit(func);
}

function validatePassword(password, password2, func){
    if(password.length >= 8 && password === password2 && password != "")
        if(func == "create")
        confirmPassTxt.style.color = "white";    
        else if(func == "update")
        confirmPassTxt2.style.color = "white";    
    else{
        if(func == "create")
            confirmPassTxt.style.color = "white";    
        else if(func == "update")
            confirmPassTxt2.style.color = "white";    
    }
    readyToSubmit(func);
}

function fetchUsers(){
    //resets usernames and emails 
    usernames = [];
    emails = [];
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