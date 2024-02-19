<?php 
require("../mysql/mysqli_session.php"); 

require("common_languages.php"); // Translator_Functions are alr required in this file

// Language Translation, please check https://rapidapi.com/dickyagustin/api/text-translator2 for more information.
// Translate text input
if($_SERVER["REQUEST_METHOD"] == "POST"){ 
    
    // Error Handling first before translation 
    
    ErrorHandling::checkLanguageChosen("text", $languages, $common_languages);
    ErrorHandling::checkTextInput();
    
    // translates text, get output
    $translation = Translator::translate($_POST["text"], 
        $lang_codes[$_POST["src"]], 
        $lang_codes[$_POST["target"]]
    );

    // insert into database
    $source_lang = $_POST['src'];
    $target_lang = $_POST['target'];
    $orig_text = $_POST["text"];
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
    $query_select = mysqli_prepare($dbcon, "SELECT text_id FROM text_translations ORDER BY id DESC LIMIT 1");
    mysqli_stmt_execute($query_select);
    mysqli_stmt_bind_result($query_select, $id);
    mysqli_stmt_fetch($query_select);
    mysqli_stmt_close($query_select);
    
    
    logs("text-to-text", $id, $dbcon);

    
    header("Location: ../text-text.php?translated=1");
    exit();
}
?>