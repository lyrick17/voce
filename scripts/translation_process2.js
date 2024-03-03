// real time translation on t2t

const textinput = document.getElementById("text-input");

let typingTimer;                // this timer is for listening to the text area
let savingTimer;                // this timer is for saving the translation to the database
let isSrcChosen = false;
let isTargetChosen = false;

const sourceLanguage = document.getElementById("sourceLanguage");
const targetLanguage = document.getElementById("targetLanguage");


sourceLanguage.addEventListener("change", function() {
    isSrcChosen = true;
    translateInput();
});
targetLanguage.addEventListener("change", function() {
    isTargetChosen = true;
    translateInput();
});

textinput.addEventListener("input", translateInput);

function translateInput() {
    clearTimeout(typingTimer);
    clearTimeout(savingTimer);
    if (isSrcChosen && isTargetChosen) { 

        typingTimer = setTimeout(function() {
            realTimeTranslate();    // translate the text after a pause
            document.getElementById("error-message").innerHTML = '';
        }, 600); // Adjust the duration (in milliseconds) as needed
    } else if (isSrcChosen && isTargetChosen && textinput.value == '') {
        document.getElementById("error-message").innerHTML = 'Please type text to be translated.';
    }
    document.getElementById("download-file").style.display = "none";
}
function realTimeTranslate() {
    const form = document.getElementById("myForm");
    const text_info = new FormData(form);

    fetch('utilities/text_translation.php', {
        method: "POST",
        body: text_info,
    })
    .then(response => response.json())
    .then(data => {
        if (data.error == 0) { 
            displayTranslation(data);
            timerForSavingDB();
            console.log("translated");
        } else {
            finishProcess(data.error);
            console.log("error");
        }
    });


}

function displayTranslation(data) { 
    document.getElementById("text-output").innerHTML = data.translation;
}


function finishProcess(errornumber) {
    switch (errornumber) {
        case 1: // user did not chooose language
            document.getElementById("error-message").innerHTML = 'Please select a source/translated language.';
            break;
        case 2: // user did not enter text
            document.getElementById("error-message").innerHTML = 'Please choose two different language.';
            break;
        case 4: // user added unprovided choices
            document.getElementById("error-message").innerHTML = 'Please choose only on the provided models/languages.';
            break;
    }
}

function timerForSavingDB() {
    savingTimer = setTimeout(function() {
        const form = document.getElementById("myForm");
        const text_info = new FormData(form);
        text_info.append("translation", document.getElementById("text-output").innerHTML);
        fetch('utilities/text_translation_save.php', {
            method: "POST",
            body: text_info,
        })
        .then(response => response.json());
        console.log("saved to db");
        document.getElementById("download-file").style.display = "block";
    }, 3000); // save the translation to the database after 5 seconds
}
