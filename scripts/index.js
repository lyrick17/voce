const sideLinks = document.querySelectorAll('.sidebar .side-menu li a:not(.logout)');
const menuBar = document.querySelector('.content nav .bx.bx-menu');
const sideBar = document.querySelector('.sidebar');

// Function to save sidebar state to localStorage
function saveSidebarState(isClosed) {
    localStorage.setItem('sidebarState', isClosed ? 'closed' : 'open');
}

// Function to load sidebar state from localStorage
function loadSidebarState() {
    return localStorage.getItem('sidebarState') === 'closed';
}

// Set initial state based on localStorage
sideBar.classList.toggle('close', loadSidebarState());

sideLinks.forEach(item => {
    const li = item.parentElement;
    item.addEventListener('click', () => {
        sideLinks.forEach(i => {
            i.parentElement.classList.remove('active');
        });
        li.classList.add('active');

        if (window.innerWidth < 768) {
            sideBar.classList.add('close');
            saveSidebarState(true);
        }
    });
});

menuBar.addEventListener('click', () => {
    sideBar.classList.toggle('close');
    saveSidebarState(sideBar.classList.contains('close'));
});



window.addEventListener('resize', () => {
    if (window.innerWidth < 768 && !sideBar.classList.contains('close')) {
        sideBar.classList.add('close');
        saveSidebarState(true);
    } else if (window.innerWidth >= 768 && sideBar.classList.contains('close')) {
        sideBar.classList.remove('close');
        saveSidebarState(false);
    }
});

    // Get references to the select elements and the translate button
    const sourceLanguageSelect = document.getElementById('sourceLanguage');
    const targetLanguageSelect = document.getElementById('targetLanguage');
    const modelSizeSelect = document.getElementById('modelSize');

    const yourButtonID = document.getElementById('yourButtonID');

    // Add an event listener to both select elements
    sourceLanguageSelect.addEventListener('change', toggleTranslateButton);
    targetLanguageSelect.addEventListener('change', toggleTranslateButton);
    modelSizeSelect.addEventListener('change', toggleTranslateButton);

    // Function to enable/disable the translate button based on selections
    function toggleTranslateButton() {
        if (sourceLanguageSelect.value && targetLanguageSelect.value && modelSizeSelect.value) {
            yourButtonID.removeAttribute('disabled');
            
        } else {
            yourButtonID.setAttribute('disabled', 'true');
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
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
    });
    // modal for loading the duck
    function showLoading() {
        document.getElementById('loadingModal').style.display = 'flex';
    }
    
    
    
