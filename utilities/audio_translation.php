<?php
require ("../mysql/mysqli_session.php");

require ("common_languages.php"); // Translator_Functions and Error Habdling are alr required in this file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // required for uploading the file
    // checks if user uploads file or live record
    
    $a_info = 'a_info' . $_POST['random'];
    // $_SESSION[$a_info]['newfile'] contains new Filename
    // $_SESSION[$a_info]['text'] contains transcript text from Whisper
    // $_SESSION[$a_info]['lang'] contains transcript language from Whisper
    // $_SESSION[$a_info]['output'] contains result text which is translated from API

    if (!isset($_SESSION[$a_info])) {
        $_SESSION[$a_info] = array();
    }

    if (isset($_FILES['record'])) {
        $is_recorded = true;
        $record = $_FILES['record'];
    } else {
        $is_recorded = false;
        $path = $_FILES['user_file']['name'];         // file
        $pathsize = $_FILES['user_file']['size']; // file size
    }

    $src_lang = ($_POST['src'] == 'auto') ? "auto" : $common_langs[$_POST["src"]] ?? '';  //($_POST['src'] == 'auto') ? "auto" : $common_langs[$_POST["src"]] ?? '';
    $trg_lang = $common_langs[$_POST["target"]] ?? '';

    // Checks whether checkbox is checked or not
    $removeBGM = isset($_POST["removeBGM"]) ? "on" : "off";


    if ($_POST['step'] == 1) { #!!! error handling and insertion of audio file to database
        ErrorHandling::checkLanguageChosen("audio", $deep_langs, $common_langs);
        if (!$is_recorded) {
            ErrorHandling::checkFileUpload($_FILES["user_file"]);
            ErrorHandling::validateFormat($path);
        }
        ErrorHandling::checkFolder();

        $audio = null;
        if ($is_recorded) {
            $path = Translator::db_uploadRecordFile($record);
        } else {
            Translator::db_insertAudioFile($path, $pathsize);
        }

        $newFile = Translator::createNewFilename($path, $is_recorded);

        if ($removeBGM == "off") {
            $output = Translator::createNewFolder($newFile);
        }

        // if error is 0, there is no error
        $success = [
            'removeBGM' => $removeBGM,
            'error' => 0
        ];
        $_SESSION[$a_info]['newfile'] = $newFile;
        exit(json_encode($success));
    }


    if ($_POST['step'] == 2) { #!!! extracting vocals using spleeter
        if ($removeBGM == "on") {
            Translator::getVocals($_SESSION[$a_info]['newfile']);
        }

        $success = ['error' => 0];
        exit(json_encode($success));
    }

    if ($_POST['step'] == 3) { #!!! removing silence in the file

        Translator::removeSilence($_SESSION[$a_info]['newfile'], $removeBGM);

        $success = ['error' => 0];
        exit(json_encode($success));
    }


    //FIX STEP 4
    if ($_POST['step'] == 4) { #!!! transcribing the vocals/audio file using whisper
        $data = Translator::uploadAndTranscribe($_SESSION[$a_info]['newfile'], $removeBGM, $src_lang);

        if ($data['error'] != 1) {
            $_SESSION[$a_info]['text'] = $data['text'];
            $_SESSION[$a_info]['lang'] = $data['language'];
            $success = ['error' => 0];
        } else {
            $success = ['error' => 8];
        }

        exit(json_encode($success));
    }


    if ($_POST['step'] == 5) {
        $trans_src = "";
        if ($_SESSION[$a_info]['lang'] == 'zh') {
            $trans_src = "chinese (simplified)";
        } else {
            $trans_src = $common_codes[$_SESSION[$a_info]['lang']];
        }

        $result = Translator::translate($_SESSION[$a_info]['text'], $trans_src, $_POST["target"], $_SESSION[$a_info]['newfile']);
        $_SESSION[$a_info]['output'] = $result;
        $success = ['error' => 0];
        exit(json_encode($success));
    }


    if ($_POST['step'] == 6) { #!!! record the text output on database

        if ($_POST['src'] == 'auto') {
            $source_lang = $common_codes[$_SESSION[$a_info]['lang']];
        } else {
            $source_lang = $_POST['src'];
        }

        $target_lang = $_POST['target'];

        $isFromAudio = TRUE;
        if ($is_recorded) {
            $translation_type = "recorded";
        } else {
            $translation_type = "uploaded";
        }

        $fileid = explode("_", $_SESSION[$a_info]['newfile'])[0]; // split string by underscore (_), then take first element of result array

        $query_insert1 = mysqli_prepare($dbcon, "INSERT INTO text_translations(file_id, from_audio_file, translation_type, original_language, translated_language,
        translate_from, translate_to, translation_date) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");

        mysqli_stmt_bind_param($query_insert1, 'iisssss', $fileid, $isFromAudio, $translation_type, $source_lang, $target_lang, $_SESSION[$a_info]['text'], $_SESSION[$a_info]['output']);
        mysqli_stmt_execute($query_insert1);



        $id = 0;
        $query_select = mysqli_prepare($dbcon, "SELECT text_id FROM text_translations ORDER BY text_id DESC LIMIT 1");
        mysqli_stmt_execute($query_select);
        mysqli_stmt_bind_result($query_select, $id);
        mysqli_stmt_fetch($query_select);
        mysqli_stmt_close($query_select);

        $_SESSION['recent_audio'] = $id;
        $_SESSION['audio_time'] = time();

        success_logs("audio-to-text", $id, $dbcon);

        $success = ['error' => 0];
        deleteSuccessFile($_SESSION[$a_info]['newfile']);
        unset_extra_sess_vars($a_info);

        exit(json_encode($success));
    }
}

function unset_extra_sess_vars($a_info)
{
    if (isset($_SESSION[$a_info])) {
        unset($_SESSION[$a_info]);
    }
}

?>