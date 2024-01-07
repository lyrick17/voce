<?php
 require("mysql/mysqli_connect.php"); 
 require("utilities/delete_files.php");

 
if($_POST['clearAll'] == 'true'){
    $userId = $_POST['userId'];
    // Deletes all rows corresponding to user from audio_files table if it's an audio to text translation
    if($_POST['fromAudio'] == 1){

        $deleteQuery = mysqli_prepare($dbcon, "DELETE FROM text_translations WHERE user_id = ? AND from_audio_file = 1");
        bindAndExec($deleteQuery, "s", $userId);

        $deleteQuery = mysqli_prepare($dbcon, "DELETE FROM audio_files WHERE user_id = ?");
        bindAndExec($deleteQuery, "s", $userId);


        deleteAllAudioFiles($userId);
        
    }
    else{            
        // Deletes all rows corresponding to user from text_translation table
        $deleteQuery = mysqli_prepare($dbcon, "DELETE FROM text_translations WHERE user_id = ? AND from_audio_file = 0");
        bindAndExec($deleteQuery, "s", $userId);
    }
 }

 elseif($_POST['deleteRows'] == 'true'){

    $userId = $_POST['userId'];

    if($_POST['fromAudio'] == 1){
        $rowsToDelete = json_decode($_POST['rowsToDelete']);
        $filesToDelete = json_decode($_POST['filesToDelete']);
        for($i = 0; $i < count($rowsToDelete); $i++){
                $deleteRows = $rowsToDelete[$i];
                $deleteFiles =$filesToDelete[$i];
                

                deleteAudioFile($deleteFiles, $userId);

                
                $deleteQuery = mysqli_prepare($dbcon, "DELETE FROM text_translations WHERE text_id = ?");
                bindAndExec($deleteQuery, "s", $deleteRows);

                $deleteQuery = mysqli_prepare($dbcon, "DELETE FROM audio_files WHERE file_id = ?");
                bindAndExec($deleteQuery, "s", $deleteFiles);

                
            }
    }

    else{
        foreach(json_decode($_POST['rowsToDelete']) as $rowNum){
            $deleteQuery = mysqli_prepare($dbcon, "DELETE FROM text_translations WHERE text_id = ?");
            bindAndExec($deleteQuery, "s", $rowNum);        
        }
    }
    
 }

 else{
    //deletes row from database

    $userId = $_POST['userId'];

    $rowId = $_POST['rowId'];
    $fileId = $_POST['fileId'];
    $deleteQuery = mysqli_prepare($dbcon, "DELETE FROM text_translations WHERE text_id = ?");
    bindAndExec($deleteQuery, "s", $rowId); 

    //deletes audio file record from database if it's an audio to text translation
    if($_POST['fileId'] != null){
        
        deleteAudioFiles($fileId, $userId);
        
        $deleteQuery = mysqli_prepare($dbcon, "DELETE FROM audio_files WHERE file_id = ?");
        bindAndExec($deleteQuery, "s", $fileId); 
        
    }
 }


//query for text to text history
if($_POST['fromAudio'] == 0)
    $q = "SELECT * FROM text_translations WHERE user_id = ". $_POST['userId'] ." AND from_audio_file = 0 ORDER BY translation_date DESC";
//query for audio to text history
else
    $q = "SELECT * FROM text_translations t INNER JOIN audio_files a ON t.file_id = a.file_id WHERE t.user_id = ". $_POST['userId'] ." AND a.user_id = ". $_POST['userId'] ." AND t.from_audio_file = 1 ORDER BY translation_date DESC";

$sql = mysqli_query($dbcon, $q);
$result = mysqli_fetch_all($sql, MYSQLI_ASSOC);

exit(json_encode($result));

function bindAndExec($stmt, $markers, $value){
    mysqli_stmt_bind_param($stmt, $markers, $value);
    mysqli_stmt_execute($stmt);
}



function removeFolder($path) {
    // process of deleting the folder in audio files
    if (is_dir($path)) {
        $iterator = new DirectoryIterator($path);
    
        
        // loop through each content inside the folder
        foreach($iterator as $file) {
            if ($file->isFile()) {
                unlink($file->getRealPath()); // get the relative path and delete the file
            } elseif (!$file->isDot() && $file->isDir()) {
                rmdir($file->getRealPath()); // get the relative path and delete the folder
            }
        }
        
        // finally, delete the now-empty folder
        rmdir($path);
    }
}

function fetchAudioContent($fileid) {
    global $dbcon;
    $datequery = "SELECT file_name, DATE_FORMAT(upload_date, '%m%d%Y_%H%i%s') AS formatted_date 
                        FROM audio_files WHERE file_id = '$fileid'";
    $dateresult = mysqli_query($dbcon, $datequery);

    $audiofile =  mysqli_fetch_assoc($dateresult);

    // get the filename and extension
    $filename = pathinfo($audiofile['file_name'], PATHINFO_FILENAME);
    $extension = pathinfo($audiofile['file_name'], PATHINFO_EXTENSION);

    // put filename, extension and date in array, these infos are needed to select
    //  specific record from the database in order to delete a specific file
    $info = array("filename" => $filename, 
                "extension" => $extension,
                "formatted_date" => $audiofile['formatted_date']);

    return $info;
}

function deleteAudioFile($fileid) {
    //get the audio file content from the database, to be used on locating the filename in audio_files folder
    $audiofile = fetchAudioContent($fileid);

    // remove the audio file uploaded by user
    $filenameMask = "audio_files/" . $userId . "_" . $audiofile['filename'] . $audiofile['formatted_date'] . "." . $audiofile['extension']; 
    if (file_exists($filenameMask)) 
        unlink($filenameMask);
    
    // remove the folder created on specific file and all its contents
    $folderMask = "audio_files/" . $userId . "_" . $audiofile['filename'] . $audiofile['formatted_date'];
    removeFolder($folderMask);
}

function deleteAllAudioFiles() {
    // since we delete all, get all the files and folder that has the user_id in file
    $mask = "audio_files/" . $userId . "_*";

    // put all the folder/filenames that matches the $mask in an array
    $items = glob($mask);

    
    foreach ($items as $file) {
    
        // check if it's an audio file or a folder, then delete them 
        //  using their designated step
        if (is_file($file)) {
            unlink($file);
        } elseif (is_dir($file)) {
            removeFolder($file);
        }
    
    }
}