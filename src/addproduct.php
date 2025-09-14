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
Name of new product: <input type="text" name="productNaam" minlength="3" maxlength="25"><br>
Name of product type: <input type="text" name="productType" minlength="3" maxlength="25"><br>
Name of product factory: <input type="text" name="productFabriek" minlength="3" maxlength="25"><br>
Name of product price: <input type="text" name="productPrijs" minlength="1" maxlength="25"><br>
<input id="verzenden" type="submit" value="submit">
        </form>
   
</body>
<?php

if ($_SERVER["REQUEST_METHOD"] === "POST"){
$action = $_POST["action"] ?? "";

if ($action === "add" && isset($_POST["productNaam"], $_POST["productType"], $_POST["productFabriek"], $_POST["productPrijs"])){
$prodName = $_POST["productNaam"] ?? "";
$prodType = $_POST["productType"] ?? "";
$prodFactory = $_POST["productFabriek"] ?? "";
$prodPrice = $_POST["productPrijs"] ?? "";

$insert_stmt = $conn->prepare("INSERT INTO product (Naam, Typey, Fabriek, Prijs) VALUES (?, ?, ?, ?)");
$insert_stmt->bind_param("sssd", $prodName, $prodType, $prodFactory, $prodPrice);

    if ($insert_stmt->execute()) {
        echo "product " . htmlspecialchars($prodName) . " is aangemaakt.";
    } else {
        echo "Fout bij aanmaking: " . $insert_stmt->error;
    }
    $insert_stmt->close();
    
}
}




if ($action === "delete" && isset($_POST["Id"])) {
$delete_product = $_POST['Id'] ?? null;
if (!$delete_product) {
    echo "Geen product gespecificeerd.";
    exit();
}

if ($delete_product){
        $stmt = $conn->prepare("DELETE FROM product WHERE Id = ?");
    $stmt->bind_param("i", $delete_product);

    if ($stmt->execute()) {
        echo "prodct met id " . htmlspecialchars($delete_product) . "!";
                

    } else {
        echo "Fout bij verwijderen van gebruiker.";
    }

    $stmt->close();
    
}
}

$prodList = "SELECT * FROM product";
$prodListResult = $conn->query($prodList);

if ($prodListResult->num_rows > 0) {
    echo "<ul>";

    while($row = $prodListResult->fetch_assoc()){
        $prodId = htmlspecialchars($row["Id"]);
    $prodNaam = htmlspecialchars($row["Naam"]);
    $prodType = htmlspecialchars($row["Typey"]);
    $prodFabriek = htmlspecialchars($row["Fabriek"]);
    $prodPrijs = htmlspecialchars($row["Prijs"]);
    echo "<li style='margin-bottom:10px;'>
            Id: $prodId <br>
            Naam: $prodNaam <br>
            Type: $prodType <br>
            Fabriek: $prodFabriek <br>
            Prijs: $prodPrijs <br>
         <form method='post' action='' style='display:inline;'>
    <input type='hidden' name='action' value='delete'>
    <input type='hidden' name='Id' value='$prodId'>
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