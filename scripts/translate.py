import sys
import os
import whisper
import subprocess
import time
import io


sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

# model sizes: tiny, base, small, medium, large
model = whisper.load_model("base")

# TAKE NOTE: make sure the filename does not have any space
filename = sys.argv[1]

if __name__ == '__main__':
    # specified language is a need so the model won't assume it always as english i guess?
    #   - the language codes are in notes/language_support.txt
    #   - kelangan natin na i-connect yung language supported ng whisper at ng api natin
    # also, plan is model size would come from the user 
    

    # make sure the audio has been processed in spleeter first        
    
    result = model.transcribe("audio_files/" + filename + "/vocals.wav")
    
    
    #result = model.transcribe("audio_files/" + filename + "/vocals.wav", language="tl")
    print(result["text"])


#subprocess.call(['python', 'scripts/separate.py'])
#time.sleep(1)

''' ------- PREVIOUS CODE
import sys
import whisper

model = whisper.load_model("base")
result = model.transcribe('audio_files/' + sys.argv[1])
print(result["text"])
'''
