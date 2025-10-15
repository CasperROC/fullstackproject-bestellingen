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
$conn = new mysqli($servername, $username, $password, "Newmydb");
if ($conn->connect_error) {
    die(" Connection failed: " . $conn->connect_error);
}

$action = $_POST["action"] ?? "";
$filterLocatie = $_POST["locatie_Id1"] ?? null;

$sql = "SELECT DISTINCT l.Id, l.Naam
        FROM locatie_has_product lhp
        JOIN locatie l ON l.Id = lhp.locatie_Id1";
$result = $conn->query($sql);

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
    <p class='randomtext'>Welkom, <?php echo htmlspecialchars($name); ?>!</p>

<form method="POST" action="">
    <input type="hidden" name="action" value="filterLoc">
    <label for="locatie">Filter op locatie:</label>
    <select name="locatie_Id1" id="locatie">
        <?php while($row = $result->fetch_assoc()): ?>
            <option value="<?= $row['Id'] ?>">
                <?= ($filterLocatie == $row['Id']) ? 'geselecteerd' : '' ?>>
                <?= $row['Naam'] ?>
            </option>
        <?php endwhile; ?>
    </select>
    <button type="submit">Filter</button>
</form>

<form method="POST" action="">
    <button type="submit" name="action" value="unfilter">stop filter</button>
</form>
<?php
//JOIN MAKEN VOOR LOCATIE/PRODUCT?
//VEILIG MET PREPARED STATEMENTS NACHECKEN?


if ($action === "delete" && isset($_POST["ID1"]) && isset($_POST["ID2"])) {
    $delete_product = $_POST['ID1'] ?? null;
     $delete_product2 = $_POST['ID2'] ?? null;
    if (!$delete_product || !$delete_product2) {
        echo "Geen product gespecificeerd.";
        exit();
    }

    if ($delete_product && $delete_product2) {
        $stmt = $conn->prepare("DELETE FROM locatie_has_product WHERE locatie_Id1 = ? AND product_Id1 = ?");
        $stmt->bind_param("ii", $delete_product, $delete_product2);

        if ($stmt->execute()) {
            echo "product met id " . htmlspecialchars($delete_product) . " " . htmlspecialchars($delete_product2) . " is verwijderd!";
        } else {
            echo "Fout bij verwijderen van product.";
        }

        $stmt->close();
    }
}

$VrdLijst = "SELECT lhp.Aantal,

  lhp.locatie_Id1,
  lhp.product_Id1,
   l.Naam AS locatieNaam,
    p.Naam AS productNaam,
    p.Prijs AS productPrijs,
    p.prijs * lhp.Aantal AS WaardeInkoop1,
     ROUND(p.prijs * lhp.Aantal * p.multiplier, 2) AS WaardeVerkoop1
FROM locatie_has_product lhp
JOIN locatie l ON l.Id = lhp.locatie_Id1
JOIN product p ON p.Id = lhp.product_Id1";

if ($action === "filterLoc" && $filterLocatie) {
    $VrdLijst .= " WHERE lhp.locatie_Id1 = " . intval($filterLocatie);
}
if ($action === "unfilter" && $filterLocatie) {
    $VrdLijst .= null;
}

$VrdLijstResult = $conn->query($VrdLijst);

if ($VrdLijstResult->num_rows > 0) {
    echo "<ul>";

    while ($row = $VrdLijstResult->fetch_assoc()) {
        $LocID = htmlspecialchars($row["locatie_Id1"] ?? "");
        $ProdID = htmlspecialchars($row["product_Id1"] ?? "");
        $Aantal = htmlspecialchars($row["Aantal"] ?? "");
        $Inkoop = htmlspecialchars($row["WaardeInkoop1"] ?? "");
        $Verkoop = htmlspecialchars($row["WaardeVerkoop1"] ?? "");
        $locNaam = htmlspecialchars($row["locatieNaam"] ?? "");
        $prodNaam = htmlspecialchars($row["productNaam"] ?? "");
        echo "<li class='randomtext' style='margin-bottom:10px;'>
                Locatie: $LocID, $locNaam <br>
                Product: $ProdID, $prodNaam <br>
                Aantal op voorraad: $Aantal <br>
                inkoopwaarde: $Inkoop <br>
                Verkoopwaarde: $Verkoop <br>
             <form method='post' action='' style='display:inline;'>
                <input type='hidden' name='action' value='delete'>
                <input type='hidden' name='ID1' value='$LocID'>
                <input type='hidden' name='ID2' value='$ProdID'>
                <button type='submit'>Verwijder</button>
            </form>
              </li>";
    }
    echo "</ul>";
}
?>


<a href="homepage.php" class="link">Terug naar Home</a>

</body>
</html>
