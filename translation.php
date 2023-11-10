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
		"X-RapidAPI-Key: 5a4a854aecmsh5aefb5b52f1c29ap189bdfjsnebc4acefe413"
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

// Error Logs: --------------------------------------
// 		contains user did not upload file and invalid file format
function audioError2() {
	// error, user did not upload file
	global $dbcon;
	logs("error-at", $_SESSION['username'], $dbcon);
	header("Location: history_audio.php?error=2");
	exit();
}

function validateFormat() {
	// error, user uploaded invalid file format
	// only accepts these formats provided
	$validExtensions = array('m4a', 'mp3', 'webm', 'mp4', 'mpga', 'wav', 'mpeg');
	$filePath = $_FILES['user_file']['name'];

	// get the file extension, then check if extension is in array, return error if none
	$ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
	global $dbcon;

	if (!in_array($ext, $validExtensions)) {
		logs("error-at", $_SESSION['username'], $dbcon);
		header("Location: history_audio.php?error=3");
		exit();
	}
}
// Error Logs: --------------------------------------


function getVocals($file) {
	# Activate the virtual environment
    # call the separate.py which includes the spleeter code for extracting vocals,
    #   and pass the file as argument 
    # then, deactivate virtual environment
    $output = shell_exec("spleeter_env\\Scripts\\activate && python scripts\\separate.py " . $file . " && deactivate");
}

function uploadAndTranscribe($path){

	$pathto="audio_files/".$path;

	// get the name of file only, for translating the vocals
    $filename = pathinfo($_FILES['user_file']['name'], PATHINFO_FILENAME);
	// modified die() if user did not upload file
	move_uploaded_file( $_FILES['user_file']['tmp_name'],$pathto) or die(audioError2());
	
	// separate bg music from vocals using spleeter
	getVocals($_FILES["user_file"]['full_path']);
	
	// make sure to go to php.ini in xampp (config > php.ini) 
	// and set max_execution_time into 600 [10 minutes] or higher (write in seconds), for longer processing

	// you only need to pass the name of file as argument for translation (file extension not needed)
	return shell_exec("python scripts\\translate.py " . $filename);

	//return shell_exec("python scripts/translate.py " . $_FILES["user_file"]['full_path'] . " " . $_FILES['user_file']['name']);
}

// Language Translation, please check https://rapidapi.com/dickyagustin/api/text-translator2 for more information.
if($_SERVER["REQUEST_METHOD"] == "POST"){
	$curl = curl_init();

	// Error Handling if user did not select language
	if ($_POST["src"] == "" || $_POST['target'] == "") {
        // error, user did not choose language
        logs("error-at", $_SESSION['username'], $dbcon);
        header("Location: history_audio.php?error=1");
        exit();
        
    } 

	if(ISSET($_POST["text"])){
		$transcript = $_POST["text"];
	}
	else{
		$path=$_FILES['user_file']['name'];
		$transcript = uploadAndTranscribe($path);


	} 
	
	// check file format
	validateFormat();

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
			"X-RapidAPI-Key: 5a4a854aecmsh5aefb5b52f1c29ap189bdfjsnebc4acefe413",
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
		$result = $decoded["data"]["translatedText"];
	}
}

?>