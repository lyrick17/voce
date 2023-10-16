import sys
import whisper

model = whisper.load_model("large")
result = model.transcribe("audio_files/lyrick.mp3")
print(result['text'])
