<?php
include('../includes/connect_db.php');


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $room_id = $_POST["room_id"];
    $image_id = $_POST['image_id'];

    $sql = "SELECT `image1`, `image2`, `image3`, `image4` FROM `games` WHERE `room_id` = '$room_id'";
    $result = mysqli_query($connection, $sql);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        if (in_array($image_id, $row)) {
            echo 'false';
        } else {
            session_start();
            $user_num = $_SESSION['user_num'];
            $image_num = "image" . $user_num;
            $sql = "UPDATE `games` SET `$image_num` = '$image_id' WHERE `room_id` = '$room_id'";
            mysqli_query($connection, $sql);
        }
    }
}
?>