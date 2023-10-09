<?php

// Use to inspect contents of json / array objects.
function dd($value){
	var_dump($value);
	die();
}

$lang_codes = [];

// Get request for language names and codes, please check https://rapidapi.com/dickyagustin/api/text-translator2 for more information.
$curl = curl_init();

curl_setopt_array($curl, [
	CURLOPT_URL => "https://text-translator2.p.rapidapi.com/getLanguages",
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	//CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "GET",
	CURLOPT_HTTPHEADER => [
		"X-RapidAPI-Host: text-translator2.p.rapidapi.com",
		"X-RapidAPI-Key: 1404802bd3msh016a1d77bd4d159p13ca69jsnfcb6fc6df689"
	],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
	echo "cURL Error #:" . $err;
} else {
	$languages = json_decode($response, true)["data"]["languages"];
	foreach($languages as $language){
		$lang_codes[$language["name"]] = $language["code"];
	}
}


// Language Translation, please check https://rapidapi.com/dickyagustin/api/text-translator2 for more information.
if($_SERVER["REQUEST_METHOD"] == "POST"){
	$curl = curl_init();

	$src_lang =  $lang_codes[$_POST["src"]] ?? '';
	$trg_lang = $lang_codes[$_POST["target"]] ?? '';
	$transcript = $_POST["text"];
	curl_setopt_array($curl, [
		CURLOPT_URL => "https://text-translator2.p.rapidapi.com/translate",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		//CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_POST => true,
		CURLOPT_POSTFIELDS => "source_language=".$src_lang."&target_language=".$trg_lang."&text=".$transcript,
		CURLOPT_HTTPHEADER => [
			"X-RapidAPI-Host: text-translator2.p.rapidapi.com",
			"X-RapidAPI-Key: 1404802bd3msh016a1d77bd4d159p13ca69jsnfcb6fc6df689",
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
		$result = $decoded["data"]["translatedText"];

	}
}

?>

<body>

<div class="col-md-4">
        <div class="dashboard-rectangle1" style="background-color: #D2ACA4;">
        <center><h3 class="text-dark">Text to Translate <i class="fas fa-download"></i></h3></center>
          <p><form action = "dashboard.php" method = "POST" >
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
	<center>
	<p class="text-dark" style="font-family: Times New Roman, Times, serif; font-size: 150%;" >Original: <?= $_POST['text']?? ''?></p>
	<p class="text-dark" style="font-family: Times New Roman, Times, serif; font-size: 150%;">Translated: <?= $result ?? ''?> </p>
	</center>
        </div>
      </div>

	
</body>
</html>