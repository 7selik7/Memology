<?php
include('../includes/connect_db.php');

// Обработка запроса
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $room_id = $_POST["room_id"];
    $image_id = $_POST['image_id'];

    session_start();
    $user_num = $_SESSION['user_num'];
    $image_num = "image" . $user_num;

    $sql = "UPDATE `games` SET `$image_num` = '$image_id' WHERE `room_id` = '$room_id'";
    mysqli_query($connection, $sql);
}
?>