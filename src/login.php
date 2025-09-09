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
if(isset($_SESSION["name"])){
    session_destroy();
}
        $servername = "mysql";
    $username = "root";
    $password = "password";

     $conn = new mysqli($servername, $username, $password, "mydb");
        if ($conn->connect_error) {
  die(" Connection failed: " . $conn->connect_error);}
    
    ?>
<h1 class="titletext">  
  <span style="--i:1">l</span>
  <span style="--i:2">o</span>
  <span style="--i:3">g</span>
  <span style="--i:4"> </span>
  <span style="--i:5">i</span>
  <span style="--i:6">n</span>
  <span style="--i:7">:</span></h1>
<form class="textbrick" action="homepage.php" method="post">
Name: <input type="text" name="name" required><br>
Password: <input type="password" name="password" required><br>
<input id="verzenden" type="submit" value="submit">

<a id="entrybutton" href="signup.php" class="button">sign up</a>
</body>
</html>