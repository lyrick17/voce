<?php require ("mysql/mysqli_session.php");
$current_page = basename($_SERVER['PHP_SELF']);

function dd($item)
{
    var_dump($item);
    exit();
}
// Call the test connection function to check if api is on, which would direct the user on translation page
require ("utilities/Translator_Functions.php");
Translator::checkConnection();

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
    <title>Audio to Text Translation</title>
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
        <div class="container-service">
            <h3 style="text-align:center;">Voce Service Unavailable</h3>
            <hr>
            <div style="background-color: #fff; padding: 20px;  margin: 15px; border-radius: 10px; text-align:center;">
                We apologize, but our <b>Translation Services</b> is currently experiencing technical
                difficulties.
                Our team is working diligently to restore the service.
                Please check back later. Thank you for your understanding!
            </div>
            <center> <img src="images/servicerobot.png" style="opacity: 0.5; border-radius: 10px;"></center>
        </div>
    </div>
    <br>

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
    </div>
    <!-- for an in-depth walkthrough for pagination, please visit https://bilalakil.me/simplepagination -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/simplePagination.js/1.4/jquery.simplePagination.min.js"
        integrity="sha512-J4OD+6Nca5l8HwpKlxiZZ5iF79e9sgRGSf0GxLsL1W55HHdg48AEiKCXqvQCNtA1NOMOVrw15DXnVuPpBm2mPg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="scripts/index.js"></script>
    <script src="scripts/translation_process.js"></script>
    <script src="scripts/newindex.js"></script>
</body>

</html>