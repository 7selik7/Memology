<?php
$file = fopen("../source/themes.txt", "r");
$content = fread($file, filesize("../source/themes.txt"));
$sentences = explode("\r\n\r\n", $content);
fclose($file);
include('../includes/connect_db.php');
$room_id = $_GET['room_id'];
$current_round = $_GET['current_round'];

$sql = "SELECT `theme_id` FROM `games` WHERE `room_id` = '$room_id'";
$result = mysqli_query($connection, $sql);
$row = mysqli_fetch_assoc($result);
$theme_id_array = json_decode($row['theme_id'], true);
echo $sentences[$theme_id_array[$current_round]];
?>