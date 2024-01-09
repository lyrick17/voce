<?php 

require("error_handling.php");

class Translator{

    static function db_insertAudioFile($path, $userid,  $pathsize) {
        global $dbcon;
        // prepare userid, filename, filesize, fileformat
        $file_name = $path;
        $file_size = round($pathsize / 1024 / 1024, 2);
            //$file_size = round(filesize('audio_files/' . $file_name)/1000000, 2);
        $file_format =  pathinfo('../audio_files/' . $file_name, PATHINFO_EXTENSION);
        
        // insert audio file into database
          $query_insert2 = mysqli_prepare($dbcon, "INSERT INTO audio_files(user_id, file_name, file_size, file_format,
          upload_date) VALUES (?, ?, ?, ?, NOW())");
    
        // execute the query
          mysqli_stmt_bind_param($query_insert2, 'isss', $userid, $file_name, $file_size, $file_format);
          mysqli_stmt_execute($query_insert2);
    }
    
    
    static function createNewFilename($path, $userid) {
        global $dbcon;
        // create a new filename with format
        $filename = pathinfo($path, PATHINFO_FILENAME);
        $extension = pathinfo($path, PATHINFO_EXTENSION);

        $path = mysqli_real_escape_string($dbcon, $path);
        $userid = mysqli_real_escape_string($dbcon, $userid);
        // get the date of the file from db
        $datequery = "SELECT DATE_FORMAT(upload_date, '%m%d%Y_%H%i%s') AS formatted_date 
                        FROM audio_files WHERE user_id = '$userid' and file_name = '$path' ORDER BY file_id DESC LIMIT 1";
        $dateresult = mysqli_query($dbcon, $datequery);
        $row = mysqli_fetch_assoc($dateresult);

        $newFilename = $userid . "_" . $filename . $row['formatted_date'];
        $newFile = $newFilename . "." . $extension;
        
        // audio files folder
        $pathto="../audio_files/" . $newFile;

        move_uploaded_file( $_FILES['user_file']['tmp_name'],$pathto) or die(ErrorHandling::audioError2());
        
        return $newFile;
    }

    static function createNewFolder($filename) {
        // if audio file will not be processed in extracting vocals
        //  create a folder to place the new file with removed audio

        $filename = pathinfo($filename, PATHINFO_FILENAME);
        $directory = "../audio_files/" . $filename . "/";
        if(!is_dir($directory)){
			mkdir($directory, 0777, true);
		}
    }

    static function removeSilence($inputFile, $removeBGM) {
        // if removeBGM is on, do not make a folder, else, create a new folder
        $filename = pathinfo($inputFile, PATHINFO_FILENAME);

        if ($removeBGM == "on") { // we will use vocals.wav to remove silence instead
            $output = shell_exec("cd .. && ffmpeg -y -i " . escapeshellarg("audio_files/" . $filename . "/vocals.wav") . 
            " -af  silenceremove=stop_periods=-1:stop_duration=1:stop_threshold=-50dB " . escapeshellarg("audio_files/" . $filename . "/audio_processed.mp3"));
        
        } else {                    // we will use the original file since spleeter isn't used
            $output = shell_exec("cd .. && ffmpeg -y -i " . escapeshellarg("audio_files/". $inputFile) . 
            " -af  silenceremove=stop_periods=-1:stop_duration=1:stop_threshold=-50dB " . escapeshellarg("audio_files/" . $filename . "/audio_processed.mp3"));

        }
    }

    static function uploadAndTranscribe($userid, $newFile, $removeBGM, $src_lang, $modelSize){

        global $dbcon;      

        $filename = pathinfo($newFile, PATHINFO_FILENAME);
        $extension = pathinfo($newFile, PATHINFO_EXTENSION);
        
                /* make sure to go to php.ini in xampp (config > php.ini) 
                *  and set max_execution_time into 600 [10 minutes] or higher (write in seconds), for longer processing
                *  you only need to pass the name of file as argument for translation (file extension not needed)
                */
        
        // will receive json containing text and language
        $outputString = shell_exec("cd .. && python scripts/translate.py " . 
                                    escapeshellarg($filename) . " " . 
                                    escapeshellarg($removeBGM) . " " . 
                                    escapeshellarg($extension) . " " .
                                    escapeshellarg($src_lang) . " " .
                                    escapeshellarg($modelSize));


        // replace single quotes around language codes and fields with double quotes
        $outputString = preg_replace('/(?<!\w)\'(.*?)\'/', '"$1"', $outputString);

        //$outputString = preg_replace('/\'(.*?)\'/', '"$1"', $outputString);
        $output = json_decode($outputString, true);
        
        if (!isset($output['text'])) { exit(json_encode(['error' => var_dump($outputString)])); }

        if ($output["text"]) {
            return $output;
            // the array will be returned so both text and language can be accessed
        } else {
            ErrorHandling::audioError3($newFile, $userid); // pass the filename so it can be deleted, since it's not processed
        }
    }




    static function getVocals($file) {
        # Activate the virtual environment
        # use spleeter for extracting vocals,
        #   and pass the file as argument 
        # then, deactivate virtual environment
        
        #   code for Python 3.8 system
        # $output = shell_exec("cd .. && python scripts/separate.py " . escapeshellarg($file) . ");
        
        #   code for Python 3.11 system with py3.8 spleeter_env virtual env
        $output = shell_exec("cd .. && spleeter_env\\Scripts\\activate && python scripts/separate.py " . escapeshellarg($file) . " && deactivate");
       
    }

    
    //IMPORTANT! $history should contain the query result 
    // translation format should either be text2text
    static function displayHistory($history, $translation_format){
        // Displays text to text history  or audio2text
        if($translation_format == "text2text"){
            while($row = mysqli_fetch_assoc($history)){
                echo               
                "<tr id = ". $row['text_id'] ." class = '". $row['user_id']. " " . "t2t" . " paginate" . "'>" .
                "<td class = '" .$row['user_id']. " truncate-text'>" .$row['translate_from'] . "</td>" . 
                "<td class = " .$row['user_id']. ">" .$row['original_language'] . "</td>" .
                "<td class = '" .$row['user_id']. " truncate-text'>" .$row['translate_to'] . "</td>" .
                "<td class = " .$row['user_id']. ">" .$row['translated_language'] . "</td>" . 
                "<td class = " .$row['user_id']. ">" .$row['translation_date'] . "</td>" .  
                "<td class = " .$row['user_id']. ">"."<button type = 'button' class = 'delete-btn'>Delete</button></td>" .  
                "<td class = " .$row['user_id']. ">" . "<input type = 'checkbox' class = 'delete-checkbox' id = ". $row['text_id'] ."></td>"   
                . "</tr>";
            }
        }
        // Displays audio to text history
        elseif($translation_format == "audio2text"){
            while($row = mysqli_fetch_assoc($history)){
                echo               
                "<tr id = ". $row['text_id'] ." class = '". $row['user_id']. " " . "a2t". " " . $row['file_id'] .  " paginate" . "'>" .
                "<td class = '" .$row['user_id']. " break-word'>" .$row['file_name'] . "</td>" . 
                "<td class = '" .$row['user_id']. " truncate-text'>" .$row['translate_from'] . "</td>" . 
                "<td class = " .$row['user_id']. ">" .$row['original_language'] . "</td>" . 
                "<td class = '" .$row['user_id']. " truncate-text'>" .$row['translate_to'] . "</td>" .
                "<td class = " .$row['user_id']. ">" .$row['translated_language'] . "</td>" .
                "<td class = " .$row['user_id']. ">" .$row['translation_date'] . "</td>" . 
                "<td class = " .$row['user_id']. ">" . "<button type = 'button' class = 'delete-btn'>Delete</button></td>" .
                "<td class = " .$row['user_id']. ">" . "<input type = 'checkbox' class = 'delete-checkbox' id = ". $row['text_id'] ."></td>"   
                . "</tr>";

            }
        }
    }

    static function displayUsers($users){
        // Displays text to text history  or audio2text
        while($user = mysqli_fetch_assoc($users)){
            echo   
            "<tr class = 'paginate' id = '". $user['user_id'] . "'>" .            
            "<td class='user-td'>" . $user['user_id']. "</td>" .
            "<td class='user-td' id = 'u-username'>" . $user['username']. "</td>" .
            "<td class='user-td' id = 'u-email'>" . $user['email']. "</td>" .
            "<td class='user-td'>" . $user['registration_date']. "</td>" .
            "<td class='user-td'>" . $user['type'] . "</td>" .
            "<td class='user-td'><button type = 'button' class = 'table-btn update-user'>Update</button></td>" .
            "<td class='user-td'><button type = 'button' class = 'table-btn delete-user'>Delete</button></td>" .
            "<td style = 'display: none;' class = 'user-td " .$user['user_id']. "'>" . "<input type = 'checkbox' class = 'delete-checkbox' id = ". $user['user_id'] ."></td>" .   
            "</tr>";
        }
    }
    
    static function getLangCodes(){

        // create a cache file to store the languages from api instead of
        //  calling the api over and over again, wasting api calls
        ErrorHandling::checkCacheFolder();
        $cache_file = "cache/lang-codes-cache.json";
        $expiration = time() - ((60 * 60) * 12); // cache file will be expired in twelve hours 
        $lang_codes = [];
        
        if (!file_exists($cache_file) || fileatime($cache_file) < $expiration || file_get_contents($cache_file) == '') {
            // there is no cached file or file expires or file no content
            
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
                    "X-RapidAPI-Key: 5a4a854aecmsh5aefb5b52f1c29ap189bdfjsnebc4acefe413"
                ],
            ]);

            $response = curl_exec($curl);
            $err = curl_error($curl);
            
            curl_close($curl);
            
            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                $lang_codes = json_decode($response, true)['data']['languages'];
                file_put_contents($cache_file,  json_encode($lang_codes));
            }
        } else {
            // there is cached file
            $lang_codes = json_decode(file_get_contents($cache_file), true);
        }
        return $lang_codes;
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
                "X-RapidAPI-Key: 5a4a854aecmsh5aefb5b52f1c29ap189bdfjsnebc4acefe413",
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
?>