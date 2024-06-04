<?php
require_once("../hotel_db_connect.php");

//跑迴圈進資料表


$startHotelId = 7;
$endHotelId = 23;

for ($hotelId = $startHotelId; $hotelId <= $endHotelId; $hotelId++) {
  for ($i = 1; $i <= 3; $i++) {
    $path = $hotelId . "_" . $i . ".jpg";
    $valid = 1;

    $sql = "INSERT INTO hotel_img (hotel_id, path, valid) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isi", $hotelId, $path, $valid);

    if ($stmt->execute()) {
      echo "Inserted record for hotel_id: $hotelId, path: $path<br>";
    } else {
      echo "Error inserting record: " . $stmt->error . "<br>";
    }
  }
}

$conn->close();
