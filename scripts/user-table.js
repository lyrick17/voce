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

//Hides delete confirmation window if user clicks no
noBtn.addEventListener("click", () => {
    hideConfirmWindow(deleteWindow);
});


//Deletes row and updates user history
yesBtn.addEventListener("click", () => {
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
                let deleteBtn = document.querySelectorAll(".delete-user");
                userId = 0;

                //re-adds eventlisteners to new delete buttons.
                for(let i = 0; i <deleteBtn.length;i++){
                    deleteBtn[i].addEventListener("click", (e) => {
                    userId = e.target.parentNode.parentNode.id;
                    displayConfirmWindow(deleteWindow);
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