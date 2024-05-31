<?php
require_once("../hotel_db_connect.php");


// 搜尋欄
if (isset($_GET["search"])) {
  $search = $_GET["search"];
  $result = $conn->query($sql);
  $hotelCount = $result->num_rows;
} else {
  $sql = "SELECT hotel_list. *, room_category.room_type FROM hotel_list 
  JOIN room_category ON hotel_list.room_type_id = room_category.id";



  $result = $conn->query($sql);
  $hotelCount = $result->num_rows;
}



$rows = $result->fetch_all(MYSQLI_ASSOC);
?>

<?php include("../css.php") ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>旅館列表</title>
</head>

<body>

  <div class="container">
    <div class="py-2">
      <div class="d-flex justify-content-end gap-3">
        <div>
          <?php if (isset($_GET["search"])) : ?>
            <a class="btn btn-outline-dark" href="hotel-list.php"><i class="fa-solid fa-arrow-left"></i></i></a>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <!-- 搜尋欄 -->
    <div class="d-flex justify-content-end gap-3">
      <form action="">
        <div class="input-group">
          <input type="text" class="form-control" placeholder="search..." name="search">
          <button class="btn btn-dark" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
        </div>
      </form>
    </div>

    <div class="pb-2">
      共 <?= $hotelCount ?> 間
    </div>

    <div class="py-2 mb-3">
      <?php if ($hotelCount > 0) : ?>
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>ID</th>
              <th>地區</th>
              <th>旅館名稱</th>
              <th>介紹</th>
              <th>房間類型</th>
              <th>詳細地址</th>
              <th>聯絡電話</th>

            </tr>
          </thead>
          <tbody>
            <?php foreach ($rows as $hotel_list) : ?>
              <tr>
                <td><?= $hotel_list["id"] ?></td>
                <td><?= $hotel_list["location"] ?></td>
                <td><?= $hotel_list["name"] ?></td>
                <td class="<?= isset($_GET["search"]) ? '' : 'ellipsis' ?>"><?= $hotel_list["description"] ?></td>
                <td><?= $hotel_list["room_type"] ?></td>
                <td><?= $hotel_list["address"] ?></td>
                <td><?= $hotel_list["phone"] ?></td>

                <td>
                  <a class="btn btn-outline-dark" href="hotel-edit.php?id=<?= $hotel_list["id"] ?>" title="編輯狗狗旅館"><i class="fa-regular fa-pen-to-square"></i></a>
                  <a class="btn btn-outline-warning" href=""><i class="fa-solid fa-trash"></i></a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php else : ?>
        <p>沒有找到任何狗狗旅館。</p>
      <?php endif; ?>
    </div>
  </div>

</body>

</html>