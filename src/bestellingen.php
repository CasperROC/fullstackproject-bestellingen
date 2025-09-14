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
    <?php 

            $servername = "mysql";
    $username = "root";
    $password = "password";
     $conn = new mysqli($servername, $username, $password, "mydb");
        if ($conn->connect_error) {
  die(" Connection failed: " . $conn->connect_error);}
  
  $result = $conn->query("SELECT Id, Naam FROM locatie");
  $result2 = $conn->query("SELECT Id, Naam FROM product");
  ?>

  
<form method="POST" action="">
    <input type="hidden" name="action" value="bestel">
    <label for="locatie">Selecteer een locatie:</label>
    <select name="locatie_id" id="locatie">
        <?php while($row = $result->fetch_assoc()): ?>
            <option value="<?= $row['Id'] ?>"><?= $row['Naam'] ?></option>
        <?php endwhile; ?>
    </select>


    <label for="product">Selecteer een product:</label>
    <select name="product_id" id="product">
        <?php while($row = $result2->fetch_assoc()): ?>
            <option value="<?= $row['Id'] ?>"><?= $row['Naam'] ?></option>
        <?php endwhile; ?>
    </select>
    <input id="bestellingverzenden" type="submit" name="bestellingsubmit" value="bestel">
        </form>

<?php

if ($_SERVER["REQUEST_METHOD"] === "POST"){
$bestellingsubmit = $_POST["bestellingsubmit"] ?? "";

if ($bestellingsubmit === "bestel"){
    $locatieIDbestelling = $_POST["locatie_id"] ?? "";
    $productIDbestelling = $_POST["product_id"] ?? "";

    $insert_stmt = $conn->prepare("INSERT INTO bestelling (locatieID_besteld, productID_besteld) VALUES (?, ?)");
$insert_stmt->bind_param("ii", $locatieIDbestelling, $productIDbestelling);

    if ($insert_stmt->execute()) {
        echo "bestelling " . htmlspecialchars($locatieIDbestelling) . ", " . htmlspecialchars($productIDbestelling) ." is aangemaakt.";
    } else {
        echo "Fout bij aanmaking: " . $insert_stmt->error;
    }
    $insert_stmt->close();
}

}
?>
    <a href="homepage.php" class="button">Terug naar Home</a>
</body>
</html>