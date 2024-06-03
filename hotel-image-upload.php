<?php
require_once("../hotel_db_connect.php");

// 把圖片路徑傳到mysql內


$uploadDir = '../hotels/';
$imageUrls = [];
$hotelId = 4; // 假設的旅館 ID

// 假設每個旅館有 3 張照片，命名規則為 1_1.jpg, 1_2.jpg, 1_3.jpg
for ($i = 1; $i <= 3; $i++) {
  $imageName = $hotelId . "_" . $i . ".jpg";
  $targetFilePath = $uploadDir . $imageName;

  // 確定檔案的絕對路徑，並檢查檔案是否存在
  $absoluteFilePath = realpath($targetFilePath);

  echo "Checking file: " . $targetFilePath . "<br>"; // 調試輸出

  if ($absoluteFilePath && file_exists($absoluteFilePath)) {
    // 將圖片路徑添加到陣列
    $imageUrls[] = $targetFilePath;
  } else {
    echo "File does not exist: " . $targetFilePath . "<br>";
  }
}

if (!empty($imageUrls)) {
  // 將圖片路徑陣列轉換為 JSON 字串
  $imagesJson = json_encode($imageUrls);
  // 更新資料庫中的 images 欄位
  $sql = "UPDATE hotel_list SET images='$imagesJson' WHERE id=$hotelId";
  if ($conn->query($sql) === TRUE) {
    echo "圖片路徑成功上傳!";
  } else {
    echo "圖片上傳有誤: " . $conn->error;
  }
} else {
  echo "找不到圖片";
}




$conn->close();
