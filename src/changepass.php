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


        $servername = "mysql";
    $dbUser = "root";
    $password = "password";
    

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Students</title>
  <link rel="stylesheet" href="styler.css">
</head>

<body>
  <a href="homepage.php" class="button">back</a>
  <hr>

<div class="thisdiv">
    <form method="POST" action="">
        <label for="old_password">old password:</label><br>
        <input class="inner-div" type="password" name="old_password" required><br>

        <label for="new_password">new password:</label><br>
        <input class="inner-div" type="password" name="new_password" required><br>

        <button type="submit">change password</button>
    </form>
</div>


</body>
</html>
<?php

  
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $oldPassword = $_POST['old_password'];
    $newPassword = $_POST['new_password'];


     $conn = new mysqli($servername, $dbUser, $password, "Newmydb");
        if ($conn->connect_error) {
  die(" Connection failed: " . $conn->connect_error);}

  //haalt hashed pass op
$stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
$stmt->bind_param("s", $name); 
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashed_password = $row["password"];

//controleert of passwords zelfde zijn
if (password_verify($oldPassword, $hashed_password)){
$newPasswordHashed = password_hash($newPassword, PASSWORD_DEFAULT);


 $updateStmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
 $updateStmt->bind_param("ss", $newPasswordHashed, $name);
 $updateStmt->execute();

 
            echo 'password changed successfully <br>'; 
        } else {
            echo 'old password is incorrect <br>';
        }
    

    $stmt->close();
    $conn->close();
      }
    }
?>

  <a href="login.php" class="button">login again (required after password change)</a>

