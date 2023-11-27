<?php 
require("mysql/mysqli_session.php"); 
require("Translator_Functions.php");
require("languages.php");

$process = json_decode(file_get_contents('php://input'), true);



if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['step'] == 1) {
    // required for uploading the file
    $path=$_FILES['user_file']['name']; // file
    $pathsize = $_FILES['user_file']['size']; // file size
    $userid = $_SESSION['user_id']; // user id needed to separate all files between each user by appending userid to filename

    $model_size = $_POST['modelSize'];
    $src_lang = ($_POST['src'] == 'auto') ? "auto" : $audio_src_lang_codes[$_POST["src"]];
    $trg_lang = $audio_trgt_lang_codes[$_POST["target"]] ?? ''; 
    $newFile = '';
    
    // Checks whether checkbox is checked or not
    $removeBGM = ISSET($_POST["removeBGM"]) ?  "on" : "off";
    
    
    // step 1: loading
    // error handlings first before proceeding to the main process
    // will automatically halt the process once error caught
	ErrorHandling::checkLanguageChosen();
	ErrorHandling::validateFormat($path);
	ErrorHandling::checkFolder();
    
    Translator::db_insertAudioFile($path, $userid, $pathsize);
    $newFile = Translator::createNewFilename($path, $userid);
    // containing the important values
    $user_data = [
        'user_id' => $userid,
        'src_lang' => $src_lang,
        'trg_lang' => $trg_lang,
        'removeBGM' => $removeBGM,
        'newFile' => $newFile,
        'modelSize' => $model_size
    ];
    
    exit(json_encode($user_data));
}


if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['step'] == 2) {
    exit();
}


if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['step'] == 3) {
    exit();
}


if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['step'] == 4) {
    exit();
}


if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['step'] == 5) {
    exit();
}


?>