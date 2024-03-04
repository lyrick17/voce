<?php 
require("../mysql/mysqli_session.php"); 

if (isset($_SESSION['recent_audio'])):

    $audioid = $_SESSION['recent_audio']; 
    $data = mysqli_query($dbcon, "SELECT * FROM text_translations WHERE text_id = '$audioid' AND from_audio_file = 1 ORDER BY translation_date DESC LIMIT 1")->fetch_row();

    $date = $data[8];
    // Use DateTime to parse and format the date and time in various ways
    $date = new DateTime($data[8]);
    
    // Format the date and time
    $formattedDateTime = str_replace(['-', ' ', ':'], '', $data[8]);

    
    // USER WANTS TO DOWNLOAD IN WORD OR PDF FILE
    if (isset($_POST['word'])):
        // filename for microsoft word
        
        // filename for microsoft word
        $filename = "audiotranslation_" . $formattedDateTime . ".doc";
        
        header("Content-type: application/vnd.ms-word");
        header("Content-Disposition: attachment;  Filename=$filename");
?>
        <html>
        <body>
            <h1>Voce Audio to Text Translation</h1>
            <hr>
            <h2>Original Text</h2>
            <h3>Language: <?php echo $data[4] ?></h3>
            <p><?php echo $data[6] ?></p>
            <hr>
            <h2>Translated Text</h2>
            <h3>Language: <?php echo $data[5] ?></h3>
            <p><?php echo $data[7] ?></p>
            <hr>
            <span><i>from Voce<i></span>
        </body>
        </html>

<?php
    // USER WANTS TO DOWNLOAD IN TEXT FILE
    elseif (isset($_POST['text'])):
        // filename for text file
        $filename = "audiotranslation_" . $formattedDateTime . ".txt";
        
        header("Content-type: text/plain");
        header("Content-Disposition: attachment;  Filename=$filename");

        echo "Voce Audio to Text Translation\n"
            . "---\n"
            . "Original Text\n"
            . "Language: " . $data[4] . "\n"
            . "Text: " . $data[6] . "\n"
            . "---\n"
            . "Translated Text\n"
            . "Language: " . $data[5] . "\n"
            . "Text: " . $data[7] . "\n"
            . "---\n"
            . "from Voce\n";

    endif;
endif;
?>