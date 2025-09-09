<?php
session_start();

// Controleer of gebruiker is ingelogd
if (!isset($_SESSION["name"])) {
    echo "Je bent niet ingelogd!";
    exit();
}

// Haal eventueel naam en wachtwoord uit de sessie
$name = $_SESSION["name"];
$pass = $_SESSION["password"];

        $servername = "mysql";
    $username = "root";
    $password = "password";
 $conn = new mysqli($servername, $username, $password, "mydb");
        if ($conn->connect_error) {
  die(" Connection failed: " . $conn->connect_error);}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lege Pagina</title>
    <link rel="stylesheet" href="styler.css">
</head>
<body>
    <p>Welkom, <?php echo htmlspecialchars($name); ?>!</p>

    <form action="" method="post">
Name of new location: <input type="text" name="locationName" minlength="3" maxlength="25"><br>
<input id="verzenden" type="submit" value="submit">

    <a href="homepage.php" class="button">Terug naar Home</a>
</body>
<?php
$locName = $_POST["locationName"];

$insert_stmt = $conn->prepare("INSERT INTO locatie (Naam) VALUES (?)");
$insert_stmt->bind_param("s", $locName);
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["locationName"])){
    if ($insert_stmt->execute()) {
        echo "locatie " . htmlspecialchars($locName) . " is aangemaakt.";
    } else {
        echo "Fout bij aanmaking: " . $insert_stmt->error;
    }
    $insert_stmt->close();
}
?>
</html>