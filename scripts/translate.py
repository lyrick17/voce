import sys
import os
import whisper
import subprocess
import time

model = whisper.load_model("base")

# TAKE NOTE: make sure the filename does not have any space
filename = sys.argv[1]
removeBGM = sys.argv[2]
extension = sys.argv[3]
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




''' ------- PREVIOUS CODE
import sys
import whisper

model = whisper.load_model("base")
result = model.transcribe('audio_files/' + sys.argv[1])
print(result["text"])
'''
