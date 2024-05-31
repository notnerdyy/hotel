<?php
require_once("../hotel_db_connect.php");

if (!isset($_GET["id"])) {
  $id = 1;
} else {
  $id = $_GET["id"];
}

$sql = "SELECT id, name, address, phone, description, category_id FROM hotel_list WHERE valid = 1";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
var_dump($row)

?>

<?php include("../css.php") ?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>hotel-edit</title>
</head>

<body>

  <div class="container">

  </div>

</body>

</html>