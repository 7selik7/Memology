<?php
include('../includes/connect_db.php');

// Обработка запроса
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $room_id = $_POST["room_id"];
    $image_id = $_POST['image_id'];

    session_start();
    $images = $_SESSION['image_info'];
    $image_index = array_search($image_id, $images);
    $points_column = 'points' . ($image_index + 1);
    echo $points_column;
    $sql = "UPDATE `games` SET `$points_column` = `$points_column` + 1 WHERE `room_id` = '$room_id'";
    mysqli_query($connection, $sql);
}
?>