<?php require("mysql/mysqli_session.php"); 
    $current_page = basename($_SERVER['PHP_SELF']);
    if (!isset($_SESSION['username'])) {
        header("location: index.php");
        exit(); 
    }

    function dd($item){
        var_dump($item);
        exit();
    }

require("utilities/common_languages.php"); // Translator_Functions and Error Handling are alr required in this file

$id = is_array($_SESSION['user_id']) ? $_SESSION['user_id']['user_id'] : $_SESSION['user_id'];


// Translation history for audio to text 
$history = mysqli_query($dbcon, "SELECT * FROM text_translations t INNER JOIN audio_files a ON t.file_id = a.file_id WHERE t.user_id = $id AND a.user_id = $id AND t.from_audio_file = 1 ORDER BY translation_date DESC");


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

<body id = "<?= $_SESSION['user_id']?>">
    <!-- Sidebar -->
    <?php require "sidebar.php"?>

    <!-- Main Content -->
    <div class="content">
        <!-- Navbar -->
        <nav>
            <i class='bx bx-menu'></i><?= $_SESSION['username']; ?>
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
                        <img src="images/loading.gif" alt="Loading..." />
                        <p id="loadingModalMessage">Loading....</p>
                    </div>
                </div>
            <form enctype="multipart/form-data" id="form" action="utilities/audio_translation.php" method = "POST" onsubmit="showLoading()">

            <label>  
			Model Size:
			<select name="modelSize" id="modelSize" class="form-control">
				<option value="">Select One …</option>
				<option value="base">Base (For Quick Process)</option>
				<option value="small">Small (Balance of Quick and Accurate Transcription)</option>
				<option value="medium">Medium (Most Recommended Model Size)</option>
				<option value="large">Large (Most Accurate, but Slowest Transcription)</option>

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
      <label for = "removeBGM">Remove BGM <br> <span style = "font-style: italic; color: red;">PS: Remove BGM before translating audio with music.</span></label>
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
                    <button type = 'button' class = "deleteSelectedRows" id = "a2t">Delete Selected Rows</button>
                    <button type = 'button' class = "deleteRows-btn" id = "a2t">Delete Rows</button>
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
                        <!-- Displays audio to text history -->
                        <?php Translator::displayHistory($history, "audio2text")?>
                        </tbody>
                    </table>
                    <div id="page-nav"></div>
                </div>

            </div>

        </main>

    </div>
    <!-- for an in-depth walkthrough for pagination, please visit https://bilalakil.me/simplepagination -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>     
    <script src="https://cdnjs.cloudflare.com/ajax/libs/simplePagination.js/1.4/jquery.simplePagination.min.js" integrity="sha512-J4OD+6Nca5l8HwpKlxiZZ5iF79e9sgRGSf0GxLsL1W55HHdg48AEiKCXqvQCNtA1NOMOVrw15DXnVuPpBm2mPg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="scripts/index.js"></script>
    <script src="scripts/delete.js"></script>
    <script src="scripts/translation_process.js"></script>

</body>

</html>
