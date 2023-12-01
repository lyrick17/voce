import subprocess

def remove_silence(input_file, output_file):
    command = ['ffmpeg', '-i', input_file, '-af', 'silenceremove=1:0:-50dB', output_file]
    subprocess.run(command, check=True)

# Use the function
remove_silence('input.mp3', 'output.mp3')