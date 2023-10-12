import sys
import whisper

model = whisper.load_model("base")
result = model.transcribe('audio_files/' + sys.argv[1])
print(result["text"])