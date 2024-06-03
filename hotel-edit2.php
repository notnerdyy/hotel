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
        WHERE hotel_list.id = $id AND hotel_list.valid = 1";;
$result = $conn->query($sql);
$row = $result->fetch_assoc();


//缺少編輯圖片

// 房型下拉選單
$sql = "SELECT id, room_type FROM room_category";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  $room_types = $result->fetch_all(MYSQLI_ASSOC);
} else {
  $room_types = [];
}

// 地區下拉選單
$sql2 = "SELECT id, location FROM area_category";
$result2 = $conn->query($sql2);

if ($result2->num_rows > 0) {
  $locations = $result2->fetch_all(MYSQLI_ASSOC);
} else {
  $locations = [];
}

$conn->close();

?>

<!-- 缺編輯圖片 -->
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
          <input type="hidden" name="id" value="<?= $row["id"] ?>">
          <th>ID</th>
          <td><?= $row["id"] ?></td>
        </tr>
        <tr>
          <th>地區</th>
          <td>
            <select class="form-select" id="location" name="location" aria-label="市/區">
              <?php foreach ($locations as $location) : ?>
                <option value="<?= $location['id'] ?>"><?= $location['location'] ?></option>
              <?php endforeach; ?>
            </select>
          </td>
        </tr>
        <tr>
          <th>旅館名稱</th>
          <td> <input type="text" class="from-control" name="name" value="<?= $row["name"] ?>"></td>
        </tr>
        <tr>
          <th>介紹</th>
          <td> <input type="text" class="from-control" name="description" value="<?= $row["description"] ?>"></td>
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
          <td><select class="form-select" id="room_type" name="room_type" aria-label="房間類型">
              <?php foreach ($room_types as $room_type) : ?>
                <option value="<?= $room_type['id'] ?>"><?= $room_type['room_type'] ?></option>
              <?php endforeach; ?>
            </select></td>
        </tr>
        <tr>
          <th>詳細地址</th>
          <td> <input type="text" class="from-control" name="address" value="<?= $row["address"] ?>"></td>

        </tr>
        <tr>
          <th>聯絡電話</th>
          <td> <input type="text" class="from-control" name="phone" value="<?= $row["phone"] ?>"></td>
        </tr>
      </table>

  </div>


  <div class="row px-5">
    <div class="col-lg-4">
      <button class="btn btn-outline-dark" type="submit">編輯</button>
      <button class="btn btn-dark" type="submit">送出</button>
      </form>
    </div>
  </div>

</body>

</html>