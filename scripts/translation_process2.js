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
    console.log("a");
});
targetLanguage.addEventListener("change", function() {
    isTargetChosen = true;
    translateInput();
    console.log("a");
});

textinput.addEventListener("input", translateInput);
function translateInput() {
    console.log("aa");
    clearTimeout(typingTimer);
    clearTimeout(savingTimer);
    if (isSrcChosen && isTargetChosen) { 
        console.log("bb");

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
            console.log("error" + data.error);
        }
    });
    console.log("cc");

}

function displayTranslation(data) { 
    let words = data.translation;
    words = words.split(" ");
    console.log(words);
    let tags = "";
    for(let i = 0; i < words.length; i++){
        tags +=  "<span class ='word-span'>" + words[i] +" </span>";
    }

    document.querySelector(".outputText").innerHTML = tags;

    const wordSpans = document.querySelectorAll(".word-span");
    const displayWord = document.querySelector(".hovered-word");
    const displayedMeaning = document.querySelector(".word-meaning");


    console.log("HI THERE");
    const nonLetterRegex = /[^a-zA-Z'-]/g;
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



        });
        
    }
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
        console.log("saved to db");
        document.getElementById("download-file").style.display = "block";
    }, 3000); // save the translation to the database after 5 seconds
}



