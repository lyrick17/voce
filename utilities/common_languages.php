<?php 

require("Translator_Functions.php");

require "whisper_languages.php";            // consists of supported languages from the whisper-ai library
$deep_langs = Translator::getLangCodes();    // consists of supported languages from the deep-translator library


//changes the structure of the array.  {"name" => "code"}
$whisper_langs = array_column($whisperlanguages, 'code', 'name');

$common_langs = array_intersect($deep_langs, $whisper_langs);

$common_langs['chinese (simplified)'] = "zh-CN";
$common_langs['chinese (traditional)'] = "zh-TW";


?>