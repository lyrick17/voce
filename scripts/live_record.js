// contains the record button and playback audio
const mic_btn = document.querySelector('#mic');
const playback = document.querySelector(".playback");


mic_btn.addEventListener("click", ToggleMic);



let is_recording = false;
let recorder = null;

// store anything we've record in segments in an array 
//  to confine into blob latur on
let chunks = []; 

let silenceTimeout; // Timer for silence detection

// FUNCTION - When user clicks the record button
function ToggleMic() {
    // change the status of recording, if it's recording, stop it
    is_recording = !is_recording;

    if (is_recording) {
        console.log('start');

        // Function to request microphone permission
        const getMicrophonePermission = async () => {
            try {
            const stream = await navigator.mediaDevices
                .getUserMedia({ audio: true })
                .then(setupStream); // set up the recording once permission is granted
            } catch (error) {
            console.error('Microphone access denied:', error);
            is_recording = !is_recording;   // Revert recording status if permission denied
            }
        };
        
        // Call the function to request permission
        
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
    
    // RECORDER START ---------------
    recorder.start();

    // AUDIO DATA AVAILABLE ---------------
    //  this is gonna create a chunk of data so often 
    //  that we can push them into chunks of array to be turned into blob
    recorder.ondataavailable = e => {
        chunks.push(e.data);
    }
    
    // RECORDER STOP ---------------
    // when we stop recording we can create a blob from the chunks
    // audio ogg = format
    // codecs=opus = compression
    recorder.onstop = e => {
        console.log('stopped');
        const blob = new Blob(chunks, { type: "audio/mpeg; codecs=opus"});
        
        // reset the chunks
        chunks = [];

        // display the recorded audio in the website
        const audioURL = window.URL.createObjectURL(blob);
        playback.src = audioURL;

        document.getElementById("input-record").value = blob;
        console.log(blob);
    }
    
    // FOR DETECTING SILENCE

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
                }
            }, 5000); // 5 seconds of silence to stop recording
        }

        if (is_recording) window.requestAnimationFrame(checkSound);
    };

    checkSound();
}