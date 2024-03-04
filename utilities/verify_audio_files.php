<?php 

$verify_message = '';
$removed = false;
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // if admin clicks verify audio files
    // all audio files that were uploaded in audio_files and saved in db but not connected to a translation will be deleted

    // get the date of the file from db
    $query = "SELECT file_id FROM text_translations ORDER BY text_id";

    $result = mysqli_query($dbcon, $query);
    $file_ids = array();
    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $file_ids[] = $row['file_id'];
    }
    
    // get all the file id in audio_files database
    $audio_query = "SELECT file_id FROM audio_files ORDER BY file_id";
    $audio_result = mysqli_query($dbcon, $audio_query);
    $audio_ids = array();
    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $audio_ids[] = $row['file_id'];
    }
    foreach (glob('audio_files/*') as $file) {
        // Loop through all the files in the audio_files directory
        // Your code here
        
        if (is_file($file)) {
            $file = str_replace('audio_files/', '', $file); // Remove "audio_files/" from the file path
            $file_id = explode("_", $file)[0];
            if (!in_array($file_id, $file_ids)) {
                // if the file is not in the translation, delete it
                unlink('audio_files/' . $file);
                $verify_message = "All audio files that were uploaded in audio_files and saved in db but not connected to a translation have been deleted.";
                $removed = true;
                // delete the record in db as well
                // if the file is in the audio_files, delete it
                if (in_array($file_id, $audio_ids)) {
                    $delquery = "DELETE FROM audio_files WHERE file_id = '$file_id'";
                    $delresult = mysqli_query($dbcon, $delquery);
                }
            }
        } elseif (is_dir($file)) {
            $filename = str_replace('audio_files/', '', $file); // Remove "audio_files/" from the file path
            $file_id = explode("_", $filename)[0];
            if (!in_array($file_id, $file_ids)) {
                // if the file is not in the translation, delete it
                removeFolder($file);
                $removed = true;
                // delete the record in db as well
                if (in_array($file_id, $audio_ids)) {
                    $delquery = "DELETE FROM audio_files WHERE file_id = '$file_id'";
                    $delresult = mysqli_query($dbcon, $delquery);
                }
            }
        }
    }
    if ($removed) {
        $verify_message = "All audio files that were uploaded in audio_files and saved in db but not connected to a translation have been deleted.";
    }
}
?>