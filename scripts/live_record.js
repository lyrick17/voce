const mic_btn = document.querySelector('#mic');

const playback = document.querySelector(".playback");

mic_btn.addEventListener("click", ToggleMic);

let can_record = true;
let is_recording = false;
let recorder = null;

let chunks = []; // store anything we've record in segments in an array to confine into blob latur on

/*function setup_audio() {
    console.log("setup");
    // check first if MediaAPI is available and we can get reference to our mic
    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
        navigator.mediaDevices
            .getUserMedia({ 
                audio: true 
            })
            .then(setup_stream)
            .catch(allow_record);
    }
}*/

function setup_stream(stream) {
    recorder = new MediaRecorder(stream);

    //  this is gonna create a chunnnk of data so often that we can push them into chunks of array turned into blob
    recorder.ondataavailable = e => {
        chunks.push(e.data);
    }

    recorder.onstop = e => {
        // when we stop recording we can create a blob from the chunks
        // audio ogg = format
        // codecs=opus = compression
        const blob = new Blob(chunks, { type: "audio/mpeg; codecs=opus"});
        chunks = [];
        const audioURL = window.URL.createObjectURL(blob);
        playback.src = audioURL;
    }

    recorder.start();
}

function ToggleMic() {
    is_recording = !is_recording;

    if (is_recording) {
        const getMicrophonePermission = async () => {
            try {
                const stream = await navigator.mediaDevices
                                            .getUserMedia({ audio: true })
                                            .then(setup_stream)
                                            .catch(allow_record);
                // You can now use the stream for audio recording or other purposes.
            } catch (error) {
                console.error('Microphone access denied:', error);
            }
            };
            
            // Call the function to request permission
        
        getMicrophonePermission();
    } else {
        recorder.stop();
    }


    
}

