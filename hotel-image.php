<?php
require_once("../hotel_db_connect.php");

// 把照片從資料庫撈出來

$hotelId = 3; // 假設的旅館 ID
$sql = "SELECT images FROM hotel_list WHERE id=$hotelId";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$imageUrls = json_decode($row['images'], true);

if (!empty($imageUrls)) {
    foreach ($imageUrls as $imageUrl) {
        echo '<img src="' . $imageUrl . '" alt="Hotel Image" class="hotel-image">';
    }
} else {
    echo "找不到此旅館照片ID: $hotelId";
}

$conn->close();


?>

<!DOCTYPE html>
<html>

<head>
    <style>
        .hotel-image {
            max-width: 250px;
            max-height: 150px;
            padding: 5px;
        }
    </style>
</head>

<body>



</body>

</html>