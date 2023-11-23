import sys
import whisper
import io

# allows other characters (ex. jp, kr) to be printed and passed to PHP

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

model = whisper.load_model("small")

filename = sys.argv[1]
removeBGM = sys.argv[2]
extension = sys.argv[3]
srcLanguage = sys.argv[4]
if __name__ == '__main__':

    # make sure the audio has been processed in spleeter first        

    # script will transcribe the audio file, then 'text' and 'language' will be stored
    # in a separate dictionary to be printed out
    # this allows auto detect language
    output = {}

    filePath = "audio_files/" + filename + ("." + extension if removeBGM == "off" else "/vocals.wav")

    if srcLanguage == "auto":
        result = model.transcribe(filePath)
    else:
        result = model.transcribe(filePath, language=srcLanguage)
        
    output = {"text": result["text"], "language": result["language"]}
    print(output)

