<?php require("mysql/mysqli_session.php"); 
    $current_page = basename($_SERVER['PHP_SELF']);

    function dd($item){
        var_dump($item);
        exit();
    }

require("utilities/common_languages.php"); // Translator_Functions and Error Handling are alr required in this file
require("utilities/recent_audio_translation.php");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="styles/style2.css">
    <link rel="stylesheet" href="styles/simplePagination.css">
    <title>Audio to Text Translation</title>
    <link rel="icon" type="image/x-icon" href="images/icon.ico">
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
                    <p style="color: red;" id="error-message"><i>
                    <?php
                        if (isset($_GET['error'])) {
                            switch ($_GET['error']) {
                                case 1: // user did not choose language
                                    echo "Please select a model or source/translated language.";
                                    break;
                                case 2:  // user did not upload file
                                    echo "No File Upload. Please try again.";
                                    break;
                                case 3: // user upload wrong file type
                                    echo "File Format Not Supported. Please try again.";
                                    break;
                                case 4: // user choose two same language
                                    echo "Please choose two different language.";
                                    break;
                                case 5: // it's what the programmers say, "WHY IS IT NOT WORKING??"
                                    echo "Audio File not processed well. Please try again.";
                                    break;
                                case 6: // user added unprovided choices
                                    echo "Please choose only on the provided models/languages.";
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
                        <img src="images/duckagain.gif" alt="Loading..." />
                        <p id="loadingModalMessage">Loading....</p>
                    </div>
                </div>
            <form enctype="multipart/form-data" id="form" action="utilities/audio_translation.php" method = "POST" onsubmit="showLoading()">

            
            <br>
            <label>  
			Source language:
			<select name="src" id="sourceLanguage" class="form-control">
                <!-- Will display Languages supported by API and Whisper -->
				<option value="auto">Auto-Detect Language...</option> <!-- Auto-Detect --> 
                <?php foreach($common_langs as $lang => $code): ?>
                    <option name = "language"><?= $lang ?></option>
                <?php endforeach ?> 	

			</select>
			</label>
            <br>
			<label>
			Target language:
			<select name="target" id="targetLanguage" class="form-control">
                <!-- Will display languages supported by API only-->
				<option value="">Select One …</option>
                <?php foreach($common_langs as $lang => $code): ?>
                    <option name = "language"><?= $lang ?></option>
                <?php endforeach ?> 	

			</select>
			</label><br><br>
                    
            <div class="container">
                <button id="mic" class="mic-toggle">Record Now</button>
                <audio class="playback" controls></audio>
            </div>


           <div class="container">
            <div class="wrapper">
    <header>Transcribe Now</header>
    
    
        <div class="upload-file" id="drop-zone" 
                ondrop="fileDropHandler(event);" 
                ondragover="dragOverHandler(event);"
                ondragenter="dragEnterHandler(event);"
                ondragleave="dragLeaveHandler(event);">
      <center><i class="bx bx-upload"></i></center>
      <p>Drag and Drop File to Upload</p>
                </div>
      <input class="file-input" type="file" name="user_file" id="fileInputLabel" for="fileInput">


      <input class = "removeBGM" type = "checkbox" name = "removeBGM">
      <label for = "removeBGM">Remove BGM <br> <span style = "font-style: italic; color: red;">*Remove BGM before translating audio with music.</span></label>
      <br><span style = "font-style: italic; color: red;">*Only a maximum of 60MB file is accepted.</span>
    </div>
 


<button type = "submit" id="yourButtonID" class="custom-button" disabled>Translate</button>

</form>
  <div class="text-section">
        <header>Original text:</header>
        <textarea id="originalText" name="originalText" class="customtextfield" rows="4" readonly><?php
            if (isset($_SESSION['recent_audio'])) {
                $textid = $_SESSION['recent_audio']; 
                $data = mysqli_query($dbcon, "SELECT * FROM text_translations WHERE text_id = '$textid' AND from_audio_file = 1 ORDER BY translation_date DESC LIMIT 1")->fetch_row();
            }
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

        </main>

    </div>
    <!-- for an in-depth walkthrough for pagination, please visit https://bilalakil.me/simplepagination -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>     
    <script src="https://cdnjs.cloudflare.com/ajax/libs/simplePagination.js/1.4/jquery.simplePagination.min.js" integrity="sha512-J4OD+6Nca5l8HwpKlxiZZ5iF79e9sgRGSf0GxLsL1W55HHdg48AEiKCXqvQCNtA1NOMOVrw15DXnVuPpBm2mPg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="scripts/live_record.js"></script>
    <script src="scripts/index.js"></script>
    <script src="scripts/translation_process.js"></script>

</body>

</html>
