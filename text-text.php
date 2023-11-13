<?php require("mysql/mysqli_session.php"); 
$current_page = basename($_SERVER['PHP_SELF']);
?>

<?php if (!isset($_SESSION['username'])) {
        header("location: loginpage.php");
        exit();
} ?>   

<?php
require "Translator_Functions.php";
//  Get language codes for each language
$languages = Translator::getLangCodes();
$lang_codes = [];

// get session id
$id = is_array($_SESSION['user_id']) ? $_SESSION['user_id']['user_id'] : $_SESSION['user_id'];

// Translation history for text to text 
$history = mysqli_query($dbcon, "SELECT * FROM text_translations WHERE user_id = $id AND from_audio_file = 0 ORDER BY translation_date DESC");

foreach($languages as $language){
  $lang_codes[$language["name"]] = $language["code"];
}
// Language Translation, please check https://rapidapi.com/dickyagustin/api/text-translator2 for more information.

// Translate text input
if($_SERVER["REQUEST_METHOD"] == "POST"){ 

    // Error Handling first before translation 
    if ($_POST["src"] == "" || $_POST['target'] == "") {
        // error, user did not choose language
        logs("error-tt", $_SESSION['username'], $dbcon);
        header("Location: text-text.php?error=1");
        exit();
        
    } 
    if (empty(trim($_POST['text']))) {
        // error, user did not type anything
        logs("error-tt", $_SESSION['username'], $dbcon);
        header("Location: text-text.php?error=2");
        exit();
    }
    
  // translates text, get output
  $translation = Translator::translate($_POST["text"], 
      $lang_codes[$_POST["src"]], 
      $lang_codes[$_POST["target"]]
  );

  // insert into database
  $source_lang = $_POST['src'];
  $target_lang = $_POST['target'];
  $orig_text = $_POST["text"];
  $isFromAudio = False;
  
  // db query
  $query_insert = mysqli_prepare($dbcon, "INSERT INTO text_translations(user_id, from_audio_file, original_language, translated_language,
  translate_from, translate_to, translation_date) VALUES (?, ?, ?, ?, ?, ?, NOW())");
  mysqli_stmt_bind_param($query_insert, 'iissss', $id, $isFromAudio, $source_lang, $target_lang, $orig_text, $translation);
  mysqli_stmt_execute($query_insert);

  logs("text-to-text", $_SESSION['username'], $dbcon);

  
  header("Location: text-text.php?translated=1");
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

<!-- Confirm delete window -->
<div class = "delete-window">
    <div class = "confirm-div">
        <h4 class = "confirm-text">Are you sure you want to delete this row?</h4>
        <div class = "confirm-btn-div">
            <button class = "confirm-btn confirm-yes">Yes</button>
            <button class = "confirm-btn confirm-no">No</button>
        </div>
    </div>
</div>

<body>


    <!-- Sidebar -->
    <div class="sidebar">
        <a href="dashboard1.php" class="logo">
            <i class="fa fa-microphone"></i>
            <div class="logo-name"><span>Vo</span>CE</div>
        </a>
        <ul class="side-menu">
            <li><a href="dashboard1.php"><i class='bx bxs-dashboard'></i>Dashboard</a></li>
            <li class="<?php echo ($current_page === 'text-text.php') ? 'active' : ''; ?>">
            <a href="text-text.php"><img src="images/sidebartext.png" alt="scroll icon" width="25" height="25" style="margin-left: 5px;">
            &nbsp Text to Text</a> 
                 

            <li class="<?php echo ($current_page === 'history_audio.php') ? 'active' : ''; ?>">
            <a href="history_audio.php"><img src="images/sidebaraudio.png" alt="scroll icon" width="25" height="25" style="margin-left: 5px;">
            &nbsp Audio to Text</a> 
            <li><a href="#"><i class='bx bx-cog'></i>Settings</a></li>
        </ul>
        <ul class="side-menu">
            <li>
                <a href="logout.php" class="logout">
                    <i class='bx bx-log-out-circle'></i>
                    Logout
                </a>
            </li>
        </ul>
    </div>
    <!-- End of Sidebar -->

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
                    <h1>
                    Text-Text Translator 
                    </h1>


                    <!-- Error Message: Pabago nalang if may naiisip kang ibang design -->
                    <p style="color: red;"><i>
                    <?php
                        if (isset($_GET['error']) && $_GET['error'] == 1) {
                            echo "Please select a source/translated language.";
                        }
                        if (isset($_GET['error']) && $_GET['error'] == 2) {
                            echo "Please type text to be translated.";
                        }
                    ?> 
                    </i></p>

                    
                </div>
            </div>
         

            <!-- Insights -->

            <!-- End of Insights -->
            <div class="bottom-data">

                <!-- SOURCE LANGUAGE DIVISION-->
                <div class="translator">
                    <div class="header">
                        <h3>Source Language</h3>   
                        <br>   
                    </div>


                    <!-- START OF FORM, COVERS TWO SELECT AND ONE TEXT AREA -->
                    <form id="myForm" action="text-text.php" method="POST">   
                        <!-- SELECT LANGUAGE -->     
                        <select name="src" id="SourceLanguage">
                        <option value="">Select One …</option>
                            <?php foreach($languages as $language): ?>
                                <option name = "language"><?= $language["name"]?></option>
                            <?php endforeach ?> 	
                        </select>
            
                       <!-- <input type="text" class="textfield" style="vertical-align: top;"> -->
                        
                       <!-- Input text-->
                       <!-- <input type = "text" name = "text" class="form-control"> -->
                        <textarea class="custom-textfield" name = "text" placeholder='Type Here...'><?php
                        // url must have translated=1 before showing the output
                        if (isset($_GET['translated']) && $_GET['translated'] == 1) {
                            $data = mysqli_query($dbcon, "SELECT * FROM text_translations WHERE user_id = $id AND from_audio_file = 0 ORDER BY translation_date DESC LIMIT 1")->fetch_row();
                            echo $data[6] ?? '';
                        }
                        ?></textarea>

                        <!-- Button to submit -->
                        <a href="javascript:void(0);" onclick="document.getElementById('myForm').submit();">
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
                    <select name="target" class="form-control">
                        <option value="">Select One …</option>
                        <?php foreach($languages as $language): ?>
                            <option name = "language"><?= $language["name"]?></option>
                        <?php endforeach ?>
                    </select>
                    
                    <!-- END OF FORM -->
                    </form>
                     

    
                    <!-- Output text-->
                    <div class="custom-textfield" contenteditable="true" readonly>
                    <p class="test"><?php
                       // url must have translated=1 before showing the output
                       if (isset($_GET['translated']) && $_GET['translated'] == 1) {
                        echo $data[7] ?? '';
                        }
                    ?>
                    </p>
                    </div>
                </div>


                <!-- Table -->
                <div class="orders">
                    <div class="header">
                        <h2>Recent Text to Text Translations</h2>
                        <br>
                    </div>
                    <table>
                        <thead>
                            <tr>
                            <th>Original Text</th>
                            <th>Source Language</th>
                            <th>Translated Text</th>
                            <th>Target Language</th>
                            <th>Translation Date</th>  
                            <th>Delete</th>  
                            </tr>
                        </thead>
                        <tbody class = "history-body">
                        
                        <!-- Displays text to text history -->
                        <?php Translator::displayHistory($history, "text2text")?>
                        </tbody>
                    </table>
                </div>
          
                

                <!-- End of Reminders-->

            </div>

        </main>

    </div>



    <script src="scripts/index.js"></script>
    <script src="scripts/delete.js"></script>

</body>

</html>