<?php 
require("../mysql/mysqli_session.php"); 

require("common_languages.php"); // Translator_Functions are alr required in this file

// Language Translation, please check https://rapidapi.com/dickyagustin/api/text-translator2 for more information.
// Translate text input
if($_SERVER["REQUEST_METHOD"] == "POST"){ 
    
    // Error Handling first before translation 
    
    ErrorHandling::checkLanguageChosen("text", $deep_langs, $common_langs);
    ErrorHandling::checkTextInput();
    
    // translates text, get output
    
    $source_lang = mysqli_real_escape_string($dbcon, trim($_POST['src']));
    $target_lang = mysqli_real_escape_string($dbcon, trim($_POST['target']));
    $orig_text = mysqli_real_escape_string($dbcon, trim($_POST["text"]));
    $isFromAudio = False;
    $translation = Translator::translate($orig_text,  $source_lang,  $target_lang);
    
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