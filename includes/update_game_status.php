<?php
include('../includes/connect_db.php');

// Обработка запроса
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $game_status = $_POST['game_status'];
  $code = $_POST["code"];
  $sql = "UPDATE `rooms` SET game_status='$game_status' WHERE `room_name` = '$code'";
  mysqli_query($connection, $sql);
}
?>