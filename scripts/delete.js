//stores list of all delete 
let deleteWindow = document.querySelector(".delete-window");
let deleteBtn = document.querySelectorAll(".delete-btn");
let deleteAllBtn = document.querySelector(".deleteAll-btn");
let noBtn = document.querySelector(".confirm-no");
let yesBtn = document.querySelector(".confirm-yes");
let history = document.querySelector(".history-body");
let confirmText = document.querySelector(".confirm-text");
//adds event listener to all delete buttons

//initialize form data content
let deleteId = 0;
let userId = 0;
let fromAudio = 0;
let clearAll = false;
let fileId = null;

//Click event for delete all
deleteAllBtn.addEventListener("click", (e) =>{
    confirmText.innerHTML = "Are you sure you want to delete all rows?";
    /* 
    Checks if translation is from audio. If it is, 
    it follows the following scenario.
    1. Deletes all rows from text_translation table correspod.
    2.
    */
    fromAudio = (e.target.id == "a2t") ? 1 : 0;
    clearAll = true;
    userId = 1;
    displayConfirmWindow(deleteWindow);
});

for(let i = 0; i <deleteBtn.length;i++){
    deleteBtn[i].addEventListener("click", (e) => {
    confirmText.innerHTML = "Are you sure you want to delete this row?";
    //updates form data content with user data
    deleteId = e.target.parentNode.parentNode.id;
    userId = e.target.parentNode.parentNode.classList[0];
    //checks if the row is from text2text or audio2text
    fromAudio = (e.target.parentNode.parentNode.classList[1] == "a2t") ? 1 : 0;
    fileId = fromAudio == 1 ? e.target.parentNode.parentNode.classList[2] : null;
    displayConfirmWindow(deleteWindow);
    });
}

//Hides delete confirmation window if user clicks no
noBtn.addEventListener("click", () => {
    hideConfirmWindow(deleteWindow);
    clearAll = false;
});


//Deletes row and updates user history
yesBtn.addEventListener("click", () => {
    let fd = new FormData();
    fd.append('rowId', deleteId);
    fd.append('userId', userId);
    fd.append('fromAudio', fromAudio);
    fd.append('clearAll', clearAll);
    fd.append('fileId', fileId);

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

            //Truncates text after history is updated
            truncateText();

            //re-initializes delete variables after updating the history.
            deleteWindow = document.querySelector(".delete-window");
            deleteBtn = document.querySelectorAll(".delete-btn");
             deleteId = 0;
             userId = 0;
             fromAudio = 0;
             clearAll = false;
             fileId = null;

            //re-adds eventlisteners to new delete buttons.
            for(let i = 0; i <deleteBtn.length;i++){
               deleteBtn[i].addEventListener("click", (e) => {
                   deleteId = e.target.parentNode.parentNode.id;
                   userId = e.target.parentNode.parentNode.classList[0];
                   fromAudio = (e.target.parentNode.parentNode.classList[1] == "a2t") ? 1 : 0;
                    fileId = fromAudio == 1 ? e.target.parentNode.parentNode.classList[2] : null;
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
    {    return "<tr id = " + objData['text_id'] + " class = '" + objData['user_id'] + " " + "t2t" + "'>" +
    "<td class = '" + objData['user_id']+  " truncate-text'>" + objData['translate_from'] + "</td>" + 
    "<td class = " + objData['user_id']+ ">"+ objData['original_language'] + "</td>" +
    "<td class = '" + objData['user_id']+  " truncate-text'>" + objData['translate_to'] + "</td>" + 
        "<td class = " + objData['user_id']+ ">"+ objData['translated_language'] + "</td>" + 
        "<td class = " + objData['user_id']+ ">"+ objData['translation_date'] + "</td>" +
        "<td class = " + objData['user_id']+ ">"+ "<button type = 'button' class = 'delete-btn'>Delete</button></td>"   
        + "</tr>";
    }

    //Sets new rows for audio to text history
    else{
        return "<tr id = " + objData['text_id'] + " class = '" + objData['user_id'] + " " + "a2t" + " " + objData['file_id'] + "'>" +
        "<td class = " + objData['user_id'] + ">"  + objData['file_name'] + "</td>" + 
        "<td class = " + objData['user_id'] + ">"  + objData['file_format'] + "</td>" +
        "<td class = " + objData['user_id'] + ">"  + objData['file_size'] + "</td>" +
        "<td class = '" + objData['user_id']+  " truncate-text'>" + objData['translate_from'] + "</td>" + 
        "<td class = " + objData['user_id'] + ">"  + objData['original_language'] + "</td>" + 
        "<td class = '" + objData['user_id'] +  " truncate-text'>" + objData['translate_to'] +  "</td>" +
        "<td class = " + objData['user_id'] + ">"  + objData['translated_language'] + "</td>" +
        "<td class = " + objData['user_id'] + ">"  + objData['translation_date'] + "</td>" +
        "<td class = " + objData['user_id'] + ">"  +  "<button type = 'button' class = 'delete-btn'>Delete</button></td>"   
        + "</tr>";
    }
}

// Truncates text that have characters greater than 150
function truncateText() {
    // Get all the cells with class 'truncate-text'
    var cells = document.querySelectorAll('.truncate-text');

    // Get the modal and its close button
    var modal = document.getElementById('myModal');
    var closeBtn = modal.querySelector('.close');

    // Iterate through each cell and add the truncation functionality
    cells.forEach(function (cell) {
        var originalText = cell.textContent.trim();

        // Check if the text length is greater than 150 characters
        if (originalText.length > 150) {
            // If yes, truncate the text and add ellipsis as a button
            var truncatedText = originalText.substring(0, 150);
            var contentSpan = document.createElement('span');
            contentSpan.innerHTML = truncatedText + '<button class="ellipsis" type="button">.....</button>';

            // Replace the content of the cell with the new span
            cell.innerHTML = '';
            cell.appendChild(contentSpan);

            // Add a click event listener to the ellipsis button to show the full text in a modal
            var ellipsisButton = cell.querySelector('.ellipsis');
            ellipsisButton.addEventListener('click', function () {
                // Set the full text in the modal
                document.getElementById('modalText').textContent = originalText;

                // Display the modal
                modal.style.display = 'block';
            });
        } else {
            // If not, display the original text without ellipsis
            cell.innerHTML = originalText;
        }
    });

    // Close the modal when the close button or outside the modal is clicked
    closeBtn.addEventListener('click', function () {
        modal.style.display = 'none';
    });

    window.addEventListener('click', function (event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    });
}