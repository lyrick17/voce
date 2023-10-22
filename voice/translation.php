<?php

// Use to inspect contents of json / array objects.
function dd($value){
	var_dump($value);
	die();
}

$lang_codes = [];

//CREATE CLASS FOR CURL 
$curl = curl_init();

curl_setopt_array($curl, [
	CURLOPT_URL => "https://text-translator2.p.rapidapi.com/getLanguages",
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	//CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "GET",
	CURLOPT_HTTPHEADER => [
		"X-RapidAPI-Host: text-translator2.p.rapidapi.com",
		"X-RapidAPI-Key: 81901ce272msh4265f1573ac1dc7p17b83ejsnc3b7fa66ab56"
	],
]);

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);

if ($err) {
	echo "cURL Error #:" . $err;
} else {
	$languages = json_decode($response, true)["data"]["languages"];
	foreach($languages as $language){
		$lang_codes[$language["name"]] = $language["code"];
	}
}


function uploadAndTranscribe($path){
	$pathto="audio_files/".$path;
    move_uploaded_file( $_FILES['user_file']['tmp_name'],$pathto) or die( "Could not copy file!");
	return shell_exec("C:\Users\User\AppData\Local\Programs\Python\Python311\python.exe translate.py " . $_FILES["user_file"]['full_path']);
}

// Language Translation, please check https://rapidapi.com/dickyagustin/api/text-translator2 for more information.
if($_SERVER["REQUEST_METHOD"] == "POST"){
	$curl = curl_init();

	if(ISSET($_POST["text"])){
		$transcript = $_POST["text"];
	}
	else{
		$path=$_FILES['user_file']['name'];
		$transcript = uploadAndTranscribe($path);
		echo 'TRANSCRIPT: ' . $transcript;
	} 

	
	$src_lang =  $lang_codes[$_POST["src"]] ?? '';
	$trg_lang = $lang_codes[$_POST["target"]] ?? '';
	curl_setopt_array($curl, [
		CURLOPT_URL => "https://text-translator2.p.rapidapi.com/translate",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		//CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_POST => true,
		CURLOPT_POSTFIELDS => "source_language=".$src_lang."&target_language=".$trg_lang."&text=".$transcript,
		CURLOPT_HTTPHEADER => [
			"X-RapidAPI-Host: text-translator2.p.rapidapi.com",
			"X-RapidAPI-Key: 81901ce272msh4265f1573ac1dc7p17b83ejsnc3b7fa66ab56",
			"content-type: application/x-www-form-urlencoded"
		],
	]);

	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);

	if ($err) {
		echo "cURL Error #:" . $err;
	} else {
		$decoded = json_decode($response, true);
		//var_dump($decoded);
		//$result = $decoded["data"]["translatedText"];
		$result = $decoded["data"]["translatedText"];
	}
}

?>

