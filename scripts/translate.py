import sys
import os
import whisper
import subprocess
import time
import io

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

filename = sys.argv[1]
modelSize = sys.argv[2]
removeBGM = sys.argv[3]
extension = sys.argv[4]

model = whisper.load_model(modelSize)


if __name__ == '__main__':

    #subprocess.call(['python', 'scripts/separate.py'])
    #time.sleep(1)

    # make sure the audio has been processed in spleeter first        

    # Translates uploaded file directly if checkbox is not checked
    if removeBGM == "off":
        result = model.transcribe("audio_files/" + filename + "." + extension)
        print(result["text"])
    
    # Translates the extracted vocals if checkbox is checked
    else:
        result = model.transcribe("audio_files/" + filename + "/vocals.wav")
        print(result["text"])


