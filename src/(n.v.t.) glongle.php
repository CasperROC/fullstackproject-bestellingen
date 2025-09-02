<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    $servername = "mysql";
    $username = "root";
    $password = "password";

    $conn = new mysqli($servername, $username, $password);

    if ($conn->connect_error) {
  die(" Connection failed: " . $conn->connect_error);
}
echo " Connected successfully ";

$sql = "CREATE DATABASE IF NOT EXISTS zingusDB";

if ($conn->query($sql) === true) {
    echo " database workey do ";
} else {
    echo " error creating databse ";
}

$conn->select_db(database: "zingusDB");

$sqlTableInsert = "CREATE TABLE IF NOT EXISTS zorblo (
id int auto_increment primary key,
firstname varchar(50) not null,
lastname varchar(50) not null,
age int(3) not null,
hotdogseaten int(50) not null  
)";

$newAge = 18;

if($conn->query(query: $sqlTableInsert) === true) {
    echo " ts works ";
} else {
    echo " ts dont work ";
}

$sqlTableInsert = "INSERT INTO zorblo (firstname, lastname, age, hotdogseaten) values ('Gooper', 'Zooper', $newAge, 6873)";

$sqlUsersTable = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
)"; 

if ($conn->query($sqlUsersTable) === TRUE) {
    echo " Gebruikerstabel is gemaakt ";
} else {
    echo " Gebruikerstabel brokey ";
}

if($conn->query(query: $sqlTableInsert) === true) {
    echo " Gooper has arrived ";
} else {
    echo " Gooper died :( ";
}

    ?>
</body>
</html>