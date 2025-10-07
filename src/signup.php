<?php
session_start();

if(isset($_POST["name"])){
$name = $_POST["name"];
$pass = $_POST["password"];
$_SESSION["name"] = $name;
$_SESSION["password"] = $pass;

}else if(isset($_SESSION["name"])){
$name = $_SESSION["name"];
$pass = $_SESSION["password"];
}else{
  echo "nope";
  exit();
}
 ?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styler.css">
</head>
<body>

    <?php 
 

        $servername = "mysql";
    $username = "root";
    $password = "password";

     $conn = new mysqli($servername, $username, $password, "Newmydb");
        if ($conn->connect_error) {
  die(" Connection failed: " . $conn->connect_error);}
  
$stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
$stmt->bind_param("s", $name); 
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashed_password = $row["password"];
}

if (password_verify($pass, $hashed_password)){
if ($name === 'adminaccount'){
  echo "currect account: " . $name;
} else {
        echo "Page for admin use only.";
        exit();
    }
} else {
    echo "Password Incorrect";
    exit();
}

    ?>
<h1 class="titletext">  
  <span style="--i:1">create new account</span>

</h1>
<div class="textbrick">
<form action="aftersignup.php" method="post">
Name: <input type="text" name="namenewacc" minlength="3" maxlength="15"><br>
Password: <input type="password" name="passwordnewacc" minlength="5" maxlength="15"><br>
<input id="verzenden" type="submit" value="submit">

        </div>
</body>
</html>