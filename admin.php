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

require "utilities/Translator_Functions.php";
$languages = Translator::getLangCodes();
$lang_codes = [];
foreach($languages as $language){
    $lang_codes[$language["name"]] = $language["code"];
  }


  $id = is_array($_SESSION['user_id']) ? $_SESSION['user_id']['user_id'] : $_SESSION['user_id'];


// Translation history for text to text 
$history = mysqli_query($dbcon, "SELECT * FROM text_translations t INNER JOIN audio_files a ON t.file_id = a.file_id WHERE t.user_id = $id AND a.user_id = $id AND t.from_audio_file = 1 ORDER BY translation_date DESC");

// Language Translation, please check https://rapidapi.com/dickyagustin/api/text-translator2 for more information.

// Query for total number of users
$q = "SELECT COUNT(user_id) AS total_users FROM users";
$result = mysqli_query($dbcon, $q);
$num_of_users = mysqli_fetch_assoc($result);


// Query for total number of files uploaded
$q = "SELECT COUNT(file_id) AS total_files FROM audio_files";
$result = mysqli_query($dbcon, $q);
$num_of_files = mysqli_fetch_assoc($result);

// Query for total number of text-to-text translations
$q = "SELECT COUNT(from_audio_file) AS total_t2t FROM text_translations WHERE from_audio_file = 0";
$result = mysqli_query($dbcon, $q);
$num_of_t2t = mysqli_fetch_assoc($result);

// Query for total number of audio-to-text translations
$q = "SELECT COUNT(from_audio_file) AS total_a2t FROM text_translations WHERE from_audio_file = 1";
$result = mysqli_query($dbcon, $q);
$num_of_a2t = mysqli_fetch_assoc($result);
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

        <main style = "padding: 0;">
            <div class = "admin-container">
                <div class = "admin-content content-users">
                    <h1>Total Users</h1>
                    <h1 class = "count"><?= $num_of_users['total_users'] ?></h1>

                </div>    
                <div class = "admin-content content-files">
                    <h1>Total Audio Files uploaded</h1>
                    <h1 class = "count"><?= $num_of_files['total_files']?></h1>
                </div>    
                <div class = "admin-content content-t2t">
                <h1>Total Text Translations</h1>
                <h1 class = "count"><?= $num_of_t2t['total_t2t']?></h1>
                </div>    
                <div class = "admin-content content-a2t">
                <h1>Total Audio Translations</h1>
                <h1 class = "count"><?= $num_of_a2t['total_a2t']?></h1>
                </div>    
                <div class = "admin-content graph">
                  <h1>GRAPH</h1>
                  <button type = "button" class = " change-btn line-btn">Line Chart</button>
                  <button type = "button" class = "change-btn pie-btn">Pie Chart</button>
                  <canvas id = "myChart">
                  </canvas>
                </div>  
            </div>

        </main>

    </div>

    <script src="scripts/index.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>                            
    <script src = "scripts/admin.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js/dist/chart.umd.min.js"></script>

</body>

</html>