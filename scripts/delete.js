//stores list of all delete 
paginateRows();

let deleteWindow = document.querySelector(".delete-window");
let deleteBtn = document.querySelectorAll(".delete-btn");
let deleteAllBtn = document.querySelector(".deleteAll-btn");
let deleteRowsBtn = document.querySelector(".deleteRows-btn");
let deleteSelectedBtn = document.querySelector(".deleteSelectedRows");
let noBtn = document.querySelector(".confirm-no");
let yesBtn = document.querySelector(".confirm-yes");
let history = document.querySelector(".history-body");
let confirmText = document.querySelector(".confirm-text");
//adds event listener to all delete buttons

//initialize form data content
let deleteId = 0;
let userId = document.getElementsByTagName("body")[0].id;
console.log(userId);
let fromAudio = 0;
let clearAll = false;
let fileId = null;
let deleteRows = false;

//initialized variables for selection delete of rows
let checkboxes = document.querySelectorAll(".delete-checkbox");
let rowsToDelete = [];
let filesToDelete = [];

//event listener for checkboxes; adds fileid and textid to rowsToDelete & filesToDelete array respectively
for(let i = 0; i < checkboxes.length; i++){
    checkboxes[i].addEventListener("change", (e) => {
        if(checkboxes[i].checked){
            addToDeleteRows(checkboxes[i]);
        }
        else{removeToDeleteRows(checkboxes[i]);
        }
    });
}

deleteSelectedBtn.addEventListener("click", (e) => {
    confirmText.innerHTML = "Are you sure you want to delete all rows you selected?";
    fromAudio = (e.target.id == "a2t") ? 1 : 0;
    // console.log(fromAudio);
    // console.log(rowsToDelete);
    // console.log(deleteRows);
    displayConfirmWindow(deleteWindow);
});

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
    displayConfirmWindow(deleteWindow);
});

deleteRowsBtn.addEventListener("click", (e) => {
    fromAudio = (e.target.id == "a2t") ? 1 : 0;
    console.log(fromAudio);
    if(!deleteRows)
        displayCheckboxes();
    else
        hideCheckboxes();

    console.log(deleteRows);
});

for(let i = 0; i <deleteBtn.length;i++){
    deleteBtn[i].addEventListener("click", (e) => {
    deleteRows = false;
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
    fd.append('deleteRows', deleteRows);
    fd.append('filesToDelete', JSON.stringify(filesToDelete));
    fd.append('rowsToDelete', JSON.stringify(rowsToDelete));


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
             userId = document.getElementsByTagName("body")[0].id;
             fromAudio = 0;
             clearAll = false;
             fileId = null;
             checkboxes = document.querySelectorAll(".delete-checkbox");
            rowsToDelete = [];
            filesToDelete = [];

            //re-adds eventlisteners to new delete buttons.
            for(let i = 0; i <deleteBtn.length;i++){
               deleteBtn[i].addEventListener("click", (e) => {
                    deleteRows = false;
                   deleteId = e.target.parentNode.parentNode.id;
                   userId = e.target.parentNode.parentNode.classList[0];
                   fromAudio = (e.target.parentNode.parentNode.classList[1] == "a2t") ? 1 : 0;
                    fileId = fromAudio == 1 ? e.target.parentNode.parentNode.classList[2] : null;
                   displayConfirmWindow(deleteWindow);
               });
           }
           

           for(let i = 0; i < checkboxes.length; i++){
            checkboxes[i].addEventListener("change", (e) => {
                if(checkboxes[i].checked){
                    addToDeleteRows(checkboxes[i]);
                }
                else{removeToDeleteRows(checkboxes[i]);
                }
                });
            }

            
            
        deleteSelectedBtn.addEventListener("click", (e) => {
            confirmText.innerHTML = "Are you sure you want to delete all rows you selected?";
            fromAudio = (e.target.id == "a2t") ? 1 : 0;
            console.log(fromAudio);
            console.log(rowsToDelete);
            console.log(deleteRows);
            displayConfirmWindow(deleteWindow);
        });

        paginateRows();
            hideConfirmWindow(deleteWindow);
            hideCheckboxes();
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

function displayCheckboxes(){
    deleteRows = true;
    deleteSelectedBtn.style.visibility = "visible";
    for(let i = 0; i< checkboxes.length; i++)
        checkboxes[i].style.display = "block";
    deleteRowsBtn.textContent = "Cancel Delete Rows";
    
}

function hideCheckboxes(){
    deleteRows = false;
    deleteSelectedBtn.style.visibility = "hidden";
    deleteRowsBtn.textContent = "Delete Rows";
    for(let i = 0; i< checkboxes.length; i++){
        checkboxes[i].style.display = "none";
        checkboxes[i].checked = false;
    }

    rowsToDelete = [];
    filesToDelete = [];
}

function setNewRow(objData){

    //Sets new rows for audio to text history
    if(fromAudio == 0)
    {    return "<tr id = " + objData['text_id'] + " class = '" + objData['user_id'] + " " + "t2t" + " paginate" + "'>" +
    "<td class = '" + objData['user_id']+  " truncate-text'>" + objData['translate_from'] + "</td>" + 
    "<td class = " + objData['user_id']+ ">"+ objData['original_language'] + "</td>" +
    "<td class = '" + objData['user_id']+  " truncate-text'>" + objData['translate_to'] + "</td>" + 
        "<td class = " + objData['user_id']+ ">"+ objData['translated_language'] + "</td>" + 
        "<td class = " + objData['user_id']+ ">"+ objData['translation_date'] + "</td>" +
        "<td class = " + objData['user_id']+ ">"+ "<button type = 'button' class = 'delete-btn'>Delete</button></td>" +
        "<td class = " + objData['user_id'] + ">" + "<input type = 'checkbox' class = 'delete-checkbox' id = " + objData['text_id'] +"></td>"     
        + "</tr>";
    }

    //Sets new rows for audio to text history
    else{
        return "<tr id = " + objData['text_id'] + " class = '" + objData['user_id'] + " " + "a2t" + " " + objData['file_id']  + " paginate" + "'>" +
        "<td class = " + objData['user_id'] + ">"  + objData['file_name'] + "</td>" + 
        "<td class = " + objData['user_id'] + ">"  + objData['file_format'] + "</td>" +
        "<td class = " + objData['user_id'] + ">"  + objData['file_size'] + "</td>" +
        "<td class = '" + objData['user_id']+  " truncate-text'>" + objData['translate_from'] + "</td>" + 
        "<td class = " + objData['user_id'] + ">"  + objData['original_language'] + "</td>" + 
        "<td class = '" + objData['user_id'] +  " truncate-text'>" + objData['translate_to'] +  "</td>" +
        "<td class = " + objData['user_id'] + ">"  + objData['translated_language'] + "</td>" +
        "<td class = " + objData['user_id'] + ">"  + objData['translation_date'] + "</td>" +
        "<td class = " + objData['user_id'] + ">"  +  "<button type = 'button' class = 'delete-btn'>Delete</button></td>" +
        "<td class = " + objData['user_id'] + ">" + "<input type = 'checkbox' class = 'delete-checkbox' id = " + objData['text_id'] +"></td>"     
        + "</tr>";
    }
}

function addToDeleteRows(checkbox){
    rowsToDelete.push(checkbox.id);
    console.log("Rows to delete: " + rowsToDelete);
    if(fromAudio == 1){
        filesToDelete.push(checkbox.parentNode.parentNode.classList[2]);
        console.log("Files to delete: " + filesToDelete);
    }
    console.log(fromAudio);
}

function removeToDeleteRows(checkbox){
    rowsToDelete.splice(rowsToDelete.indexOf(checkbox.id), 1);
    if(fromAudio == 1){
        let fileIdToRemove = checkbox.parentNode.parentNode.classList[2];
        filesToDelete.splice(filesToDelete.indexOf(fileIdToRemove), 1);
        console.log("Files to delete (changed): " + filesToDelete);
    }
    console.log("Rows To Delete (changed)" + rowsToDelete);
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

//function for adding pagination for every 5 rows
function paginateRows(){
    jQuery(function($) {
        // Grab whatever we need to paginate
        var pageParts = $(".paginate");
    
        // How many parts do we have?
        var numPages = pageParts.length;
        // How many parts do we want per page?
        var perPage = 5;
    
        // When the document loads we're on page 1
        // So to start with... hide everything else
        pageParts.slice(perPage).hide();
        // Apply simplePagination to our placeholder
        $("#page-nav").pagination({
            items: numPages,
            itemsOnPage: perPage,
            cssStyle: "light-theme",
            // We implement the actual pagination
            //   in this next function. It runs on
            //   the event that a user changes page
            onPageClick: function(pageNum) {
                // Which page parts do we show?
                var start = perPage * (pageNum - 1);
                var end = start + perPage;
    
                // First hide all page parts
                // Then show those just for our page
                pageParts.hide()
                         .slice(start, end).show();
            }
        });
    });

}