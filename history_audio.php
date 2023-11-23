<?php require("mysql/mysqli_session.php"); 
$current_page = basename($_SERVER['PHP_SELF']);
?>
<?php if (!isset($_SESSION['username'])) {
    
  header("location: index.php");
  exit(); 
}?>

<?php

function dd($item){
    var_dump($item);
    exit();
}

require "Translator_Functions.php";


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

// for debugging purposes only
//debugging_show_lang($PUT_YOUR_ARRAY_HERE);



  $id = is_array($_SESSION['user_id']) ? $_SESSION['user_id']['user_id'] : $_SESSION['user_id'];


// Translation history for audio to text 
$history = mysqli_query($dbcon, "SELECT * FROM text_translations t INNER JOIN audio_files a ON t.file_id = a.file_id WHERE t.user_id = $id AND a.user_id = $id AND t.from_audio_file = 1 ORDER BY translation_date DESC");

// Language Translation, please check https://rapidapi.com/dickyagustin/api/text-translator2 for more information.

// Translate text input
if($_SERVER["REQUEST_METHOD"] == "POST"){



    // required for uploading the file
    $path=$_FILES['user_file']['name']; // file
    $pathsize = $_FILES['user_file']['size']; // file size
    $userid = $_SESSION['user_id']; // user id needed to separate all files between each user by appending userid to filename
    
    if ($_POST['src'] == 'auto') {
        $src_lang = "auto";
    } else {
        $src_lang =  $audio_src_lang_codes[$_POST["src"]] ?? '';    
    }
    $trg_lang = $audio_trgt_lang_codes[$_POST["target"]] ?? ''; 

    
    // error handlings first before proceeding to the main process
        // will automatically halt the process once error caught
	ErrorHandling::checkLanguageChosen();
	ErrorHandling::validateFormat($path);
	ErrorHandling::checkFolder();

    Translator::db_insertAudioFile($path, $userid, $pathsize);

    // Checks whether checkbox is checked or not
    $removeBGM = ISSET($_POST["removeBGM"]) ?  "on" : "off";

    # Arguments: path of the audio file, user id, on (if checkbox is checked)
    # NOTE: $transcript SOON will be an assoc_array, with ['text'] && ['language']
    # NOTE: as of Nov 20, 2023, it's still text
    $transcript = Translator::uploadAndTranscribe($path, $userid, $removeBGM, $src_lang);

    $result = Translator::translate($transcript['text'], $transcript['language'], $trg_lang);

    // after translating, find the language name in the common language array
    $key = array_search($transcript['language'], array_column($common_languages, 'code'));    
    if ($_POST['src'] == 'auto') {
        $source_lang = $common_languages[$key]['name']; // extract the lang name from array if user chooses auto-detect
    } else {
        $source_lang = $_POST['src']; 
    }

    $target_lang = $_POST['target'];

    $isFromAudio = TRUE;
    
    $get_fileid = "SELECT file_id FROM audio_files WHERE user_id = '$id' ORDER BY file_id DESC LIMIT 1";
    $fileresult = mysqli_query($dbcon, $get_fileid);

    $row = mysqli_fetch_assoc($fileresult);

    $query_insert1 = mysqli_prepare($dbcon, "INSERT INTO text_translations(file_id, user_id, from_audio_file, original_language, translated_language,
    translate_from, translate_to, translation_date) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
    
    mysqli_stmt_bind_param($query_insert1, 'iiissss', $row['file_id'], $id, $isFromAudio, $source_lang, $target_lang, $transcript['text'], $result);
    mysqli_stmt_execute($query_insert1);

    logs("audio-to-text", $_SESSION['username'], $dbcon);
    header("Location: history_audio.php?translated=1");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="styles/style2.css">
    
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">





</head>

<!-- Confirm delete window -->
<div class = "delete-window">
    <div class = "confirm-div">
        <h4 class = "confirm-text"></h4>
        <div class = "confirm-btn-div">
            <button class = "confirm-btn confirm-yes">Yes</button>
            <button class = "confirm-btn confirm-no">No</button>
        </div>
    </div>
</div>

<body>


    <!-- Sidebar -->
    <?php require "sidebar.php"?>

    <!-- Main Content -->
    <div class="content">
        <!-- Navbar -->
        <nav>
            <i class='bx bx-menu'></i>
        </nav>

        <!-- End of Navbar -->

        <main>

    
            <div class="header">
                <div class="left">
                    <h1>Audio Transcriber</h1>
                  
                    <!-- Error Message: Pabago nalang if may naiisip kang ibang design -->
                    <p style="color: red;"><i>
                    <?php
                        if (isset($_GET['error'])) {
                            switch ($_GET['error']) {
                                case 1: // user did not choose language
                                    echo "Please select a source/translated language.";
                                    break;
                                case 2:  // user did not upload file
                                    echo "No File Upload. Please try again.";
                                    break;
                                case 3: // user upload wrong file type
                                    echo "Invalid File Format. Please try again.";
                                    break;
                                case 4: // user choose two same language
                                    echo "Please choose two different language.";
                                    break;
                                case 5: // it's what the programmers say, "WHY IS IT NOT WORKING??"
                                    echo "Audio File not processed well. Please try again.";
                                    break;
                                default;
                                    break;
                            }
                        }
                    ?> 
                    </i></p>
                </div>
            </div>
                        <!-- Add this div for the loading feature -->
            <!-- Loading Modal after clicking translate button -->
                <div id="loadingModal" class="modalloading">
                    <div class="modal-contentloading">
                        <img src="images/loading.gif" alt="Loading..." />
                        <p>Loading....</p>
                    </div>
                </div>
            <form enctype="multipart/form-data" action = "history_audio.php" method = "POST" onsubmit="showLoading()">
            <label>  
			Model Size:
			<select name="modelSize" id="modelSize" class="form-control">
				<option value="">Select One …</option>
				<option value="base">Base</option>
				<option value="medium">Medium</option>
				<option value="large">Large</option>

			</select>
			</label>
            <br>
            <label>  
			Source language:
			<select name="src" id="sourceLanguage" class="form-control">
                <!-- Will display Languages supported by API and Whisper -->
				<option value="">Select One …</option>
				<option value="auto">Auto-Detect Language...</option> <!-- Auto-Detect --> 
				<?php foreach($common_languages as $language): ?>
					<option name = "language"><?= $language["name"]?></option>
				<?php endforeach ?> 

			</select>
			</label>
            <br>
			<label>
			Target language:
			<select name="target" id="targetLanguage" class="form-control">
                <!-- Will display languages supported by API only-->
				<option value="">Select One …</option>
				<?php foreach($languages as $language): ?>
					<option name = "language"><?= $language["name"]?></option>
				<?php endforeach ?>

			</select>
			</label><br><br>
                    
	
           <div class="container">
            <div class="wrapper">
    <header>Transcribe Now</header>
    
    
        <div class="upload-file">          
      <center><i class="bx bx-upload"></i></center>
      <p>Browse File to Upload</p>
                </div>
      <input class="file-input" type="file" name="user_file" id="fileInputLabel" for="fileInput">


      <input class = "removeBGM" type = "checkbox" name = "removeBGM">
      <label for = "removeBGM">Remove BGM <br> <span style = "font-style: italic; color: red;">PS: Remove BGM before translating audio with music.</span></label>
      <!-- accepts only Supported formats: ['m4a', 'mp3', 'webm', 'mp4', 'mpga', 'wav', 'mpeg'] -->
  </div>
 


<button type = "submit" id="yourButtonID" class="custom-button" disabled>Translate</button>

</form>
  <div class="text-section">
        <header>Original text:</header>
        <textarea id="originalText" name="originalText" class="customtextfield" rows="4" readonly><?php
            $data = mysqli_query($dbcon, "SELECT * FROM text_translations WHERE user_id = $id AND from_audio_file = 1 ORDER BY translation_date DESC LIMIT 1")->fetch_row();
            if (isset($_GET['translated']) && $_GET['translated'] == 1) {
                echo $data[6] ?? '';
            }
            ?>
        </textarea>

        <header>Translated text:</header>
        <textarea id="translatedText" name="translatedText" class="customtextfield" rows="4" readonly>`<?php
            if (isset($_GET['translated']) && $_GET['translated'] == 1) {
                echo $data[7] ?? '';
            }
            ?>
        </textarea>
               
             </div>
        </div>
      
<br>    
                <!-- Truncate Text -->
                <div id="myModal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <p id="modalText"></p>
                </div>
                </div>
            <div class="bottom-data">
                <div class="orders">
                <div class = "deleteAllClass">
                    <button type = 'button' class = "deleteAll-btn" id = "a2t">Delete All</button>
                </div>
                    <div class="header">
                        <h2>Recent Audio to Text Translations</h3>
                        <i class='bx bx-filter'></i>
                        <i class='bx bx-search'></i>
                    </div>
                    <table>
                        <thead>
                            <tr>
                            <th>File Name</th>
                            <th>File Type</th>
                            <th>File Size</th>  
                            <th>Original Text</th>
                            <th>Source Language</th>
                            <th>Translated Text</th>
                            <th>Target Language</th>
                            <th>Translation Date</th>    
                            <th>Delete</th>    
                            </tr>
                        </thead>
                        <tbody class = "history-body">
                            <!-- Example rows, replace with your actual file data -->
                        <!-- Displays audio to text history -->
                        <?php Translator::displayHistory($history, "audio2text")?>
                        </tbody>
                    </table>

                </div>

            </div>

        </main>

    </div>
                                
    <script src="scripts/index.js"></script>
    <script src="scripts/delete.js"></script>
</body>

</html>
