<?php
require_once("../hotel_db_connect.php");

$sqlAll = "SELECT * FROM hotel_list WHERE valid = 1";
$resultAll = $conn->query($sqlAll);
$allUserCount = $resultAll->num_rows;

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
    <div class="py-2 mb-3">
      <?php if ($allUserCount > 0) : ?>
        <table class="table table_bordered">
          <thead>

            <tr>
              <th>ID</th>
              <th>地區</th>
              <th>旅館名稱</th>
              <th>介紹</th>
              <th>地址</th>
              <th>聯絡電話</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($hotel_list = $resultAll->fetch_assoc()) : ?>

              <td><?= $hotel_list["id"] ?></td>
              <td><?= $hotel_list["category_id"] ?></td>
              <td><?= $hotel_list["name"] ?></td>
              <td><?= $hotel_list["description"] ?></td>
              <td><?= $hotel_list["address"] ?></td>
              <td><?= $hotel_list["phone"] ?></td>

              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>




        </nav>

      <?php else : ?>
        <p>沒有找到任何旅館。</p>
      <?php endif; ?>

    </div>
  </div>

</body>

</html>