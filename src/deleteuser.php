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
//waarde is username. als geen username, exit
$delete_username = $_POST['username'] ?? null;
if (!$delete_username) {
    echo "Geen gebruiker gespecificeerd.";
    exit();
}

if ($delete_username){
    $servername = "mysql";
    $username = "root";
    $password = "password";

    $conn = new mysqli($servername, $username, $password, "Newmydb");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    //delete de delete_username van users
    $stmt = $conn->prepare("DELETE FROM users WHERE username = ?");
    $stmt->bind_param("s", $delete_username);

    if ($stmt->execute()) {
        header("Location: userlijst.php");
        exit();
    } else {
        echo "Fout bij verwijderen van gebruiker.";
    }

    $stmt->close();
    $conn->close();
}
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>




<a href="userlijst.php" class="button">back</a>
</body>
</html>