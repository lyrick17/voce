<?php

require "Translator_Functions.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    echo Translator::get_meanings($_POST['word']);
}
