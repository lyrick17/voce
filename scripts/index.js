
   /* document.addEventListener('DOMContentLoaded', function () {
        // Get all the cells with class 'truncate-text'
        var cells = document.querySelectorAll('.truncate-text');
    
        // Get the modal and its close button
        var modal = document.getElementById('myModal');
    
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
    }); */

    const openFeedbackBtn = document.getElementById('open-feedback');
    const feedbackSidebar = document.querySelector('.feedback-sidebar');

    if(openFeedbackBtn != null){
        openFeedbackBtn.addEventListener('click', () => {
            feedbackSidebar.classList.toggle('active');
        });
    }

    // Get a reference to the submit button (if needed for future functionality)

    if(feedbackSidebar != null){
        const submitBtn = feedbackSidebar.querySelector('button');
    }
    // modal for loading the duck
    function showLoading() {
        document.getElementById('loadingModal').style.display = 'flex';
      }
    function removeLoading() {
        document.getElementById('loadingModal').style.display = 'none';
    }
    
// for drag and drop audio file
function fileDropHandler(event) { 
    event.preventDefault();
    event.currentTarget.classList.remove('drag-hover');
    
    const file = event.dataTransfer.files[0];
    console.log(file.type);
    let filelist = new File([file], file.name);
    
    let transferFile = new DataTransfer();

    transferFile.items.add(filelist);
    // Simulate a file drop event on the input element
    document.getElementById('fileInputLabel').files =  transferFile.files;

    // check the format, then the file size
    checkFileFormat(document.getElementById('fileInputLabel'), file.type);
    checkFileSize(document.getElementById('fileInputLabel'));
    resetRecord();
}


function handleDrop(e) {
    e.preventDefault();

    var files = e.dataTransfer.files;

}

function dragOverHandler(event) {
    // Prevent default behavior (Prevent file from being opened)
    event.preventDefault();
}
function dragEnterHandler(event) {
    event.preventDefault();
    event.currentTarget.classList.add('drag-hover');
}
function dragLeaveHandler(event) {
    event.preventDefault();
    
    if (!event.currentTarget.contains(event.relatedTarget)) {
        event.currentTarget.classList.remove('drag-hover');
    }
}


const sidebar = document.querySelector('.sidebar');
const mainContent = document.querySelector('.main-content');

// Function to toggle sidebar
function toggleSidebar(event) {
    event.preventDefault(); // Prevent default behavior (page reload)
    sidebar.classList.toggle('open');
    mainContent.classList.toggle('open');
}

// Add event listener to icons inside the options div
const optionIcons = document.querySelectorAll('.options img');
optionIcons.forEach(icon => {
    icon.addEventListener('click', toggleSidebar);
});


