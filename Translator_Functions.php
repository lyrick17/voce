<?php 

class Translator{
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
                "X-RapidAPI-Key: 5a4a854aecmsh5aefb5b52f1c29ap189bdfjsnebc4acefe413"
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
        return shell_exec("C:\Users\User\AppData\Local\Programs\Python\Python311\python.exe translate.py " . $_FILES["user_file"]['full_path']);
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