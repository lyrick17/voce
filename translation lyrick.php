<?php

// translation.php is for audio to text translation

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
		"X-RapidAPI-Key: d5185f2565msh3cdba754dc69affp10ba69jsn87d2b93e11ba"
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
    # use spleeter for extracting vocals,
    #   and pass the file as argument 
    # then, deactivate virtual environment
    $output = shell_exec("spleeter_env\\Scripts\\activate && python scripts/separate.py " . $file . " && deactivate");
	#  shell_exec("spleeter_env\\Scripts\\activate")
	#  $output = shell_exec("spleeter separate -o audio_output " . $file);
	#  shell_exec("deactivate");
}

function uploadAndTranscribe($path, $userid){

	// 3. get the name of file and extension separately
	$filename = pathinfo($path, PATHINFO_FILENAME);
  	$extension = pathinfo($path, PATHINFO_EXTENSION);

	// Append the user's ID to the filename
	$newFilename = $filename . "_" . $userid . "." . $extension;
	// audio files folder
	$pathto="audio_files/".$newFilename;

	// 4.
		// make sure audio_files folder is created
		if(!is_dir("audio_files")){
			mkdir("audio_files", 0777, true);
		}

		// check if a file with same name already exists
		if(file_exists($pathto)){
			// Handle the error, e.g. by logging it or displaying a message
			//error_log("A file with the name " . $newFilename . " already exists.");
			return;
		}
	
	// 5.
 	move_uploaded_file( $_FILES['user_file']['tmp_name'],$pathto) or die(audioError2());
	//move_uploaded_file( $_FILES['user_file']['tmp_name'],$pathto) or die(audioError2());
	
	// 6.
	getVocals($pathto);
	
	// make sure to go to php.ini in xampp (config > php.ini) 
	// and set max_execution_time into 600 [10 minutes] or higher (write in seconds), for longer processing
	
	// you only need to pass the name of file as argument for translation (file extension not needed)
	// 7.
	return shell_exec("python scripts/translate.py " . $newFilename);

	//return shell_exec("python scripts/translate.py " . $_FILES["user_file"]['full_path'] . " " . $_FILES['user_file']['name']);
}

// Language Translation, please check https://rapidapi.com/dickyagustin/api/text-translator2 for more information.
if($_SERVER["REQUEST_METHOD"] == "POST"){
	// start of the audio to text translation
	//	1. Error handling, if user did not select language
	//	2. Check file format
	//  3. append user_id into the filename
	//  4. double check if (audio_files folder exists) and (filename already exists)
	//	5. Upload the file onto audio_files folder of server (must be updated with user id)
	//	6. remove background noise/music, get the vocals
	// 	7. translate the transcribed file
	
	$curl = curl_init();

	// 1.
	if ($_POST["src"] == "" || $_POST['target'] == "") {
        // error, user did not choose language
        logs("error-at", $_SESSION['username'], $dbcon);
        header("Location: history_audio.php?error=1");
        exit();
        
    } 

	// 2.
	if(ISSET($_POST["text"])){
		$transcript = $_POST["text"];
	}
	else{
		// required for uploading the file
		$path=$_FILES['user_file']['name']; // file
		$userid = $_SESSION['user_id']; // user id needed to separate all files between each user by appending userid to filename
		
		// 2. 
		validateFormat();

		// 3. 4. 5. 6. 7.
		$transcript = uploadAndTranscribe($path, $userid);
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
		$result = $decoded["data"]["translatedText"];
	}
}

?>

