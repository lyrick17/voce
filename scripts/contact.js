const contactform = document.getElementById("contact-form");

contactform.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const contact_info = new FormData(this);
    
    
    document.getElementById("contact-return-message").innerHTML = "<i>Sending message...</i>";
    fetch('utilities/contact.php', {
        method: "POST",
        body: contact_info,
    })
    .then(response => response.json())
    .then(data => {
        if (data.error == 0) { 
            document.getElementById("contact-return-message").innerHTML = data.message;
            document.getElementById("contact-return-message").style.color = data.color;
            console.log("contact form submitted");
        } else {
            console.log("error" + data.error);
        }
    });
});