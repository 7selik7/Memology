<?php
include('../includes/connect_db.php');
$room_id = $_POST['room_id'];

$sql = "SELECT `points1`, `points2`, `points3`, `points4` FROM `games` WHERE `room_id` = '$room_id'";
$result = mysqli_query($connection, $sql);
$row1 = mysqli_fetch_assoc($result);

$sql = "SELECT `user1`, `user2`, `user3`, `user4` FROM `rooms` WHERE `room_id` = '$room_id'";
$result = mysqli_query($connection, $sql);
$row2 = mysqli_fetch_assoc($result);

$response = array();
foreach ($row1 as $key => $value) {
  $user_key = str_replace('points', 'user', $key);
  $response[$row2[$user_key]] = $value;
}

echo json_encode($response);
?>