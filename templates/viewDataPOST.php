<?php
require_once('config.php');
$searchQuery  = "SELECT id, first_name, last_name, age FROM users LIMIT 400" ;  //limiting helps with that memory overflow error in my original question
$searchResult = mysqli_query($conn, $searchQuery);

while ($row = mysqli_fetch_row($searchResult)) {

    $item = array();
    
        $item["first name"] = $row[1];
        $item["last name"] = $row[2];
        $item["age"] = $row[3];
    
    
        $output[] = $item;
    
        }
    
$out = array('aaData' => $output);
echo json_encode($out);