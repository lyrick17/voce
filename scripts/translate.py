import sys
import whisper
import io

# allows other characters (ex. jp, kr) to be printed and passed to PHP
sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

model = whisper.load_model("small")

filename = sys.argv[1]
removeBGM = sys.argv[2]
extension = sys.argv[3]
if __name__ == '__main__':

    # make sure the audio has been processed in spleeter first        

    # script will transcribe the audio file, then 'text' and 'language' will be stored
    # in a separate dictionary to be printed out
    output = {}

    # Translates uploaded file directly if checkbox is not checked
    if removeBGM == "off":
        result = model.transcribe("audio_files/" + filename + "." + extension)
    
    # Translates the extracted vocals if checkbox is checked
    else:
        result = model.transcribe("audio_files/" + filename + "/vocals.wav")

    output = {"text": result["text"], "language": result["language"]}
    print(output)

# --- additional lines of code to be added when language is also needed ---
# srcLanguage = sys.argv[4]
    # result = model.transcribe("audio_files/" + filename + "." + extension, language=srcLanguage)

#languages = whisper.languages()
