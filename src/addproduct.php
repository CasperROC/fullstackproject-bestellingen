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
Naam van nieuw product: <input type="text" name="productNaam" minlength="3" maxlength="25"><br>
Naam van product type: <input type="text" name="productType" minlength="3" maxlength="25"><br>
Naam van fabriek: <input type="text" name="productFabriek" minlength="3" maxlength="25"><br>
product prijs: <input type="text" name="productPrijs" minlength="1" maxlength="25"><br>
<input id="verzenden" type="submit" value="submit">
        </form>
   
</body>
<?php

if ($_SERVER["REQUEST_METHOD"] === "POST"){
$action = $_POST["action"] ?? "";

if ($action === "add" && isset($_POST["productNaam"], $_POST["productType"], $_POST["productFabriek"], $_POST["productPrijs"])){
$prodNaam = $_POST["productNaam"] ?? "";
$prodType = $_POST["productType"] ?? "";
$prodFabriek = $_POST["productFabriek"] ?? "";
$prodPrijs = $_POST["productPrijs"] ?? "";

$insert_stmt = $conn->prepare("INSERT INTO product (Naam, Typey, Fabriek, Prijs) VALUES (?, ?, ?, ?)");
$insert_stmt->bind_param("sssd", $prodNaam, $prodType, $prodFabriek, $prodPrijs);

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

if ($delete_product){
        $stmt = $conn->prepare("DELETE FROM product WHERE Id = ?");
    $stmt->bind_param("i", $delete_product);

    if ($stmt->execute()) {
        echo "prodct met id " . htmlspecialchars($delete_product) . "!";
                

    } else {
        echo "Fout bij verwijderen van product.";
    }

    $stmt->close();
    
}
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
    echo "<li style='margin-bottom:10px;'>
            Id: $prodIdlijst <br>
            Naam: $prodNaamlijst <br>
            Type: $prodTypelijst <br>
            Fabriek: $prodFabrieklijst <br>
            Prijs: $prodPrijslijst <br>
         <form method='post' action='' style='display:inline;'>
    <input type='hidden' name='action' value='verwijder'>
    <input type='hidden' name='Id' value='$prodIdlijst'>
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