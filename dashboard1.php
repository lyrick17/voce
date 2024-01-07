
<?php require("mysql/mysqli_session.php");
    $current_page = basename($_SERVER['PHP_SELF']);
    if (!isset($_SESSION['username'])) {
        header("location: loginpage.php");
        exit();
    }

    require("utilities/faqs.php");
     
    // 2
    $total_audio = "SELECT COUNT(*) FROM text_translations WHERE user_id = '" . $_SESSION['user_id'] . "' and from_audio_file = 1";
    $total_audio_result = mysqli_query($dbcon, $total_audio);
    $audio_result_row = mysqli_fetch_array($total_audio_result);
   
    $total_audio = $audio_result_row[0];


    $total_text = "SELECT COUNT(*) FROM text_translations WHERE user_id = '" . $_SESSION['user_id'] . "' AND from_audio_file = 0";
    $total_text_result = mysqli_query($dbcon, $total_text);
    
    $text_result_row = mysqli_fetch_array($total_text_result);

    $total_text = $text_result_row[0];

    $total_message_array = array();
    
    // message to display on TEXT TO TEXT part
    if ($total_text == 0) {
        // message to display if user hasn't translated in text to text yet.
        array_push($total_message_array, "Translate Now in Text to Text translation!");

    } else if ($total_text > $total_audio) {
        // message to display if user translated MORE in text to text
        array_push($total_message_array, "You have been using Text to Text more with a total of  <b>" . $total_text . "</b> times.");
    
    } else if ($total_text < $total_audio) {
        // message to display if user translated LESS in text to text
        array_push($total_message_array, "You have a total of <b>" . $total_text . "</b> Text to Text translation.");
    
    } else if ($total_text == $total_audio) {
        // message to display if user both utilized text and audio the same
        array_push($total_message_array, "You have been using both Text to Text and Audio to Text translation with a total of <b>" . $total_text . "</b> times.");
    }
    
    // message to display on AUDIO TO TEXT part
    if ($total_audio == 0) {
      // message to display if user hasn't translated in audio to text yet.
      array_push($total_message_array, "You haven't translated any audio yet! Translate one now!");

    } else if ($total_audio > $total_text) {
      // message to display if user translated MORE in audio to text
      array_push($total_message_array, "You have been using Audio to Text more with a total of  <b>" . $total_audio . "</b> times.");
    
    } else if ($total_audio < $total_text) {
      // message to display if user translated LESS in audio to text
      array_push($total_message_array, "You have a total of <b>" . $total_audio . "</b> audio to text translation.");
    
    } else if ($total_audio == $total_text) {
      // message to display if user both utilized text and audio the same
      array_push($total_message_array, "Thank you for using both Text to Text and Audio to Text Translation Service!");
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
    <link rel="icon" type="image/x-icon" href="images/icon.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://kit.fontawesome.com/yourcode.js" crossorigin="anonymous"></script>
    <style>
.accordion {
  display: flex;
  max-width: 100%; /* Change max-width to 100% */
  width: 100%;    /* Add width: 100% */
  align-items: center;
  justify-content: space-between;
  background: #fff;
  border-radius: 25px;
  padding: 45px 90px 45px 60px;
}
.accordion .image-box {
  height: 100%; /* Set height to 100% */
  width: 300px;
}
.accordion .image-box img{
  height: 100%;
  width: 100%;
  object-fit: contain;
  border-radius: 25px;
}
.accordion .accordion-text{
  width: 60%;
}
.accordion .accordion-text .title{
  font-size: 35px;
  font-weight: 600;
  color: #7d2ae8;
  font-family: 'Fira Sans', sans-serif;
}
.accordion .accordion-text .faq-text{
  margin-top: 25px;
  height: 263px;
  overflow-y: auto;
}
.faq-text::-webkit-scrollbar{
  display: none;
}
.accordion .accordion-text li{
  list-style: none;
  cursor: pointer;
}
.accordion-text li .question-arrow{
  display: flex;
  align-items: center;
  justify-content: space-between;
}
.accordion-text li .question-arrow .question{
  font-size: 18px;
  font-weight: 500;
  color: #595959;
  transition: all 0.3s ease;
}
.accordion-text li .question-arrow .arrow{
  font-size: 20px;
  color: #595959;
  transition: all 0.3s ease;
}
.accordion-text li.showAnswer .question-arrow .arrow{
  transform: rotate(-180deg);
}
.accordion-text li:hover .question-arrow .question,
.accordion-text li:hover .question-arrow .arrow{
  color: #7d2ae8;
}
.accordion-text li.showAnswer .question-arrow .question,
.accordion-text li.showAnswer .question-arrow .arrow{
  color: #7d2ae8;
}
.accordion-text li .line{
  display: block;
  height: 2px;
  width: 100%;
  margin: 10px 0;
  background: rgba(0, 0, 0, 0.1);
}
.accordion-text li p{
  width: 92%;
  font-size: 15px;
  font-weight: 500;
  color: #595959;
  display: none;
}
.accordion-text li.showAnswer p{
  display: block;
}


@media screen and (max-width: 768px) {
  .accordion {
    flex-direction: column;
    align-items: center;
  }

  .accordion .image-box {
    width: 100%;
    margin-right: 0;
  }

  .accordion .accordion-text {
    width: 100%;
  }

  .accordion .accordion-text .faq-text {
    height: auto; /* Adjust height to auto for smaller screens */
  }
}

</style>
</head>

<body>

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
                    <h2>
                        <?= $_SESSION['username']; ?>'s Dashboard
                    </h2>
                </div>
            </div>

            <!-- Insights -->
            
            <!-- End of Insights -->
            <div class="bottom-data">

                <!-- Text to Text Translation -->
                <div class="orders">
                    <div class="header">
                            <img src="images/translateicon.png" width="120px" height="100px">
                            <h3>Text to Text Translation</h3>      
                            <p class="description">
                                Comminucate effortlessly by translating your content into another language using our technology.
                            </p>
                            <button class="button"><a href="text-text.php" class="logo">START TRANSLATING TEXT
                                <i class="fa fa-arrow-circle-o-right"></i></button></a>
                            
                    </div>
                </div>
                

                <!-- Audio to Text Translation -->
                <div class="orders">
                    <div class="header">
                            <img src="images/languageicon.png" width="120px" height="100px">
                            <h3>Audio to Text Translation</h3>      
                            <p class="description">
                            Upload your audio file to smoothly translate it into another language, breaking the language barrier.
                            </p>
                            <button class="button"><a href="history_audio.php" class="logo">START TRANSCRIBING AUDIO
                                <i class="fa fa-arrow-circle-o-right"></i></button></a>
                    </div>
                    <br>
                </div>

                
                <div class="orders">
                  <div class="header">
                    <span style="font-size: 1.3em; text-align:center;"><?php echo $total_message_array[0]; ?></span>
                  </div>
                </div>
                <div class="orders">
                  <div class="header">
                    <span style="font-size: 1.3em; text-align:center;"><?php echo $total_message_array[1]; ?></span>
                  </div>
                </div>
            </div>

<div class="accordion">
    <div class="image-box">
      <img src="images/faqtranslator.jpg" alt="translator robot" >
    </div>
    <div class="accordion-text">
      <div class="title">FAQS</div>
    <ul class="faq-text">
    <?php if (!empty($faq_question) && !empty($faq_answer)) { 
            for ($i = 0; $i < sizeof($faq_question); $i++) {     ?>
              <li>
                <div class="question-arrow">
                  <span class="question"><?= $faq_question[$i]; ?></span>
                  <i class="bx bxs-chevron-down arrow"></i>
                </div>
                <p><?= $faq_answer[$i]; ?></p>
                <span class="line"></span>
              </li>
    <?php    }
        }     ?>  
    </ul>
    
    </div>
  </div>
  <br>
        </main>

    </div>

    <script>
    let li = document.querySelectorAll(".faq-text li");
    for (var i = 0; i < li.length; i++) {
      li[i].addEventListener("click", (e)=>{
        let clickedLi;
        if(e.target.classList.contains("question-arrow")){
          clickedLi = e.target.parentElement;
        }else{
          clickedLi = e.target.parentElement.parentElement;
        }
       clickedLi.classList.toggle("showAnswer");
      });
    }
  </script>
    <script src="scripts/index.js"></script>
</body>

</html>
