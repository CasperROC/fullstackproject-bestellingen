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
  <title>Students</title>
  <link rel="stylesheet" href="styler.css">
</head>

<body>
  <a href="homepage.php" class="button">back</a>
  <hr>
<?php


        $servername = "mysql";
    $username = "root";
    $password = "password";

     $conn = new mysqli($servername, $username, $password, "mydb");
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


$sql2 = "SELECT username FROM users";
$result2 = $conn->query($sql2);

//controleert of er gebruikers zijn en maakt een lijst
if ($result2->num_rows > 0) {
    echo "<ul>";
  while($row = $result2->fetch_assoc()) {
    $username = htmlspecialchars($row["username"]);
    echo "<li style='margin-bottom:10px;'>
            $username 
            <form method='post' action='deleteuser.php' style='display:inline;'>
                <input type='hidden' name='username' value='$username'>
                <button type='submit'>Verwijder</button>
            </form>
          </li>";
}
    echo "</ul>";
} else {
    echo "Geen gebruikers gevonden.";
}

?>


</body>
</html>
