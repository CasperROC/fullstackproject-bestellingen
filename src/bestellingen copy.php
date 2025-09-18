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
            <option value="<?= $row['Id'] ?>"><?= $row['Id'] ?>, <?= $row['Naam'] ?></option>
        <?php endwhile; ?>
    </select>


    <label for="product">Selecteer een product:</label>
    <select name="product_id" id="product">
        <?php while($row = $result2->fetch_assoc()): ?>
            <option value="<?= $row['Id'] ?>"><?= $row['Id'] ?>, <?= $row['Naam'] ?></option>
        <?php endwhile; ?>
    </select>


              <input type="hidden" name="amountaction" value="add">
Amount: <input type="text" name="Amount" minlength="1" maxlength="25"><br>
    <input id="bestellingverzenden" type="submit" name="bestellingsubmit" value="bestel">
        </form>

<?php

if ($_SERVER["REQUEST_METHOD"] === "POST"){
$bestellingsubmit = $_POST["bestellingsubmit"] ?? "";


if ($bestellingsubmit === "bestel"){
    $locatieIDbestelling = $_POST["locatie_id"] ?? "";
    $productIDbestelling = $_POST["product_id"] ?? "";
    $AmountBestelling = $_POST["Amount"] ?? "";

    $insert_stmt = $conn->prepare("INSERT INTO bestelling (locatieID_besteld, productID_besteld, aantalbesteld) VALUES (?, ?, ?)");
$insert_stmt->bind_param("iii", $locatieIDbestelling, $productIDbestelling, $AmountBestelling);

    if ($insert_stmt->execute()) {
        echo "bestelling " . htmlspecialchars($locatieIDbestelling) . ", " . htmlspecialchars($productIDbestelling) ." is aangemaakt.";
    } else {
        echo "Fout bij aanmaking: " . $insert_stmt->error;
    }
    $insert_stmt->close();
}

}

if ($action === "delete" && isset($_POST["Id"])) {
$delete_bestelling = $_POST['Id'] ?? null;
if (!$delete_bestelling) {
    echo "Geen bestelling gespecificeerd.";
    exit();
}





if ($delete_bestelling){
        $stmt = $conn->prepare("DELETE FROM bestelling WHERE Id = ?");
    $stmt->bind_param("s", $delete_bestelling);

    if ($stmt->execute()) {
        echo "successfully deleted " . htmlspecialchars($delete_bestelling) . "!";
                

    } else {
        echo "Fout bij verwijderen van bestelling.";
    }

    $stmt->close();
    
}
}

if ($action === "claim" && isset($_POST["Id"])) {
$location_ID = $_POST['locID'] ?? null;
$product_ID = $_POST['prodID'] ?? null;
$amountofproduct = $_POST['amount'] ?? null;

$insert_stmt = $conn->prepare("INSERT INTO locatie_has_product (locatie_Id1, product_Id1, Aantal) VALUES (?, ?, ?)");
$insert_stmt->bind_param("iii", $location_ID, $product_ID, $amountofproduct);

    if ($insert_stmt->execute()) {
        echo "product " . htmlspecialchars($location_ID) . htmlspecialchars($product_ID) . " is aangemaakt.";
    } else {
        echo "Fout bij aanmaking: " . $insert_stmt->error;
    }
    $insert_stmt->close();
}



$bestellingList = "SELECT Id, locatieID_besteld, productID_besteld, aantalbesteld FROM bestelling";
$bestelListResult = $conn->query($bestellingList);

if ($bestelListResult->num_rows > 0) {
    echo "<ul>";

    while($row = $bestelListResult->fetch_assoc()){
        $Id = $row["Id"];
    $locID = $row["locatieID_besteld"];
    $prodID = $row["productID_besteld"];
        $amount = $row["aantalbesteld"];
    echo "<li style='margin-bottom:10px;'>
            Id: $Id<br>
            locationID: $locID <br>
            productID: $prodID <br>
            amount: $amount <br>
            <form method='post' action='' style='display:inline;'>
                <input type='hidden' name='action' value='delete'>
                <input type='hidden' name='Id' value='$Id'>
                <button type='submit'>Verwijder</button>
            </form>
                        <form method='post' action='' style='display:inline;'>
                <input type='hidden' name='action' value='claim'>
                <input type='hidden' name='Id' value='$Id'>
                <input type='hidden' name='locID' value='$locID'>
                 <input type='hidden' name='prodID' value='$prodID'>
                  <input type='hidden' name='amount' value='$amount'>
                <button type='submit'>Ontvangen</button>
            </form>
          </li>";

    }}
?>
    <a href="homepage.php" class="button">Terug naar Home</a>
</body>
</html>