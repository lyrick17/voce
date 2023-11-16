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
		"X-RapidAPI-Key: dd79fde36amsh4a5e9db6ec28ec6p1577f9jsn41a1205a8919"
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
// 		contains user did not upload file, invalid file format
class ErrorHandling {
	function audioError2() {
		// error, user did not upload file
		global $dbcon;
		logs("error-at", $_SESSION['username'], $dbcon);
		header("Location: history_audio.php?error=2");
		exit();
	}
	function audioError3() {
		// error, for some reason, there is no output
		global $dbcon;
		logs("error-at", $_SESSION['username'], $dbcon);
		header("Location: history_audio.php?error=5");
		exit();
	}
	
	static function checkLanguageChosen() {
		global $dbcon;
	
		// Error Handling if user did not select language 
		//	and if user select same language on src and target
	
		if ($_POST["src"] == "" || $_POST['target'] == "") {
			// error, user did not choose language
			logs("error-at-1", $_SESSION['username'], $dbcon);
			header("Location: history_audio.php?error=1");
			exit();
		} 
	
		if ($_POST["src"] == $_POST['target']) {
			// error, user choose two same language
			logs("error-at-4", $_SESSION['username'], $dbcon);
			header("Location: history_audio.php?error=4");
			exit();
		} 
	
	}
	
	static function validateFormat($filePath) {
		// error, user uploaded invalid file format
		// only accepts these formats provided
		$validExtensions = array('m4a', 'mp3', 'webm', 'mp4', 'mpga', 'wav', 'mpeg');
	
		// get the file extension, then check if extension is in array, return error if none
		$ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
		global $dbcon;
	
		if (!in_array($ext, $validExtensions)) {
			logs("error-at", $_SESSION['username'], $dbcon);
			header("Location: history_audio.php?error=3");
			exit();
		}
	}

	static function checkFolder() {
		// make sure audio_files folder is created
		if(!is_dir("audio_files")){
			mkdir("audio_files", 0777, true);
		}
	}
}
// Error Logs: --------------------------------------




function db_insertAudioFile($path, $userid) {
	global $dbcon;
	// prepare userid, filename, filesize, fileformat
	$file_name = $path;
	$file_size = round(filesize('audio_files/' . $file_name)/1000000, 2);
	$file_format =  pathinfo('audio_files/' . $file_name, PATHINFO_EXTENSION);
	
	// insert audio file into database
  	$query_insert2 = mysqli_prepare($dbcon, "INSERT INTO audio_files(user_id, file_name, file_size, file_format,
  	upload_date) VALUES (?, ?, ?, ?, NOW())");

	// execute the query
  	mysqli_stmt_bind_param($query_insert2, 'isss', $userid, $file_name, $file_size, $file_format);
  	mysqli_stmt_execute($query_insert2);
}


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
	global $dbcon;
	// get the name of file and extension separately
	$filename = pathinfo($path, PATHINFO_FILENAME);
  	$extension = pathinfo($path, PATHINFO_EXTENSION);

		// get the date of the file
		$datequery = "SELECT DATE_FORMAT(upload_date, '%m%d%Y_%H%i%s') AS formatted_date 
						FROM audio_files WHERE user_id = '$userid' and file_name = '$path' ORDER BY file_id DESC LIMIT 1";
		$dateresult = mysqli_query($dbcon, $datequery);
		$row = mysqli_fetch_assoc($dateresult);

	// 5. 
	$newFile = $userid . "_" . $filename . $row['formatted_date'] . "." . $extension;
	$newFilename = pathinfo($newFilename, PATHINFO_FILENAME);
	$newFilename = escapeshellarg($newFilename);
	// audio files folder
	$pathto="audio_files/" . $newFile;


	// 6.
 	move_uploaded_file( $_FILES['user_file']['tmp_name'],$pathto) or die(ErrorHandling::audioError2());
	//move_uploaded_file( $_FILES['user_file']['tmp_name'],$pathto) or die(audioError2());

	// 7.
	getVocals($newFile);
	
			/* make sure to go to php.ini in xampp (config > php.ini) 
			*  and set max_execution_time into 600 [10 minutes] or higher (write in seconds), for longer processing
			*  you only need to pass the name of file as argument for translation (file extension not needed)
			*/

	// 8.
	$output = shell_exec("python scripts\\translate.py " . escapeshellarg($newFilename));
	if ($output) {
		return $output;
	} else {
		ErrorHandling::audioError3();
	}
}







// Language Translation, please check https://rapidapi.com/dickyagustin/api/text-translator2 for more information.
if($_SERVER["REQUEST_METHOD"] == "POST"){
	// steps of audio to text translation
	//	1. Error handling, if user did not select language / same language
	//	2. Error handling, Check file format
	//	3. Error handling, make sure there is audio_files folder
	//  4. insert audio file into database
	//  5. append user_id and upload date into the filename
	//	6. Upload the file onto audio_files folder of server (must be updated with user id)
	//	7. remove background noise/music, get the vocals
	// 	8. translate the transcribed file
	
	$curl = curl_init();

	// 1.
	ErrorHandling::checkLanguageChosen();

	// 2.
	ErrorHandling::validateFormat($_FILES['user_file']['name']);

	// 3. 
	ErrorHandling::checkFolder();

	if(ISSET($_POST["text"])){
		$transcript = $_POST["text"];
	}
	else{
		// required for uploading the file
		$path=$_FILES['user_file']['name']; // file
		$userid = $_SESSION['user_id']; // user id needed to separate all files between each user by appending userid to filename
		
		// 4.
		db_insertAudioFile($path, $userid);
		
		// 5. 6. 7. 8.
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
			"X-Rapid: d5185f2565msh3cdba754dc69affp10ba69jsn87d2b93e11ba",
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

