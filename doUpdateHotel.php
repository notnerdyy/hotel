<?php
require_once("../db_connect.php");

if (!isset($_POST["name"]) || !isset($_POST["id"]) || !isset($_POST["description"]) || !isset($_POST["address"]) || !isset($_POST["phone"]) || !isset($_POST["room_type"]) || !isset($_POST["location"])) {
  echo "請依循正常管道進入此頁";
  exit;
}



$id = $_POST["id"];
$name = $_POST["name"];
$description = $_POST["description"];
$address = $_POST["address"];
$phone = $_POST["phone"];
$room_type_id = $_POST["room_type"]; // 下拉式選單, 此資料表為：room_category
$location_id = $_POST["location"]; // 下拉式選單, 此資料表為：area_category

$sql = "UPDATE hotel_list SET 
        name='$name', 
        description='$description', 
        address='$address', 
        phone='$phone', 
        location_id='$location_id', 
        room_type_id='$room_type_id'
        WHERE id=$id";


if ($conn->query($sql) === TRUE) {
  echo "更新成功";
} else {
  echo "更新資料錯誤: " . $conn->error;
}

header("location: hotel-edit.php?id=" . $id);
$conn->close();
