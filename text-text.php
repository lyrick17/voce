<?php require ("mysql/mysqli_session.php");
$current_page = basename($_SERVER['PHP_SELF']);

require ("utilities/common_languages.php"); // Translator_Functions and Error Handling are alr required in this file
require ("utilities/recent_text_translation.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <!-- <link rel="stylesheet" href="styles/style2.css"> -->
    <link rel="stylesheet" href="styles/text-textstyle.css">
    <link rel="stylesheet" href="styles/simplePagination.css">
    <title>Text to Text Translation</title>
    <link rel="icon" type="image/x-icon" href="images/icon.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


</head>

<!-- Confirm delete window 
<div class="delete-window">
    <div class="confirm-div">
        <h4 class="confirm-text"></h4>
        <div class="confirm-btn-div">
            <button class="confirm-btn confirm-yes">Yes</button>
            <button class="confirm-btn confirm-no">No</button>
        </div>
    </div>
</div> -->

<body>
    <!-- Main Content -->

    <!-- Navbar -->
    <nav>
        <div class="logo">
            <img src="images/logo.png" alt="Voce logo">
            <span>Text Translator</span>
        </div>
    </nav>
    <!-- End of Navbar -->
    <div class="sidebar">
        <div class="top-iconsheader">
            <div class="top-icons">
                <a href=""><img src="images/anti-clockwise.png" alt="Language Icon" width="30px"></a>
                <a href=""><img src="images/anti-clockwise.png" alt="Language Icon" width="30px"></a>
            </div>
            <div class="top-close">
                <a href=""><img src="images/xmas.png" alt="Language Icon" width="30px"></a>
            </div>
        </div>
        <div class="sidebar-header">
            <h2 style="margin-left: 10px;">History</h2>
        </div>
        <div class="sidebar-headerclear">
            <a href="#" style="margin-right: 4px; padding: 5px;">Clear all history</a>
        </div>
        <br>
        <div class="sidebar-history">
            Testting first
            Testting first
            Testting first
            Testting first
        </div>
        <div class="robot-section">
            <img src="images/hi-robot.gif" class="robot" alt="robot waving" width="80%">
        </div>
    </div>
    <div class="main-content">
        <div class="header">
            <a href="text-text.php"><button><img src="images/translator.png" alt="Language Icon"
                        width="30px">Text</button></a>
            <a href="index.php"><button><img src="images/music-file.png" alt="Language Icon" width="30px">Upload
                    a File</button></a>
        </div>
        <div class="header-downloadfile-wrapper">
            <div class="header-downloadfile" dir="rtl" id="download-file" style="display: none;">
                <form method="post" action="utilities/download_text_output.php">
                    <button type="submit" name="text">
                        <img src="images/write.png" alt="Language Icon" width="30px">Download as Text File</button>
                    <button type="submit" name="word"><img src="images/download.png" alt="Language Icon"
                            width="30px">Download as Word File</button>
                </form>
            </div>
        </div>
        <!-- Error Message: Pabago nalang if may naiisip kang ibang design -->
        <p style="color: red; display: none;" id="error-message"><i>
                <?php
                if (isset ($_GET['error'])) {
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
        <div class="header-language selector">
            <!-- SELECT LANGUAGE -->
            <div class="language-selector-wrapper">
                <div class="language-selector">
                    <label class="label1">
                        Source language:
                    </label>
                    <form id="myForm" action="utilities/text_translation.php" method="POST" onsubmit="showLoading()">

                        <select name="src" id="sourceLanguage">
                            <option value="">Select One …</option>
                            <?php foreach ($common_langs as $lang => $code): ?>
                                <option name="language">
                                    <?= $lang ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                </div>
            </div>
            <div class="language-selector-wrapper">
                <div class="language-selector">
                    <label class="label1">
                        Target language:
                    </label>
                    <select name="target" id="targetLanguage" class="form-control">
                        <!-- Will display languages supported by API only-->
                        <option value="">Select One …</option>
                        <?php foreach ($common_langs as $lang => $code): ?>
                            <option name="language">
                                <?= $lang ?>
                            </option>
                        <?php endforeach ?>
                    </select>

                </div>
            </div>
        </div>
        <div class="container">
            <div class="box">
                <!-- START OF FORM, COVERS TWO SELECT AND ONE TEXT AREA -->
                <div class="custom-textfield">

                    <?php // url must have translated=1 before showing the output
                    if (isset ($_SESSION['recent_text'])) {
                        $textid = $_SESSION['recent_text'];
                        $data = mysqli_query($dbcon, "SELECT * FROM text_translations WHERE text_id = '$textid' AND from_audio_file = 0 ORDER BY translation_date DESC LIMIT 1")->fetch_row();
                    }
                    ?>
                    <?php if (isset ($_SESSION['recent_text']) && isset ($_GET['translated']) && $_GET['translated'] == 1) {
                        echo "Language: " . $data[4];
                    } ?>
                    <textarea class="textinput" name="text" id="text-input" placeholder='Type Here...'><?php
                    if (isset ($_SESSION['recent_text']) && isset ($_GET['translated']) && $_GET['translated'] == 1) {
                        echo $data[6] ?? '';
                    }
                    ?></textarea>

                    <?php if (isset ($_SESSION['recent_text']) && isset ($_GET['translated']) && $_GET['translated'] == 1) {
                        echo "Language: " . $data[5];
                    } ?>
                    <!-- end of form -->
                    </form>
                </div>
            </div>
            <div class="box2">
                <!-- Output text-->
                <div class="output-p" readonly>
                    <p class="test outputText" id="text-output">
                    </p>
                </div>
            </div>
            <div class="options">
                <a href=""><img src="images/anti-clockwise.png" alt="Language Icon" width="30px"></a>
                <label>History</label>
                <br>
                <a href=""><img src="images/dictionary-icon.png" alt="Language Icon" width="30px"></a>
                <label>Dictionary</label>
            </div>
        </div>
        <div class="feedback">
            <button type="button" id="open-feedback">send feedback</button>
        </div>
        <!-- dictionary-->
        <div class="dict-div">
            <h2 class="hovered-word">Word</h2>
            <p class="word-meaning">Meaning: </p>
        </div>

    </div>
    <div class="feedback-sidebar">
        <div class="feedbackcontainer">
            <div class="feedbackheader">
                <h3>Send Feedback to Voce</h3>
                <button class="close-feedback"
                    onclick="document.querySelector('.feedback-sidebar').classList.remove('active'); document.querySelector('#overlay').classList.remove('active');">X</button>
            </div>
            <form method="post" action="index.php" id="contact-form">
                <h3>Tell us what you think!</h3>
                <span id="contact-return-message"></span>
                <div class="input-form">
                    <input type="text" name="contact_name" class="form-control name-form" id="name"
                        placeholder="Your Name">
                </div>
                <div class="input-form">
                    <input type="text" class="form-control subject-form" name="contact_subject" id="subject"
                        placeholder="Subject">
                </div>
                <div class="feedback-textfield">
                    <textarea class="form-control no-resize" name="contact_message" rows="5"
                        placeholder="Message"></textarea>
                </div>
                <br />
                <div class="text-center">
                    <button type="submit" class="feedback-button" name="contact_submit">
                        Send Message
                    </button>
                </div>
            </form>
        </div>
    </div>


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
    <script src="scripts/landingpage.js"></script>
    <script>
        function updateBox2Height() {
            const outputP = document.querySelector(".outputText");
            const padding = parseInt(getComputedStyle(outputP).paddingTop) + parseInt(getComputedStyle(outputP).paddingBottom);
            const newHeight = outputP.scrollHeight + padding + "px";

            const box2 = outputP.closest('.box2');
            box2.style.height = newHeight;
        }

        // Adapting box1's logic for box2:
        const outputP = document.querySelector(".outputText");
        outputP.addEventListener("input", () => { // Use "input" event for broader compatibility
            updateBox2Height();
        });
    </script>
    <script>
        const textInput = document.getElementById('text-input');
        const container = textInput.closest('.box'); // Get the closest parent box


        textInput.addEventListener('input', function () {
            const desiredHeight = this.scrollHeight + parseInt(getComputedStyle(this).paddingTop) + parseInt(getComputedStyle(this).paddingBottom);
            container.style.height = desiredHeight + 'px';
        });
    </script>
    <script>
        const textarea = document.querySelector("textarea");
        textarea.addEventListener("keyup", e => {
            textarea.style.height = "63px";
            let scHeight = e.target.scrollHeight;
            textarea.style.height = `${scHeight}px`;
        });
    </script>

    <script src="scripts/landingpage.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/simplePagination.js/1.4/jquery.simplePagination.min.js"
        integrity="sha512-J4OD+6Nca5l8HwpKlxiZZ5iF79e9sgRGSf0GxLsL1W55HHdg48AEiKCXqvQCNtA1NOMOVrw15DXnVuPpBm2mPg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="scripts/index.js"></script>
    <script src="scripts/translation_process2.js"></script>
    <script src="scripts/newindex.js"></script>
    <script src="scripts/contact.js"></script>
    <!-- <script src="scripts/delete.js"></script> -->




</body>

</html>