<?php
include('connect_db.php'); 

$room_id = $_POST['room_id'];
$sql = "SELECT `image1`, `image2`, `image3`, `image4` FROM `games` WHERE `room_id` = '$room_id'";
$result = $connection->query($sql);

$images = array();
if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $images = array_values($row);
}

echo json_encode($images);
$connection->close();
?>