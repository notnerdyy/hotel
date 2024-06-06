<?php
require_once("../db_connect.php");


$sqlAll = "SELECT * FROM hotel_list WHERE valid = 1";
$resultAll = $conn->query($sqlAll);
$allHotelCount = $resultAll->num_rows;

// 如無資訊則抓第一頁
$page = isset($_GET["page"]) ? (int)$_GET["page"] : 1;
// 每頁顯示筆數
$perPage = 4;
$firstItem = ($page - 1) * $perPage;
$pageCount = ceil($allHotelCount / $perPage);

$order = isset($_GET['order']) ? (int)$_GET['order'] : 1;

switch ($order) {
  case 1: // id ASC
    $orderClause = "ORDER BY id ASC";
    break;
  case 2: // id DESC
    $orderClause = "ORDER BY id DESC";
    break;
  default: // 預設排序方式
    $orderClause = "ORDER BY id ASC";
    break;
}

$sql = "SELECT hotel_list.*, room_category.room_type, hotel_img.path 
        FROM hotel_list 
        JOIN room_category ON hotel_list.room_type_id = room_category.id 
        LEFT JOIN (
          SELECT hotel_img.hotel_id, MIN(hotel_img.path) as path
          FROM hotel_img
          WHERE hotel_img.valid = 1
          GROUP BY hotel_img.hotel_id
        ) as hotel_img ON hotel_list.id = hotel_img.hotel_id
        WHERE hotel_list.valid = 1";

// 搜尋欄
if (isset($_GET["search"])) {
  $search = $conn->real_escape_string($_GET["search"]);
  $sql .= " AND (hotel_list.name LIKE '%$search%' OR hotel_list.description LIKE '%$search%' OR room_category.room_type LIKE '%$search%' )";
  // 如果有搜尋條件，不設定 LIMIT
  $sql .= " $orderClause";
} else {
  // 如果沒有搜尋條件，設定 LIMIT
  $sql .= " $orderClause LIMIT $firstItem, $perPage";
}

$result = $conn->query($sql);
$hotelCount = $result->num_rows;
$rows = $result->fetch_all(MYSQLI_ASSOC);

if (isset($_GET["page"])) {
  $hotelCount = $allHotelCount;
}

// 出現搜尋結果時不顯示頁碼
$isSearch = isset($_GET["search"]);

// 分頁數
$sqlTotalItems = "SELECT COUNT(*) as total FROM hotel_list WHERE valid = 1";
$resultTotalItems = $conn->query($sqlTotalItems);
$rowTotalItems = $resultTotalItems->fetch_assoc();
$totalItems = $rowTotalItems['total'];
$itemsPerPage = 4;
$totalPages = ceil($totalItems / $itemsPerPage);

$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$currentPage = max(1, min($totalPages, $currentPage));

// 房型分類
$sqlRoom = "SELECT * FROM room_category ORDER BY id ASC";
$resultCate = $conn->query($sqlRoom);
$cateRows = $resultCate->fetch_all(MYSQLI_ASSOC);

if (isset($_GET["category"])) {
  $category_id = (int)$_GET["category"];
  $sql = "SELECT hotel_list.*, room_category.room_type AS category_room_type
          FROM hotel_list
          JOIN room_category ON hotel_list.room_type_id = room_category.id
          WHERE hotel_list.room_type_id = $category_id
          ORDER BY hotel_list.id ASC";
} else {
  $sql = "SELECT hotel_list.*, room_category.room_type AS category_room_type
          FROM hotel_list
          JOIN room_category ON hotel_list.room_type_id = room_category.id
          ORDER BY hotel_list.id ASC";
}
$category_id = isset($_GET["category"]) ? $_GET["category"] : '';
?>


<?php include("../css.php") ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>旅館列表</title>

</head>
<style>
  .hotel-image {
    height: 70px;
    width: 80px;

  }
</style>

<body>
  <!-- Modal -->
  <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">刪除</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          是否確認刪除旅館資料?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
          <a href="#" id="confirmDeleteBtn" class="btn btn-danger">確認</a>
        </div>
      </div>
    </div>
  </div>
  <div class="container">
    <div class="py-2">



      <div class="d-flex justify-content-between gap-3">
        <!-- 返回箭頭 -->
        <div class="d-flex align-items-center">
          <?php if (isset($_GET["search"])) : ?>
            <a class="btn btn-outline-dark" href="hotel-list.php?page=1"><i class="fa-solid fa-arrow-left"></i></a>
          <?php endif; ?>
        </div>

        <!-- 搜尋欄 -->
        <div class="d-flex">
          <form action="" class="d-flex">
            <div class="input-group">
              <input type="text" class="form-control" placeholder="search..." name="search">
              <button class="btn btn-primary" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
            </div>
          </form>
        </div>
      </div>

      <div class="d-flex justify-content-between py-3">
        <!-- 房型分類 -->
        <ul class="nav nav-pills">
          <li class="nav-item">
            <a class="nav-link <?= !isset($_GET["category"]) ? 'active' : '' ?>" href="hotel-list.php?page=1">全部</a>
          </li>

          <li class="nav-item">
            <a class="nav-link <?= $category_id == '1' ? 'active' : '' ?>" href="hotel-list.php?category=1&search=迷你犬">迷你犬</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= $category_id == '2' ? 'active' : '' ?>" href="hotel-list.php?category=2&search=小型犬">小型犬</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= $category_id == '3' ? 'active' : '' ?>" href="hotel-list.php?category=3&search=中型犬">中型犬</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= $category_id == '4' ? 'active' : '' ?>" href="hotel-list.php?category=4&search=大型犬">大型犬</a>
          </li>

        </ul>



        <!-- 新增 -->
        <a class="btn btn-success" href="add-hotel.php">新增旅館</a>

      </div>



      <div class="pb-2">
        <div class="d-flex justify-content-end">
          <div class="btn-group">
            <a href="?page=<?= $page ?>&order=1" class="btn btn-outline-primary <?php if ($order == 1) echo 'active'; ?>">ID 升序</a>
            <a href="?page=<?= $page ?>&order=2" class="btn btn-outline-primary <?php if ($order == 2) echo 'active'; ?>">ID 降序</a>
          </div>
        </div>
        共 <?= $hotelCount ?> 間
      </div>

      <!-- id ASC DESC -->




      <div class="py-2 mb-3">
        <?php if ($hotelCount > 0) : ?>
          <table class="table table-bordered">
            <thead>
              <tr class="text-nowrap">
                <th>
                  ID
                </th>
                <th>旅館名稱</th>
                <th>介紹</th>
                <th>圖片</th>
                <th>房間類型</th>
                <th>詳細地址</th>
                <th>聯絡電話</th>
                <th>建立時間</th>
                <th></th>

              </tr>
            </thead>
            <tbody>
              <?php foreach ($rows as $hotel_list) : ?>
                <tr>
                  <td><?= $hotel_list["id"] ?></td>
                  <td><?= $hotel_list["name"] ?></td>
                  <td class="<?= isset($_GET["search"]) ? '' : 'ellipsis' ?>"><?= $hotel_list["description"] ?></td>
                  <td>
                    <?php if (!empty($hotel_list["path"])) : ?>
                      <img src="../hotels_img/<?= $hotel_list["path"] ?>" class="hotel-image" alt="<?= $hotel_list["name"] ?>">
                    <?php else : ?>
                      沒有圖片
                    <?php endif; ?>
                  </td>
                  <td><?= $hotel_list["room_type"] ?></td>
                  <td><?= $hotel_list["address"] ?></td>
                  <td><?= $hotel_list["phone"] ?></td>
                  <td><?= $hotel_list["created_at"] ?></td>

                  <td>
                    <a class="btn btn-primary" href="hotel-edit.php?id=<?= $hotel_list["id"] ?>" title="編輯狗狗旅館"><i class="fa-regular fa-pen-to-square"></i></a>
                    <button class="btn btn-danger delete-btn" title="刪除狗狗旅館" data-id="<?= $hotel_list["id"] ?>" data-bs-toggle="modal" data-bs-target="#deleteModal"><i class="fa-solid fa-trash"></i></button>
                  </td>
                </tr>

              <?php endforeach; ?>
            </tbody>
          </table>
          <?php if (!$isSearch) : ?>
            <!-- 分頁鍵 -->
            <nav aria-label="Page navigation example">
              <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                  <li class="page-item <?= ($i == $currentPage) ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                  </li>
                <?php endfor; ?>
              </ul>
            </nav>
          <?php endif; ?>




      </div>
    </div>


  <?php else : ?>
    <p>沒有找到任何狗狗旅館。</p>
  <?php endif; ?>


  <!-- 確認刪除旅館資料 -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const deleteBtns = document.querySelectorAll('.delete-btn');
      const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

      deleteBtns.forEach(button => {
        button.addEventListener('click', function() {
          const hotelId = this.getAttribute('data-id');
          confirmDeleteBtn.setAttribute('href', 'hotel-delete.php?id=' + hotelId);
        });
      });
    });
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>