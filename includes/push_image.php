<?php
include('../includes/connect_db.php');

// Обработка запроса
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $room_id = $_POST["room_id"];
    $image_id = $_POST['image_id'];

    session_start();
    $user_num = $_SESSION['user_num'];
    $image_num = "image" . $user_num;

    $user_images = $_SESSION['user_image_indexes'];
    $index = array_search($image_id, $user_images);
    if ($index !== false) {
        array_splice($user_images, $index, 1);
    }
    $_SESSION['user_image_indexes'] = $user_images;

    $all_indexes = range(1, 24);
    $used_indexes = $_SESSION['user_image_indexes'];
    $available_indexes = array_diff($all_indexes, $used_indexes);
    $new_index = array_rand($available_indexes);
    $_SESSION['user_image_indexes'][] = $new_index;

    $sql = "UPDATE `games` SET `$image_num` = '$image_id' WHERE `room_id` = '$room_id'";
    mysqli_query($connection, $sql);
}
?>