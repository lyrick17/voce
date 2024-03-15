// Contains Two Main Processes:
// 1. Record Audio through MediaStream API
// 2. Audio to Text Translation submission
// 3. Reset Upload File or Audio Record  

// ---------------------------------------------------------------------------------
// 1. RECORD AUDIO THROUGH MEDIASTREAM API


// contains the record button and playback audio
const mic_btn = document.querySelector('#mic');
const playback = document.querySelector(".playback");

mic_btn.addEventListener("click", ToggleMic);
let is_recording = false;
let recorder = null;


let chunks = [];    // store anything we've record in segments in an array to confine into blob latur on 
let silenceTimeout; // Timer for silence detection
let audio_blob = null;

// FUNCTION - When user clicks the record button
function ToggleMic() {
    // change the status of recording, if it's recording, stop it
    is_recording = !is_recording;

    if (is_recording) {
        console.log('start');
        document.getElementById("audio-translate-btn").disabled = true;
        // Function to request microphone permission
        const getMicrophonePermission = async () => {
            try {
            const stream = await navigator.mediaDevices
                .getUserMedia({ audio: true })
                .then(setupStream);                         // set up the recording once permission is granted
            } catch (error) {
            console.error('Microphone access denied:', error);
            document.getElementById("audio-translate-btn").disabled = false;
            is_recording = !is_recording;                   // Revert recording status if permission denied
            }
        };
        
        getMicrophonePermission();
    } else {
        console.log('stop2');
        recorder.stop();
    }
}

// FUNCTION - permission is granted, record the audio through microphone
function setupStream(stream) {
    recorder = new MediaRecorder(stream);

    console.log('setup');
    
    mic_btn.innerHTML = "Stop Recording";

    // ------ RECORDER START ---------------
        recorder.start();

    // ------ AUDIO DATA AVAILABLE ---------------
    //  this is gonna create a chunk of data so often 
    //  that we can push them into chunks of array to be turned into blob
        recorder.ondataavailable = e => {
            chunks.push(e.data);
        }
    
    // ------ RECORDER STOP ---------------
    // when we stop recording we can create a blob from the chunks - type: format; compression
        recorder.onstop = e => {
            console.log('stopped');
            document.getElementById("audio-translate-btn").disabled = false;
            const blob = new Blob(chunks, { type: "audio/webm; codecs=opus"});
            audio_blob = blob;                                  // save blob on file for audio transfer to fetchapi
            chunks = [];                                        // reset the chunks

            
            const audioURL = window.URL.createObjectURL(blob);  // display the recorded audio in the website
            playback.src = audioURL;

            resetUpload();                                      // remove uploaded files if there is any
            mic_btn.innerHTML = "Record Now";
        }
    
    // ------ FOR DETECTING SILENCE ---------------

    // minimum decibels to detect silence
    const MIN_DECIBELS = -45;

    // main component of the Web Audio API, acting as a hub for creating and managing all of the various audio elements known as nodes.
    const audioContext = new AudioContext();

    // responsible for playing the audio stream. It's an input node that feeds audio data into the audio graph.
    const audioStreamSource = audioContext.createMediaStreamSource(stream);

    // performs real-time frequency and time-domain analysis. 
    // used to extract data about the audio for visualization or other purposes. 
    // can analyze the audio data in various ways, such as frequency or waveform.
    const analyser = audioContext.createAnalyser();

    analyser.minDecibels = MIN_DECIBELS;

    audioStreamSource.connect(analyser);

    // Each bin represents a range of frequencies, and the total number of bins
    // determines the frequency resolution of the analyser. The frequency resolution is the ability to distinguish between
    // different frequencies in the audio signal. A higher number of bins means a higher frequency resolution.
    const bufferLength = analyser.frequencyBinCount;

    // stores the frequency data. 
    // This array will hold the frequency data that is extracted from the audio stream by the analyser. The Uint8Array is
    // used because it is a typed array that holds 8-bit unsigned integers, which is suitable for storing frequency data.
    const domainData = new Uint8Array(bufferLength);

    detectSound(analyser, domainData, bufferLength);
}

// FUNCTION - continuously check for silence, to stop recording if silence = 5 seconds
function detectSound(analyser, domainData, bufferLength) {
    let soundDetected = false;

    const checkSound = () => {
        analyser.getByteFrequencyData(domainData);

        for (let i = 0; i < bufferLength; i++) {
            if (domainData[i] > 0) {
                soundDetected = true;
                break;
            }
        }

        if (soundDetected) {
            console.log('Sound detected');
            clearTimeout(silenceTimeout);
            soundDetected = false;
            // Reset the timer for silence detection
            silenceTimeout = setTimeout(() => {
                if (is_recording) {
                    is_recording = false;
                    recorder.stop();
                    console.log("Silence detected, recording stopped.");
                    document.getElementById("audio-translate-btn").disabled = false;
                }
            }, 5000); // 5 seconds of silence to stop recording
        }

        if (is_recording) window.requestAnimationFrame(checkSound);
    };

    checkSound();
}





// ---------------------------------------------------------------------------------
// 2. AUDIO TO TEXT TRANSLATION SUBMISSION




// provides updates on user while translation is happening
//  using fetch api

// get the form of audio to text translation and once submitted

const uploadField = document.getElementById("fileInputLabel");

uploadField.addEventListener("change", function() {
    if (checkFileSize(this)) {      
        resetRecord();              // reset the record once user uploads file
    };
});


const form = document.getElementById("form");

form.addEventListener('submit', function(e) {
    e.preventDefault();

    const audio_info = new FormData(this);

    // add a 'step' data in the POST variables for server to detect what current step to take
    // add a 'record' data in the POST variables if user recorded
    audio_info.append('step', 1);
    if (audio_blob) { audio_info.append('record', audio_blob); }

    console.log(audio_info.values());

    // validate if file is 60mb or there is a record
    if (checkFileSize(uploadField) || audio_blob) {
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
        window.location.href = "index.php?translated=1";
    } else {
        window.location.href = "index.php?error=" + errornumber;
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


// ---------------------------------------------------------------------------------
// 3. RESET UPLOAD FILE OR AUDIO RECORD

function resetRecord() {
    audio_blob = null;
    playback.src = "";
    chunks = [];
    if (is_recording) {
        is_recording = false;
        recorder.stop();
    }
}

function resetUpload() {
    uploadField.value = "";
}