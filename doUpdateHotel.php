<?php
require_once("../hotel_db_connect.php");

if (!isset($_POST["name"]) || !isset($_POST["id"])) {
  echo "請依循正常管道進入此頁";
  exit;
}



$id = $_POST["id"];
$name = $_POST["name"];
$description = $_POST["description"];
$address = $_POST["address"];
$phone = $_POST["phone"];

$location_id = $_POST["location"];
$room_type_id = $_POST["room_type"];

$sql = "UPDATE hotel_list SET 
        name='$name', 
        description='$description', 
        address='$address', 
        phone='$phone' 
        location_id='$location', 
        room_type_id='$room_type',
        WHERE id=$id";



if ($conn->query($sql) === TRUE) {
  echo "更新成功";
} else {
  echo "更新資料錯誤: " . $conn->error;
}

header("location: hotel-edit.php?id=" . $id);

$conn->close();
