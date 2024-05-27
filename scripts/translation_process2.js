// real time translation on t2t

const textinput = document.getElementById("text-input");

let typingTimer;                // this timer is for listening to the text area
let savingTimer;                // this timer is for saving the translation to the database
let gentleTimer;                // this timer is for gentle reminder to choose language
let isSrcChosen = false;
let isTargetChosen = false;

const sourceLanguage = document.getElementById("sourceLanguage");
const targetLanguage = document.getElementById("targetLanguage");


gentleMessage = document.getElementById("gentle-message");
gentleMessage.style.color = "red";

// checks the selected language, source and target
// returns boolean if they select a language
function checkForm(lang) {
    const chkform = document.getElementById("myForm");
    const info = new FormData(chkform);
    if (lang == 'src') {
        let source = info.get("src");
        return source != '';
    } else if (lang == 'target') {
        let target = info.get("target");
        return target != '';
    }
}

sourceLanguage.addEventListener("change", function() {
    if (checkForm('src')) {
        isSrcChosen = true;
    } else {
        isSrcChosen = false;
    }
    translateInput();
});
targetLanguage.addEventListener("change", function() {
    if (checkForm('target')) {
        isTargetChosen = true;
    } else {
        isTargetChosen = false;
    }
    
    translateInput();
});

textinput.addEventListener("input", translateInput);
function translateInput() {
    clearTimeout(typingTimer);
    clearTimeout(savingTimer);
    clearTimeout(gentleTimer);

    const form = document.getElementById("myForm");
    const text_info = new FormData(form);
    let source = text_info.get("src");
    let target = text_info.get("target");


    if (isSrcChosen && isTargetChosen) { 
        typingTimer = setTimeout(function() {
            realTimeTranslate();    // translate the text after a pause
            document.getElementById("gentle-message").innerHTML = '';
            document.getElementById("error-message").innerHTML = '';
        }, 600); // Adjust the duration (in milliseconds) as needed

    } else if (isSrcChosen && isTargetChosen && textinput.value == '') {
        document.getElementById("gentle-message").innerHTML = '';
        document.getElementById("error-message").innerHTML = 'Please type text to be translated.';
    
    } else {
        if (textinput.value != '' && document.getElementById("gentle-message").innerHTML == '') { 
            gentleTimer = setTimeout(function() {   // translate the text after a pause
                gentleMessage.innerHTML = 'Please select source and target language.';
            }, 3000); // after 3 seconds, notify user that has not chosen a language yet
            
        }
    }

    textInput
    document.getElementById("download-file").style.display = "none";
}


function realTimeTranslate() {

    const form = document.getElementById("myForm");
    const text_info = new FormData(form);

    errorMsg = ["~<b>Voce Error</b>: Please connect to the Internet to continue translating~", 
                "~<b>Voce Error</b>: Could not translate input~"]

    // validate if text input is not empty
    if (text_info.get("text") != '') {
        
        document.getElementById("gentle-message").innerHTML = '';
        document.getElementById("translating-message").innerHTML = 'translating...';
        fetch('utilities/text_translation.php', {
            method: "POST",
            body: text_info,
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById("translating-message").innerHTML = '';
            if (data.error == 0) { 
                if (!errorMsg.includes(data.translation)) { 
                    // If no error message, display the translation
                    displayTranslation(data);
                    timerForSavingDB(data);
                } else {
                    // If error, display through gray message
                    document.getElementById("translating-message").innerHTML = data.translation;
                    document.querySelector(".outputText").innerHTML = '';
                }
            } else {
                finishProcess(data.error);
            }
        });
    }



}

function displayTranslation(data) { 


    let words = data.translation;
    words = words.split(" ");
    
    let tags = "";
    for(let i = 0; i < words.length; i++){
        tags +=  "<span class ='word-span'>" + words[i] +" </span>";
    }

    // Determine if the output is in English or not
    if (targetLanguage.value == 'english') {
        // output is in english, add <span> on each word
        document.querySelector(".outputText").innerHTML = tags;
        document.getElementById("click-english").innerHTML = "Click on an English word to view it's meaning!";
    } else {
        // output is not in english, display the simple text
        document.querySelector(".outputText").innerHTML = data.translation;
        document.getElementById("click-english").innerHTML = "";
    }
    updateBox2Height();

    const wordSpans = document.querySelectorAll(".word-span");
    const displayWord = document.querySelector(".hovered-word");
    const displayedMeaning = document.querySelector(".word-meaning");

    const nonLetterRegex =  /[^a-zA-Z-\p{Emoji}]/gu;
    wordSpans.forEach((wordspan) => {
    wordspan.addEventListener("click", () => {
        console.log("clicked");
        // Displays the word on the dictionary div 
        displayedMeaning.textContent = "Loading";
        let clickedWord = wordspan.textContent;
        clickedWord = clickedWord.replace(nonLetterRegex, "")
        displayWord.textContent = clickedWord;
        displayMeaning(clickedWord);
    });
    });


    
    // Display the meaning of the word
    async function displayMeaning(word){
        const data = new FormData();
        data.append("word", word);
    
        await fetch('utilities/getmeaning.php', {
            method : 'POST',
            body: data
        }).then((res) => res.json())
        .then((response) => {

            console.log(response);
            displayedMeaning.textContent = "";
    
            Object.keys(response).forEach(key => {
    
                displayedMeaning.innerHTML = displayedMeaning.innerHTML + String(key) + ": <br>";
    
                for(let i = 0; i < response[key].length; i++){
                    displayedMeaning.innerHTML = displayedMeaning.innerHTML + (i+1) + ".) " + response[key][i] + "<br>";
                }
    
                displayedMeaning.innerHTML = displayedMeaning.innerHTML + "<br>";
            });
    
    
    
        }).catch((error) => {
            displayedMeaning.textContent = "The definition of " + word + " is not recorded in our dictionary.";
        });
        
    }
}


function finishProcess(errornumber) {
    switch (errornumber) {
        /*case 1: // user did not chooose language
            document.getElementById("error-message").innerHTML = 'Please select a source/translated language.';
            break;
        case 2: // user did not enter text
            document.getElementById("error-message").innerHTML = 'Please choose two different language.';
            break;
        */
        case 4: // user added unprovided choices
            document.getElementById("error-message").innerHTML = 'Please choose only on the provided languages.';
            break;
        case 5: // user added unprovided choices
            document.getElementById("error-message").innerHTML = 'Character limit is only 5,000.';
            break;
    }
}

function timerForSavingDB(data) {
    errorMsg = ["~<b>Voce Error</b>: Please connect to the Internet to continue translating~", 
                "~<b>Voce Error</b>: Could not translate input~"]

    if (!errorMsg.includes(data.translation)) {
        // provide a timer before saving to db, timer is reset when user inputs
        savingTimer = setTimeout(function() {

            const form = document.getElementById("myForm");
            const text_info = new FormData(form);
            let output = document.getElementById("text-output").innerHTML;

            // remove all the html span elements 
            output = output.replaceAll("\"word-span\"", "");
            output = output.replaceAll("<span class=>", "");
            output = output.replaceAll("</span>", "");
            text_info.append("translation", output);

            fetch('utilities/text_translation_save.php', {
                method: "POST",
                body: text_info,
            })
            .then(response => response.json());

            document.getElementById("download-file").style.display = "block";
        }, 3000); // save the translation to the database after 3 seconds
    }
}

function capitalizeFirstLetter(str) {
    str = str.toLowerCase()
    return str.charAt(0).toUpperCase() + str.slice(1);
  }


