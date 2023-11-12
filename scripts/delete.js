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
let fromAudio = 0;

for(let i = 0; i <deleteBtn.length;i++){
    deleteBtn[i].addEventListener("click", (e) => {
        //updates form data content with user data
        deleteId = e.target.parentNode.parentNode.id;
        userId = e.target.parentNode.parentNode.classList[0];
        //checks if the row is from text2text or audio2text
        fromAudio = (e.target.parentNode.parentNode.classList[1] === undefined) ? 1 : e.target.parentNode.parentNode.classList[1];
        displayConfirmWindow(deleteWindow);
    });
}

//Hides delete confirmation window if user clicks no
noBtn.addEventListener("click", () => {
    hideConfirmWindow(deleteWindow);
});


//Deletes row and updates user history
yesBtn.addEventListener("click", () => {
    let fd = new FormData();
    fd.append('rowId', deleteId);
    fd.append('userId', userId);
    fd.append('fromAudio', fromAudio);
    fetch('delete_query.php',{
        method : 'post', 
        body: fd})
        .then((res) => res.json()) // Converts response to JSON
        .then(response => {
            console.log(response);
            let newHistory = '';

            //add rows to new history
            for(let i = 0; i < response.length; i++){
                let obj = response[i];
                newHistory += setNewRow(obj);
            }


            //updates history content.
            history.innerHTML = newHistory;

            //re-initializes delete variables after updating the history.
            deleteWindow = document.querySelector(".delete-window");
            deleteBtn = document.querySelectorAll(".delete-btn");

            //re-adds eventlisteners to new delete buttons.
            for(let i = 0; i <deleteBtn.length;i++){
               deleteBtn[i].addEventListener("click", (e) => {
                   deleteId = e.target.parentNode.parentNode.id;
                   userId = e.target.parentNode.parentNode.classList[0];
                   fromAudio = (e.target.parentNode.parentNode.classList[1] === undefined) ? 1 : e.target.parentNode.parentNode.classList[1];
                   displayConfirmWindow(deleteWindow);
               });
           }
            hideConfirmWindow(deleteWindow);
        });
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

    //Sets new rows for audio to text history
    if(fromAudio == 0)
    {    return "<tr id = " + objData['text_id'] + " class = " + objData['user_id'] + " " + objData['from_audio_file'] + ">" +
        "<td class = " + objData['user_id']+ ">"+ objData['translate_from'] + "</td>" +
        "<td class = " + objData['user_id']+ ">"+ objData['original_language'] + "</td>" +
        "<td class = " + objData['user_id']+ ">"+ objData['translate_to'] + "</td>" +
        "<td class = " + objData['user_id']+ ">"+ objData['translated_language'] + "</td>" + 
        "<td class = " + objData['user_id']+ ">"+ objData['translation_date'] + "</td>" +
        "<td class = " + objData['user_id']+ ">"+ "<button type = 'button' class = 'delete-btn'>Delete</button></td>"   
        + "</tr>";
    }

    //Sets new rows for audio to text history
    else{
        return "<tr id = " + objData['text_id'] + " class = '" + objData['user_id'] + " " + objData['from_audio_file'] + "'>" +
        "<td class = " + objData['user_id'] + ">"  + objData['file_name'] + "</td>" + 
        "<td class = " + objData['user_id'] + ">"  + objData['file_format'] + "</td>" +
        "<td class = " + objData['user_id'] + ">"  + objData['file_size'] + "</td>" +
        "<td class = " + objData['user_id'] + ">"  + objData['translate_from'] + "</td>" + 
        "<td class = " + objData['user_id'] + ">"  + objData['original_language'] + "</td>" + 
        "<td class = " + objData['user_id'] + ">"  + objData['translate_to'] + "</td>" +
        "<td class = " + objData['user_id'] + ">"  + objData['translated_language'] + "</td>" +
        "<td class = " + objData['user_id'] + ">"  + objData['translation_date'] + "</td>" +
        "<td class = " + objData['user_id'] + ">"  +  "<button type = 'button' class = 'delete-btn'>Delete</button></td>"   
        + "</tr>";
    }
}