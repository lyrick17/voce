from spleeter.separator import Separator
import sys

file = sys.argv[1]
if __name__ == '__main__':
    # separates the vocal part of song from accompaniment/bg music
    separator = Separator("spleeter:2stems")
    separator.separate_to_file("audio_files/" + file, "audio_files")
