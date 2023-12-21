
<?php require("mysql/mysqli_session.php");
    $current_page = basename($_SERVER['PHP_SELF']);
    if (!isset($_SESSION['username'])) {
        header("location: loginpage.php");
        exit();
    }

    // consists of all the questions and answers in faqs
    $faq_question = array();
    $faq_answer = array();

    array_push($faq_question, "What does Voce Do?");
    array_push($faq_answer, "Voce allows your text inputs to be translated in another language in the Text to Text section. Also, our main feature
                            allows you to upload your audio file, and it will be translated into another language!");

    array_push($faq_question, "How do I translate my audio?");
    array_push($faq_answer, "Go to Audio to Text, then select a model, the language of your audio in source language,
                            and the language you want it to be translated in target language. Upload your audio file, and click translate. 
                            Then, wait for the system to process your request.");

    array_push($faq_question, "Can Voce remove backgound music in my audio?");
    array_push($faq_answer, "Yes! Simply click the checkbox under the upload file to remove the background music. Please take note that 
                            the background music will not be removed if you did not click the checkbox; thus, the system might not
                            be able to handle the audio well.");
    
    array_push($faq_question, "Why is my translation not accurate?");
    array_push($faq_answer, "The accuracy of the translation depends on the \"model size\" of your choice.
                            \nChoose a lighter model, like 'Base' or 'Small' to have a quicker translation but it can be less accurate. 
                            \nChoose a heavier model, like 'Medium' or 'Large' to have a more accurate translation, but it would take more time than lighter models.");

    array_push($faq_question, "Are my translations saved?");
    array_push($faq_answer, "Yes! All of your translations are saved and you can go back and see them again! 
                            In Text to Text section or Audio to Text section, scroll down to see the table that contains your translations from recent to the oldest");

    $faqs
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
                
                <!-- Reminders -->
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
