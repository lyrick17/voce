
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

