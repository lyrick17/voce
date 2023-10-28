const container = document.getElementById('container');
const registerBtn = document.getElementById('register');
const loginBtn = document.getElementById('login');

registerBtn.addEventListener('click', () => {
    container.classList.add("active");
});

loginBtn.addEventListener('click', () => {
    container.classList.remove("active");
});

// Function to simulate loading
function simulateLoading() {
    // Show the loader
    var loader = document.querySelector('.loader');
    loader.style.display = 'block';

    // Simulate loading by waiting for a few seconds (you can replace this with your actual content loading logic)
    setTimeout(function() {
        // Hide the loader
        loader.style.display = 'none';

        // Enable the button
        var button = document.getElementById('yourButtonID');
        button.disabled = false;
    }, 2000); // Replace 2000 with the actual loading time in milliseconds
}

// Add click event listener to the button
document.getElementById('yourButtonID').addEventListener('click', function(event) {
    event.preventDefault(); // Prevent the form submission
    simulateLoading();
});