
<?php require("mysql/mysqli_session.php");
    $current_page = basename($_SERVER['PHP_SELF']);
    if (!isset($_SESSION['username'])) {
        header("location: loginpage.php");
        exit();
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
    <script src="https://kit.fontawesome.com/yourcode.js" crossorigin="anonymous"></script>


</head>

<body>

    <!-- Sidebar -->
    <?php require "sidebar.php"?>

    <!-- Main Content -->
    <div class="content">
        <!-- Navbar -->
        <nav>
            <i class='bx bx-menu' ></i>
        </nav>

        <!-- End of Navbar -->

        <main>
            <div class="header">
                <div class="left">
                    <h1>
                        Hello, <?= $_SESSION['username']; ?>
                    </h1>
                </div>
            </div>

            <!-- Insights -->
            <ul class="insights">
                <li>

                    <span class="info">
                        <h3>
                        <?php 
                        // 2
                        $total_audio = "SELECT COUNT(*) FROM text_translations WHERE user_id = '" . $_SESSION['user_id'] . "' and from_audio_file = 1";
                        $total_audio_result = mysqli_query($dbcon, $total_audio);
                        $audio_result_row = mysqli_fetch_array($total_audio_result);
                       
                            echo $audio_result_row[0];
                        ?>
                        </h3>
                        <p class="text-with-icon">Total Audio to Text Translations</p>
                    </span>
                </li>
                <li>
                    <span class="info">
                        <h3>
                            <?php
                            $total_text = "SELECT COUNT(*) FROM text_translations WHERE user_id = '" . $_SESSION['user_id'] . "' AND from_audio_file = 0";
                            $total_text_result = mysqli_query($dbcon, $total_text);
                            
                            $text_result_row = mysqli_fetch_array($total_text_result);
                            echo $text_result_row[0];
                                ?>
                        </h3>
                        <p>Total Text to Text Translations</p>
                    </span>
                </li>
            </ul>
            <!-- End of Insights -->
            <div class="bottom-data">
                <div class="orders">
                    <div class="header">
                            <img src="images/translateicon.png" width="120px" height="100px">
                            <h3>Text to Text Translation</h3>      
                            <p class="description">
                                Revolutionize your communication and content adaptation with this advanced language transformation technology
                            </p>
                            <button class="button"><a href="text-text.php" class="logo">START TRANSLATING TEXT
                                <i class="fa fa-arrow-circle-o-right"></i></button></a>
                    </div>
                </div>
                
                <!-- Reminders -->
                <div class="orders">
                    <div class="header">
                    <img src="images/languageicon.png" width="120px" height="100px">
                            <h3>Audio to Text Translation</h3>      
                            <p class="description">
                            Audio-to-text translation is the process of converting spoken language or audio content into written text
                            </p>
                            <button class="button"><a href="history_audio.php" class="logo">START TRANSCRIBING AUDIO
                                <i class="fa fa-arrow-circle-o-right"></i></button></a>
                    </div>
                    <br>
                </div>

            </div>

        </main>

    </div>

    <script src="scripts/index.js"></script>
</body>

</html>