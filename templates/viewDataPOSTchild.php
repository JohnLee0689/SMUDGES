<?php
include 'ChromePhp.php';
require_once('config.php');

$searchQuery  = "SELECT id, first_name, last_name, age FROM users WHERE first_name = '" . $_POST['value'] . "' LIMIT 400" ;  //limiting helps with that memory overflow error in my original question

$searchResult = mysqli_query($conn, $searchQuery);

while ($row = mysqli_fetch_row($searchResult)) {

    $item = array();
    
       $item["first_name"] = $row[1];
        $item["last_name"] = $row[2];
        $item["age"] = $row[3];
    
    
        $output[] = $item;
    
        }
    
$out = array('aaData' => $output);
//ChromePhp::log(json_encode($out));
echo json_encode($out);