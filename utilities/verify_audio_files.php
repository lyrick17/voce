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
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $file_ids[] = $row['file_id'];
    }

    // get all the file id in audio_files database
    $audio_query = "SELECT file_id FROM audio_files ORDER BY file_id";
    $audio_result = mysqli_query($dbcon, $audio_query);
    $audio_ids = array();
    while ($row2 = mysqli_fetch_array($audio_result, MYSQLI_ASSOC)) {
        $audio_ids[] = $row2['file_id'];
    }

    // Loop through all the file record in database as well
    foreach ($audio_ids as $audio_id) {
        if (!in_array($audio_id, $file_ids)) {
            // if the file is not in the translation, delete it
            $delquery = "DELETE FROM audio_files WHERE file_id = '$audio_id'";
            $delresult = mysqli_query($dbcon, $delquery);
            $removed = true;
        }
    }

    // Loop through all the files in the audio_files directory
    foreach (glob('audio_files/*') as $file) {
        // we must delete the file that is already been recorded, there might be a chance
        //  when the admin is deleting a file, but a current process is still happening, which can disrupt the user exp


        $latestidquery = "SELECT file_id FROM text_translations ORDER BY text_id DESC LIMIT 1";
        $latestresult = mysqli_query($dbcon, $latestidquery);
        $row = mysqli_fetch_array($latestresult, MYSQLI_ASSOC);
        if (is_file($file)) {
            $file = str_replace('audio_files/', '', $file); // Remove "audio_files/" from the file path
            $file_id = explode("_", $file)[0];
            if (in_array($file_id, $file_ids) || $file_id < $row['file_id']) {
                // if the file is IN the translation or an error recorded file before, delete the file
                unlink('audio_files/' . $file);
                $removed = true;
                
            }
        } elseif (is_dir($file)) {
            $filename = str_replace('audio_files/', '', $file); // Remove "audio_files/" from the file path
            $file_id = explode("_", $filename)[0];
            if (in_array($file_id, $file_ids) || $file_id < $row['file_id']) {
                // if the file is IN the translation or an error recorded file before, delete it
                removeFolder($file);
                $removed = true;
            }
        }
    } 



    if ($removed) {
        $verify_message = "All audio files that were uploaded in audio_files and saved in db but not connected to a translation have been deleted.";
    }
}
?>