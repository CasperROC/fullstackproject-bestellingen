<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Games; met SQL prepared statement en partial</title>
  <link rel="stylesheet" href="css/style.css">
</head>

<body>
  <table>
    <tr>
      <th>Id</th>
      <th>Age</th>
      <th>score</th>
    </tr>

    <?php
    $conn = require_once "partials/dbconnection.php";
    $platform = "PS4";

    $stmt = $conn->prepare("SELECT * FROM GAMES WHERE platform = ?");
    $stmt->bind_param("s", $platform);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0)
      exit('No rows');

    while ($row = $result->fetch_assoc()) {
      echo "<tr>";
      echo "<td> <a href='details.php?id=" . $row['id'] . "'>" . $row['id'] . "</a></td>";
      echo "<td>" . $row['name'] . "</td>";
      echo "<td>" . $row['metascore'] . "</td>";
      echo "</tr>";
    }
    echo "</table>";

    $stmt->close();
    ?>
</body>

</html>