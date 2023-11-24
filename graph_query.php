<?php
 require("mysql/mysqli_connect.php"); 


//total number of a2t/t2t translations. from current to past 6 days

$total_query = "SELECT COUNT(*) AS total_a2t FROM text_translations WHERE  from_audio_file = 0;";
$total_query .= "SELECT COUNT(*) AS total_t2t FROM  text_translations WHERE  from_audio_file = 1";

mysqli_multi_query($dbcon, $total_query);

//[# of a2t translations, # of t2t translations]
$pie_values = [0, 0];
$j = 0;

do {
    /* store the result set in PHP */
    if ($result = mysqli_store_result($dbcon)) {
        while ($row = mysqli_fetch_row($result)) {

            $pie_values[$j] = $row[0];
            $j++;
        }
    }
    /* print divider */
} while (mysqli_next_result($dbcon));

//Line graph values
$a2t_per_day = [0, 0 ,0 ,0 ,0 ,0 ,0];
$t2t_per_day = [0, 0 ,0 ,0 ,0 ,0 ,0];

for($i = 0; $i < 7; $i++){

    //Gets total a2t today and for the last 6 days
    $a2t_query = "SELECT COUNT(*) AS a2t_total 
    FROM text_translations 
    WHERE DATE(translation_date) = 
    DATE(DATE_SUB(NOW(), INTERVAL $i DAY)) AND from_audio_file = 1";

    //Gets total t2t today and for the last 6 days
    $t2t_query = "SELECT COUNT(*) AS t2t_total 
    FROM text_translations 
    WHERE DATE(translation_date) = 
    DATE(DATE_SUB(NOW(), INTERVAL $i DAY)) AND from_audio_file = 0";

    //query result
    $a2t_line_result = mysqli_query($dbcon, $a2t_query);
    $t2t_line_result = mysqli_query($dbcon, $t2t_query);

    //get associative array for each result
    $a2t_assoc = mysqli_fetch_assoc($a2t_line_result);
    $t2t_assoc = mysqli_fetch_assoc($t2t_line_result);

    //store total a2t and t2t in respective array
    $a2t_per_day[$i] = $a2t_assoc['a2t_total'];
    $t2t_per_day[$i] = $t2t_assoc['t2t_total'];
}



$graph_data = [
    'line_values' => [
        'a2t_totals' => $a2t_per_day, 
        't2t_totals' => $t2t_per_day],
    'pie_values' => $pie_values
];

exit(json_encode($graph_data));

// exit(json_encode($result));