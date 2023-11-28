// provides updates on user while translation is happening
//  using fetch api

const form = document.getElementById("form");

form.addEventListener('submit', function(e) {
    //prevent the page from reloading when form is submitted
    // instead, use fetchapi to send the data
    e.preventDefault();

    // duck loading must be stopped too

    const audio_info = new FormData(this);
    audio_info.append('step', 1);
    console.log([...audio_info]);

    translationProcess(audio_info);
});



async function translationProcess(audio_info) {
    // step 1
    let data = await fetch('translation.php', {
                        method: "POST",
                        body: audio_info,
                    })
                    .then(response => response.json());
                    
        // console.log(data);
    let removeBGM = data.removeBGM;

    // step 2 to 5
    for (let i = 2; i <= 5; i++) {
        audio_info.set('step', i);

        if (data.error != 0) { audioProcessError(data.error); break; } // if there is an error, stop the loop
        if (i == 2 && removeBGM == 'off') { continue; } // skip step 2 if not remove BGM 

        displayLoadingMessage(i);
            //console.log(audio_info.get('step'));
        
        data = await fetch('translation.php', {
                        method: "POST",
                        body: audio_info,
                    })
                    .then(response => response.json());
                    

        // console.log(data);
    }
    if (data.error == 0) {
        window.location.href = "history_audio.php?translated=1";
    } else {

    }


}



function displayLoadingMessage(step) {
    let message = document.getElementById("loadingModalMessage");
    switch (step) {
        case 1:
            document.getElementById("loadingModalMessage").innerHTML = "Loading...";
            break;
        case 2:
            document.getElementById("loadingModalMessage").innerHTML = "Extracting Vocals... (2/5)";
            break;
        case 3:
            document.getElementById("loadingModalMessage").innerHTML = "Transcribing Audio File... (3/5)";
            break;
        case 4:
            document.getElementById("loadingModalMessage").innerHTML = "Translating Text... (4/5)";
            break;
        case 5:
            document.getElementById("loadingModalMessage").innerHTML = "Recording the Data... (5/5)";
            break;
    }
        
}



function audioProcessError(errornumber) {
    window.location.href = "history_audio.php?error=" + errornumber;
}