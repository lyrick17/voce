from deep_translator import GoogleTranslator
from flask import Flask, request, jsonify
import json


langs_dict = GoogleTranslator().get_supported_languages(as_dict=True) 
app = Flask('lang_codes')

print(langs_dict)

@app.route("/getlangcodes", methods=["GET"])
def getlangcodes():
    json_data = json.dumps(langs_dict)
    return json_data

@app.route("/translate", methods=["POST"])
def translate():
    json_data = request.get_json()

    src = langs_dict[json_data['src']]
    trg = langs_dict[json_data['trg']]
    translated = GoogleTranslator(source= src, target= trg).translate(json_data['txt'])
    return translated

if __name__ == "__main__":
    app.run(debug=True, port=5000)
