<?php

include('../includes/connect_db.php');
$room_id = $_POST['room_id'];

$sql = "SELECT `points1`, `points2`, `points3`, `points4` FROM `games` WHERE `room_id` = '$room_id'";
$result = mysqli_query($connection, $sql);
$row = mysqli_fetch_assoc($result);
echo json_encode($row);

$sql = "DELETE FROM games WHERE room_id='$room_id'";
mysqli_query($connection, $sql);

$sql = "DELETE FROM rooms WHERE room_id='$room_id'";
mysqli_query($connection, $sql);

session_start();
session_destroy();
?>