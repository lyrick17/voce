import sys
import os
import whisper
import subprocess
import time

model = whisper.load_model("base")

# TAKE NOTE: make sure the filename does not have any space
filename = sys.argv[1]

if __name__ == '__main__':

    #subprocess.call(['python', 'separate.py'])
    #time.sleep(1)

    # make sure the audio has been processed in spleeter first        
    
    result = model.transcribe("audio_files/" + filename + "/vocals.wav")
    print(result["text"])




''' ------- PREVIOS CODE
import sys
import whisper

model = whisper.load_model("base")
result = model.transcribe('audio_files/' + sys.argv[1])
print(result["text"])
'''
