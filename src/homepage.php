<?php 

session_start();

//als verzonden, naam en pass er uit halen.
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
  <title>Students</title>
  <link rel="stylesheet" href="styler.css">
</head>

<body>
  <a href="login.php" class="button">back</a>
  <hr>
<?php


        $servername = "mysql";
    $username = "root";
    $password = "password";
 $conn = new mysqli($servername, $username, $password, "zingusDB");
        if ($conn->connect_error) {
  die(" Connection failed: " . $conn->connect_error);}

  //haalt hashed password op
$stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
$stmt->bind_param("s", $name); 
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashed_password = $row["password"];


//controleert of ingevoerde pass zelfde is als pass uit database
if (password_verify($pass, $hashed_password)){
echo "Welcome, " . $name;
} else {
  echo "username or password incorrect";
  exit();
}

} else {
    echo "username or password incorrect";
    exit();
}
?>

<p>homepage!<p>
  <a href="userlijst.php" class="button">account list</a>
<a href="login.php" class="button">log out</a>
<a href="changepass.php" class="button">change password</a>

</body>
</html>

</body>

</html>