

const passform = document.getElementById("inputpass-form");
const userform = document.getElementById("inputuser-form");
const emailform = document.getElementById("inputemail-form");


// listen for password change
passform.addEventListener('submit', function(e) {
    e.preventDefault();
    const pass_info = new FormData(passform);
    fetch('utilities/account.php', {
        method: "POST",
        body: pass_info,
    })
    .then(response => response.json())
    .then(data => {
        if (data.error == 0) { 
            console.log("no error, password changed");
            editMessage(data.error, "pass-error");

            // reset fields
            document.getElementById("old-pword").value = "";
            document.getElementById("new-pword").value = "";
            document.getElementById("new-pword2").value = "";
        } else {
            console.log(data);
            console.log("error: " + data.error);
            editMessage(data.error, "pass-error");
        }
    });
    console.log("cc");
});

// listen for username change
userform.addEventListener('submit', function(e) {
    e.preventDefault();
    const user_info = new FormData(userform);
    fetch('utilities/account.php', {
        method: "POST",
        body: user_info,
    })
    .then(response => response.json())
    .then(data => {
        if (data.error == 0) { 
            console.log("no error, username changed")
            editMessage(data.error, "user-error");
            document.getElementById("current-username").value = data.username;
            document.getElementById("current-username").value = data.username;
        } else {
            console.log(data);
            console.log("error: " + data.error);
            editMessage(data.error, "user-error");
        }
    });
    console.log("cc");
});

// listen for email change
emailform.addEventListener('submit', function(e) {
    e.preventDefault();
    const email_info = new FormData(emailform);

    fetch('utilities/account.php', {
        method: "POST",
        body: email_info,
    })
    .then(response => response.json())
    .then(data => {
        if (data.error == 0) { 
            console.log("no error, email changed")
            editMessage(data.error, "email-error");
            document.getElementById("current-email").value = data.email;
        } else {
            console.log(data);
            console.log("error: " + data.error);
            editMessage(data.error, "email-error");
        }
    });
});

function editMessage(error, error_id) {
    switch (error) {
        // no error
        case 0:
            document.getElementById(error_id).innerHTML = "Changes saved.";
            document.getElementById(error_id).style.color = "green";
            break;

        // username errors
        case 1:
            document.getElementById(error_id).innerHTML = "You are already using this username.";
            document.getElementById(error_id).style.color = "red";
            break;
        case 2:
            document.getElementById(error_id).innerHTML = "This username already exists.";
            document.getElementById(error_id).style.color = "red";
            break;

        // email errors
        case 3:
            document.getElementById(error_id).innerHTML = "You are already using this email.";
            document.getElementById(error_id).style.color = "red";
            break;
        case 4:
            document.getElementById(error_id).innerHTML = "This email is already taken.";
            document.getElementById(error_id).style.color = "red";
            break;
        case 7:
            document.getElementById(error_id).innerHTML = "Invalid email.";
            document.getElementById(error_id).style.color = "red";
            break;


        // password errors
        case 5:
            document.getElementById(error_id).innerHTML = "Your password must be different from your old password";
            document.getElementById(error_id).style.color = "red";
            break;
        case 6:
            document.getElementById(error_id).innerHTML = "Your old password is not correct.";
            document.getElementById(error_id).style.color = "red";
            break;

    }
    
}
