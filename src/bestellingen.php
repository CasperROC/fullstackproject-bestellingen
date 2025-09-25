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
$action = $_POST["action"] ?? "";
$action2 = $_POST["action2"] ?? "";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bestellingen</title>
    <link rel="stylesheet" href="styler.css">
</head>
<body>
    <p>Welkom, <?php echo htmlspecialchars($name); ?>!</p>
    <?php 
    $servername = "mysql";
    $username = "root";
    $password = "password";
    $conn = new mysqli($servername, $username, $password, "Newmydb");
    if ($conn->connect_error) {
        die(" Connection failed: " . $conn->connect_error);
    }

    $result = $conn->query("SELECT Id, Naam FROM locatie");
    $result2 = $conn->query("SELECT Id, Naam, Prijs FROM product");
    ?>

    <form method="POST" action="">
        <input type="hidden" name="action" value="bestel">
        <label for="locatie">Selecteer een locatie:</label>
        <select name="locatie_id" id="locatie">
            <?php while($row = $result->fetch_assoc()): ?>
                <option value="<?= $row['Id'] ?>"><?= $row['Id'] ?>, <?= $row['Naam'] ?></option>
            <?php endwhile; ?>
        </select>

        <label for="product">Selecteer een product:</label>
        <select name="product_id" id="product">
            <?php while($row = $result2->fetch_assoc()): ?>
                <option value="<?= $row['Id'] ?>"><?= $row['Id'] ?>, <?= $row['Naam'] ?>, $<?= $row['Prijs'] ?></option>
            <?php endwhile; ?>
        </select>

        <input type="hidden" name="aantalaction" value="add">
        Aantal: <input type="number" name="Aantal" min="1" max="999999"><br>
        <input id="bestellingverzenden" type="submit" name="bestellingsubmit" value="bestel">
    </form>

<?php
// Nieuwe bestelling plaatsen
if ($_SERVER["REQUEST_METHOD"] === "POST"){
    $bestellingsubmit = $_POST["bestellingsubmit"] ?? "";

    if ($bestellingsubmit === "bestel"){
        $locatieIDbestelling = $_POST["locatie_id"] ?? "";
        $productIDbestelling = $_POST["product_id"] ?? "";
        $AantalBestelling = $_POST["Aantal"] ?? "";

        $insert_stmt = $conn->prepare("INSERT INTO bestelling (locatieID_besteld, productID_besteld, aantalbesteld) VALUES (?, ?, ?)");
        $insert_stmt->bind_param("iii", $locatieIDbestelling, $productIDbestelling, $AantalBestelling);

        if ($insert_stmt->execute()) {
            echo "bestelling " . htmlspecialchars($locatieIDbestelling) . ", " . htmlspecialchars($productIDbestelling) ." is aangemaakt.";
        } else {
            echo "Fout bij aanmaking: " . $insert_stmt->error;
        }
        $insert_stmt->close();
    }
}

// Bestelling verwijderen
if ($action === "delete" && isset($_POST["Id"])) {
    $delete_bestelling = $_POST['Id'] ?? null;
    if (!$delete_bestelling) {
        echo "Geen bestelling gespecificeerd.";
        exit();
    }

    if ($delete_bestelling){
        $stmt = $conn->prepare("DELETE FROM bestelling WHERE Id = ?");
        $stmt->bind_param("i", $delete_bestelling);

        if ($stmt->execute()) {
            echo "successfully deleted " . htmlspecialchars($delete_bestelling) . ".    ";
        } else {
            echo "Fout bij verwijderen van bestelling.";
        }

        $stmt->close();
    }
}

// Bestelling claimen → toevoegen aan locatie_has_product
if ($action2 === "claim" && isset($_POST["Id"])) {
    $locatie_ID = $_POST['locID'] ?? null;
    $product_ID = $_POST['prodID'] ?? null;
    $prodAantal = $_POST['aantal'] ?? null;
    $inkoopWaarde = $_POST['inkoopWaarde'] ?? null;
    $verkoopWaarde = $_POST['verkoopWaarde'] ?? null;
    
    if ($locatie_ID && $product_ID && $prodAantal) {
        $insert_stmt = $conn->prepare("INSERT INTO locatie_has_product (locatie_Id1, product_Id1, WaardeInkoop, WaardeVerkoop, Aantal) VALUES (?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE Aantal = Aantal + ?");
        $insert_stmt->bind_param("iiddii", $locatie_ID, $product_ID, $inkoopWaarde, $verkoopWaarde, $prodAantal, $prodAantal);
 
        if ($insert_stmt->execute()) {
            echo "Product (locatie: " . htmlspecialchars($locatie_ID) . ", product: " . htmlspecialchars($product_ID) . ") is aangemaakt.   ";
        } else {
            echo "Fout bij aanmaking: " . $insert_stmt->error;
        }
        $insert_stmt->close();
    } else {
        echo "Claim mislukt: gegevens ontbreken.";
    }
}

// Bestellingenlijst tonen
//"SELECT Id, locatieID_besteld, productID_besteld, aantalbesteld FROM bestelling"
$bestellingLijst = "SELECT 
b.aantalbesteld,
 b.Id,
  b.locatieID_besteld,
  b.productID_besteld,
   l.Naam AS locatieNaam,
    p.Naam AS productNaam,
    p.prijs * b.aantalbesteld AS inkoopWaarde,
     ROUND(p.prijs * b.aantalbesteld * 1.2, 2) AS verkoopWaarde
FROM bestelling b
JOIN locatie l ON l.Id = b.locatieID_besteld
JOIN product p ON p.Id = b.productID_besteld";
$bestelLijstResult = $conn->query($bestellingLijst);

if ($bestelLijstResult->num_rows > 0) {
    echo "<ul>";

    while($row = $bestelLijstResult->fetch_assoc()){
        $Id = $row["Id"];
        $locID = $row["locatieID_besteld"];
        $prodID = $row["productID_besteld"];
        $aantal = $row["aantalbesteld"];
        $locNaam = htmlspecialchars($row["locatieNaam"] ?? "");
        $prodNaam = htmlspecialchars($row["productNaam"] ?? "");
        $inkoopWaarde = htmlspecialchars($row["inkoopWaarde"] ?? "");
           $verkoopWaarde = htmlspecialchars($row["verkoopWaarde"] ?? "");
        echo "<li style='margin-bottom:10px;'>
                Id van bestelling: $Id<br>
                locatie: $locID, $locNaam <br>
                product: $prodID, $prodNaam <br>
                aantal: $aantal <br>
                inkoopWaarde: $inkoopWaarde <br>
                verkoopWaarde: $verkoopWaarde <br>
                <form method='post' action='' style='display:inline;'>
                    <input type='hidden' name='action' value='delete'>
                    <input type='hidden' name='Id' value='$Id'>
                    <button type='submit'>Verwijder</button>
                </form>
                <form method='post' action='' style='display:inline;'>
                    <input type='hidden' name='action2' value='claim'>
                         <input type='hidden' name='action' value='delete'>
                    <input type='hidden' name='Id' value='$Id'>
                    <input type='hidden' name='locID' value='$locID'>
                    <input type='hidden' name='prodID' value='$prodID'>
                    <input type='hidden' name='aantal' value='$aantal'>
                    <input type='hidden' name='inkoopWaarde' value='$inkoopWaarde'>
                    <input type='hidden' name='verkoopWaarde' value='$verkoopWaarde'>
                    <button type='submit'>Ontvangen</button>
                </form>
              </li>";
    }
    echo "</ul>";
}
?>
    <a href="homepage.php" class="button">Terug naar Home</a>
</body>
</html>
