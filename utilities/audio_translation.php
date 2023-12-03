<?php 
require("../mysql/mysqli_session.php"); 

require("common_languages.php"); // Translator_Functions and Error Habdling are alr required in this file

// consider if javascript is turned off,


if (!isset($_SESSION['a_info'])) $_SESSION['a_info'] = array();
    // $_SESSION['a_info']['newfile'] contains new Filename
    // $_SESSION['a_info']['text'] contains transcript text from Whisper
    // $_SESSION['a_info']['lang'] contains transcript language from Whisper
    // $_SESSION['a_info']['output'] contains result text which is translated from API

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // required for uploading the file
    $path=$_FILES['user_file']['name']; // file
    $pathsize = $_FILES['user_file']['size']; // file size
    $userid = $_SESSION['user_id']; // to be used to add userid on new filename

    $model_size = $_POST['modelSize'];
    $src_lang = ($_POST['src'] == 'auto') ? "auto" : $audio_src_lang_codes[$_POST["src"]] ?? '';
    $trg_lang = $audio_trgt_lang_codes[$_POST["target"]] ?? ''; 
    
    // Checks whether checkbox is checked or not
    $removeBGM = ISSET($_POST["removeBGM"]) ?  "on" : "off";
    

    if ($_POST['step'] == 1) { #!!! error handling and insertion of audio file to database
        
        ErrorHandling::checkLanguageChosen("audio", $languages, $common_languages);
        ErrorHandling::validateFormat($path);
        ErrorHandling::checkFolder();
        
        Translator::db_insertAudioFile($path, $userid, $pathsize);
        $newFile = Translator::createNewFilename($path, $userid);
        if ($removeBGM == "off") {
            $output = Translator::createNewFolder($newFile);
        }
        
        // if error is 0, there is no error
        $success = [
            'removeBGM' => $removeBGM,
            'error' => 0
        ];
        $_SESSION['a_info']['newfile'] = $newFile; 
        exit(json_encode($success));
    }


    if ($_POST['step'] == 2) { #!!! extracting vocals using spleeter
        if ($removeBGM == "on") {
            Translator::getVocals($_SESSION['a_info']['newfile']);
        }

        $success = ['error' => 0];
        exit(json_encode($success));
    }

    if ($_POST['step'] == 3) { #!!! removing silence in the file
        
        Translator::removeSilence($_SESSION['a_info']['newfile'], $removeBGM);
        
        $success = ['error' => 0];
        exit(json_encode($success));
    }

    if ($_POST['step'] == 4) { #!!! transcribing the vocals/audio file using whisper
        $transcript = Translator::uploadAndTranscribe($_SESSION['a_info']['newfile'], $removeBGM, $src_lang, $_POST['modelSize']);
        $_SESSION['a_info']['text'] = $transcript['text']; 
        $_SESSION['a_info']['lang'] = $transcript['language']; 

        $success = ['error' => 0];
        exit(json_encode($success));
    }


    if ($_POST['step'] == 5) { #!!! using the text transcribed, call api to translate text
        $result = Translator::translate($_SESSION['a_info']['text'], $_SESSION['a_info']['lang'], $trg_lang);
        
        $_SESSION['a_info']['output'] = $result;
        $success = ['error' => 0];
        exit(json_encode($success));
    }


    if ($_POST['step'] == 6) { #!!! record the text output on database

        if ($_POST['src'] == 'auto') {
            $key = array_search($_SESSION['a_info']['lang'], array_column($common_languages, 'code'));    
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
        
        mysqli_stmt_bind_param($query_insert1, 'iiissss', $row['file_id'], $userid, $isFromAudio, $source_lang, $target_lang, $_SESSION['a_info']['text'], $_SESSION['a_info']['output']);
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


// substitute overall process of audio-translation without the use of javascript
/*
if($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['step'])) {

    // required for uploading the file
    $path=$_FILES['user_file']['name']; // file
    $pathsize = $_FILES['user_file']['size']; // file size
    $userid = $_SESSION['user_id']; // user id needed to separate all files between each user by appending userid to filename
    

	$src_lang = ($_POST['src'] == 'auto') ? "auto" : $audio_src_lang_codes[$_POST["src"]];
	$trg_lang = $audio_trgt_lang_codes[$_POST["target"]] ?? ''; 


    // Checks whether checkbox is checked or not
    $removeBGM = ISSET($_POST["removeBGM"]) ?  "on" : "off";
    

	
	// step 1: loading
    // error handlings first before proceeding to the main process
        // will automatically halt the process once error caught
	ErrorHandling::checkLanguageChosen();
	ErrorHandling::validateFormat($path);
	ErrorHandling::checkFolder();


    Translator::db_insertAudioFile($path, $userid, $pathsize);
    $newFile = Translator::createNewFilename($path, $userid);

    // step 2: extracting vocals
    # Extract vocals if checkbox is checked
    if ($removeBGM === "on") {
        Translator::getVocals($newFile);
    }

    // step 3: transcribing audio
    //  $transcript contains the text and language
    $transcript = Translator::uploadAndTranscribe($newFile, $removeBGM, $src_lang, $_POST['modelSize']);



    // step 4: translating text
    $result = Translator::translate($transcript['text'], $transcript['language'], $trg_lang);


    //  STEP 6
    // after translating, find the language name in the common language array
    $key = array_search($transcript['language'], array_column($common_languages, 'code'));    
    if ($_POST['src'] == 'auto') {
        $source_lang = $common_languages[$key]['name']; // extract the lang name from array if user chooses auto-detect
    } else {
        $source_lang = $_POST['src']; 
    }

    $target_lang = $_POST['target'];

    $isFromAudio = TRUE;
    
    $get_fileid = "SELECT file_id FROM audio_files WHERE user_id = '$id' ORDER BY file_id DESC LIMIT 1";
    $fileresult = mysqli_query($dbcon, $get_fileid);

    $row = mysqli_fetch_assoc($fileresult);

    $query_insert1 = mysqli_prepare($dbcon, "INSERT INTO text_translations(file_id, user_id, from_audio_file, original_language, translated_language,
    translate_from, translate_to, translation_date) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
    
    mysqli_stmt_bind_param($query_insert1, 'iiissss', $row['file_id'], $id, $isFromAudio, $source_lang, $target_lang, $transcript['text'], $result);
    mysqli_stmt_execute($query_insert1);

	$response = array(
		"validatingInputs" => true, // Replace with actual validation status
		"uploadingFile" => true, // Replace with actual upload status
		"extractingVocals" => true, // Replace with actual extraction status
		"transcribingAudio" => true, // Replace with actual transcription status
		"translatingText" => true // Replace with actual translation status
	);
	
    logs("audio-to-text", $_SESSION['username'], $dbcon);
    header("Location: history_audio.php?translated=1");
}
*/
?>