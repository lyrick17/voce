<?php
 require("mysql/mysqli_connect.php"); 

$deleteQuery = "DELETE FROM text_translations WHERE text_id = " . $_POST['rowId'];
mysqli_query($dbcon, $deleteQuery);
$q = "SELECT * FROM text_translations WHERE user_id = ". $_POST['userId'] ." AND from_audio_file = 0 ORDER BY translation_date DESC";
$sql = mysqli_query($dbcon, $q);
$result = mysqli_fetch_all($sql, MYSQLI_ASSOC);
exit(json_encode($result));
