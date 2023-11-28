<?php 
require("mysql/mysqli_session.php"); 
require("Translator_Functions.php");
require("languages.php");

$process = json_decode(file_get_contents('php://input'), true);

// consider if javascript is turned off,


if (!isset($_SESSION['a_info'])) $_SESSION['a_info'] = array();
    // $_SESSION['a_info'][0] contains new Filename
    // $_SESSION['a_info'][1] contains transcript text from Whisper
    // $_SESSION['a_info'][2] contains transcript language from Whisper
    // $_SESSION['a_info'][3] contains result text which is translated from API

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // required for uploading the file
    $path=$_FILES['user_file']['name']; // file
    $pathsize = $_FILES['user_file']['size']; // file size
    $userid = $_SESSION['user_id']; // to be used to add userid on new filename

    $model_size = $_POST['modelSize'];
    $src_lang = ($_POST['src'] == 'auto') ? "auto" : $audio_src_lang_codes[$_POST["src"]];
    $trg_lang = $audio_trgt_lang_codes[$_POST["target"]] ?? ''; 
    
    // Checks whether checkbox is checked or not
    $removeBGM = ISSET($_POST["removeBGM"]) ?  "on" : "off";
    

    if ($_POST['step'] == 1) { #!!! error handling and insertion of audio file to database
        
        ErrorHandling::checkLanguageChosen();
        ErrorHandling::validateFormat($path);
        ErrorHandling::checkFolder();
        
        Translator::db_insertAudioFile($path, $userid, $pathsize);
        $newFile = Translator::createNewFilename($path, $userid);
        
        // if error is 0, there is no error
        $success = [
            'removeBGM' => $removeBGM,
            'error' => 0
        ];
        $_SESSION['a_info'][0] = $newFile; 
        exit(json_encode($success));
    }


    if ($_POST['step'] == 2) { #!!! extracting vocals using spleeter
        if ($removeBGM === "on") {
            Translator::getVocals($_SESSION['a_info'][0]);
        }

        $success = ['error' => 0];
        exit(json_encode($success));
    }


    if ($_POST['step'] == 3) { #!!! transcribing the vocals/audio file using whisper
        $transcript = Translator::uploadAndTranscribe($_SESSION['a_info'][0], $removeBGM, $src_lang, $_POST['modelSize']);
        $_SESSION['a_info'][1] = $transcript['text']; 
        $_SESSION['a_info'][2] = $transcript['language']; 

        $success = ['error' => 0];
        exit(json_encode($success));
    }


    if ($_POST['step'] == 4) { #!!! using the text transcribed, call api to translate text
        $result = Translator::translate($_SESSION['a_info'][1], $_SESSION['a_info'][2], $trg_lang);
        
        $_SESSION['a_info'][3] = $result;
        $success = ['error' => 0];
        exit(json_encode($success));
    }


    if ($_POST['step'] == 5) { #!!! record the text output on database
        if ($_POST['src'] == 'auto') {
            $key = array_search($_SESSION['a_info'][2], array_column($common_languages, 'code'));    
            $source_lang = $common_languages[$key]['name']; // extract the lang name from array if user chooses auto-detect
        } else {
            $source_lang = $_POST['src']; 
        }

        $target_lang = $_POST['target'];

        $isFromAudio = TRUE;
        
        $get_fileid = "SELECT file_id FROM audio_files WHERE user_id = '$userid' ORDER BY file_id DESC LIMIT 1";
        $fileresult = mysqli_query($dbcon, $get_fileid);

        $row = mysqli_fetch_assoc($fileresult);

        $query_insert1 = mysqli_prepare($dbcon, "INSERT INTO text_translations(file_id, user_id, from_audio_file, original_language, translated_language,
        translate_from, translate_to, translation_date) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
        
        mysqli_stmt_bind_param($query_insert1, 'iiissss', $row['file_id'], $userid, $isFromAudio, $source_lang, $target_lang, $_SESSION['a_info'][1], $_SESSION['a_info'][3]);
        mysqli_stmt_execute($query_insert1);

        unset_extra_sess_vars();
        
        $success = ['error' => 0];
        exit(json_encode($success));
    }
}

function unset_extra_sess_vars() {
    if (isset($_SESSION['a_info'])) {
        unset($_SESSION['a_info']);
    }
}

?>