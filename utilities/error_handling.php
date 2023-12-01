<?php
class ErrorHandling{
	static function audioError2() {
		// error, user did not upload file
		global $dbcon;
		logs("error-at-2", $_SESSION['username'], $dbcon);
		//header("Location: history_audio.php?error=2");
		$exit = ['error' => 2];
        exit(json_encode($exit));
	}


	static function audioError3() {
		// error, for some reason, there is no output
		global $dbcon;
		logs("audio-to-text-fail", $_SESSION['username'], $dbcon);
		//header("Location: history_audio.php?error=5");
		
		$exit = ['error' => 5];
        exit(json_encode($exit));
	}


	static function checkTextInput() {
		global $dbcon;
		// error, user did not input text
		if (empty(trim($_POST['text']))) {
			logs("error-tt-2", $_SESSION['username'], $dbcon);
			header("Location: ../text-text.php?error=3");
			
			exit(json_encode($exit));
		}
	}


	static function checkLanguageChosen($mode, $api_lang, $common_lang) {
		global $dbcon;

		//	- both applies on text and audio
		// (1) if user did not select language or model size
		// (2) or if user select same language on src and target
		// (3) or user manually used inspect element to add other options

		if ($mode == "text") {
			// (1)
			if ($_POST["src"] == "" || $_POST['target'] == "") {
				// error, user did not choose language
				logs("error-tt-1", $_SESSION['username'], $dbcon);
				header("Location: ../text-text.php?error=1");
				exit();
				
			} 

			// (2)
			if ($_POST["src"] == $_POST['target']) {
				// error, user picked same language, useless
				logs("error-tt-3", $_SESSION['username'], $dbcon);
				header("Location: ../text-text.php?error=2");
				exit();
			}

			// (3)
			if (array_search($_POST['src'], array_column($api_lang, 'name')) === false ||
				array_search($_POST['target'], array_column($api_lang, 'name')) === false) {
					logs("error-at-4", $_SESSION['username'], $dbcon);
	
					header("Location: ../text-text.php?error=4");
					exit();
			}

		} else if ($mode == "audio") {

			$modelSizes = array("base", "medium", "large");
			// (1)
			if ($_POST["src"] == "" || $_POST['target'] == "" ||  $_POST['modelSize'] == "") {
				logs("error-at-1", $_SESSION['username'], $dbcon);
				//header("Location: history_audio.php?error=1");
				$exit = ['removeBGM' => 'error', 'error' => 1];
				exit(json_encode($exit));
			} 

			// (2)
			if ($_POST["src"] == $_POST['target']) {
				logs("error-at-4", $_SESSION['username'], $dbcon);
				//header("Location: history_audio.php?error=4");
				$exit = ['removeBGM' => 'error', 'error' => 4];
				exit(json_encode($exit));
			} 
			
			// (3) Note: Source Language would be compared on common_languages of API and Whisper
			if (array_search($_POST['src'], array_column($common_lang, 'name')) === false ||
				array_search($_POST['target'], array_column($api_lang, 'name')) === false ||
				!in_array($_POST['modelSize'], $modelSizes)) {
					logs("error-at-5", $_SESSION['username'], $dbcon);
	
					$exit = ['removeBGM' => 'error', 'error' => 6];
					exit(json_encode($exit));
			}
			
		}

	
	}
	
	static function validateFormat($filePath) {
		// error, user no upload file
		if (!$filePath) {
			self::audioError2();
		}
		
		// error, user uploaded invalid file format
		// only accepts these formats provided
		$validExtensions = array('m4a', 'mp3', 'webm', 'mp4', 'mpga', 'wav', 'mpeg');
	
		// get the file extension, then check if extension is in array, return error if none
		$ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
		global $dbcon;
		
		if (!in_array($ext, $validExtensions)) {
			logs("error-at-3", $_SESSION['username'], $dbcon);
			//header("Location: history_audio.php?error=3");
			
			$exit = ['removeBGM' => 'error', 'error' => 3];
        	exit(json_encode($exit));
		}
	}

	static function checkFolder() {
		// make sure audio_files folder is created (outside utilities folder)
		if(!is_dir("../audio_files")){
			mkdir("../audio_files", 0777, true);
		}
	}

	static function checkCacheFolder() {
		// make sure cache folder is created (inside utilities Folder)
		if(!is_dir("cache")){
			mkdir("cache", 0777, true);
		}
	}
	
}

?>