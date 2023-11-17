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