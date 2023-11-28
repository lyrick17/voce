const closeBtn = document.querySelector('.closecreate-btn');
const createWindow = document.querySelector(".create-window");
const createBtn = document.querySelector(".create-btn");

closeBtn.addEventListener("click", () => {
    hideCreateWindow(createWindow);
});

createBtn.addEventListener("click", () => {
    displayCreateWindow(createWindow);
});




function displayCreateWindow(window){
    window.style.visibility = "visible";
}

//hides confirmation window
function hideCreateWindow(window){
    window.style.visibility = "hidden";
}