//stores list of all delete 
let deleteWindow = document.querySelector(".delete-window");
let deleteBtn = document.querySelectorAll(".delete-btn");
let noBtn = document.querySelector(".confirm-no");
let yesBtn = document.querySelector(".confirm-yes");
let history = document.querySelector(".history-body");
//adds event listener to all delete buttons

//initialize form data content
let deleteId = 0;
let userId = 0;

//Hides delete confirmation window if user clicks no
noBtn.addEventListener("click", () => {
    hideConfirmWindow(deleteWindow);
});


//Deletes row and updates user history
yesBtn.addEventListener("click", () => {
    let fd = new FormData();
    fd.append('rowId', deleteId);
    fd.append('userId', userId);
    fetch('delete_query.php',{
        method : 'post', 
        body: fd})
        .then((res) => res.json()) // Converts response to JSON
        .then(response => {
            let newHistory = '';

            //add rows to new history
            for(let i = 0; i < response.length; i++){
                let obj = response[i];
                newHistory += "<tr id = " + obj['text_id'] + " class = " + obj['user_id'] + ">" +
                "<td class = " + obj['user_id']+ ">"+ obj['translate_from'] + "</td>" +
                "<td class = " + obj['user_id']+ ">"+ obj['original_language'] + "</td>" +
                "<td class = " + obj['user_id']+ ">"+ obj['translate_to'] + "</td>" +
                "<td class = " + obj['user_id']+ ">"+ obj['translated_language'] + "</td>" + 
                "<td class = " + obj['user_id']+ ">"+ obj['translation_date'] + "</td>" +
                "<td class = " + obj['user_id']+ ">"+ "<button type = 'button' class = 'delete-btn'>Delete</button></td>"   
                + "</tr>";
            }


            //updates history content.
            history.innerHTML = newHistory;

            //re-initializes delete variables after updating the history.
            deleteWindow = document.querySelector(".delete-window");
            deleteBtn = document.querySelectorAll(".delete-btn");

            for(let i = 0; i <deleteBtn.length;i++){
               deleteBtn[i].addEventListener("click", (e) => {
                   deleteId = e.target.parentNode.parentNode.id;
                   userId = e.target.parentNode.parentNode.className;
                   displayConfirmWindow(deleteWindow);
               });
           }

            hideConfirmWindow(deleteWindow);
        });
});

for(let i = 0; i <deleteBtn.length;i++){
    deleteBtn[i].addEventListener("click", (e) => {
        //updates form data content with user data
        deleteId = e.target.parentNode.parentNode.id;
        userId = e.target.parentNode.parentNode.className;
        displayConfirmWindow(deleteWindow);
    });
}

//shows confirmation window
function displayConfirmWindow(window){
    window.style.visibility = "visible";
}

//hides confirmation window
function hideConfirmWindow(window){
    window.style.visibility = "hidden";
}