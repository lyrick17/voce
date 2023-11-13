<?php 

class Translator{


    //IMPORTANT! $history should contain the query result 
    // translation format should either be text2text
    static function displayHistory($history, $translation_format){
        // Displays text to text history  or audio2text
        if($translation_format == "text2text"){
            while($row = mysqli_fetch_assoc($history)){
                echo               
                "<tr id = ". $row['text_id'] ." class = '". $row['user_id']. " " . $row['from_audio_file'] . "'>" .
                "<td class = " .$row['user_id']. ">" .$row['translate_from'] . "</td>" . 
                "<td class = " .$row['user_id']. ">" .$row['original_language'] . "</td>" .
                "<td class = " .$row['user_id']. ">" .$row['translate_to'] . "</td>" .
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
                "<tr id = ". $row['text_id'] ." class = '". $row['user_id']. " " . $row['from_audio_file'] . "'>" .
                "<td class = " .$row['user_id']. ">" .$row['file_name'] . "</td>" . 
                "<td class = " .$row['user_id']. ">" .$row['file_format'] . "</td>" .
                "<td class = " .$row['user_id']. ">" .$row['file_size'] . "</td>" .
                "<td class = " .$row['user_id']. ">" .$row['translate_from'] . "</td>" . 
                "<td class = " .$row['user_id']. ">" .$row['original_language'] . "</td>" . 
                "<td class = " .$row['user_id']. ">" .$row['translate_to'] . "</td>" .
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
                "X-RapidAPI-Key: d5185f2565msh3cdba754dc69affp10ba69jsn87d2b93e11ba"
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

    static function uploadAndTranscribe($path){
        $pathto="audio_files/".$path;
        move_uploaded_file( $_FILES['user_file']['tmp_name'],$pathto) or die( "Could not copy file!");
        return shell_exec("python scripts/translate.py " . $_FILES["user_file"]['full_path']);
    }

    static function translate($input = '', $src = '', $target = '', $mode = "text"){
        $curl = curl_init();

        if($mode === "text"){
            $transcript = $input;
        }

        else{
            $path= $input;
            $transcript = uploadAndTranscribe($path);

            /* 
                $path=$_FILES['user_file']['name'];
                $transcript = uploadAndTranscribe($path);
            */
        } 
    
        
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
                "X-RapidAPI-Key: d5185f2565msh3cdba754dc69affp10ba69jsn87d2b93e11ba",
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