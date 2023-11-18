<?php
 require("mysql/mysqli_connect.php"); 

 
 if($_POST['clearAll'] == 'true'){
    // Deletes all rows corresponding to user from audio_files table if it's an audio to text translation
    if($_POST['fromAudio'] == 1){
        $deleteQuery = "DELETE FROM text_translations WHERE user_id = " . $_POST['userId']  . " AND from_audio_file = 1";
        mysqli_query($dbcon, $deleteQuery);
        $deleteQuery = "DELETE FROM audio_files WHERE user_id = " . $_POST['userId'];
        mysqli_query($dbcon, $deleteQuery);
    }
    else{            
        // Deletes all rows corresponding to user from text_translation table
        $deleteQuery = "DELETE FROM text_translations WHERE user_id = " . $_POST['userId']  . " AND from_audio_file = 0";
        mysqli_query($dbcon, $deleteQuery);
    }
 }

 else{
    //deletes row from database
    $deleteQuery = "DELETE FROM text_translations WHERE text_id = " . $_POST['rowId'];
    mysqli_query($dbcon, $deleteQuery);

    //deletes audio file record from database if it's an audio to text translation
    if($_POST['fileId'] != null){
        $deleteQuery = "DELETE FROM audio_files WHERE file_id = " . $_POST['fileId'];
        mysqli_query($dbcon, $deleteQuery);
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
