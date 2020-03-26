<?php
require_once('resources/php/connect.php');
session_start();
$message = "";
$type = "";
$p = "";
$u = "";
$a = 0;
$currentid = "";
if(isset($_SESSION['id'])){
    if($_SESSION['id'] == 0){
        echo "<script>window.location.href = '/403error';</script>";
    }
}

if(isset($_POST['password']) and $_POST['password'] != ""){
    $p = $_POST['password'];
}else{
    $message = "";
}
if(isset($_POST['username']) and $_POST['username'] != ""){
    $u = $_POST['username'];
}else{
    $message = "";
}
if(isset($_POST['option'])){
    $a = $_POST['option'];
}else{
    $message = "";
}
if(isset($_POST['type'])){
    $type = $_POST['type'];
}else{
    $message = "";
}
//
    //Add User
if($type == "newUser"){
        if(isset($_POST['admin'])){
            $a = (int)$_POST['admin'];
        }else{
            $a = 0;
        }
        try{
        $stmt = $conn->prepare("INSERT INTO users SET username = :u, password = :p, admin = :a ;");
        $stmt->bindParam(':a', $a, PDO::PARAM_INT);
        $stmt->bindParam(':u', $u, PDO::PARAM_STR);
        $stmt->bindParam(':p', $p, PDO::PARAM_STR);
        $stmt->execute();
        $changed = $stmt->rowCount();
        if($changed != 0){
            $message = $changed." user was added successfully";
        } else {
            $message = "ERROR: No users were added, please check your input and try again";
        }
    }catch(Exception $e){
        if($e->getCode() == 23000){
            $message = "ERROR: A user with this username already exists";
        }else{
            $message = "ERROR: Please check your input and try again";
        }
    }
} else if($type == "deleteUser"){
    //Delete User
    //dont allow adming to delete themselves if they are the last admin remaning
    $count = $conn->query("SELECT count(1) FROM users WHERE admin = 1")->fetchColumn(); //count of admins remaming
    $sql =  $conn->prepare("SELECT admin FROM users WHERE username = ?");
    $sql->execute([$u]);
    $admin = $sql->fetchColumn(); //the user being changed is an admin
    
    try{
        if($admin == 0 or $count > 1){ //if not an admin or more than 1 admin remains
            $stmt = $conn->prepare("DELETE FROM users WHERE username = ?");
            $stmt->execute([$u]);
            $changed = $stmt->rowCount();
            $stmt = $conn->prepare("DELETE FROM udg WHERE owner = ?");
            $stmt->execute([$u]);
            $changed += $stmt->rowCount();
            $stmt = $conn->prepare("DELETE FROM BasicUDG WHERE owner = ?");
            $stmt->execute([$u]);
            $changed += $stmt->rowCount();
            $stmt = $conn->prepare("DELETE FROM Files WHERE Owner = ?");
            $stmt->execute([$u]);
            $changed += $stmt->rowCount();
            if($changed != 0){
                $message = $changed." lines of data were deleted successfully";
            } else {
                $message = "ERROR: No users were found with this username";
            }
        }else{
            $message = "ERROR: Cannot remove this admin because it is the only admin remaining";
        }
    }catch(Exception $e){
        $message = "ERROR: Please check your input and try again";
    }
    
}else if($type == "changePassword"){
    //Change Password
    try{
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
            $stmt->execute([$p,$u]);
            $changed = $stmt->rowCount();
            if($changed != 0){
                $message = $changed." users were changed successfully";
            } else {
                $message = "ERROR: No users were found with this username";
            }

    }catch(Exception $e){
        $message = "ERROR: Please check your input and try again";
    }
}else if($type == "changeAdmin"){
    $count = $conn->query("SELECT count(1) FROM users WHERE admin = 1")->fetchColumn(); //count of admins remaming
    $sql =  $conn->prepare("SELECT admin FROM users WHERE username = ?");
    $sql->execute([$u]);
    $admin = $sql->fetchColumn(); //the user being changed is an admin
    
    //dont allow all the admins to be removed
    try{
        if($admin == 0 or $count > 1){ //if not an admin or more than 1 admin remains
            $stmt = $conn->prepare("UPDATE users SET admin = ? WHERE username = ?");
            $stmt->execute([$a,$u]);
            $changed = $stmt->rowCount();
            if($changed != 0){
                $message = $changed." users were changed successfully";
            } else {
                $message = "ERROR: No users were found with this username";
            }
        }else{
            $message = "ERROR: Cannot remove admin from this user because it is the only admin remaining";
        }
        
    }catch(Exception $e){
        $message = "ERROR: Please check your input and try again";
    }
}
?>
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
 
		 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <title> 
            SMUDGES: Systematically Mapping Ultra-Diffuse Galaxies
        </title>
        
        <style>
        
            .header { 
                background-image: url('UDG.jpg');
                background-color: teal; 
                padding: 10px; 
                text-align: center; 
            }
            /* CSS property for nevigation menu */ 
            .nav_menu { 
                overflow: hidden; 
                background-color: #333; 
            } 
            .nav_menu a { 
                float: right; 
                display: block; 
                color: white; 
                text-align: center; 
                padding: 14px 16px; 
                text-decoration: none;
            } 
            .nav_menu a:hover { 
                background-color: white; 
                color: teal; 
            }
	  .button {
	  background-color: #4CAF50;
	  border: none;
	  color: white;
	  padding: 15px 25px;
	  text-align: center;
	  font-size: 16px;
	  cursor: pointer;
	  }

	  .button:hover {
	      background-color: green;
	  }
        .column {
            float: left;
            width: 48%;
            padding-left:10%;
        }

/* Clear floats after the columns */
.row:after {
  content: "";
  display: table;
  clear: both;
}

@media screen and (max-width: 600px) {
  .column {
    width: 100%;
  }
}
    .message{
        <?php
        if($message == ""){
        }else if(strpos($message, 'ERROR') !== false) {
            echo 'background-color: tomato;text-align: center;color:white';
        }else{
            echo 'background-color: deepskyblue;text-align: center;color:black';
        }
        ?>
    }

.button:hover {
  background-color: green;
}             
        </style>
    </head>
    <body>
        <div class="header"> 
            <h2 style="color:white;font-size:200%">SMUDGES: Admin Portal</h2> 
        </div> 
        <div class="nav_menu"> 
            <a href="/adminPortal">Login/Logout</a>
            <a href="/uploadData">Upload Data</a>   
            <a href="/viewData">View Data</a>             
            <a href="/adminPortal">Admin Portal</a> 
            <a href="/">Homepage</a> 
        </div> 
	<!--
	<button class="button">Create new Collaborator</button>
	<button class="button">Remove Collaborator</button>
	<button class="button">Change User Password</button>
	-->
	
<div class=message>
    <p>
    <?php
    if($message != ""){
    echo $message;
    }
    ?>
    </p>
</div>
<div class="row">
    <div class="column">
	<form action="" method="POST">
	<h2>Create new Collaborator:</h2>
	  New Username:<br>
	  <input type="text" name="username" value="">
	  <br>
	  New Password:<br>
	  <input type="text" name="password" value="">
	  <br>
	  <input type="hidden" id="type" name="type" value="newUser">
	  <input type="checkbox" id="admin" name="admin" value="1">
	  <label for="admin"> Give new user admin privilages</label><br>
	  <input type="submit" value="Submit">
	</form>
	<br><br>
	<h2>Change User Admin Privilages:</h2>
	<form action="" method="POST">
	  Existing Username:<br>
	  <input type="text" name="username" value="">
	  <br>
	  Choose One:<br>
	  <select id="option" name = "option">
        <option value="0">Remove Admin Privileges</option>
        <option value="1">Give Admin Privileges</option>
        </select>
        <br>
	  <input type="hidden" id="type" name="type" value="changeAdmin"><br>
	  <input type="submit" value="Submit">
	</form>
    </div>
    <div class="column">
    <h2>Remove Collaborator:</h2>
	<form action="" method="POST">
	  <p style = "color:red;">This Cannnot be undone and will delete all data associated with this user</p>
	  Existing Username:<br>
	  <input type="text" name="username" value="">
	  <br>
	  <br>
	  <input type="submit" value="Submit">
	  <input type="hidden" id="type" name="type" value="removeUser">
	</form>
	<br><br>
	<h2>Change User Password:</h2>
	<form action="" method="POST">
	  Existing Username:<br>
	  <input type="text" name="username" value="">
	  <br>
	  New Password:<br>
	  <input type="text" name="password" value="">
	  <br><br>
	   <input type="hidden" name="type" value="ChangePassword">
	  <input type="submit" value="Submit">
	  <input type="hidden" id="type" name="type" value="changePassword">
	</form>
	
	</div>
</div>

</body></html>