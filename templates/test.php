<?php
require_once('config.php');
 
$sql = "SELECT id, first_name, last_name, age FROM users";
$result = $conn->query($sql);
$arr_users = [];
if ($result->num_rows > 0) {
    $arr_users = $result->fetch_all(MYSQLI_ASSOC);
}
?>