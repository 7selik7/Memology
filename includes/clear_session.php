<?php
include('../includes/connect_db.php');

$room_id = $_POST['room_id'];
$sql = "DELETE FROM games WHERE room_id='$room_id'";
mysqli_query($connection, $sql);

$sql = "DELETE FROM rooms WHERE room_id='$room_id'";
mysqli_query($connection, $sql);

session_start();
session_destroy();
?>