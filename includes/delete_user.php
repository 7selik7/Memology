<?php
include('../includes/connect_db.php');
$room_id = $_POST['room_id'];
$user_index = $_POST['index'];
$user = 'user' . $user_index;
if ($user_index != '1'){
    $sql = "UPDATE `rooms` SET `$user` = NULL WHERE `room_id` = '$room_id'";
    mysqli_query($connection, $sql);
}
?>