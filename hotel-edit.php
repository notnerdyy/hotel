<?php

if (!isset($_GET["id"])) {
  // 若没有传入ID参数，则默认ID设置为1（或其他合理的默认值）
  $id = 1;
} else {
  $id = $_GET["id"];
}

require_once("../hotel_db_connect.php");

$sql = "SELECT hotel_list.*, room_category.room_type FROM hotel_list 
        JOIN room_category ON hotel_list.room_type_id = room_category.id
        WHERE hotel_list.id = $id AND hotel_list.valid = 1";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $row["name"] ?></title>
  <?php include("../css.php") ?>

</head>

<body>

  <div class="container">
    <div class="py-2">
      <a class="btn btn-dark" href="hotel-list.php"><i class="fa-solid fa-arrow-left"></i> 回狗狗旅館列表</a>
    </div>

    <table class="table table-bordered">
      <tr>
        <th>ID</th>
        <td><?= $row["id"] ?></td>
      </tr>
      <tr>
        <th>地區</th>
        <td><?= $row["location"] ?></td>
      </tr>
      <tr>
        <th>旅館名稱</th>
        <td><?= $row["name"] ?></td>
      </tr>
      <tr>
        <th>介紹</th>
        <td><?= $row["description"] ?></td>
      </tr>
      <tr>
        <th>房間類型</th>
        <td><?= $row["room_type"] ?></td>
      </tr>
      <tr>
        <th>詳細地址</th>
        <td><?= $row["address"] ?></td>
      </tr>
      <tr>
        <th>聯絡電話</th>
        <td><?= $row["phone"] ?></td>
      </tr>
    </table>
  </div>

  <div class="row justify-content-center">
    <div class="col-lg-4">
      <button class="btn btn-dark" type="submit">送出</button>
    </div>
  </div>

</body>

</html>