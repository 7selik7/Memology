<?php
include('../includes/connect_db.php');

// Обработка запроса
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $game_status = $_POST['game_status'];
  $room_id = $_POST["room_id"];
  $sql = "UPDATE `rooms` SET game_status='$game_status' WHERE `room_id` = '$room_id'";
  mysqli_query($connection, $sql);

  $sql = "UPDATE `games` SET start_time = NOW() WHERE `room_id` = '$room_id'";
  mysqli_query($connection, $sql);

  $theme_id = mt_rand(0, 25);
  $sql = "UPDATE `games` SET `theme_id` = $theme_id WHERE `room_id` = '$room_id'";
  mysqli_query($connection, $sql);

}
?>