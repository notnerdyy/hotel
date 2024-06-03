<?php

if (!isset($_GET["id"])) {
  $id = 1;
} else {
  $id = $_GET["id"];
}

require_once("../hotel_db_connect.php");

$sql = "SELECT hotel_list.*, room_category.room_type, area_category.location FROM hotel_list 
        JOIN room_category ON hotel_list.room_type_id = room_category.id
        JOIN area_category ON hotel_list.location_id = area_category.id
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

    <form action="doUpdateHotel.php" method="post">
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
          <th>圖片</th>
          <td>
            <?php
            $imageUrls = json_decode($row['images'], true);
            if (!empty($imageUrls)) {
              foreach ($imageUrls as $imageUrl) {
                echo '<img src="' . $imageUrl . '" alt="Hotel Image" class="hotel-image">';
              }
            } else {
              echo "找不到此旅館照片";
            }
            ?></td>
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
    </form>
  </div>

  <div class="row px-5">
    <div class="col-lg-4">
      <a class="btn btn-outline-dark" href="hotel-edit2.php">編輯</a>
      <button class="btn btn-dark" type="submit">送出</button>

    </div>
  </div>

</body>

</html>