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

<body>
    <!-- Main Content -->

    <!-- Navbar -->
<?php include "navbar.php" ?>
    <!-- End of Navbar -->

    <div class="main-content">
        <div class="header">
            <a href="text-text.php">
                <button>
                    <img src="images/translator.png" alt="Language Icon" width="30px">Text
                </button>
            </a>
            <a href="index.php">
                <button>
                    <img src="images/music-file.png" alt="Language Icon" width="30px">Upload a File
                </button>
            </a>
            <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == 1): ?>
                <a href="admin.php">
                    <button>
                        <img src="images/admin.png" alt="Language Icon" width="30px">Admin
                    </button>
                </a>
            <?php endif; ?>
        </div>
        <div class="header-downloadfile-wrapper">
            <div style="text-align:center; width:100%;">
                <p style="color: red;" id="error-message"></p>
                <i><p id="gentle-message"></p></i>
            </div>
        </div>
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
                            <option name="language">auto</option>
                            <?php foreach ($common_langs as $lang => $code): ?>
                                <option name="language">
                                    <?= $lang ?>
                                </option>
                            <?php endforeach; ?>
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
                        <?php endforeach; ?>
                    </select>

                </div>
            </div>
        </div>
        <div class="container">
            <div class="box">
                <!-- START OF FORM, COVERS TWO SELECT AND ONE TEXT AREA -->
                <div class="custom-textfield">

                    <textarea class="textinput" name="text" id="text-input" placeholder='Type Here...'></textarea>

                    <!-- end of form -->
                    </form>
                </div>
            </div>
            <div class="box2">
                <!-- Output text-->
                <div class="output-p" readonly>
                    <p class="test outputText" id="text-output">
                    </p>
                    <span id="translating-message" style="color:gray;font-style:italic;"></span>
                </div>
            </div>
        </div>
        <div class="feedback">
            <button type="button" id="open-feedback">send feedback</button>
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
        <!-- dictionary-->
        <div class="dict-div">
            <div class="dict-div-content">
                <p id="click-english"></p>
                <hr />
                <h2 class="hovered-word"></h2>
                <p class="word-meaning"></p>
            </div>
        </div>

    </div>
    <div class="feedback-sidebar">
        <div class="feedbackcontainer">

            <div class="feedbackheader">
                <button class="close-feedback"
                    onclick="document.querySelector('.feedback-sidebar').classList.remove('active'); document.querySelector('#overlay').classList.remove('active');">X</button>
            </div>
            <form method="post" action="index.php" id="contact-form">
                <h3 class="feedback-text">Send Feedback to Voce</h3>
                <h3>Tell us what you think!</h3>
                <span id="contact-return-message"></span>
                <div class="input-form">
                    <input type="text" name="contact_name" class="form-control name-form fback-input" id="name"
                        placeholder="Your Name">
                </div>
                <div class="input-form">
                    <input type="text" class="form-control subject-form fback-input" name="contact_subject" id="subject"
                        placeholder="Subject">
                </div>
                <div class="input-form">
                    <textarea class="fback-msg" name="contact_message" rows="5" placeholder="Message"></textarea>
                </div>
                <br />
                <div class="submit-fback">
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
            <div>
                <span>Voce © 2024. All Rights Reserved</span>
            </div>
        </div>
    </nav>
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

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/simplePagination.js/1.4/jquery.simplePagination.min.js"
        integrity="sha512-J4OD+6Nca5l8HwpKlxiZZ5iF79e9sgRGSf0GxLsL1W55HHdg48AEiKCXqvQCNtA1NOMOVrw15DXnVuPpBm2mPg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="scripts/index.js"></script>
    <script src="scripts/translation_process2.js"></script>
    <script src="scripts/newindex.js"></script>
    <script src="scripts/contact.js"></script>

</body>

</html>