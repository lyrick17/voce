<?php 

require "whisper_languages.php";            // consists of supported whisper languages
$languages = Translator::getLangCodes();    // consists of supported api languages 


// extract the language codes from both Whisper and API and combine them to find
//  common languages of both sides using language codes
$whisper_lang_codes = array_column($whisperlanguages, 'code');
$api_lang_codes = array_column($languages, 'code');
$api_lang_names = array_column($languages, 'name');

$common_codes = array_intersect($whisper_lang_codes, $api_lang_codes);

$common_languages = [];

foreach($languages as $language) {
    if (in_array($language['code'], $common_codes)) {
        array_push($common_languages, array("code" => $language['code'], "name" => $language['name']));
    }
}


#!!! lang_codes for AUDIO TO TEXT
// loop through the array of api language codes and check if code is present in common_languages
//  once it's present, put it in another array to be displayed on the website
$audio_src_lang_codes = [];
$audio_trgt_lang_codes = [];
foreach($languages as $language){
    
    if (in_array($language['code'], $common_codes)) {
        $audio_src_lang_codes[$language["name"]] = $language["code"];
    }
    
    $audio_trgt_lang_codes[$language["name"]] = $language["code"];
}

#!!! lang_codes for TEXT TO TEXT
$lang_codes = []; // for text to text
foreach($languages as $language){
    $lang_codes[$language["name"]] = $language["code"];
}



// for debugging purposes only
//debugging_show_lang($PUT_YOUR_ARRAY_HERE);

?>