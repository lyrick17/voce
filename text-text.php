<?php require("mysql/mysqli_session.php"); 
    $current_page = basename($_SERVER['PHP_SELF']);
    
require("utilities/common_languages.php"); // Translator_Functions and Error Handling are alr required in this file
require("utilities/recent_text_translation.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="styles/style2.css">
    <link rel="stylesheet" href="styles/simplePagination.css">
    <title>Text to Text Translation</title>
    <link rel="icon" type="image/x-icon" href="images/icon.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://kit.fontawesome.com/yourcode.js" crossorigin="anonymous"></script>


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
           
<!-- Add this div for the loading feature -->
<!-- Loading Modal after clicking translate button -->
  
            <div class="header">
                <div class="left">
                    <h1>
                    Text-Text Translator 
                    </h1>


                    <!-- Error Message: Pabago nalang if may naiisip kang ibang design -->
                    <p style="color: red;" id="error-message"><i>
                    <?php
                        if (isset($_GET['error'])) {
                            switch ($_GET['error']) {
                                case 1: // user did not chooose language
                                    echo "Please select a source/translated language.";
                                    break;
                                case 2: // user did not enter text
                                    echo "Please choose two different language.";
                                    break;
                                case 3: // user chose two same language
                                    echo "Please type text to be translated.";
                                    break;
                                case 4: // user added unprovided choices
                                    echo "Please choose only on the provided models/languages.";
                                    break;
                            }
                        }
                    ?> 
                    </i></p>

                    
                </div>
            </div>
         

  

            <!-- Truncate Text -->
            <div id="myModal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <p id="modalText"></p>
                </div>
            </div>
            
            <div class="bottom-data">

                <!-- SOURCE LANGUAGE DIVISION-->
                <div class="translator">
                    <div class="header">
                        <h3>Source Language</h3>   
                        <br>   
                    </div>

                    <div id="loadingModal" class="modalloading">
                    <div class="modal-contentloading">
                        <img src="images/loading.gif" alt="Loading..." />
                        <p>Loading....</p>
                    </div>
                </div>

                    <!-- START OF FORM, COVERS TWO SELECT AND ONE TEXT AREA -->
                    <form id="myForm" action="utilities/text_translation.php" method="POST" onsubmit="showLoading()">   
                        <?php // url must have translated=1 before showing the output
                            if (isset($_SESSION['recent_text'])) {
                                $textid = $_SESSION['recent_text']; 
                                $data = mysqli_query($dbcon, "SELECT * FROM text_translations WHERE text_id = '$textid' AND from_audio_file = 0 ORDER BY translation_date DESC LIMIT 1")->fetch_row();
                            }
                        ?>
                        <!-- SELECT LANGUAGE -->     
                        <select name="src" id="sourceLanguage">
                        <option value="">Select One …</option>
                            <?php foreach($common_langs as $lang => $code): ?>
                                <option name = "language"><?= $lang ?></option>
                            <?php endforeach ?> 	
                        </select>
                        <br>
                        <?php if (isset($_SESSION['recent_text']) && isset($_GET['translated']) && $_GET['translated'] == 1) { echo "Language: " . $data[4]; } ?>
                       <!-- <input type = "text" name = "text" class="form-control"> -->
                        <textarea class="custom-textfield" name = "text" id = "text-input" placeholder='Type Here...'><?php
                        if (isset($_SESSION['recent_text']) && isset($_GET['translated']) && $_GET['translated'] == 1) {
                            echo $data[6] ?? '';
                        }
                        ?></textarea>

                        <!-- Button to submit -->
                        <!--<button type = "submit" id="yourButtonID" class="custom-button">Translate</button> -->

                        <a href="javascript:void(0);" onclick="document.getElementById('myForm').submit(); showLoading();">
                        <img src="images/arrows.png" alt="Image Description" class="center-image" width="50px" height="40px">
                        </a>
                     
                </div>

                <!-- TARGETED LANGUAGE DIVISION -->
                <div class="translator">
                    <div class="header">
                        <h3>Targeted Language</h3>
                        <br>
                    </div>

                    <!-- SELECT LANGUAGE -->  
                    <select name="target" class="form-control" id="targetLanguage">
                        <option value="">Select One …</option>
                        <?php foreach($common_langs as $lang => $code): ?>
                            <option name = "language"><?= $lang ?></option>
                        <?php endforeach ?> 	
                    </select>
                    <br>
                    <?php if (isset($_SESSION['recent_text']) && isset($_GET['translated']) && $_GET['translated'] == 1) { echo "Language: " . $data[5]; } ?>
                    <!-- END OF FORM -->
                    </form>
                     

    
                    <!-- Output text-->
                    <div class="custom-textfield" contenteditable="true" readonly>
                    <p class="test outputText" id="text-output">
                    </p>
                    </div>
                </div>
                <div class = "dict-div">
                    <h2 class = "hovered-word">Word</h2>
                    <p class = "word-meaning">Meaning: </p>
     

                </div>

                <div class="download button" dir="rtl" id="download-file" style="display:none;">
                    <form method="post" action="utilities/download_text_output.php">
                        <button type="submit" name="text" style="padding:5px;">Download as Text File</button>   
                        <button type="submit" name="word" style="padding:5px;">Download as Word File</button>
                    </form>
                </div>
            </div>
        </main>

                    
    </div>

    <!-- for an in-depth walkthrough for pagination, please visit https://bilalakil.me/simplepagination -->
    <script src="scripts/dictionary.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/simplePagination.js/1.4/jquery.simplePagination.min.js" integrity="sha512-J4OD+6Nca5l8HwpKlxiZZ5iF79e9sgRGSf0GxLsL1W55HHdg48AEiKCXqvQCNtA1NOMOVrw15DXnVuPpBm2mPg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="scripts/index.js"></script>
    <script src="scripts/translation_process2.js"></script>
    <!-- <script src="scripts/delete.js"></script> -->



</body>

</html>