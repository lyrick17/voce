<?php 
require "error_handling.php";
class Translator{

    static function db_insertAudioFile($path, $userid,  $pathsize) {
        global $dbcon;
        // prepare userid, filename, filesize, fileformat
        $file_name = $path;
        $file_size = round($pathsize / 1000000, 2);
            //$file_size = round(filesize('audio_files/' . $file_name)/1000000, 2);
        $file_format =  pathinfo('audio_files/' . $file_name, PATHINFO_EXTENSION);
        
        // insert audio file into database
          $query_insert2 = mysqli_prepare($dbcon, "INSERT INTO audio_files(user_id, file_name, file_size, file_format,
          upload_date) VALUES (?, ?, ?, ?, NOW())");
    
        // execute the query
          mysqli_stmt_bind_param($query_insert2, 'isss', $userid, $file_name, $file_size, $file_format);
          mysqli_stmt_execute($query_insert2);
    }
    
    

    
    static function uploadAndTranscribe($path, $userid, $removeBGM){
        
        global $dbcon;      
            // get the name of file and extension separately
            $filename = pathinfo($path, PATHINFO_FILENAME);
            $extension = pathinfo($path, PATHINFO_EXTENSION);
    
            // get the date of the file
            $datequery = "SELECT DATE_FORMAT(upload_date, '%m%d%Y_%H%i%s') AS formatted_date 
                            FROM audio_files WHERE user_id = '$userid' and file_name = '$path' ORDER BY file_id DESC LIMIT 1";
            $dateresult = mysqli_query($dbcon, $datequery);
            $row = mysqli_fetch_assoc($dateresult);
    
        // 5. 
        $newFilename = $userid . "_" . $filename . $row['formatted_date'];
        $newFile = $newFilename . "." . $extension;
        
        // audio files folder
        $pathto="audio_files/" . $newFile;
    
    
        // 6.
        move_uploaded_file( $_FILES['user_file']['tmp_name'],$pathto) or die(ErrorHandling::audioError2());
        
        // 7.

        # Extract vocals if checkbox is checked
        if ($removeBGM == "on") {
            self::getVocals($newFile);
        }
        
            
                /* make sure to go to php.ini in xampp (config > php.ini) 
                *  and set max_execution_time into 600 [10 minutes] or higher (write in seconds), for longer processing
                *  you only need to pass the name of file as argument for translation (file extension not needed)
                */
    
        // 8.
        $output = shell_exec("python scripts\\translate.py " . escapeshellarg($newFilename) . " " . escapeshellarg($removeBGM) . " " . escapeshellarg($extension));
        if ($output)
            return $output;
        else
            ErrorHandling::audioError3();
        
    }

    static function getVocals($file) {
        # Activate the virtual environment
        # use spleeter for extracting vocals,
        #   and pass the file as argument 
        # then, deactivate virtual environment
        
        #   code for Python 3.8 system
        # $output = shell_exec("python scripts/separate.py " . escapeshellarg($file) . " && deactivate");
        
        #   code for Python 3.11 system with py3.8 spleeter_env virtual env
        $output = shell_exec("spleeter_env\\Scripts\\activate && python scripts/separate.py " . escapeshellarg($file) . " && deactivate");
       
    }

    
    //IMPORTANT! $history should contain the query result 
    // translation format should either be text2text
    static function displayHistory($history, $translation_format){
        // Displays text to text history  or audio2text
        if($translation_format == "text2text"){
            while($row = mysqli_fetch_assoc($history)){
                echo               
                "<tr id = ". $row['text_id'] ." class = '". $row['user_id']. " " . "t2t" . "'>" .
                "<td class = '" .$row['user_id']. " truncate-text'>" .$row['translate_from'] . "</td>" . 

                "<td class = " .$row['user_id']. ">" .$row['original_language'] . "</td>" .
                "<td class = '" .$row['user_id']. " truncate-text'>" .$row['translate_to'] . "</td>" .
                "<td class = " .$row['user_id']. ">" .$row['translated_language'] . "</td>" . 
                "<td class = " .$row['user_id']. ">" .$row['translation_date'] . "</td>" .  
                "<td class = " .$row['user_id']. ">"."<button type = 'button' class = 'delete-btn'>Delete</button></td>"   
                . "</tr>";
            }
        }
        // Displays audio to text history
        elseif($translation_format == "audio2text"){
            while($row = mysqli_fetch_assoc($history)){
                echo               
                "<tr id = ". $row['text_id'] ." class = '". $row['user_id']. " " . "a2t". " " . $row['file_id'] . "'>" .

                "<td class = " .$row['user_id']. ">" .$row['file_name'] . "</td>" . 
                "<td class = " .$row['user_id']. ">" .$row['file_format'] . "</td>" .
                "<td class = " .$row['user_id']. ">" .$row['file_size'] . "</td>" .
                "<td class = '" .$row['user_id']. " truncate-text'>" .$row['translate_from'] . "</td>" . 
                "<td class = " .$row['user_id']. ">" .$row['original_language'] . "</td>" . 
                "<td class = '" .$row['user_id']. " truncate-text'>" .$row['translate_to'] . "</td>" .
                "<td class = " .$row['user_id']. ">" .$row['translated_language'] . "</td>" .
                "<td class = " .$row['user_id']. ">" .$row['translation_date'] . "</td>" . 
                "<td class = " .$row['user_id']. ">" . "<button type = 'button' class = 'delete-btn'>Delete</button></td>"   
                . "</tr>";

            }
        }
    }

    static function getLangCodes(){
        $lang_codes = [];

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://text-translator2.p.rapidapi.com/getLanguages",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "X-RapidAPI-Host: text-translator2.p.rapidapi.com",
                "X-RapidAPI-Key: dd79fde36amsh4a5e9db6ec28ec6p1577f9jsn41a1205a8919"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            return json_decode($response, true)['data']['languages'];

        }
    }


    static function translate($input = '', $src = '', $target = '', $mode = "text"){
        $curl = curl_init();

        $transcript = $input;
        

  
    
        $src_lang =  $src;
        $trg_lang =  $target;
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://text-translator2.p.rapidapi.com/translate",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => "source_language=".$src_lang."&target_language=".$trg_lang."&text=".$transcript,
            CURLOPT_HTTPHEADER => [
                "X-RapidAPI-Host: text-translator2.p.rapidapi.com",
                "X-RapidAPI-Key: dd79fde36amsh4a5e9db6ec28ec6p1577f9jsn41a1205a8919",
                "content-type: application/x-www-form-urlencoded"
            ],
        ]);
    
        $response = curl_exec($curl);
        $err = curl_error($curl);
    
        curl_close($curl);
    
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {

            $decoded = json_decode($response, true);
            return $decoded["data"]["translatedText"];
        }
    }
}