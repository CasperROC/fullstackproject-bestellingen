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

  $action = $_POST["action"] ?? "";
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
          <input type="hidden" name="action" value="add">
Name of new location: <input type="text" name="locationName" minlength="1" maxlength="25"><br>
<input id="verzenden" type="submit" value="submit">
        </form>
   
</body>
<?php

if ($_SERVER["REQUEST_METHOD"] === "POST"){
$action = $_POST["action"] ?? "";

if ($action === "add" && isset($_POST["locationName"])){
$locNaam = $_POST["locationName"];

$insert_stmt = $conn->prepare("INSERT INTO locatie (Naam) VALUES (?)");
$insert_stmt->bind_param("s", $locNaam);
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["locationName"])){
    if ($insert_stmt->execute()) {
        echo "locatie " . htmlspecialchars($locNaam) . " is aangemaakt.";
    } else {
        echo "Fout bij aanmaking: " . $insert_stmt->error;
    }
    $insert_stmt->close();
    
}
}
}



if ($action === "verwijder" && isset($_POST["Naam"])) {
$verwijder_locnaam = $_POST['Naam'] ?? null;
if (!$verwijder_locnaam) {
    echo "Geen locatie gespecificeerd.";
    exit();
}

if ($verwijder_locnaam){
        $stmt = $conn->prepare("DELETE FROM locatie WHERE Naam = ?");
    $stmt->bind_param("s", $verwijder_locnaam);

    if ($stmt->execute()) {
        echo "Succesvol verwijderd " . htmlspecialchars($verwijder_locnaam) . "!";
                

    } else {
        echo "Fout bij verwijderen van locatie.";
    }

    $stmt->close();
    
}
}

$locNaamLijst = "SELECT Id, Naam FROM locatie";
$locLijstResult = $conn->query($locNaamLijst);

if ($locLijstResult->num_rows > 0) {
    echo "<ul>";

    while($row = $locLijstResult->fetch_assoc()){
        $Id = htmlspecialchars($row["Id"]);
    $Naam = htmlspecialchars($row["Naam"]);
    echo "<li style='margin-bottom:10px;'>
            Id: $Id <br>
            Naam: $Naam <br>
            <form method='post' action='' style='display:inline;'>
                <input type='hidden' name='action' value='verwijder'>
                <input type='hidden' name='Id' value='$Id'>
                <button type='submit'>Verwijder</button>
            </form>
          </li>";

    }
}
?>
</html>
<body>
    <hr>
     <a href="homepage.php" class="button">Terug naar Home</a>
</body>