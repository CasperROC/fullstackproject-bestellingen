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
    <p>Welkom, <?php echo htmlspecialchars($name); ?>!</p>

<form method="POST" action="">
    <input type="hidden" name="action" value="filterLoc">
    <label for="locatie">Filter op locatie:</label>
    <select name="locatie_Id1" id="locatie">
        <?php while($row = $result->fetch_assoc()): ?>
            <option value="<?= $row['Id'] ?>">
                <?= ($filterLocatie == $row['Id']) ? 'selected' : '' ?>>
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


if ($action === "delete" && isset($_POST["Id"])) {
    $delete_product = $_POST['Id'] ?? null;
    if (!$delete_product) {
        echo "Geen product gespecificeerd.";
        exit();
    }

    if ($delete_product) {
        $stmt = $conn->prepare("DELETE FROM product WHERE Id = ?");
        $stmt->bind_param("i", $delete_product);

        if ($stmt->execute()) {
            echo "product met id " . htmlspecialchars($delete_product) . "!";
        } else {
            echo "Fout bij verwijderen van product.";
        }

        $stmt->close();
    }
}

$VrdLijst = "SELECT * FROM locatie_has_product";

if ($action === "filterLoc" && $filterLocatie) {
    $VrdLijst .= " WHERE locatie_Id1 = " . intval($filterLocatie);
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
        $Inkoop = htmlspecialchars($row["WaardeInkoop"] ?? "");
        $Verkoop = htmlspecialchars($row["WaardeVerkoop"] ?? "");
        echo "<li style='margin-bottom:10px;'>
                Locatie nr: $LocID <br>
                Product nr: $ProdID <br>
                Aantal op voorraad: $Aantal <br>
                inkoopwaarde: $Inkoop <br>
                Verkoopwaarde: $Verkoop <br>
             <form method='post' action='' style='display:inline;'>
                <input type='hidden' name='action' value='delete'>
                <input type='hidden' name='Id' value='$LocID'>
                <button type='submit'>Verwijder</button>
            </form>
              </li>";
    }
    echo "</ul>";
}
?>


<a href="homepage.php" class="button">Terug naar Home</a>

</body>
</html>
