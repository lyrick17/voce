const sideLinks = document.querySelectorAll('.sidebar .side-menu li a:not(.logout)');
const menuBar = document.querySelector('.content nav .bx.bx-menu');
const sideBar = document.querySelector('.sidebar');
const searchBtn = document.querySelector('.content nav form .form-input button');
const searchBtnIcon = document.querySelector('.content nav form .form-input button .bx');
const searchForm = document.querySelector('.content nav form');

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

    if (window.innerWidth > 576) {
        searchBtnIcon.classList.replace('bx-x', 'bx-search');
        searchForm.classList.remove('show');
    }
});

    // Get references to the select elements and the translate button
    const sourceLanguageSelect = document.getElementById('sourceLanguage');
    const targetLanguageSelect = document.getElementById('targetLanguage');
    const yourButtonID = document.getElementById('yourButtonID');

    // Add an event listener to both select elements
    sourceLanguageSelect.addEventListener('change', toggleTranslateButton);
    targetLanguageSelect.addEventListener('change', toggleTranslateButton);

    // Function to enable/disable the translate button based on selections
    function toggleTranslateButton() {
        if (sourceLanguageSelect.value && targetLanguageSelect.value) {
            yourButtonID.removeAttribute('disabled');
            
        } else {
            yourButtonID.setAttribute('disabled', 'true');
        }
    }

    
