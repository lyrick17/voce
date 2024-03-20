from deep_translator import GoogleTranslator
from flask import Flask, request, jsonify
from PyDictionary import PyDictionary
import pandas as pd
import whisper
import json

# used for creating spleeter environment and calling ffmpeg
# much safer than shell_exec of PHP
import os
import subprocess
import shlex

words_def = pd.read_csv('scripts/cleaned_def.csv', sep=',',engine='python',encoding='utf-8-sig')
words_def.set_index('Word', inplace=True)
model = whisper.load_model("small")
langs_dict = GoogleTranslator().get_supported_languages(as_dict=True) 
app = Flask('lang_codes')

@app.route("/get_meanings", methods=["POST"])
def get_meaning():
    json_data = request.get_json()
    word = json_data['word']
    definitions = words_def.loc[word.capitalize(), :]
    try:
        word_definitions = definitions['Definition'].reset_index(drop=True).to_list()
        word_pos = definitions['POS'].reset_index(drop=True).to_list()

        pos_n_def = {'POS' : word_pos,
                    'Definition' : word_definitions}
        
        return json.dumps(pos_n_def)
    except:
        return json.dumps(definitions.to_dict())

@app.route("/transcribe", methods=["POST"])
def transcribe():
    json_data = request.get_json()
    print(json_data)

    filePath = "audio_files/" + json_data['fname'] + "/audio_processed.mp3"
    #filePath = "audio_files/" + filename + ("." + extension if removeBGM == "off" else "/vocals.wav")
    if json_data['src'] == "auto":
        result = model.transcribe(filePath)
    else:
        result = model.transcribe(filePath, language=json_data['src'])
        
    output = {"text": result["text"], "language": result["language"]}
    print(str(output))
    return output

@app.route("/getlangcodes", methods=["GET"])
def getlangcodes():
    json_data = json.dumps(langs_dict)
    return json_data

@app.route("/translate", methods=["POST"])
def translate():
    json_data = request.get_json()
    print(json_data)
    src = langs_dict[json_data['src']]
    trg = langs_dict[json_data['trg']]
    translated = GoogleTranslator(source= src, target= trg).translate(json_data['txt'])
    return translated


@app.route("/spleeter", methods=["POST"])
def spleeter():
    # needs filename with extension
    json_data = request.get_json()
    file = json_data['file']
    file = shlex.quote(file)
    print(json_data)
    
    # Call the function to activate the spleeter environment and run separate.py
    # code for Python 3.8 system
    #three_eight(file)

    # code for Python 3.11 system with py3.8 spleeter_env virtual env
    three_eleven(file)
    

    return "spleeter"

@app.route("/removesilence", methods=["POST"])
def removesilence():
    # TO BE FIXED
    json_data = request.get_json()
    file = json_data['file']
    filename = json_data['filename']
    removeBGM = json_data['removeBGM']
    if removeBGM == "on":
        input_path = os.path.join("audio_files", shlex.quote(filename), "vocals.wav")
    else:
        input_path = os.path.join("audio_files", shlex.quote(file))
    

    # Construct the input and output file paths with proper escaping
    output_path = os.path.join("audio_files", shlex.quote(filename), "audio_processed.mp3")

    # Build the FFmpeg command list
    ffmpeg_command = [
        "ffmpeg",
        "-y",  # Overwrite output file if it exists
        "-i", input_path,
        "-af", "silenceremove=stop_periods=-1:stop_duration=1:stop_threshold=-50dB",
        output_path
    ]

      # Execute the FFmpeg command with error handling
    try:
        output = subprocess.run(ffmpeg_command, check=True, capture_output=True, text=True)
        print("ffmpeg success")
        return "success"
    except subprocess.CalledProcessError as e:
        print(f"Error processing audio with FFmpeg: {e}")
        print("ffmpeg error")
        return "errors"

@app.route("/testcon", methods=["GET"])
def testcon():
    return "connected"

if __name__ == "__main__":
    app.run(debug=True, port=5000)



# functions on extracting vocals, 
#   for spleeter_env 
#   for python 3.8
def three_eleven(file):
    if os.name == 'nt':  # Windows
        activate_cmd = os.path.join('spleeter_env', 'Scripts', 'activate')
    else:  # Linux, macOS
        activate_cmd = os.path.join('spleeter_env', 'bin', 'activate')

    # Full command to activate and run separate.py
    full_cmd = f'{activate_cmd} && python scripts/separate.py {file} && deactivate'

    
    # Use subprocess for safe and reliable execution
    try:
        subprocess.run(full_cmd.split(), shell=True, check=True)
    except subprocess.CalledProcessError as e:
        print(f"Error activating spleeter_env or running separate.py: {e}")

def three_eight(file):
    call_spleeter = ["python", "scripts/separate.py", file]
    try:
        subprocess.run(call_spleeter, check=True)
    except subprocess.CalledProcessError as e:
        print(f"Error activating spleeter_env or running separate.py: {e}")
 


# from deep_translator import GoogleTranslator
# from flask import Flask, request
# import json
# import whisper
# model = whisper.load_model("medium")
# langs_dict = GoogleTranslator().get_supported_languages(as_dict=True) 
# app = Flask('lang_codes')


# @app.route("/getlangcodes", methods=["GET"])
# def getlangcodes():
#     json_data = json.dumps(langs_dict)
#     return json_data

# @app.route("/transcribe", methods=["POST"])
# def transcribe():
#     json_data = request.get_json()
#     src = langs_dict[json_data['src']]
#     trg = langs_dict[json_data['trg']]

#     filePath = "audio_files/" + json_data['fname'] + "/audio_processed.mp3"

#     if json_data['removeBGM'] == "auto":
#         result = model.transcribe(filePath)
#     else:
#         result = model.transcribe(filePath, language= json_data['src'])
        
#     transcript = result["text"]

#     translated = GoogleTranslator(source= src, target= trg).translate(transcript)
#     return translated

# @app.route("/translate", methods=["POST"])
# def translate():
#     json_data = request.get_json()

#     src = langs_dict[json_data['src']]
#     trg = langs_dict[json_data['trg']]
#     translated = GoogleTranslator(source= src, target= trg).translate(json_data['txt'])
#     return translated

# if __name__ == "__main__":
#     app.run(debug=True, port=5000)
