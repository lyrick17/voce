<?php require ("mysql/mysqli_session.php");
$current_page = basename($_SERVER['PHP_SELF']);

function dd($item)
{
    var_dump($item);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <!--<link rel="stylesheet" href="styles/style2.css"> -->
    <link rel="stylesheet" href="styles/simplePagination.css">
    <link rel="stylesheet" href="styles/index-style.css">
    <link rel="stylesheet" href="styles/faqs.css">
    <title>FAQs Voce</title>
    <link rel="icon" type="image/x-icon" href="images/icon.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

</head>

<body>

    <!-- Navbar -->
    <nav>
        <div class="logo">
            <a href="index.php"> <img src="images/logo.png" alt="Voce logo"></a>
            <span>Text Translator</span>
        </div>
    </nav>
    <!-- End of Navbar -->
    <div class="main-content">

        <div id="loadingModal" class="modalloading">
            <div class="modal-contentloading">
                <img src="images/duckagain.gif" alt="Loading..." />
                <p id="loadingModalMessage">Loading....</p>
            </div>
        </div>
        <div class="header">
            <a href="text-text.php"><button><img src="images/translator.png" alt="Language Icon"
                        width="30px">Text</button></a>
            <a href="index.php"><button><img src="images/music-file.png" alt="Language Icon" width="30px">Upload
                    a File</button></a>
        </div>
        
        <div class="header-language selector">
            <div class="">
                <div class="language-selector">
                    <h2>Frequently Asked Questions about Voce</h2>
                </div>
            </div>
        </div>
        <div class="container">

                <div class="faq-box">
                    <div class="text-section">
                        <h3>How to use Audio to Text Translation?</h3>
                        <p>
                            - A source and target language must be selected first to determine what language do you want your speech to be translated into.
                            After selecting, you can upload your audio file or record your speech and click the "Translate" button to start the translation process.
                        </p>
                        <hr />
                        <h3>Is there a file size limit?</h3>
                        <p>
                            - Yes. The file size that Voce can process must only be up to 25mb. Anything more than the allowed limit will not be processed.
                        </p>
                        <hr />
                        <h3>Can I upload and record at the same time?</h3>
                        <p>
                            - Unfortunately, we only allow one submitted audio per translation process. Recording your speech will forfeit your uploaded audio, and uploading your audio file will disregard your previously recorded speech.
                        </p>
                        <hr />
                        <h3>An error saying "Audio file not processed well" is showing. What does this mean?</h3>
                        <p>
                            - This error message means that the audio file you uploaded or recorded was not able to capture your speech, thus there is no speech to be translated. 
                            This may be due to the audio quality. Please make sure that the audio file you are submitting is in the correct format and is of good quality.
                        </p>
                        <hr />
                        <h3>How can I translate an audio with background music / noise?</h3>
                        <p>
                            - Before clicking the "Translate" button, check the Remove BGM checkbox to allow 
                            the system to remove the background music or noise from your audio file. 
                            This further ensures that the translation process will be more accurate.<br />
                            - However, consider that clicking this option would take longer to process your audio file.
                        </p>
                        <hr />
                        <h3>Can I translate a video?</h3>
                        <p>
                            - Yes! Voce also process video files. 
                            However, only the audio from the video will be processed and translated. 
                            Furthermore, it must be in the accepted file formats and 25mb file size or below.
                        </p>
                        <hr />
                        <h3>What are the file formats accepted?</h3>
                        <p>
                            - The file formats accepted are:
                                <i>m4a, mp3, webm, mp4, mpga, wav, mpeg</i>
                        </p>
                        <hr />
                        <h3>How can I save the Translation?</h3>
                        <p>
                            - An option to download the translated text as a text file or word file will be available 
                            after the translation process is completed. You can download the translated text by clicking the "Download as Text File" or "Download as Word File" button.
                        </p>
                        <hr />
                        
                    </div>
                </div>
                


        </div>
        </form>

    </div>
    <!-- Live Recording 
                <div class="container">
                    <input type="hidden" name="record" />
                    <button type="button" id="mic" class="mic-toggle">Record Now</button>
                    <audio class="playback" controls></audio>
                </div> -->


    <!-- <div class="container">
                    <div class="wrapper">
                        <header>Transcribe Now</header>


                        <div class="upload-file" id="drop-zone" ondrop="fileDropHandler(event);"
                            ondragover="dragOverHandler(event);" ondragenter="dragEnterHandler(event);"
                            ondragleave="dragLeaveHandler(event);">
                            <center><i class="bx bx-upload"></i></center>
                            <p>Drag and Drop File to Upload</p>
                        </div>
                        <input class="file-input" type="file" name="user_file" id="fileInputLabel" for="fileInput">
                        <input class="removeBGM" type="checkbox" name="removeBGM">
                        <label for="removeBGM">Remove BGM <br> <span style="font-style: italic; color: red;">*Remove
                                BGM
                                before translating audio with music.</span></label>
                        <br><span style="font-style: italic; color: red;">*Only a maximum of 60MB file is
                            accepted.</span>
                    </div> 



                    <button type="submit" id="yourButtonID" class="custom-button">Translate</button> -->


    <?php if (isset ($_SESSION['recent_audio']) && isset ($_GET['translated']) && $_GET['translated'] == 1): ?>
        <div class="download button" dir="rtl" id="download-file">
            <form method="post" action="utilities/download_audio_output.php">
                <button type="submit" name="text" style="padding:5px;">Download as Text File</button>
                <button type="submit" name="word" style="padding:5px;">Download as Word File</button>
            </form>
        </div>
    <?php endif; ?>

    <br>
    
    <nav>
        <div class="footer">
        <div>
            <a href="faqs.php">FAQs</a>
            <a href="about.php">About Voce</a>
        </div>
        <div >
            <span>Voce © 2024. All Rights Reserved</span>
        </div>
        </div>
    </nav>


    <!-- for an in-depth walkthrough for pagination, please visit https://bilalakil.me/simplepagination -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/simplePagination.js/1.4/jquery.simplePagination.min.js"
        integrity="sha512-J4OD+6Nca5l8HwpKlxiZZ5iF79e9sgRGSf0GxLsL1W55HHdg48AEiKCXqvQCNtA1NOMOVrw15DXnVuPpBm2mPg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="scripts/index.js"></script>
    <script src="scripts/translation_process.js"></script>
</body>

</html>