
<?php require("mysql/mysqli_session.php"); ?>


<?php if (isset($_SESSION['username'])) { ?>

<?php
require "Translator_Functions.php";

//  Get language codes for each language
$languages = Translator::getLangCodes();
$lang_codes = [];


$id = is_array($_SESSION['user_id']) ? $_SESSION['user_id']['user_id'] : $_SESSION['user_id'];

// Translation history for text to text 
$history = mysqli_query($dbcon, "SELECT * FROM text_translations WHERE user_id = $id AND from_audio_file = 0 ORDER BY translation_date DESC");

foreach($languages as $language){
  $lang_codes[$language["name"]] = $language["code"];
}
// Language Translation, please check https://rapidapi.com/dickyagustin/api/text-translator2 for more information.

// Translate text input
if($_SERVER["REQUEST_METHOD"] == "POST"){
  
	$translation = Translator::translate($_POST["text"], 
    $lang_codes[$_POST["src"]], 
    $lang_codes[$_POST["target"]]
  );
  
  $source_lang = $_POST['src'];
  $target_lang = $_POST['target'];
  $orig_text = $_POST["text"];
  $isFromAudio = False;
  

  $query_insert = mysqli_prepare($dbcon, "INSERT INTO text_translations(user_id, from_audio_file, original_language, translated_language,
  translate_from, translate_to, translation_date) VALUES (?, ?, ?, ?, ?, ?, NOW())");
  mysqli_stmt_bind_param($query_insert, 'iissss', $id, $isFromAudio, $source_lang, $target_lang, $orig_text, $translation);
  mysqli_stmt_execute($query_insert);


  /*
  mysqli_query($dbcon, "INSERT INTO text_translations(user_id, from_audio_file, original_language, translated_language,
  translate_from, translate_to) VALUES 
  ('$id',
   '$isFromAudio',
  '$source_lang', 
  '$target_lang',
  '$orig_text', '$translation')");*/

  logs("text-to-text", $_SESSION['username'], $dbcon);
  header("Location: text_translator.php?translated=1");
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="styles.css">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Dela+Gothic+One&family=Roboto+Mono:wght@100&family=Young+Serif&display=swap"
    rel="stylesheet">

</head>

<body class="vh-100">
  <nav class="navbar navbar-expand-lg navbar-light bg-transparent">
    <div class="container">
      <!-- Logo -->

      <a class="navbar-brand fs-4 text-light nav-link active" style="color: #D3C5C3; font-family: 'Young Serif', serif;"
        aria-current="page" href="index.php">Voice</a>




      <!-- Sidebar Body -->
      <div class="offcanvas-body d-flex flex-column flex-lg-row p-4">
        <ul class="navbar-nav justify-content-center align-items-center fs-5 flex-grow-1 pe-3">
          <li class="nav-item mx-2">
            <a class="nav-link" style="color: #D2ACA4; font-family: 'Roboto Mono', monospace;" href="#">Sample Audio</a>
          </li>
          <li class="nav-item mx-2">
            <a class="nav-link" style="color: #D2ACA4; font-family: 'Roboto Mono', monospace;" href="#">Features</a>
          </li>
          <li class="nav-item mx-2">
            <a class="nav-link" style="color: #D2ACA4; font-family: 'Roboto Mono', monospace;" href="#">Enthusiasts</a>
          </li>
        </ul>
        <!-- Login -->
        <div class="d-flex flex-column justify-content-conter align-items-center gap-3 flex-lg-row">
          
          <a href="logout.php" class="btn btn-primary rounded-pill text-center"
            style="border-width: 2px; padding: 10px 20px; font-family: 'Young Serif', serif;">Logout</a>

          <!--<button class="btn btn-primary rounded-pill text-center" data-bs-toggle="modal" data-bs-target="#enroll"
            style="border-width: 2px; padding: 10px 20px; font-family: 'Young Serif', serif;">Logout</button>-->

        </div>
      </div>
    </div>
    </div>
  </nav>
  <!-- Dashboard content -->

    <!-- Text File -->
    <center>
    <div class="col-md-4">
        <div class="dashboard-rectangle1" style="background-color: #D2ACA4;">
        <center><h3 class="text-dark">Text to Translate <i class="fas fa-download"></i></h3></center>
          <p><form action = "text_translator.php" method = "POST" >
		<input type = "text" name = "text" class="form-control"><br>
		<label>
		Source language:
		<select name="src" class="form-control">
			<option value="">Select One …</option>
			<?php foreach($languages as $language): ?>
				<option name = "language"><?= $language["name"]?></option>
			<?php endforeach ?> 	
		</select>
		</label>
		<label>
		Target language:
		<select name="target" class="form-control">
			<option value="">Select One …</option>
			<?php foreach($languages as $language): ?>
				<option name = "language"><?= $language["name"]?></option>
			<?php endforeach ?>
		</select>
		</label><br><br>
		<button type = "submit" class="rounded-pill" style="border-width: 2px; padding: 10px 20px;">Translate</button>
	</form>
				<br>
	<div class="text-center">
	<p class="text-dark" style="font-family: Times New Roman, Times, serif; font-size: 150%;" >Original:
   <?php
   $data = mysqli_query($dbcon, "SELECT * FROM text_translations WHERE user_id = $id AND from_audio_file = 0 ORDER BY translation_date DESC LIMIT 1")->fetch_row();
   echo $data[6] ?? '';
   ?></p>
	<p class="text-dark" style="font-family: Times New Roman, Times, serif; font-size: 150%;">Translated: <?php 
   echo $data[7] ?? '';
   
  ?> </p>
			</div>
        </div>
</div>
    </center>
    
      <!-- Translate History -->
      <div class="container mt-4">
        <table class="table table-hover table-bordered">
          <thead>
            <tr>
              <th>Original Text</th>
              <th>Source Language</th>
              <th>Translated Text</th>
              <th>Target Language</th>
              <th>Translation Date</th>                   

            </tr>
          </thead>
          <tbody>
            <!-- Example rows, replace with your actual file data -->
            <?php while($row = mysqli_fetch_assoc($history)) : ?>
            <tr>
              <td><?= $row['translate_from'] ?></td>
              <td><?= $row['original_language'] ?></td>
              <td><?= $row['translate_to']?></td>
              <td><?= $row['translated_language']?></td>
              <td><?= $row['translation_date']?></td>

            </tr>
            <?php endwhile ?>

            <!-- Add more rows for additional files -->
          </tbody>
        </table>
      </div>

    </div>
  </div>
  <!--  <div class="col-md-6">
			<iframe class="embed-responsive-item rounded-pill" width="660" height="500"	src="vid.mp4" frameborder="0" ></iframe>
         <img src="vid.mp4" alt="Sample Image" class="img-fluid rounded-pill"> 
        </div> -->
  </div>
  </div>

  <!-- Popup-->
  <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="rectangle-outline">
                    <div class="rectangle">
                        <i class="fas fa-download fa-5x"></i>
                        <p class="text-center mt-3 text-white" style="font-size: 18px;">Upload Your File</p>
                        <input class="bg-primary text-white" style="font-size: 14px;" value="Browse Your Computer" type="file">
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>







  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
    crossorigin="anonymous"></script>
  <script src="javascript.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>

</html>
<?php } else {
  header("location: index.php");
  exit();

 } ?>