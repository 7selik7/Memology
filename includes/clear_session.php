<?php
include('../includes/connect_db.php');
$room_id = $_POST['room_id'];

$sql = "SELECT `points1`, `points2`, `points3`, `points4` FROM `games` WHERE `room_id` = '$room_id'";
$result = mysqli_query($connection, $sql);
$row = mysqli_fetch_assoc($result);
echo json_encode($row);

session_start();
session_destroy();
?>