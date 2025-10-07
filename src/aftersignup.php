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

        $servername = "mysql";
    $username = "root";
    $password = "password";
 $conn = new mysqli($servername, $username, $password, "Newmydb");
        if ($conn->connect_error) {
  die(" Connection failed: " . $conn->connect_error);}

//als verzonden, naam en pass er uit halen.
if(isset($_POST["namenewacc"])){
$namenewacc = $_POST["namenewacc"];
$passhashnewacc = password_hash($_POST["passwordnewacc"], PASSWORD_DEFAULT);


}else{
  echo "nope";
  exit();
} ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Students</title>
  <link rel="stylesheet" href="styler.css">
</head>

<body>
  <a href="signup.php" class="button">back</a>
  <hr>
<?php

//checkt of naam al bestaat
    $check_stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $check_stmt->bind_param("s", $namenewacc);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        echo "The name " . htmlspecialchars($namenewacc) . " is already taken. Please try again.";
        exit();
    }
    $check_stmt->close();

//bereidt query voor account toevoeging
$insert_stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
$insert_stmt->bind_param("ss", $namenewacc, $passhashnewacc);
    if ($insert_stmt->execute()) {
        echo htmlspecialchars($namenewacc) . " has been created.";
    } else {
        echo "Fout bij registratie: " . $insert_stmt->error;
    }
    $insert_stmt->close();
?>
<br>
<a href="login.php" class="button">log in</a>
<a href="homepage.php" class="button">homepagina</a>
</body>
</html>

</body>

</html>