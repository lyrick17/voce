<?php 

require("Translator_Functions.php");

require "whisper_languages.php";            // consists of supported languages from the whisper-ai library
$deep_langs = Translator::getLangCodes();    // consists of supported languages from the deep-translator library

// $langs_json = file_get_contents('cache\common_languages.json');
// $codes_json = file_get_contents('cache\common_codes.json');
// $deep_json = file_get_contents('cache\deep_langs.json');
// $common_langs = json_decode($langs_json, true);
// $common_codes = json_decode($codes_json, true);
// $deep_langs = json_decode($deep_json, true);


// changes the structure of the array.  {"name" => "code"}
$whisper_langs = array_column($whisperlanguages, 'code', 'name');

$common_langs = array_intersect($deep_langs, $whisper_langs);
$common_langs['chinese (simplified)'] = "zh-CN";
$common_langs['chinese (traditional)'] = "zh-TW";
$common_codes = array_flip($common_langs);
ksort($common_langs);

// $handle = fopen('cache\deep_langs.json', 'w');
// fwrite($handle, json_encode($deep_langs, JSON_PRETTY_PRINT));
// fclose($handle);

// try{
// $langs_json = file_get_contents('cache\common_languages.json');
// $codes_json = file_get_contents('cache\common_codes.json');
// $common_langs = json_decode($langs_json, true);
// $common_codes = json_decode($codes_json, true);

// }
// catch (Exception $e){
//     header("Location: service-unavailable.php");
//     exit();
// }
?>
