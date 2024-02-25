from deep_translator import GoogleTranslator
from flask import Flask, request, jsonify
import json

translator = GoogleTranslator()  # Create a single instance

app = Flask('lang_codes')

@app.route("/process_data", methods=["GET"])
def return_langcodes():
    # Process data as needed (e.g., parse JSON)
    translated = GoogleTranslator(source='auto', target='de').translate("keep it up, you are awesome")
    langs_dict = GoogleTranslator().get_supported_languages(as_dict=True) 

    json_data = json.dumps(langs_dict)
    return json_data

if __name__ == "__main__":
    app.run(debug=True, port=5000)
