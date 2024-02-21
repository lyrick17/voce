// provides updates on user while translation is happening
//  using fetch api

// get the form of audio to text translation and once submitted

const uploadField = document.getElementById("fileInputLabel");


uploadField.addEventListener("change", function() {
    checkFileSize(this);
});


const form = document.getElementById("form");

form.addEventListener('submit', function(e) {
    //prevent the page from reloading when form is submitted
    // instead, use fetchapi to send the data and retrieve updates
    e.preventDefault();

    const audio_info = new FormData(this);

    // add a 'step' data in the POST variables for server to detect
    //  what current step to take
    audio_info.append('step', 1);
    if (checkFileSize(uploadField)) {
        translationProcess(audio_info);
    } else {
        removeLoading(); 
    }
});


    // each step would receive a response if the step has an error, or success
    //  proceed on next steps if no error, otherwise, break process
async function translationProcess(audio_info) {
    // step 1
    let data = await fetch('utilities/audio_translation.php', {
                        method: "POST",
                        body: audio_info,
                    })
                    .then(response => response.json());
                    
                    
    let removeBGM = data.removeBGM ? data.removeBGM : ' ';

    // step 2 to 5
    if (data.error == 0) {
        for (let i = 2; i <= 6; i++) {
            audio_info.set('step', i);
    
            if (i == 2 && removeBGM == 'off') { continue; } // skip step 2 if not remove BGM 
            
            displayLoadingMessage(i);
            
            data = await fetch('utilities/audio_translation.php', {
                method: "POST",
                body: audio_info,
            })
            .then(response => response.json())
            .catch(error => finishProcess(error));
            
            if (data.error != 0) { break; }                 // if there is an error, stop the loop
    
            // console.log(data);
        }
    }

    finishProcess(data.error);

}


function checkFileSize(uploadField) {
    // validation if user upload is less than 60mb

    let errormessage = document.getElementById('error-message');
    if (uploadField.files.length > 0) {
        const filesize = uploadField.files[0].size;
        const fileMB = filesize / 1024 / 1024;
        //console.log(fileMB);
        if (fileMB > 60) {
            errormessage.innerHTML = "File Size limit is only on 60MB.";
            uploadField.value = "";
            return false;
        } else if (errormessage.innerHTML == "File Size limit is only on 60MB.") {
            errormessage.innerHTML = "";
        }
        return true;
    }
}


function finishProcess(errornumber) {
    if (errornumber == 0) {
        window.location.href = "history_audio.php?translated=1";
    } else {
        window.location.href = "history_audio.php?error=" + errornumber;
    }
}

function displayLoadingMessage(step) {
    let message = document.getElementById("loadingModalMessage");
    switch (step) {
        case 1: // error handling
            message.innerHTML = "Loading...";
            break;
        case 2: // spleeter_env
            message.innerHTML = "Extracting Vocals... (2/6)";
            break;
        case 3: // silence remover
            message.innerHTML = "Cleaning Up Audio... (3/6)";
            break;
        case 4: // whisper transcription
            message.innerHTML = "Transcribing Audio File... (4/6)";
            break;
        case 5: // api translation
            message.innerHTML = "Translating Text... (5/6)";
            break;
        case 6: // saving onto database
            message.innerHTML = "Recording the Data... (6/6)";
    }
        
}