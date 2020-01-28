<?php
$conn = new mysqli('localhost', 'root','', 'test2');
 
if ($conn->connect_errno) {
    echo "Error: " . $conn->connect_error;
}
?>