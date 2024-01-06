<?php
 require("mysql/mysqli_connect.php"); 


 
if($_POST['clearAll'] == 'true'){
    $userId = $_POST['userId'];
    // Deletes all rows corresponding to user from audio_files table if it's an audio to text translation
    if($_POST['fromAudio'] == 1){

        $deleteQuery = mysqli_prepare($dbcon, "DELETE FROM text_translations WHERE user_id = ? AND from_audio_file = 1");
        bindAndExec($deleteQuery, "s", $userId);

        $deleteQuery = mysqli_prepare($dbcon, "DELETE FROM audio_files WHERE user_id = ?");
        bindAndExec($deleteQuery, "s", $userId);

        // since we delete all, get all the files that has the user_id in file and delete them
        $mask = $userId . "_*";

        $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($mask, FilesystemIterator::SKIP_DOTS),
                RecursiveIteratorIterator::CHILD_FIRST
            );

        //$items = glob($mask);

        foreach ($iterator as $file) {
            if ($file->isFile()) {
                unlink($file->getPathname());
            } elseif ($file->isDir() && !$iterator->hasChildren()) {
                rmdir($file->getPathname());rmdir($item);
            }
        }

        //array_map('unlink', glob($mask));
    }
    else{            
        // Deletes all rows corresponding to user from text_translation table
        $deleteQuery = mysqli_prepare($dbcon, "DELETE FROM text_translations WHERE user_id = ? AND from_audio_file = 0");
        bindAndExec($deleteQuery, "s", $userId);
    }
 }

 elseif($_POST['deleteRows'] == 'true'){
    if($_POST['fromAudio'] == 1){
        $rowsToDelete = json_decode($_POST['rowsToDelete']);
        $filesToDelete = json_decode($_POST['filesToDelete']);
        for($i = 0; $i < count($rowsToDelete); $i++){
                $deleteRows = $rowsToDelete[$i];
                $deleteFiles =$filesToDelete[$i];
                $deleteQuery = mysqli_prepare($dbcon, "DELETE FROM text_translations WHERE text_id = ?");
                bindAndExec($deleteQuery, "s", $deleteRows);

                $deleteQuery = mysqli_prepare($dbcon, "DELETE FROM audio_files WHERE file_id = ?");
                bindAndExec($deleteQuery, "s", $deleteFiles);

            }
    }

    else{
        foreach(json_decode($_POST['rowsToDelete']) as $rowNum){
            $deleteQuery = mysqli_prepare($dbcon, "DELETE FROM text_translations WHERE text_id = ?");
            bindAndExec($deleteQuery, "s", $rowNum);        }
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
        $datequery = "SELECT file_name, DATE_FORMAT(upload_date, '%m%d%Y_%H%i%s') AS formatted_date 
                        FROM audio_files WHERE file_id = '$fileId'";
        $dateresult = mysqli_query($dbcon, $datequery);
        $row = mysqli_fetch_assoc($dateresult);

        $filename = pathinfo($row['file_name'], PATHINFO_FILENAME);
        $extension = pathinfo($row['file_name'], PATHINFO_EXTENSION);

        $filenameMask = "audio_files/" . $userId . "_" . $filename . $row['formatted_date'] . $extension;
        unlink($filenameMask);

        /*$iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directoryMask, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );
     
        foreach ($iterator as $item) {
            if ($item->isFile()) {
                unlink($item->getPathname());
            } elseif ($item->isDir() && !$iterator->hasChildren()) {
                rmdir($item->getPathname());
            }
        }*/
        

        
        
        
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
