<?php
require_once("../db_connect.php");

if (!isset($_POST["name"]) || !isset($_POST["description"]) || !isset($_POST["address"]) || !isset($_POST["phone"]) || !isset($_POST["room_type"]) || !isset($_POST["location"])) {
  echo "請依循正常管道進入此頁";
  exit;
}

$name = $_POST["name"];
$description = $_POST["description"];
$room_type_id = $_POST["room_type"]; // 下拉式選單, 此資料表為：room_category
$location_id = $_POST["location"]; // 下拉式選單, 此資料表為：area_category
$images = $_POST["images"]; // 假設多個圖片用數組傳遞, 此資料表為：hotel_img
$address = $_POST["address"];
$phone = $_POST["phone"];

if (empty($name) || empty($description) || empty($address) || empty($phone)) {
  echo "資訊未填寫完整";
  exit;
}

$now_date = date('Y-m-d H:i:s');

// 插入 hotel_list 資料
$sql = "INSERT INTO hotel_list (name, description, address, phone, created_at, room_type_id, location_id, valid)
VALUES ('$name', '$description', '$address', '$phone', '$now_date', '$room_type_id', '$location_id', 1)";

if ($conn->query($sql) === TRUE) {
  $hotel_id = $conn->insert_id;

  // 插入 hotel_img 資料
  foreach ($images as $path) {
    $sql_img = "INSERT INTO hotel_img (hotel_id, path, valid) VALUES ('$hotel_id', '$path', 1)";
    if ($conn->query($sql_img) !== TRUE) {
      echo "錯誤: " . $sql_img . "<br>" . $conn->error;
    }
  }

  echo "新增旅館成功, id為 $hotel_id";
} else {
  echo "錯誤: " . $sql . "<br>" . $conn->error;
}

//儲存圖片嘗試

// 插入 hotel_img 資料並移動圖片
$upload_dir = "../hotels_img/";
foreach ($_FILES["images"]["tmp_name"] as $key => $tmp_name) {
  $file_name = $_FILES["images"]["name"][$key];
  $file_tmp = $_FILES["images"]["tmp_name"][$key];
  $file_path = $upload_dir . basename($file_name);

  if (move_uploaded_file($file_tmp, $file_path)) {
    $sql_img = "INSERT INTO hotel_img (hotel_id, path, valid) VALUES ('$hotel_id', '$file_path', 1)";
    if ($conn->query($sql_img) !== TRUE) {
      echo "錯誤: " . $sql_img . "<br>" . $conn->error;
    }
  } else {
    echo "圖片上傳失敗: " . $file_name . "<br>";
  }
}




header("location: hotel-list.php?");

$conn->close();
