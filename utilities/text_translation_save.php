<?php 
require("../mysql/mysqli_session.php"); 

require("common_languages.php"); // Translator_Functions are alr required in this file

// Language Translation, please check https://rapidapi.com/dickyagustin/api/text-translator2 for more information.
// Translate text input
if($_SERVER["REQUEST_METHOD"] == "POST"){ 
    
    // Error Handling first before translation 
    $source_lang = mysqli_real_escape_string($dbcon, trim($_POST['src']));
    $target_lang = mysqli_real_escape_string($dbcon, trim($_POST['target']));
    $orig_text = mysqli_real_escape_string($dbcon, trim($_POST["text"]));
    $translation = mysqli_real_escape_string($dbcon, trim($_POST["translation"]));
    $isFromAudio = False;
    

    // db query
    $query_insert = mysqli_prepare($dbcon, "INSERT INTO text_translations(from_audio_file, original_language, translated_language,
    translate_from, translate_to, translation_date) VALUES (?, ?, ?, ?, ?, NOW())");
    mysqli_stmt_bind_param($query_insert, 'issss', $isFromAudio, $source_lang, $target_lang, $orig_text, $translation);
    mysqli_stmt_execute($query_insert);

    /*$query_insert = mysqli_prepare($dbcon, "INSERT INTO text_translations(user_id, from_audio_file, original_language, translated_language,
    translate_from, translate_to, translation_date) VALUES (?, ?, ?, ?, ?, ?, NOW())");
    mysqli_stmt_bind_param($query_insert, 'iissss', $id, $isFromAudio, $source_lang, $target_lang, $orig_text, $translation);
    mysqli_stmt_execute($query_insert);*/

    $id = 0;
    $query_select = mysqli_prepare($dbcon, "SELECT text_id FROM text_translations ORDER BY text_id DESC LIMIT 1");
    mysqli_stmt_execute($query_select);
    mysqli_stmt_bind_result($query_select, $id);
    mysqli_stmt_fetch($query_select);
    mysqli_stmt_close($query_select);
    
    // set session variable to display the latest translation
    $_SESSION['recent_text'] = $id;
    $_SESSION['text_time'] = time();

    success_logs("text-to-text", $id, $dbcon);

    $exit = ['error' => 0,
            'source' => $source_lang,
            'target' => $target_lang,
            'orig_text' => $orig_text,  
            'translation' => $translation];

    exit(json_encode($exit));
    //header("Location: ../text-text.php?translated=1");
    //exit();
}
?>