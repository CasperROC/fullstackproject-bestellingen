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
Naam van nieuw product: <input type="text" name="productNaam" minlength="3" maxlength="25"><br>
Naam van product type: <input type="text" name="productType" minlength="3" maxlength="25"><br>
Naam van fabriek: <input type="text" name="productFabriek" minlength="3" maxlength="25"><br>
product prijs: <input type="text" name="productPrijs" minlength="1" maxlength="25"><br>
product verkoop multiplier: <input type="text" name="productWinst" minlength="1" maxlength="3"> (bijv. 1.5) <br>
<input id="verzenden" type="submit" value="submit">
        </form>
   
</body>
<?php

if ($_SERVER["REQUEST_METHOD"] === "POST"){
$action = $_POST["action"] ?? "";

if ($action === "add" && isset($_POST["productNaam"], $_POST["productType"], $_POST["productFabriek"], $_POST["productPrijs"], $_POST["productWinst"])){
$prodNaam = $_POST["productNaam"] ?? "";
$prodType = $_POST["productType"] ?? "";
$prodFabriek = $_POST["productFabriek"] ?? "";
$prodPrijs = $_POST["productPrijs"] ?? "";
$prodWinst = $_POST["productWinst"] ?? "";

$insert_stmt = $conn->prepare("INSERT INTO product (Naam, Typey, Fabriek, Prijs, multiplier) VALUES (?, ?, ?, ?, ?)");
$insert_stmt->bind_param("sssdd", $prodNaam, $prodType, $prodFabriek, $prodPrijs, $prodWinst);

    if ($insert_stmt->execute()) {
        echo "product " . htmlspecialchars($prodNaam) . " is aangemaakt.";
    } else {
        echo "Fout bij aanmaking: " . $insert_stmt->error;
    }
    $insert_stmt->close();
    
}
}




if ($action === "verwijder" && isset($_POST["Id"])) {
$verwijder_product = $_POST['Id'] ?? null;
if (!$verwijder_product) {
    echo "Geen product gespecificeerd.";
    exit();
}

if ($verwijder_product){
        $stmt = $conn->prepare("DELETE FROM product WHERE Id = ?");
    $stmt->bind_param("i", $verwijder_product);

    if ($stmt->execute()) {
        echo "prodct met id " . htmlspecialchars($verwijder_product) . " successvol verwijderd!";
                

    } else {
        echo "Fout bij verwijderen van product.";
    }

    $stmt->close();
    
}
}

if ($action === "updatePrijs" && isset($_POST["Id"] , $_POST["nieuwePrijs"] )) {
$productId = $_POST['Id'] ?? null;
$nieuwePrijs = $_POST['nieuwePrijs'];

    $update_stmt = $conn->prepare("UPDATE product SET Prijs = ? WHERE Id = ?");
    $update_stmt->bind_param("di", $nieuwePrijs, $productId); // d = double, i = int

        if ($update_stmt->execute()) {
        echo "Prijs van product $productId is succesvol gewijzigd naar €" . htmlspecialchars($nieuwePrijs) . ".";
    } else {
        echo "Fout bij prijs aanpassen: " . $update_stmt->error;
    }
    $update_stmt->close();
} 

if ($action === "updateWinst" && isset($_POST["Id"] , $_POST["nieuweWinst"] )) {
$productId = $_POST['Id'] ?? null;
$nieuweWinst = $_POST['nieuweWinst'];

    $update_stmt = $conn->prepare("UPDATE product SET multiplier = ? WHERE Id = ?");
    $update_stmt->bind_param("di", $nieuweWinst, $productId); // d = double, i = int

        if ($update_stmt->execute()) {
        echo "Winstmultiplier van product $productId is succesvol gewijzigd naar x" . htmlspecialchars($nieuweWinst) . ".";
    } else {
        echo "Fout bij winst aanpassen: " . $update_stmt->error;
    }
    $update_stmt->close();
} 

$prodLijst = "SELECT * FROM product";
$prodLijstResult = $conn->query($prodLijst);

if ($prodLijstResult->num_rows > 0) {
    echo "<ul>";

    while($row = $prodLijstResult->fetch_assoc()){
        $prodIdlijst = htmlspecialchars($row["Id"]);
    $prodNaamlijst = htmlspecialchars($row["Naam"]);
    $prodTypelijst = htmlspecialchars($row["Typey"]);
    $prodFabrieklijst = htmlspecialchars($row["Fabriek"]);
    $prodPrijslijst = htmlspecialchars($row["Prijs"]);
        $prodWinstlijst = htmlspecialchars($row["multiplier"] ?? '');
    echo "<li style='margin-bottom:10px;'>
            Id: $prodIdlijst <br>
            Naam: $prodNaamlijst <br>
            Type: $prodTypelijst <br>
            Fabriek: $prodFabrieklijst <br>
            Prijs: $prodPrijslijst <br>
            Winst: $prodWinstlijst <br>
         <form method='post' action='' style='display:inline;'>
    <input type='hidden' name='action' value='verwijder'>
    <input type='hidden' name='Id' value='$prodIdlijst'>
    <button type='submit'>Verwijder</button>
</form>

        <form method='post' action='' style='display:inline; margin-left:10px;'>
            <input type='hidden' name='action' value='updatePrijs'>
            <input type='hidden' name='Id' value='$prodIdlijst'>
            <input type='text' name='nieuwePrijs' placeholder='Nieuwe prijs' required>
            <button type='submit'>Wijzig prijs</button>
        </form>

                <form method='post' action='' style='display:inline; margin-left:10px;'>
            <input type='hidden' name='action' value='updateWinst'>
            <input type='hidden' name='Id' value='$prodIdlijst'>
            <input type='text' name='nieuweWinst' placeholder='Nieuwe Multi' required>
            <button type='submit'>Wijzig winstmulti</button>
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